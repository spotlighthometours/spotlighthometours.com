<?php
/**********************************************************************************************
Document: affiliate-program/index.php
Creator: Jacob Edmond Kerr
Date: 12-14-2015
Purpose: SpotlightHomeTours.com - Affiliate Program
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	$title = 'Spotlight | Affiliate Photographer Program';
	$header = '<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css"><link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css"><script src="../repository_inc/affiliate-program.js"></script><link rel="stylesheet" type="text/css" href="../repository_css/affiliate-program.css"/>';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// Objects
//=======================================================================
	
//=======================================================================
// Document
//=======================================================================
	
?>
<div class="affiliate-program">
	<div class="banner">
		
		<div class="cta" onclick="javascript:window.location='http://www.spotlighthometours.com/register/affiliate.php'"><div style="margin-top:-25px; font-size:16px; color:white; font-style:italic;">For more information please:</div></div>
        <div class="intro-text">Spotlight Home Tours is seeking highly qualified photographers/ videographers all over the country to partner with our corporate office, to provide quality and cutting edge real estate photography/video tours.</div>
	</div>
	<div class="features">
		<ul>
			<li>
            	Photo & Video Editing
            	<span>All photo and video editing can be<br />done by our corporate office.</span>
            </li>
			<li></li>
            <li>
            	Opportunity for Training
            	<span>Learn to shoot video, floor plans,<br/>3D, aerial, etc.</span>
            </li>
			<li>
            	Personalized Tour Pricing
            	<span>Create pesonalized pricing packages to<br/>remain competitive in your area.</span>
            </li>
		</ul>
		<ul>
			<li>
            	Interactive Tour Window
            	<span>Displays still photos, motion scenes, <br/>videos, floors plans, 3D tours, etc.</span>
            </li>
			<li></li>
            <li>
            	Marketing & Sales Support
            	<span>Marketing & Sales support available from<br/>our corporate office.</span>
            </li>
			<li>
            	Affiliate Dashboard
            	<span>The Affiliate Dashboard will help you <br/>manage your clients, orders & scheduling.</span>
            </li>
		</ul>
		<div class="clear"></div>
	</div>
    <br/>
	<iframe width="677" height="381" src="https://www.youtube.com/embed/fspygqvu0q8" frameborder="0" allowfullscreen style="display:block; margin:auto;"></iframe>
    <br/><br/>
    <div class="image-reel-pattern"></div>
	<div class="featured-content" style="width:100%;">
		<div class="column4" style="width:100%;padding-top:40px;">
			<div class="free-trial-btn" id="affiliateFrame" style="position:relative;" onClick="javascript:window.location='http://www.spotlighthometours.com/register/affiliate.php'"><div style="position:absolute;top:-25px; font-size:16px; color:white; font-style:italic;">For more information please:</div></div>
		</div>
		<div class="clear"></div>
	</div>
    <div class="clear"></div>
	<div class="affiliate-program-content">
	</div>
</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>

<!-- Begin GOOGLE ANALYTICS -->

<script type="text/javascript">

  var _gaq = _gaq || [];

_gaq.push(function() {
  var pageTracker = _gat._getTrackerByName();
  var iframe = document.getElementById('affiliateFrame');
  iframe.src = pageTracker._getLinkerUrl('http://www.spotlighthometours.com/register/affiliate.php');
});

  _gaq.push(['_setAccount', 'UA-20874682-1']);
  _gaq.push(['_trackEvent', 'affiliate', 'REGISTER']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<!-- END GOOGLE ANALYTICS -->