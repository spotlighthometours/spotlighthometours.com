<?php
/**********************************************************************************************
Document: about-us.php
Creator: Jacob Edmond Kerr
Date: 03-04-12
Purpose: SpotlightHomeTours.com - About Us
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	$title = 'About Spotlight Home Tours';
	$header = '<link rel="stylesheet" type="text/css" href="repository_css/splash.css"/>
<link rel="stylesheet" type="text/css" href="repository_css/about-us.css"/>
';
	require_once('repository_inc/template-header.php');
	
//=======================================================================
// Objects
//=======================================================================
	
//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="page-intro">
		<h1>About Us</h1>
		<p>Text</p>
		<div class="clear"></div>
	</div>
	<div class="image-reel-pattern"></div>
	<div class="page-content">
		<div class="main-column left">
			<div class="details">
				<h2 class="strip-top-marg">Text</h2>
			</div>
		</div>
		<div class="side-column right">
<?PHP
	include("repository_inc/template-side-column.php");
?>
		</div>
		<div class="clear"></div>
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('repository_inc/template-footer.php');
?>