<?PHP
/*
 * Admin Product Tour Type Control File
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');

// Create instance of the tour object
$products = new products();
$brokerage = new brokerages($db);

// Get all products
$productList = $products->getList();
$productCount = count($productList);

// Get selected products
$brokerage->brokerageID = $_GET['id'];
$blackListedProducts = $brokerage->getBlacklistedProducts();

// Get brokerage name
$brokerageName = $brokerage->getByID($_GET['id'], "brokerageName, brokerageDesc");
$brokerageName = (isset($brokerageName['brokerageDesc'])&&!empty($brokerageName['brokerageDesc']))?$brokerageName['brokerageName'].'-'.$brokerageName['brokerageDesc']:$brokerageName['brokerageName'];

// Get product pricing
$productPricing = $brokerage->getProductPricing();

// Get affiliate photographer ID
$affiliatePhotographerID = $brokerage->getByID($_GET['id'], "affiliatePhotographerID");
$affiliatePhotographerID = $affiliatePhotographerID['affiliatePhotographerID'];

// Number of tour types per column
if(isset($_GET['columns'])&&!empty($_GET['columns'])){
	$columns = $_GET['columns'];
}else{
	$columns = 3;
}
$number_per_column = $productCount / $columns;
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
		if($('#product_'+id).is(':checked')){
			removeSelection(id);
		}else{
			addSelection(id);
		}
	}
	
	function addSelection(id){
		query = "action=add&id="+<?PHP echo $_GET['id']; ?>+"&productID="+id;
		var url = "../repository_queries/admin_brokerage_updateproducts.php?"+query;
		params = "";
		ajaxQuery(url, params, 'nothing');
	}
	
	function removeSelection(id){
		query = "action=remove&id="+<?PHP echo $_GET['id']; ?>+"&productID="+id;
		var url = "../repository_queries/admin_brokerage_updateproducts.php?"+query;
		params = "";
		ajaxQuery(url, params, 'nothing');
	}
	
	function updatePrice(element){
		var id = element.id;
		var exploded = id.split('_');
		id = exploded[1];
		if(element.value==""){
			query = "action=remove&id="+<?PHP echo $_GET['id']; ?>+"&productID="+id;
			var url = "../repository_queries/admin_brokerage_updateproductpricing.php?"+query;
			params = "";
			ajaxQuery(url, params, 'nothing');
		}else{
			var strPattern = /^\s*(\+|-)?((\d+(\.\d\d)?)?(\.\d\d))\s*$/;  
			if(element.value.search(strPattern)== -1){
				element.focus();
				element.value = '';
				alert('Please enter the proper money format: 1.22');
			}else{
				query = "action=add&id="+<?PHP echo $_GET['id']; ?>+"&productID="+id+"&price="+element.value;
				var url = "../repository_queries/admin_brokerage_updateproductpricing.php?"+query;
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
	
	function deselectAll(){
		var proceed = confirm("Are you sure you want to remove all products from this brokerage?");
		if(proceed){
			$('input[type=checkbox]').each(function () {
				if($(this).attr('id').indexOf("product") >= 0){
					$(this).parent().show();
					if(this.checked){
						$(this).attr('checked', false);
						var id = $(this).attr('id');
						var exploded = id.split('_');
						id = exploded[1];
						addSelection(id,"0");
					}
				}
			});
			alert('Removing all Products from this brokerage!');
		}
	}
	
	function selectAll(){
		var proceed = confirm("Are you sure you want to add all product to this brokerage?");
		if(proceed){
			$('input[type=checkbox]').each(function () {
				$(this).parent().show();
				if($(this).attr('id').indexOf("product") >= 0){
					if(!this.checked){
						$(this).attr('checked', true);
						var id = $(this).attr('id');
						var exploded = id.split('_');
						id = exploded[1];
						removeSelection(id,"0");
					}
				}
			});
			alert('Adding all Products to this brokerage!');
		}
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
<h1 style="margin-bottom:0px;"><?PHP echo $brokerageName; ?></h1>
<h2 style="margin-top:0px;">Available Products</h2>
<?PHP
echo ($affiliatePhotographerID>0)?'<h4>[ <span style=\'cursor:pointer\' onclick="window.location=\'http://www.spotlighthometours.com/admin/affiliate_products.php?id='.$affiliatePhotographerID.'\'">Load affiliate\'s available products</span> ]</h4>':'';
?>
<table border="0" cellpadding="0" cellspacing="0" style="width:auto; margin:0px;">
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
      <td align="left"> [ <a href="javascript:showPricing()">show pricing</a> ] [ <a href="javascript:hidePricing()">hide pricing</a> ] [ <a href="javascript:selectAll()">select all</a> ] [ <a href="javascript:deselectAll()">deselect all</a> ]</td>
    </tr>
  <tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" style="width:auto; margin:0px;">
  <tr>
    <?PHP
	$count = 0;
	foreach($productList as $row => $column){
		if($count==0){
			echo '<td valign="top" style="vertical-align:text-top;">';
		}
?>
    <input name="products[]" type="checkbox" id="product_<?PHP echo $column['productID']; ?>" value="<?PHP echo $column['productID'] ?>" <?PHP echo (in_array($column['productID'], $blackListedProducts))?'':'CHECKED' ?> onchange="updateSelection(<?PHP echo $column['productID']; ?>)"/>
    <?PHP echo $column['productName'] ?><br/>
    <?PHP
		$productPrice = (array_key_exists($column['productID'], $productPricing))?$productPricing[$column['productID']]:'';
		$regPrice = number_format($products->getRegPrice($column['productID']), 2, '.', '');
		echo '<span>Price: <input type="text" id="productprice_'.$column['productID'].'" value="'.$productPrice.'" size="5" onkeydown="disallowNonNumeric(event)" onchange="updatePrice(this)"/> Reg: $'.$regPrice.'<br/></span>';
	?>
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