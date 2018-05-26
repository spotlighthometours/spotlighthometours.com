<?php
/*
 * Admin: Pay When Sold List
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$paywhensold = new paywhensold();
$users = new users($db);

// Require admin
//$users->authenticateAdmin();

// Pull needed information
$pwsList = $paywhensold->getList();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pay When Sold List</title>
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
<body style="width:100%;">
<h1>Pay When Sold</h1>
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
			<th>Tour ID</th>
			<th>Tour Type</th>
			<th>Address</th>
			<th>Agent</th>
			<th>Ordered On</th>
			<th>Collected</th>
			<th>MLS#</th>
			<th>MLS Provider</th>
            <th>Status</th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($pwsList as $row => $column){
?>
		<tr id="pws_<?PHP echo $column['tourID'] ?>">
			<td><?PHP echo $column['tourID'] ?></td>
			<td><?PHP echo $column['item'] ?></td>
			<td><?PHP echo $column['address'] ?></td>
			<td><?PHP echo $column['agent'] ?></td>
			<td><?PHP echo $column['ordered_on']; ?></td>
			<td><?PHP echo $column['collected']; ?></td>
			<td><?PHP echo $column['mls_num']; ?></td>
			<td><?PHP echo $column['mls_provider']; ?></td>
            <td><?PHP echo $column['status']; ?></td>
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