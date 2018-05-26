<?php
/**********************************************************************************************
Document: process_concierge_order.php
Creator: Jacob Edmond Kerr
Date: 09-12-16
Purpose: Process credit card transaction for concierge orders, save transaction response 
         and order. Activate Concierge membership and/or package. Also send email notifications (for Ajax request)  
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================
	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	showErrors();
//=======================================================================
// Objects
//=======================================================================

	// Create Users Object
	$users = new users($db);
	
	// Create Transactions Object
	$transactions = new transactions();
	//echo '<pre>';
	//print_r($transactions);
	//echo '</pre>';
	$authorizenet = new authorizenet();
	// Create Orders Object
	//$orders = new orders();
	
	// Create Packages Object
	//$packages = new packages();
	
	// Create Emailer Object
	//$emailer = new emailer();
	
	// Create Brokerages Object
	//$brokerages = new brokerages($db);
	
//=======================================================================
// Document
//=======================================================================

	//$customerProfiles = $authorizenet->getCustomerProfile();
	$customerProfileLists = $authorizenet->getCustomerPaymentProfileList();
	//echo "<pre>";
	//print_r($$customerProfileLists);
	//echo "</pre>";
	$infusionsofts = new infusionsoft();
	$infu = $infusionsofts->getTags();
	print_r($infu);
	

?>