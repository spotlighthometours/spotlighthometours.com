<?php
/*
 * Admin: Floorplans (Create)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();

// Create instances of needed objects
$users = new users($db);
$floorplans = new floorplans();

// Require admin
$users->authenticateAdmin();

clearCache();

if(isset($_REQUEST['tourID'])&&!empty($_REQUEST['tourID'])){
	$tourID = intval($_REQUEST['tourID']);
	$floorplanList = $floorplans->getFloorplans($tourID);
}else{
	die('<h1>tourID required! Please pass the tourID as a parameter to this page!</h1>');
}

// Check if the info to create a floorplan has been passed, if so lets create the floorplan and then redirect to the floorplan home page if successful and output error if not.
if(isset($tourID)&&isset($_REQUEST['label'])&&isset($_FILES["Filedata"])&&is_uploaded_file($_FILES["Filedata"]["tmp_name"])){
	$floorplanID = $floorplans->createFloorplan();
	if($floorplanID){
		header( 'Location: index.php?tourID='.$tourID.'&alert=Floorplan created!' ) ;
	}else{
		$errorMsg = $floorplans->errors->listErrors();
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Create Floorplan</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-floorplans.js" type="text/javascript"></script><!-- Floorplan JS file -->
<!-- WYSIWYG Style Sheet -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
 @import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Create Floorplan</h1>
<div id="floorplanMsg" style="margin-bottom:-10px;"></div>
<div class="form_line" >
	<div class="form_direction" >Floorplan Information</div>
</div>
<form action="create.php" method="POST" id="floorPlanCreateFrm" enctype="multipart/form-data">
	<input type="hidden" name="tourID" value="<?PHP echo $tourID ?>" />
    <div class="form_line" >
        <div class="input_line w_lg" >
            <div class="input_title" >Label</div>
            <input id="label" name="label" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" />
            <div class="input_info" style="display: none;" >
                <div class="info_text" >Floorplan label. I.e: 1rst Floor</div>
            </div>
        </div>
        <div class="required_line w_lg" > <span class="required" >required</span> </div>
    </div>
    <div class="form_line">
        <div class="input_line w_lg">
            <div class="input_title">Img</div>
            <input type="file" name="Filedata" class="file" accept="image/gif, image/jpeg, image/png">
        </div>
        <div class="required_line w_lg"> <span class="required">required</span> </div>
    </div>
</form>
<div class="grey-divider" style="margin-bottom:10px;"></div>
</div>
<br/>
<table cellpadding="5">
	<tr>
		<td><div class="button_new button_blue button_mid" onclick="createFloorplan()">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Save</span>
				<div class="curve curve_right" ></div>
			</div></td>
		<td><div class="button_new button_dgrey button_mid" onclick="window.location='index.php?tourID=<?PHP echo $tourID ?>'">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Cancel</span>
				<div class="curve curve_right" ></div>
			</div></td>
	</tr>
</table>
<br/>
<?PHP
	if(isset($errorMsg)){
?>
<script>
		outputError('floorplanMsg', "<?PHP echo $errorMsg ?>");
</script>
<?PHP
	}
?>
<?PHP
	if(isset($alertMsg)){
?>
<script>
		outputAlert('floorplanMsg', "<?PHP echo $alertMsg ?>");
</script>
<?PHP
	}
?>
</body>
</html>