<?php
/**********************************************************************************************
Document: checkout_getproductdesc.php
Creator: Brandon Freeman
Date: 02-24-11
Purpose: Returns set description for a product. (for Ajax request)  
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

//=======================================================================
// Document
//=======================================================================

	if (isset($_POST['name'])) {
		$name = CleanQuery($_POST['name']);
	} elseif (isset($_GET['name'])) {
		$name = CleanQuery($_GET['name']);
	}

	$query = "SELECT description FROM products WHERE productName = '" . $name . "' LIMIT 1";
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error());
	$result = mysql_fetch_array($r);
	echo $result['description'];

?>