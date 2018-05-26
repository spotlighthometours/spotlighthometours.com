<?php
/**********************************************************************************************
Document: admin_regional.php
Creator: Brandon Freeman
Date: 05-17-11
Purpose: Monkey with prices at the regional level.
**********************************************************************************************/

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
	require_once ('../repository_inc/clean_query.php');
	require_once ('../repository_inc/classes/inc.global.php');
	
//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$debug = true;
	
	// Require Admin Login
	if (!$debug) {
		require_once ('../repository_inc/require_admin.php');
	}
	
	if (isset($_GET['id'])) {
		$id = CleanQuery($_GET['id']);
	} elseif (isset($_GET['id'])) {
		$id = CleanQuery($_GET['id']);
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - Regional Pricing</title>
        <link type="text/css" href="../repository_css/admin.css" rel="stylesheet" />
        <script type="text/javascript">
		
			function CheckAll(checkType) {
				if (document.getElementsByName(checkType)) {
					checkboxes = document.getElementsByName(checkType);
					for (var i = 0; i < checkboxes.length; i++) { 
                		var obj = document.getElementsByName(checkType).item(i);
						obj.checked = "checked";
					}
				}
			}
				
			function ConfirmDelete(itemName, url, checkType) {
				if (confirm("Delete " + itemName + "?! \nAre you sure?")) {
					if (itemName == "All Checked Pricing" || itemName == "All Checked Blacklistings") {
						var checkedItems = "";
						if (document.getElementsByName(checkType)) {
							checkboxes = document.getElementsByName(checkType);
							for (var i = 0; i < checkboxes.length && i < 500; i++) { 
								var obj = document.getElementsByName(checkType).item(i);
								//alert(obj.checked);
								if (obj.checked == true) {
									if (checkedItems.length > 0) 
										checkedItems += ',';
									id = obj.id;
									id = id.replace(checkType+"_", "");
									checkedItems += id;
								}
							}
							//alert(checkedItems);
							if (itemName == "All Checked Blacklistings") 
								url += "&del_bl=" + checkedItems;
							else
								url += "&del=" + checkedItems;
						}
					}
					//alert(url);
					window.location.href = url;
				}
			}
			
			function SetPermissionOnChecked(allow, url, checkType) {
				if (confirm("Are you sure you want to set permission on checked items?")) {
					var checkedItems = "";
					if (document.getElementsByName(checkType)) {
						checkboxes = document.getElementsByName(checkType);
						for (var i = 0; i < checkboxes.length && i < 500; i++) { 
							var obj = document.getElementsByName(checkType).item(i);
					
							if (obj.checked == true) {
								if (checkedItems.length > 0) 
									checkedItems += ',';
								id = obj.id;
								id = id.replace(checkType+"_", "");
								checkedItems += id;
							}
						}
						url += "&set_permission=" + allow + "&update=" + checkedItems;
					}
				}
				//alert(url);
				window.location.href = url;
			}
			
			function AreYouSure() {
				var ready = true;
				if (document.getElementById('add_type')) {
					if (document.getElementById('add_type').selectedIndex == -1) ready = false;
				} else {
					ready = false;	
				}
				
				if (document.getElementById('add_id')) {
					if (document.getElementById('add_id').selectedIndex == -1) ready = false;
				} else {
					ready = false;	
				}
				
				if (document.getElementById('add_price')) {
					if (isNaN(parseFloat(document.getElementById('add_price').value))) ready = false;
				} else {
					ready = false;	
				}
				
				if (ready) {	
					if (confirm("Are you sure you want to add a price for this region?! \nDoing so will overwrite all pricing for this item.")) {
						document.add_region_price.submit();
					}
				} else {
					alert("Not all selections have been made.");	
				}
			}
			
			function Blacklist() {
				var ready = true;
				if (document.getElementById('bl_type')) {
					if (document.getElementById('bl_type').selectedIndex == -1) ready = false;
				} else {
					ready = false;	
				}
				if (document.getElementById('bl_id')) {
					if (document.getElementById('bl_id').selectedIndex == -1) ready = false;
				} else {
					ready = false;	
				}
				
				if (ready) {	
					if (confirm("Are you sure you want to create a blacklist for this region?!")) {
						document.add_blacklist.submit();
					}
				} else {
					alert("Not all selections have been made.");	
				}
			}
			
			function Mileage() {
				var ready = true;
				
				if (document.getElementById('add_mileage_price')) {
					if (isNaN(parseFloat(document.getElementById('add_mileage_price').value))) ready = false;
				} else {
					ready = false;	
				}
				
				if (ready) {	
					if (confirm("Are you sure you want to add a price for this region?! \nDoing so will overwrite all pricing for this item.")) {
						document.add_region_mileage.submit();
					}
				} else {
					alert("Not all selections have been made.");	
				}
			}
			
			function Select(scope) {
				try {
					if (scope == "country") {
						if (document.getElementById('state')) document.getElementById('state').selectedIndex = -1;
						if (document.getElementById('county')) document.getElementById('county').selectedIndex = -1;
						if (document.getElementById('city')) document.getElementById('city').selectedIndex = -1;
						if (document.getElementById('zip')) document.getElementById('zip').selectedIndex = -1;
					} else if (scope == "state") {
						if (document.getElementById('county')) document.getElementById('county').selectedIndex = -1;
						if (document.getElementById('city')) document.getElementById('city').selectedIndex = -1;
						if (document.getElementById('zip')) document.getElementById('zip').selectedIndex = -1;
					} else if (scope == "county") {
						if (document.getElementById('city')) document.getElementById('city').selectedIndex = -1;
						if (document.getElementById('zip')) document.getElementById('zip').selectedIndex = -1;
					} else if (scope == "city") {
						if (document.getElementById('zip')) document.getElementById('zip').selectedIndex = -1;
					} else if (scope == "zip") {
						if (document.getElementById('city')) document.getElementById('city').selectedIndex = -1;
					}
					
					document.region.submit();
				} catch(err) {
					window.alert("Select: " + err + ' (line: ' + err.line + ')');
				}	
			}
			
			function GetForm(sender, target, name) {
				try {
					if (document.getElementById(sender).selectedIndex > 0 ) {
						var selection = document.getElementById(sender).options[document.getElementById(sender).selectedIndex].value;
						
						var url = "admin_brokerages_types_for_regional.php";
						var params  = "type=" + selection + "&name=" + name;
						
						var HTTP = false;
						if (window.XMLHttpRequest) {
							HTTP = new XMLHttpRequest();
						} else if (window.ActiveXObject) {
							HTTP = new ActiveXObject("Microsoft.XMLHTTP");
						}
						
						if(HTTP) {
							HTTP.open("POST", url, true);
							HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							HTTP.setRequestHeader("Content-length", params.length);
							HTTP.setRequestHeader("Connection", "close");

							HTTP.onreadystatechange = function() { 
								if (HTTP.readyState == 4 && HTTP.status == 200) {
									document.getElementById(target).innerHTML = HTTP.responseText;
								}
							}
							HTTP.send(params);
						}
					}
				} catch(err) {
					window.alert("GetForm: " + err + ' (line: ' + err.line + ')');
				}	
			}
		</script>
		<style>
			#display_frame {
				position: absolute;
				width: 700px;
				min-height: 100%;
				top: 0px;
				left: 50%;
				margin-left: -350px;
			}
			
			#display_frame #title_frame {
				position: relative;
				width: 100%;
				height: 30px;
				min-height: 100%;
				line-height: 30px;
				text-align: center;
				font: Verdana, Geneva, sans-serif;
				font-size: 22px;
				margin-top: 20px;
				color: white;
				background-color: #36F;		
			}
			
			#display_frame #selection_frame {
				position: relative;
				width: 100%;
				height: 200px;
			}
			
			#display_frame #selection_frame .scope_frame {
				position: relative;
				float: left;
				width: 20%;
				height: 200px;
				background-color: #c3d9ff;	
			}
			
			#display_frame #selection_frame .scope_frame .title {
				width: 90%;
				margin-left: 5%;	
				margin-top: 10px;
				font: Verdana, Geneva, sans-serif;
				font-size: 15px;
				line-height: 20px;
				text-align: center;
				height: 20px;
			}
			
			#display_frame #selection_frame .scope_frame select {
				width: 90%;
				margin-left: 5%;	
				margin-top: 10px;
				font: Verdana, Geneva, sans-serif;
				font-size: 15px;
				height: 150px;
			}
			
			#display_frame .pricing_frame {
				position: relative;
				width: 100%;
				height: 300px;
				margin-top: 10px;
			}
			
			#display_frame .pricing_frame .title {
				position: relative;
				width: 90%;
				margin-left: 5%;	
				font: Verdana, Geneva, sans-serif;
				font-size: 15px;
				line-height: 20px;
				text-align: center;
				height: 20px;		
			}
			
			#display_frame .pricing_frame .listing_frame {
				position: relative;
				width: 100%;
				height: 280px;	
				border: 1px solid black;
			}
			
			#display_frame #add_frame {
				position: relative;
				width: 100%;
				height: 325px;
				margin-top: 10px;
			}
			
			#display_frame #add_frame .title {
				position: relative;
				width: 90%;
				margin-left: 5%;	
				font: Verdana, Geneva, sans-serif;
				font-size: 15px;
				line-height: 20px;
				text-align: center;
				height: 20px;	
			}
			
		</style>
    </head>
    <body>
    <?php
	
	$location = '';
	
	function getZip($zip){
		global $db;
		CleanQuery($zip);
		$zipQ = "SELECT city, state_prefix, county, country FROM nf_locations WHERE zip_code ='".$zip."' LIMIT 1";
		$r = $db->run($zipQ);
		if (!empty($r)) {
			$r = $r[0];
			$_GET['country'] = $r['country'];
			$_GET['state'] = $r['state_prefix'];
			$_GET['county'] = $r['county'];
			$_GET['city'] = $r['city'];
			$_GET['zip'] = $zip;
		}
		else {
			$_GET['country'] = 'US';
			$_GET['state'] = "";
			$_GET['county'] = "";
			$_GET['city'] = "";
			$_GET['zip'] = "";
		}
	}
	
	function getCity($city, $state=""){
		global $db;
		
		CleanQuery($city);
		CleanQuery($state);
		if (strlen($state)>0) 
			$state = "AND state_prefix = '".$state."' ";
		else
			$state = "";
		$cityQ = "SELECT state_prefix, country, county, city FROM nf_locations WHERE city ='".$city."' ".$state."LIMIT 1";
		$r = $db->run($cityQ);
		if (!empty($r)) {
			$r = $r[0];
			$_GET['country'] = $r['country'];
			$_GET['state'] = $r['state_prefix'];
			$_GET['county'] = $r['county'];
			$_GET['city'] = $r['city'];
			$_GET['zip'] = "";
		}
		else {
			$_GET['country'] = 'US';
			$_GET['state'] = "";
			$_GET['county'] = "";
			$_GET['city'] = "";
			$_GET['zip'] = "";
		}
	}
	
	function getCounty($county, $state=""){
		global $db;
		CleanQuery($county);
		CleanQuery($state);
		if (strlen($state)>0) 
			$state = "AND state_prefix = '".$state."' ";
		else
			$state = "";
		$countyQ = "SELECT state_prefix, county, country FROM nf_locations WHERE county ='".$county."' ".$state."LIMIT 1";
		$r = $db->run($countyQ);
		if (!empty($r)) {
			$r = $r[0];
			$_GET['country'] = $r['country'];
			$_GET['state'] = $r['state_prefix'];
			$_GET['county'] = $r['county'];
			$_GET['city'] = "";
			$_GET['zip'] = "";
		}
		else {
			$_GET['country'] = 'US';
			$_GET['state'] = "";
			$_GET['county'] = "";
			$_GET['city'] = "";
			$_GET['zip'] = "";
		}
	}
	
	if(isset($_GET['get_zip'])&&!empty($_GET['get_zip'])){
		getZip($_GET['get_zip']);
	}
	elseif(isset($_GET['get_city'])&&!empty($_GET['get_city'])){
		getCity($_GET['get_city'], $_GET['get_state']);
	}
	elseif(isset($_GET['get_county'])&&!empty($_GET['get_county'])){
		getCounty($_GET['get_county'], $_GET['get_state']);
	}
	elseif(isset($_GET['get_state'])&&!empty($_GET['get_state'])){
		$_GET['country'] = 'US';
		$_GET['state'] = $_GET['get_state'];
		$_GET['county'] = "";
		$_GET['city'] = "";
		$_GET['zip'] = "";
	}
	
	if(strlen($_GET['country']) > 0) {
		$location .= "country=" . $_GET['country'];
	}
	if(strlen($_GET['state']) > 0) {
		$location .= '&state=' . $_GET['state'];
	}
	if(strlen($_GET['county']) > 0) {
		$location .= '&county=' . $_GET['county'];
	}
	if(strlen($_GET['city']) > 0) {
		$location .= '&city=' . $_GET['city'];
	}
	if(strlen($_GET['zip']) > 0) {
		$location .= '&zip=' . $_GET['zip'];
	}
	
