<?php
/**********************************************************************************************
Document: concierge/profile.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-08-16
Purpose: Spotlight's Concierge tour window edit page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight | Concierge Tour Window Editor';
	$header = '
<script src="js/tour-window.js"></script>
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/tour-window.css"/>
';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// Document
//=======================================================================
?>
	<div class="concierge-set-up">
		<div id="setup"><strong>Step 2:</strong> Setup your Tour Window</div>
		<div id="concierge-line"><img src="images/concierge-line.png" alt="line" /></div>
		<div id="concierge-logo"><img src="images/concierge-logo.png" alt="logo" /></div>
		<div id="topbar-logo">
			<div id="tour-window-logo"><img src="images/tour-window.png" alt="profile" width="150px"/></div>
		</div>
		<div id="topbar-text">
			<div id="tour-window-manager">Tour Window Manager</div>
			<div id="tour-window-text">Here you can edit the appearance  and functionality of your tour window.</div>
		</div>
		<div id="concierge-line2"> <img src="images/concierge-line.png" alt="line" /> </div>
		<iframe width="1045" height="806" frameborder="0"src="http://www.spotlighthometours.com/tours/tourwindow-editor.php?scope=user&userID=<?PHP echo $users->userID ?>"></iframe>
		<br />
		<div id="previous-step"><a href="profile.php"><img src="images/previous-step.png" alt="previous-step" width="150px"/></a></div>
		<div id="save-exit"><a href="set-up.php"><img src="images/save-exit.png" alt="save-exit" width="150px"/></a></div>
		<div id="next-step"><a href="microsite.php"><img src="images/next-step.png" alt="next-step" width="150px"/></a></div>
		<br />
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>