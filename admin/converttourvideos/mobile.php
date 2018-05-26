<?php
/*
 * Admin: Convert Tour Videos to HLS
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();

// Create instances of needed objects
$users = new users($db);
$brokerages = new brokerages();

// Require admin
$users->authenticateAdmin();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Convert Tour Videos to HLS</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<link rel="stylesheet" type="text/css" href="../../repository_css/jquery.tagsinput.css"/> <!-- JQery Tags Styles -->
<script src="../../repository_inc/jquery.tagsinput.min.js"></script> <!-- JQuery Tags JS -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
@import "../../repository_css/admin-v2.css";
.ajaxMessage.processing {
	background-image:url('../../repository_images/loader.gif');
	background-position:15px 9px;
	background-repeat:no-repeat;
	background-color:#333;
}
#step2, #step3, #step4{
	display:none;
}
</style>
<script>
	var showStep1 = true;
	var showStep2 = false;
	var showStep3 = false;
	var showStep4 = false;
	$(function(){
		$("#tourIDs").tagsInput({
			'defaultText':'add a tour ID',
			'onAddTag': function(theTag){
				if (theTag.indexOf(',') > -1) {
					var theTag = theTag.split(",");
				}
				if (theTag instanceof Array) {
					var tourID;
					for (key in theTag) {
						var isnum = /^\d+$/.test(theTag[key]);
						if(isnum){
							showStep2 = true;
						}else{
							$("#tourIDs").removeTag(theTag[key]);
						}
					}
				}else{
					var isnum = /^\d+$/.test(theTag);
					if(isnum){
						showStep2 = true;
					}else{
						$("#tourIDs").removeTag(theTag);
					}
				}
				if(!$("#tourIDs").val()){
					showStep2 = false;
				}
				showSteps();
			},
			'onRemoveTag': function(){
				if(!$("#tourIDs").val()){
					showStep2 = false;
				}
				showSteps();
			}
		});
		$('.add-user select[name="brokerageID"]').live("change",function(){
			var defaultHTML = '<select name="userID" disabled="disabled"><option value="0">Select a brokerage to load users!</option></select>';
			if($(this).val()>0){
				$.getJSON("../../repository_queries/get_bkr_users.php",{brokerageID: $(this).val()}, function(j){
					var html = '<select name="userID">';
					var options = '';
					for (var i = 0; i < j.length; i++) {
						options += '<option value="' + j[i].userID + '">' + j[i].lastName + ', '+j[i].firstName+'</option>';
					}
					html += options+'</select>';
					$(".add-user .user-select").html(html);
				});
				showStep3 = true;
			}else{
				$(".add-user .user-select").html(defaultHTML);
				showStep3 = false;
			}
			showSteps();
		});
	});
	function showSteps(){
		var isStep1Showing = $('#step1').css('display')!=='none';
		var isStep2Showing = $('#step2').css('display')!=='none';
		var isStep3Showing = $('#step3').css('display')!=='none';
		if(showStep1){
			if(!isStep1Showing){
				$('#step1').fadeIn('slow');
			}
		}else{
			if(isStep1Showing){
				$('#step1').fadeOut('slow');
			}
		}
		if(showStep2){
			if(!isStep2Showing){
				$('#step2').fadeIn('slow');
			}
		}else{
			if(isStep2Showing){
				$('#step2').fadeOut('slow');
			}
		}
		if(showStep3){
			if(!isStep3Showing){
				$('#step3').fadeIn('slow');
			}
		}else{
			if(isStep3Showing){
				$('#step3').fadeOut('slow');
			}
		}
	}
	function convertVideos(){
		var tourIDs = $("#tourIDs").val();
		ajaxMessage('Send Job Request(s)', 'processing');
		var params = 'tourIDs='+tourIDs;
		var url = '../../repository_queries/admin_convertmvideos_tours.php';
		ajaxQuery(url, params, 'videosConverted');
		$("#dupbtn").fadeOut('slow');
	}
	function videosConverted(){
		var convertedVideos = JSON.parse(response);
		var convertVideosHTML = '';
		jQuery.each(convertedVideos, function(i, val){
			convertVideosHTML += '<tr>';
          	convertVideosHTML += '	<td>'+val.tourID+'</td>';
          	convertVideosHTML += '	<td>'+val.id+'</td>';
			convertVideosHTML += '	<td>'+val.name+'</td>';
			convertVideosHTML += '	<td>'+val.status+'!</td>';
        	convertVideosHTML += '</tr>';
		});
		$("#convertedVideos").html(convertVideosHTML);
		showStep1 = false;
		showStep2 = false;
		showStep3 = true;
		showSteps();
		ajaxMessage('Job Request(s) Sent!', 'success');
	}
</script>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Convert Tour Videos to Mobile (for facebook etc)</h1>
<div id="step1">
    <h2>Step 1: Enter Tour IDs</h2>
    <i>The tour IDs maybe separated with a comma or entered individually.</i><br/><br/>
    <input type="text" id="tourIDs" name="tourIDs" value="" />
</div>
<br/>
<div id="step2">
    <h2>Step 2: Convert Tour Videos to HLS (send job to Amazon Transcoder)</h2>
    <br/>
    <div class="button_new button_blue button_mid" onclick="convertVideos()" id="dupbtn">
        <div class="curve curve_left" ></div>
        <span class="button_caption" >Send Job Request(s)</span>
        <div class="curve curve_right" ></div>
    </div>
    <br/>
</div>
<div id="step3">
    <h2>Step 3: View Response</h2>
    <table border="0" cellspacing="0" cellpadding="10" class="list">
      <thead>
        <tr>
          <th>tour ID</th>
          <th>Media ID</th>
          <th>Video Name</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="convertedVideos">
      	
      </tbody>
    </table>
    <script>
        loadListEffects();
    </script>
    <br/><br/>
    <div class="button_new button_blue button_mid" onclick="window.location='mobile.php'">
        <div class="curve curve_left" ></div>
        <span class="button_caption" >Convert More</span>
        <div class="curve curve_right" ></div>
    </div>
</div>
</body>
</html>