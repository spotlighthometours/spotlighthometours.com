<?php
/*
 * Admin: Suggestions (Create)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

// Create instances of needed objects
$tours = new tours($db);
$users = new users($db);

// Require admin
$users->authenticateAdmin();

// Pull needed information
$tourTypes = $tours->getTourTypeList();

clearCache();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Create Tour Suggestion</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-suggestions.js" type="text/javascript"></script><!-- Packages JS file -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
 @import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Create Suggestion</h1>
<div id="suggestionMsg" style="margin-bottom:-10px;"></div>
<div class="form_line" >
	<div class="form_direction" >Suggestion Range</div>
</div>
  <div class="form_line">
    <div class="input_line w_sm">
      <div class="input_title">Type</div>
      <select name="type">
	  	<option value="1">Price</option>
		<option value="2">Sq Ft</option>
	  </select>
      </div>
	</div>
  </div>
  <div class="form_line widthAuto left">
    <div class="input_line w_sm">
      <div class="input_title">From</div>
      <input id="from" name="from" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" onkeyup="setFocusOnEnter(event, 'lastName')" />
      <div class="input_info" style="display: none;" >
        <div class="info_text" ># only. No decimal.</div>
      </div>
    </div>
    <div class="required_line w_sm" ><span class="required" >required</span> </div>
  </div>
  <div class="left"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>
  <div class="form_line widthAuto left" >
    <div class="input_line w_sm" >
      <div class="input_title" >To</div>
      <input id="to" name="to" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" onkeyup="setFocusOnEnter(event, 'userType')" />
      <div class="input_info" style="display: none;" >
        <div class="info_text" >Empty = To+</div>
      </div>
    </div>
   </div>
  </div>
  <div class="clear"></div>
<div class="form_line" >
	<div class="form_direction" >Tour Types</div>
</div>
<div id="items">
	<div class="form_line" >
		<div class="input_line w_lg" >
			<div class="input_title" >Tour</div>
			<select name="tourTypeID[]">
				<option value="0">Select...</option>
				<?PHP
	foreach($tourTypes as $row => $column){
?>
				<option value="<?PHP echo $column['tourTypeID']?>"><?PHP echo $column['tourTypeName']?></option>
				<?PHP
	}
?>
			</select>
		</div>
	</div>
</div>
<div class="form_line">
	<div class="button_new button_dgrey button_sm" onclick="addItem();">
		<div class="curve curve_left" ></div>
		<span class="button_caption" style="font-weight:bold;">+ TOUR</span>
		<div class="curve curve_right" ></div>
	</div>
</div>
	<br/>
	<div class="grey-divider" style="margin-bottom:10px;"></div>
</div>
<br/>
<table cellpadding="5">
	<tr>
		<td>
			<div class="button_new button_blue button_mid" onclick="createSuggestion()">
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
<script>
	saveTourTypeHTML();
</script>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>