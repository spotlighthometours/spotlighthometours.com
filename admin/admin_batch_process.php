<?php
/**********************************************************************************************
Document: admin_batch_process
Creator: Jacob Edmond Kerr
Date: 10-11-13
Purpose: Processes all the photos and videos in /images/dropbox/  
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	ignore_user_abort(true);
	set_time_limit(0);

	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	showErrors();
	
//=======================================================================
// Objects
//=======================================================================

	// Create Processor Object
	$processor = new processor();
	
//=======================================================================
// Document
//=======================================================================

	$processor->runBatch();

?>