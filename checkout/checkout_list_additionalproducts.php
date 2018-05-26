<?php
/**********************************************************************************************
Document: checkout_list_additionalproducts.php
Creator: Brandon Freeman
Date: 02-22-11
Purpose: Creates the html elements for displaying additional products. (for Ajax request)  
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

//=======================================================================
// Document
//=======================================================================
	
	if (isset($_POST['id'])) {
		$id = CleanQuery($_POST['id']);
	} elseif (isset($_GET['id'])) {
		$id = CleanQuery($_GET['id']);
	}
	
	// Set broker id.
	if (isset($_POST['brokerid'])) {
		$brokerid = cleanQuery($_POST['brokerid']);
	} else {
		$brokerid = -1;
	}

	// Set city.
	if (isset($_POST['city'])) {
		$city = cleanQuery($_POST['city']);
	} else {
		$city = -1;
	}

	// Set zip.
	if (isset($_POST['zip'])) {
		$zip = cleanQuery($_POST['zip']);
	} else {
		$zip = -1;
	}

	// If we don't have enough information, just get out.
	if ($city == -1 || $zip == -1 || $id == -1) {
		die("Not Enough Information: id=" . $id . " brokerid=" . $brokerid . " city=" . $city . " zip=" . $zip);
	}
	
	$product['productID'] = 0;
	
	$leftoption = true;  // What we use to toggle left orientation for the option.
	
	$query = '
		SELECT p.productID, p.visible, p.sort, p.productName, p.tagline, p.unitPrice, p.chargeSalesTax, p.productIcon, p.monthly, p.onePerOrder, p.checkoutFormName 
		FROM tour_products tp
		LEFT JOIN products p ON  tp.productID = p.productID
		WHERE p.productName IS NOT NULL 
		AND p.parentProduct IS NULL
		AND tp.tourTypeID = ' . $id.'
		ORDER BY p.sort ASC';
		
	$p = mysql_query($query) or die("Query failed with error:<br />" . mysql_error() . "<br />Query being run:<br />" . $query);
	
	$query = 'SELECT zip_code.city, zip_code.zip_code,';
	while($product = mysql_fetch_array($p)){
		$query .= '
			(SELECT products.unitPrice FROM products WHERE products.productID = ' . $product['productID'] . ' LIMIT 1) AS ' . $product['productID'] . '_default_price,
			(SELECT pricing_brokers_additional.unitPrice FROM pricing_brokers_additional WHERE pricing_brokers_additional.product_id = ' . $product['productID'] . ' AND pricing_brokers_additional.brokerage_id = ' . $brokerid . ' LIMIT 1) AS ' . $product['productID'] . '_broker_price,
			(SELECT pricing_cities_additional.unitprice FROM pricing_cities_additional WHERE pricing_cities_additional.city_id = zip_code_state_county_city.zip_code_state_id AND tourtype_id = ' . $product['productID'] . ' LIMIT 1) AS ' . $product['productID'] . '_city_price,
			(SELECT pricing_zips_additional.unitprice FROM pricing_zips_additional WHERE pricing_zips_additional.zip_id = zip_code.zipid AND tourtype_id = ' . $product['productID'] . ' LIMIT 1) as zip_price,
			(SELECT pricing_counties_additional.unitprice FROM pricing_counties_additional WHERE pricing_counties_additional.county_id = zip_code_state_county.zip_code_state_id AND tourtype_id = ' . $product['productID'] . ' LIMIT 1) AS ' . $product['productID'] . '_county_price,
			(SELECT pricing_states_additional.unitprice FROM pricing_states_additional WHERE pricing_states_additional.state_id = zip_code_state.zip_code_state_id AND tourtype_id = ' . $product['productID'] . ' LIMIT 1) AS ' . $product['productID'] . '_state_price,';
	}
	// Remove the last character, being the comma, from the string.
	// We have to move on to the FROM.
	$query = substr($query,0,-1);
	$query .= '
		FROM ((
			zip_code
			LEFT JOIN zip_code_state_county_city
			ON zip_code.state_prefix = zip_code_state_county_city.state
			AND zip_code.county = zip_code_state_county_city.county
			AND zip_code.city = zip_code_state_county_city.city)
				LEFT JOIN zip_code_state_county
				ON zip_code.state_prefix = zip_code_state_county.state
				AND zip_code.county = zip_code_state_county.county)
					LEFT JOIN zip_code_state
					ON zip_code.state_prefix = zip_code_state.state
		WHERE zip_code.zip_code = "' . $zip . '"
		AND zip_code.city = "' . $city . '"
		LIMIT 1';
	
	$pr = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query being run: " . str_replace(Chr(10), "<br>", $query));
	$pricing = mysql_fetch_array($pr);

	$first = true;
	$list = "";

	// Set the tour results back to its first element.
	@mysql_data_seek($p,0);
	while($product = mysql_fetch_array($p)){
		$price = -1;
		// Get the price of the tour.
		if ($pricing[$product['productID'] . '_broker_price'] != null) {
			$price = $pricing[$product['productID'] . '_broker_price'];
		} elseif ($pricing[$product['productID'] . '_zip_price'] != null) {
			$price = $pricing[$product['productID'] . '_zip_price'];
		} elseif ($pricing[$product['productID'] . '_city_price'] != null) {
			$price = $pricing[$product['productID'] . '_city_price'];
		} elseif ($pricing[$product['productID'] . '_county_price'] != null) {
			$price = $pricing[$product['productID'] . '_county_price'];
		} elseif ($pricing[$product['productID'] . '_state_price'] != null) {
			$price = $pricing[$product['productID'] . '_state_price'];
		} else {
			$price = $product['unitPrice'];
		}
		
		if ($leftoption) {
			$orientation = 'optleft';
		} else {
			$orientation = 'optright';
		}
		$leftoption = !$leftoption; // Toggle for the next item.
		
		if (!isset($product['tagline']) || strlen($product['tagline']) == 0) { // If we don't have a tagline in the DB, just put the price in.
			$product['tagline'] = "$" . number_format($price, 2, '.', '');
		}

		if($product['visible']){
		
			print '
						<!--- Option ' . $orientation . ' --->
						<div id="' . $product['productID'] . '-additionalproduct" class="optionframe optionstd ' . $orientation . '" >
							<input id="' . $product['productID'] . '-productprice" type="hidden" value="' . $price . '" />
							<div class="optionicon" >
			';
			
			if (strlen($product['productIcon']) > 0) {
				echo '
								<img src="' . $product['productIcon'] . '" />
				';
			} else {
				echo '
								<img src="../repository_thumbs/product_unknown.png" />
				';
			}
			
			echo '
							</div>
							<div class="optiontitle" >' . $product['productName'] . '</div>
							<div class="optionsubtitle" >' . str_replace("<price>", "$" . number_format($price, 2, '.', ''), $product['tagline']) . '</div>
							<div id="btnlearn' . $product['productID'] . '" class="optionbutton btnleft" onmouseover="HighlightBtn(' . chr(39) . 'btnlearn' . $product['productID'] . chr(39) . ');" onmouseout="DeHighlightBtn(' . chr(39) . 'btnlearn' . $product['productID'] . chr(39) . ');" onclick="DispProductDescription(' . chr(39) . $product['productName'] . chr(39) . ');" >
								<div id="btnlearn' . $product['productID'] . 'capl" class="btncap btncapl" ></div>
								<div id="btnlearn' . $product['productID'] . 'icon" class="btnicon btnbody" >?</div>
								<div id="btnlearn' . $product['productID'] . 'txt" class="btntxt btnbody" >Learn more</div>
								<div id="btnlearn' . $product['productID'] . 'capr" class="btncap btncapr" ></div>
							</div>
			';
			
			if (isset($product['checkoutFormName']) && strlen($product['checkoutFormName']) > 0) {  // If we have a form to use, use that form.
				echo '
							<input id="' . $product['productID'] . '-productincrement" type="hidden" value="form">
							<div id="btnform' . $product['productID'] . '" class="optionbutton btnright visible" onmouseover="HighlightBtn(' . chr(39) . 'btnform' . $product['productID'] . chr(39) . ');" onmouseout="DeHighlightBtn(' . chr(39) . 'btnform' . $product['productID'] . chr(39) . ');" onclick="ToggleForm(' . chr(39) . $product['checkoutFormName'] . chr(39) . ');" >
								<div id="btnform' . $product['productID'] . 'capl" class="btncap btncapl" ></div>
								<div id="btnform' . $product['productID'] . 'icon" class="btnicon btnbody" >
									<img class="iconimage" src="../repository_images/build.png" />
								</div>
								<div id="btnform' . $product['productID'] . 'txt" class="btntxt btnbody" >Add to order</div>
								<div id="btnform' . $product['productID'] . 'capr" class="btncap btncapr" ></div>
							</div>
							<div id="btnform' . $product['productID'] . 'hl" class="optionbutton btnright hidden" onclick="ToggleForm(' . chr(39) . $product['checkoutFormName'] . chr(39) . ');" >
								<div class="btncap bl_opt_cap_l" ></div>
								<div class="bl_opt_text" >Selected</div>
								<div class="btncap bl_opt_cap_r" ></div>
							</div>
				';
			} else if ($product['onePerOrder'] == 1) {  // Otherwise, if we only have one per order, toggle the button and add price.
				echo '
							<input id="' . $product['productID'] . '-productincrement" type="hidden" value="single">
							<div id="btnform' . $product['productID'] . '" class="optionbutton btnright visible" onmouseover="HighlightBtn(' . chr(39) . 'btnform' . $product['productID'] . chr(39) . ');" onmouseout="DeHighlightBtn(' . chr(39) . 'btnform' . $product['productID'] . chr(39) . ');" onclick="SetItem(' . $product['productID'] . ', 1);" >
								<div id="btnform' . $product['productID'] . 'capl" class="btncap btncapl" ></div>
								<div id="btnform' . $product['productID'] . 'icon" class="btnicon btnbody" >+</div>
								<div id="btnform' . $product['productID'] . 'txt" class="btntxt btnbody" >Add to order</div>
								<div id="btnform' . $product['productID'] . 'capr" class="btncap btncapr" ></div>
							</div>
							<div id="btnform' . $product['productID'] . 'hl" class="optionbutton btnright hidden" onclick="SetItem(' . $product['productID'] . ', 0);" >
								<div class="btncap bl_opt_cap_l" ></div>
								<div class="bl_opt_text" >Selected</div>
								<div class="btncap bl_opt_cap_r" ></div>
							</div>
				';
			} else {  // Otherwise, we must have a quantity toggle.
				echo '
							<input id="' . $product['productID'] . '-productincrement" type="hidden" value="multi">
							<div id="btnform' . $product['productID'] . '" class="optionbutton btnright" >
								<div class="counterframe" >
									<div class="counterbutton counterminus" onclick="SubOne(' . $product['productID'] . ');" ></div>
									<input id="' . $product['productID'] . '-productcounter" class="counterinput" type="text" value=0 onchange="UpdateCount(' . $product['productID'] . ')" />
									<div class="counterbutton counterplus" onclick="AddOne(' . $product['productID'] . ');" ></div>
								</div>
							</div>
				';
			}
			
			echo'
						</div>
			';
		}else{
			print '
				<input id="' . $product['productID'] . '-productprice" type="hidden" value="' . $price . '" />
			';
		}
	}

?>