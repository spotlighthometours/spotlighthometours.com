<?php
/*
 * Admin: Packages (Create / Edit)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$memberships = new memberships();
$users = new users($db);

// Require admin
  $users->authenticateAdmin();

// Pull needed information
$membershipList = $memberships->getMemberships();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Memberships</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Memberships</h1>
<table align="right">
	<tr>
		<td>
			<div class="button_new button_blue button_mid" onclick="window.open('https://www.spotlighthometours.com/admin/memberships/signup.php', '_blank')">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Signup</span>
				<div class="curve curve_right" ></div>
			</div>
		</td>
		<td>
			<div class="button_new button_blue button_mid" onclick="window.location='create.php'">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Add</span>
				<div class="curve curve_right" ></div>
			</div>	
		</td>
	</tr>
</table>
<div class="clear"></div>
<br/>
<?PHP
	if(isset($_REQUEST['error'])){
?>
<div class="errors"><?PHP echo $_REQUEST['error'] ?></div>
<?PHP
	}
?>
<?PHP
	if(isset($_REQUEST['alert'])){
?>
<div class="alert"><?PHP echo $_REQUEST['alert'] ?></div>
<?PHP
	}
?>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th align="center">ID</th>
			<th>Name</th>
			<th align="center">Trial Duration</th>
			<th align="center">Month $</th>
			<th align="center">Year $</th>
			<th align="center">URL</th>
			<th>Description</th>
			<th></th>
			<th></th>
            <th></th>
            <th></th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($membershipList as $row => $column){
		$description = substr($column['description'], 0, 200);
		$memberships->getPrice($column['id']);
		$memberships->getYrPrice($column['id']);
?>
		<tr id="membership_<?PHP echo $column['id'] ?>">
			<td align="center"><?PHP echo $column['id'] ?></td>
			<td style="white-space:nowrap !important;"><?PHP echo $column['name'] ?></td>
			<td align="center"><?PHP echo $column['trialDuration'] ?> Days</td>
			<td align="center"><?PHP echo '$'.$memberships->price ?></td>
			<td align="center"><?PHP if(empty($memberships->priceyear)){echo '$'.'0.00';}else{echo '$'.$memberships->priceyear;}?></td>
			<td style="white-space:nowrap !important;"><?PHP echo $column['url'] ?></td>
			<td><?PHP echo $description ?></td>
            <td class="list-button"><a href="teams.php?id=<?PHP echo $column['id'] ?>">Teams</a></td>
            <td class="list-button"><a href="brokerages.php?id=<?PHP echo $column['id'] ?>">Brokerages</a></td>
			<td class="list-button"><a href="users.php?id=<?PHP echo $column['id'] ?>">Users</a></td>
			<td class="list-button"><a href="edit.php?id=<?PHP echo $column['id'] ?>">Edit</a></td>
		</tr>
<?PHP
	}
?>
	</tbody>
</table>
<script>
	loadListEffects()
</script>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>