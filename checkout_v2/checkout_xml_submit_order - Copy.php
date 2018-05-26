<?php


session_start();

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');

require_once ('../transactional/transactional_pricing.php');
require_once ('../transactional/transactional_merchant.php');
require_once ('../repository_inc/clean_query.php');
require_once ('../repository_inc/phpgmailer/class.phpgmailer.php');
require_once ('../repository_inc/write_log.php');

// Create instances of needed objects
$security = new security();
$credits = new credits();
$users = new users($db);
$diy = new diy();
$mls = new mls();
$users->loadUser();

// I don't like working with this array as $_POST ... feels dirty.
$params = $_POST;

$tourid = -1;
$new_orderid = -1;

// Check if a tour id already exist. If so do not attempt to insert a tour for this order (this would be used for the additional products pages)
$insert_tour = true;
if(isset($params['tourid'])&&!empty($params['tourid'])){
	$tourid = $params['tourid'];
	$insert_tour = false;
}

$params['tourtypeid'] = array(array('id'=>intval($params['tourtypeid']),'qty'=>1));

$items = array();
$params['products'] = explode(";",$params['products']);
foreach ($params['products'] as $prod) {
	$item = explode(",",$prod);
	$index = sizeof($items);
	$items[$index]['id'] = intval($item[0]);
	$items[$index]['qty'] = intval($item[1]);
}
$params['products'] = $items;
unset($items);
$params['mls_str'] = $params['mls'];
$params['mls'] = explode(",",$params['mls']);
$params['mls_provider'] = explode(",",$params['mls_provider']);

$userid = $_SESSION['user_id'];
$brokerid = $_SESSION['broker_id'];
$Cid = 0;

// Save or update CC
if($params['save_cc']=="1"){
	$encryptedCardNumber = $security->encrypt($params['cc_number']);
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = "SELECT crardId FROM usercreditcards WHERE userid='".$_SESSION['user_id']."' AND cardType='".$params['cc_type']."' AND cardMonth='".$params['cc_month']."' AND cardYear='".$params['cc_year']."'"; 
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam(':userid', $userid);
		try {
			$stmt->execute();
		} catch (PDOException $e){
			WriteLog("checkout_xml_submit_order", $e->getMessage());
		}
		$result = $stmt->fetch();
		
		if($stmt->rowCount()>0){
			$query = 'UPDATE usercreditcards SET
				userid = :userid, 
				cardName = :cardName,
				cardAddress = :cardAddress, 
				cardCity = :cardCity, 
				cardState = :cardState, 
				cardZip = :cardZip,
				cardPhone = :cardPhone, 
				cardType = :cardType, 
				cardNumber = :cardNumber, 
				cardMonth = :cardMonth,
				cardYear = :cardYear,
				cardNick = :cardNick,
				cardDefault = :cardDefault 
				WHERE userid = :userid AND cardType = :cardType AND cardNumber = :cardNumber';
			$Cid = $result['crardId'];
		}else{
			$query = 'INSERT INTO usercreditcards SET
				userid = :userid, 
				cardName = :cardName,
				cardAddress = :cardAddress, 
				cardCity = :cardCity, 
				cardState = :cardState, 
				cardZip = :cardZip,
				cardPhone = :cardPhone, 
				cardType = :cardType, 
				cardNumber = :cardNumber, 
				cardMonth = :cardMonth,
				cardYear = :cardYear,
				cardNick = :cardNick,
				cardDefault = :cardDefault'
				;
		}
		if($stmt = $dbh->prepare($query)) {
			$cardNick = 'XXXX-XXXX-XXXX-' . substr($params['cc_number'], -4, 4);
			$cardDefault = 0;
			$stmt->bindParam(':userid', $userid);
			$stmt->bindParam(':cardName', $params['cc_name']);
			$stmt->bindParam(':cardAddress', $params['cc_address']);
			$stmt->bindParam(':cardCity', $params['cc_city']);
			$stmt->bindParam(':cardState', $params['cc_state']);
			$stmt->bindParam(':cardZip', $params['cc_zip']);
			$stmt->bindParam(':cardPhone', $params['cc_phone']);
			$stmt->bindParam(':cardType', $params['cc_type']);
			$stmt->bindParam(':cardNumber', $encryptedCardNumber);
			$stmt->bindParam(':cardMonth', $params['cc_month']);
			$stmt->bindParam(':cardYear', $params['cc_year']);
			$stmt->bindParam(':cardNick', $cardNick);
			$stmt->bindParam(':cardDefault', $cardDefault);	
			try {
				if($stmt->execute()) {
					// UPDATED OR INSTERTED
					if ($Cid == 0)	// $id is already set if UPDATE, so get new card id after INSERT.
						$Cid = intval($dbh->lastInsertId());
				}
			} catch (PDOException $e){
				WriteLog("checkout_xml_submit_order", $e->getMessage());
				echo $e->getMessage() . "\n";
			}
		}
	}
}

