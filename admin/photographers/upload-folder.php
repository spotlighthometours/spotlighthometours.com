<?php
/*
 * Admin: Photographer Upload: Folder Details
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed objects
$users = new users($db);
$upload = new upload();
$tours = new tours();
$photographers = new photographers();

// Require admin
$users->authenticateAdmin();

// Pull upload folder details
$folderDetails = $upload->getGroup($_REQUEST['id']);
$tourID = $folderDetails['tourID'];
$numberUploads = $folderDetails['numberUploads'];
$tours->loadTour($tourID);
$tourAddress = trim($tours->address);
$tourAddress .= (isset($tours->unitNumber)&&!empty($tours->unitNumber))?' Unit '.$tours->unitNumber:'';
$tourAddress .= ' '.trim($tours->city).', '.trim($tours->state).' '.trim($tours->zipCode);
$folderFiles = $upload->getGroupFiles($_REQUEST['id']);
$uploadedFile = $folderFiles[0]['file'];
$folderDirectory = $upload->getGroupDirectory($_REQUEST['id']);
$completeUploads = $upload->countCompleteGroupFiles($_REQUEST['id']);
$incompleteUploads = $upload->countIncompleteGroupFiles($_REQUEST['id']);
$erroredUploads = $upload->countErroredGroupFiles($_REQUEST['id']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Photographer Upload Folder Details</title>
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
<h1>Upload Folder: <?PHP echo $folderDetails['name']; ?></h1>
<div class="alert">Tour ID: <?PHP echo $tourID; ?><br />
  Tour Address: <?PHP echo $tourAddress; ?><br />
  Folder Directory: <?PHP echo $folderDirectory; ?><br />
  Number Upoads: <?PHP echo $numberUploads; ?><br />
  Uploaded: <?PHP echo $completeUploads; ?><br />
  Uploading: <?PHP echo $incompleteUploads; ?><br />
  Errors: <?PHP echo $erroredUploads; ?><br />
</div>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th>File</th>
			<th>Start Time</th>
			<th>End Time</th>
			<th>Status</th>
			<th>Error</th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($folderFiles as $row => $column){
		$startTime = date("m/d/y g:i A", strtotime($column['startTime']));
		if(!empty($column['endTime'])){
			$endTime = date("m/d/y g:i A", strtotime($column['endTime']));
		}else{
			$endTime = '...';
		}
?>
		<tr id="file_<?PHP echo $column['ID'] ?>">
		  <td valign="top" style="white-space:nowrap;"><?PHP echo str_replace($folderDirectory,'',$column['file']) ?></td>
		  <td valign="top" style="white-space:nowrap;"><?PHP echo $startTime ?></td>
		  <td valign="top" style="white-space:nowrap;"><?PHP echo $endTime ?></td>
		  <td valign="top"><?PHP echo $column['progress'] ?></td>
		  <td valign="top"><?PHP echo $column['error'] ?></td>
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