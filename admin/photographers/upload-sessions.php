<?php
/*
 * Admin: Photographer Uploads: Upload Sessions
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed objects
$users = new users($db);
$upload = new upload();
$photographers = new photographers();

// Require admin
$users->authenticateAdmin();

// Pull photographer upload sessions
$photographerUploads = $upload->getSessions("photographer");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Photographer Upload Sessions</title>
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
<h1>Photographer Upload sessions</h1>
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
			<th>Photographer</th>
			<th>Start Time</th>
			<th>End Time</th>
			<th>TourID: Folder Name(#files)</th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($photographerUploads as $row => $column){
		$startTime = date("m/d/y g:i A", strtotime($column['startTime']));
		if(!empty($column['endTime'])){
			$endTime = date("m/d/y g:i A", strtotime($column['endTime']));
		}else{
			$endTime = '...';
		}
		$photographer = $photographers->get('fullName', $column['userID']);
		$photographer = $photographer['fullName'];
		unset($folders);
		$folders = $upload->getSessionGroups($column['ID']);
		$folderFiles = '';
		$first = true;
		foreach($folders as $foldrow => $foldcolumn){
			if(!$first){
				$folderFiles .= ', ';
			}
			$folderFiles .= $foldcolumn['tourID'].': <a href="upload-folder.php?id='.$foldcolumn['ID'].'" target="_blank">'.$foldcolumn['name'].'</a>('.$foldcolumn['numberUploads'].')';
			$first = false;
		}
?>
		<tr id="session_<?PHP echo $column['ID'] ?>">
			<td valign="top" style="white-space:nowrap;"><?PHP echo $photographer ?></td>
		  <td valign="top" style="white-space:nowrap;"><?PHP echo $startTime ?></td>
		  <td valign="top" style="white-space:nowrap;"><?PHP echo $endTime ?></td>
			<td valign="top"><?PHP echo $folderFiles ?></td>
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