$order = order( $params['tourtypeid'], $params['products'], CleanQuery($params['city']), CleanQuery($params['zip']), $brokerid, $userid, CleanQuery($params['coupon']), $params['usePaySold'] );

// This passes the order by reference, not by value .. the results of the transactions will be part of the array.

// Is there something to bill the user?
$transaction_results = array();
$free = false;
if($order['totals']['f_ub_total'] > 0 || $order['totals']['f_mb_total'] > 0 || $order['totals']['f_bb_total'] > 0) {
	$transaction_results = RunTransactions($order, $params, $userid);
} else {
	// There was nothing to bill, so free = true!
	$free = true;
}

$errors = array();
$single_transaction_success = true;
$mb_transaction_success = true;

if(!$free){
	foreach($order['mb_items'] as $mb) {
		if($mb['total']>0){
			if(!$mb['result']['success']) {
				$mb_transaction_success = false;
				$errors[] = 'Your monthly order was declined: ' . $mb['result']['ErrorMessage'];
			}
		}
	}
	$single_transaction_success = false;
	if(isset($order['totals']['result']['success'])){
		if($order['totals']['result']['success']){
			$single_transaction_success = true;
		}else{
			$errors[] = 'Your single order was declined: ' . $order['totals']['result']['ErrorMessage'];
		}
	}else{
		$single_transaction_success = true;
	}
}

if(($single_transaction_success&&$mb_transaction_success)||$free) {
	// Deduct used credits
	//products
	foreach($_SESSION['usedCredits'] as $id => $quantity){
		$credits->deductCredits($id, $quantity);
	}
	// tour type
	foreach($params['tourtypeid'] as $typeIndex => $tourType){
		$credits->deductCredits($_SESSION['tourTypeCredits'][$tourType['id']], 1);
	}
	
	if($insert_tour){
		$tourid = InsertTour($userid, $params, $order);
	}
	if ($tourid > 0) {
		if($insert_tour){
			InsertMLS($tourid, $params['state'], $params['mls'], $params['mls_provider']);
			CreateFolder($tourid);	
		}

		$orderid = InsertOrder($tourid, $order['totals'], $Cid);
		if(intval($orderid) > 0) {
			$new_orderid = $orderid;
			InsertOrderLines ($orderid, $order['lines']);
		}
		
		// Insert the legacy information.
		//$orderid = InsertOrder_Legacy($userid, $tourid, $order['totals'],$params['shipAddress'],$params['shipCity'],$params['shipState'],$params['shipZip']);
		$orderid = InsertOrder_Legacy($userid, $tourid, $order['totals'], $Cid);
		if(intval($orderid) > 0) {
			InsertOrderLines_Legacy ($orderid, $order['lines']);
		}
		
		// Send tour order email
		//SendEmails($userid, $tourid, $params, $order, $params['shipAddress'],$params['shipCity'],$params['shipState'],$params['shipZip']);
		SendEmails($userid, $tourid, $params, $order, $params['usePaySold']);
		
	} else {
		$errors[] = 'There was an error in creating your tour.  Your card may have been charged, so please call for assistance.';	
	}
}

foreach($order['mb_items'] as $mb) {
	if($mb['result']['success']) {
		if ($tourid > 0) {
			InsertReoccuring($tourid, $mb);
		}
	} 
}

// Start the XML output;

header("Content-type: text/xml");

//create the xml document
$xmlDoc = new DOMDocument();
$root = $xmlDoc->appendChild($xmlDoc->createElement("orderinfo"));
if(sizeof($errors) > 0) {
	//create the root element
		
	foreach($errors as $error) {
		$root->appendChild(
			$xmlDoc->createElement("error", $error));
	}
	
	foreach($transaction_results as $t_result) {
		$root->appendChild(
			$xmlDoc->createElement("error", $t_result['ErrorMessage']));
	}
	
} else {
	if($tourid > -1) {
		$root->appendChild(
			$xmlDoc->createElement("tourid", $tourid));
	}
	if($orderid > -1) {
		$root->appendChild(
			$xmlDoc->createElement("orderid", $orderid));
	}
}



