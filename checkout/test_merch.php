<?php
/**********************************************************************************************
Document: test_merch.php
Creator: Brandon Freeman
Date: 06-14-11
Purpose: Test the merchant acct.
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
	require_once ('checkout_merchantx.php');

//=======================================================================
// Document
//=======================================================================

	// Start the session
	//userId
		$transInfo['userId'] = '123';
	
		$transInfo['nameOnCard'] = 'Brandon Freeman';
	
		$transInfo['cardNumber'] = '4680056024603400';
	
		$transInfo['cardMonth'] = '02';
	
		$transInfo['cardYear'] = '14';
	
		$transInfo['cardAddress'] = '419 E 770 N';
	
		$transInfo['cardCity'] = 'Tooele';
	
		$transInfo['cardState'] = 'UT';
	
		$transInfo['cardZip'] = '84074';
	
		$transInfo['orderTotal'] = 0.01;
		
		$transInfo['invoiceNum'] = '8675309';
		
		$response = SingleTransaction($transInfo);
		
		print_r($response);
?>