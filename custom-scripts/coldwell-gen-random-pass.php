<?PHP
/**********************************************************************************************
Document: coldwell-welcome-email.php
Creator: Jacob Edmond Kerr
Date: 04-08-11
Purpose: Sends a welcome email to everyone in Coldwell Brokerages. 
Notes: 
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

	// Global Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');

//=======================================================================
// Document
//=======================================================================

	// Create instance of the emailer class
	$brokerages = new brokerages($db);

	// Create instance of the security obj
	$security = new security();

	// Create instance of my logger class
	$logger = new logger();
	
//=======================================================================
// Document
//=======================================================================

	$brokerageIDs = array(
	'406',
	'407',
	'408',
	'409',
	'410',
	'411',
	'412',
	'413',
	'415',
	'416',
	'417',
	'418',
	'419',
	'420'
	);
	
	foreach($brokerageIDs as $index => $id){
		$users = $db->select("users", "brokerageID='".$id."' AND password='temp'", "", "userID");
		foreach($users as $row => $column){
			$password = $security->generatePassword();
			$update_data = Array(
				'password'=>$password
			);
			$db->update('users', $update_data, 'userID="'.$column['userID'].'"');
			$logger->logInfo("User: ".$column['userID']." password updated with random password!");
		}
	}
?>