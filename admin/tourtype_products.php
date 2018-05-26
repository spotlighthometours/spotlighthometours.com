<?PHP
/*
 * Admin Product Tour Type Control File
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');

// Create instance of the tour object
$tour = new tours($db);
$products = new products();

// Get all products
$products = $products->getList();
$productCount = count($products);

// Get selected products
$tour->tourTypeID = $_GET['id'];
$selectedProducts = $tour->getProducts($_GET['id']);

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
			addSelection(id);
		}else{
			removeSelection(id);
		}
	}
	
	function addSelection(id){
		query = "action=add&id="+<?PHP echo $_GET['id']; ?>+"&productID="+id;
		var url = "../repository_queries/admin_tourtypes_updateproducts2.php?"+query;
		params = "";
		ajaxQuery(url, params, 'nothing');
	}
	
	function removeSelection(id){
		query = "action=remove&id="+<?PHP echo $_GET['id']; ?>+"&productID="+id;
		var url = "../repository_queries/admin_tourtypes_updateproducts2.php?"+query;
		params = "";
		ajaxQuery(url, params, 'nothing');
	}
	
	function deselectAll(){
		var proceed = confirm("Are you sure you want to remove all products from this tour type?");
		if(proceed){
			$('input[type=checkbox]').each(function () {
				if($(this).attr('id').indexOf("product") >= 0){
					$(this).parent().show();
					if(this.checked){
						$(this).attr('checked', false);
						var id = $(this).attr('id');
						var exploded = id.split('_');
						id = exploded[1];
						addSelection(id);
					}
				}
			});
			alert('Removing all Products from this tour type!');
		}
	}
	
	function selectAll(){
		var proceed = confirm("Are you sure you want to add all product to this tour type?");
		if(proceed){
			$('input[type=checkbox]').each(function () {
				$(this).parent().show();
				if($(this).attr('id').indexOf("product") >= 0){
					if(!this.checked){
						$(this).attr('checked', true);
						var id = $(this).attr('id');
						var exploded = id.split('_');
						id = exploded[1];
						removeSelection(id);
					}
				}
			});
			alert('Adding all Products to this tour type!');
		}
	}
</script>
<style>
	td{
		white-space:nowrap;
	}
</style>
</head>
<body style="padding:20px;">
<h1>Available Products</h1>
<table border="0" cellpadding="10" cellspacing="0" style="width:auto; margin:0px;">
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
      <td align="left">[ <a href="javascript:selectAll()">select all</a> ] [ <a href="javascript:deselectAll()">deselect all</a> ]</td>
    </tr>
  <tr>
    <?PHP
	$count = 0;
	foreach($products as $row => $column){
		if($count==0){
			echo '<td valign="top" style="vertical-align:text-top;">';
		}
?>
    <input name="products[]" type="checkbox" id="product_<?PHP echo $column['productID']; ?>" value="<?PHP echo $column['productID'] ?>" <?PHP echo (in_array($column['productID'], $selectedProducts))?'CHECKED':'' ?> onchange="updateSelection(<?PHP echo $column['productID']; ?>)"/>
    <?PHP echo $column['productName'] ?><br/>
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