// Make the output pretty
$xmlDoc->formatOutput = true;

// Output!
echo $xmlDoc->saveXML();


/*********************************
* FUNCTIONS
*********************************/

//function SendEmails($userid, $tourid, $info, $order, $shipAddress, $shipCity, $shipState, $shipZip) {
function SendEmails($userid, $tourid, $info, $order, $paySold=0) {
	$body = table($order, $userid, $paySold);
	
	$result = array();
//	if(strlen($shipAddress) > 0) 
//		$shippingAddress = "<br /><br />Shipping Address:<br />".$shipAddress."<br />".$shipCity.", ".$shipState." ".$shipZip;
//	else
		$shippingAddress = "";
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = '
		SELECT CONCAT(u.firstName, " ", u.lastName) AS name, u.phone, u.email, b.brokerageName, u.brokerageID
		FROM users u
		LEFT JOIN brokerages b ON u.brokerageID = b.brokerageID
		WHERE u.userID = :userid
		LIMIT 1	
	'; 
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam(':userid', $userid);
		try {
			$stmt->execute();
		} catch (PDOException $e){
			WriteLog("checkout_xml_submit_order", $e->getMessage());
		}
		$result = $stmt->fetch();
	}
	
	if(isset($_REQUEST['DIYMembership'])){
		$user_msg = '
		Dear ' . $result['name'] . ',<br />
		<br />
		Thank you for ordering Spotlight Home Tour\'s DIY Membership.<br />
		With the Do It Yourself (DIY) Membership you can order and create unlimited<br />
		virtual tours online for one low monthly cost.<br /><br />
		If you have any questionsor comments then please feel free to contact us<br />
		at 801-466-4074 or <a href="mailto:support@spotlighthometours.com">support@spotlighthometours.com</a>.<br />
		<br />
		Thanks again,<br />
		<br />
		Spotlight Home Tours<br />
		<br /><br />
		' . $body . '<br />
		';
			
		$admin_msg = '
		The following user has signed up for the DIY membership: ' . $result['name'] . '.<br />
		<b>Agent Brokerage:</b> ' . $result['brokerageName'] . '<br />
		<b>Phone:</b> ' . $result['phone'] . '<br />
		<b>Email:</b> ' . $result['email'] . '<br />
		<br />
		<b>Ordered on:</b> ' . date('Y-m-d') . '<br />
		<br />
		<br />
		' . $body . '<br />
		';

	}else if($info['tourtypeid'][0]['id']=="18"){
		$user_msg = '
		Dear ' . $result['name'] . ',<br />
		<br />
		Thank you for ordering a new DIY tour through Spotlight<br />
		Home Tours. Please use the DIY tour builder to upload and manage the<br />		
		tour photos and create the tour slide shows. You can find the DIY tour<br /> 
		builder under the Active tours on your account home page.<br/>
	
		If you have any questions about our packages please feel free to contact us<br />
		at 801-466-4074 or visit us at www.spotlighthometours.com and view our demos<br />
		and addons pages.<br />
		<br />
		Thanks again,<br />
		<br />
		Spotlight Home Tours<br />
		<br />
		<b>Tour ID:</b> ' . $tourid . '<br />
		<b>Address:</b> ' . $info['address'] . '<br />
		' . $body . '<br />
		';
			
		$admin_msg = '
		A new DIY tour order has been recieved from the user ' . $result['name'] . '.<br />
		<b>Agent Brokerage:</b> ' . $result['brokerageName'] . '<br />
		<b>Phone:</b> ' . $result['phone'] . '<br />
		<b>Email:</b> ' . $result['email'] . '<br />
		<br />
		<b>Ordered on:</b> ' . date('Y-m-d') . '<br />
		<b>Tour ID:</b> ' . $tourid . '<br />
		<b>Tour Title:</b> ' . $info['title'] . '<br />
		<b>Address:</b> ' . $info['address'] . '<br />
		<b>City:</b> ' . $info['city'] . '<br />
		<b>State:</b> ' . $info['state'] . '<br />
		<b>Zip Code:</b> ' .$info['zip']  . '<br />
		<b>List Price:</b> ' . $info['price'] . '<br />
		<b>Total Square Feet:</b> ' . $info['sqft'] . '<br />
		<b>Bedrooms:</b> ' . $info['beds'] . '<br />
		<b>Bathrooms:</b> ' . $info['baths'] . '<br />
		<b>MLS:</b> ' . $info['mls_str'] . '<br />
		<br />
		<b>Description:</b> ' . $info['desc'] . '<br />
		<br />
		<b>Instructions:</b> ' . $info['add'] . '<br />
		<br />
		' . $body . '<br />
		';
	}else{
		$user_msg = '
		Dear ' . $result['name'] . ',<br />
		<br />
		Thank you for ordering the best home tour available today through Spotlight<br />
		Home Tours. Please anticipate a call from our tour coordinator within 24<br />
		hours to arrange access to your listing. If you have any questions or have<br />
		not heard from our representative within 24 hours, please contact us<br />
		immediately at customerservice@spotlighthometours.com so we can make prompt<br />
		arrangements for your tour to be completed.<br />
	
		If you have any questions about our packages please feel free to contact us<br />
		at 801-466-4074 or visit us at www.spotlighthometours.com and view our demos<br />
		and addons pages.<br />
		<br />
		Thanks again,<br />
		<br />
		Spotlight Home Tours<br />
		<br />
		<b>Tour ID:</b> ' . $tourid . '<br />
		<b>Address:</b> ' . $info['address'] . '<br />
		' . $body . '<br />
		'.$shippingAddress;
			
		$admin_msg = '
		A new tour order has been recieved from the user ' . $result['name'] . '.<br />
		<b>Agent Brokerage:</b> ' . $result['brokerageName'] . '<br />
		<b>Phone:</b> ' . $result['phone'] . '<br />
		<b>Email:</b> ' . $result['email'] . '<br />
		<br />
		<b>Ordered on:</b> ' . date('Y-m-d') . '<br />
		<b>Tour ID:</b> ' . $tourid . '<br />
		<b>Tour Title:</b> ' . $info['title'] . '<br />
		<b>Address:</b> ' . $info['address'] . '<br />
		<b>City:</b> ' . $info['city'] . '<br />
		<b>State:</b> ' . $info['state'] . '<br />
		<b>Zip Code:</b> ' .$info['zip']  . '<br />
		<b>List Price:</b> ' . $info['price'] . '<br />
		<b>Total Square Feet:</b> ' . $info['sqft'] . '<br />
		<b>Bedrooms:</b> ' . $info['beds'] . '<br />
		<b>Bathrooms:</b> ' . $info['baths'] . '<br />
		<b>MLS:</b> ' . $info['mls_str'] . '<br />
		<br />
		<b>Description:</b> ' . $info['desc'] . '<br />
		<br />
		<b>Instructions:</b> ' . $info['add'] . '<br />
		<br />
		' . $body . '<br />
		'.$shippingAddress;
	}
	
	$mail = new PHPGMailer();
	$mail->Username = 'info@spotlighthometours.com'; 
	$mail->Password = 'bailey22';
	$mail->From = 'info@spotlighthometours.com'; 
	$mail->FromName = 'Spotlight';
	
	if(isset($_REQUEST['DIYMembership'])){
		$mail->Subject = 'DIY Membership Order Confirmation';
	}else{
		$mail->Subject = 'Your Spotlight Order Confirmation';
	}
	if ($_SESSION['debug'] == true) {
		$mail->AddAddress('jacob@spotlighthometours.com');
	} else {
		$mail->AddAddress('neworders@spotlighthometours.com');
	}
	$mail->Body = $admin_msg;
	$mail->IsHTML(true); // send as HTML
	if(!$mail->Send()) {
		WriteLog("checkout_xml_submit_order", "Mailer Error: " . $mail->ErrorInfo);
	}
	
		// This removes the coupon row below subtotal and above total (the agent does not get to see this in the email)
	
	if (strpos($user_msg, $order['totals']['coupon']) > 0 && strlen($order['totals']['coupon']) > 0) {
		$user_msg = str_replace($order['totals']['coupon'], "", $user_msg);
	}
	
	$mail = new PHPGMailer();
	$mail->Username = 'info@spotlighthometours.com'; 
	$mail->Password = 'bailey22';
	$mail->From = 'info@spotlighthometours.com'; 
	$mail->FromName = 'Spotlight';
	if(isset($_REQUEST['DIYMembership'])){
		$mail->Subject = 'DIY Membership Order Confirmation';
	}else{
		$mail->Subject = 'Your Spotlight Order Confirmation';
	}
	$mail->AddAddress($result['email']);
		// Get a list of all the other emails that need an order notification
	$query = "SELECT email FROM usernotifications WHERE userid = :userid AND length(email)> 0 AND action= 'ordered'  ORDER BY num";	
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam(':userid', $userid);
		try {
			$stmt->execute();
		} catch (PDOException $e){
			WriteLog("checkout_xml_submit_order", $e->getMessage());
		}
		while ($result2 = $stmt->fetch()) {
			if ($result['email'] != $result2['email']) {
				$mail->AddAddress($result2['email']);
			}
		}
	}
		// Get a list of the brokerage emails that need an order notification
	$query = "SELECT email FROM teamsnotifications WHERE brokerageid = :brokerageid AND length(email)> 0 AND action= 'ordered'  ORDER BY num";	
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam(':brokerageid', $result['brokerageID']);
		try {
			$stmt->execute();
		} catch (PDOException $e){
			WriteLog("checkout_xml_submit_order", $e->getMessage());
		}
		while ($result3 = $stmt->fetch()) {
			if ($result['email'] != $result3['email']) {
				$mail->AddAddress($result3['email']);
			}
		}
	}
	
	$mail->Body = $user_msg;
	$mail->IsHTML(true); // send as HTML
	if(!$mail->Send()) {
		WriteLog("checkout_xml_submit_order", "Mailer Error: " . $mail->ErrorInfo);
	}
}

