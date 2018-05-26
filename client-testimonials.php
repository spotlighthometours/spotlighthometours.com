<?php
/**********************************************************************************************
Document: client-testimonials.php
Creator: Jacob Edmond Kerr
Date: 02-28-12
Purpose: SpotlightHomeTours.com - Client Testimonials
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	$title = 'Spotlight | Client Testimonials';
	$header = '<link rel="stylesheet" type="text/css" href="repository_css/splash.css"/>
<link rel="stylesheet" type="text/css" href="repository_css/testimonials.css"/>
';
	require_once('repository_inc/template-header.php');
	
//=======================================================================
// Objects
//=======================================================================

	// Create Instances of Needed Objects
	$testObj = new testimonials();
	$testimonials = $testObj->getTestimonials();

//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="page-intro">
		<h1>Client Testimonials</h1>
		<div class="clear"></div>
	</div>
	<div class="image-reel-pattern"></div>
	<div class="direction">Scroll down to see our client testimonials</div>
	<div class="page-content">
		<div class="main-column left">
			<div class="details">
<?PHP
	foreach($testimonials as $row => $column){
?>
				<h2 id="<?PHP echo $column['id'] ?>"><?PHP echo $column['name'] ?></h2>
				<i><p>"<?PHP echo $column['body'] ?>"</p></i>
				<hr />
<?PHP
	}
?>			
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