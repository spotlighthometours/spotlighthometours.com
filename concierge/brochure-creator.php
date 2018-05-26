<?php
/**********************************************************************************************
Document: concierge/index.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-08-16
Purpose: Spotlight's Concierge Email Blast setup page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight | Concierge Brochure Creator';
	$header = '
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/brochure-creator.css"/>
';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="concierge-set-up">
		<div id="setup"><strong>Step 7:</strong> Setup your Brochure Creator</div>
		<div id="concierge-line"><img src="images/concierge-line.png" alt="line" /></div>
		<div id="concierge-logo"><img src="images/concierge-logo.png" alt="logo" /></div>
		<div id="topbar-logo">
			<div id="brochure-creator-logo"><img src="images/brochure-creator.png" alt="brochure-creator" width="150px"/></div>
		</div>
		<div id="topbar-text">
			<div id="brochure-creator">Brochure Creator</div>
			<div id="brochure-creator-text">This page allows you to set the default color schemes for your brochures.</div>
		</div>
		<div id="concierge-line2"> <img src="images/concierge-line.png" alt="line" /> </div>
		<div id="link-accounts">Settings COMING SOON</div>
		<div id="concierge-line3"> <img src="images/concierge-line.png" alt="line" /> </div>
		<br />
		<br />
		<br />
		<div id="previous-step"><a href="spotlight-preview.php"><img src="images/previous-step.png" alt="previous-step" width="150px"/></a></div>
		<div id="save-exit"><a href="set-up.php"><img src="images/save-exit.png" alt="save-exit" width="150px"/></a></div>
		<div id="next-step"><a href="listing-syndication.php"><img src="images/next-step.png" alt="next-step" width="150px"/></a></div>
		<br />
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>