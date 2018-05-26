<?php
/**********************************************************************************************
Document: checkout_address_info.php
Creator: Brandon Freeman
Date: 02-07-11
Purpose: Returns a piece of requested address information. (for Ajax request).  
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

//=======================================================================
// Functions
//=======================================================================

// Helps prevent XSS attacks
function transform_HTML($string, $length = null) {
	// Remove dead space.
	$string = trim($string);
	
	// Prevent potential Unicode codec problems.
	$string = utf8_decode($string);
	
	// HTMLize HTML-specific characters.
	$string = htmlentities($string, END-NOQUOTES);
	$string = str_replace("#", "&#35;", $string);
	$string = str_replace("%", "&#37;", $string);
	
	$length = intval($length);
	if ($length > 0) {
		$string = substr($string, 0, $length);
	}
	return $string;
}

// Helps to prevent SQL injection.
function cleanQuery($string) {
	if (get_magic_quotes_gpc()) {  // prevents duplicate backslashes
		$string = stripslashes($string);
	}
	if (phpversion() >= '4.3.0') {
		$string = mysql_real_escape_string($string);
	} else {
		$string = mysql_escape_string($string);
	}
	return $string;
}

//=======================================================================
// Document
//=======================================================================

$zip = cleanQuery($_GET['zip']);
$city = cleanQuery($_GET['city']);
$state = cleanQuery($_GET['state']);
//echo "zip: " . $zip . " state: " . $state . " city: " . $city . "<br />";
$query = "";

if (strlen($zip) > 0 && strlen($city) > 0 && strlen($state) == 0) {
	//We have zip amd city, but need a state.
	$query = "SELECT state_prefix AS result FROM zip_code WHERE zip_code = '" . $zip . "' AND city = '" . $city . "'";
} elseif (strlen($zip) > 0 && strlen($city) == 0 && strlen($state) > 0) {
	//We have zip amd state, but need a city.
	$query = "SELECT city AS result FROM zip_code WHERE zip_code = '" . $zip . "' AND state_prefix = '" . $state . "'";
} elseif (strlen($zip) == 0 && strlen($city) > 0 && strlen($state) > 0) {
	//We have state amd city, but need a zip.
	$query = "SELECT zip_code AS result FROM zip_code WHERE state_prefix = '" . $state . "' AND city = '" . $city . "'";
}

//echo "query: " . $query . "<br />";

if (strlen($query) > 0) {
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
	$first = true;
	while($result = mysql_fetch_array($r)){
		if (!$first) {
			echo ",";
		} else {
			$first = !$first;
		}
		echo $result['result'];
	}
}

?>