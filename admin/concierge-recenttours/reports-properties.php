<?php
/*
 * Admin: Concierge Reports / Properties (Shows Properties from Feed)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

showErrors();

// Create instances of needed objects
$users = new users($db);
$concierge = new concierge();
$mls = new mls();
$mlsProvider = $mls->providerFactory($_REQUEST['mlsProvider']);

// Require admin
$users->authenticateAdmin();

$officeProperties = $mlsProvider->getOfficeProperties($_REQUEST['officeID']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Concierge Reports</title>
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
<h1>Office Properties from Feed</h1>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
<?PHP
	foreach($officeProperties[0] as $columnName => $columnV){
?>
			<th><?PHP echo $columnName ?></th>
<?PHP		
	}
?>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($officeProperties as $propertyRow => $propertyColumns){
?>
		<tr id="mlsprovider_<?PHP echo $id ?>">
<?PHP
		foreach($propertyColumns as $columnName => $columnValue){
			$out = strlen($columnValue) > 50 ? substr($columnValue,0,50)."..." : $columnValue;
?>
			<td><?PHP echo $out ?></td>
<?PHP
		}
?>
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