function CreateFolder($tourId) {
	// Create an image folder for the tour
	@mkdir($_SERVER['DOCUMENT_ROOT'] . "/users/images/tours/" . $tourId, 0777, true);	
}

function InsertReoccuring($tourid, $rec) {
	
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$query = '
		INSERT INTO order_reoccuring
		(tourID, transID, name, total, day_offset)
		VALUES
		(:tourID, :transID, :name, :total, :day_offset)
	';
	
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam('tourID', $tourid);
		$stmt->bindParam(':transID', $rec['recordid']);
		$stmt->bindParam(':name', $rec['name']);
		$stmt->bindParam(':total', $rec['total']);
		$stmt->bindParam(':day_offset', $rec['day_offset']);
		try {
			$stmt->execute();
		} catch (PDOException $e){
			WriteLog("checkout_xml_submit_order", $e->getMessage());
		}
	}
}

function InsertMLS($tourid, $state, $mls_id, $mls_provider) {
	global $mls;
	$numberOfMLS = count($mls_id);
	for($i=0; $i<$numberOfMLS; $i++) {
		$mls->saveTourID($tourid, $mls_id[$i], $mls_provider[$i]);
	}
}

function convert_smart_quotes($string) { 
    $search = array(chr(145), 
                    chr(146), 
                    chr(147), 
                    chr(148), 
                    chr(151)); 
 
    $replace = array("'", 
                     "'", 
                     '"', 
                     '"', 
                     '-'); 
 
    return str_replace($search, $replace, $string); 
} 

