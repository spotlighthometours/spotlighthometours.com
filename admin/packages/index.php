<?php
/*
 * Admin: Packages (Create / Edit)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$packages = new packages();
$users = new users($db);

// Require admin
$users->authenticateAdmin();

// Pull needed information
$packageList = $packages->getPackages();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Packages</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-packages.js" type="text/javascript"></script><!-- Admin Package JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Packages</h1>
<div align="right">
	<div class="button_new button_blue button_mid" onclick="window.location='create.php'">
		<div class="curve curve_left" ></div>
		<span class="button_caption" >Add</span>
		<div class="curve curve_right" ></div>
	</div>
</div>
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
			<th>Name</th>
			<th>Price</th>
			<th>Monthly</th>
			<th>Mon. Price</th>
			<th>Created On</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($packageList as $row => $column){
		$monthly = ($column['monthly']=="0")?'No':'Yes';
?>
		<tr id="package_<?PHP echo $column['id'] ?>">
			<td style="white-space:nowrap !important;"><?PHP echo $column['name'] ?></td>
			<td><?PHP echo '$'.$column['price'] ?></td>
			<td><?PHP echo $monthly ?></td>
			<td><?PHP echo '$'.$column['monthlyPrice'] ?></td>
			<td style="white-space:nowrap !important;"><?PHP echo date('F d, Y', strtotime($column['createDate'])); ?></td>
			<td class="list-button"><a href="users.php?id=<?PHP echo $column['id'] ?>">Users</a></td>
			<td class="list-button"><a href="edit.php?id=<?PHP echo $column['id'] ?>">Edit</a></td>
			<td class="list-button">
<?PHP
	//if($column['finalized']=="0"){
?>
				<a href="javascript: deletePackage(<?PHP echo $column['id'] ?>)">Delete</a>
<?PHP
	//}else{
?>
				<!--FINALIZED-->
<?PHP
	//}
?>
			</td>
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