/**********************************************************************************************
DELETE PRICE
**********************************************************************************************/	
	if(isset($_GET['del']) && intval($_GET['del']) > 0) {
		$delReg = CleanQuery($_GET['del']);
		while(strlen($delReg) > 0) {
			if (strlen($delReg) > 3900) {
				$delRegPortion = substr($delReg, 0, strpos($delReg, ',', 3900));
				$delReg = substr($delReg, strpos($delReg, ',', 3900)+1);
			}
			else {
				$delRegPortion = $delReg;
				$delReg = "";
			}
			$query = 'DELETE FROM nf_pricing WHERE pricingID IN (' . $delRegPortion . ')';
			mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
		}
		header('Location: ' . basename($_SERVER['PHP_SELF']) . '?' . $location);
	}
/**********************************************************************************************
DELETE BLACKLIST
**********************************************************************************************/	
	if(isset($_GET['del_bl']) && intval($_GET['del_bl']) > 0) {
		$query = 'DELETE FROM nf_blacklist WHERE blacklistID IN (' . CleanQuery($_GET['del_bl']) . ')';
		//echo $query;
		mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
		header('Location: ' . basename($_SERVER['PHP_SELF']) . '?' . $location);
	}
/**********************************************************************************************
UPDATE BLACKLIST
**********************************************************************************************/	
	if(isset($_GET['update']) && intval($_GET['update']) > 0) {
		$query = 'UPDATE nf_blacklist SET permission = '.$_GET['set_permission'].' WHERE blacklistID IN (' . CleanQuery($_GET['update']) . ')';
		
		mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
		header('Location: ' . basename($_SERVER['PHP_SELF']) . '?' . $location);
	}
