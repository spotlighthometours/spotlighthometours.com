<?PHP
/*
 * Admin Memberships Control File
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');

$memberships = new memberships();
$brokerage = new brokerages($db);

// Get all memberships
$membershipList = $memberships->getMemberships();
$membershipCount = $memberships->rowCount;

// Get selected blacklisted memberships
$brokerage->brokerageID = $_GET['id'];
$blackListedMemberships = $brokerage->getBlacklistedMemberships();

// Get brokerage name
$brokerageName = $brokerage->getByID($_GET['id'], "brokerageName, brokerageDesc");
$brokerageName = (isset($brokerageName['brokerageDesc'])&&!empty($brokerageName['brokerageDesc']))?$brokerageName['brokerageName'].'-'.$brokerageName['brokerageDesc']:$brokerageName['brokerageName'];

// Get membership pricing
$MembershipPricing = $brokerage->getMembershipPricing();

// Number of membership per column
if(isset($_GET['columns'])&&!empty($_GET['columns'])){
	$columns = $_GET['columns'];
}else{
	$columns = 3;
}
$number_per_column = $membershipCount / $columns;
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
		if($('#MembershipSelection_'+id).is(':checked')){
			removeSelection(id);
		}else{
			addSelection(id);
		}
	}
	
	function addSelection(id){
		query = "action=add&id="+<?PHP echo $_GET['id']; ?>+"&MembershipID="+id;
		var url = "../repository_queries/admin_brokerage_updateMembershipList.php?"+query;
		params = "";
		ajaxQuery(url, params, 'nothing');
	}
	
	function removeSelection(id){
		query = "action=remove&id="+<?PHP echo $_GET['id']; ?>+"&MembershipID="+id;
		var url = "../repository_queries/admin_brokerage_updateMembershipList.php?"+query;
		params = "";
		ajaxQuery(url, params, 'nothing');
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
			query = "action=remove&id="+<?PHP echo $_GET['id']; ?>+"&MembershipID="+id;
			var url = "../repository_queries/admin_brokerage_updateMembershipPricing.php?"+query;
			params = "";
			ajaxQuery(url, params, 'nothing');
		}else{
			var strPattern = /^\s*(\+|-)?((\d+(\.\d\d)?)?(\.\d\d))\s*$/;  
			if(element.value.search(strPattern)== -1){
				element.focus();
				element.value = '';
				alert('Please enter the proper money format: 1.22');
			}else{
				query = "action=add&id="+<?PHP echo $_GET['id']; ?>+"&MembershipID="+id+"&price="+element.value;
				var url = "../repository_queries/admin_brokerage_updateMembershipPricing.php?"+query;
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
<h1 style="margin-bottom:0px;"><?PHP echo $brokerageName; ?></h1>
<h2 style="margin-top:0px;">Available Memberships</h2>
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
      <td align="left"> [ <a href="javascript:showPricing()">show pricing</a> ] [ <a href="javascript:hidePricing()">hide pricing</a> ]</td>
    </tr>
  <tr>
</table>
<table border="0" cellpadding="10" cellspacing="0" style="width:auto; margin:0px;">
    <tr>
    <?PHP
	$count = 0;
	foreach($membershipList as $row => $column){
		$memberships->getPrice($column['id']);
		if($count==0){
			echo '<td valign="top" style="vertical-align:text-top;">';
		}
?>
    <input name="membershipList[]" type="checkbox" id="MembershipSelection_<?PHP echo $column['id']; ?>" value="<?PHP echo $column['id'] ?>" <?PHP echo (in_array($column['id'], $blackListedMemberships))?'':'CHECKED' ?> onchange="updateSelection(<?PHP echo $column['id']; ?>)"/>
    <?PHP echo $column['name'] ?><br/>
    <?PHP
		$membershipPrice = (array_key_exists($column['id'], $MembershipPricing))?$MembershipPricing[$column['id']]:'';
		$regPrice = number_format($memberships->price, 2, '.', '');
		echo '<span>Price: <input type="text" id="membershipPrice_'.$column['id'].'" value="'.$membershipPrice.'" size="5" onkeydown="disallowNonNumeric(event)" onchange="updatePrice(this)"/> Reg: '.$regPrice.'<br/></span>';
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