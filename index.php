<?PHP
	// code to check cbrbhome sub-domains and redirecting.
	include("repository_inc/data.php");
	mysql_connect($server, $username, $password) or die(mysql_error());
	mysql_select_db($database) or die(mysql_error());
	
	$host = "$_SERVER[HTTP_HOST]";
	$host_names = explode(".", $host);
	
	$server_name =trim($_SERVER['SERVER_NAME']);
	$host_names = explode(".", $server_name);
	$bottom_host_name = $host_names[count($host_names)-2] . "." . $host_names[count($host_names)-1];

	if (is_array($host_names) && count($host_names) > 1 && trim($host_names[1]) == "cbrbhome")
	{
		$sub_domain = $host_names[0];
		$sub_domain = trim($sub_domain);
		$sub_domain = str_replace(" ", "", $sub_domain); // replacing space to prevent sql injection
		
		if (empty($sub_domain))
			break;

		$sql = "SELECT tourID
				FROM tour_subdomain
				WHERE sub_domain = '$sub_domain'";

		$rs = mysql_query($sql) or die(mysql_error());
		if (mysql_num_rows($rs) > 0)
		{
			$r = mysql_fetch_array($rs);
			$tour_id = $r['tourID'];

			if (!$tour_id)
				break;

			$redirect_url = "http://{$host}/tours/tour.php?tourid={$tour_id}";
			echo "<meta http-equiv='refresh' content='0;url={$redirect_url}'>";
			exit;
		}
		
	}
	
	$sql_userDomainUrl = "SELECT user_id, url_address
				FROM microsite_domain_url
				WHERE url_address = '{$bottom_host_name}'
					OR url_address = CONCAT('www.','{$bottom_host_name}')
					OR url_address = CONCAT('http://www.','{$bottom_host_name}')
					OR url_address = CONCAT('http://','{$bottom_host_name}')
				";
	$results = mysql_query($sql_userDomainUrl) or die(mysql_error());
		if (mysql_num_rows($results) > 0)
		{
			$result = mysql_fetch_array($results);
			header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: microsites/agent.php?userID={$result['user_id']}");
			echo "<script>window.location='microsites/agent.php?userID={$result['user_id']}'</script>";
		}
		
	if($_SERVER['SERVER_NAME'] == "kylenorthup.synergysir.com" || $_SERVER['SERVER_NAME'] == "www.kylenorthup.synergysir.com"){
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: microsites/agent.php?userID=40954");
		echo "<script>window.location='microsites/agent.php?userID=40954'</script>";
	}
	if($_SERVER['SERVER_NAME'] == "kylenorthup.synergysir.com/eng" ){
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: microsites/agent.php?userID=40954");
		echo "<script>window.location='microsites/agent.php?userID=40954'</script>";
	}
	$checkVar = "true";

?>

