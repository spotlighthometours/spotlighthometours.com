<?php
require_once('../repository_inc/classes/inc.global.php');
include ('../repository_inc/data.php');
require_once ('../repository_inc/write_log.php');
require_once ('../repository_inc/phpgmailer/class.phpgmailer.php');
require_once ('../transactional/transactional_merchant.php');
require_once ('../repository_inc/clean_query.php');

global $dbh;
$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//All $_POST info is being provided from the membership_checkout.js file.
switch( $_GET['view'] ) //check the $_GET for what to do. NEED TO refactor some day
{
    case 'populateOrder':
        return populateOrder();
    break;

    case 'submitOrder':

       //need to add in some validation that the session exists
        $userid = $_SESSION['user_id'];

        $order = array();

        $order['totals']['f_ub_total'] = $_POST['f_ub_total'];
        $order['mb_items']['total'] = $_POST['total'];
        $order['mb_items']['day_offset'] = $_POST['day_offset'];
        $order['mb_items']['name'] = $_POST['membershiptype'];



        //store and/or update cc info
        if ( $_POST['save_cc'] == 1)
            storeUpdateCC(new Security(),$userid, $_POST);

        //echo out the response for membership_chckout.js
        echo SubmitOrder($order, $_POST, $_SESSION['user_id'] );
    break;

    case 'applyCoupon':
        $coupon         = $_POST['coupon'];
        $membership     = $_POST['type'];
        $order_price    = $_POST['order_price'];
        $brokerid       = $_SESSION['broker_id']; //should be set from the login

        return applyCoupon($coupon, $order_price, $membership );
    break;


}

function applyCoupon($coupon, $order_price, $order_type )
{
    global $dbh;//need access to the dbh defined above

    $totals['coupon'] = '';
    $totals['coupon_dollar'] = 0;
    $totals['coupon_percent'] = 0;
    $totals['f_mb_total'] = $order_price;
    $totals['f_ub_total'] = $order_price;
    //$totals['f_bb_total'] = $order_price;

    $couponQ = '
			SELECT
			pc.codestr AS coupon, pc.limits AS c_limit,
			pcv.dayValue AS day_offset,
			pcv.dollarValue AS c_dollar, pcv.percentValue AS c_percent,
			(pc.limits - COUNT(o.orderID)) as c_remaining
			FROM promocodes pc
			LEFT JOIN promocode_values pcv ON pc.codestr = pcv.codestr
			LEFT JOIN orders o ON o.coupon = pc.codestr
			WHERE pc.active = 1
			AND pcv.type = "'. $order_type .'"
			AND pc.codestr = "' . $coupon . '"
			GROUP BY pc.codestr, pcv.type
			LIMIT 1
		';

    $result = array();
    $stmt = $dbh->prepare($couponQ);
    $stmt->execute();
    $result = $stmt->fetch();


    if( isset($result['c_remaining']) ) //&& (intval($itemList[$i]['coupon_limit']) == 0 || intval($itemList[$i]['coupon_remaining']) > 0))
    {
        $totals['day_offset'] = $result['day_offset'];
        $totals['coupon'] = $result['coupon'];
        $totals['coupon_remaining'] = intval($result['c_remaining']);
        $totals['coupon_dollar'] = '<br/>-$' . number_format($result['c_dollar'], 2, '.', ''); //used to format the display in the order table
        $totals['coupon_percent'] = '<br/>-' .  round($result['c_percent']*100, 0) . '%'; //used to format the display in the order table


        if($result['c_dollar'] > 0)
        {
            // Apply dollar discount to the order totals except monthly, as those should have already been handled.
            $discounts = $result['c_dollar'];
            $total = $totals['f_ub_total'];

            //$brokertotal = $totals['bb_total'];

            if ($total > $discounts)
            {
                $total -= $discounts;
            }
            else
            {
                //don't want a negative charge just set the total to 0
                $total = 0;
            }

            $totals['f_ub_total'] = '$'. number_format($total,2,'.','');
            $totals['f_mb_total'] = '$'. number_format($total,2,'.','');

        }
        elseif ($result['c_percent'] > 0)
        {
            $totals['f_ub_total'] = round((1 - $totals['coupon_percent']) * $totals['f_ub_total'],2);
            $totals['f_mb_total'] = round((1 - $totals['coupon_percent']) * $totals['f_mb_total'],2);
        }

        if($totals['f_ub_total'] < 0)
            $totals['f_ub_total'] = 0;

        if($totals['f_mb_total'] < 0)
            $totals['f_mb_total'] = 0;


        // if($totals['f_bb_total'] < 0)
        //  $totals['f_bb_total'] = 0;

    }


    header('Content-Type: application/json');
    echo json_encode($totals);
}