function InsertTour($userid, $info, $order) {
	global $diy;
	global $users;
	global $db;
	$id = -1;
	$timestamp = date('Y-m-d H:i:s');
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$query = '
		INSERT INTO tours 
		(userID, tourTypeID, title, address, unitNumber, city, state, zipCode, listPrice, sqFootage, bedrooms, bathrooms, description, additionalInstructions, createdOn, modifiedOn, hideprice, hidesqfoot, hidebeds, hidebaths, hideAddress, codestr, codeval, couserID, brokerbilled, suspended, TourWindowType )
		VALUES
		(:userID, :tourTypeID, :title, :address, :unitNumber, :city, :state, :zipCode, :listPrice, :sqFootage, :bedrooms, :bathrooms, :description, :additionalInstructions, :createdOn, :modifiedOn, :hideprice, :hidesqfoot, :hidebeds, :hidebaths, :hideAddress, :codestr, :codeval, :couserID, :brokerbilled, :suspended, :tourWindowType)
	';
	
	$info['desc'] = convert_smart_quotes($info['desc']);
	$info['add'] = convert_smart_quotes($info['add']);
	
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam(':userID', $userid);
		$stmt->bindParam(':tourTypeID', $info['tourtypeid'][0]['id']);
		$stmt->bindParam(':title', $info['title']);
		$stmt->bindParam(':address', $info['address']);
		$stmt->bindParam(':unitNumber', $info['unitNumber']);
		$stmt->bindParam(':city', $info['city']);
		$stmt->bindParam(':state', $info['state']);
		$stmt->bindParam(':zipCode', $info['zip']);
		$stmt->bindParam(':listPrice', $info['price']);
		$stmt->bindParam(':sqFootage', $info['sqft']);
		$stmt->bindParam(':bedrooms', $info['beds']);
		$stmt->bindParam(':bathrooms', $info['baths']);
		//$stmt->bindParam(':mls', $info['mls_str']);
		$stmt->bindParam(':description', $info['desc']);
		$stmt->bindParam(':additionalInstructions', $info['add']);
		$stmt->bindParam(':createdOn', $timestamp);
		$stmt->bindParam(':modifiedOn', $timestamp);
		$stmt->bindParam(':hideprice', $info['hide_price']);
		$stmt->bindParam(':hidesqfoot', $info['hide_sqft']);
		$stmt->bindParam(':hidebeds', $info['hide_beds']);
		$stmt->bindParam(':hidebaths', $info['hide_baths']);
		$stmt->bindParam(':hideAddress', $info['hide_address']);
		$stmt->bindParam(':codestr', $order['totals']['coupon']);
		$stmt->bindParam(':codeval', $order['totals']['coupon_dollar']);
		$stmt->bindParam(':couserID', intval($info['coagent']));
		$stmt->bindParam(':brokerbilled', $order['totals']['f_bb_total']);
		
		// If purchasing DIY membership suspend tour
		if(isset($_REQUEST['DIYMembership'])){
			$stmt->bindValue(':suspended', 1, PDO::PARAM_INT);
		}else{
			$stmt->bindValue(':suspended', NULL, PDO::PARAM_INT);
		}
		$tourWindowType = 'Both';
		$stmt->bindParam(':tourWindowType', $tourWindowType);
		
		try {
			if($stmt->execute()) {
				$id = intval($dbh->lastInsertId());
			}
		} catch (PDOException $e){
			WriteLog("checkout_xml_submit_order", $e->getMessage().' UserID: '.$userid.'||');
			echo "ttid: " . $info['tourtypeid'] . "\n";
			echo $e->getMessage() . "\n";
		}
	}
        
   	// If they paid to be an express user, update their account.
	foreach($order['lines'] as $line => $item){
		// Find DIY tour and make sure it cost them (no free monthly DIY memberships!)
		if($item['itemID']==18 && intval($item['mb_total'])>0){
			$diy->addMember($_SESSION['user_id']);
			$diy->activateTours($_SESSION['user_id']);
		}
	}
	
	return $id;

}

