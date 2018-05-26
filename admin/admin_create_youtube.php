<?php
/**********************************************************************************************
Document: admin_create_youtube.php
Creator: Jacob Edmond Kerr
Date: 05-17-13
Purpose: Allow admin to select a slideshow for a tour and send it to the cue for youtube video creation.
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

	$slideshows = new slideshows();
	$users = new users();
	
//=======================================================================
// Document
//=======================================================================

$tourID = $_REQUEST['tourID'];
$tourSlideshows = $slideshows->getSlideShows($tourID);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - Slideshow Youtube Video Creator</title>
        <link type="text/css" href="../repository_css/admin.css" rel="stylesheet" />
		<script src="../repository_inc/jquery-1.6.2.min.js"></script>
        <script src="../repository_inc/template.js"></script>
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
    </head>
    <body style="margin:50px; margin-top:30px;">
    <h1>Slideshow to YouTube Video Creator</h1>
    <p>Please select a slideshow to convert to a YouTube video:</p>
    <select class="slideshows">
    	<option value="0">Please select a slideshow...</option>
<?PHP
	foreach($tourSlideshows as $row => $column){
		if(!$slideshows->videoStatusExist($column['photoTourID'])||$slideshows->videoComplete($column['photoTourID'])){
?>
		<option value="<?PHP echo $column['photoTourID'] ?>"><?PHP echo $column['name'] ?></option>
<?PHP
		}
	}
?>
	</select>
    <input type="button" value="Create Video!" onclick="sendVideo()"/>
    </body>
</html>