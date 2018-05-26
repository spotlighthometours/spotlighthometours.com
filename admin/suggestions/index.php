<?php
/*
 * Admin: Tour Suggestions (Create / Edit)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$toursuggestions = new toursuggestions();
$users = new users($db);

// Require admin
$users->authenticateAdmin();

// Pull needed information
$rangeList = $toursuggestions->rangeList();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Suggestion Ranges</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-suggestions.js" type="text/javascript"></script><!-- Admin Package JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Suggestion Ranges</h1>
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
			<th>Type</th>
			<th>From</th>
			<th>To</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($rangeList as $row => $column){
		if(empty($column['to_range'])){
			$to = "+";
		}else{
			$to = $column['to_range'];
		}
		switch($column['type']){
			case 1:
				$type = "Price";
			break;
			case 2:
				$type = "Sq Ft";
			break;
		}
?>
		<tr id="range_<?PHP echo $column['id'] ?>">
			<td><?PHP echo $type; ?></td>
			<td><?PHP echo $column['from_range']; ?></td>
			<td><?PHP echo $to; ?></td>
			<td class="list-button"><a href="edit.php?id=<?PHP echo $column['id'] ?>">Edit</a></td>
			<td class="list-button"><a href="javascript: deleteRange(<?PHP echo $column['id'] ?>)">Delete</a></td>
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
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>