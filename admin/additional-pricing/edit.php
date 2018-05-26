<?php
/*
 * Admin: Additional Pricing (Edit)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();

// Create instances of needed objects
$users = new users($db);
$pricing = new pricing();

// Require admin
$users->authenticateAdmin();

clearCache();

if((isset($_REQUEST['addPricingID']))){
	$addPricingID = $_REQUEST['addPricingID'];
	$addPricingInfo = $pricing->getAdditonalInfo($addPricingID);
}else{
	die('<h1>addPricingID required! Please pass addPricingID to this page as a parameter.</h1>');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Additional Pricing</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-addpricing.js" type="text/javascript"></script><!-- Floorplan JS file -->
<script>
	var addPricingCondParams = <?PHP echo json_encode($pricing->getAdditionalCondParams()); ?>;
	var itemType = '<?PHP echo $type ?>';
	var itemID = '<?PHP echo $typeID ?>';
	addPricingID = <?PHP echo $addPricingID ?>;
	addPricingConds = <?PHP echo json_encode($pricing->getAdditionalConds($addPricingID)) ?>;
	window.onload = function(){
		showConditions();
	}
</script>
<!-- WYSIWYG Style Sheet -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
 @import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Edit Additional Pricing</h1>
<div id="addpricingMsg" style="margin-bottom:-10px;"></div>
<div class="form_line" >
  <div class="form_direction" >Additional Pricing Information</div>
</div>
<input type="hidden" name="addPricingID" value="<?PHP echo $addPricingID ?>" />
<div class="form_line">
  <div class="input_line w_lg">
    <div class="input_title" >Label</div>
    <input id="label" name="label" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" value="<?PHP echo $addPricingInfo['label'] ?>" />
    <div class="input_info" style="display: none;">
      <div class="info_text" >This will show on the checkout.</div>
    </div>
  </div>
  <div class="required_line w_lg" > <span class="required" >required</span> </div>
</div>
<div class="form_line">
  <div class="input_line w_sm">
    <div class="input_title" >Amount</div>
    <input id="amount" name="amount" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" value="<?PHP echo number_format($addPricingInfo['amount'],2) ?>" />
    <div class="input_info" style="display: none;">
      <div class="info_text">I.E: 20.00</div>
    </div>
  </div>
  <div class="required_line w_sm" > <span class="required">required</span> </div>
</div>
<div class="form_line" >
  <div class="form_direction">Additional pricing conditions</div>
  <div class="form_direction_cta" onclick="getAddCondPopup()">Add Condition</div>
</div>
<div class="conditions">
</div>
<div class="grey-divider" style="margin-bottom:10px;"></div>
</div>
<br/>
<table cellpadding="5">
	<tr>
		<td><div class="button_new button_blue button_mid" onclick="updateAddPricing()">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Save</span>
				<div class="curve curve_right" ></div>
			</div></td>
		<td><div class="button_new button_dgrey button_mid" onclick="history.back()">
  <div class="curve curve_left" ></div>
  <span class="button_caption" >Cancel</span>
  <div class="curve curve_right"></div>
</div></td>
	</tr>
</table>
<div class="modal-bg"></div>
<div class="modal">
  <div class="content"> </div>
</div>
</body>
</html>