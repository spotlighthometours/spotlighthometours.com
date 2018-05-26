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

	$statesQ = "SELECT stateAbbrName FROM states WHERE country='USA'";
	$statesR = mysql_query($statesQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $statesQ);
	
	while($state = mysql_fetch_array($statesR)){
		
		$whiteListedTourTypes = array(
			'18'
		);
		
		$whiteListedProducts = Array();
		
		/*// Blacklist Products
		$productsQ = "SELECT productID FROM `products` WHERE productName IS NOT NULL";
		$productsR = mysql_query($productsQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $productsQ);
		while($product = mysql_fetch_array($productsR)){
			if(!in_array($product['productID'],$whiteListedProducts)){
				blackListState($state['stateAbbrName'], 'product', $product['productID']);
			}
		}*/
			
		// Blacklist Tour Types
		$tourTypesQ = "SELECT tourTypeID FROM `tourtypes`";
		$tourTypesR = mysql_query($tourTypesQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $tourTypesQ);
		while($tourType = mysql_fetch_array($tourTypesR)){
			if(!in_array($tourType['tourTypeID'],$whiteListedTourTypes)){
				blackListState($state['stateAbbrName'], 'tour', $tourType['tourTypeID']);
			}
		}
	}
	
	function blackListState($state, $itemType, $itemID){	
		$qRegion = 'country = "US" ';
			
		$location .= '&state=' . $state;
		$qRegion .= 'AND state_prefix = "' . $state . '" ';
			
		$selectQ = "SELECT zipID FROM nf_locations WHERE " . $qRegion;
		$r = mysql_query($selectQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
		$zipIDs = array();
		while($result = mysql_fetch_array($r)) {
			array_push($zipIDs, $result['zipID']);
		}
			
		if (sizeof($zipIDs) > 0) {
				
			$insertQ = 'INSERT INTO nf_blacklist (itemType, itemID, category, categoryID, permission) VALUES';
			$first = true;
			foreach($zipIDs as $zipID) {
				if(!isPriceSet($itemType, $itemID, 'region', $zipID)){
					if ($first) {
						$first = !$first;	
					} else {
						$insertQ .= ', ';
					}
					$insertQ .= '("' . $itemType . '", "' . $itemID . '", "region", "' . $zipID . '", "0" )';
				}
			}
			$insertQ .= ' ON DUPLICATE KEY UPDATE permission = VALUES(permission)';
					
			mysql_query($insertQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $insertQ);
		}
	}
	
	function isPriceSet($itemType, $itemID, $category, $categoryID){
		$priceCheckQ = "SELECT price FROM nf_pricing WHERE itemType='".$itemType."' AND itemID='".$itemID."' AND category='".$category."' AND categoryID='".$categoryID."' LIMIT 1";
		$priceCheckR = mysql_query($priceCheckQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $priceCheckQ);
		if(mysql_num_rows($priceCheckR)>0){
			return true;
		}else{
			return false;
		}
	}

?>