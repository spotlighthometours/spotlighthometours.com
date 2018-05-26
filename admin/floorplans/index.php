<?php
/*
 * Admin: Floorplans (Create / Edit)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
//showErrors();

clearCache();

// Create instances of needed objects
$users = new users($db);
$floorplans = new floorplans();
$tours = new tours();

// Require admin
$users->authenticateAdmin();

if(isset($_REQUEST['tourID'])&&!empty($_REQUEST['tourID'])){
	$tourID = intval($_REQUEST['tourID']);
	$tours->loadTour($tourID);
	$floorplanList = $floorplans->getFloorplans($tourID);
}else{
	die('<h1>tourID required! Please pass the tourID as a parameter to this page!</h1>');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Floorplans</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-floorplans.js" type="text/javascript"></script><!-- Floorplan JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Floorplans for <?PHP echo $tours->address .' '. $tours->city .', '.$tours->state.' '.$tours->zipCode?></h1>
<div id="floorplanMsg"></div>
<div align="right">
	<div class="button_new button_blue button_mid" onclick="window.location='create.php?tourID=<?PHP echo $tourID ?>'">
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
	if(count($floorplanList)>0){
?>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th align="center">ID</th>
			<th>Label</th>
			<th align="center"># of Snapshots</th>
            <th></th>
            <th></th>
			<th></th>
            <th></th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($floorplanList as $row => $column){
?>
		<tr id="floorplan_<?PHP echo $column['id'] ?>">
			<td><?PHP echo $column['id'] ?></td>
			<td style="white-space:nowrap !important;"><?PHP echo $column['label'] ?></td>
			<td><?PHP echo $floorplans->countSnapShots($column['id']) ?></td>
            <td class="list-button" style="white-space:nowrap !important;"><a href="edit.php?floorplanID=<?PHP echo $column['id'] ?>">Edit Details</a></td>
            <td class="list-button" style="white-space:nowrap !important;"><a href="preview.php?floorplanID=<?PHP echo $column['id'] ?>">Preview</a></td>
			<td class="list-button" style="white-space:nowrap !important;"><a href="snapshots.php?floorplanID=<?PHP echo $column['id'] ?>">Place Snapshots</a></td>
			<td class="list-button delete" style="white-space:nowrap !important;"><a href="javascript:void(0)">Delete</a></td>
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
	}else{
		echo '<p>There are currently no floorplans for this tour. Please add a floor plan using the "Add" button above.</p>';
	}
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>