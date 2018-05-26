<?php
/*
 * Admin: Attach Media: Media Selection
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
	$attachments = $media->getAttachments($typeID, $type);
}else{
	die('<h1>type and typeID required! Please pass the type and typeID as a parameter to this page! type=product&typeID=21</h1>');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Attached Media Selection</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-attmedia.js" type="text/javascript"></script><!-- Attach Media JS file -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
 @import "../../repository_css/admin-v2.css";
 @import "../../repository_css/admin-attmedia.css";
</style>
<script>
	addMediaProdID = <?PHP echo $typeID ?>;
	mediaPrice = 5.00;
	addMediaMultiSelect = true;
	window.onload = function(){
		loadAddMedia(addMediaProdID);
	}
</script>
</head>
<body>
<!-- MODAL WINDOW -->
<div class="modal" style="display:block; border:0px;">
    <div id="backdrop" style="display: block;" onclick="HidePopUp();"></div>
    <div class="modal-window" style="display: block;" id="pop_up_frame">
      <div class="top"><a class="close" onClick="HidePopUp();"></a></div>
      <div class="middle">
        <div id="pop_up_content">
        	<div class="add-media">
<?PHP
	$first = true;
	$html = '<h1>Add Media to Order</h1>';
	foreach($attachments as $index => $attachment){
		$mediaID = $attachment['mediaID'];
		$mediaInfo = $media->getMediaByID($mediaID);
		$mediaType = $mediaInfo['mediaType'];
		if($mediaType=="photo"){
			$thmbSrc = $tourphotos->generateURL($mediaInfo);
		}else{
			$thmbSrc = $tourphotos->generateURL($tours->getVideoIcon($mediaInfo, true));
		}
		if($first){
			$html .= '<script>mediaShowingID = '.$mediaInfo['mediaID'].';</script>';
			$html .= '<div class="media-desc" data-mediaid="'.$mediaInfo['mediaID'].'">
						<div class="media-preview">';
			if($mediaType=="photo"){
				$html .= '	<img src="'.$tourphotos->generateURL($mediaInfo,400).'" width="313" />';
			}else{
				$html .= '	<iframe src="http://www.spotlighthometours.com/tours/video-player.php?type=video&id='.$mediaInfo['mediaID'].'" width="313" height="235" frameborder="0" />';
			}
			$html .= '	</div>
						<div class="media-details">
							<h2>'.$mediaInfo['room'].'</h2>
							<p>'.$mediaInfo['description'].'</p>
							<div class="price">Price: $<span>5.00</span></div>
							<div class="total">Total: $<span>0.00</span></div>
							<div class="add-btn"></div>
						</div>
						<div class="clear"></div>
					</div>';
			$html .= '<div class="thmb-list">';
		}
		$html .= '<div class="thmb" id="thmb_'.$mediaInfo['mediaID'].'" data-mediatype="'.$mediaType.'" data-tourid="'.$mediaInfo['tourID'].'"><div class="'.$mediaType.'-icon"></div><div class="zoom"></div><div class="thmb-lbl">'.$mediaInfo['room'].'</div><img src="'.$thmbSrc.'" /><div class="thmb-add">Add</div><div class="thmb-desc">'.$mediaInfo['description'].'</div></div>';
		$first = false;
	}
	$html .= '<div class="clear"></div></div>';
	echo $html;
?>
				<br/>
                <div class="grey-divider" style="margin-bottom:10px;"></div>
                <table cellpadding="5">
                	<tbody>
                    	<tr>
                			<td>
                            	<div class="button_new button_blue button_mid" onclick="addToOrder()">
                                    <div class="curve curve_left"></div>
                                    <span class="button_caption">Add to Order</span>
                                    <div class="curve curve_right"></div>
                                </div>
                            </td>
                            <td>
                            	<div class="button_new button_dgrey button_mid" onclick="cancelOrder()">
                                    <div class="curve curve_left"></div>
                                    <span class="button_caption">Cancel</span>
                                    <div class="curve curve_right"></div>
                                </div>
                            </td>
                    	</tr>
                	</tbody>
              </table>
            </div>	        	
        </div>
      </div>
      <div class="bottom"></div>
    </div>
</div>
<!-- END MODAL WINDOW -->
</body>
</html>