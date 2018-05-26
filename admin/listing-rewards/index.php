<?php
/*
 * Admin: Listing Rewards Recent Tours
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$users = new users();
$boxapi = new boxapi();

// Box API stuff. Check if our app has access if so proceed if not lets load the request for access
if(!$boxapi->load_token()){
	if(isset($_GET['code'])){
		$token = $boxapi->get_token($_GET['code'], true);
		if($boxapi->write_token($token, 'file')){
			$boxapi->load_token();
		}
	} else {
		$boxapi->get_code();
	}
}

// Require admin
$users->authenticateAdmin();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Listing Rewards | Recent Tours</title>
<script src="../../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/jtable/jquery.jtable.js" type="text/javascript"></script><!-- jTable -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<link href=http://getbootstrap.com/dist/css/bootstrap.min.css rel=stylesheet>
<link href="data:text/css;charset=utf-8," data-href=http://getbootstrap.com/dist/css/bootstrap-theme.min.css rel=stylesheet id=bs-theme-stylesheet>
<link href=http://getbootstrap.com/assets/css/docs.min.css rel=stylesheet>
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
 @import "../../repository_css/admin-v2.css";
 @import "../../repository_css/jquery-ui-1.8.16.custom.css";
 @import "../../repository_inc/jtable/themes/lightcolor/blue/jtable.min.css";
</style>
<script>
	$(function() {
		$( "input[name='dateFrom']" ).datepicker({dateFormat : 'yy-mm-dd'});
		$( "input[name='dateTo']" ).datepicker({dateFormat : 'yy-mm-dd'});
	});
	function optOut(Obj){
		var tourID = $(Obj).parent().parent().data("record-key");
		var isChecked = $(Obj).is(":checked");
		var optOut = 0;
		var optMessage = "Opting In";
		if(isChecked){
			optOut = 1;
			optMessage = "Opting Out";
		}
		ajaxMessage(optMessage, 'processing');
		var url = "../../../repository_queries/admin_tour_opt_out.php";
		var params  = "tourID="+tourID+"&opt_out="+optOut;
		ajaxQuery(url, params, 'optSaved');
	}
	function optSaved(){
		ajaxMessage('Opt Saved!', 'success');
	}
	var photoTourBeingFinalized = 0;
	function finalizePhoto(tourID){
		if(photoTourBeingFinalized==0){
			var url = "../../../repository_queries/admin_finalize_listingrewardstour.php";
			var params  = "tourID="+tourID+"&type=photo";
			photoTourBeingFinalized = tourID;
			GetLoadingScreen('Finalzing Photo Tour');
			ajaxMessage('Finalzing Photo Tour', 'processing');
			ajaxQuery(url, params, 'photoTourFinalized');
		}
	}
	function photoTourFinalized(){
		ajaxMessage('Photo Tour Finalized!', 'success');
		$(".jtable").find("[data-record-key='" + photoTourBeingFinalized + "']").find('.glyphicon-camera').parent().removeClass('btn-default').addClass('btn-success');
		$(".jtable").find("[data-record-key='" + photoTourBeingFinalized + "']").find('.glyphicon-camera').parent().html('<span class="glyphicon glyphicon-camera"></span> finalized');
		photoTourBeingFinalized = 0;
	}
	var videoTourBeingFinalized = 0;
	function finalizeVideo(tourID){
		if(videoTourBeingFinalized==0){
			var url = "../../../repository_queries/admin_finalize_listingrewardstour.php";
			var params  = "tourID="+tourID+"&type=video";
			videoTourBeingFinalized = tourID;
			ajaxMessage('Finalzing Video Tour', 'processing');
			ajaxQuery(url, params, 'VideoTourFinalized');
		}
	}
	function VideoTourFinalized(){
		ajaxMessage('Video Tour Finalized!', 'success');
		$(".jtable").find("[data-record-key='" + videoTourBeingFinalized + "']").find('.glyphicon-facetime-video').parent().removeClass('btn-default').addClass('btn-success');
		$(".jtable").find("[data-record-key='" + videoTourBeingFinalized + "']").find('.glyphicon-facetime-video').parent().html('<span class="glyphicon glyphicon-facetime-video"></span> finalized');
		videoTourBeingFinalized = 0;
	}
	var tourBeingFinalized = 0;
	function finalize(tourID){
		if(tourBeingFinalized==0){
			var url = "../../../repository_queries/admin_finalize_listingrewardstour.php";
			var params  = "tourID="+tourID+"&type=process";
			tourBeingFinalized = tourID;
			ajaxMessage('Finalizing Tour', 'processing');
			ajaxQuery(url, params, 'tourFinalized');
		}
	}
	function tourFinalized(){
		ajaxMessage('Tour Finalized!', 'success');
		$(".jtable").find("[data-record-key='" + tourBeingFinalized + "']").find('.glyphicon-check').parent().removeClass('btn-default').addClass('btn-success');
		$(".jtable").find("[data-record-key='" + tourBeingFinalized + "']").find('.glyphicon-check').parent().html('<span class="glyphicon glyphicon-check"></span> finalized');
		tourBeingFinalized = 0;
	}
	function getAddUserPage(){
		var content = '<iframe src="http://www.spotlighthometours.com/admin/users/userslr.cfm?pg=edituserlr" frameborder="0" width="100%" height="700px;"></iframe>';
		showModal(content);
	}
</script>
<style>
.jtable a {
	color:#09F;
	text-decoration:none;
}
</style>
</head>
<body style="width:1300px;">
<div id="ajaxMessage"></div>
<h1>Listing Rewards Tours</h1>
<div align="right" style="margin-top:10px;margin-bottom:10px;">
	<div class="button_new button_blue button_mid" onclick="getAddUserPage()">
		<div class="curve curve_left"></div>
		<span class="button_caption">Add Agent</span>
		<div class="curve curve_right"></div>
	</div>
</div>
<div id="listingRewardsTours"></div>
<script type="text/javascript">
		$(document).ready(function () {
		    //Prepare jTable
			$('#listingRewardsTours').jtable({
				title: 'Listing Rewards Tours',
				paging: true,
				pageSize: 100,
				sorting: true,
				defaultSorting: 'createdOn DESC',
				actions: {
					listAction: '../../../repository_queries/get_recenttours_data.php'
				},
				fields: {
					tourID: {
						key: true,
						create: false,
						edit: false,
						list: true,
						title: 'Tour ID',
						display: function(data){
							return '<span onclick="viewTour('+data.record.tourID+')" style="cursor:pointer;color:#09F;">'+data.record.tourID+'</span>';
						},
						width: '5%'
					},
					address: {
						title: 'Address',
						display: function(data){
							if(data.record.unitNumber){
								return data.record.address+', Unit:'+data.record.unitNumber+' '+data.record.city+', '+data.record.state;
							}else{
								return data.record.address+' '+data.record.city+', '+data.record.state;
							}
						},
						width: '7%'
					},
					lastName: {
						title: 'Agent',
						display: function(data){
							return data.record.lastName+', '+data.record.firstName;
						},
						width: '6%'
					},
					createdOn: {
						title: 'Created',
						type: 'date',
						displayFormat: 'mm/dd/yy',
						width: '6%'
					},
					slideshowBtn:{
						title: ' ',
						sorting: false,
						display: function (data) {
							return '<center><button type="button" class="btn btn-primary" onclick="window.open(\'http://www.spotlighthometours.com/admin/users/users.cfm?pg=slideshows&tourid='+data.record.tourID+'\',\'Photo Manager\',\'height=700,width=950\');"><span class="glyphicon glyphicon-film"></span> slideshows</button></center>';
						},
						width: '5%'
					},
					reorderBtn:{
						title: ' ',
						sorting: false,
						display: function (data) {
							return '<center><button type="button" class="btn btn-primary" onclick="window.open(\'http://www.spotlighthometours.com/admin/users/users.cfm?pg=reorder&tour='+data.record.tourID+'\',\'Photo Manager\',\'height=700,width=950\');"><span class="glyphicon glyphicon-picture"></span> manage photos</button></center>';
						},
						width: '5%'
					},
					isPhotoFinalized:{
						title: ' ',
						sorting: false,
						display: function (data) {
							if(data.record.isPhotoFinalized){
								return '<center><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-camera"></span> finalized</button></center>';
							}else{
								return '<center><button type="button" class="btn btn-primary" onclick="finalizePhoto('+data.record.tourID+')"><span class="glyphicon glyphicon-camera"></span> finalize</button></center>';
							}
						},
						width: '5%'
					},
					isVideoFinalized:{
						title: ' ',
						sorting: false,
						display: function (data) {
							if(data.record.isVideoFinalized=="true"){
								return '<center><button type="button" class="btn btn-success"><span class="glyphicon glyphicon-facetime-video"></span> finalized</button></center>';
							}else{
								return '<center><button type="button" class="btn btn-primary" onclick="finalizeVideo('+data.record.tourID+')"><span class="glyphicon glyphicon-facetime-video"></span> finalize</button></center>';
							}
						},
						width: '5%'
					},
					isFinalized:{
						title: ' ',
						sorting: false,
						display: function (data) {
							if(data.record.processed=="1"){
								return '<center><button type="button" class="btn btn-success"><span class="glyphicon glyphicon glyphicon-check"></span> finalized</button></center>';
							}else{
								return '<center><button type="button" class="btn btn-primary" onclick="finalize('+data.record.tourID+')"><span class="glyphicon glyphicon glyphicon-check"></span> finalize</button></center>';
							}
						},
						width: '5%'
					},
					opt_out:{
						title: ' ',
						sorting: false,
						display: function (data) {
							if(data.record.opt_out=="1"){
								return '<input type="checkbox" name="opt_out" value="1" onchange="optOut(this);" checked> OPT OUT';
							}else{
								return '<input type="checkbox" name="opt_out" value="1" onchange="optOut(this);"> OPT OUT';
							}
						},
						width: '5%'
					}
				}
			});
			$('#listingRewardsTours').jtable('load');
		});
	</script>
	<div class="modal">
		<div class="content">
		</div>
	</div>
	<div class="modal-bg"></div>
</body>
</html>