<?php
/**********************************************************************************************
Document: admin_user_inoutvid.php
Creator: Jacob Edmond Kerr
Date: 02-26-14
Purpose: Allow admin to manager the intro and out video upload for a user
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================
	
	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	showErrors();
	
//=======================================================================
// Objects
//=======================================================================

	$uservideos = new uservideos();
	$users = new users();
	
//=======================================================================
// Document
//=======================================================================

	// Require admin
	$users->authenticateAdmin();
	
	if(isset($_REQUEST['userID'])&&!empty($_REQUEST['userID'])){
		$userID = $_REQUEST['userID'];
		$usersName = $users->getName($userID);
		$introVideo = $uservideos->videoExist($userID, 'intro');
		$outroVideo = $uservideos->videoExist($userID, 'outro');
	}else{
		die('<h1>userID is required!</h1>');
	}
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - User Intro/Outro Video Upload Manager</title>
        <link type="text/css" href="../repository_css/template.css" rel="stylesheet" />
        <link type="text/css" href="../repository_css/admin-v2.css" rel="stylesheet" />
		<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
		<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
        <script src="../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
		<script src="../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
        <script type="text/javascript" src="../uploader/swfupload.js"></script>
		<script type="text/javascript" src="../uploader/plugins/swfupload.queue.js"></script>
		<script type="text/javascript" src="../uploader/plugins/fileprogress.js"></script>
		<script type="text/javascript" src="../uploader/plugins/handlers.js"></script>
        <link href="../uploader/css/default.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript">
			var swfu;
			function loadUploader(type) {
				var settings = {
					debug: false,
					flash_url : "../uploader/flash/swfupload.swf",
					upload_url: "../repository_queries/admin_user_uploadinoutvid.php?userID=<?PHP echo $userID ?>&type="+type,
					file_types : "*.jpg;*.jpeg;*.png",
					file_types_description : "Web Image Files",
					file_upload_limit : 1,
					assume_success_timeout : 1,
					file_types : "*.mp4; *.mov",
					file_types_description : "MP4 / MOV Video Files",
					file_size_limit : "500 MB",
					file_queue_limit : 1,
					custom_settings : {
						progressTarget : type+"UploadProgress",
						cancelButtonId : type+"UploadCancel"
					},
					requeue_on_error: true,
					// Button settings
					button_image_url : "../uploader/images/XPButtonUploadText_61x22.png",
					button_placeholder_id : type+"UploaderBtn",
					button_width: 61,
					button_height: 22,
					// The event handler functions are defined in handlers.js
					file_queued_handler : fileQueued,
					file_queue_error_handler : fileQueueError,
					file_dialog_complete_handler : fileDialogComplete,
					upload_start_handler : uploadStart,
					upload_progress_handler : uploadProgress,
					upload_error_handler : uploadError,
					upload_success_handler : uploadSuccess,
					upload_complete_handler : function(){
						$('.'+type+'-uploader').slideToggle('slow');
						$('.'+type+'-file-txt').html(type+'_<?PHP echo $userID ?>');
						$('.'+type+'-action-link').html('Delete');
						$('.'+type+'-action-link').fadeIn('slow');
						$('.'+type+'-action-link').unbind('click');
						$('.'+type+'-action-link').attr('onclick', '').click(function(){
							if(type=='intro'){
								deleteIntroVideo();
							}else{
								deleteOutroVideo();
							}
						});
					},
					queue_complete_handler : queueComplete	// Queue plugin event
				};
				swfu = new SWFUpload(settings);
			};
			function getIntroUploader(){
				$('.intro-action-link').fadeOut('slow');
				$('.intro-uploader').slideToggle('slow');
				loadUploader('intro');
			}
			function getOutroUploader(){
				$('.outro-action-link').fadeOut('slow');
				$('.outro-uploader').slideToggle('slow');
				loadUploader('outro');
			}
			function deleteIntroVideo(){
				try {
					deleteIt = confirm('Are you sure you want to delete this intro video?');
					if(deleteIt){
						var url = "../../repository_queries/admin_user_deleteinoutvid.php";
						var params  = "userID=<?PHP echo $userID ?>&type=intro";
						ajaxQuery(url, params, 'introVideoDeleted');
					}
				} catch(err) {
					alert("GetStates: " + err);
				}
			}
			function deleteOutroVideo(){
				try {
					deleteIt = confirm('Are you sure you want to delete this outro video?');
					if(deleteIt){
						var url = "../../repository_queries/admin_user_deleteinoutvid.php";
						var params  = "userID=<?PHP echo $userID ?>&type=outro";
						ajaxQuery(url, params, 'outroVideoDeleted');
					}
				} catch(err) {
					alert("GetStates: " + err);
				}
			}
			function introVideoDeleted(){
				if(response==1){
					$('.intro-action-link').html('Upload');
					$('.intro-action-link').unbind('click');
					$('.intro-action-link').attr('onclick', '');
					$('.intro-action-link').click(function(){
						getIntroUploader();
					});
					$('.intro-file-txt').html('File not found! Please upload...');
				}else{
					alert('INTRO VIDEO DELETION FAILED! The system failed to find the intro video file for this user!');
				}
			}
			function outroVideoDeleted(){
				if(response==1){
					$('.outro-action-link').html('Upload');
					$('.outro-action-link').unbind('click');
					$('.outro-action-link').attr('onclick', '');
					$('.outro-action-link').click(function(){
						getOutroUploader();
					});
					$('.outro-file-txt').html('File not found! Please upload...');
				}else{
					alert('OUTRO VIDEO DELETION FAILED! The system failed to find the outro video file for this user!');
				}
			}
		</script>
        <script type="text/javascript">
			function sendVideo(){
				if($(".slideshows").val()==0){
					alert("Please select a slideshow!");
				}else{
					var url = "../repository_queries/slideshow_createvideo.php";
					var params  = "slideshowID="+$(".slideshows").val();
					ajaxQuery(url, params, 'videoSent');
				}
			}
			function videoSent(){
				$(".slideshows :selected").remove();
				$(".slideshows").val(0);
				alert('The slideshow has been added to the cue for YouTube video creation!');
			}
		</script>
        <style>
			.list td{
				vertical-align:middle !important;
			}
		</style>
    </head>
    <body style="margin:50px; margin-top:30px;">
   		<h1>Manage <?PHP echo $usersName['firstName'] ?> <?PHP echo $usersName['lastName'] ?>'s Intro/Outro Video Uploads</h1>
    	<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th>Video Type</th>
			<th align="center">File</th>
            <th></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td width="150">Intro</td>
			<td class="intro-file-txt"><?PHP echo ($introVideo)?$introVideo:'File not found! Please upload...';?></td>
			<td class="list-button" style="text-align:right"><a href="#" onclick="<?PHP echo ($introVideo)?'deleteIntroVideo()':'getIntroUploader()';?>" class="intro-action-link"><?PHP echo ($introVideo)?'Delete':'Upload';?></a></td>
		</tr>
        <tr style="display:none" class="intro-uploader">
			<td colspan="3">
            	<div class="fieldset flash" id="introUploadProgress">
                	<span class="legend">Upload Intro</span>
               	</div>
                <div id="divStatus">0 Files Uploaded</div>
                <div>
                	<span id="introUploaderBtn"></span>
                    <input id="introUploadCancel" type="button" onclick="swfu.cancelQueue();" disabled="disabled" />
                </div>
            </td>
		</tr>
        <tr>
			<td width="150">Outro</td>
			<td class="outro-file-txt"><?PHP echo ($outroVideo)?$outroVideo:'File not found! Please upload...';?></td>
			<td class="list-button" style="text-align:right"><a href="#" onclick="<?PHP echo ($outroVideo)?'deleteOutroVideo()':'getOutroUploader()';?>" class="outro-action-link"><?PHP echo ($outroVideo)?'Delete':'Upload';?></a></td>
		</tr>
        <tr style="display:none"  class="outro-uploader">
			<td colspan="3">
            	<div class="fieldset flash" id="outroUploadProgress">
                	<span class="legend">Upload Outro</span>
                </div>
                <div id="divStatus">0 Files Uploaded</div>
                <div>
                	<span id="outroUploaderBtn"></span>
                	<input id="outroUploadCancel" type="button" onclick="swfu.cancelQueue();" disabled="disabled" />
                </div>
            </td>
		</tr>
	</tbody>
</table>
<script>
	loadListEffects()
</script>
    </body>
</html>