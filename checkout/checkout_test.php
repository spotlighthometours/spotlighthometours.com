<?php
/**********************************************************************************************
Document: checkout_test.php
Creator: Brandon Freeman
Date: 02-01-11
Purpose: Brand new check-out system for 2011.  Now with 50% more awesome!
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();

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

	// Start the session
	session_start();
	
	$_SESSION['express_user'] = false;
	$_SESSION['realtordotcom'] = true;
	$_SESSION['state'] = 'UT';
	
	$tourtypes[0]['id'] = 18;
	$tourtypes[0]['qty'] = 1;
	$tourtypes[1]['id'] = 28;
	$tourtypes[1]['qty'] = 2;
	//$tourtypes = null;
	$items[0]['id'] = 34;
	$items[0]['qty'] = 2;
	$items[1]['id'] = 40;
	$items[1]['qty'] = 5;
	$items[2]['id'] = 23;
	$items[2]['qty'] = 1;
	$items[3]['id'] = 1;
	$items[3]['qty'] = 1;
	$city = "Tooele";
	$zip = "84074";
	$brokerid = 68;
	//$brokerid = 80;
	
	$coupon = 'freeman';
	
	$return = pricing( $tourtypes, $items, $city, $zip, $brokerid );
	$return = ApplyMileage($return, $city, $zip);
	
	
	//echo "mileage: " . GetMileagePrice($city, $zip);
	//print_r ( $return );
	
	//echo BuildTable($return, $coupon, '');
	
	$return = ApplyItemDiscounts($return, $coupon);
	
	print_r ( $return );
	
	$totals = GetOrderTotals($return, $coupon);
	
	print_r ( $totals );
?>