<?php
/**********************************************************************************************
Document: index.php
Creator: Jacob Edmond Kerr
Date: 01-16-12
Purpose: SpotlightHomeTours.com Home Page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight | Real Estate Virtual Home Tours';
	$header = '
<meta http-equiv="cache-control" content="no-cache" />
<meta name="google-site-verification" content="Yh7Agqxo_Kd36-R8aHiW-tGN_V4SxLdELb6VcmBSnUw" />
<meta name="google-site-verification" content="q97smJXKLf7QNzRfSN3Aba6PiuadNmbvMjXqtqQlDkc" />
<link rel="stylesheet" type="text/css" href="repository_css/home.css?randIt='.$homeRandNum.'"/>
<script src="repository_inc/slides.jquery.js?randIt='.$homeRandNum.'"></script>
<script src="repository_inc/jquery.smoothDivScroll-1.1-min.js?randIt='.$homeRandNum.'"></script>
<script src="repository_inc/home.js?randIt='.$homeRandNum.'"></script>
<script src="repository_inc/flowplayer/flowplayer-3.2.6.min.js?randIt='.$homeRandNum.'"></script>
';

	require_once('repository_inc/template-header.php');	
	
//=======================================================================
// Document
//=======================================================================

/*
if($_SERVER['SERVER_NAME'] == "lorijacksonrealty.com" || $_SERVER['SERVER_NAME'] == "www.lorijacksonrealty.com"){
	echo "<script>window.location='microsites/agent.php?userID=125'</script>";
}
if($_SERVER['SERVER_NAME'] == "thehiattgroup.pro" || $_SERVER['SERVER_NAME'] == "www.thehiattgroup.pro"){
	echo "<script>window.location='microsites/agent.php?userID=21289'</script>";
}
if($_SERVER['SERVER_NAME'] == "www.saltlakecitylisting.com" || $_SERVER['SERVER_NAME'] == "saltlakecitylisting.com"){
	echo "<script>window.location='microsites/agent.php?userID=908'</script>";
}
if($_SERVER['SERVER_NAME'] == "www.sylviafarrerrealtor.com" || $_SERVER['SERVER_NAME'] == "sylviafarrerrealtor.com"){
	echo "<script>window.location='microsites/agent.php?userID=2281'</script>";
}
if($_SERVER['SERVER_NAME'] == "www.cameron-wood.com" || $_SERVER['SERVER_NAME'] == "cameron-wood.com"){
	echo "<script>window.location='microsites/agent.php?userID=12393'</script>";
}

if($_SERVER['SERVER_NAME'] == "imagineutahhomes.com" || $_SERVER['SERVER_NAME'] == "www.imagineutahhomes.com" ||
	$_SERVER['SERVER_NAME'] == "janetandcindi.com" || $_SERVER['SERVER_NAME'] == "www.janetandcindi.com" ||
	$_SERVER['SERVER_NAME'] == "imaginerealestatehomes.com" || $_SERVER['SERVER_NAME'] == "www.imaginerealestatehomes.com" ||
	$_SERVER['SERVER_NAME'] == "southjordanutahhome.com" || $_SERVER['SERVER_NAME'] == "www.southjordanutahhome.com" ||
	$_SERVER['SERVER_NAME'] == "homesdraperutah.com" || $_SERVER['SERVER_NAME'] == "www.homesdraperutah.com" ||
	$_SERVER['SERVER_NAME'] == "cottonwoodheightsutahhomes.com" || $_SERVER['SERVER_NAME'] == "www.cottonwoodheightsutahhomes.com" ||
	$_SERVER['SERVER_NAME'] == "sandyutahhome.com" || $_SERVER['SERVER_NAME'] == "www.sandyutahhome.com" ||
	$_SERVER['SERVER_NAME'] == "cindihowell.com" || $_SERVER['SERVER_NAME'] == "www.cindihowell.com" ||
	$_SERVER['SERVER_NAME'] == "janeteakin.com" || $_SERVER['SERVER_NAME'] == "www.janeteakin.com" ||
	$_SERVER['SERVER_NAME'] == "cindiandjanet.com" || $_SERVER['SERVER_NAME'] == "www.cindiandjanet.com" ||
	$_SERVER['SERVER_NAME'] == "utahhomesbycindi.com" || $_SERVER['SERVER_NAME'] == "www.utahhomesbycindi.com" ||
	$_SERVER['SERVER_NAME'] == "utahhomesbyjanet.com" || $_SERVER['SERVER_NAME'] == "www.utahhomesbyjanet.com"){
	echo "<script>window.location='microsites/agent.php?userID=28888'</script>";
}

if($_SERVER['SERVER_NAME'] == "www.carolyntriptow.com" || $_SERVER['SERVER_NAME'] == "carolyntriptow.com"){
	echo "<script>window.location='microsites/agent.php?userID=28940'</script>";
}

if($_SERVER['SERVER_NAME'] == "www.angeliquerealty.com" || $_SERVER['SERVER_NAME'] == "angeliquerealty.com" || $_SERVER['SERVER_NAME'] == "http://angeliquerealty.com/"){
	echo "<script>window.location='microsites/agent.php?userID=28618'</script>";
}
if($_SERVER['SERVER_NAME'] == "www.rockerrealtor.com" || $_SERVER['SERVER_NAME'] == "rockerrealtor.com"){
	echo "<script>window.location='microsites/agent.php?userID=28956'</script>";
}

if($_SERVER['SERVER_NAME'] == "paarbuyerservices.com" || $_SERVER['SERVER_NAME'] == "www.paarbuyerservices.com"){
	echo "<script>window.location='microsites/agent.php?userID=28972'</script>";
}*/

