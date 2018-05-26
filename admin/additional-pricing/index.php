<?php
/*
 * Admin: Additional Pricing (list)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();

clearCache();

// Create instances of needed objects
$users = new users($db);
$pricing = new pricing();
$tours = new tours();

// Require admin
$users->authenticateAdmin();

if((isset($_REQUEST['type'])&&!empty($_REQUEST['type']))&&(isset($_REQUEST['typeID'])&&!empty($_REQUEST['typeID']))){
	$type = $_REQUEST['type'];
	$typeID = $_REQUEST['typeID'];
	$additionalPricing = $pricing->getAdditional($type, $typeID);
}else{
	die('<h1>type and typeID required! Please pass the type and typeID as a parameter to this page! type=tour&typeID=21 (tour = tour type)</h1>');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Additional Pricing</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-addpricing.js" type="text/javascript"></script><!-- Floorplan JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Additional Pricing</h1>
<div align="right">
	<div class="button_new button_blue button_mid" onclick="window.location='create.php?type=<?PHP echo $type ?>&typeID=<?PHP echo $typeID ?>'">
		<div class="curve curve_left" ></div>
		<span class="button_caption" >Add</span>
		<div class="curve curve_right" ></div>
	</div>
</div>
<div id="addpricingMsg"></div>
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
	if(count($additionalPricing)>0){
?>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th align="center">ID</th>
			<th>Label</th>
			<th align="center">Amount</th>
            <th></th>
            <th></th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($additionalPricing as $row => $column){
?>
		<tr id="additional_<?PHP echo $column['id'] ?>">
			<td><?PHP echo $column['id'] ?></td>
			<td style="white-space:nowrap !important;"><?PHP echo $column['label'] ?></td>
			<td><?PHP echo '$'.number_format($column['amount'],2) ?></td>
            <td class="list-button" style="white-space:nowrap !important;"><a href="edit.php?addPricingID=<?PHP echo $column['id'] ?>">Edit</a></td>
			<td class="list-button delete" style="white-space:nowrap !important;"><a href="javascript:void(0)">Delete</a></td>
		</tr>
<?PHP
	}
?>
	</tbody>
</table>
<script>
	loadListEffects();
</script>
<?PHP
	}else{
		echo '<p>There is currently no additional pricing for this product/tour type. Please add additional pricing using the "Add" button above.</p>';
	}
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>