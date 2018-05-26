<?php
/**********************************************************************************************
Document: checkout_agents.php
Creator: Brandon Freeman
Date: 02-05-11
Purpose: Display all of the agents from users in comma delimited format (for Ajax request).  
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
// Document
//=======================================================================

$query = "SELECT firstName, lastName FROM users WHERE userType='Agent'";
$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
$first = true;
while($result = mysql_fetch_array($r)){
	if (!$first) {
		echo ",";
	} else {
		$first = !$first;
	}
	echo $result['firstName'] . " " . $result['lastName'];
}


?>