?>
	<!-- Popup Videos -->
	<iframe src="" width="900" height="507" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen class="popup-video" style="display:none;"></iframe>
	<div class="modal-bg" onClick="hideVideo()"></div>
	<!-- End Popup Videos -->
	<div class="slides" id="slides">
		<div style="display:none;">variable_check - <?php echo $checkvar; ?></div>
		<div class="slides_container">
			<div class="slide-1">
				<div class="content">
                	Over the past decade <span>Spotlight Home Tours</span> has been the trusted name for photography, video production and marketing solutions.
                </div>
                <div class="photo-port" onclick="window.location='products/architectural-photography.php?showPort=true'"><span>Photo Portfolio</span></div>
                <div class="video-port" onclick="window.location='tour-demos/cinematic-video.php'"><span>Video Portfolio</span></div>
			</div>
			<div class="slide-2">
				<div class="slide-content">
					<div class="title-bar">
						<div class="bar"></div>
						<div class="bar-title">Cinematic Videos</div>
						<div class="clear"></div>
					</div>
					<div class="header">Get the Cinematic</div>
					<img src="repository_images/new/banners/text/experience.png" width="170" height="41" alt="experience" />
					<div class="text">Spotlight’s new cinematic tour line focuses on the property’s unique features to help your client not only see and feel the lifestyle.  </div>
					<div style="margin-top:25px;">
						<div class="blue-btn left" onClick="window.location='tour-demos/cinematic-video.php'">View all Demos</div>
					</div>
				</div>
			</div>
            <div class="slide-3">
				<div class="slide-content">
					<div class="title-bar">
						<div class="bar" style="width:148px;"></div>
						<div class="bar-title">Architectural Photography</div>
						<div class="clear"></div>
					</div>
					<div class="header">Photography that</div>
					<img src="repository_images/new/banners/text/captures-emotion.png" alt="captures emotion" />
					<div class="text" style="margin-top:50px;">The argument can be made that in today's market, curb appeal has been replaced by web appeal.  Spotlight's photography style is centered around Architectural style photography that drives web appeal.</div>
					<div style="margin-top:20px;">
						<div class="blue-btn left" onClick="window.location='products/architectural-photography.php?showPort=true'">See our portfolio</div>
						<div class="blue-btn right" onClick="viewVidDemoTour(29760)">Watch a Motion Tour</div>
					</div>
				</div>
			</div>
            <div class="slide-4">
				<div class="slide-content">
					<div class="header">Get connected.</div>
					<img src="repository_images/new/banners/text/all-in-one-spot.png" alt="all in one spot" />
					<div class="text" style="margin-top:50px;">Social Hub lets you communicate and manage each one of your social network accounts with one simple tool.</div>
                    <div style="margin-top:20px;">
						<div class="blue-btn left" onClick="window.location='/social-hub/'">Learn More</div>
						<div class="blue-btn right" onClick="<?PHP echo (isset($_SESSION['user_id'])) ? 'socialHubBuyNow('.$_SESSION['user_id'].')' : 'window.location=\'/social-hub/?getStarted=true\''; ?>">Get Started!</div>
					</div>
				</div>
			</div>
			<div class="slide-5">
				<div class="slide-content">
					<div class="title-bar">
						<div class="bar" style="width:182px;"></div>
						<div class="bar-title">Do-it-Yourself Tours</div>
						<div class="clear"></div>
					</div>
					<div class="header">Build tours catered to</div>
					<img src="repository_images/new/banners/text/your-own-taste.png" alt="your own taste" />
					<div class="text" style="margin-top:50px;">Using Spotlight’s dynamic tour builder, you can create your own motion slide shows by deciding which photos and music to play.</div>
					<div style="margin-top:20px;">
						<div class="blue-btn left" onClick="showVideo('http://player.vimeo.com/video/37773600?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1')">Watch Video</div>
						<div class="blue-btn right" onClick="window.location='diy/'">Learn more here</div>
					</div>
				</div>
			</div>
			<div class="slide-6">
				<div class="slide-content">
					<div class="title-bar">
						<div class="bar" style="width:220px;"></div>
						<div class="bar-title">Virtual Staging</div>
						<div class="clear"></div>
					</div>
					<div class="header">No furniture?</div>
					<img src="repository_images/new/banners/text/no-problem.png" alt="no problem" />
					<div class="text" style="margin-top:50px;">Spotlight’s Virtual Staging feature now allows a vacant room to be filled with photorealistic furniture.</div>
					<div style="margin-top:20px;">
						<div class="blue-btn left" onClick="window.location='products/virtual-staging.php'">Learn more</div>
					</div>
				</div>
			</div>
			<div class="slide-7">
				<div class="slide-content">
					<div class="title-bar">
						<div class="bar" style="width:215px;"></div>
						<div class="bar-title">Mobile Preview</div>
						<div class="clear"></div>
					</div>
					<div class="header">Capture and manage</div>
					<img src="repository_images/new/banners/text/reliable-leads.png" alt="reliable leads" />
					<div class="text" style="margin-top:50px;">A perfect lead generation tool for both the buyer and seller agents. Lets clients search any listing on the MLS with your unique keyword.</div>
					<div style="margin-top:20px;">
						<div class="blue-btn left">Coming Soon</div>
					</div>
				</div>
			</div>
			<div class="slide-8">
				<div class="slide-content">
					<div class="title-bar">
						<div class="bar" style="width:226px;"></div>
						<div class="bar-title">Mobile Ready</div>
						<div class="clear"></div>
					</div>
					<div class="header">Universal access</div>
					<img src="repository_images/new/banners/text/to-your-listings.png" alt="to your listings" />
					<div class="text" style="margin-top:50px;">All of our virtual home tours are mobile ready and can be accessed with any mobile device such as iPad, iPhone and Android.</div>
				</div>
			</div>
		</div>
	</div>
	<div class="image-reel-pattern"></div>
	<div class="icon-slide">
		<div class="scrollingHotSpotLeft"></div>
		<div class="scrollingHotSpotRight"></div>
		<div class="scrollWrapper">
			<div class="scrollableArea">
				<ul class="icon-slide">
					<li> <a href="products/architectural-photography.php" title="Architectural Photography"> <img src="repository_images/new/icons/architectural-photography-lrg.jpg" width="160" height="130" /> Architectural Photography </a> </li>
                	<!-- <li> <a href="products/marketing-platform.php" title="Marketing Platform"> <img src="repository_images/new/icons/marketing-platform-lrg.jpg" width="160" height="130" /> Marketing Platform </a> </li> -->
					<li> <a href="products/cinematic-video.php" title="Cinematic Video"> <img src="repository_images/new/icons/cinematic-video-lrg.jpg" width="160" height="130" /> Cinematic Video </a> </li>
                    <li> <a href="concierge/"> <img src="repository_images/new/icons/concierge-lrg.jpg" title="Spotlight Concierge" width="160" height="130" /> Concierge Marketing Platform</a> </li>
                    <li> <a href="realtor-websites/" title="Realtor Websites"> <img src="repository_images/new/icons/realtor-websites-lrg.jpg" width="160" height="130" /> Realtor Websites </a> </li>
					<li> <a href="social-compass/" title="Social Compass"> <img src="repository_images/new/icons/social-compass-lrg.jpg" width="160" height="130" /> Social Compass </a> </li>
                    <li> <a href="products/3d-home-tours.php" title="3D Home Tours"> <img src="repository_images/new/icons/3d-motion-lrg-home.jpg" width="160" height="130" /> 3D Home Tour </a> </li>
					<li> <a href="social-hub/"> <img src="repository_images/new/social_hub/social_hub_logo.png" title="Social Hub" width="160" height="130" /> Social Hub </a> </li>
					<li> <a href="diy/index.php"> <img src="repository_images/new/icons/diy-lrg.jpg" title="Do-it-yourself Tours" width="160" height="130" /> Do-it-yourself Tours </a> </li>
                    <li> <a href="#"> <img src="repository_images/new/icons/mobile-preview-lrg.jpg" title="Mobile Preview" width="160" height="130" /> Mobile Preview </a> </li>
					<li> <a href="products/virtual-staging.php"> <img src="repository_images/new/icons/virtual-staging-lrg.jpg" title="Virtual Staging" width="160" height="130" /> Virtual Staging </a> </li>
					<li> <a href="products/3d-motion-effects.php"> <img src="repository_images/new/icons/3d-motion-lrg.jpg" width="160" height="130" title="3D Motion" /> 3D Motion </a> </li>
				</ul>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('repository_inc/template-footer.php');
?>