/**********************************************************************************************
ADD PRICE
**********************************************************************************************/	
	
	if(isset($_GET['open_region'])&&$_GET['open_region']=="true"){
		openRegion();
	}
	
	function openRegion(){
		$state = "";
		$county = "";
		$city = "";
		$zip = "";
		if(isset($_GET['state']) && strlen($_GET['state']) > 0) {
			$state = $_GET['state'];
		}
		if(isset($_GET['county']) && strlen($_GET['county']) > 0) {
			$county = $_GET['county'];
		}
		if(isset($_GET['city']) && strlen($_GET['city']) > 0) {
			$city = $_GET['city'];
		}
		if(isset($_GET['zip']) && strlen($_GET['zip']) > 0) {
			$zip = $_GET['zip'];
		}
		$tourTypesQ = "SELECT tourTypeID, unitPrice FROM `tourtypes`";
		$tourTypesR = mysql_query($tourTypesQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $tourTypesQ);
		while($tourType = mysql_fetch_array($tourTypesR)){
			addPrice('tour', $tourType['tourTypeID'], number_format($tourType['unitPrice'], 2, '.', ''), "US", $state, $county, $city, $zip);
		}
	}
	
	function addPrice($type, $id, $price, $country, $state="", $county="", $city="", $zip=""){
		
		$qRegion = 'country = "' . CleanQuery($country) . '" ';
			
		if(isset($state) && strlen($state) > 0) {
			$qRegion .= 'AND state_prefix = "' . CleanQuery($state) . '" ';
		}
		if(isset($county) && strlen($county) > 0) {
			$qRegion .= 'AND county = "' . CleanQuery($county) . '" ';
		}
		if(isset($city) && strlen($city) > 0) {
			$qRegion .= 'AND city = "' . CleanQuery($city) . '" ';
		}
		if(isset($zip) && strlen($zip) > 0) {
			$qRegion .= 'AND zip_code = "' . CleanQuery($zip) . '" ';
		}
		
		$selectQ = "SELECT zipID FROM nf_locations WHERE " . $qRegion;
		$r = mysql_query($selectQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $selectQ);
		$zipIDs = array();
		while($result = mysql_fetch_array($r)) {
			array_push($zipIDs, $result['zipID']);
		}

		if (sizeof($zipIDs) > 0 && $price >= 0) {
			$deleteQ = 'DELETE FROM nf_pricing WHERE itemType = "' . CleanQuery($type) . '" AND itemID = "' . CleanQuery($id) . '" AND category = "region" AND (';
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
				if ($first) {
					$first = !$first;	
				} else {
					$insertQ .= ', ';
				}
				$first2 = true;
				foreach($_GET['add_id'] as $blacklistID) {
					if ($first2) {
						$first2 = !$first2;	
					} else {
						$insertQ .= ', ';
					}
					$insertQ .= '("' . CleanQuery($type) . '", "' . CleanQuery($blacklistID) . '", "region", "' . 
								$zipID . '", "' . CleanQuery($price) . '" )';
					deleteRegionBlackList(CleanQuery($type), CleanQuery($blacklistID), $zipID);
				}
			}
			$insertQ .= ' ON DUPLICATE KEY UPDATE price = "' . CleanQuery($price) . '"';
			
			mysql_query($insertQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $insertQ);
		}
	}
	
	if(isset($_GET['add_country'])) {
		$state = "";
		$county = "";
		$city = "";
		$zip = "";
		
		if(isset($_GET['add_state']) && strlen($_GET['add_state']) > 0) {
			$location .= '&state=' . $state;
			$state = $_GET['add_state'];
		}
		if(isset($_GET['add_county']) && strlen($_GET['add_county']) > 0) {
			$location .= '&county=' . $county;
			$county = $_GET['add_county'];
		}
		if(isset($_GET['add_city']) && strlen($_GET['add_city']) > 0) {
			$location .= '&city=' . $city;
			$city = $_GET['add_city'];
		}
		if(isset($_GET['add_zip']) && strlen($_GET['add_zip']) > 0) {
			$location .= '&zip=' . $zip;
			$zip = $_GET['add_zip'];
		}
		addPrice($_GET['add_type'], $_GET['add_id'], $_GET['add_price'], $_GET['add_country'], $state, $county, $city, $zip);
		$location = 'Location: ' . basename($_SERVER['PHP_SELF']) . '?country=' . $_GET['add_country'];
		header($location);
	}
	
