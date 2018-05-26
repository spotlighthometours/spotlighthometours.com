<?php
/**********************************************************************************************
Document: checkout_getccdetails.php
Creator: Brandon Freeman
Date: 02-28-11
Purpose: Returns cc information. (for Ajax request)  
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

//=======================================================================
// Document
//=======================================================================

	if (isset($_POST['cardid'])) {
		$cardid = CleanQuery($_POST['cardid']);
	} elseif (isset($_GET['cardid'])) {
		$cardid = CleanQuery($_GET['cardid']);
	}
	
	$userid = -1;
	if (isset($_POST['userid'])) {
		$userid = CleanQuery($_POST['userid']);
	} elseif (isset($_GET['userid'])) {
		$userid = CleanQuery($_GET['userid']);
	} elseif (isset($_SESSION['user_id'])) {
		$userid = $_SESSION['user_id'];
	}
	
	header("Content-type: text/xml");
	echo '<?xml version="1.0" encoding="UTF-8" ?> ' . chr(10);
	
	$query = "SELECT crardId, cardName, cardAddress, cardCity, cardState, cardZip, cardType, cardNumber, cardMonth, cardYear FROM usercreditcards WHERE crardId = " . $cardid . " AND userid = " . $userid . " LIMIT 1";
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error());
	$result = mysql_fetch_array($r);
	echo '<ccard id="' . $result['crardId'] . '" name="' . $result['cardName'] . '" address="' . $result['cardAddress'] . '" city="' . $result['cardCity'] . '" state="' . $result['cardState'] . '" zip="' . $result['cardZip'] . '" type="' . $result['cardType'] . '" number="' . $result['cardNumber'] . '" month="' . $result['cardMonth'] . '" year="' . $result['cardYear'] . '" />' . Chr(10);

?>