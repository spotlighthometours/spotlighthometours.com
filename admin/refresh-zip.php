<?php
/**********************************************************************************************
Creator: William Merfalen
Date: 09-30-2015 ILYK
Purpose: Allow a user to delete a zip file
**********************************************************************************************/

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
showErrors();

//=======================================================================
// Includes
//=======================================================================

//=======================================================================
// Document
//=======================================================================

// Start the session
session_start();
$users = new users;
$tourphotos = new tourphotos();

$a = $users->authenticateAdmin(true);
if( isset($_POST['zip']) ){
	$file = preg_replace('|[\\/]{1,}|','',$_POST['zip']);
	@unlink($_SERVER['DOCUMENT_ROOT'] . '/image_processor/zips/' . $file);
}

function generateZipFileName($tourid){
	global $db;
    $queryT = "SELECT address FROM tours WHERE tourID = '".$tourid."'";
    $rt = $db->run($queryT);
    $tourTitle = strtolower($rt[0]['address']);
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
    
    //$zipName = $tourTitle . '_' . $w . 'x' . $h . '.zip'; 
	return $tourTitle;
/*
    if($high) {
        $zipName = $tourTitle . '_highres.zip';
    }
    return $zipName;
*/
    
}

function validateSize($s){
	if( preg_match("|^highres\$|",$s) ){
		return "highres";
	}
	if( preg_match("|^([0-9]{3,}x[0-9]{3,})\$|",$s,$matches) ){
		return $matches[1];
	}
	return null;
}

if( isset($_POST['ajax']) ){
	switch($_POST['mode']){
		case 'list':
			$title = generateZipFileName($_POST['tourId']);
			if( strlen($title) == 0 ){
				die(json_encode(array()));
			}
			//$files = glob($_SERVER['DOCUMENT_ROOT'] . '/image_processor/zips/' . $title . '*');
			$tourphotos = new tourphotos();
			$files = $tourphotos->listZipFiles($_POST['tourId']);
			$a = array();
			foreach($files as $index => $fileName){
				$fileName = explode(".zip",$fileName);
				$fileName = $fileName[0];
				$a[] = array('tourId'=>$_POST['tourId'],'size'=>$fileName);
			}
			die(json_encode($a));
		break;
		case 'delete':
			/*$title = generateZipFileName($_POST['tourId']);
			if( strlen($title) == 0 ){
				die(json_encode(array('status'=>'nothing to delete')));
			}
			$file = $_SERVER['DOCUMENT_ROOT'] . '/image_processor/zips/' . $title . '_' . validateSize($_POST['size'])  . '.zip';
			if(file_exists($file)){
				unlink($file);
				die(json_encode(array('status'=>'deleted')));
			}
			die(json_encode(array('status'=>'nothing to do')));*/
			$tourphotos = new tourphotos();
			$width = $_POST['size'];
			$width = explode("_", $width);
			$width = end($width);
			$width = explode("x",$width);
			$width = $width[0];
			$tourphotos->deleteZip($_POST['tourId'], $width);	
		break;
	}
	die;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Spotlight Home Tours Admin - Refresh Zip</title>
		<link REL="SHORTCUT ICON" HREF="../repository_images/icon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">@import "../repository_css/admin.css";</style>
		<style type="text/css" media="screen">@import "../repository_css/spinner.css";</style>
		<script src="/repository_inc/jquery-1.11.2.min.js"></script>
		<script>
			$(document).ready(function(){
			});
			function deleteZip(obj){
				if(!confirm("Are you sure you want to delete this?") ){
					return;
				}
				zip = $(obj).data("zip");
				$.ajax({
					url: '/admin/refresh-zip.php',
					data: {
						ajax: 1,
						mode: 'delete', 
						'tourId': $(obj).data("tourid"),
						size: $(obj).data("size")
					},
					type: 'POST'
				}).done(function(msg){
					alert("File has been deleted");
					$(obj).remove();
				});
			}
			function search(){
				$.ajax({ 
					url: '/admin/refresh-zip.php', 
					data: { 'ajax': 1,
						'mode': 'list',
						'tourId': $("#tourId").val()
					},
					type: 'post'
				}).done(function(msg){
					a = $.parseJSON(msg);
					if( a.length == 0 ){
						$("#output").html("<b>No zip files found :/ </b>");
						return;
					}
					$("#output").html("");
					for(i=0;i < a.length;i++){
						$("#output").append("<a href='javascript:void(0);' onClick='deleteZip(this)' data-tourid='" + a[i].tourId + "' data-size='" + a[i].size + "'>Delete</a><b>" + a[i].tourId + "::" + a[i].size + "</b><br>");
					}
				});
			}
		</script>
	</head>
<body style='margin: 20px;'>
<div style='width: 50%;float:left;clear:both;background-color: #eee;padding:20px;'>
<b>How it works</b><br>
<p>
If an agent calls in and says that the zip files they downloaded contain stale or out of date images, then you'll want to use this page. This page allows you to delete old zip files. The way this works is that if the zip file is out of date, deleting them will force the system to recreate a zip file. Simply type in the tour id of the tour and click search. A list of zip files will appear with a "delete" link next to them. Choose the files you want to delete and then have the customer try to download their zip files once more. When they do, they will get their up to date photos
</p>
</div>
<div style='clear:both;'></div>
<div style='float:left;border: 1px solid #aaa;padding: 20px;'>
		Tour ID:
		<input type='text' id='tourId'><button onClick='search()'>Search</button><br>
		<div id='output'>

		</div>
</div>
</body></html>
