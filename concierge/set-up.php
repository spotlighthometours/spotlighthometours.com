<?php
/**********************************************************************************************
Document: concierge/social-hub.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-09-16
Purpose: Spotlight's Concierge setup page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight | Concierge Setup';
	$header = '
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/set-up.css"/>
';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="concierge-set-up">
		<div id="setup">Setup your Concierge account:</div>
		<div id="concierge-line"><img src="images/concierge-line.png" alt="line" /></div>
		<div id="concierge-logo"><img src="images/concierge-logo.png" alt="logo" /></div>
		<div id="profile-logo"><a href="profile.php"><img src="images/profile.png" alt="profile" width="125px"/></a></div>
		<div id="tour-window-logo"><a href="tour-window.php"><img src="images/tour-window.png" alt="tour-window" width="125px"/></a></div>
		<div id="microsite-logo"><a href="microsite.php"><img src="images/microsite.png" alt="microsite" width="125px"/></a></div>
		<div id="social-hub-logo"><a href="social-hub.php"><img src="images/social-hub.png" alt="social-hub" width="125px"/></a></div>
		<div id="profile-text"><a href="profile.php">Profile</a></div>
		<div id="tour-window-text"><a href="tour-window.php">Tour Window</a></div>
		<div id="microsite-text"><a href="microsite.php">Microsite</a></div>
		<div id="social-hub-text"><a href="social-hub.php">Social Hub</a></div>
		<div id="youtube-logo"><a href="youtube.php"><img src="images/youtube.png" alt="youtube" width="125px"/></a></div>
		<div id="spotlight-preview-logo"><a href="spotlight-preview.php"><img src="images/spotlight-preview.png" alt="spotlight-preview" width="125px"/></a></div>
		<!--<div id="brochure-creator-logo"><a href="brochure-creator.php"><img src="images/brochure-creator.png" alt="brochure-creator" width="125px"/></a></div>-->
		<div id="listing-syndication-logo"><a href="listing-syndication.php"><img src="images/listing-syndication.png" alt="listing-syndication" width="125px"/></a></div>
		<div id="youtube-text"><a href="youtube.php">YouTube</a></div>
		<div id="spotlight-preview-text"><a href="spotlight-preview.php">Spotlight Preview</a></div>
		<!-- <div id="brochure-creator-text"><a href="brochure-creator.php">Brochure Creator</a></div> -->
		<div id="listing-syndication-text"><a href="listing-syndication.php">Lisitng Syndication</a></div>
		<!-- <div id="email-logo"><a href="email-blast.php"><img src="images/email.png" alt="email" width="125px"/></a></div>
		<div id="email-text"><a href="email-blast.php">Email Blast</a></div> -->
		<div id="set-up-later-button"><a href="http://www.spotlighthometours.com/users/new/"><img src="images/set-up-later-button.png" alt="social-hub" width="175px"/></a></div>
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>