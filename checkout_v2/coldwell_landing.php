<?php
/**********************************************************************************************
Document: coldwell_landing.php
Creator: Jacob Edmond Kerr
Date: 06-11-12
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================
	
	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	
//=======================================================================
// Document
//=======================================================================

	// Rand to force fresh download of needed inc files
	$randIt = rand(100, 99999999);
		
	$title = 'Create New Tour';
	$header = '
			<link rel="stylesheet" media="all" href="checkout.css?randIt='.$randIt.'">
			<script src="../repository_inc/jquery-1.5.min.js" type="text/javascript"></script> <!--- jQuery --->
			<script type="text/javascript" src="../repository_inc/template.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="checkout.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="checkout_step1.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="coldwell_landing.js?randIt='.$randIt.'"></script>
			<script>
				checkUnload = false;
			</script>
			<style>
				.tour_package .price_frame .notice{
					position: relative;
					width: 110px;
					font-size: 12px;
					color: #333;
					text-align: center;
					margin-bottom: 5px;
					margin-top:-10px;
					margin-right:-10px;
				}
			</style>
	';
	$page = true;	
	require_once('../repository_inc/template_header.php');
?>

<div class="main_frame" style="padding-top:40px;">
	<div id="step_2" class="step_frame" style="display: block;">
		<div id="tour_packages">
			<div class="package_section">
				<div class="cap left"></div>
				<div class="body">Coldwell Banker Photo and Tour Packages</div>
				<div class="cap right"></div>
			</div>
			<div class="tour_package ">
				<div class="icon_frame">
					<div class="icon" style="background-image: url(../repository_thumbs/tour_icons/still02.png);"></div>
				</div>
				<div class="text_frame"> <span class="title_text">15 Still Photos -- CB1 </span><br>
					<span class="tag_text">CB1 Marketing Photos</span><span class="descrip_text"><br>
					<br>
					Includes: <br />
					- 
					15 Still Photos <br>
					</span> </div>
				<div class="price_frame">
					<div class="notice">Covered by your CB1 Form</div>
					<div class="price">$0.00</div>
					<div id="tt_39" class="button_new button_tour button_sm" onclick="GetColdwellLoginScreen();">
						<div class="curve curve_left"></div>
						<span class="button_caption">Select</span>
						<div class="curve curve_right"></div>
					</div>
				</div>
			</div>
			<div class="tour_package ">
				<div class="icon_frame">
					<div class="icon" style="background-image: url(../repository_thumbs/tour_icons/still06.png);"></div>
				</div>
				<div class="text_frame"> <span class="title_text">25 Still Photos -- CB2</span><br>
					<span class="tag_text">CB2 Marketing Photos</span><span class="descrip_text"><br>
					<br>
					Includes:<br />
					- 25 Still Photos<br />
					- Virtual tour window  ( with branded &amp; non branded links )<br />
					</span> </div>
				<div class="price_frame">
					<div class="notice">Covered by your CB2 Form</div>
					<div class="price" style="margin-bottom:0px;">$0.00</div>
					<div class="button_new button_tour button_sm" onclick="openPopup('http://www.spotlighthometours.com/tours/tour.php?demo=true&tourid=28153', 980, 730);">
						<div class="curve curve_left"></div>
						<span class="button_caption">View Demo</span>
						<div class="curve curve_right"></div>
					</div>
					<div id="tt_48" class="button_new button_tour button_sm" onclick="GetColdwellLoginScreen();">
						<div class="curve curve_left"></div>
						<span class="button_caption">Select</span>
						<div class="curve curve_right"></div>
					</div>
				</div>
			</div>
			<div class="tour_package ">
				<div class="icon_frame">
					<div class="icon" style="background-image: url(../repository_thumbs/tour_icons/motion05.png);"></div>
				</div>
				<div class="text_frame"> <span class="title_text" style="font-size:29px !important;">Motion Photo Tour CB3 Previews</span><br />
					<span class="tag_text">An emphasis on capturing emotion</span><span class="descrip_text">
					<div style="margin-bottom:10px;"></div>
					Includes: <br />
					- 25 Still Photos<br />
					- 4 Motion Scenes of different rooms<br />
					- Virtual tour window  ( with branded &amp; non branded links )</span></div>
				<div class="price_frame">
					<div class="notice">Covered by your CB3 Form</div>
					<div class="price" style="margin-bottom:0px;">$0.00</div>
					<div class="button_new button_tour button_sm" onclick="openPopup('http://www.spotlighthometours.com/tours/tour.php?tourid=10107', 980, 730);">
						<div class="curve curve_left"></div>
						<span class="button_caption">View Demo</span>
						<div class="curve curve_right"></div>
					</div>
					<div id="tt_" class="button_new button_tour button_sm" onclick="GetColdwellLoginScreen();">
						<div class="curve curve_left"></div>
						<span class="button_caption">Select</span>
						<div class="curve curve_right"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?PHP	
	include('../repository_inc/html/modal.html');
	
	require_once('../repository_inc/template_footer.php');

?>
