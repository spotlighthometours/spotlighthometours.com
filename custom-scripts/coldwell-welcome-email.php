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
	
//=======================================================================
// Document
//=======================================================================

	set_time_limit(1200);	

	/*$brokerageIDs = array(
	'138',
	'300',
	'456',
	'457',
	'458',
	'400',
	'326',
	'339',
	'78',
	'431',
	'130',
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
	'420',
	'421',
	'135',
	'354',
	'423'
	);*/

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
		print '<a href="?brokerage='.$id.'">'.$id.'</a> ';
	}	

	if(isset($_REQUEST['brokerage'])){
	// Count Users in Brokerage
	$userCount = $brokerages->countUsers($_REQUEST['brokerage']);

	$limit = 100;
	$pages = ceil($userCount/$limit);

	print '<br/><br/>page ';

	for($i=0; $i<$pages; $i++){
		$page = $i+1;
		print '<a href="?brokerage='.$_REQUEST['brokerage'].'&page='.$page.'">'.$page.'</a> ';
	}

	if(isset($_REQUEST['page'])){
		$brokerages->sendEmail($_REQUEST['brokerage'], SUPPORT_EMAIL, "Welcome to Spotlight Home Tours!", 'coldwell-welcome', $_REQUEST['page'], $limit);
	}
	}
	
?>