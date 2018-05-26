<?php
/**********************************************************************************************
Document: admin_products_list_products.php
Creator: Brandon Freeman
Date: 02-24-11
Purpose: Creates a table with the products listed. (for Ajax request)  
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
		<th>Parent ID</th>
		<th>Name</th>
        <th>Order</th>
		<th>Unit Price</th>
		<th></th>
	</tr>
	<?php
		// List the Tour Categories in some option tags for the select.
		$query = "
		SELECT pr.productID, pr.parentProduct, pr.productName, 
		(SELECT price FROM nf_pricing WHERE itemType='product' AND itemID=pr.productID AND category='standard' AND categoryID IS NULL) as unitPrice,
		sort FROM products pr WHERE productName IS NOT NULL";
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		$highlight = false;
		$count = 0;
		while($result = mysql_fetch_array($r)){
			
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "";
			}
			$highlight = !$highlight;
			
			echo '
	<tr class="' . $class . '" >
		<td class="center" >
			<div class="button_txt left" style="margin-top: 0px;" onclick="Edit(' . Chr(39) . $result['productID'] . Chr(39) . ');" >Edit</div>
		</td>
		<td class="center" id="' . $count . '-id">' . $result['productID'] . '</td>
		<td class="center" >' . $result['parentProduct'] . '</td>
		<td>' . $result['productName'] . '</td>
		<td class="center" >
			<input id="' . $count . '-order" class="input xsm left" type="text" value="' . $result['sort'] . '" onchange="NumberCheck(' . $count . ');" /> 
		</td>
		<td class="center">$' . number_format($result['unitPrice'], 2, '.', ',') . '</td>
		<td>
			<div class="button_txt left" style="margin-top: 0px;" onclick="confirmDelete(' . Chr(39) . $result['productName'] . Chr(39) . ', ' . Chr(39) . $result['productID'] . Chr(39) . ')" >Del</div>
		</td>
	</tr>
			';
			$count++;
		}
		echo '
		<tr>
			<td>
				<input id="count" type="hidden" value="'.$count.'" />
                <input type="button" value="Update Order" onclick="UpdateOrder();" />
			</td>
		</tr>
		';
	?>
</table>
