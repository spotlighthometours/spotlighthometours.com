<?php
/**********************************************************************************************
Document: admin_mls_list_providers.php
Creator: Jacob Emdond Kerr
Date: 09-13-11
Purpose: Creates a table with the MLS providers listed. (for Ajax request)  
**********************************************************************************************/

// This guy is different.
// This is called as an include for building the original php document.
// It is also used as ajax.
// In the ajax query, we pass the 'includes' parameter.
// This will hopefully counter the double include.

if (isset($_POST['includes'])) {

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();

//=======================================================================
// Includes
//=======================================================================

	// Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');
}

//=======================================================================
// Object Instances
//=======================================================================

// MLS Object
$mls = new mls();

//=======================================================================
// Document
//=======================================================================

// Set number per page, sort order, page# and action.
$defaults = Array(
    'page',
    'order_by',
    'order',
    'limit'
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
    }
    $start = $limit*($page-1);
}
$mlsProviders = $mls->getProviders($start, $limit, $order_by, $order);
if($mls->listOrder=="ASC"){
	$order = "DESC";
}else{
	$order = "ASC";
}
$query = $_SERVER["QUERY_STRING"];
$remove_position = strpos($query, '&order_by');
if($remove_position===false){
	// nothing
}else{
	$query = substr($query, 0, $remove_position);
}
?>

<table>
	<tr>
		<th><div class="button_txt left" style="margin-top: 0px;" onclick="Reset(); ViewForm();" >Add</div></th>
		<th><a href="<?PHP echo '?'.$query.'&order_by=id&order='.$order; ?>">ID</a></th>
		<th><a href="<?PHP echo '?'.$query.'&order_by=name&order='.$order; ?>">Name</a></th>
		<th><a href="<?PHP echo '?'.$query.'&order_by=stateID&order='.$order; ?>">State</a></th>
        <th>&nbsp;</th>
	</tr>
	<?php
		// List the Tour Categories in some option tags for the select.
		
		$highlight = false;
		$count = 0;
		foreach($mlsProviders as $row => $column){
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "";
			}
			$highlight = !$highlight;
			
			$name = (isset($column['website'])&&!empty($column['website']))?'<a href="'.$column['website'].'" target="_blank">'.$column['name'].'</a>':'';
			$state = getStateByID($column['stateID']);
			
			echo '
	<tr class="' . $class . '" >
		<td class="center" >
			<div class="button_txt left" style="margin-top: 0px;" onclick="Edit(' . Chr(39) . $column['id'] . Chr(39) . ');" >Edit</div>
		</td>
		<td class="center" id="' . $count . '-id">' . $column['id'] . '</td>
		<td class="center" >' . $name . '</td>
		<td class="center" >' . $state['stateFullName'] . '</td>
		<td>
			<div class="button_txt" style="margin-top: 0px;" onclick="confirmDelete(' . Chr(39) . $column['name'] . Chr(39) . ', ' . Chr(39) . $column['id'] . Chr(39) . ')" >Del</div>
		</td>
	</tr>
			';
			$count++;
		}
	?>
</table>