function storeUpdateCC(Security $security, $userid, $params)
{
    $encryptedCardNumber = $security->encrypt($params['cc_number']);
    global $dbh;

    $query = "SELECT crardId FROM usercreditcards WHERE userid='".$_SESSION['user_id'].
                "' AND cardType='".$params['cc_type']."' AND cardMonth='".$params['cc_month']."' AND cardYear='".$params['cc_year']."'";
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
                }
            } catch (PDOException $e){
                WriteLog("checkout_xml_submit_order", $e->getMessage());
                echo $e->getMessage() . "\n";
            }
        }
    }
}


function ValidateOrder($postParams, $userId)
{

    $data = array();
    if(strlen($userId) > 0) {
        $data['formInfo']['userId'] = $userId;
    } else {
        $data['error'][] = 'Userid was not supplied.';
    }

    // Optional
    $data['formInfo']['invoiceNum'] = CleanQuery($postParams['invoice']);

    if(strlen($postParams['cc_name']) > 0) {
        $data['formInfo']['nameOnCard'] = CleanQuery($postParams['cc_name']);
    } else {
        $data['error'][] = 'Credit card name was not supplied.';
    }

    if(strlen($postParams['cc_type']) > 0) {
        $data['formInfo']['cardType'] =  CleanQuery($postParams['cc_type']);
    } else {
        $data['error'][] = 'Credit card type was not supplied.';
    }

    if(strlen($postParams['cc_number']) > 0) {
        $data['formInfo']['cardNumber'] =  CleanQuery($postParams['cc_number']);
    } else {
        $data['error'][] = 'Credit card number was not supplied.';
    }

    if(strlen($postParams['cc_month']) > 0) {
        $data['formInfo']['cardMonth'] = CleanQuery($postParams['cc_month']);
        $data['formInfo']['cardMonth'] = str_pad($data['formInfo']['cardMonth'], 2, "0", STR_PAD_LEFT);
    } else {
        $data['error'][] = 'Credit card month was not supplied.';
    }

    if(strlen($postParams['cc_year']) > 0) {
        $data['formInfo']['cardYear'] =  CleanQuery($postParams['cc_year']);
        if (strlen($data['formInfo']['cardYear']) > 2) {
            $data['formInfo']['cardYear'] = substr($data['formInfo']['cardYear'], -2);
        }
        $data['formInfo']['cardYear'] = str_pad($data['formInfo']['cardYear'], 2, "0", STR_PAD_LEFT);
    } else {
        $data['error'][] = 'Credit card year was not supplied.';
    }

    if(strlen($postParams['cc_address']) > 0) {
        $data['formInfo']['cardAddress'] =  CleanQuery($postParams['cc_address']);
    } else {
        $data['error'][] = 'Credit card address was not supplied.';
    }

    if(strlen($postParams['cc_city']) > 0) {
        $data['formInfo']['cardCity'] =  CleanQuery($postParams['cc_city']);
    } else {
        $data['error'][] = 'Credit card city was not supplied.';
    }

    if(strlen($postParams['cc_state']) > 0) {
        $data['formInfo']['cardState'] =  CleanQuery($postParams['cc_state']);
    } else {
        $data['error'][] = 'Credit card state was not supplied.';
    }

    if(strlen($postParams['cc_zip']) > 0) {
        $data['formInfo']['cardZip'] =  CleanQuery($postParams['cc_zip']);
    } else {
        $data['error'][] = 'Credit card zip was not supplied.';
    }


    return $data;
}


