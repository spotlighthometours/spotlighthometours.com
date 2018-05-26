<?php

/**********************************************************************************************
Document: get_photos.php
Creator: Brandon Freeman
Date: 06-01-11
Purpose: Processes photos for a tour at a specified size, and zips them up.
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================
//ini_set ('display_errors', 1);
//error_reporting (E_ALL & ~E_NOTICE);
//ob_start();
ob_end_clean(); 
//=======================================================================
// Includes
//=======================================================================
	
require_once ('../repository_inc/classes/inc.global.php');
if (!isset($dbc)) {
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
}
require_once ('../repository_inc/create_zip.php');
require_once ('../repository_inc/image_processor_inline.php');
require_once ('../repository_inc/generate_password.php');

session_write_close();

function t($msg){
    file_put_contents("wills_log",date("Y-m-d H:i:s") . ": {$msg}\n",FILE_APPEND);
}

//=======================================================================
// Document
//=======================================================================

$s3 = new s3utils;

$s3->verbose = false;
$stretch = false;
$crop = false;
$high = false;

if(isset($_GET['tourid'])) {
	$tourid = intval($_GET['tourid']);
} elseif(isset($_POST['tourid'])) {
	$tourid = intval($_POST['tourid']);
} else {
	$tourid = 0;
}

if(isset($_GET['w'])) {
	$w = intval($_GET['w']);
} elseif(isset($_POST['w'])) {
	$w = intval($_POST['w']);
} else {
	$w = 1800;
}

if(isset($_GET['h'])) {
	$h = intval($_GET['h']);
} elseif(isset($_POST['h'])) {
	$h = intval($_POST['h']);
} else {
	$h = 1200;
}

if(isset($_GET['m'])) {
	if($_GET['m'] == 'crop') {
		$crop = true;
		$stretch = false;
		$high = false;
	} elseif ($_GET['m'] == 'stretch') {
		$crop = false;
		$stretch = true;
		$high = false;
	} elseif ($_GET['m'] == 'high') {
		$crop = false;
		$stretch = false;
		$high = true;
	}
} elseif(isset($_POST['m'])) {
	if($_POST['m'] == 'crop') {
		$crop = true;
		$stretch = false;
		$high = false;
	} elseif ($_POST['m'] == 'stretch') {
		$crop = false;
		$stretch = true;
		$high = false;
	} elseif ($_POST['m'] == 'high') {
		$crop = false;
		$stretch = false;
		$high = true;
	}
}

$log = "=================" . date("Y/m/d H:i:s") . "=================\n";
$logFolder = 'log';
$myFile = $logFolder . "/" . date("Ymd") . "-" . $tourid . ".txt";

try {
	$log .= date("YmdHis") . " - TOURID: " . $tourid .  "\n";
	$log .= date("YmdHis") . " - WIDTH: " . $w .  "\n";
	$log .= date("YmdHis") . " - HEIGHT: " . $h .  "\n";
	if($crop) $log .= date("YmdHis") . " - CROP: true\n";
	if($stretch) $log .= date("YmdHis") . " - STRETCH: true\n";
	if($high) $log .= date("YmdHis") . " - HIGH: true\n";
    $folderFound = false;
	
    if( file_exists($source = $_SERVER['DOCUMENT_ROOT'] . '/images/tours/' . $tourid . '/') ){
	    $folderFound = true;
    }else if(file_exists($source = 'F:/images/tours/' . $tourid . '/')){
        $folderFound = true;
	}else if(file_exists( $source = 'G:/images/tours/' . $tourid . '/') ){
        $folderFound = true;
	}
    t("Before generate password");
	$tempdir = generatePassword();
	$rel_dest = 'photos/' . $tempdir . '_' . $tourid;
    $dirName = dirname(__FILE__) . "/{$rel_dest}";
    t("Directory name: $dirName");
    //If we found the tour's folder
    if( $folderFound ){
        t("Folder was found. Let's copy all of that good stuff to $dirName");
        $files = glob("{$source}photo_high_*.jpg");
        foreach($files as $index => $img ){
            //Get the file name
            $parts = explode("/",$img);
            $fileName = array_pop($parts);
            $ret = copy($img , "{$dirName}/{$fileName}");
            t("Copied: $img to {$dirName}/{$fileName} -- copy status: " . var_export($ret,1) );
        }
    }
    //Tell it to stfu
    $s3->verbose = false; 
    //Grabbing from s3
    t("Downloading photos from S3");
    $s3->downloadPhotos($tourid,$dirName);
    $source = $dirName;

    t("tourID: $tourid and file exists{$source}: " . var_export(file_exists($source),1) );
	if ($tourid && file_exists($source) ) { 
		t("TourId: $tourid");
		$queryT = "SELECT address, unitNumber FROM tours WHERE tourID = '".$tourid."'";
		$rt = mysql_query($queryT);
		$tourTitle = strtolower(mysql_result($rt, 0, 'address'));
		$unitNumber = mysql_result($rt, 0, 'unitNumber');
		if(!empty($unitNumber)){
			$tourTitle = strtolower($unitNumber).'-'.$tourTitle;
		}
		$titleSearch = array(
			"south",
			"east",
			"west",
			"street",
			"drive",
			"road"
		);
		$titleReplace = array(
			"s",
			"e",
			"w",
			"str",
			"dr",
			"rd"
		);
		$tourTitle = str_replace($titleSearch, $titleReplace, $tourTitle);
		$tourTitle = explode(" ", $tourTitle);
		$tourTitleMod = $tourTitle[0];
        t("tourtitle mod: $tourTitleMod");
		if(isset($tourTitle[1])&&!empty($tourTitle[1])){
			$tourTitleMod .= "-".$tourTitle[1];
		}
		if(isset($tourTitle[2])&&!empty($tourTitle[2])){
			$tourTitleMod .= "-".$tourTitle[2];
		}
		if(isset($tourTitle[3])&&!empty($tourTitle[3])){
			$tourTitleMod .= "-".$tourTitle[3];
		}
		$tourTitle = str_replace(str_split(preg_replace("/([[:alnum:]_\.-]*)/","-",$tourTitleMod)),"-",$tourTitleMod);
		
		$zipName = $tourTitle . '_' . $w . 'x' . $h . '.zip';
        t("ZipName: $zipName");
		if($high) {
            if( $w != 0 && $h != 0 ){
    			$zipName = $tourTitle . '_' . $w . 'x' . $h . '_highres.zip';
            }else{
    			$zipName = $tourTitle . '_highres.zip';
            }
		}
		
		//Strip non-ascii characters
		$zipName = preg_replace('|[^0-9\.\-a-zA-Z_]{1,}|','',$zipName);
		
		$zipFile = $_SERVER['DOCUMENT_ROOT'].'/image_processor/zips/'. $zipName;
		// If the zip file exist check to see if there are photos that were created after the zip file or if the photo count is dif then what is in the zip now
        //XXX I've been receiving tons of calls of people 
        // complaining that the zip files don't have updated 
        // pics and whatnot. The caching of zip files is a great 
        // idea, but when the number of pictures remains the 
        // same and they get updated/edited, then this script
        // has no way of knowing. The end result is that the zip
        // file gets served to the client with stale data - see 
        // SVN revision 160 for the cache code
		if(file_exists($zipFile)){
			unlink($zipFile);
		}
		// If the zip file is already created then do not process the photos again just feed the old zip file from before (deletes every 3 days)
		if(file_exists($zipFile)){
			if(isset($_REQUEST['download'])&&$_REQUEST['download']=="1"){
				DownloadFile($zipFile);
			}else{
				echo dirname($_SERVER['PHP_SELF']) . '/zips/' . $zipName;
			}
		}else{
			// Zip does not exist or the photo count does not match on both ends. Process photos and create a new zip file for download
			$destination = $_SERVER['DOCUMENT_ROOT'] . '/image_processor/' . $rel_dest;
				
			$query = '
				SELECT mediaID, mediaType, room, fileExt FROM media WHERE tourID = "' . $tourid . '" AND mediaType = "photo"
			';
			$r = mysql_query($query);
			while($result = mysql_fetch_array($r)) {
				set_time_limit(900);
				$fileName = $result['mediaType'] . '_high_' . $result['mediaID'] . '.' . $result['fileExt'];
//echo "File exists: $source . $fileName . " . var_export(file_exists($source . $fileName),1) . "<hr>";
				if(file_exists($source . $fileName)) {
                    t("File exists: $source $fileName");
					$log .= date("YmdHis") . " - " . $source . $fileName . ' FOUND' . "\n";
					$fileRoom = str_replace(str_split(preg_replace("/([[:alnum:]_\.-]*)/","-",$result['room'])),"-",$result['room']);
					if(!$high) {
						if(ImageProc($source . $fileName, $destination, $result['mediaID'] . "_" . $fileRoom . "_" . $w . 'x' . $h . '.' . $result['fileExt'], $w, $h, $crop, $stretch)) {
							$log .= date("YmdHis") . " - " .  $result['mediaID'] . "_" . $fileRoom . "_" . $w . 'x' . $h . '.' . $result['fileExt'] . ' SUCCESSFUL' . "\n"; 
						} else {
							$log .= date("YmdHis") . " - " .  $result['mediaID'] . "_" . $fileRoom . "_" . $w . 'x' . $h . '.' . $result['fileExt'] . ' FAILURE' . "\n"; 
						}
					} else {
						// Copy the high resolution photos to the working directory.	
						if (!file_exists($destination)) {
							mkdir($destination, 0777, true);
						}
						if(copy($source . $fileName, $destination . '/' . $result['mediaID'] . "_" . $fileRoom . "_high." . $result['fileExt'])) {
							$log .= date("YmdHis") . " - " . $source . $result['mediaID'] . "_" . $fileRoom . "_high." . $result['fileExt'] . ' COPY SUCCESSFUL' . "\n"; 
						} else {
							$log .= date("YmdHis") . " - " . $source . $result['mediaID'] . "_" . $fileRoom . "_high." . $result['fileExt'] . ' COPY FAILURE' . "\n"; 
						}
	
					}
                    
				} else {
					$log .= date("YmdHis") . " - " .  $source . $fileName . ' NOT FOUND' . "\n";
				}
			}
			
			$files_to_zip = array();
			$files = @scandir($rel_dest);
			t("Scan dir: $rel_dest :: " . var_export($files,1));
			foreach ($files as $file) {
				if (strpos($file, '.jpg')||strpos($file, '.jpeg')) {
					array_push($files_to_zip, $rel_dest . '/' . $file);
				}
			}
			t("Creating zip -- Zips: " . var_export($files_to_zip,1) . " -- zipname; $zipName");
			if (create_zip($files_to_zip, 'zips/' . $zipName, true)) {
				t("Zip creation okay... zipname and files: $zipName " . var_export($files_to_zip,1));
				$log .= date("YmdHis") . " - " .  'CREATE: ' . $zipName . ' - SUCCESSFUL' . "\n";
				if(isset($_REQUEST['download'])&&$_REQUEST['download']=="1"){
					$file = $_SERVER['PHP_SELF'] . '/zips/' . $zipName;
					
					// Set headers
	//				header("Cache-Control: public");
	//				header("Content-Description: File Transfer");
	//				header("Content-Disposition: attachment; filename=$zipName");
	//				header("Content-Type: application/zip");
	//				header("Content-Transfer-Encoding: binary");
		
					/*header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename='.basename($_SERVER['DOCUMENT_ROOT'].'/image_processor/zips/'. $zipName));
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate');
					header('Pragma: public');
					header('Content-Length: ' . filesize($_SERVER['DOCUMENT_ROOT'].'/image_processor/zips/'. $zipName));
					ob_clean();
					flush();
					
					// Read the file from disk
					readfile($_SERVER['DOCUMENT_ROOT'].'/image_processor/zips/'. $zipName);*/
					// find function in inc.global.php
					DownloadFile($_SERVER['DOCUMENT_ROOT'].'/image_processor/zips/'. $zipName);
				}else{
					echo dirname($_SERVER['PHP_SELF']) . '/zips/' . $zipName;
				}
			} else {
				$log .= date("YmdHis") . " - " .  'CREATE: ' . $zipName . ' - FAILURE' . "\n";
			}
			
			foreach ($files as $file) {
				if(file_exists($rel_dest . '/' . $file)) {
					if(strlen($file) > 2) {
						unlink($rel_dest . '/' . $file);
					}
				}
			}
			rmdir($rel_dest);
		}
	}

} catch (Exception $e) {
	$log .= date("YmdHis") . " - ERROR: " . $e->getMessage() . "\n";
}

//Write to the log
//Create the file if it doesn't exist.
if (!file_exists($logFolder . '/')) {
	mkdir($logFolder, 0777, true);
}
if (!file_exists($myFile)) {
	$fh = fopen($myFile, 'w') or die("can't open file");
	fclose($fh);
}
$fh = fopen($myFile, 'a') or die("can't open file");
fwrite($fh, $log);
fclose($fh);


?>