function deleteRegionBlackList($itemType, $itemID, $zipID){
	$deleteQ = "DELETE FROM nf_blacklist WHERE itemType='".$itemType."' AND itemID='".$itemID."' AND category='region' AND categoryID='".$zipID."'";
	mysql_query($deleteQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $deleteQ);
}
	
/**********************************************************************************************
ADD MILEAGE
**********************************************************************************************/	
	if(isset($_GET['add_mileage_country'])) {
		
		$location = 'Location: ' . basename($_SERVER['PHP_SELF']) . '?country=' . $_GET['add_mileage_country'];
		$qRegion = 'country = "' . CleanQuery($_GET['add_mileage_country']) . '" ';
		
		if(isset($_GET['add_mileage_state']) && strlen($_GET['add_mileage_state']) > 0) {
			$location .= '&state=' . $_GET['add_mileage_state'];
			$qRegion .= 'AND state_prefix = "' . CleanQuery($_GET['add_mileage_state']) . '" ';
		}
		if(isset($_GET['add_mileage_county']) && strlen($_GET['add_mileage_county']) > 0) {
			$location .= '&county=' . $_GET['add_mileage_county'];
			$qRegion .= 'AND county = "' . CleanQuery($_GET['add_mileage_county']) . '" ';
		}
		if(isset($_GET['add_mileage_city']) && strlen($_GET['add_mileage_city']) > 0) {
			$location .= '&city=' . $_GET['add_mileage_city'];
			$qRegion .= 'AND city = "' . CleanQuery($_GET['add_mileage_city']) . '" ';
		}
		if(isset($_GET['add_mileage_zip']) && strlen($_GET['add_mileage_zip']) > 0) {
			$location .= '&zip=' . $_GET['add_mileage_zip'];
			$qRegion .= 'AND zip_code = "' . CleanQuery($_GET['add_mileage_zip']) . '" ';
		}
		
		$selectQ = "SELECT zipID FROM nf_locations WHERE " . $qRegion;
		$r = mysql_query($selectQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $selectQ);
		$zipIDs = array();
		while($result = mysql_fetch_array($r)) {
			array_push($zipIDs, $result['zipID']);
		}
		//echo $selectQ . '<br />';
		if (sizeof($zipIDs) > 0 && $_GET['add_mileage_price'] >= 0) {
			$deleteQ = 'DELETE FROM nf_pricing WHERE itemType = "mileage" AND category = "region" AND (';
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
			//echo $deleteQ . '<br />';
			mysql_query($deleteQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $deleteQ);
			
			$insertQ = 'INSERT INTO nf_pricing (itemType, itemID, category, categoryID, price) VALUES ';
			$first = true;
			foreach($zipIDs as $zipID) {
				if ($first) {
					$first = !$first;	
				} else {
					$insertQ .= ', ';
				}
				$insertQ .= '("mileage", "0", "region", "' . $zipID . '", "' . CleanQuery($_GET['add_mileage_price']) . '" )';
			}
			$insertQ .= ' ON DUPLICATE KEY UPDATE price = "' . CleanQuery($_GET['add_mileage_price']) . '"';
			mysql_query($insertQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $insertQ);
		}
		
		
		header($location);
	}

/**********************************************************************************************
ADD BLACKLIST
**********************************************************************************************/	
	if(isset($_GET['bl_country'])) {
		
		$location = 'Location: ' . basename($_SERVER['PHP_SELF']) . '?country=' . $_GET['bl_country'];
		$qRegion = 'country = "' . CleanQuery($_GET['bl_country']) . '" ';
		
		if(isset($_GET['bl_state']) && strlen($_GET['bl_state']) > 0) {
			$location .= '&state=' . $_GET['bl_state'];
			$qRegion .= 'AND state_prefix = "' . CleanQuery($_GET['bl_state']) . '" ';
		}
		if(isset($_GET['bl_county']) && strlen($_GET['bl_county']) > 0) {
			$location .= '&county=' . $_GET['bl_county'];
			$qRegion .= 'AND county = "' . CleanQuery($_GET['bl_county']) . '" ';
		}
		if(isset($_GET['bl_city']) && strlen($_GET['bl_city']) > 0) {
			$location .= '&city=' . $_GET['bl_city'];
			$qRegion .= 'AND city = "' . CleanQuery($_GET['bl_city']) . '" ';
		}
		if(isset($_GET['bl_zip']) && strlen($_GET['bl_zip']) > 0) {
			$location .= '&zip=' . $_GET['bl_zip'];
			$qRegion .= 'AND zip_code = "' . CleanQuery($_GET['bl_zip']) . '" ';
		}
		
		$selectQ = "SELECT zipID FROM nf_locations WHERE " . $qRegion;
		$r = mysql_query($selectQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $selectQ);
		$zipIDs = array();
		while($result = mysql_fetch_array($r)) {
			array_push($zipIDs, $result['zipID']);
		}
		
		if (sizeof($zipIDs) > 0 && $_GET['bl_price'] >= 0) {
			
			$insertQ = 'INSERT INTO nf_blacklist
						(itemType, itemID, category, categoryID, permission) VALUES';
			$first = true;
			foreach($zipIDs as $zipID) {
				if ($first) {
					$first = !$first;	
				} else {
					$insertQ .= ', ';
				}
				$first2 = true;
				foreach($_GET['bl_id'] as $blacklistID) {
					if ($first2) {
						$first2 = !$first2;	
					} else {
						$insertQ .= ', ';
					}
					$insertQ .= '("' . CleanQuery($_GET['bl_type']) . '", "' . CleanQuery($blacklistID) . '", "region", "' . 
								$zipID . '", "' . intval(CleanQuery($_GET['bl_permission'])) . '" )';
				}
			}
			$insertQ .= ' ON DUPLICATE KEY UPDATE permission = "' . intval(CleanQuery($_GET['bl_permission'])) . '"';
			
			mysql_query($insertQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $insertQ);
		}
		
		//echo $insertQ . ':' . $_GET['bl_permission'];
		header($location);
	
	}

