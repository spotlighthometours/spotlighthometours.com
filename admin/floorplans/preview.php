<?php
/*
 * Admin: Floorplans (Preview)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();

// Create instances of needed objects
$users = new users($db);
$floorplans = new floorplans();
$media = new media();

// Require admin
$users->authenticateAdmin();

clearCache();

if(isset($_REQUEST['floorplanID'])&&!empty($_REQUEST['floorplanID'])){
	$floorplanID = $_REQUEST['floorplanID'];
	$floorplans->loadPlan($floorplanID);
	$snapShots = $floorplans->getSnapshots($floorplanID);
	$floorplanList = $floorplans->getFloorplans($floorplans->tourID);
}else{
	die('<h1>floorplanID required! Please pass the floorplanID as a parameter to this page!</h1>');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Preview Floorplan</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.10.4.js" type="text/javascript"></script><!-- jQuery UI -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin CP JS file -->
<script src="../../repository_inc/jquery.fancybox.js" type="text/javascript"></script><!-- FancyBox JS file -->
<script src="../../repository_inc/admin-floorplans.js" type="text/javascript"></script><!-- Floorplan JS file -->
<!-- WYSIWYG Style Sheet -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
@import "../../repository_css/admin-v2.css";
@import "../../repository_css/fancy-box/jquery.fancybox.css";
@import "../../repository_css/admin-floorplans.css";
</style>
</head>
<body>
<div id="ajaxMessage"></div>
<div class="floorplan-preview">
	<ul class="floorplan-links">
<?PHP
	foreach($floorplanList as $row => $column){
?>
        <li id="fp<?PHP echo $column['id'] ?>"><span class="<?PHP echo ($column['id']==$floorplanID)?'active':''; ?>" onclick="window.location='?floorplanID=<?PHP echo $column['id'] ?>'"><?PHP echo $column['label'] ?></span></li>
<?PHP
	}
?>
    	<div class="clear"></div>
    </ul>
    <img src="<?PHP echo $floorplans->getImg($floorplanID, true) ?>" height="710" width="920" />
<?PHP
	foreach($snapShots as $row => $column){
		$media->loadMedia('media', $column['mediaID'], 'photo');
?>
	<a id="ss<?PHP echo $column['id'] ?>" rel="" class="<?PHP switch($column['mediaType']){case'slideshow':echo'sssnapshot';break;case'video':echo'videosnapshot';break;default:echo'snapshot';break;} ?>" href="<?PHP echo ($column['mediaType']=='slideshow'||$column['mediaType']=='video')?'http://wwww.spotlighthometours.com/tours/video-player.php?type='.$column['mediaType'].'&id='.$column['mediaID']:SITE_URL.'/images/tours/'.$floorplans->tourID.'/photo_960_'.$media->mediaID.'.'.$media->fileExt ?>" style="position: absolute; left: <?PHP echo $column['x'] ?>px; top: <?PHP echo $column['y'] ?>px;" data-ssid="<?PHP echo $column['id'] ?>" data-mediaid="<?PHP echo $column['mediaID'] ?>" title="<?PHP echo $media->room ?>"></a>
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
</body>
</html>