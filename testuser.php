<?php
/**********************************************************************************************
Document: testuser.php
Creator: Brandon Freeman
Date: 03-06-11
Purpose: Output user information from the session.
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


//=======================================================================
// Document
//=======================================================================

	// Start the session
	session_start();

	echo "userid: " . $_SESSION['user_id']. "(" . gettype($_SESSION['user_id']) . ')<br />';
	echo "brokerid: " . $_SESSION['broker_id']. '<br />'; 
	echo "salesrepid: " . $_SESSION['salesrep_id']. '<br />';
	echo "firstname: " . $_SESSION['first_name']. '<br />';
	echo "lastname: " . $_SESSION['last_name']. '<br />';
	echo "username: " . $_SESSION['user_name']. '<br />';
	echo "city: " . $_SESSION['city']. '<br />';
	echo "state: " . $_SESSION['state']. '<br />';
	echo "zip: " . $_SESSION['zip']. '<br />'; 
	echo "phone: " . $_SESSION['phone']. '<br />';
	echo "email: " . $_SESSION['email']. '<br />';
	echo "express: " . $_SESSION['express_user']. "(" . gettype($_SESSION['express_user']) . ')<br />';
	//if ($_SESSION['express_user'] == true) {
	//	echo "expressuser: true<br />"; 
	//} else {
	//	echo "expressuser: false<br />"; 
	//}
	echo "country: " . $_SESSION['country']. '<br />';
	echo "mls: " . $_SESSION['mls']. '<br />';
	echo "previewuser: " . $_SESSION['preview_user']. '<br />';
?>