function SubmitOrder(array $order, array $postParams, $userId)
{

    $transInfo = array();
    $returnData = array();

    if ( $order['totals']['f_ub_total'] > 0 )
    {


        if(strlen($userId) > 0) {
            $transInfo['formInfo']['userId'] = $userId;
        } else {
            $returnData['error'][] = 'Userid was not supplied.';
        }

        // Optional
        $transInfo['formInfo']['invoiceNum'] = CleanQuery($postParams['invoice']);

        if(strlen($postParams['cc_name']) > 0) {
            $transInfo['formInfo']['nameOnCard'] = CleanQuery($postParams['cc_name']);
        } else {
            $returnData['error'][] = 'Credit card name was not supplied.';
        }

        if(strlen($postParams['cc_type']) > 0) {
            $transInfo['formInfo']['cardType'] =  CleanQuery($postParams['cc_type']);
        } else {
            $returnData['error'][] = 'Credit card type was not supplied.';
        }

        if(strlen($postParams['cc_number']) > 0) {
            $transInfo['formInfo']['cardNumber'] =  CleanQuery($postParams['cc_number']);
        } else {
            $returnData['error'][] = 'Credit card number was not supplied.';
        }

        if(strlen($postParams['cc_month']) > 0) {
            $transInfo['formInfo']['cardMonth'] = CleanQuery($postParams['cc_month']);
            $transInfo['formInfo']['cardMonth'] = str_pad($transInfo['formInfo']['cardMonth'], 2, "0", STR_PAD_LEFT);
        } else {
            $returnData['error'][] = 'Credit card month was not supplied.';
        }

        if(strlen($postParams['cc_year']) > 0) {
            $transInfo['formInfo']['cardYear'] =  CleanQuery($postParams['cc_year']);
            if (strlen($transInfo['formInfo']['cardYear']) > 2) {
                $transInfo['formInfo']['cardYear'] = substr($transInfo['formInfo']['cardYear'], -2);
            }
            $transInfo['formInfo']['cardYear'] = str_pad($transInfo['formInfo']['cardYear'], 2, "0", STR_PAD_LEFT);
        } else {
            $returnData['error'][] = 'Credit card year was not supplied.';
        }

        if(strlen($postParams['cc_address']) > 0) {
            $transInfo['formInfo']['cardAddress'] =  CleanQuery($postParams['cc_address']);
        } else {
            $returnData['error'][] = 'Credit card address was not supplied.';
        }

        if(strlen($postParams['cc_city']) > 0) {
            $transInfo['formInfo']['cardCity'] =  CleanQuery($postParams['cc_city']);
        } else {
            $returnData['error'][] = 'Credit card city was not supplied.';
        }

        if(strlen($postParams['cc_state']) > 0) {
            $transInfo['formInfo']['cardState'] =  CleanQuery($postParams['cc_state']);
        } else {
            $returnData['error'][] = 'Credit card state was not supplied.';
        }

        if(strlen($postParams['cc_zip']) > 0) {
            $transInfo['formInfo']['cardZip'] =  CleanQuery($postParams['cc_zip']);
        } else {
            $returnData['error'][] = 'Credit card zip was not supplied.';
        }
    }


    //if( floatval( $order['totals']['f_ub_total'] ) > 0 ) {
        $transInfo['orderTotal'] =  number_format( floatval( $order['totals']['f_ub_total'] ), 2, '.', '');            
    //} else {
      //  $errors[] = 'The system cannot charge $0.';
    //}



    //need to make sure the form passes!
    if ( isset($returnData['errors']) )
    {
        return json_encode($returnData['error']);
    }
    
    
    $transaction_results = array();
    $single_transaction_success = true;
    $mb_transaction_success = true;
    $free = false;
    if ( $transInfo['orderTotal'] > 0 ) // Pay-up sucka!
    {
        //fire single transaction
        $single_result = SingleTransaction($transInfo);
        $order['totals']['result'] = $single_result;
        $order['totals']['recordid'] = RecordResult($single_result, $transInfo['formInfo']);

        $transInfo['orderTotal'] = CleanQuery($order['mb_items']['total']);
        $transInfo['startDate'] = date('Ymd', strtotime("+" . intval($order['mb_items']['day_offset']) . " day")  );

        //monthly billing - reocurring
        $monthly_result = RecurringTransaction($transInfo);
        $order['mb_items']['result'] = $monthly_result;
        $order['mb_items']['recordid'] = RecordResult($monthly_result, $transInfo['formInfo']);
        
        //monthly
        if ( ! $order['mb_items']['result'] )
        {
            $returnData['error'][] = 'Your monthly order was declined: ' . $order['mb_items']['result']['ErrorMessage'];
            $mb_transaction_success = false;
        }
        //single transaction
        if( ! $order['totals']['result']  )
        {
            $returnData['error'][] = 'Your single order was declined: ' . $order['totals']['result']['ErrorMessage'];
            $single_transaction_success = false;
        }

    }
    else
    {
        $free = true;
        $order['mb_items']['result'] = true;
        $order['mb_items']['recordid'] = 0;
    }


    if( ($single_transaction_success && $mb_transaction_success) || $free  )
    {
        // Deduct used credits
        //products
        /**
         *NEED TO ADDRESS CREDITS.
         * foreach($_SESSION['usedCredits'] as $id => $quantity){
         *  $credits->deductCredits($id, $quantity);
         *  }
         */

        /*
         * MAKE NEW WAY TO SEND EMAIL.
         * PASS EVERYTHING TO IT. DO NOT HARD CODE ANYTHING
         */
        
        Email($userId, $postParams, $_POST);
         
         //   SendEmails($userid, $tourid, $params, $order);

        } else {
            $returnData['error'][] = 'There was an error in creating your tour.  Your card may have been charged, so please call for assistance.';
        }

    
    if( $order['mb_items']['result'] ) //$order['monthly_transaction']['result'] === true
        InsertReoccuring(null, $order['mb_items']);

    
    //Finally return everything
    echo isset($returnData['error']) ? json_encode($returnData['error']) : 'success';

}

