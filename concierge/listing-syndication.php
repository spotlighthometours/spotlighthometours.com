<?php
/**********************************************************************************************
Document: concierge/listing-syndication.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-09-16
Purpose: Spotlight's Concierge listing syndication setup page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight | Concierge Listing Syndication Setup';
	$header = '
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/listing-syndication.css"/>
';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="concierge-set-up">
		<div id="setup"><strong>Step 7:</strong> Setup your Listing Syndication</div>
		<div id="concierge-line"><img src="images/concierge-line.png" alt="line" /></div>
		<div id="concierge-logo"><img src="images/concierge-logo.png" alt="logo" /></div>
		<div id="topbar-logo">
			<div id="brochure-creator-logo"><img src="images/listing-syndication.png" alt="brochure-creator" width="150px"/></div>
		</div>
		<div id="topbar-text">
			<div id="brochure-creator">Listing Syndication</div>
			<div id="brochure-creator-text">Select the places you wish to syndicate. Your brokerage and/or team users cannot dictate where your tours are syndicated. You have full control over where your tours are syndicated</div>
		</div>
		<div id="concierge-line2"> <img src="images/concierge-line.png" alt="line" /> </div>
		<div id="listing-syndication-frame">
			<iframe height="900" width="900" src="http://www.spotlighthometours.com/users/new/syndication-settings-include.php"></iframe>
		</div>
		<div id="previous-step"><a href="spotlight-preview.php"><img src="images/previous-step.png" alt="previous-step" width="150px"/></a></div>
		<div id="save-exit"><a href="http://www.spotlighthometours.com/users/new/"><img src="images/finish.png" alt="finish" width="150px"/></a></div>
		<br />
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>