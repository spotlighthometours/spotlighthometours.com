<?php
/*
 * Admin: Attach Media
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed objects
$media = new media();
$users = new users();
$tourphotos = new tourphotos();
$tours = new tours();

// Require admin
$users->authenticateAdmin();

if((isset($_REQUEST['type'])&&!empty($_REQUEST['type']))&&(isset($_REQUEST['typeID'])&&!empty($_REQUEST['typeID']))){
	$type = $_REQUEST['type'];
	$typeID = $_REQUEST['typeID'];
	switch($type){
		case'tour':
			$tourtypes = new tourtypes();
			$tourtypes->load($typeID);
			$typeName = $tourtypes->tourTypeName;
		break;
		case'product':
			$products = new products();
			$products->load($typeID);
			$typeName = $products->productName;
		break;
	}
	$premiumPhotos = $media->getPremiumPhotos();
	$premiumVideos = $media->getPremiumVideos();
	$attachments = $media->getAttachments($typeID, $type);
	foreach($attachments as $row => $attachmentInfo){
		$savedAttachments[$attachmentInfo['mediaID']] = $attachmentInfo['multiSelect'];
	}
}else{
	die('<h1>type and typeID required! Please pass the type and typeID as a parameter to this page! type=product&typeID=21</h1>');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Attach Media</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-attmedia.js" type="text/javascript"></script><!-- Attach Media JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
<script>
	type = '<?PHP echo $type ?>';
	typeID = <?PHP echo $typeID ?>;
	tourID = <?PHP echo $media->premiumMediaTourID ?>;
</script>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Attach Media to <?PHP echo $type ?>: <?PHP echo $typeName ?></h1>
<p>Please select the photos and/or videos you would like to attach to this <?PHP echo $type ?>. Photos and Videos are grouped by name and have 2 options attach and multi. The multi option states that the photo or video can be selected with other photos / videos during checkout. For instance if you have 3 photos attached with multi select enabled and one without multiselect enabled the user will be able to select all 3 photos with multiselect but when and if they go to select the photo without multiselect all 3 selected images will be deselected and only the one without multiselect will be selected... <strong>Note: The premium photos and videos are uploaded to tour ID: 52776</strong></p>
<div id="attachMediaMsg"></div>
<h2>Premium Photos</h2>
<?PHP
	foreach($premiumPhotos as $groupName => $photos){
		$allAttached = true;
		$allMulti = true;
		foreach($photos as $row => $mediaInfo){
			if(!isset($savedAttachments[$mediaInfo['mediaID']])){
				$allAttached = false;
			}
			if(isset($savedAttachments[$mediaInfo['mediaID']])&&$savedAttachments[$mediaInfo['mediaID']]==0){
				$allMulti = false;
			}
		}
?>		
	<div class="media-group">
        <div class="form_line">
        	<div class="form_direction opencloser">
            	<?PHP echo $groupName; ?>(<?PHP echo count($photos); ?>)
                <div class="right plusminus">+</div>
            </div>
            <div class="form_direction_options">
            	<input type="checkbox" name="attach" <?PHP echo ($allAttached)?'checked="checked"':''; ?>>attach 
                <input type="checkbox" name="multi" <?PHP echo ($allMulti&&$allAttached)?'checked="checked"':''; ?>>multi
            </div>
		</div>
        <div class="media-list slidebox">
<?PHP
	foreach($photos as $row => $mediaInfo){
?>
			<div class="media-item" data-mediaid="<?PHP echo $mediaInfo['mediaID'] ?>" data-mediatype="photo">
            	<div class="media-label"><?PHP echo $mediaInfo['room'] ?></div>
                <img src="<?PHP echo $tourphotos->generateURL($mediaInfo); ?>" /><br/>
                <input type="checkbox" name="attach" <?PHP echo (isset($savedAttachments[$mediaInfo['mediaID']]))?'checked="checked"':''; ?>>attach 
                <input type="checkbox" name="multi" <?PHP echo (isset($savedAttachments[$mediaInfo['mediaID']])&&$savedAttachments[$mediaInfo['mediaID']]==1)?'checked="checked"':''; ?>>multi
            </div>
<?PHP
	}
?>
        	<div class="clear"></div>
        </div>
	</div>
<?PHP
	}
?>
<h2>Premium Videos</h2>
<?PHP
	foreach($premiumVideos as $groupName => $videos){
?>	
	<div class="media-group">	
		<div class="form_line">
        	<div class="form_direction opencloser" style="cursor:pointer;">
            	<?PHP echo $groupName; ?>(<?PHP echo count($videos); ?>)
                <div class="right plusminus">+</div>
            </div>
            <div class="form_direction_options">
            	<input type="checkbox" name="attach" <?PHP echo (isset($savedAttachments[$mediaInfo['mediaID']]))?'checked="checked"':''; ?>>attach 
                <input type="checkbox" name="multi" <?PHP echo (isset($savedAttachments[$mediaInfo['mediaID']])&&$savedAttachments[$mediaInfo['mediaID']]==1)?'checked="checked"':''; ?>>multi
            </div>
      	</div>
        <div class="media-list slidebox">
<?PHP
	foreach($videos as $row => $mediaInfo){
?>
			<div class="media-item" data-mediaid="<?PHP echo $mediaInfo['mediaID'] ?>" data-mediatype="video">
            	<div class="media-label"><?PHP echo $mediaInfo['room'] ?></div>
                <img src="<?PHP echo $tourphotos->generateURL($tours->getVideoIcon($mediaInfo, true)); ?>" /><br/>
                <input type="checkbox" name="attach" <?PHP echo (isset($savedAttachments[$mediaInfo['mediaID']]))?'checked="checked"':''; ?>>attach 
                <input type="checkbox" name="multi" <?PHP echo (isset($savedAttachments[$mediaInfo['mediaID']])&&$savedAttachments[$mediaInfo['mediaID']]==1)?'checked="checked"':''; ?>>multi
            </div>
<?PHP
	}
?>
        	<div class="clear"></div>
        </div>
    </div>
<?PHP
	}
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div class="modal-bg"></div>
<div class="modal">
    <div class="content">
    </div>
</div>
</body>
</html>