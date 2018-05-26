<?php
/*
 * Admin: Progress / Video
 */

// Include appplication's global configuration
require_once('../../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$reports = new reports();
$tourtypes = new tourtypes();
$users = new users();

// Require admin
$users->authenticateAdmin();

// Pull tour types this will be used to replace the tour type ID with it's name
$tourtypelist = $tourtypes->listAll();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tour Queue | Video</title>
<script src="../../../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../../repository_inc/jtable/jquery.jtable.js" type="text/javascript"></script><!-- jTable -->
<script src="../../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../../repository_inc/admin-progress.js" type="text/javascript"></script><!-- Admin Progress JS file -->
<style type="text/css" media="screen">
	@import "../../../repository_css/template.css";
 	@import "../../../repository_css/admin-v2.css";
	@import "../../../repository_css/jquery-ui-1.8.16.custom.css";
	@import "../../../repository_inc/jtable/themes/lightcolor/blue/jtable.min.css";
</style>
<script>
	var cols = new Array(
		"isVideoTour"
	);
	var opperators = new Array(
		"="
	);
	var values = new Array(
		"1"
	);
	var conds = new Array(
		"AND"
	);
	$(function() {
		$( "input[name='dateFrom']" ).datepicker({dateFormat : 'yy-mm-dd'});
		$( "input[name='dateTo']" ).datepicker({dateFormat : 'yy-mm-dd'});
	});
</script>
<style>
	.jtable a{
		color:#09F;
		text-decoration:none;
	}
</style>
</head>
<body style="width:1300px;">
<h1>Video Tour Queue</h1>
<div class="filtering">
	<div class="templates">
        <form>
            <label>Show video tours that are: 
                <select name="filterTemplate">
               	  <option value="0">Video tours (all)</option>
                    <option value="1">Not finalized</option>
                    <option value="2">Waiting on editing</option>
                    <option value="3">Waiting on media</option>
                    <option value="4">Waiting on scheduling</option>
                    <option value="5">Edited and not finalized</option>
                </select>
            </label>
            <button type="submit" id="LoadRecordsButton">Load records</button>
        </form>
    </div>
    <div class="search">
    	<form>
            <label>Search:
                <input type="text" name="searchVal" value="" />
                <select name="searchType">
                    <option value="address">Tour Address</option>
                    <option value="tourid">Tour ID</option>
                </select>
            </label>
            <button type="submit" id="searchBtn">Run search</button>
        </form>
    </div>
    <div class="clear"></div>
    <div class="date-range">
    	<label>Date Range:</label>
        <input name="dateFrom" value="" /> 
        <input name="dateTo" value="" />
        <select name="dateType">
            <option value="createdOn">Created</option>
            <option value="VideoScheduledOn">Scheduled</option>
            <option value="VideoReScheduledOn">ReScheduled</option>
        </select>
        <button type="submit" id="dateRangeBtn">Load range</button>
    </div>
    <div class="clear"></div>
    <div class="custom" style="display:none;">
    	<div class="heading">Custom Filter</div>
        <div class="clear"></div>
        <div class="filters">
        	<select name="filter">
            	<option>Select filter...</option>
            </select>
        </div>
    </div>
</div>
<div id="videoReportContainer"></div>
<script type="text/javascript">

		$(document).ready(function () {

		    //Prepare jTable
			$('#videoReportContainer').jtable({
				title: 'Video Tours',
				paging: true,
				pageSize: 100,
				sorting: true,
				defaultSorting: 'tourProgID DESC',
				actions: {
					listAction: '../../../repository_queries/get_tpreport_data.php',
					deleteAction: '../../../repository_queries/affiliate_hide_queue.php'
				},
				deleteConfirmation: function(data) {
    				data.deleteConfirmMessage = 'Are you sure to hide Tour ID: ' + data.record.tourid + ' from the video queue?';
				},
				fields: {
					tourid: {
						key: true,
						create: false,
						edit: false,
						list: true,
						title: 'Tour ID',
						display: function(data){
							return '<span onclick="viewTour('+data.record.tourid+')" style="cursor:pointer;color:#09F;">'+data.record.tourid+'</span>';
						},
						width: '6%'
					},
					address: {
						title: 'Address',
						display: function(data){
							if(data.record.unitNumber){
								return '<a href="http://www.spotlighthometours.com/admin/users/users.cfm?pg=editTour&tour='+data.record.tourid+'" target="_new">'+data.record.address+', Unit:'+data.record.unitNumber+' '+data.record.city+', '+data.record.state+'</a>';
							}else{
								return '<a href="http://www.spotlighthometours.com/admin/users/users.cfm?pg=editTour&tour='+data.record.tourid+'" target="_new">'+data.record.address+' '+data.record.city+', '+data.record.state+'</a>';
							}
						},
						width: '6%'
					},
					lastName: {
						title: 'Agent',
						display: function(data){
							return '<a href="http://www.spotlighthometours.com/admin/users/users.cfm?pg=editUser&user='+data.record.userID+'" target="_new">'+data.record.lastName+', '+data.record.firstName+'</a>';
						},
						width: '5%'
					},
					tourTypeID: {
						title: 'Tour Type',
						type: 'checkbox',
						values: {<?PHP $firstTT = true; foreach($tourtypelist as $row => $columns){ if(!$firstTT){echo',';} $firstTT=false; ?>'<?PHP echo $columns['tourTypeID'] ?>':'<?PHP echo str_replace("'","",$columns['tourTypeName']) ?>'<?PHP } ?>},
						width: '7%'
					},
					createdOn: {
						title: 'Created',
						type: 'date',
						displayFormat: 'mm/dd/yy',
						width: '6%'
					},
					VideoScheduledOn: {
						title: 'Sched On',
						type: 'date',
						displayFormat: 'mm/dd/yy',
						width: '7%'
					},
					ScheduleAttemptedon: {
						title: 'Sched Atpt',
						type: 'date',
						displayFormat: 'mm/dd/yy',
						width: '7%'
					},
					VideoMediaReceivedOn: {
						title: 'Media',
						type: 'date',
						displayFormat: 'mm/dd/yy',
						width: '5%'
					},
					VideoEditedOn: {
						title: 'Edited',
						type: 'date',
						displayFormat: 'mm/dd/yy',
						width: '5%'
					},
					VideoFinalizedOn: {
						title: 'Finalized',
						display: function(data){
							if(data.record.VideoFinalized==1){;
								var finalizedOnMysqlTimeStamp = data.record.VideoFinalizedOn;
								if(finalizedOnMysqlTimeStamp){
									var t = finalizedOnMysqlTimeStamp.split(/[- :]/);
									var d = new Date(t[0], t[1]-1, t[2]);
									var date = d.getDate();
									var month = d.getMonth();
									var year = d.getFullYear();
									return month+'/'+date+'/'+year;
								}else{
									return '';
								}
							}else{
								return '';
							}
						},
						width: '6%'
					},
					VideoReScheduledOn: {
						title: 'Resched On',
						type: 'date',
						displayFormat: 'mm/dd/yy',
						width: '8%'
					},
					VideoMediaReReceivedOn: {
						title: 'ReMedia',
						type: 'date',
						displayFormat: 'mm/dd/yy',
						width: '6%'
					},
					VideoReEditedOn: {
						title: 'ReEdited',
						type: 'date',
						displayFormat: 'mm/dd/yy',
						width: '6%'
					},
					progress:{
						title: 'Progress',
						sorting: false,
						display: function (data) {
							var scheduled = false;
							var mediaReceived = false;
							var edited = false;
							var finalized = false;
							if(data.record.VideoScheduledOn){
								scheduled = true;
							}
							if(data.record.VideoReScheduledOn){
								scheduled = true;
							}
							if(data.record.VideoMediaReceivedOn){
								mediaReceived = true;
							}
							if(data.record.VideoReScheduledOn){
								if(data.record.VideoMediaReReceivedOn){
									mediaReceived = true;
								}else{
									mediaReceived = false;
								}
							}
							if(data.record.VideoEdited==1){
								edited = true;
							}
							if(data.record.VideoReScheduledOn){
								if(data.record.VideoReEditedOn){
									edited = true;
								}else{
									edited = false;
								}
							}
							if(data.record.VideoFinalized==1){
								finalized = true;
							}
							var progress = 'Waiting on scheduling';
							if(scheduled&&!edited&&!finalized&&!mediaReceived){
								var progress = 'Waiting on media';
							}
							if(mediaReceived&&!edited&&!finalized){
								var progress = 'Waiting on editing';
							}
							if(edited&&!finalized){
								var progress = 'Waiting to be finalized';
							}
							if(finalized){
								var progress = 'Finalized';
							}
							return "<span style='font-size:14px;'>"+progress+"</span>";
						},
						width: '7%'
					},
					tourSheetLink:{
						title: ' ',
						sorting: false,
						display: function (data) {
							return '<a href="http://www.spotlighthometours.com/admin/users/users.cfm?pg=toursheet&tour='+data.record.tourid+'&user='+data.record.userID+'" target="_new">tour sheet</a>';
						},
						width: '5%'
					}
				}
			});

<?PHP
		if(isset($_REQUEST['filterTemplate'])){
?>
			$('select[name="filterTemplate"]').val(<?PHP echo $_REQUEST['filterTemplate'] ?>);
			$( "#LoadRecordsButton" ).trigger( "click" );
<?PHP
		}else{
?>
			//Load list from server
			$('#videoReportContainer').jtable('load',{ cols: cols, opperators: opperators, values: values, conds: conds });
<?PHP
		}
?>

		});
		
		//Re-load records when user click 'load records' button.
        $('#LoadRecordsButton').click(function (e) {
            e.preventDefault();
			switch($("select[name='filterTemplate']").val()){
				case "0":
					var cols = new Array(
						"isVideoTour"
					);
					var opperators = new Array(
						"="
					);
					var values = new Array(
						"1"
					);
					var conds = new Array(
						"AND"
					);
				break;
				case "1":
					var cols = new Array(
						"VideoFinalized",
						"isVideoTour"
					);
					var opperators = new Array(
						"=",
						"="
					);
					var values = new Array(
						"0",
						"1"
					);
					var conds = new Array(
						"AND",
						"AND"
					);
				break;
				case "2":
					var cols = new Array(
						"VideoMediaReceived",
						"VideoEdited",
						"VideoFinalized",
						"VideoMediaReReceivedOn",
						"VideoReEditedOn",
						"VideoFinalized"
					);
					var opperators = new Array(
						"=",
						"=",
						"=",
						"IS NOT",
						"IS",
						"="
					);
					var values = new Array(
						"1",
						"0",
						"0",
						"NULL",
						"NULL",
						"0"
					);
					var conds = new Array(
						"AND",
						"AND",
						"AND",
						"OR",
						"AND",
						"AND"
					);
				break;
				case "3":
					var cols = new Array(
						"VideoMediaReceived",
						"VideoMediaReceivedOn",
						"isVideoTour",
						"VideoFinalized",
						"VideoScheduledOn",
						"VideoReScheduledOn",
						"isVideoTour",
						"VideoFinalized",
						"VideoMediaReReceivedOn"
					);
					var opperators = new Array(
						"=",
						"IS",
						"=",
						"=",
						"IS NOT",
						"IS NOT",
						"=",
						"=",
						"IS"
					);
					var values = new Array(
						"0",
						"NULL",
						"1",
						"0",
						"NULL",
						"NULL",
						"1",
						"0",
						"NULL"
					);
					var conds = new Array(
						"AND",
						"AND",
						"AND",
						"AND",
						"AND",
						"OR",
						"AND",
						"AND",
						"AND"
					);
				break;
				case "4":
					var cols = new Array(
						"VideoScheduled",
						"VideoReScheduledOn",
						"VideoMediaReceivedOn",
						"VideoMediaReReceivedOn",
						"isVideoTour",
						"VideoFinalized"
					);
					var opperators = new Array(
						"=",
						"IS",
						"IS",
						"IS",
						"=",
						"="
					);
					var values = new Array(
						"0",
						"NULL",
						"NULL",
						"NULL",
						"1",
						"0"
					);
					var conds = new Array(
						"AND",
						"AND",
						"AND",
						"AND",
						"AND",
						"AND"
					);
				break;
				case "5":
					var cols = new Array(
						"VideoEdited",
						"VideoFinalized",
						"VideoReEditedOn",
						"VideoFinalized"
					);
					var opperators = new Array(
						"=",
						"=",
						"IS NOT",
						"="
					);
					var values = new Array(
						"1",
						"0",
						"NULL",
						"0"
					);
					var conds = new Array(
						"AND",
						"AND",
						"OR",
						"AND"
					);
				break;
			}
            $('#videoReportContainer').jtable('load',{ cols: cols, opperators: opperators, values: values, conds: conds });
        });
		$('#dateRangeBtn').click(function (e) {
			e.preventDefault();
			if($('input[name="dateFrom"]').val()){	
			}else{
				alert("Please select an starting date for the date range!");
				return false;
			}
			if($('input[name="dateTo"]').val()){	
			}else{
				alert("Please select an ending date for the date range!");
				return false;
			}
			switch($("select[name='dateType']").val()){
				case "VideoReScheduledOn":
					var cols = new Array(
						"VideoReScheduledOn",
						"VideoReScheduledOn"
					);
					var opperators = new Array(
						">",
						"=",
						"<",
						"="
					);
					var values = new Array(
						"'"+$('input[name="dateFrom"]').val()+"'",
						"'"+$('input[name="dateFrom"]').val()+"'",
						"'"+$('input[name="dateTo"]').val()+"'",
						"'"+$('input[name="dateTo"]').val()+"'"
					);
					var conds = new Array(
						"AND",
						"OR",
						"AND",
						"OR"
					);
					$('#videoReportContainer').jtable('load',{ cols: cols, opperators: opperators, values: values, conds: conds });
				break;
				case "VideoScheduledOn":
					var cols = new Array(
						"VideoScheduledOn",
						"VideoScheduledOn"
					);
					var opperators = new Array(
						">",
						"=",
						"<",
						"="
					);
					var values = new Array(
						"'"+$('input[name="dateFrom"]').val()+"'",
						"'"+$('input[name="dateFrom"]').val()+"'",
						"'"+$('input[name="dateTo"]').val()+"'",
						"'"+$('input[name="dateTo"]').val()+"'"
					);
					var conds = new Array(
						"AND",
						"OR",
						"AND",
						"OR"
					);
					$('#videoReportContainer').jtable('load',{ cols: cols, opperators: opperators, values: values, conds: conds });
				break;
				case "createdOn":
					var cols = new Array(
						"tourProId"
					);
					var opperators = new Array(
						"IS NOT"
					);
					var values = new Array(
						"NULL"
					);
					var conds = new Array(
						"AND"
					);
					var tcols = new Array(
						"createdOn",
						"createdOn"
					);
					var topperators = new Array(
						">",
						"=",
						"<",
						"="
					);
					var tvalues = new Array(
						"'"+$('input[name="dateFrom"]').val()+"'",
						"'"+$('input[name="dateFrom"]').val()+"'",
						"'"+$('input[name="dateTo"]').val()+"'",
						"'"+$('input[name="dateTo"]').val()+"'"
					);
					var tconds = new Array(
						"AND",
						"OR",
						"AND",
						"OR"
					);
					$('#videoReportContainer').jtable('load',{ cols: cols, opperators: opperators, values: values, conds: conds, tcols: tcols, topperators: topperators, tvalues: tvalues, tconds: tconds });
				break;
			}
		});
		$('#searchBtn').click(function (e) {
            e.preventDefault();
			var cols = new Array(
				"tourProId"
			);
			var opperators = new Array(
				"IS NOT"
			);
			var values = new Array(
				"NULL"
			);
			var conds = new Array(
				"AND"
			);
			var tcols = new Array(
				$("select[name='searchType']").val()
			);
			var topperators = new Array(
				"LIKE"
			);
			var tvalues = new Array(
				"'%"+$("input[name='searchVal']").val()+"%'"
			);
			var tconds = new Array(
				"AND"
			);
            $('#videoReportContainer').jtable('load',{ cols: cols, opperators: opperators, values: values, conds: conds, tcols: tcols, topperators: topperators, tvalues: tvalues, tconds: tconds });
        	$('input[name="dateFrom"]').val("");
			$('input[name="dateTo"]').val("");
		});

	</script>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>