/**********************************************************************************************
DOCUMENT AS USUAL
**********************************************************************************************/	
	
	
	$query1 =   'SELECT DISTINCT country FROM nf_locations WHERE LENGTH(country) <= 3 ORDER BY country';
	$c = mysql_query($query1) or die("Query failed with error: " . mysql_error() . "<br />");
	
	if(isset($_GET['country'])){
	$firstReg = true;
	if(isset($_GET['state']) && strlen($_GET['state']) > 0) {
		if(!$firstReg){
			$qRegion .= 'AND ';
		}
		$qRegion .= 'state_prefix = "' . CleanQuery($_GET['state']) . '" ';
		$firstReg = false;
	}
	if(isset($_GET['county']) && strlen($_GET['county']) > 0) {
		if(!$firstReg){
			$qRegion .= 'AND ';
		}
		$qRegion .= 'county = "' . CleanQuery($_GET['county']) . '" ';
		$firstReg = false;
	}
	if(isset($_GET['country']) && strlen($_GET['country']) > 0) {
		if(!$firstReg){
			$qRegion .= 'AND ';
		}
		$qRegion .= 'country = "' . CleanQuery($_GET['country']) . '" ';
		$firstReg = false;
	}
	if(isset($_GET['city']) && strlen($_GET['city']) > 0) {
		if(!$firstReg){
			$qRegion .= 'AND ';
		}
		$qRegion .= 'city = "' . CleanQuery($_GET['city']) . '" ';
		$firstReg = false;
	}
	if(isset($_GET['zip']) && strlen($_GET['zip']) > 0) {
		if(!$firstReg){
			$qRegion .= 'AND ';
		}
		$qRegion .= 'zip_code = "' . CleanQuery($_GET['zip']) . '" ';
		$firstReg = false;
	}
		
	$selectQ = "SELECT zipID FROM nf_locations WHERE " . $qRegion;
	$r = mysql_query($selectQ) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $selectQ);
	$zipIDs = array();
	while($result = mysql_fetch_array($r)) {
		array_push($zipIDs, $result['zipID']);
	}
	}
	echo '
    	<div id="display_frame" >
        	<div id="title_frame" >Regional Pricing</div>
        	<form name="region" action="' . basename($_SERVER['PHP_SELF']) . '" method="get">
				<div id="selection_frame" >	
					<div id="country_frame" class="scope_frame" >
						<div class="title" >Select Country</div>
						<select id="country" name="country" size="6" onchange="Select(' . chr(39) . 'country' . chr(39) .  ');" >
	';	
	
	while($country = mysql_fetch_array($c)) {
		if ($_GET['country'] == $country['country']) {
			echo '<option value="' . $country['country'] . '" SELECTED >' . $country['country'] . '</option>	';	
		} else {
			echo '<option value="' . $country['country'] . '" >' . $country['country'] . '</option>	';	
		}
	}
		
	echo '			
						</select>
					</div>
					<div id="state_frame" class="scope_frame" >
	';
	
	if (isset($_GET['country'])) {
		$query1 =   'SELECT DISTINCT state_prefix AS state 
					FROM nf_locations 
					WHERE country = "' . $_GET['country'] . '" 
					AND LENGTH(state_prefix) >= 2
					ORDER BY state_prefix';
		$s = mysql_query($query1) or die("Query failed with error: " . mysql_error() . "<br />");
		echo '
						<div class="title" >Select State</div>
						<select id="state" size="6" name="state" onchange="Select(' . chr(39) . 'state' . chr(39) .  ');">
		';
		
		while($state = mysql_fetch_array($s)) {
			if ($_GET['state'] == $state['state']) {
				echo '<option value="' . $state['state'] . '" SELECTED >' . $state['state'] . '</option>	';	
			} else {
				echo '<option value="' . $state['state'] . '" >' . $state['state'] . '</option>	';	
			}
		}
		
		echo '
						</select>
		';
	}
	
	echo '
					</div>
					<div id="county_frame" class="scope_frame" >
	';
	
	if (isset($_GET['state'])) {
		$query1 =   'SELECT DISTINCT county 
					FROM nf_locations 
					WHERE state_prefix = "' . $_GET['state'] . '" 
					AND LENGTH(county) > 0
					ORDER BY county';
		$co = mysql_query($query1) or die("Query failed with error: " . mysql_error() . "<br />");
		echo '
						<div class="title" >Select County</div>
						<select id="county" name="county" size="6" onchange="Select(' . chr(39) . 'county' . chr(39) .  ');">
		';
		
		while($county = mysql_fetch_array($co)) {
			if ($_GET['county'] == $county['county']) {
				echo '<option value="' . $county['county'] . '" SELECTED >' . $county['county'] . '</option>	';	
			} else {
				echo '<option value="' . $county['county'] . '" >' . $county['county'] . '</option>	';	
			}
		}
		
		echo '
						</select>
		';
	}
	
	echo '
					</div>
					<div id="city_frame" class="scope_frame" >
	';
	
	if (isset($_GET['county'])) {
		$query1 =   'SELECT DISTINCT city 
					FROM nf_locations 
					WHERE county = "' . $_GET['county'] . '" 
					AND LENGTH(city) > 0
					ORDER BY city';
		$ci = mysql_query($query1) or die("Query failed with error: " . mysql_error() . "<br />");
		echo '
						<div class="title" >Select City</div>
						<select id="city" name="city" size="6" onchange="Select(' . chr(39) . 'city' . chr(39) .  ');">
		';
		
		while($city = mysql_fetch_array($ci)) {
			if ($_GET['city'] == $city['city']) {
				echo '<option value="' . $city['city'] . '" SELECTED >' . $city['city'] . '</option>	';	
			} else {
				echo '<option value="' . $city['city'] . '" >' . $city['city'] . '</option>	';	
			}
		}
		
		echo '
						</select>
		';
	}
	
	echo '
					</div>
					<div id="city_frame" class="scope_frame" >
	';
	
	if (isset($_GET['county'])) {
		$query1 =   'SELECT DISTINCT zip_code AS zip 
					FROM nf_locations 
					WHERE county = "' . $_GET['county'] . '" 
					AND LENGTH(zip_code) > 0
					ORDER BY zip_code';
		$z = mysql_query($query1) or die("Query failed with error: " . mysql_error() . "<br />");
		echo '
						<div class="title" >Select Zip</div>
						<select id="zip" name="zip" size="6" onchange="Select(' . chr(39) . 'zip' . chr(39) .  ');">
		';
		
		while($zip = mysql_fetch_array($z)) {
			if ($_GET['zip'] == $zip['zip']) {
				echo '<option value="' . $zip['zip'] . '" SELECTED >' . $zip['zip'] . '</option>	';	
			} else {
				echo '<option value="' . $zip['zip'] . '" >' . $zip['zip'] . '</option>	';	
			}
		}
		
		echo '
						</select>
		';
	}
	
	echo '
				</div>
				</div>
	';
