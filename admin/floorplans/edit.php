<?php
/*
 * Admin: Floorplans (Edit)
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

if(isset($_REQUEST['floorplanID'])&&!empty($_REQUEST['floorplanID'])){
	$floorplanID = $_REQUEST['floorplanID'];
	$floorplans->loadPlan($floorplanID);
}else{
	die('<h1>floorplanID required! Please pass the floorplanID as a parameter to this page!</h1>');
}

// Check if the info to update a floorplan has been passed, if so lets update the floorplan and then redirect to the floorplan home page if successful and output error if not.
if(isset($floorplanID)&&isset($_REQUEST['label'])){
	if($floorplans->updateFloorplan()){
		header( 'Location: index.php?tourID='.$floorplans->tourID.'&alert=Floorplan updated!' ) ;
	}else{
		$errorMsg = $floorplans->errors->listErrors();
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Floorplan</title>
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
<h1>Edit Floorplan</h1>
<div id="floorplanMsg" style="margin-bottom:-10px;"></div>
<div class="form_line" >
	<div class="form_direction" >Floorplan Information</div>
</div>
<form action="edit.php" method="POST" id="floorPlanUpdateFrm" enctype="multipart/form-data">
	<input type="hidden" name="floorplanID" value="<?PHP echo $floorplanID ?>" />
    <div class="form_line" >
        <div class="input_line w_lg" >
            <div class="input_title" >Label</div>
            <input id="label" name="label" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" value="<?PHP echo $floorplans->label ?>" />
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
        <div class="required_line w_lg"> <span class="required">select file to update, leave empty to keep saved img.</span> </div>
    </div>
</form>
<div class="grey-divider" style="margin-bottom:10px;"></div>
</div>
<br/>
<table cellpadding="5">
	<tr>
		<td><div class="button_new button_blue button_mid" onclick="updateFloorplan()">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Save</span>
				<div class="curve curve_right" ></div>
			</div></td>
		<td><div class="button_new button_dgrey button_mid" onclick="window.location='index.php?tourID=<?PHP echo $floorplans->tourID ?>'">
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