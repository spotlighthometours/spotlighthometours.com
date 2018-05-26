<?php
	/**********************************************************************************************
	Document: concierge.php
	Creator: Jacob Edmond Kerr
	Date: 04-15-15
	Purpose: Scheduled task. This file will run all the automated stuff for concierge. Mainly pulling and parsing the MLS feeds, creating tours etc.
	**********************************************************************************************/

	//=======================================================================
	// Includes
	//=======================================================================

	$debugInfo = 'Initialized App';
	$debugInfo = file_put_contents('D:\websites\spotlighthometours\public\mls-temp\concierge.txt','create_conc_object:'.$debugInfo."\r\n",FILE_APPEND);

		// Include appplication's global configuration
		require_once('repository_inc/classes/inc.global.php');
		// showErrors();
	//=======================================================================
	// Objects
	//=======================================================================

	$debugInfo = 'Initialized Resource';
	$debugInfo = file_put_contents('D:\websites\spotlighthometours\public\mls-temp\concierge.txt','load_conc_object:'.$debugInfo."\r\n",FILE_APPEND);

		// Create needed Objects
		$concierge = new concierge();

	$debugInfo = 'Initialized Resource';
	$debugInfo = file_put_contents('D:\websites\spotlighthometours\public\mls-temp\concierge.txt','load_conc_object:'.$debugInfo."\r\n",FILE_APPEND);

	//=======================================================================
	// Document
	//=======================================================================

	$debugInfo = $concierge->build();
	// print_r($concierge);
	die('system halted task complete ['.$debugInfo."]");
?>