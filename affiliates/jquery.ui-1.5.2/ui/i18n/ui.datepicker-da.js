<?php
/*
 * Admin: Photographer Feedback 
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
showErrors();
// Create instances of needed objects
$emailer = new emailer();
$photographers = new photographers();
$editors = new editors();
$tours = new tours($db);
$users = new users($db);

// Require admin
$users->authenticateAdmin();

if(isset($_REQUEST['tourID'])){
	$tourID = intval($_REQUEST['tourID']);
}else{
	die("<h1>Tour ID missing!</h1>");
}

$negative = array(
	"Overexposed",
	"Lens flares",
	"Underexposed",
	"No email sent",
	"HDR's not aligned",
	"Photos sent too late",
	"Dirty lense",
	"Not enough HDRs",
	"Twilight shot too early",
	"HDR exposures",
	"Twilight shot too late",
	"Not enough images",
	"Tour not cut"
);

$positive = array(
	"Great job!",
	"Great HDR exposures!",
	"Good exposures!",
	"Great composition!"
);

$tours->tourID = $tourID;
$tours->get("address");
$tours->get("city");
$tours->get("state");
$tours->get("zipCode");
$tours->get("userID"); 

$Progress = $db->run("SELECT *, ISNULL(ReScheduledon) as PhotoReIsNull, ISNULL(VideoReScheduledOn) as VideoReIsNull 
						FROM tourprogress WHERE tourID = ".$tourID." LIMIT 1");
$Progress = $Progress[0];

$tourA