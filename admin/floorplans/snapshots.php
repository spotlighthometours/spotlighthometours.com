<?php
/*
 * Admin: Floorplans (Snapshots)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();

// Create instances of needed objects
$users = new users($db);
$floorplans = new floorplans();

// Require admin
$users->authenticateAdmin();

clearCache();

if(isset($_REQUEST['floorplanID'])&&!empty($_REQUEST['floorplanID'])){
	$floorplanID = $_REQUEST['floorplanID'];
	$floorplans->loadPlan($floorplanID);
	$snapShots = $floorplans->getSnapshots($floorplanID);
}else{
	die('<h1>floorplanID required! Please pass the floorplanID as a parameter to this page!</h1>');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Set Snapshots</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.10.4.js" type="text/javascript"></script><!-- jQuery UI -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin CP JS file -->
<script src="../../repository_inc/admin-floorplans.js" type="text/javascript"></script><!-- Floorplan JS file -->
<!-- WYSIWYG Style Sheet -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
@import "../../repository_css/admin-v2.css";
@import "../../repository_css/admin-floorplans.css";
</style>
<script>
	floorplanID = <?PHP echo $floorplanID ?>;
	tourID = <?PHP echo $floorplans->tourID ?>;
</script>
</head>
<body>
<div id="ajaxMessage"></div>
<h1><?PHP echo $floorplans->label ?> Snapshots</h1>
<div id="floorplanMsg" style="margin-bottom:-10px;"></div>
<table border="0" cellspacing="5" cellpadding="0" class="instructions-tbl">
  <tr>
    <td>
        Drag and drop the following camera/slideshow/video icon to create a new snapshot:
    </td>
    <td>
    	<div class="snapshot-icon"></div>
    </td>
    <td>
    	<div class="sssnapshot-icon"></div>
    </td>
    <td>
    	<div class="videosnapshot-icon"></div>
    </td>
  </tr>
</table>
<p><i>To delete the snapshot or to select the snapshot photo please double click on the camera icon after placing it on the floorplan.</i></p>
<div class="floorplan-img">
    <img src="<?PHP echo $floorplans->getImg($floorplanID, true) ?>" height="710" width="920" />
<?PHP
	foreach($snapShots as $row => $column){
?>
	<div class="<?PHP switch($column['mediaType']){case'slideshow':echo'setsssnapshot-icon';break;case'video':echo'setvideosnapshot-icon';break;default:echo'setsnapshot-icon';break;} ?> ui-draggable" style="position: absolute; left: <?PHP echo $column['x'] ?>px; top: <?PHP echo $column['y'] ?>px;" data-ssid="<?PHP echo $column['id'] ?>" data-mediaid="<?PHP echo $column['mediaID'] ?>"></div>
<?PHP
	}
?>
</div>
<br/>
<table cellpadding="5">
	<tr>
		<td><div class="button_new button_dgrey button_mid" onclick="window.location='index.php?tourID=<?PHP echo $floorplans->tourID ?>'">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Back</span>
				<div class="curve curve_right" ></div>
			</div></td>
	</tr>
</table>
<br/>
<?PHP
	if(isset($errorMsg)){
?>
<script>
		outputError('floorplanMsg', "<?PHP echo $errorMsg ?>");
</script>
<?PHP
	}
?>
<?PHP
	if(isset($alertMsg)){
?>
<script>
		outputAlert('floorplanMsg', "<?PHP echo $alertMsg ?>");
</script>
<?PHP
	}
?>
<div class="modal-bg">
</div>
<div class="modal">
    <div class="content">
    </div>
</div>
</body>
</html>