function InsertReoccuring($tourid, $rec) {
    
    global $dbh;
    $query = 'INSERT INTO order_reoccuring (tourID, transID, name, total, day_offset) VALUES (:tourID, :transID, :name, :total, :day_offset)';

    if ( $stmt = $dbh->prepare($query) )
    {
        $stmt->bindParam('tourID', $tourid);
        $stmt->bindParam(':transID', $rec['recordid']);
        $stmt->bindParam(':name', $rec['name']);
        $stmt->bindParam(':total', $rec['total']);
        $stmt->bindParam(':day_offset', $rec['day_offset']);

        try {
            $stmt->execute();
        } catch (PDOException $e){
            WriteLog("membership_functions.php", $e->getMessage());
        }

    }
}

function Email($userid, $postParams, $_POST)
{
    global $dbh;
    //$body = table($order);
    
	$m = new memberships();
	$membership = $m->getMembership($_POST['id']);
	
    $result = array();
        
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

    $mail = new PHPGMailer();
    $mail->Username = 'info@spotlighthometours.com'; 
    $mail->Password = 'bailey22';
    $mail->From = 'info@spotlighthometours.com'; 
    $mail->FromName = 'Spotlight';
    $mail->AddAddress($result['email']);
	
    $subject = "Spotlight Home Tours ".$membership['name'];
    $mail->Subject = $subject;
    $mail->IsHTML(true); // send as HTML
	
	switch ($_POST['id']) {
		case 2:
			$message = "Thank you for activating your Spotlight Preview membership with Spotlight Home Tours!
				<br />";
		case 3:
			$message = "Thank you for activating your Social Hub account with Spotlight Home Tours!
				<br />
				<br />
				To begin adding your social media accounts log into your <a href=\"http://www.spotlighthometours.com\">spotlighthometours.com<a/> account and click on \"social hub\" in the left navigation pane.
				<br />
				Next, click on the individual social media icons to connect ALL your accounts into Social Hub. 
				<br />
				<br />
				Once you are connected, you can now start sharing photos and tours to all your accounts at once! 
				<br />
				<br />
				As always feel free to contact us with any questions or concerns<br />
				<a href=\"mailto:support@spotlighthometours.com\">support@spotlighthometours.com</a>.<br />
				1-888-838-8810 
				<br />
				<br />
				***This is an automated email, please do not respond!***";           
	}
		
    $mail->Body = $message;

    if( !$mail->Send() ) 
    {   
        WriteLog("membership_functions", "Mailer Error: " . $mail->ErrorInfo);
    }
    
}