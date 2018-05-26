<?PHP
/**********************************************************************************************
Document: tourtype-by-ordered-prod
Creator: Jacob Edmond Kerr
Date: 08-01-13
Purpose: Loop through the product id 2 tour type id array: $additionalP2TourT and find all orders with additional products id from array and set the tour type id (also in the array) on the tour.
Notes: The product id 2 tour type id is in the conf file $additionalP2TourT. The function that makes the switch is in the tours class.
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Global Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');
	showErrors();
	
//=======================================================================
// Document
//=======================================================================

$tours = new tours();

foreach($additionalP2TourT as $additionalProductID => $tourTypeID){
	$tourIDs = $db->run("SELECT DISTINCT(o.tourid) FROM orderdetails od, orders o WHERE od.type='product' AND od.productID='".$additionalProductID."' AND o.orderID = od.orderID AND o.tourid IS NOT NULL");
	foreach($tourIDs as $row => $columns){
		$tours->setTourTypeByProduct($additionalProductID, $columns['tourid']);
	}
}
?>