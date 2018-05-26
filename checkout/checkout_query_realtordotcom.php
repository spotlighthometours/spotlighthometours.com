<?php
/**********************************************************************************************
Document: checkout_query_realtordotcom.php
Creator: Brandon Freeman
Date: 03-08-11
Purpose: Toggles the user session variable for being a member or realtor.com. (for Ajax request)  
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
// Document
//=======================================================================

	if (isset($_POST['member'])) {
		if ($_POST['member'] == 1) {
			$_SESSION['realtordotcom'] = true;
		} else {
			$_SESSION['realtordotcom'] = false;
		}
	}
	
?>