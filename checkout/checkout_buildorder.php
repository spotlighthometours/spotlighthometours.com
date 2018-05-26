<?php
/**********************************************************************************************
Document: checkout_buildorder.php
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
	require_once ('../repository_inc/error_recorder_incode.php');
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
	
	$return = pricing( $tourtypes, $items, $city, $zip, $brokerid );
	$return = ApplyMileage($return, $city, $zip);
	
	echo BuildTable($return, $coupon, 'checkout_table');
	
?>