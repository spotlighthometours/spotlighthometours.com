<?PHP

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	set_time_limit(90000);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();

//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
	
//=======================================================================
// Document
//=======================================================================

$state = "CO";

// ID for the Tour to match regions with
$openRegionTourTypeID = '15'; // 15 Motion Photo Tour Plus

// IDs for the tour types we would like to set pricing (same as global) for in the same regions as the tour above.
$tourTypeIDs = array(
	'39',
	'40',
	'38'
);

// Pull the global price for the above tour types and set the regional pricing
foreach($tourTypeIDs as $index => $id){
	$unitPriceQ = "SELECT unitPrice FROM tourtypes WHERE tourTypeID = ".$id;
	$unitPriceR = mysql_query($unitPriceQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $unitPriceQ);
	$unitPrice = mysql_result($unitPriceR,0,'unitPrice');
	$unitPrice = number_format($unitPrice, 2);
	addPrice($id, $unitPrice);
}

function addPrice($itemID, $price){		
	global $state;
	$qRegion = 'country = "US" ';
	$qRegion .= 'AND state_prefix = "' . $state . '" ';
		
	$selectQ = "SELECT zipID FROM nf_locations WHERE " . $qRegion;
	$r = mysql_query($selectQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $selectQ);

	$zipIDs = array();
	while($result = mysql_fetch_array($r)) {
		array_push($zipIDs, $result['zipID']);
	}

	if (sizeof($zipIDs) > 0) {
		$deleteQ = 'DELETE FROM nf_pricing WHERE itemType = "tour" AND itemID = "' . $itemID . '" AND category = "region" AND (';
		$first = true;
		foreach($zipIDs as $zipID) {
			if ($first) {
				$first = !$first;	
			} else {
				$deleteQ .= ' OR ';
			}
			$deleteQ .= ' categoryID = "' . $zipID . '" ';
		}
		$deleteQ .= ')';
			
		mysql_query($deleteQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $deleteQ);
			
		$insertQ = 'INSERT INTO nf_pricing (itemType, itemID, category, categoryID, price) VALUES ';
		$first = true;
		foreach($zipIDs as $zipID) {
			if(regionOpen($zipID)){
				if ($first) {
					$first = !$first;	
				} else {
					$insertQ .= ', ';
				}
				$insertQ .= '("tour", "' . $itemID . '", "region", "' . $zipID . '", "' . $price . '" )';
			}
		}
			
		mysql_query($insertQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $insertQ);
	}
}

function regionOpen($zipID){
	global $openRegionTourTypeID;
	$regionQ = "SELECT pricingID FROM nf_pricing WHERE itemType='tour' AND itemID='".$openRegionTourTypeID."' AND category='region' AND categoryID='".$zipID."' LIMIT 1";
	$regionR = mysql_query($regionQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $regionQ);
	if(mysql_num_rows($regionR)>0){
		return true;
	}else{
		return false;
	}
}

?>