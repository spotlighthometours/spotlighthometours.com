<?php
/**********************************************************************************************
Document: list_tourtypes.php
Creator: Brandon Freeman
Date: 02-11-11
Purpose: Creates a table with the tourtypes listed. (for Ajax request)  
Notes: This guy hangs out in the admin_queries folder.
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

	// Connect to MySQL
	require_once ('../repository_inc/connect.php');
}

//=======================================================================
// Document
//=======================================================================

?>

<table>
	<tr>
		<th><div class="button_txt left" style="margin-top: 0px;" onclick="Reset(); ViewForm();" >Add</div></th>
		<th>ID</th>
		<th>Name</th>
		<th>Category</th>
		<th>Order</th>
		<th>Unit Price</th>
		<th>Hidden</th>
		<th></th>
	</tr>
	<?php
		// List the Tour Categories in some option tags for the select.
		$query = "
			SELECT tt.tourTypeID, tt.tourTypeName, tt.tourCategory, tt.tour_order, tt.unitPrice, tt.hidden 
			FROM tourtypes tt
			LEFT JOIN tour_category tc ON tt.tourCategory = tc.category_name
			ORDER BY tc.category_order, tt.tourCategory, tt.tour_order";
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		$highlight = false;
		$count = 0;
		while($result = mysql_fetch_array($r)){
			if ($result['hidden']) {
				$hidden = "true";
			} else {
				$hidden = "false";
			}
			
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "";
			}
			$highlight = !$highlight;
			
			echo '
	<tr class="' . $class . '" >
		<td class="center" >
			<div class="button_txt left" style="margin-top: 0px;" onclick="EditTourType(' . Chr(39) . $result['tourTypeID'] . Chr(39) . ');" >Edit</div>
		</td>
		<td id="' . $count . '-id" class="center" >' . $result['tourTypeID'] . '</td>
		<td>' . $result['tourTypeName'] . '</td>
		<td class="center" >' . $result['tourCategory'] . '</td>
		<td class="center" >
			<input id="' . $count . '-order" class="input xsm left" type="text" value="' . $result['tour_order'] . '" onchange="NumberCheck(' . $count . ');" /> 
		</td>
		<td class="center">$' . number_format($result['unitPrice'], 2, '.', ',') . '</td>
		<td class="center">' . $hidden . '</td>
		<td>
			<div class="button_txt left" style="margin-top: 0px;" onclick="confirmDelete(' . Chr(39) . $result['tourTypeName'] . Chr(39) . ', ' . Chr(39) . $result['tourTypeID'] . Chr(39) . ')" >Del</div>
		</td>
	</tr>
			';
			$count++;
		}
		echo '<input id="count" type="hidden" value="' . $count . '" /> ';
		echo '<tr><td colspan=8><input type="button" value="Update Order" onclick="UpdateOrder();" /></td></tr> ';
	?>
</table>