function InsertOrder($tourid, $order, $Cid) {
	global $transID;
	$id = -1;
	
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$query = '
		INSERT INTO order_total
		(transID , tourID, bb_sub , bb_tax , bb_total , ub_sub , ub_tax , ub_total , mb_sub , mb_tax , mb_total , coupon , coupon_dollar , coupon_percent , f_mb_total , f_ub_total , f_bb_total, bb_paySold_tax, bb_paySold_sub, bb_paySold_total, ub_paySold_tax, ub_paySold_sub, ub_paySold_total, f_bb_paySold_total, f_ub_paySold_total, crardId)
		VALUES
		(:transID, :tourID, :bb_sub , :bb_tax , :bb_total , :ub_sub , :ub_tax , :ub_total , :mb_sub , :mb_tax , :mb_total , :coupon , :coupon_dollar , :coupon_percent , :f_mb_total , :f_ub_total , :f_bb_total, :bb_paySoldTax, :bb_paySoldSub, :bb_paySoldTotal, :ub_paySoldTax, :ub_paySoldSub, :ub_paySoldTotal, :f_bb_paySoldTotal, :f_ub_paySoldTotal, :crardId)
	'; //, coupon_remaining) :coupon_remaining)
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam(':transID', $order['recordid']);
		$stmt->bindParam(':tourID', $tourid);
		$stmt->bindParam(':bb_sub', $order['bb_sub']);
		$stmt->bindParam(':bb_sub', $order['bb_sub']);
		$stmt->bindParam(':bb_tax', $order['bb_tax']);
		$stmt->bindParam(':bb_total', $order['bb_total']);
		$stmt->bindParam(':ub_sub', $order['ub_sub']);
		$stmt->bindParam(':ub_tax', $order['ub_tax']);
		$stmt->bindParam(':ub_total', $order['ub_total']);
		$stmt->bindParam(':mb_sub', $order['mb_sub']);
		$stmt->bindParam(':mb_tax', $order['mb_tax']);
		$stmt->bindParam(':mb_total', $order['mb_total']);
		$stmt->bindParam(':coupon', $order['coupon']);
		$stmt->bindParam(':coupon_dollar', $order['coupon_dollar']);
		$stmt->bindParam(':coupon_percent', $order['coupon_percent']);
		$stmt->bindParam(':f_mb_total', $order['f_mb_total']);
		$stmt->bindParam(':f_ub_total', $order['f_ub_total']);
		$stmt->bindParam(':f_bb_total', $order['f_bb_total']);
		
		$stmt->bindParam(':bb_paySoldTax', $line['bb_paySoldTax']);
		$stmt->bindParam(':bb_paySoldSub', $line['bb_paySoldSub']);
		$stmt->bindParam(':bb_paySoldTotal', $line['bb_paySoldTotal']);
		$stmt->bindParam(':ub_paySoldTax', $line['ub_paySoldTax']);
		$stmt->bindParam(':ub_paySoldSub', $line['ub_paySoldSub']);
		$stmt->bindParam(':ub_paySoldTotal', $line['ub_paySoldTotal']);
		$stmt->bindParam(':f_bb_paySoldTotal', $order['f_bb_paySoldTotal']);
		$stmt->bindParam(':f_ub_paySoldTotal', $order['f_ub_paySoldTotal']);
		$stmt->bindParam(':crardId', $Cid);

		//$stmt->bindParam(':coupon_remaining', intval($order['coupon_remaining']));
		
		try {
			if($stmt->execute()) {
				$id = intval($dbh->lastInsertId());
				// Update saved transaction with orderID
				if(!empty($transID)){
					$query = 'UPDATE order_transactions SET
					OrderId = :OrderId 
					WHERE RecordID = :RecordID';
					if($stmt = $dbh->prepare($query)) {
						$stmt->bindParam(':OrderId', $id);
						$stmt->bindParam(':RecordID', $transID);
						$stmt->execute();
					}
				}
			}
		} catch (PDOException $e){
			WriteLog("checkout_xml_submit_order", $e->getMessage());
			echo $e->getMessage() . "\n";
		}
	}
	return $id;
}

