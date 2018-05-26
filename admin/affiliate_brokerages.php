<?PHP
/* Author:  Edward Seniw
 * Date:	5/23/13
 * Affiliate Brokerages Control File
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
ShowErrors();
// Create instance of the tour object
$brokerages = new brokerages($db);
$affiliate = new affiliates($db);

// Get all brokerages
if(isset($_GET['keyword'])&&!empty($_GET['keyword'])){
	$brokerages = $brokerages->listAll($_GET['keyword']);
}else{
	$brokerages = $brokerages->listAll();
}
$brokeragesCount = count($brokerages);
// Get the affiliate brokerages
$affBrokerages = $affiliate->getBrokerageIDs($_REQUEST['id']);
// Number of tour types per column
if(isset($_GET['columns'])&&!empty($_GET['columns'])){
	$columns = $_GET['columns'];
}else{
	$columns = 2;
}
$number_per_column = $brokeragesCount / $columns;
$number_per_column = round($number_per_column, 0, PHP_ROUND_HALF_UP);
if(isset($_REQUEST['id'])){
	$_SESSION['photographerID'] = $_REQUEST['id'];
}
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
	var addSel = new Array();
	var delSel = new Array();
	
	function updateSelection(id){
		if($('#brokerage_'+id).is(':checked')){
			index = $.inArray(id, delSel);
			if (index == -1){
				addSel.push(id);
			}else{
				delSel.splice(index, 1);
				addSel.push(id);
			}
		}else{
			index = $.inArray(id, addSel);
			if (index == -1){
				delSel.push(id);
			}else{
				addSel.splice(index, 1);
				delSel.push(id);
			}
		}
		updateSelections();
	}
	
	function updateSelections() {
		for(i = 0; i < addSel.length; i++) 			
			addSelection(addSel[i]);
			
		for(i = 0; i < delSel.length; i++) 			
			removeSelection(delSel[i]);	
	}
	
	function addSelection(id){
		query = "action=add&photographerID=<?PHP echo $_REQUEST['id'];?>&brokerageID="+id;
		var url = "../repository_queries/admin_affiliate_updatebrokerages.php?"+query;
		params = "";
		ajaxQuery(url, params, 'nothing');
	}
	
	function removeSelection(id){
		query = "action=remove&photographerID=<?PHP echo $_REQUEST['id'];?>&brokerageID="+id;
		var url = "../repository_queries/admin_affiliate_updatebrokerages.php?"+query;
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
		var proceed = confirm("Are you sure you want to add this product to all brokerages?");
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
			alert('Adding all Brokerages to this tour type!');
		}
	}
	
	function deselectAll(){
		var proceed = confirm("Are you sure you want to remove all brokerages from this product?");
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
			alert('Removing all Brokerages from this tour type!');
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
<body style="padding:20px;">
<h1>Available Brokerages</h1>
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
      <td align="left"> [ <a href="javascript:showUnchecked()">show unselected</a> ] [ <a href="javascript:showChecked()">show selected</a> ] [ <a href="javascript:showAll()">show all</a> ] [ <a href="javascript:selectAll()">select all</a> ] [ <a href="javascript:deselectAll()">deselect all</a> ]</td>
    </tr>
  </table>
</form>
<table border="0" cellpadding="10" cellspacing="0" style="width:auto; margin:0px;">
  <tr>
    <?PHP
	$count = 0;
	foreach($brokerages as $row => $column){
		if($count==0){
			echo '<td valign="top" style="vertical-align:text-top;">';
		}
?>
    <div id="brokerageWrapper_<?PHP echo $column['brokerageID']; ?>">
      <input name="brokerages[]" type="checkbox" id="brokerage_<?PHP echo $column['brokerageID']; ?>" value="<?PHP echo $column['brokerageID'] ?>" <?PHP echo (in_array($column['brokerageID'], $affBrokerages))?'CHECKED':''; ?> onchange="updateSelection(<?PHP echo $column['brokerageID']; ?>)"/>
  	<?PHP 
		echo '<a href="admin_brokerages.php?id='.$column['brokerageID'].'" target="_blank">';
		if(isset($column['brokerageDesc'])&&!empty($column['brokerageDesc'])){
			echo $column['brokerageName'] .' - '.$column['brokerageDesc'];
		}else{
			echo $column['brokerageName'];
		}
		echo '</a>';
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
<div align="center">
	<span style="background-color:lightgray;border-radius:9px;cursor:pointer; padding:5px 20px;" onclick="updateSelections()">Update</span>
</div>
</body>
</html>