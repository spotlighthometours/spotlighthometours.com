<?php
/**********************************************************************************************
Document: concierge/social-hub.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-09-16
Purpose: Spotlight's Concierge Social Hub setup page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight | Concierge Social Hub Setup';
	$header = '
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/social-hub.css"/>
';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="concierge-set-up">
		<div id="setup"><strong>Step 4: </strong> Setup your Social Hub</div>
		<div id="concierge-line"><img src="images/concierge-line.png" alt="line" /></div>
		<div id="concierge-logo"><img src="images/concierge-logo.png" alt="logo" /></div>
		<div id="topbar-logo">
			<div id="social-hub-logo"><img src="images/social-hub.png" alt="social-hub" width="150px"/></div>
		</div>
		<div id="topbar-text">
			<div id="social-hub-manager">Social Hub Manager</div>
			<div id="social-hub-text">Social Hub lets you communicate and manage each one of your social network accounts with on simple tool.</div>
		</div>
		<div id="concierge-line2"> <img src="images/concierge-line.png" alt="line" /> </div>
		<div id="social-hub-frame">
			<iframe height="900" width="900" src="http://www.spotlighthometours.com/users/new/social-hub-include.php?settings=true"></iframe>
		</div>
		<div id="previous-step"><a href="microsite.php"><img src="images/previous-step.png" alt="previous-step" width="150px"/></a></div>
		<div id="save-exit"><a href="set-up.php"><img src="images/save-exit.png" alt="save-exit" width="150px"/></a></div>
		<div id="next-step"><a href="youtube.php"><img src="images/next-step.png" alt="next-step" width="150px"/></a></div>
		<br />
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>