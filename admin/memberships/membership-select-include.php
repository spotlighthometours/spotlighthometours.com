<?php
/*
 * Admin: Membership Selection Input (for include)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

// Create instances of needed objects
$memberships = new memberships($db);

// Check if id was passed if not redirect to list with error else save id in var
if(isset($_REQUEST['id'])){
	$memberships->id = intval($_REQUEST['id']);
	$memberships->loadMembership();
	$membershipList = $memberships->getMemberships();
}

?>

<select name="membershipID">
	<option value="0" selected="">Select a membership...</option>
<?PHP
	foreach($membershipList as $row => $column){
?>	
	<option value="<?PHP echo $column['id'] ?>"><?PHP echo $column['name'] ?></option>
<?PHP
	}
?>			
</select>