/**********************************************************************************************
PRICING FRAME
**********************************************************************************************/
	echo '
				<form method="post" action="">
					<table border="0" cellspacing="0" cellpadding="5" style="width:auto;">
					  <tr>
						<td>
						  <label for="get_state">State:</label>
						  <input style="width: 30px;" type="text" name="get_state" />
						</td>
						<td>
						  <label for="get_county">County:</label>
						  <input style="width: 110px;" type="text" name="get_county" />
						</td>
						<td>
						  <label for="get_city">City:</label>
						  <input style="width: 110px;" type="text" name="get_city" />
						</td>
						<td>
						  <label for="textfield">Zip Code:</label>
						  <input style="width: 90px;" type="text" name="get_zip" />
						</td>
						<td><br><input type="submit" name="button" id="button" value="Open Area" /></td>
						<td width="90px"><br>[<a href="?open_region=true&'.$location.'">Create Region</a>]</td>
					  </tr>
					</table>
				</form>
				<div class="pricing_frame">
					<div class="title" >
	';
		
	$query1 = '';
	if(isset($_GET['country']) && strlen($_GET['country']) > 0) {
		echo 'Current Pricing For Region: ';
		echo $_GET['country'];
		
		$query1 .= '
			SELECT 
			p.pricingID, p.itemType, p.itemID,
			tt.tourTypeName as tname,
			pr.productName AS pname,
			p.price, 
			l.country, l.state_prefix, l.county, l.city, l.zip_code AS zip 
			FROM nf_pricing p
			LEFT JOIN tourtypes tt ON p.itemType = "tour" AND p.itemID = tt.tourTypeID
			LEFT JOIN products pr ON p.itemType = "product" AND p.itemID = pr.productID,
			nf_locations l
			WHERE p.category = "region"
			AND p.itemType != "mileage"
			AND l.zipID = p.categoryID
			AND (
		';
		
		$first=true;
		foreach($zipIDs as $index => $zipID){
			if(!$first){
				$query1 .= " OR ";
			}
			$query1 .= "p.categoryID = '".$zipID."'";
			$first=false;
		}
		
		$query1 .= ")";
		$query1 .= ' ORDER BY p.itemType, pr.productName, tt.tourTypeName';			
		
		echo '
						</div>
						<div class="listing_frame" style="overflow-y: scroll;" >
							<table style="width: 100%;" >
								<tr>
									<th>Type</th>
									<th>Item</th>
									<th>Price</th>
									<th>Location</th>
									<th><button onclick="CheckAll(' . chr(39) . 'checkbox' . chr(39) . ');">All</button></th>
									<th><button onclick="ConfirmDelete(' . chr(39) . 'All Checked Pricing' . chr(39) .',' . chr(39) . basename($_SERVER['PHP_SELF']) . '?' . $location . chr(39) . ',' . chr(39) . 'checkbox' . chr(39) . ');">Delete</button></th>
								</tr>
		';
		$pr = mysql_query($query1) or die("Query failed with error: " . mysql_error() . "<br />");
		$highlight = true;
		while ($pricing = mysql_fetch_array($pr)) {
			$name = "--";
			if (strlen($pricing['tname']) > 0) {
				$name = $pricing['tname'];
			} elseif (strlen($pricing['pname']) > 0) {
				$name = $pricing['pname'];
			}
			
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "nohighlight";
			}
			$highlight = !$highlight;
			
			echo '
			<tr class="' . $class . '" >
				<td>' . $pricing['itemType'] . '</td>
				<td>' . $name . '</td>
				<td>$' . $pricing['price'] . '</td>
				<td>' . $pricing['country'] . ' - ' . $pricing['state_prefix'] . ' - ' . $pricing['county'] . ' - ' . 
						$pricing['city'] . ' - ' . $pricing['zip'] . 
				'</td>
				<td><input class="checkbox" type="checkbox" id="checkbox_' . $pricing['pricingID'] . '" name="checkbox"></input>
				</td>
				<td><img src="../repository_images/del.png" onclick="ConfirmDelete(' . chr(39) . 'price of ' . $pricing['price'] . chr(39) .  ', ' . chr(39) . basename($_SERVER['PHP_SELF']) . '?del=' . $pricing['pricingID'] . '&' . $location . chr(39) . ',' . chr(39) . 'checkbox' . chr(39) . ');" /></td>
			</tr>
			
			';	
		}
		
		echo '
							</table>
						</div>
					</div>
				</form>
				<form name="add_region_price" action="' . basename($_SERVER['PHP_SELF']) . '" method="get">
					<input name="add_country" type="hidden" value="' . $_GET['country'] . '" />
					<input name="add_state" type="hidden" value="' . $_GET['state'] . '" />
					<input name="add_county" type="hidden" value="' . $_GET['county'] . '" />
					<input name="add_city" type="hidden" value="' . $_GET['city'] . '" />
					<input name="add_zip" type="hidden" value="' . $_GET['zip'] . '" />
					
					<div id="add_frame" >
						<div class="title" >Change prices for your current region.</div>
						<div class="formrow" >
							<div class="row r_name" >Category</div>
							<div class="row r_content" >
								<select id="add_type" name="add_type" class="input mid" onchange="GetForm(' . chr(39) . 'add_type' . chr(39) . ', ' . chr(39) . 'add_ids' . chr(39) . ', ' . chr(39) . 'add_id' . chr(39) . ');" >
									<option value="" >Select Type</option>
									<option value="tour">Tour</option>
									<option value="product">Product</option>							
								</select>
							</div>
						</div>
						<div id="add_ids" >
							<div class="formrow" style="height:145px;">
								<div class="row r_name" >Item</div>
								<div class="row r_content" >
									Choose a category
								</div>
							</div>
						</div>
						<div class="formrow">
							<div class="row r_name" >Price</div>
							<div class="row r_content" >
								<input id="add_price" name="add_price" class="input mid exp" type="text" /> (ex. 3.20 for $3.20)
							</div>
						</div>
						<div class="formrow" >
							<div class="row r_name invisible" ></div>
							<div class="row r_content" >
								<input type="button" value="submit" onclick="AreYouSure();" />
							</div>
						</div>
					</div>
				</form>
		';
/**********************************************************************************************
MILEAGE FRAME
**********************************************************************************************/
		echo '
				<div class="pricing_frame">
					<div class="title" >
		';
			
		echo 'Current Mileage Rates For Region: ';
		echo $_GET['country'];
		$query = '
			SELECT 
			p.pricingID, p.itemType, p.price, 
			l.country, l.state_prefix, l.county, l.city, l.zip_code AS zip 
			FROM nf_pricing p, nf_locations l 
			WHERE p.category = "region"
			AND l.zipID = p.categoryID
			AND p.itemType = "mileage"
			AND (
		';
		
		$first=true;
		foreach($zipIDs as $index => $zipID){
			if(!$first){
				$query .= " OR ";
			}
			$query .= "p.categoryID = '".$zipID."'";
			$first=false;
		}
		
		$query .= ")";
		$query .= ' ORDER BY p.itemType';		
		echo '
				</div>
				<div class="listing_frame" style="overflow-y: scroll;" >
					<table style="width: 100%;" >
						<tr>
							<th>Type</th>
							<th>Price</th>
							<th>Location</th>
							<th></th>
						</tr>
		';
		$pr = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		$highlight = true;
		while ($pricing = mysql_fetch_array($pr)) {
			
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "nohighlight";
			}
			$highlight = !$highlight;
			
			echo '
						<tr class="' . $class . '" >
							<td>' . $pricing['itemType'] . '</td>
							<td>$' . $pricing['price'] . '</td>
							<td>' . $pricing['country'] . ' - ' . $pricing['state_prefix'] . ' - ' . $pricing['county'] . ' - ' . $pricing['city'] . ' - ' . $pricing['zip'] . '</td>
							<td><img src="../repository_images/del.png" onclick="ConfirmDelete(' . chr(39) . 'price of ' . $pricing['price'] . chr(39) .  ', ' . chr(39) . basename($_SERVER['PHP_SELF']) . '?del=' . $pricing['pricingID'] . '&' . $location . chr(39) . ',' . chr(39) . 'checkbox' . chr(39) . ');" /></td>
						</tr>
			
			';	
		}
		echo '
					</table>
		';
			
		echo '
				</div>
			</div>
		
			<form name="add_region_mileage" action="' . basename($_SERVER['PHP_SELF']) . '" method="get">
				<input name="add_mileage_country" type="hidden" value="' . $_GET['country'] . '" />
				<input name="add_mileage_state" type="hidden" value="' . $_GET['state'] . '" />
				<input name="add_mileage_county" type="hidden" value="' . $_GET['county'] . '" />
				<input name="add_mileage_city" type="hidden" value="' . $_GET['city'] . '" />
				<input name="add_mileage_zip" type="hidden" value="' . $_GET['zip'] . '" />
				
				<div id="add_frame" >
					<div class="title" >Change mileage prices for your current region.</div>
					<div class="formrow" >
						<div class="row r_name" >Price</div>
						<div class="row r_content" >
							<input id="add_mileage_price" name="add_mileage_price" class="input mid exp" type="text" /> (ex. 3.20 for $3.20)
						</div>
					</div>
					<div class="formrow" >
						<div class="row r_name invisible" ></div>
						<div class="row r_content" >
							<input type="button" value="submit" onclick="Mileage();" />
						</div>
					</div>
				</div>
			</form>
		';
/**********************************************************************************************
BLACKLIST FRAME
**********************************************************************************************/	
		if(isset($_GET['state'])&&strlen($_GET['state']) > 0){
			echo '
				<div class="pricing_frame">
					<div class="title" >
			';
				
			echo 'Current Blacklist For Region: ';
			echo $_GET['country'];
			$query = '
				SELECT 
				bl.blacklistID, bl.itemType, bl.permission,
				tt.tourTypeName as tname,
				pr.productName AS pname,
				l.country, l.state_prefix, l.county, l.city, l.zip_code AS zip 
				FROM nf_blacklist bl 
				LEFT JOIN tourtypes tt ON bl.itemType = "tour" AND bl.itemID = tt.tourTypeID
				LEFT JOIN products pr ON bl.itemType = "product" AND bl.itemID = pr.productID
				, nf_locations l
				WHERE bl.category = "region"
				AND l.zipID = bl.categoryID
				AND (
			';
			
			$first=true;
			foreach($zipIDs as $index => $zipID){
				if(!$first){
					$query .= " OR ";
				}
				$query .= "bl.categoryID = '".$zipID."'";
				$first=false;
			}
			$query .=")";
			$query .= ' ORDER BY bl.itemType, pr.productName, tt.tourTypeName';		
			echo '
					</div>
					<div class="listing_frame" style="overflow-y: scroll;" >
						<table style="width: 100%;" >
							<tr>
								<th>Type</th>
								<th>Item</th>
								<th>Location</th>
								<th>Permission</th>
								<th>
									<button onclick="CheckAll(' . chr(39) . 'bl_checkbox' . chr(39) . ');">All</button>
									<br>
									<button onclick="SetPermissionOnChecked(1,' . chr(39) . 
											basename($_SERVER['PHP_SELF']) . '?' . $location . chr(39) . ',' . chr(39) . 'bl_checkbox' . chr(39) . 
											');">Allow</button>
									<br>
									<button onclick="SetPermissionOnChecked(0,' . chr(39) . 
											basename($_SERVER['PHP_SELF']) . '?' . $location . chr(39) . ',' . chr(39) . 'bl_checkbox' . chr(39) . 
											');">Deny</button>
									
								</th>
								<th><button onclick="ConfirmDelete(' . chr(39) . 'All Checked Blacklistings' . chr(39) .',' . chr(39) . 
											basename($_SERVER['PHP_SELF']) . '?' . $location . chr(39) . ',' . chr(39) . 'bl_checkbox' . chr(39) . 
											');">Delete</button>
								</th>
							</tr>
			';
			$pr = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
			$highlight = true;
			while ($blacklist = mysql_fetch_array($pr)) {
				$name = "--";
				if (strlen($blacklist['tname']) > 0) {
					$name = $blacklist['tname'];
				} elseif (strlen($blacklist['pname']) > 0) {
					$name = $blacklist['pname'];
				}
				
				$permission = "--";
				if ($blacklist['permission'] == 0) {
					$permission = '<span style="color: red;" >deny</span>';
				} else {
					$permission = '<span style="color: green;" >allow</span>';
				}
				
				if ($highlight) {
					$class = "highlight";
				} else {
					$class = "nohighlight";
				}
				$highlight = !$highlight;
				
				echo '
							<tr class="' . $class . '" >
								<td>' . $blacklist['itemType'] . '</td>
								<td>' . $name . '</td>
								<td>' . $blacklist['country'] . ' - ' . $blacklist['state_prefix'] . ' - ' . $blacklist['county'] . ' - ' . 
										$blacklist['city'] . ' - ' . $blacklist['zip'] . '</td>
								<td>' . $permission . '</td>
								<td><input class="bl_checkbox" type="checkbox" id="bl_checkbox_' . $blacklist['blacklistID'] . 
										'" name="bl_checkbox"></input>
								</td>
								<td><img src="../repository_images/del.png" onclick="ConfirmDelete(' . chr(39) . 'blacklist item?' . chr(39) .  
										', ' . chr(39) . basename($_SERVER['PHP_SELF']) . '?del_bl=' . $blacklist['blacklistID'] . '&' . 
										$location . chr(39) . ',' . chr(39) . 'bl_checkbox' . chr(39) . ');" /></td>
							</tr>
				
				';	
			}
			echo '
						</table>
					</div>
				</div>
			';
		}
	
		echo '
				<form name="add_blacklist" action="' . basename($_SERVER['PHP_SELF']) . '" method="get">
					<input name="bl_country" type="hidden" value="' . $_GET['country'] . '" />
					<input name="bl_state" type="hidden" value="' . $_GET['state'] . '" />
					<input name="bl_county" type="hidden" value="' . $_GET['county'] . '" />
					<input name="bl_city" type="hidden" value="' . $_GET['city'] . '" />
					<input name="bl_zip" type="hidden" value="' . $_GET['zip'] . '" />
					
					<div id="add_frame" >
						<div class="title" >Change permissions for your current region.</div>
						<div class="formrow" >
							<div class="row r_name" >Category</div>
							<div class="row r_content" >
								<select id="bl_type" name="bl_type" class="input mid" onchange="GetForm(' . chr(39) . 'bl_type' . chr(39) . ', ' . chr(39) . 'bl_ids' . chr(39) . ', ' . chr(39) . 'bl_id' . chr(39) . ');" >
									<option value="" >Select Type</option>
									<option value="tour">Tour</option>
									<option value="product">Product</option>							
								</select>
							</div>
						</div>
						<div id="bl_ids" >
							<div class="formrow" style="height:145px;" >
								<div class="row r_name" >Item</div>
								<div class="row r_content" >
									Choose a category
								</div>
							</div>
						</div>
						<div id="bl_items">
							<div class="formrow" style="position:static" >
								<div class="row r_name" >Permission</div>
								<div class="row r_content" >
									<input type="radio" name="bl_permission" value="1">Allow
									<input type="radio" name="bl_permission" value="0" checked="checked" >Deny
								</div>
							</div>
						</div>
						<div class="formrow" >
							<div class="row r_name invisible" ></div>
							<div class="row r_content" >
								<input type="button" value="submit" onclick="Blacklist();" />
							</div>
						</div>
					</div>
				</form> ';
		
		echo '
			</div>
			';
	}
?>
	</body>
</html>