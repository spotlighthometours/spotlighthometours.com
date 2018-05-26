<?php
/**********************************************************************************************
Document: concierge/spotlight-preview.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-09-16
Purpose: Spotlight's Concierge Preview setup page 
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
<link rel="stylesheet" type="text/css" href="css/spotlight-preview.css"/>
';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="concierge-set-up">
		<div id="setup"><strong>Step 6:</strong> Setup your Spotlight Preview / Lead Generation</div>
		<div id="concierge-line"><img src="images/concierge-line.png" alt="line" /></div>
		<div id="concierge-logo"><img src="images/concierge-logo.png" alt="logo" /></div>
		<div id="topbar-logo">
			<div id="spotlight-preview-logo"><img src="images/spotlight-preview.png" alt="spotlight-preview" width="150px"/></div>
		</div>
		<div id="topbar-text">
			<div id="spotlight-preview">Spotlight Preview / Lead Generation</div>
			<div id="spotlight-preview-text">Spotlight Preview is a service that allows potential clients to receive pictures and info about your listing right to their mobile phone. Once they have requested info, Spotlight Preview saves their mobile phone number and allows you to send messages back to them right from your computer!</div>
		</div>
		<div id="concierge-line2"> <img src="images/concierge-line.png" alt="line" /> </div>
		<div id="preview-frame">
			<iframe height="900" width="900" src="http://www.spotlighthometours.com/users/new/preview-include.php?settings=true&section=autoResponse"></iframe>
		</div>
		<div id="previous-step"><a href="youtube.php"><img src="images/previous-step.png" alt="previous-step" width="150px"/></a></div>
		<div id="save-exit"><a href="set-up.php"><img src="images/save-exit.png" alt="save-exit" width="150px"/></a></div>
		<div id="next-step"><a href="listing-syndication.php"><img src="images/next-step.png" alt="next-step" width="150px"/></a></div>
		<br />
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>