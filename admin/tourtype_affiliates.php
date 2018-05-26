<?PHP
/*
 * Admin Tour Type Affiliates Control File
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
showErrors();

// Create instance of the tour object
$tour = new tours();
$brokerages = new brokerages();
$products = new products();
$affiliates = new affiliates();
$blacklist = new blacklist();
$pricing = new pricing();

// Get all affiliates/photographers
if(isset($_GET['keyword'])&&!empty($_GET['keyword'])){
	$photographers = $affiliates->getPhotographers($_GET['keyword']);
}else{
	$photographers = $affiliates->getPhotographers();
}
$photographersCount = count($photographers);

// Get blacklisted affiliates for this tour type
$blackListedAffiliates = $blacklist->getBlacklistedCategories('tour', $_REQUEST['id'], 'affiliate');
$blackListedAffiliatesByID = array();
foreach($blackListedAffiliates as $row => $columns){
	if($columns['permission']==0){
		$blackListedAffiliatesByID[] = $columns['categoryID'];
	}
}

// Get affiliate pricing
$affiliatePricing = $pricing->getPricing('tour', $_REQUEST['id'], 'affiliate');
$affiliatePricingByID = array();
foreach($affiliatePricing as $row => $columns){
	$affiliatePricingByID[$columns['categoryID']] = $columns['price'];
}

// Number of tour types per column
if(isset($_REQUEST['columns'])&&!empty($_REQUEST['columns'])){
	$columns = $_REQUEST['columns'];
}else{
	$columns = 2;
}
$number_per_column = $photographersCount / $columns;
$number_per_column = round($number_per_column, 0, PHP_ROUND_HALF_UP);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link type="text/css" href="../../repository_css/admin.css" rel="stylesheet" />
<script src="../../repository_inc/jquery-1.5.min.js" type="text/javascript"></script>
<script src="../../repository_inc/admin.js"></script>
<script>
	function updateSelection(id){
		if($('#affiliate_'+id).is(':checked')){
			removeSelection(id);
		}else{
			addSelection(id);
		}
	}
	
	function addSelection(id){
		query = "action=add&id="+id+"&tourTypeID=<?PHP echo $_GET['id']; ?>";
		var url = "../repository_queries/admin_affiliate_updatetourtypes.php?"+query;
		params = "";
		ajaxQuery(url, params, 'nothing');
	}
	
	function removeSelection(id){
		query = "action=remove&id="+id+"&tourTypeID=<?PHP echo $_GET['id']; ?>";
		var url = "../repository_queries/admin_affiliate_updatetourtypes.php?"+query;
		params = "";
		ajaxQuery(url, params, 'nothing');
	}
	
	function showUnchecked(){
		showAll();
		$('input[type=checkbox]').each(function () {
			if(this.checked){
				$(this).parent().hide();
			}
		});
	}
	
	function showChecked(){
		showAll();
		$('input[type=checkbox]').each(function () {
			if(!this.checked){
				$(this).parent().hide();
			}
		});
	}
	
	function showAll(){
		$('input[type=checkbox]').each(function () {
			$(this).parent().show();
		});
	}
	
	function selectAll(){
		var proceed = confirm("Are you sure you want to add this tour type to all affiliates?");
		if(proceed){
			$('input[type=checkbox]').each(function () {
				$(this).parent().show();
				if(!this.checked){
					$(this).attr('checked', true);
					var id = $(this).attr('id');
					var exploded = id.split('_');
					id = exploded[1];
					removeSelection(id);
				}
			});
			alert('Adding all Affiliate to this tour type!');
		}
	}
	
	function deselectAll(){
		var proceed = confirm("Are you sure you want to remove all affiliates from this tour type?");
		if(proceed){
			$('input[type=checkbox]').each(function () {
				$(this).parent().show();
				if(this.checked){
					$(this).attr('checked', false);
					var id = $(this).attr('id');
					var exploded = id.split('_');
					id = exploded[1];
					addSelection(id);
				}
			});
			alert('Removing all Affiliates from this tour type!');
		}
	}
	
	function disallowNonNumeric(evt){
		if ( evt.keyCode == 46 || evt.keyCode == 8 || evt.keyCode == 110 || evt.keyCode == 190 || (evt.keyCode>95 && evt.keyCode<106)) {
            // let it happen, don't do anything
        }else {
            // Ensure that it is a number and stop the keypress
            if (evt.keyCode < 48 || evt.keyCode > 57 ) {
                evt.preventDefault(); 
            }   
        }
	}
	
	function updatePrice(element){
		var id = element.id;
		var exploded = id.split('_');
		id = exploded[1];
		if(element.value==""){
			query = "action=remove&id="+id+"&update=price&tourTypeID=<?PHP echo $_GET['id']; ?>";
			var url = "../repository_queries/admin_affiliate_updatetourtypepricing.php?"+query;
			params = "";
			ajaxQuery(url, params, 'nothing');
		}else{
			var strPattern = /^\s*(\+|-)?((\d+(\.\d\d)?)?(\.\d\d))\s*$/;  
			if(element.value.search(strPattern)== -1){
				element.focus();
				element.value = '';
				alert('Please enter the proper money format: 1.22');
			}else{
				query = "action=add&id="+id+"&tourTypeID=<?PHP echo $_GET['id']; ?>&update=price&price="+element.value;
				var url = "../repository_queries/admin_affiliate_updatetourtypepricing.php?"+query;
				params = "";
				ajaxQuery(url, params, 'nothing');
			}
		}
	}
	
	function showPricing(){
		$('input[type=text]').each(function () {
			$(this).parent().show();
		});
	}
	
	function hidePricing(){
		$('input[type=text]').each(function () {
			var id = $(this).attr('id');
			if(id!=="keyword"){
				$(this).parent().hide();
			}
		});
	}
</script>
<style>
	a{
		color:black;
		text-decoration:none;
	}
	a:hover{
		text-decoration:underline;
	}
	td{
		white-space:nowrap;
	}
</style>
</head>
<body style="padding:20px;" onLoad="hidePricing()">
<h1>Available Affiliates</h1>
<form acion="" method="get">
  <input name="id" type="hidden" value="<?PHP echo $_GET['id']; ?>" />
  <table style="width:auto; margin:0px;">
    <tr>
      <td align="left">
      	Columns: 
        <select onchange="window.location=window.location.href+'&columns='+this.value;">
        	<option value="1" <?PHP echo ($columns==1)?'SELECTED':''?>>1</option>
            <option value="2" <?PHP echo ($columns==2)?'SELECTED':''?>>2</option>
            <option value="3" <?PHP echo ($columns==3)?'SELECTED':''?>>3</option>
            <option value="4" <?PHP echo ($columns==4)?'SELECTED':''?>>4</option>
            <option value="5" <?PHP echo ($columns==5)?'SELECTED':''?>>5</option>
            <option value="6" <?PHP echo ($columns==6)?'SELECTED':''?>>6</option>
        </select>
      </td>
    </tr>
   	<tr>
      <td align="left"><input name="keyword" type="text" value="" id="keyword" />
        <input type="submit" name="action" id="button" value="search" /></td>
    </tr>
    <tr>
      <td align="left"> [ <a href="javascript:showPricing()">show pricing</a> ] [ <a href="javascript:hidePricing()">hide pricing</a> ] [ <a href="javascript:showUnchecked()">show unselected</a> ] [ <a href="javascript:showChecked()">show selected</a> ] [ <a href="javascript:showAll()">show all</a> ] [ <a href="javascript:selectAll()">select all</a> ] [ <a href="javascript:deselectAll()">deselect all</a> ]</td>
    </tr>
  </table>
</form>
<table border="0" cellpadding="10" cellspacing="0" style="width:auto; margin:0px;">
  <tr>
    <?PHP
	$count = 0;
	foreach($photographers as $row => $column){
		if($count==0){
			echo '<td valign="top" style="vertical-align:text-top;">';
		}
?>
    <div id="affiliateWrapper_<?PHP echo $column['photographerID']; ?>">
      <input name="affiliates[]" type="checkbox" id="affiliate_<?PHP echo $column['photographerID']; ?>" value="<?PHP echo $column['photographerID'] ?>" <?PHP echo (in_array($column['photographerID'], $blackListedAffiliatesByID))?'':'CHECKED' ?> onchange="updateSelection(<?PHP echo $column['photographerID']; ?>)"/>
      <?PHP 
		echo '<a href="http://www.spotlighthometours.com/admin/photographers/photographers.cfm?pg=editphotographers&rep='.$column['photographerID'].'" target="_blank">';
		echo $column['fullName'];
		echo '</a>';
		$affiliatePrice = (isset($affiliatePricingByID[$column['photographerID']]))?$affiliatePricingByID[$column['photographerID']]:'';
		echo '<span><br/>Price: <input type="text" id="affiliateprice_'.$column['photographerID'].'" value="'.$affiliatePrice.'" size="5" onkeydown="disallowNonNumeric(event)" onchange="updatePrice(this)"/></span>';
	?>
      <br/>
    </div>
    <?PHP
		$count++;
		if($count==$number_per_column){
			echo '</td>';
			$count=0;
		}
	}
		
?>
  <tr>
</table>
</body>
</html>