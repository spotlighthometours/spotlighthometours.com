<?php
/**********************************************************************************************
Document: concierge/index.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-08-16
Purpose: Spotlight's Concierge Splash page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight Concierge';
	$header = '
<link rel="stylesheet" type="text/css" href="tooltipster/css/tooltipster.bundle.min.css"/>
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/splash_dev.css"/>
<link rel="stylesheet" type="text/css" href="css/animate.css"/>
<style>
	@media only screen and (max-width: 65.5em){
		video{
			display:none;
		}
	}
</style>
';
	require_once('../repository_inc/template-header_conierge_homepage.php');
	
	$userID = 0;
	$conciergeMember = false;
	if(isset($_SESSION['user_id'])){
		$userID = $_SESSION['user_id'];
		$members = new members(11, 'user', $userID);
		if($members->userActive()){
			$conciergeMember = true;
		}
		$members->membershipID = 7;
		if($members->userActive()){
			$conciergeMember = true;
		}
		$members->membershipID = 6;
		if($members->userActive()){
			$conciergeMember = true;
		}
		$members->membershipID = 5;
		if($members->userActive()){
			$conciergeMember = true;
		}
		$members->membershipID = 4;
		if($members->userActive()){
			$conciergeMember = true;
		}
	}
	
//=======================================================================
// Document
//=======================================================================
	
?>
</div>
<div class="concierge-splash">
	<div class="banner" id="concierge-banner">
		<!-- <a id="introVideo" class="fullscreen-video mb_YTVPlayer" data-property="{videoURL:'https://www.youtube.com/watch?v=EfoQj6tr3LY', quality: 'highres',autoPlay:true,mute:false,loop:true}"  style="display: none; background: none;"></a> -->
		<video autoplay preload="auto" loop>
          <source src="images/splash/concierge-background.mp4" type="video/mp4">
          <source src="images/splash/concierge-background.ogv" type="video/ogg">
        </video>
		<div class="intro">
			<img src="images/splash/conceirge-logo.png" width="280" />
			<h1>LET US MAKE YOUR JOB EASIER</h1>
            <table border="0" cellspacing="0" cellpadding="0" align="center">
  <tbody>
    <tr>
      <td><a href="concierge-checkout-v2.php" class="cta tada animated" role="button" style="width:180px">PLANS & PRICING</a></td>
     </tr>
     <tr> 
     	<td><a  href="#concierge-platinum" class="cta tada animated" role="button" onclick="showVideo('https://www.spotlighthometours.com/tours/video-player.php?type=video&id=2577968&autoPlay=true');" style="width:180px">WATCH VIDEO</a></td>
    </tr>
  </tbody>
</table>

		</div>
	</div>
    <div class="concierge-platinum" id="concierge-platinum">
    	<div class="center-box">
        	<div class="tooltip info" title="Did you know 92% of homebuyers start their search online? Catch leads right when they are looking for a new home by advertising on Google."></div>
    <div class="tooltip info advertising-info" title="Say goodbye to aimlessly mailing flyers and hoping for a lead. With Facebook, you can reach your ideal audience and generate higher quality leads by targeting based on age, location, household income, interests and more."></div>
    <div class="tooltip info drip-info" title="You know what they say, “the fortune is in the follow up!” Use an email drip campaign to stay in contact with your client list - without the time commitment. Simply send us your contact list, and we will take it from there."></div>
    <div class="tooltip info marketing-info" title="Reach people where they spend their time - on their phones! With mobile app marketing, you can show ads to people while they are playing games, using apps, or surfing the internet on their mobile devices."></div>
    
            <a href="#concierge-platinum" class="video-bio-button"  onclick="showVideo('https://www.spotlighthometours.com/tours/video-player.php?type=video&id=1133991&autoPlay=true');">Demo</a>
            <a href="#concierge-platinum" class="logo-amination-button"  onclick="showVideo('https://www.spotlighthometours.com/tours/video-player.php?type=video&id= 2574577&autoPlay=true');" >Demo</a>
            <a href="#concierge-platinum" class="book-end-button"  onclick="showVideo('https://www.spotlighthometours.com/tours/video-player.php?type=video&id=2573572&autoPlay=true');">Demo</a>
            <a href="#concierge-platinum" class="realtor-website-button"  onclick="agentWebsite('https://www.spotlighthometours.com/microsites/agent.php?userID=15');">Demo</a>
        </div>
    </div>
	<div class="agent-sites">
		<div class="content">
			<div class="screen">
				<img src="images/splash/macbook.png" />
				<iframe src="http://www.spotlighthometours.com/microsites/agent.php?userID=15" width="1010" height="630" scrolling="no"></iframe>
			</div>
			<div class="desc">
				<h2>Realtor Websites</h2>
				<h3>Full IDX Integration</h3>
				Our custom designed websites feature moving backgrounds and an easy to use MLS search for your clients.
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="social-hub">
		<div class="content">
			<div class="desc">
				<h2 style="margin-left:50px">Automated <span style="font-size:38px !important">Social Media</span></h2>
				<h3 style="margin-left:80px">
					<img src="images/splash/conciere-text-icon.png"/>Hassle Free Posting; We do the <br />
					<span style="margin-left:35px">Work for You</span><br/>
					<img src="images/splash/conciere-text-icon.png"/>Branded Content Catered to<br />
					<span style="margin-left:35px">Your Audience</span><br/>
					<img src="images/splash/conciere-text-icon.png"/>Choose from Numerous  <br />
					<span style="margin-left:35px">Categories That Fit Your Lifestyle</span><br/>
					<img src="images/splash/conciere-text-icon.png"/>Increase Your Social Presence to<br />
					<span style="margin-left:35px">Generate Leads</span><br/>
					<span style="margin-left:270px"><img src="images/splash/social-compass.png" /></span>
				</h3>
			</div>
			<div class="icons">
				<img src="images/splash/automated-social-media.png" />
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="brochure-creator">
		<div class="content">
			<h2>Brochure Creator</h2>
			<h3>Create Your Brochures in a Few Easy Steps</h3>
			<div class="features">
				<div class="feature">
					<img src="images/splash/templates.png" /><br/>
					Choose one of our custom templates 
				</div>
				<div class="feature">
					<img src="images/splash/customize.png" /><br/>
					Customize the pictures and text  
				</div>
				<div class="feature">
					<img src="images/splash/export.png" /><br/>
					Download a PDF to print or share
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
    <div class="auto-tour">
		<h2>Automatic Tour Creation</h2>
		<h3>Have your own listing photos? We'll create a virtual tour for you!</h3>
		<div class="steps">
			<div class="step mls">
				<img src="images/splash/tour-creation-mls.png" width="130" />
				<div class="txt">Post your listing and photos to the MLS</div>
				<div class="arrow"><img src="images/splash/tour-creation-arrow.png" /></div>
			</div>
			<div class="step tour">
				<img src="images/splash/tour-creation-create.png" width="130" />
				<div class="txt">We'll create your virtual tour automatically</div>
				<div class="arrow"><img src="images/splash/tour-creation-arrow.png" /></div>
			</div>
			<div class="step update">
				<img src="images/splash/tour-creation-link.png" width="130" />
				<div class="txt">We link your virtual tour to the MLS</div>
				<div class="arrow"><img src="images/splash/tour-creation-arrow.png" /></div>
			</div>
			<div class="step hourly">
				<img src="images/splash/tour-creation-hourly.png" width="130" />
				<div class="txt">Hourly updates keep your tour info up to date</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="featured">
		<div class="content">
			<h2>Other Spotlight Concierge Features</h2>
			<div class="row1">
				<div class="feature">
					<img src="images/splash/youtube.png" /><br/>
					Create, edit and even overlay specific content on your video presentation
				</div>
				<div class="feature" style="margin-top:30px">
					<img src="images/splash/socialhub.png" /><br/>
					Manage each one of your social network accounts with one simple tool
				</div>
				<div class="feature">
					<img src="images/splash/spotlight-preview.png" /><br/>
					Lead Generation Give your clients all of the details using text messaging
				</div>
				<div class="feature">
					<img src="images/splash/microsite.png" /><br/>
					Create custom websites for your listings
				</div>
			</div>
			<div class="clear"></div>
			<div class="row2">
				<div class="feature">
					<img src="images/splash/mobile-tours.png" /><br/>
					Interactive tour windows that are compatible across all devices
				</div>
				<div class="feature" style="margin-top:55px">
					<img src="images/splash/eblast.png" /><br/>
					Create and send email blasts to all of your <br />clients
				</div>
				<!--<div class="feature">
					<img src="images/splash/syndication.png" /><br/>
					Send all of your listings to the top Real Estate sites automatically
				</div>-->
				<div class="feature">
					<img src="images/splash/seo.png" /><br/>
					Search Engine Optimization Improve your search engine results
				</div>
				<div class="feature">
					<img src="images/splash/virtual-tours.png" /><br/>
					Display your photos and videos on our interactive tour window
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="brokerages">
		<div class="content">
			<h2>Brokerages that use Spotlight Concierge</h2>
			<div class="brokerage-row1">
				<div class="brokerage"><img src="images/splash/keller-williams.png" /></div>
				<div class="brokerage remax"><img src="images/splash/remax.png" /></div>
				<div class="brokerage"><img src="images/splash/equity.png" /></div>
				<div class="clear"></div>
			</div>
			<div class="brokerage-row2">
				<div class="brokerage coldwell"><img src="images/splash/coldwell.png" /></div>
				<div class="brokerage"><img src="images/splash/berkshire.png" style="margin-top:-20px;"/></div>
				<div class="brokerage sotherbys"><img src="images/splash/sotherbys.png" /></div>
				<div class="clear"></div>
			</div>
			<div class="cta tada animated" role="button" onclick="location.href='concierge-checkout.php'" style="width:180px">PLANS & PRICING</div>
			<!--<div class="cta" style="visibility:hidden" onClick="<?PHP if($conciergeMember){ echo"window.location='set-up.php'"; }else{ echo ($userID>0)?'creatMembership()':'signupPopup()'; } ?>">PLANS & PRICING</div> -->
		</div>
	</div>
</div>
  <!-- Popup Videos -->
  <div class="popup-video">
    <iframe src="" width="900" height="507" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
  </div>
  <div class="modal-bg" onClick="hideVideo()"></div>
  <!-- End Popup Videos -->
<?PHP
	include($_SERVER['DOCUMENT_ROOT'].'/repository_inc/html/modal.html');
	$customChat = ( isset( $_REQUEST['customChat'] ) && !empty($_REQUEST['customChat']) )?true:false;
	if($customChat){
		include($_SERVER['DOCUMENT_ROOT'].'/repository_inc/html/ogg-chat.html');
	}
	include($_SERVER['DOCUMENT_ROOT'].'/repository_inc/html/google-analytics.html');
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.0.min.js"></script>
<script src="tooltipster/js/tooltipster.bundle.min.js"></script>
<!-- Video BG Loop --> 
<script src="../repository_inc/device.min.js" type="text/javascript"></script> 
<script src="../repository_inc/jquery.mb.YTPlayer.js" type="text/javascript"></script> 
<!-- End Video BG Loop --> 
<script src="js/splash.js?randNum=<?PHP echo $randNum ?>"></script>
</body>
</html>