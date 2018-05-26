<?php
/*
 * Admin: Packages (Create)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

// Create instances of needed objects
$users = new users($db);

// Require admin
$users->authenticateAdmin();

$memberships = new memberships();
$membershipList = $memberships->getMemberships();

clearCache();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Packages</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-memberships.js" type="text/javascript"></script><!-- Memberships JS file -->
<!-- WYSIWYG Style Sheet -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
 @import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Create Membership</h1>
<div id="membershipMsg" style="margin-bottom:-10px;"></div>
<div class="form_line" >
	<div class="form_direction" >Membership Information</div>
</div>
<div class="form_line" >
	<div class="input_line w_lg" >
		<div class="input_title" >Name</div>
		<input id="name" name="name" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" />
		<div class="input_info" style="display: none;" >
			<div class="info_text" >Membership name.</div>
		</div>
	</div>
	<div class="required_line w_lg" > <span class="required" >required</span> </div>
</div>
<div class="form_line" >
	<div class="input_line w_sm" >
		<div class="input_title" >Trial</div>
		<select name="trialDuration">
			<?PHP
		$maxMonths = 90;
		for($i=0; $i<=$maxMonths; $i++){
	?>
			<option value="<?PHP echo $i ?>"><?PHP echo $i ?> days</option>
			<?PHP
		}
	?>
		</select>
	</div>
</div>
<div class="form_line">
	<div class="input_line w_sm">
		<div class="input_title">Price</div>
		<input id="price" name="price" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" value="">
		<div class="input_info" style="display: none; ">
			<div class="info_text">No "$" or ","</div>
		</div>
	</div>
	<div class="required_line w_sm"> <span class="required">required</span> </div>
</div>
<div class="form_line">
	<div class="input_line w_sm">
		<div class="input_title">Price(Yr)</div>
		<input id="priceyear" name="priceyear" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" value="0.00">
		<div class="input_info" style="display: none; ">
			<div class="info_text">No "$" or ","</div>
		</div>
	</div>
	<div class="required_line w_sm"> <span class="required">required</span> </div>
</div>
<div class="form_line" >
	<div class="input_line w_lg" >
		<div class="input_title" >URL</div>
		<input id="url" name="url" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" />
		<div class="input_info" style="display: none;" >
			<div class="info_text" >url</div>
		</div>
	</div>
</div>
<div class="form_line" >
	<div class="form_direction" >Description</div>
</div>
<div class="form_line text_field" >
	<div class="input_line w_lg" >
		<div class="input_title" ></div>
		<textarea id="tour_descrip" name="description" onkeydown="CharacterCount('tour_descrip', 2000, 'char_count');" onkeyup="CharacterCount('tour_descrip', 2000, 'char_count');" /></textarea>
	</div>
	<div class="required_line w_lg" > <span id="char_count" class="required" >2000 Characters Left</span> </div>
</div>
<div class="form_line">
	<div class="form_direction">Additional Memberships</div>
</div>
<div id="access">
	<div class="form_line text_field">
		<div class="input_line w_lg" style="width:600px;">
			<div class="input_title">&nbsp;</div>
			<select name="memberships[]" multiple="multiple" onkeyup="setFocusOnEnter(event, 'otherBrokerage')" style="height:140px; width:500px; margin-top:10px;">
				<option value="" selected="">This membership comes with...</option>
<?PHP
	foreach($membershipList as $row => $column){
?>	
				<option value="<?PHP echo $column['id'] ?>"><?PHP echo $column['name'] ?></option>
<?PHP
	}
?>			
			</select>
		</div>
	</div>
<br><br>
</div>
<div class="grey-divider" style="margin-bottom:10px;"></div>
</div>
<br/>
<table cellpadding="5">
	<tr>
		<td><div class="button_new button_blue button_mid" onclick="createMembership()">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Save</span>
				<div class="curve curve_right" ></div>
			</div></td>
		<td><div class="button_new button_dgrey button_mid" onclick="window.location='index.php'">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Cancel</span>
				<div class="curve curve_right" ></div>
			</div></td>
	</tr>
</table>
<br/>
</body>
</html>