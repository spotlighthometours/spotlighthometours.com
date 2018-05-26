<?php
/*
 * Admin: Payment Plans (list)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed objects
$paymentplans = new paymentplans();
$users = new users();

// Require admin
$users->authenticateAdmin();

// Pull created payment plans
$createdPaymentPlans = $paymentplans->getPlans();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Payment Plans</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-paymentplans.js" type="text/javascript"></script><!-- Admin Payment Plans JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Payment Plans</h1>
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
        	<th>ID</th>
			<th>Name</th>
			<th>Up Front</th>
			<th>Number of Months</th>
			<th>Interest</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($createdPaymentPlans as $row => $column){
?>
		<tr id="paymentplan_<?PHP echo $column['id'] ?>">
        	<td><?PHP echo $column['id'] ?></td>
			<td style="white-space:nowrap !important;"><?PHP echo $column['title'] ?></td>
			<td><?PHP if($column['isUpFrntPercent']==1){ echo (floatval($column['upFront'])*100).'%'; }else{ echo '$'.number_format($column['upFront'], 2, '.', ','); } ?></td>
			<td><?PHP echo $column['months'] ?></td>
			<td><?PHP if($column['isIntPercent']==1){ echo (floatval($column['interest'])*100).'%'; }else{ echo '$'.number_format($column['interest'], 2, '.', ','); } ?></td>
			<td class="list-button"><a href="edit.php?id=<?PHP echo $column['id'] ?>">Edit</a></td>
			<td class="list-button"><a href="javascript: deletePaymentPlan(<?PHP echo $column['id'] ?>)">Delete</a></td>
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