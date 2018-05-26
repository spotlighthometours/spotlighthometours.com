<?php
/*
 * Admin: Duplicate Tours
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
//showErrors();
clearCache();

// Create instances of needed objects
$users = new users($db);
$brokerages = new brokerages();

// Require admin
$users->authenticateAdmin();

$brokeragesList = $brokerages->listAll();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Duplicate Tours</title>
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
		var isStep4Showing = $('#step4').css('display')!=='none';
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
		if(showStep4){
			if(!isStep4Showing){
				$('#step4').fadeIn('slow');
			}
		}else{
			if(isStep4Showing){
				$('#step4').fadeOut('slow');
			}
		}
	}
	function duplicateTours(){
		var tourIDs = $("#tourIDs").val();
		var userID = $('select[name="userID"]').val();
		ajaxMessage('Duplicating Tour(s)', 'processing');
		var params = 'tourIDs='+tourIDs+'&userID='+userID;
		var url = '../../repository_queries/admin_duplicate_tours.php';
		ajaxQuery(url, params, 'toursDuplicated');
		$("#dupbtn").fadeOut('slow');
	}
	function toursDuplicated(){
		//var newTourIDs = JSON.parse('["26562","43992"]');
		var newTourIDs = JSON.parse(response);
		var newTourIDHTML = '';
		jQuery.each(newTourIDs, function(i, val){
			var tourID = val;
			newTourIDHTML += '<tr>';
          	newTourIDHTML += '	<td>'+tourID+'</td>';
          	newTourIDHTML += '	<td class="list-button" style="width:86px;"><a href="#" onclick="viewTour('+tourID+')">View Tour</a></td>';
        	newTourIDHTML += '</tr>';
		});
		$("#newTourIDs").html(newTourIDHTML);
		showStep1 = false;
		showStep2 = false;
		showStep3 = false;
		showStep4 = true;
		showSteps();
		ajaxMessage('Tour(s) Duplicated', 'success');
	}
</script>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Duplicate Tours</h1>
<div id="step1">
    <h2>Step 1: Enter Tour IDs</h2>
    <i>The tour IDs maybe separated with a comma or entered individually.</i><br/><br/>
    <input type="text" id="tourIDs" name="tourIDs" value="" />
</div>
<br/>
<div id="step2">
    <h2>Step 2: Select User</h2>
    <br/>
    <div class="add-user">
        <div class="form_line" >
            <div class="input_line w_lg" >
                <div class="input_title" >Brokerage</div>
                <select name="brokerageID">
                    <option value="0" selected >Select one...</option>
                    <?PHP
                    foreach($brokeragesList as $row => $column){
                        $desc = '';
                        if(isset($column['brokerageDesc'])&&!empty($column['brokerageDesc'])){
                            $desc = ' - '.$column['brokerageDesc'];
                        }
    ?>
                    <option value="<?PHP echo $column['brokerageID'] ?>"><?PHP echo $column['brokerageName'].$desc; ?></option>
                    <?PHP
                    }
    ?>
                </select>
            </div>
            <div class="required_line w_lg" > <span class="required" >select to load users</span> </div>
        </div>
        <div class="form_line" >
            <div class="input_line w_lg" >
                <div class="input_title" >Users</div>
                <div class="user-select">
                    <select name="userID" disabled="disabled">
                        <option value="0">Select a brokerage to load users!</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="step3">
    <h2>Step 3: Duplicate Tours</h2>
    <br/>
    <div class="button_new button_blue button_mid" onclick="duplicateTours()" id="dupbtn">
        <div class="curve curve_left" ></div>
        <span class="button_caption" >Duplicate</span>
        <div class="curve curve_right" ></div>
    </div>
    <br/>
</div>
<div id="step4">
    <h2>Step 4: View Duplicated Tours</h2>
    <table border="0" cellspacing="0" cellpadding="10" class="list">
      <thead>
        <tr>
          <th>Tour ID</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="newTourIDs">
      	
      </tbody>
    </table>
    <script>
        loadListEffects();
    </script>
    <br/><br/>
    <div class="button_new button_blue button_mid" onclick="window.location='index.php'">
        <div class="curve curve_left" ></div>
        <span class="button_caption" >Duplicate More</span>
        <div class="curve curve_right" ></div>
    </div>
</div>
</body>
</html>