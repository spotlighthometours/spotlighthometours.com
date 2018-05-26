<?php
/**********************************************************************************************
Document: checkout_insertorder.php
Creator: Brandon Freeman
Date: 03-02-11
Purpose: Inserts the order/tour into the database. (for Ajax request)  
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();
	
	// Turn off the session warning for something I'm obviously not doing.
	// $_SESSION['foo'] = null;
	// $foo = 'yes';
	// The preceeding would throw a warning.
	ini_set('session.bug_compat_42',0);
	ini_set('session.bug_compat_warn',0);
	
	// Start the session
	session_start();

//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
	require_once ('checkout_orderpricing.php');

//=======================================================================
// Document
//=======================================================================
	
	// Set broker id.
	if (isset($_POST['brokerid'])) {
		$brokerid = cleanQuery($_POST['brokerid']);
	} else {
		$brokerid = -1;
	}

	// Set city.
	if (isset($_POST['city'])) {
		$city = cleanQuery($_POST['city']);
	} else {
		$city = -1;
	}

	// Set zip.
	if (isset($_POST['zip'])) {
		$zip = cleanQuery($_POST['zip']);
	} else {
		$zip = -1;
	}
	
	// Set tourtypes.
	if (isset($_POST['tourtypeid'])) {
		$tourtypeid = cleanQuery($_POST['tourtypeid']);
	} else {
		$tourtypeid = -1;
	}
	if (intval($tourtypeid) == -1) {
		$tourtypes = null;
	} else {
		$tourtypes[0]['id'] = $tourtypeid;
		$tourtypes[0]['qty'] = 1;	
	}
	
	// Set coupon.
	if (isset($_POST['coupon'])) {
		$coupon = cleanQuery($_POST['coupon']);
	} else {
		$coupon = -1;
	}
	if (strlen($coupon) == 0) {
		$coupon = -1;
	}
	
	// Build our list of items from the post.
	$stuff = array();
	$count = 0;
	while( isset($_POST['itemid' . $count])) {
		$query = "";
		if (is_numeric($_POST['itemid' . $count])) {
			$stuff[intval($_POST['itemid' . $count])]['id'] = intval($_POST['itemid' . $count]);
			$stuff[intval($_POST['itemid' . $count])]['qty'] = intval($_POST['itemqty' . $count]);
		}
		$count++;
	}
	
	$items = array();
	$count = 0;
	foreach ($stuff as $item) {
		
		$items[$count]['id'] = $item['id'];
		$items[$count]['qty'] = $item['qty'];
		$count++;
	}
	
	$state = -1;
	if (isset($_POST['state'])) {
		$state = cleanQuery($_POST['state']);
	}
	
	$transactionid = -1;
	if (isset($_POST['transactionid'])) {
		$transactionid = cleanQuery($_POST['transactionid']);
	}
	
	$title = -1;
	if (isset($_POST['title'])) {
		$title = cleanQuery($_POST['title']);
	}
	
	$address = -1;
	if (isset($_POST['address'])) {
		$address = cleanQuery($_POST['address']);
	}
	
	$price = -1;
	if (isset($_POST['price'])) {
		$price = cleanQuery($_POST['price']);
	}
	if(!is_numeric($price)) {
		$price = 0;
	}
	
	$sqfoot = -1;
	if (isset($_POST['sqfoot'])) {
		$sqfoot = cleanQuery($_POST['sqfoot']);
	}
	if(!is_numeric($sqfoot)) {
		$sqfoot = 0;
	}
	
	$bedrooms = -1;
	if (isset($_POST['bedrooms'])) {
		$bedrooms = cleanQuery($_POST['bedrooms']);
	}
	if(!is_numeric($bedrooms)) {
		$bedrooms = 0;
	}
	
	$bathrooms = -1;
	if (isset($_POST['bathrooms'])) {
		$bathrooms = cleanQuery($_POST['bathrooms']);
	}
	if(!is_numeric($bathrooms)) {
		$bathrooms = 0;
	}
	
	$mls = -1;
	if (isset($_POST['mls'])) {
		$mls = cleanQuery($_POST['mls']);
	}
	if ($mls == "MLS Number") {
		$mls = "";
	}
	
	$description = -1;
	if (isset($_POST['description'])) {
		$description = cleanQuery($_POST['description']);
	}
	
	$additional = -1;
	if (isset($_POST['additional'])) {
		$additional = cleanQuery($_POST['additional']);
	}
	
	$coagent = "NULL NULL";
	if (isset($_POST['coagent'])) {
		$coagent = cleanQuery($_POST['coagent']);
	}
	
	$hideprice = 0;
	if (isset($_POST['hideprice'])) {
		$hideprice = 1;
	}
	
	$hidesqfoot = 0;
	if (isset($_POST['hidesqfoot'])) {
		$hidesqfoot = 1;
	}
	
	$hidebedrooms = 0;
	if (isset($_POST['hidebedrooms'])) {
		$hidebedrooms = 1;
	}
	
	$hidebathrooms = 0;
	if (isset($_POST['hidebathrooms'])) {
		$hidebathrooms = 1;
	}
	
	$hideaddress = 0;
	if (isset($_POST['hideaddress'])) {
		$hideaddress = 1;
	}
	
	// This is only passed if this is from adding additional products.
	$prevtourid = -1;
	if (isset($_POST['prevtourid'])) {
		$prevtourid = cleanQuery($_POST['prevtourid']);
	}
	
	// timestamp
	$timestamp = date('Y-m-d H:i:s');
	
	// XML Header information
	header("Content-type: text/xml");
	echo '<?xml version="1.0" encoding="ISO-8859-1"?>' . chr(10);
	echo '<insertion>' . chr(10);
	
	// get the order information
	$shoppingList = pricing( $tourtypes, $items, $city, $zip, $brokerid );
	$shoppingList = ApplyMileage($shoppingList, $city, $zip);
	$shoppingList = ApplyItemDiscounts($shoppingList, $coupon);
	$OrderInfo = GetOrderTotals($shoppingList, $coupon);
	
	$subtotal = floatval($OrderInfo['newsub']);
	$tax = floatval($OrderInfo['newtax']);
	$total = floatval($OrderInfo['newtotal']);
	$brokertotal = floatval($OrderInfo['newbroker']);
	$couponval = floatval($OrderInfo['couponammt']);
	
	// There are two branches how this could go
	// First, we could have a standard new tour.
	// This would require an insert of all the information.
	// Secondly, we could be adding additional products to the tour.
	// This doesn't require all of the information, just the products and the tour id.
	// $prevtourid gets passed if the situation is the latter.
	
	if ($prevtourid == -1) {
		
		$name = explode(" ", $coagent);
		// Get the coagent's id
		$query = 'SELECT userID FROM users WHERE firstName = "' . $name[0] . '" AND lastName = "' . $name[1] . '" LIMIT 1';
		$r = mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
		$result = mysql_fetch_array($r);
		$coagent = $result['userID'];
		
		if (!is_numeric($coagent)) {
			$coagent = "NULL";
		}
		
		// Insert into tours
		$query = '
		INSERT INTO tours SET 
		userID = "' . $_SESSION['user_id'] . '", 
		tourTypeID = "' . $tourtypeid . '", 
		title = "' . $title . '",
		address = "' . $address . '",
		city = "' . $city . '",
		state = "' . $state . '", 
		zipCode = "' . $zip . '", 
		listPrice = "' . $price . '", 
		sqFootage = "' . $sqfoot . '", 
		bedrooms = "' . $bedrooms . '", 
		bathrooms = "' . $bathrooms . '", 
		mls = "' . $mls . '", 
		description = "' . $description . '", 
		additionalInstructions = "' . $additional . '", 
		createdOn = "' . $timestamp . '", 
		modifiedOn = "' . $timestamp . '", 
		hideprice = "' . $hideprice . '", 
		hidesqfoot = "' . $hidesqfoot . '", 
		hidebeds = "' . $hidebedrooms . '", 
		hidebaths = "' . $hidebathrooms . '",
		hideAddress = "' . $hideaddress . '",
		codestr = "' . $coupon . '",
		codeval = ' . $couponval . ',
		couserID = ' . $coagent . ',
		brokerbilled = ' . $brokertotal . '
		';	
		mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));

		// Get tourid number
		$query = '
		SELECT tourID 
		FROM tours 
		WHERE userID = ' . $_SESSION['user_id'] . ' 
		AND createdOn = "' . $timestamp . '" 
		LIMIT 1'; 
		$r = mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
		$result = mysql_fetch_array($r);
		$tourId = $result['tourID'];
		
		// Create an image folder for the tour
		@mkdir($_SERVER['DOCUMENT_ROOT'] . "/users/images/tours/" . $tourId, 0777, true);
		
		// Insert order
		$query = 'INSERT INTO orders SET
		userID = "' . $_SESSION['user_id'] . '", 
		transactionId = ' . $transactionid . ',
		tourid = "' . $tourId . '", 
		subtotal = "' . $subtotal . '", 
		salestax = "' . $tax . '", 
		total = "' . $total . '",
		broker_total = ' . $brokertotal . ', 
		coupon = "' . $coupon . '", 
		coupon_total = ' . $couponval . ', 
		createdOn = "' . $timestamp . '"';
		mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
	} else {
		// Stash the previous tour id into the tour id variable.
		// This will work out later.  The rest will assume that we've created an order.
		// Things should work no different form here on out.
		$tourId = $prevtourid;
		
		// Update the modified date on the tour.
		$query = 'UPDATE tours SET modifiedOn = "' . $timestamp . '" WHERE tourID = "' . $prevtourid . '" LIMIT 1';
		mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
		
		// Insert order
		$query = 'INSERT INTO orders SET
		userID = "' . $_SESSION['user_id'] . '",
		transactionId = ' . $transactionid . ',
		tourid = "' . $tourId . '", 
		subtotal = "' . $subtotal . '", 
		salestax = "' . $tax . '", 
		total = "' . $total . '",
		broker_total = "' . $brokertotal . '", 
		coupon = "' . $coupon . '", 
		coupon_total = ' . $couponval . ', 
		createdOn = "' . $timestamp . '"';
		mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
		
		// IF A RESHOOT WAS ORDERED ARCHIVE THE CURRENT TOUR AND RESET THE CURRENT TOUR SHEET DATA
		/*if($item['id']=='70'){
			// Archive
			$sql = 'INSERT INTO tour_archive
					SELECT t.*, CURRENT_DATE()
					FROM tours t
					WHERE t.tourID = '.$tourId;
			mysql_query($sql) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $sql . '</mysqlerror>' . chr(10));
		}*/
	}
	
	// Get orderId number
	$query = 'SELECT orderID FROM orders WHERE tourid = ' . $tourId . ' ORDER BY createdOn DESC LIMIT 1';
	$r = mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
	$result = mysql_fetch_array($r);
	$orderId = $result['orderID'];
	
	//Get realtor.com product id
	$query = "SELECT productID FROM products WHERE productName = 'Tour added to Realtor.com' LIMIT 1";
	$r = mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
	$result = mysql_fetch_array($r);
	$rdcId = intval($result['productID']);
	
	//Create order details.
	$insertToRdC = false;
	$query_conditions = '';
	$first = true;
	
	foreach ($shoppingList as $item) {
		if ($first) {
			$first = false;
		} else {
			$query_conditions .= ',';
		}
		
		// Flag if supposed to insert into realtor.com
		if(intval($item['id']) == $rdcId) $insertToRdC = true;
		
		$query_conditions .= '(' . $orderId . ', "' . $item['type'] . '", ' . $item['id'] . ', ' . $item['qty'] . ', ' . $item['price'] . ')';
	}
	
	if (strlen($query_conditions) > 0) {
		// Insert into orderDetails
		$query = 
			'INSERT INTO orderDetails (orderID, type, productID, quantity, unitPrice)
			VALUES ' . $query_conditions;
		mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
	}
	
	// Insert into realtor.com queue
	
	//if they selected the item, insert it into realtor.com
	//this database is shoddy.  It's not auto-increment on a primary key.
	//Previously, it was set to create a UUID ... unfortunately, this is not unique
	//100% of the time.  Hopefully a timestamp will work.
	
	if ($insertToRdC) {
		$query = 
			'INSERT INTO scheduled_realtorcom (id,tourid,createdon)
			VALUES
			("' . $timestamp . '", ' . $tourId . ', "' . $timestamp . '")';
		mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
	}
	
	// Insert Virtual Staging Information
	$count = 0;
	$vs = array();
	while( isset($_POST['vs' . $count])) {
		$vs[$count] = cleanQuery($_POST['vs' . $count]);
		$count++;
	}
	if (sizeof($vs) > 0) {
		$first = true;
		$query_conditions = '';
		
		foreach ($vs as $item) {
			
			if ($first) {
				$first = false;
			} else {
				$query_conditions .= ',';
			}
			
			$query_conditions .= '(' . $tourId . ', "' . $item . '")';
		}
		$query = 'INSERT INTO vs_orders (tourID, details) VALUES ' . $query_conditions;
		mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
	}
	
	// If they paid to be an express user, update their account.
	foreach ($shoppingList as $listItem) {
		if($listItem['type'] == 'tour' && intval($listItem['id']) == 18 && intval($listItem['monthly']) == 1 && isset($_SESSION['user_id'])) {
			$query = 'UPDATE USERS SET expressUser =  1 WHERE userID = ' . $_SESSION['user_id'] . ' LIMIT 1';
			mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
		}
	}
	
	// Output the tour id.
	if (isset($tourId)) {
		echo '<tourid>' . $tourId . '</tourid>' . chr(10);
	}
	if (isset($tourId)) {
		echo '<orderid>' . $orderId . '</orderid>' . chr(10);
	}
	
	echo '</insertion>' . chr(10);