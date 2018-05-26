<?php
/*
 * Admin: System Settings
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

showErrors();

// Create instances of needed objects
$users = new users($db);
$settings = new settings();

// Require admin
$users->authenticateAdmin();

$runAmexSep = $settings->getSetting(4,'runAmexSep', 'system', 1);
$runAmexSep = $runAmexSep[0]['value'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>System Settings</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>System Wide Settings</h1>
<?PHP
	if(isset($_REQUEST['error'])){
?>
<div class="errors"><?PHP echo $_REQUEST['error'] ?></div>
<?PHP
	}
?>
<?PHP
	if(isset($_REQUEST['alert'])){
?>
<div class="alert"><?PHP echo $_REQUEST['alert'] ?></div>
<?PHP
	}
?>
<div class="form_line" >
	<div class="form_direction">Transactions</div>
</div>
<div class="form_line">
	<div class="input_line w_sm">
		<div class="input_title">Amex Acnt</div>
		<select name="runAmexSep">
			<option value="true" <?PHP echo ($runAmexSep=="true")?'SELECTED':''; ?>>On</option>
            <option value="false" <?PHP echo ($runAmexSep=="false")?'SELECTED':''; ?>>Off</option>			
		</select>
	</div>
</div>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
<script>
	$("input, select").change(function(){
		updateOption($(this).attr('name'), $(this).val());
	});
	function updateOption(name, value){
		ajaxMessage('Saving Setting...', 'processing');
		var url = '../../repository_queries/save-setting.php';
		var params = 'typeID=4&userType=system&userID=1&name='+name+'&value='+value;
		ajaxQuery(url, params, 'settingSaved');
	}
	function settingSaved(){
		ajaxMessage('Setting Saved!', 'success');
	}
</script>
</body>
</html>