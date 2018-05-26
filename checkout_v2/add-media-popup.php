<?php
/*
 * Admin: Attach Media: Media Selection
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed objects
$media = new media();
$users = new users();
$tourphotos = new tourphotos();
$tours = new tours();

if((isset($_REQUEST['type'])&&!empty($_REQUEST['type']))&&(isset($_REQUEST['typeID'])&&!empty($_REQUEST['typeID']))){
	$type = $_REQUEST['type'];
	$typeID = $_REQUEST['typeID'];
	$attachments = $media->getAttachments($typeID, $type);
}else{
	die('<h1>type and typeID required! Please pass the type and typeID as a parameter to this page! type=product&typeID=21</h1>');
}

?>
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
				$html .= '	<img src="'.str_replace('http:', 'https:', $tourphotos->generateURL($mediaInfo,400)).'" width="413" />';
			}else{
				$html .= '	<iframe src="https://www.spotlighthometours.com/tours/video-player-new.php?type=video&id='.$mediaInfo['mediaID'].'" width="413" height="275" frameborder="0"></iframe>';
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
		$html .= '<div class="thmb" id="thmb_'.$mediaInfo['mediaID'].'" data-mediatype="'.$mediaType.'" data-tourid="'.$mediaInfo['tourID'].'"><div class="'.$mediaType.'-icon"></div><div class="zoom"></div><div class="thmb-lbl">'.$mediaInfo['room'].'</div><img src="'.str_replace('http:', 'https:', $thmbSrc).'" /><div class="thmb-add">Add</div><div class="thmb-desc">'.$mediaInfo['description'].'</div></div>';
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