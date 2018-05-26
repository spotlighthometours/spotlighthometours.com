<?php
/**********************************************************************************************
Document: checkout_tour_preview.php
Creator: Brandon Freeman
Date: 02-15-11
Purpose: Template for the tours on step-2.
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================
	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();
	
//=======================================================================
// Document
//=======================================================================

	$price = "";
	if (isset($_POST['price'])) {
		$price = $_POST['price'];
	} elseif (isset($_GET['price'])) {
		$price = $_GET['price'];
	} else {
		$price = "129.00";
	}

	$name = "";
	if (isset($_POST['name'])) {
		$name = $_POST['name'];
	} elseif (isset($_GET['name'])) {
		$name = $_GET['name'];
	} else {
		$name = "Really Generic Tour";
	}
	
	$icon = "";
	if (isset($_POST['icon'])) {
		$icon = $_POST['icon'];
	} elseif (isset($_GET['icon'])) {
		$icon = $_GET['icon'];
	} else {
		$icon = "../repository_thumbs/tour_icons/demo1.png";
	}
	
	$description = "";
	if (isset($_POST['description'])) {
		$description = $_POST['description'];
	} elseif (isset($_GET['description'])) {
		$description = $_GET['description'];
	} else {
		$description = "Something Really Awesome!";
	}
	
	$tagline = "";
	if (isset($_POST['tagline'])) {
		$tagline = $_POST['tagline'];
	} elseif (isset($_GET['tagline'])) {
		$tagline = $_GET['tagline'];
	} else {
		$tagline = "If it ain't generic, it ain't nothing!";
	}

	$tour = array('tourTypeID' => 0, 'tourTypeName' => $name, 'tagline' => $tagline, 'description' => $description, 'iconImage' => $icon);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Spotlight Home Tours Admin - Tour Types</title>
		<link REL="SHORTCUT ICON" HREF="admin_images/icon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">@import "../repository_css/checkout.css";</style>
	</head>
	<body>
		<div id="mainframe" >
			
			<!--- Main Area --->
			<div id="mainslice" >
				
				<!--- Shadows --->
				<div id="leftshadow" ></div>
				<div id="rightshadow" ></div>
                <?php
                    if( isset($_REQUEST['newPreview']) ){
                        require_once("checkout_new_preview.php");
                    }else{
				        require_once('checkout_tour_template.php'); 
                    }
                ?>
			</div>
		</div>
	</body>
</html>