function InsertOrderLines ($orderId, $lines) {
	
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	foreach($lines as $line) {
		$query = '
			INSERT INTO order_lines
			(orderID, itemType, itemID, name, price_cat, price, coupon, coupon_limit, coupon_remaining, coupon_dollar, coupon_percent, coupon_day, taxable, monthly, bb_dollar, bb_percent, qty, mod_price, bb_item, bb_tax, bb_sub, bb_total, ub_item, ub_tax, ub_sub, ub_total, mb_item, mb_tax, mb_sub, mb_total, bb_paySold, bb_paySold_tax, bb_paySold_sub, bb_paySold_total, ub_paySold, ub_paySold_tax, ub_paySold_sub, ub_paySold_total)
			VALUES	
			(:orderID, :itemType, :itemID, :name, :price_cat, :price, :coupon, :coupon_limit, :coupon_remaining, :coupon_dollar, :coupon_percent, :coupon_day, :taxable, :monthly, :bb_dollar, :bb_percent, :qty, :mod_price, :bb_item, :bb_tax, :bb_sub, :bb_total, :ub_item, :ub_tax, :ub_sub, :ub_total, :mb_item, :mb_tax, :mb_sub, :mb_total, :bb_paySold, :bb_paySoldTax, :bb_paySoldSub, :bb_paySoldTotal, :ub_paySold, :ub_paySoldTax, :ub_paySoldSub, :ub_paySoldTotal)
	
		';
		if($stmt = $dbh->prepare($query)) {
			
			$stmt->bindParam(':orderID', $orderId);
			$stmt->bindParam(':itemType', $line['itemType']);
			$stmt->bindParam(':itemID', $line['itemID']);
			$stmt->bindParam(':name', $line['name']);
			$stmt->bindParam(':price_cat', $line['price_cat']);
			$stmt->bindParam(':price', $line['price']);
			$stmt->bindParam(':coupon', $line['coupon']);
			$stmt->bindParam(':coupon_limit', $line['coupon_limit']);
			$stmt->bindParam(':coupon_remaining', intval($line['coupon_remaining']));
			$stmt->bindParam(':coupon_dollar', $line['coupon_dollar']);
			$stmt->bindParam(':coupon_percent', $line['coupon_percent']);
			$stmt->bindParam(':coupon_day', $line['coupon_day']);
			$stmt->bindParam(':taxable', $line['taxable']);
			$stmt->bindParam(':monthly', $line['monthly']);
			$stmt->bindParam(':bb_dollar', $line['bb_dollar']);
			$stmt->bindParam(':bb_percent', $line['bb_percent']);
			$stmt->bindParam(':qty', $line['qty']);
			$stmt->bindParam(':mod_price', $line['mod_price']);
			$stmt->bindParam(':bb_item', $line['bb_item']);
			$stmt->bindParam(':bb_tax', $line['bb_tax']);
			$stmt->bindParam(':bb_sub', $line['bb_sub']);
			$stmt->bindParam(':bb_total', $line['bb_total']);
			$stmt->bindParam(':ub_item', $line['ub_item']);
			$stmt->bindParam(':ub_tax', $line['ub_tax']);
			$stmt->bindParam(':ub_sub', $line['ub_sub']);
			$stmt->bindParam(':ub_total', $line['ub_total']);
			$stmt->bindParam(':mb_item', $line['mb_item']);
			$stmt->bindParam(':mb_tax', $line['mb_tax']);
			$stmt->bindParam(':mb_sub', $line['mb_sub']);
			$stmt->bindParam(':mb_total', $line['mb_total']);
			
			$stmt->bindParam(':bb_paySold', $line['bb_paySold']);
			$stmt->bindParam(':bb_paySoldTax', $line['bb_paySoldTax']);
			$stmt->bindParam(':bb_paySoldSub', $line['bb_paySoldSub']);
			$stmt->bindParam(':bb_paySoldTotal', $line['bb_paySoldTotal']);
			$stmt->bindParam(':ub_paySold', $line['ub_paySold']);
			$stmt->bindParam(':ub_paySoldTax', $line['ub_paySoldTax']);
			$stmt->bindParam(':ub_paySoldSub', $line['ub_paySoldSub']);
			$stmt->bindParam(':ub_paySoldTotal', $line['ub_paySoldTotal']);
			
			try {
				$stmt->execute();
			} catch (PDOException $e){
				WriteLog("checkout_xml_submit_order", $e->getMessage());
			}
		}
	}
}

