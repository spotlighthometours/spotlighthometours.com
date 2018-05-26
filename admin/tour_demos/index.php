<?php
/*
 * Admin Tour Demos Control File
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

// Create instances of needed Objects
$demos = new demos();

// Set vars
$message = false;
$error = false;

// Set number per page, sort order, page# and action.
$defaults = Array(
    'page',
    'order_by',
    'order',
    'limit',
    'action'
);
foreach($defaults as $index => $value){
    switch($value){
        case'page':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $page = 1;
            }else{
                $page = $_GET[$value]; 
            }
        break;
        case'order_by':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $order_by = "id";
            }else{
                $order_by = $_GET[$value]; 
            }
        break;
        case'order':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $order = 'DESC';
            }else{
                $order = $_GET[$value]; 
            }
        break;
        case'limit':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $limit = 25;
            }else{
                $limit = $_GET[$value]; 
            }
        break;
        case'action':
            if(!isset($_GET[$value])||empty($_GET[$value])){
                $action = 'list';
            }else{
                $action = $_GET[$value]; 
            }
        break;
    }
}

if($order == 'DESC'){
	$order = 'ASC';
}else{
    $order = 'DESC'; 
}

// Check Action
switch($action){
	case'add':
		if($demos->addTour($_GET['tourID'])){
			$message=true;
			$messageTxt="Tour added to demos!";
		}else{
			$error=true;
			$errorTxt="Tour not found OR already exist as a demo! The tour was not added to demos.";
		}
	break;
	case'delete':
		$demos->removeTour($_GET['tourID']);
	break;
}

$start = $limit*($page-1);
	
// Pull list of demos
if(isset($_GET['tourID'])){
	$demoList = $demos->getList("", $start, $limit, $order_by, $order);
}else{
	$demoList = $demos->getList($_GET['tourID'], $start, $limit, $order_by, $order);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin - Tour Demos</title>
<link type="text/css" href="../../repository_css/admin.css" rel="stylesheet" />
<script src="../../repository_inc/jquery-1.5.min.js" type="text/javascript"></script>
<script src="../../repository_inc/admin.js"></script>
<script>
	function confirmDelete(tourID){
		proceed = confirm("Are you sure you want to delete tour with the ID: "+tourID+" from the demos?");
		if(proceed){
			window.location = 'index.php?action=delete&tourID='+tourID;
		}
	}
</script>
</head>
<body>
<form action="" method="get">
  <input name="action" type="hidden" value="search" />
  <table style="width:800px;">
    <tr>
      <td width="100%">&nbsp;</td>
      <td>Filter by tour ID#<br />
        <input name="tourID" type="text" value="" /></td>
      <td valign="bottom"><br />
        <input type="submit" name="button" id="button" value="Go" /></td>
    </tr>
  	<tr>
    	<td colspan="2">
<?PHP
if($message){
	echo '<div class="alerts widthAuto">'.$messageTxt.'</div>';
}else if($error){
	echo '<div class="errors widthAuto">'.$errorTxt.'</div>';
}
?>
        </td>
    </tr>
  </table>
</form>
<table style="width:800px;">
  <tr>
    <th align="center"><a href="?&order_by=tourID&order=<?PHP echo $order; ?>">Tour ID</a></th>
    <th align="center"><a href="?&order_by=category&order=<?PHP echo $order; ?>">Category</a></th>
	<th align="center"><a href="?&order_by=tourType&order=<?PHP echo $order; ?>">Tour Type</a></th>
    <th align="center"></th>
    <th align="center"></th>
  </tr>
  <?PHP
	// PRINT DEMOS LIST
	$highlight = false;
	if(is_array($demoList)){
		foreach($demoList as $row => $column){
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "";
			}
			$highlight = !$highlight;	
?>
  <tr class="<?PHP echo $class; ?>">
    <td align="center"><?PHP echo $column['tourID']; ?></td>
    <td align="center"><?PHP echo $column['category']; ?></td>
	<td align="center"><?PHP echo $column['tourType']; ?></td>
    <td align="center"><a href="../../tours/tour.php?tourid=<?PHP echo $column['tourID']; ?>&demo=true" target="_blank">[ view ]</a></td>
    <td align="center"><img style="cursor:pointer;" src="../../repository_images/del.png" onclick="confirmDelete('<?PHP echo $column['tourID']; ?>');" /></td>
  </tr>
  <?PHP
		}
	}
?>
</table>
<form action="" method="get">
  <input type="hidden" name="action" value="add" />
  <table style="width:800px;">
    <tr>
      <td align="left">Add by tour ID#
        <input name="tourID" type="text" value="" />
        <input type="submit" name="button" id="button" value="Add" /></td>
    </tr>
  </table>
</form>
<!-- MODAL WINDOW -->
<div class="modal">
  <div id="backdrop" style="display: none;" onclick="HidePopUp();"></div>
  <div class="modal-window" id="pop_up_frame">
    <div class="top"><a class="close" href="javascript:HidePopUp();"></a></div>
    <div class="middle">
      <h1 id="pop_up_title"></h1>
      <div id="pop_up_content"> </div>
    </div>
    <div class="bottom"></div>
  </div>
</div>
<!-- END MODAL WINDOW -->
</body>
</html>