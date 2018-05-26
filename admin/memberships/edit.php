<?php
/*
 * Admin: Packages (Edit)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

// Create instances of needed objects
$users = new users($db);
$memberships = new memberships($db);

// Require admin
$users->authenticateAdmin();

// Check if id was passed if not redirect to list with error else save id in var
if(isset($_REQUEST['id'])){
	$memberships->id = intval($_REQUEST['id']);
	$memberships->loadMembership();
	$membershipList = $memberships->getMemberships();
}else{
	header('Location: index.php?error=A valid membership ID is required.');
}

clearCache();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Membership</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-memberships.js" type="text/javascript"></script><!-- Memberships JS file -->
<script type="text/javascript" src="../../uploader/swfupload.js"></script><!-- SWF Uplaod JS file -->
<script type="text/javascript">
		var swfu;
		var ias;

		window.onload = function () {

			swfu = new SWFUpload({

				// Backend Settings
				upload_url: "../../repository_queries/admin_membership_iconupload.php",
				post_params: {
					"id": "<?php echo $memberships->id; ?>"
				},
				
				// File Upload Settings
				file_size_limit : "<?PHP echo max_file_size(); ?> MB",	// 5MB
				file_types : "*.jpg;*.png",
				file_types_description : "JPG or PNG Images",
				file_queue_limit : '1',
				
				// Event Handler Settings
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "../../repository_images/buttons/upload-new-photo.gif",
				button_placeholder_id : "uploadBtn",
				button_width: 160,
				button_height: 30,
				button_text : '',
				button_text_style : '',
				button_text_top_padding: 0,
				button_text_left_padding: 0,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				// Flash Settings
				flash_url : "../../uploader/flash/swfupload.swf",
				custom_settings : {
					upload_target : "divFileProgressContainer"
				},

				// Debug Settings
				debug: false

			});
		};
		
	function fileQueueError(file, errorCode, message) {
		try {
			if(message=="1"){
				alert("I'm sorry you may only upload one photo at a time! You have selected multiple photos. Please try again.");
			}else{
				alert(message);
			}
		} catch (ex) {
			this.debug(ex);
		}
	
	}
	
	function fileDialogComplete(numFilesSelected, numFilesQueued) {
		try {
			if (numFilesQueued > 0) {
				$('#divFileProgressContainer').html('<img src="../../repository_images/spinner-sm-blue.gif" align="absmiddle"/> Uploading membership icon.');
				this.startUpload();
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadSuccess(file, serverData) {
		try {
			var d = new Date();
			$('#divFileProgressContainer').html('<img src="'+serverData+'?'+d.getTime()+'"/>');
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadComplete(file) {
		try {
			
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadError(file, errorCode, message) {
		try {
			$('.change-profile-img .right-panel .status').html('<span style="color:red;">Upload failed. Please try again. If this problem persist then please try another image.</span>');
		} catch (ex3) {
			this.debug(ex3);
		}
	
	}
</script>
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
 @import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Edit Membership</h1>
<div id="membershipMsg" style="margin-bottom:-10px;"></div>
<div class="form_line" >
	<div class="form_direction" >Package Information</div>
</div>
<input type="hidden" name="id" value="<?PHP echo $memberships->id ?>" />
<div class="form_line" >
	<div class="input_line w_lg" >
		<div class="input_title" >Name</div>
		<input id="name" name="name" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" value="<?PHP echo $memberships->name ?>"/>
		<div class="input_info" style="display: none;" >
			<div class="info_text" >Membership name.</div>
		</div>
	</div>
	<div class="required_line w_lg" > <span class="required" >required</span> </div>
</div>
<div class="form_line" >
	<div class="input_line w_sm" >
		<div class="input_title" >Trial</div>
		<select name="trialDuration">
			<?PHP
		$maxMonths = 90;
		for($i=0; $i<=$maxMonths; $i++){
	?>
			<option value="<?PHP echo $i ?>" <?PHP echo ($memberships->trialDuration==$i)?'selected="selected"':''?>><?PHP echo $i ?> days</option>
			<?PHP
		}
	?>
		</select>
	</div>
</div>
<div class="form_line">
	<div class="input_line w_sm">
		<div class="input_title">Month $</div>
		<input id="price" name="price" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" value="<?PHP echo $memberships->price; ?>">
		<div class="input_info" style="display: none; ">
			<div class="info_text">No "$" or ","</div>
		</div>
	</div>
	<div class="required_line w_sm"> <span class="required">required</span> </div>
</div>
<div class="form_line">
	<div class="input_line w_sm">
		<div class="input_title">Year $</div>
		<input id="priceyear" name="priceyear" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" value="<?PHP echo (empty($memberships->priceyear))?0.00:$memberships->priceyear; ?>">
		<div class="input_info" style="display: none; ">
			<div class="info_text">No "$" or ","</div>
		</div>
	</div>
	<div class="required_line w_sm"> <span class="required">required</span> </div>
</div>
<div class="form_line" >
	<div class="input_line w_lg" >
		<div class="input_title" >URL</div>
		<input id="url" name="url" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" value="<?PHP echo $memberships->url ?>"/>
		<div class="input_info" style="display: none;" >
			<div class="info_text" >url</div>
		</div>
	</div>
</div>
<div class="form_line" >
	<div class="form_direction" >Membership Icon</div>
</div>
<div id="divFileProgressContainer" style="margin-bottom:10px;">
<?PHP
	$iconDir = $_SERVER['DOCUMENT_ROOT'].'/images/memberships/icons/'.$memberships->id.'/';
	if(file_exists($iconDir)) {
		if(file_exists($iconDir.'medium.jpg')){
			$mediumIcon = '../../images/memberships/icons/'.$memberships->id.'/medium.jpg';
		}else{
			$mediumIcon = '../../images/memberships/icons/'.$memberships->id.'/medium.png';	
		}
		echo '<img src="'.$mediumIcon.'"/>';
	}
?>
</div>
<div class="upload-btn">
	<div id="uploadBtn"></div>
</div>
<i>*Max size is <?PHP echo max_file_size(); ?> MB</i></div>
<div class="form_line" >
	<div class="form_direction" >Description</div>
</div>
<div class="form_line text_field" >
	<div class="input_line w_lg" >
		<div class="input_title" ></div>
		<textarea id="tour_descrip" name="description" onkeydown="CharacterCount('tour_descrip', 2000, 'char_count');" onkeyup="CharacterCount('tour_descrip', 2000, 'char_count');" /><?PHP echo $memberships->description ?></textarea>
	</div>
	<div class="required_line w_lg" > <span id="char_count" class="required" >2000 Characters Left</span> </div>
</div>
<div class="form_line">
	<div class="form_direction">Additional Memberships</div>
</div>
<div id="access">
	<div class="form_line text_field">
		<div class="input_line w_lg" style="width:600px;">
			<div class="input_title">&nbsp;</div>
			<!--  isAdditionalMembership
	<?php
		// pr($membershipList);
	?>
 -->
			<select name="memberships[]" multiple="multiple" onkeyup="setFocusOnEnter(event, 'otherBrokerage')" style="height:140px; width:500px; margin-top:10px;">
				<option value="" selected="">This membership comes with...</option>
<?PHP
	foreach($membershipList as $row => $column){
		print_r($column);
?>	
				<option value="<?PHP echo $column['id'] ?>" <?PHP echo($memberships->isAdditionalMembership($column['id']))?'SELECTED="SELECTED"':''; ?>><?PHP echo $column['name'] ?></option>
<?PHP
	}
?>			
			</select>
		</div>
	</div>
<br><br>
</div>
<div class="grey-divider" style="margin-bottom:10px;"></div>
</div>
<br/>
<table cellpadding="5">
	<tr>
		<td><div class="button_new button_blue button_mid" onclick="updateMembership()">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Save</span>
				<div class="curve curve_right" ></div>
			</div></td>
		<td><div class="button_new button_dgrey button_mid" onclick="window.location='index.php'">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Cancel</span>
				<div class="curve curve_right" ></div>
			</div></td>
	</tr>
</table>
<br/>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>