function InsertOrder_Legacy($userid, $tourid, $order, $Cid) {
	$id = -1;
	$timestamp = date('Y-m-d H:i:s');
	
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$query = 'INSERT INTO orders SET
		userID = :userid, 
		transactionId = :transactionId,
		tourid = :tourid, 
		subtotal = :subtotal, 
		salestax = :tax, 
		total = :total,
		broker_total = :btotal, 
		broker_paySold_total = :bpstotal,
		agent_paySold_total = :apstotal,
		coupon = :coupon, 
		coupon_total = :ctotal, 
		createdOn = :timestamp, 
		crardId = :crardId';
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam(':userid', $userid);
		$stmt->bindParam(':transactionId', $order['recordid']);
		$stmt->bindParam(':tourid', $tourid);
		$stmt->bindParam(':subtotal', $order['ub_sub']);
		$stmt->bindParam(':tax', $order['ub_tax']);
		$stmt->bindParam(':total', $order['f_ub_total']);
		$stmt->bindParam(':btotal', $order['f_bb_total']);
		$stmt->bindParam(':bpstotal', $order['f_bb_paySoldTotal']);
		$stmt->bindParam(':apstotal', $order['f_ub_paySoldTotal']);
		$stmt->bindParam(':coupon', $order['coupon']);
		$stmt->bindParam(':ctotal', $order['coupon_dollar']);
		$stmt->bindParam(':timestamp', $timestamp);
		$stmt->bindParam(':crardId', $Cid);
		
		try {
			if($stmt->execute()) {
				$id = intval($dbh->lastInsertId());
			}
		} catch (PDOException $e){
			WriteLog("checkout_xml_submit_order", $e->getMessage());
			echo $e->getMessage() . "\n";
		}
	}
	return $id;
}

function InsertOrderLines_Legacy ($orderId, $lines) {
	
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	foreach($lines as $line) {
		$query = '
			INSERT INTO orderdetails
			(orderID, type, productID, quantity, unitPrice, broker_price, broker_paySold_price, agent_paySold_price)
			VALUES	
			(:orderID, :itemType, :itemID, :qty, :ub_item, :bb_item, :bbps_price, :ubps_price)
	
		';
		if($stmt = $dbh->prepare($query)) {
			
			$stmt->bindParam(':orderID', $orderId);
			$stmt->bindParam(':itemType', $line['itemType']);
			$stmt->bindParam(':itemID', $line['itemID']);
			$stmt->bindParam(':qty', $line['qty']);
			$stmt->bindParam(':ub_item', $line['ub_item']);
			$stmt->bindParam(':bb_item', $line['bb_item']);
			$stmt->bindParam(':bbps_price', $line['bb_paySoldTotal']);
			$stmt->bindParam(':ubps_price', $line['ub_paySoldTotal']);
			
			try {
				$stmt->execute();
			} catch (PDOException $e){
				WriteLog("checkout_xml_submit_order", $e->getMessage());
			}
		}
	}
}

?>