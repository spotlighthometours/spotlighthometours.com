<?php
/**********************************************************************************************
Document: checkout_submitorder.php
Creator: Brandon Freeman
Date: 02-28-11
Purpose: Creates the html elements for displaying additional products. (for Ajax request)  
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();
	
	// Start the session
	session_start();
//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
	require_once ('checkout_orderpricing.php');
	require_once ('checkout_merchant.php');

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
	
	// Build Array of items from post string.
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
	
	// Set ccname.
	if (isset($_POST['ccname'])) {
		$ccname = cleanQuery($_POST['ccname']);
	}
	
	// Set ccaddress.
	if (isset($_POST['ccaddress'])) {
		$ccaddress = cleanQuery($_POST['ccaddress']);
	}
	
	// Set cccity.
	if (isset($_POST['cccity'])) {
		$cccity = cleanQuery($_POST['cccity']);
	}
	
	// Set ccstate.
	if (isset($_POST['ccstate'])) {
		$ccstate = cleanQuery($_POST['ccstate']);
	}
	
	// Set cczip.
	if (isset($_POST['cczip'])) {
		$cczip = cleanQuery($_POST['cczip']);
	}
	
	// Set cctype.
	if (isset($_POST['cctype'])) {
		$cctype = cleanQuery($_POST['cctype']);
	}
	
	// Set ccnumber.
	if (isset($_POST['ccnumber'])) {
		$ccnumber = cleanQuery($_POST['ccnumber']);
	}
	
	// Set ccmonth.
	if (isset($_POST['ccmonth'])) {
		$ccmonth = str_pad(cleanQuery($_POST['ccmonth']), 2, "0", STR_PAD_LEFT);  // We always need a two digit month like '01'.
	}
	
	// Set ccyear.
	if (isset($_POST['ccyear'])) {
		$ccyear = substr(cleanQuery($_POST['ccyear']), -2); // We always need a two digit year like '11'.
	}
	
	if (isset($_GET['debug'])) {
		$_SESSION['debug'] = true;
	}
	
	// Header for XML output.
	header("Content-type: text/xml");
	echo '<?xml version="1.0" encoding="ISO-8859-1"?>' . chr(10);
	echo '<transactions>' . chr(10);
	// get the order information
	$shoppingList = pricing( $tourtypes, $items, $city, $zip, $brokerid );
	$shoppingList = ApplyMileage($shoppingList, $city, $zip);
	$shoppingList = ApplyItemDiscounts($shoppingList, $coupon);
	$OrderInfo = GetOrderTotals($shoppingList, $coupon);
	
	//if (isset($_GET['debug'])) { print_r($shoppingList); print_r($OrderInfo); }
	
	$transInfo = array();
	$transInfo['userId'] = $_SESSION['user_id'];
	$transInfo['nameOnCard'] = $ccname;
	$transInfo['cardNumber'] = $ccnumber;
	$transInfo['cardMonth'] = $ccmonth;
	$transInfo['cardYear'] = $ccyear;
	$transInfo['cardAddress'] = $ccaddress;
	$transInfo['cardCity'] = $cccity;
	$transInfo['cardState'] = $ccstate;
	$transInfo['cardZip'] = $cczip;
	
	// Proceed if we have something to charge a card.
	if ($OrderInfo['newtotal'] > 0) {
		// Put together transaction info for the single transaction.
		$transInfo['orderTotal'] = $OrderInfo['newtotal'];
		
		// Run single transaction
		$output = SingleTransaction($transInfo);
		
		// Deal with the output from that transaction
		if (isset($output)) {
			echo '<transaction type="single" >' . chr(10);
			if (isset($output['transResult'])) {
				echo '	<result>' . $output['transResult'] . '</result>' . chr(10);
			}
			if (isset($output['transId'])) {
				echo '	<id>' . $output['transId'] . '</id>' . chr(10);
			}
			if (isset($output['merchError'])) {
				echo '	<error>' . $output['merchError'] . '</error>' . chr(10);
			}
			echo '</transaction>' . chr(10);
			if (isset($output['sqlError'])) {
				echo '<mysqlerror>' . $output['sqlError'] . '</mysqlerror>' . chr(10);		
			}
		}
	}
	
	// Run the transactions for reoccuring items.
	foreach ($shoppingList as $listItem) {
		// Only run montly values that have a price > 0
		if (floatval($listItem['finalprice']) > 0 && intval($listItem['monthly']) == 1) {
			
			// Set the billing price
			$transInfo['orderTotal'] = $listItem['finalprice'];
			
			// Set the billing start date if specified
			if (isset($listItem['billingdate'])) {
				$transInfo['startDate'] = $listItem['billingdate'];
			} else {
				$transInfo['startDate'] = date('Ymd');
			}
			
			$output = RecurringTransaction($transInfo);
			
			if (isset($output)) {
				echo '<transaction type="recurring" >' . chr(10);
				if (isset($output['transResult'])) {
					echo '	<result>' . $output['transResult'] . '</result>' . chr(10);
				}
				if (isset($output['transId'])) {
					echo '	<id>' . $output['transId'] . '</id>' . chr(10);
				}
				if (isset($output['merchError'])) {
					echo '	<error>' . $output['merchError'] . '</error>' . chr(10);
				}
				echo '</transaction>' . chr(10);
				if (isset($output['sqlError'])) {
					echo '<mysqlerror>' . $output['sqlError'] . '</mysqlerror>' . chr(10);		
				}
			}
			
		}
	}
	
	echo '</transactions>' . chr(10);
	/* Save/Update Card.
	if (isset($_POST['ccsave']) && $result == "APPROVED") {
		// Check to see if the card number is already in the database.
		$query = ("SELECT COUNT(*) AS cnt FROM usercreditcards WHERE cardNumber = '" . $ccnumber . "' AND userid = '" . $_SESSION['user_id'] . "'"); 
		$r = mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
		$result = mysql_fetch_array($r);
		
		if(intval($result['cnt']) == 0) {
			$query = 
			'INSERT INTO usercreditcards
			(userid, cardName, cardAddress, cardCity, cardState, cardZip, cardPhone, cardType, cardNumber, cardMonth, cardYear, cardNick, cardDefault)
			VALUES
			(' . $_SESSION['user_id'] . ', "' . $ccname . '", "' . $ccaddress . '", "' . $cccity . '", "' . $ccstate . '", "' . $cczip . '", "", "' . $cctype . '", "' . $ccnumber . '", "' . $ccmonth . '", "' . $ccyear . '", "XXXX-XXXX-XXXX-' . substr($ccnumber, -4) . '", 0)';
			mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
		} else {
			$query = 
			'UPDATE usercreditcards
			SET
			userid = ' . $_SESSION['user_id'] . ', 
			cardName = "' . $ccname . '", 
			cardAddress = "' . $ccaddress . '", 
			cardCity = "' . $cccity . '", 
			cardState = "' . $ccstate . '", 
			cardZip = "' . $cczip . '", 
			cardType = "' . $cctype . '", 
			cardNumber = "' . $ccnumber . '", 
			cardMonth = "' . $ccmonth . '", 
			cardYear = "' . $ccyear . '", 
			cardNick = "XXXX-XXXX-XXXX-' . substr($ccnumber, -4) . '" 
			WHERE cardNumber = "' . $ccnumber . '" AND userid = "' . $_SESSION['user_id'] . '"
			LIMIT 1';
			mysql_query($query) or print ('<mysqlerror>Query failed with error: ' . mysql_error() . ' Query being run: ' . $query . '</mysqlerror>' . chr(10));
		}
	}
	*/
?>