<?php
/**********************************************************************************************
Document: concierge/youtube.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-07-16
Purpose: Spotlight's Concierge YouTube signup page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight | Concierge YouTube Setup';
	$header = '
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/youtube.css"/>
';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="concierge-set-up">
		<div id="setup"><strong>Step 5:</strong> Setup your YouTube Accounts and Settings</div>
		<div id="concierge-line"><img src="images/concierge-line.png" alt="line" /></div>
		<div id="concierge-logo"><img src="images/concierge-logo.png" alt="logo" /></div>
		<div id="topbar-logo">
			<div id="youtube-logo"><img src="images/youtube.png" alt="youtube" width="150px"/></div>
		</div>
		<div id="topbar-text">
			<div id="youtube-manager">YouTube Manager</div>
			<div id="youtube-text">This page allows you to add, remove and modify your YouTube accounts. You may add YouTube accounts for posting your tour's videos to and also select whether you would like the system to autopost your videos to YouTube or if the system will wait for you to manually post them to YouTube.</div>
		</div>
		<div id="concierge-line2"> <img src="images/concierge-line.png" alt="line" /> </div>
		<div id="youtube-frame">
			<iframe height="700" width="900" src="http://www.spotlighthometours.com/users/new/youtube-include.php?settings=true"></iframe>
		</div>
		<div id="previous-step"><a href="social-hub.php"><img src="images/previous-step.png" alt="previous-step" width="150px"/></a></div>
		<div id="save-exit"><a href="set-up.php"><img src="images/save-exit.png" alt="save-exit" width="150px"/></a></div>
		<div id="next-step"><a href="spotlight-preview.php"><img src="images/next-step.png" alt="next-step" width="150px"/></a></div>
		<br />
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>