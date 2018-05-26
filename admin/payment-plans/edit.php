<?php
/*
 * Admin: Payment Plans (Edit)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();

// Create instances of needed objects
$brokerages = new brokerages($db);
$users = new users($db);
$paymentplans = new paymentplans();

// Require admin
$users->authenticateAdmin();

// Pull needed information
$brokeragesList = $brokerages->listAll();

clearCache();

// Force the payment plan ID (required)
if(isset($_REQUEST['id'])){
	$paymentplans->load(intval($_REQUEST['id']));
	//$_SESSION['paymentplans'] = serialize($paymentplans);
}else{
	die("<h1>The payment plan ID is required! Please pass the payment plan ID as: id</h1>");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Payment Plan</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery.mousewheel.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/jquery-ui-1.10.4.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/globalize.js" type="text/javascript"></script><!-- Globalize JS -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-paymentplans.js" type="text/javascript"></script><!-- Payment Plan JS file -->
<style type="text/css" media="screen">
@import "../../repository_css/jquery-ui-1.10.2.custom.css";
@import "../../repository_css/template.css";
@import "../../repository_css/admin-v2.css";
</style>
<script>
	id = <?PHP echo $paymentplans->id ?>;
	isIntPercent = <?PHP echo $paymentplans->isIntPercent ?>;
	isUpFrntPercent = <?PHP echo $paymentplans->isUpFrntPercent ?>;
</script>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Edit Payment Plan</h1>
<div id="paymentplanMsg" style="margin-bottom:-10px;"></div>
<div class="form_line">
	<div class="form_direction" >Payment Plan Information</div>
</div>
<div class="form_line" >
	<div class="input_line w_lg" >
		<div class="input_title" >Name</div>
		<input id="title" name="title" value="<?PHP echo $paymentplans->title ?>" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" />
		<div class="input_info" style="display: none;" >
			<div class="info_text" >Payment Plan Label.</div>
		</div>
	</div>
	<div class="required_line w_lg" > <span class="required" >required</span> </div>
</div>
<div class="form_line" >
	<div class="input_line w_sm" >
		<div class="input_title" >Up Front</div>
		<input name="upFront" style="width:98px;" value="<?PHP echo ($paymentplans->isUpFrntPercent)?$paymentplans->upFront*100:$paymentplans->upFront; ?>"/>
        <div class="input_option" style="width:50px;">
			%<input type="checkbox" name="isUpFrntPercent" value="1" <?PHP echo ($paymentplans->isUpFrntPercent)?"checked":""; ?> style="width:auto; margin-top:7px; margin-left:10px;" onchange="toggleUpFrontType(this)"> 
		</div>
	</div>
	<div class="required_line w_sm" > <span class="required" >required</span> </div>
</div>
<div class="form_line" >
	<div class="input_line w_sm" >
		<div class="input_title" >Months</div>
		<input id="months" name="months" value="<?PHP echo $paymentplans->months; ?>" style="width:100px;"/>
	</div>
	<div class="required_line w_sm" > <span class="required" >required</span> </div>
</div>
<div class="form_line" >
	<div class="input_line w_sm" >
		<div class="input_title" >Interest</div>
		<input id="interest" name="interest"  value="<?PHP echo ($paymentplans->isIntPercent)?$paymentplans->interest*100:$paymentplans->interest; ?>" style="width:100px;"/>
        <div class="input_option" style="width:50px;">
			%<input type="checkbox" name="isIntPercent" value="1" <?PHP echo ($paymentplans->isIntPercent)?"checked":""; ?> style="width:auto; margin-top:7px; margin-left:10px;" onchange="toggleInterestType(this)"> 
		</div>
	</div>
	<div class="required_line w_sm" > <span class="required" >required</span> </div>
</div>
<div class="form_line" >
	<div class="form_direction" >Payment Plan Access</div>
</div>
<div id="access">
	<div class="form_line text_field" >
		<div class="input_line w_lg" style="width:600px;">
			<div class="input_title" >Brokerage</div>
			<select name="brokerID[]" multiple="multiple" onkeyup="setFocusOnEnter(event, 'otherBrokerage')" style="height:140px; width:500px; margin-top:10px;">
				<option value="select" selected >Select one...</option>
				<?PHP
				foreach($brokeragesList as $row => $column){
					$desc = '';
					if(isset($column['brokerageDesc'])&&!empty($column['brokerageDesc'])){
						$desc = ' - '.$column['brokerageDesc'];
					}
?>
				<option value="<?PHP echo $column['brokerageID'] ?>" <?PHP echo (in_array($column['brokerageID'],$paymentplans->brokerages,true))?'selected="selected"':''; ?>><?PHP echo $column['brokerageName'].$desc; ?></option>
				<?PHP
				}
?>
			</select>
		</div>
	</div>
	<div class="form_line text_field" >
		<div class="input_line w_lg" style="width:600px; height:190px;">
			<div class="input_title" >Users</div>
			<div onclick="getAddUserPopUpHTML()" style="padding-top:10px; font-weight:bold;cursor:pointer;">Add User(+)</div>
            <select name="userID[]" multiple="multiple" onkeyup="setFocusOnEnter(event, 'otherBrokerage')" style="height:140px; width:500px; margin-top:10px;">
            	<option value="0">None</option>
<?PHP
	foreach($paymentplans->users as $userindex => $userID){
		$users->userID = $userID;
		unset($users->firstName);
		unset($users->lastName);
		unset($users->brokerageID);
		$firstName = $users->get('firstName');
		$lastName = $users->get('lastName');
		$brokerage = $brokerages->getBrokerage($users->get('brokerageID'));
		$optionText = (!empty($brokerage['brokerageDesc']))?$lastName.', '.$firstName.' ('.$brokerage['brokerageName'].' - '.$brokerage['brokerageDesc'].')':$lastName.', '.$firstName.' ('.$brokerage['brokerageName'].')';
?>
				<option value="<?PHP echo $userID ?>" selected><?PHP echo $optionText ?></option>
<?PHP
	}
?>
            </select>
		</div>
	</div>
	<br/>
	<div class="grey-divider" style="margin-bottom:10px;"></div>
</div>
<br/>
<table cellpadding="5">
	<tr>
		<td>
			<div class="button_new button_blue button_mid" onclick="update()">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Save</span>
				<div class="curve curve_right" ></div>
			</div>
		</td>
		<td>
			<div class="button_new button_dgrey button_mid" onclick="window.location='index.php'">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Cancel</span>
				<div class="curve curve_right" ></div>
			</div>
		</td>
	</tr>
</table>
<br/>
<div class="modal-bg"></div>
<div class="modal">
    <div class="content">
    </div>
</div>
</body>
</html>