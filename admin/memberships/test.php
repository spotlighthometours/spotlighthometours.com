<?php
/*
 * Admin: Packages (Create / Edit)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$memberships = new memberships();
//$users = new users($db);

// Require admin
//$users->authenticateAdmin();

// Pull needed information
$membershipList = $memberships->getAgentWebsites(2210);
//echo "<pre>";
//print_r($membershipList);
if ($membershipList){
		echo "membershipe";
}
echo $membershipList;
//echo "</pre>";
?>
