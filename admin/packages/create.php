<?php
/*
 * Admin: Packages (Create)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

// Create instances of needed objects
$brokerages = new brokerages($db);
$products = new products();
$tours = new tours($db);
$users = new users($db);

// Require admin
$users->authenticateAdmin();

// Pull needed information
$brokeragesList = $brokerages->listAll();
$products = $products->getList();
$tourTypes = $tours->getTourTypeList();

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
<script src="../../repository_inc/admin-packages.js" type="text/javascript"></script><!-- Packages JS file -->
<script src="../../repository_inc/jquery.wysiwyg.js" type="text/javascript"></script><!-- WYSIWYG JS file -->
<script src="../../repository_inc/wysiwyg-controls/wysiwyg.colorpicker.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../../repository_inc/wysiwyg-controls/wysiwyg.cssWrap.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../../repository_inc/wysiwyg-controls/wysiwyg.image.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../../repository_inc/wysiwyg-controls/wysiwyg.link.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../../repository_inc/wysiwyg-controls/wysiwyg.table.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<link rel="Stylesheet" type="text/css" href="../../repository_css/jquery.wysiwyg.css" /><!-- WYSIWYG Style Sheet -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
 @import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Create Package</h1>
<div id="packageMsg" style="margin-bottom:-10px;"></div>
<div class="form_line" >
	<div class="form_direction" >Package Information</div>
</div>
<div class="form_line" >
	<div class="input_line w_lg" >
		<div class="input_title" >Name</div>
		<input id="name" name="name" onFocus="ToggleInputInfo(this, 1);" onBlur="ToggleInputInfo(this, 0);" />
		<div class="input_info" style="display: none;" >
			<div class="info_text" >Package name.</div>
		</div>
	</div>
	<div class="required_line w_lg" > <span class="required" >required</span> </div>
</div>
<div class="form_line" style="height:auto; margin-bottom:5px;">
	<div class="input_line w_lg" >
		<div class="input_title" style="width:auto; padding-left:10px;">Description</div>
	</div>
</div>
<textarea id="description" name="description" style="width:433px;" /></textarea>
<div style="margin-top:25px;"></div>
<div class="form_line" style="height:auto; margin-bottom:5px;">
	<div class="input_line w_lg" >
		<div class="input_title" style="width:auto; padding-left:10px;">Terms &amp; Conditions</div>
	</div>
</div>
<textarea id="terms" name="terms" style="width:433px;" /></textarea>
<div style="margin-top:25px;"></div>
<div class="form_line" >
	<div class="input_line w_sm" >
		<div class="input_title" >Bill Broker?</div>
		<select name="broker_billable">
			<option value="1">Yes</option>
			<option value="0" SELECTED >No</option>
		</select>
		<div class="input_info" style="display: none;" >
			<div class="info_text" >No &quot;$&quot; or &quot;,&quot;</div>
		</div>
	</div>
	<div class="required_line w_sm" > <span class="required" >required</span></div>
</div>
<div class="form_line" >
	<div class="input_line w_sm" >
		<div class="input_title" >Price</div>
		<input id="price" name="price" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" value="" />
		<div class="input_info" style="display: none;" >
			<div class="info_text" >No "$" or ","</div>
		</div>
	</div>
	<div class="required_line w_sm" > <span class="required" >required</span> </div>
</div>
<div class="form_line" >
	<div class="input_line w_sm" >
		<div class="input_title" >Monthly?</div>
		<select name="monthly">
			<option value="1">Yes</option>
			<option value="0">No</option>
		</select>
	</div>
</div>
<div id="monthlyPriceHolder">
	<div class="form_line">
		<div class="input_line w_sm" >
			<div class="input_title" >Mon. Price</div>
			<input id="monthlyPrice" name="monthlyPrice" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" value="" />
			<div class="input_info" style="display: none;" >
				<div class="info_text" >No "$" or ","</div>
			</div>
		</div>
		<div class="required_line w_sm" > <span class="required" >required</span> </div>
	</div>
	<div class="form_line" >
		<div class="input_line w_sm" >
			<div class="input_title" >Months</div>
			<select name="contract_months">
	<?PHP
		$maxMonths = 11;
		for($i=0; $i<=$maxMonths; $i++){
	?>
				<option value="<?PHP echo $i ?>"><?PHP echo $i ?></option>
	<?PHP
		}
	?>
			</select>
		</div>
	</div>
	<div class="form_line" >
		<div class="input_line w_sm" >
			<div class="input_title" >Years</div>
			<select name="contract_years">
	<?PHP
		$maxYears = 10;
		for($i=0; $i<=$maxYears; $i++){
	?>
				<option value="<?PHP echo $i ?>"><?PHP echo $i ?></option>
	<?PHP
		}
	?>
			</select>
		</div>
	</div>
</div>
<div class="form_line" >
	<div class="form_direction" >Package Items</div>
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
	<div class="or">&nbsp;&nbsp;&nbsp;or</div>
	<div class="form_line" >
		<div class="input_line w_lg" >
			<div class="input_title" >Product</div>
			<select name="productID[]">
				<option value="0">Select...</option>
				<?PHP
	foreach($products as $row => $column){
?>
				<option value="<?PHP echo $column['productID']?>"><?PHP echo $column['productName']?></option>
				<?PHP
	}
?>
			</select>
		</div>
	</div>
	<div class="form_line widthAuto left" >
		<div class="input_line w_sm" >
			<div class="input_title" >Quantity</div>
			<select name="quantity[]" id="quantity" >
			<?PHP
				$quantityMax = 50;
				for($i=0; $i<=50; $i++){
			?>
				<option value="<?PHP echo $i ?>"><?PHP echo($i==0)?'unlimited':$i; ?></option>
			<?PHP
				}
			?>
			</select>
		</div>
	</div>
	<div class="left"> &nbsp;&nbsp;&nbsp;</div>
	<div class="form_line widthAuto left" >
		<div class="input_line w_sm" >
			<div id="discount">
				<div class="input_title" >Discount</div>
				<select name="discount[]" id="discount">
					<?PHP outputPercentOptions(1.0); ?>
				</select>
			</div>
			<div id="price" style="display:none;">
				<div class="input_title" >Price</div>
				<input id="itemPrice" name="itemPrice[]" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" value="0.00" />
				<div class="input_info" style="display: none;" >
					<div class="info_text" >No "$" or ","</div>
				</div>
			</div>
			<div class="input_option" style="width:50px;">
				%<input type="checkbox" id="ispercent" value="1" style="width:auto; margin-top:7px; margin-left:10px;" checked onChange="toggleItemPrice(this)"/> 
			</div>
		</div>
	</div>
	<div class="clear"></div>
    <div class="ismaster"><input type="checkbox" id="ismaster" name="ismaster" value="1"  /> Disable all of the below items when the user has 0 credits left for the above item.</div>
	<div class="grey-divider" style="margin-bottom:10px;"></div>
</div>
<div class="form_line">
	<div class="button_new button_dgrey button_sm" onclick="addItem();">
		<div class="curve curve_left" ></div>
		<span class="button_caption" style="font-weight:bold;">+ ITEM</span>
		<div class="curve curve_right" ></div>
	</div>
</div>
<div class="form_line" >
	<div class="form_direction" >Package Access</div>
</div>
<div id="access">
	<div class="form_line text_field" >
		<div class="input_line w_lg" style="width:600px;">
			<div class="input_title" >Brokerage</div>
			<select name="brokerID[]" multiple="multiple" onkeyup="setFocusOnEnter(event, 'otherBrokerage')" style="height:140px; width:500px; margin-top:10px;">
				<option value="" selected >Select one...</option>
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
				<option value="0">None/Other (Enter right)</option>
			</select>
		</div>
	</div>
	<table>
		<tr>
			<td>
				FINALIZED
			</td>
			<td>
				<input type="checkbox" name="finalized" value="1" />
			</td>
		</tr>
	</table>
	<br/>
	<div class="grey-divider" style="margin-bottom:10px;"></div>
</div>
<br/>
<table cellpadding="5">
	<tr>
		<td>
			<div class="button_new button_blue button_mid" onclick="createPackage()">
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
	savePackageItemsHTML();
	packageAccessHTML();
</script>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>