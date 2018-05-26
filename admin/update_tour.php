<?php
/**********************************************************************************************
Document: update_tour.php
Creator: William Merfalen
Date: 10-30-2014
Purpose: Updating old tours to the new tour window
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);

	require_once ('../repository_inc/classes/inc.global.php');
	
//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$debug = true;
	
	global $db;
	
	// Require Admin Login
	if (!$debug) {
		require_once ('../repository_inc/require_admin.php');
	}
	
	if( isset($_GET['tourId']) ){
        // Ignore user abort and all the script to run forever
        set_time_limit(0);
        ignore_user_abort(1);
		$res = $db->select("administrators","administratorID=" . intval($_SESSION['admin_id']));
		$email = $res[0]['email'];
		$url = "http://cfd342.cfdynamics.com/repository_queries/admin_update_tour.php?tourId=" . intval($_GET['tourId']) ."&email=" . urlencode($email);
		callURL($url);
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - Update Old Tours</title>
		<!--  JQUERY + JQUERY UI -->
		<script src="../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->
		<script src="../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
		<script src="../repository_inc/template.js" type="text/javascript"></script><!-- For Ajax -->
		<link href="includes/admin_styles.css" rel="stylesheet" type="text/css">
		<style type="text/css" media="screen">
			@import "../repository_css/template.css";
		 	@import "../repository_css/admin-v2.css"; 
		 	</style>
        
        <script type="text/javascript">
				
			$(document).ready(function() {
				$("#updateButton").click(function(event){
					if( $("#tourId").prop("value") ){
						if (confirm("Are you sure you want to update this tour?")) {
                            spinnerAlert('spinner','Please wait while we contact the server');
                            $.ajax({
                                /*url: "http://cfd342.cfdynamics.com/repository_queries/admin_update_tour.php?tourId=" + $("#tourId").prop("value"),
                                */
                                url: 'sleep.php',
                                type: 'POST',
                                async: true,
                                timeout: '5000'
                            }).complete(function(xhr,stat){
                                console.log(stat);
                                ajaxMessage("Your tour has been submitted for update.",'success');
                                $("#spinner").fadeOut();
                            });
						}
					}else{
						$("#error").hide();
						$("#error").html("No tour specified");
						$("#error").fadeIn({duration: 2000});
						
					}
				});
                function submitted(){
                }
				$("#tourId").bind("keyup keydown",function(){
					if( $("#tourId").prop("value") ){
						$("#error").html('');
					}
				});
			});
			
		</script>
		<style>
			
		</style>
    </head>
    <body>
    <h1>Update tour</h1>
    <br>
        <div style='width: 200px;'>
        <b>How it works:</b>
        <p>
            If you have a tour that is using the old tour window (the one running in Flash), and you want
            to update it so that the tour utilizes the new tour window, just enter the tour ID below and 
            click "update" :)
        </p>
        </div>
    	<form id=form method=post >
    	    <div id='error' class='error'></div>
    		<div id='msg' class='msg'></div>
    		<table style='width: 100px;border: 1px solid black;padding: 10px;' class='list'>
    		<tr>
    			<td>
		    		<div class="input_line w_lg">
			            <div class="input_title">Tour ID</div>
					<input id='tourId' name="tourId" type=text>
			        </div>	
    			</td>
    			<td>
    			<div style='float: left;' id=updateButton class="button_new button_blue button_mid" >
		                <div class="curve curve_left"></div>
		                <span class="button_caption">Update</span>
		                <div class="curve curve_right"></div>
		    	</div>
		    	</td>
    		</tr>
			</table>
	        
		    
	  	</form>
        <div id='ajaxMessage'>&nbsp;</div>
        <div id='spinner'>&nbsp;</div>
	</body>
</html>
