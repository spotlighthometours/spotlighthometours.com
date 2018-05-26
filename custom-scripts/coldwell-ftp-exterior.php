<?PHP
/**********************************************************************************************
Document: coldwell-ftp-exterior.php
Creator: Jacob Edmond Kerr
Date: 06-19-12
Purpose: Get the tour icon for all the tours belonging to a list of Coldwell Banker brokerages and upload image via ftp.
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
	
	// Create instance of the tours obj
	$tours = new tours($db);

	// Create instance of the media obj
	$media = new media();
	
	// Create instance of the ftp obj
	$ftp = new ftp();
	
//=======================================================================
// Document
//=======================================================================

	set_time_limit(9999999999);
	
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
	
	$count = 0;
	
	// Connect to FTP Server
    $ftp->connect('ftp.nrtsupport.com');
    $ftp->login('NRTWEST/5405', '5405');
    // turn passive mode on
    $ftp->setPassive();
	$ftp->changeDir('2012 Coloradohomes & Lifestyles');
	$ftp->changeDir('PHOTOGRAPHERS');
	
	foreach($brokerageIDs as $index => $id){
		$users = $brokerages->getUsers($id);
		foreach($users as $row => $column){
			$yesterday = date('Y-m-d', strtotime("-1 day"));
			$sql = "SELECT md.mediaID, md.tourID, (SELECT address FROM tours WHERE tourID = md.tourID) as address FROM media md WHERE (SELECT userID FROM tours WHERE tourID = md.tourID) = '".$column['userID']."' AND md.createdON > '".$yesterday."' AND md.tourIcon = '1'";
			$userTours = $db->run($sql);
			foreach($userTours as $row => $column){
				$tourIcon = $tours->getTourIcon($column['tourID'], 'high', true, $_SERVER['DOCUMENT_ROOT']);
				if(!$tourIcon===false){
					$dir = ereg_replace("[^A-Za-z0-9]", "", $column['address']);
					$ftp->makeDir($dir);
					$ftp->changeDir($dir);
					$server_file = explode("/", $tourIcon);
					$server_file = $server_file[count($server_file)-1];
					$ftp->uploadFile($tourIcon, $server_file);
					$ftp->changeDir('../');
					$count++;
				}
			}
		}
	}
	
	print $count . ' photos uploaded!';
?>