<?php
/**********************************************************************************************
Document: checkout_orderpricing.php
Creator: Brandon Freeman
Date: 03-21-11
Purpose: Builds an order array for pricing.
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	if (!isset($dbc)) {
		require_once ('../repository_inc/connect.php');
	}
	
//=======================================================================
// Document
//=======================================================================
	
	function pricing($tourtypes, $add_prod, $city, $zip, $brokerid) {
		
		$error_text = "";
		
		$items = array(); //Array to house our items.
		
		// We will use this for additional products.
		// So, there might not always be a tour for the order.
		if (sizeof($tourtypes) > 0) {
			
			// Build the conditions for the query
			// This will go through each item in the array and make a condition for getting the product prices that we need.
			$first = true;
			$query_conditions = ' (';
			for ($i = 0; $i < sizeof($tourtypes); $i++) {
				if ($first) {
					$first = false;
				} else {
					$query_conditions .= ' OR ';
				}
				$query_conditions .= ' tourTypeID = "' . $tourtypes[$i]['id'] . '" ';
			}
			$query_conditions .= ') ';
			
			$query = '
			SELECT tourTypeID, tourTypeName, tourCategory, unitPrice, persistence, expressDiscount, monthly
			FROM tourtypes
			WHERE ' . $query_conditions;
			
			$t = mysql_query($query) or $error_text .= "Query failed with error: " . mysql_error() . Chr(10) . "Query being run: " . $query . Chr(10);
			
			// A place to house our tour information.
			$tourInfo = array();
			
			// Time to dynamically build a pricing query.
			$query = 'SELECT zip_code.city, zip_code.zip_code,';
			while($tour = mysql_fetch_array($t)){

				// While we run through the returned products, add their product information to the array using their productID as the array index.
				$tourInfo[intval($tour['tourTypeID'])]['name'] = $tour['tourTypeName'];
				$tourInfo[intval($tour['tourTypeID'])]['category'] = $tour['tourCategory'];
				$tourInfo[intval($tour['tourTypeID'])]['price'] = $tour['unitPrice'];
				$tourInfo[intval($tour['tourTypeID'])]['persistence'] = $tour['persistence'];
				$tourInfo[intval($tour['tourTypeID'])]['expressdiscount'] = $tour['expressDiscount'];
				$tourInfo[intval($tour['tourTypeID'])]['monthly'] = $tour['monthly'];
				
				// Build the query for tour pricing.
				$query .= '
				(SELECT tourtypes.unitPrice FROM tourtypes WHERE tourtypes.tourTypeID = ' . $tour['tourTypeID'] . ' LIMIT 1) AS ' . $tour['tourTypeID'] . '_default_price,' . Chr(10) . '
				(SELECT pricing_brokers.unitPrice FROM pricing_brokers WHERE pricing_brokers.tourtype_id = ' . $tour['tourTypeID'] . ' AND pricing_brokers.brokerage_id = ' . $brokerid . ' LIMIT 1) AS ' . $tour['tourTypeID'] . '_broker_price,' . Chr(10) . '
				(SELECT pricing_brokers.broker_billable FROM pricing_brokers WHERE pricing_brokers.tourtype_id = ' . $tour['tourTypeID'] . ' AND pricing_brokers.brokerage_id = ' . $brokerid . ' LIMIT 1) AS ' . $tour['tourTypeID'] . '_broker_billable,' . Chr(10) . '
				(SELECT pricing_cities.unitprice FROM pricing_cities WHERE pricing_cities.city_id = zip_code_state_county_city.zip_code_state_id AND tourtype_id = ' . $tour['tourTypeID'] . ' LIMIT 1) AS ' . $tour['tourTypeID'] . '_city_price,' . Chr(10) . '
				(SELECT pricing_zips.unitprice FROM pricing_zips WHERE pricing_zips.zip_id = zip_code.zipid AND tourtype_id = ' . $tour['tourTypeID'] . ' LIMIT 1) as zip_price,' . Chr(10) . '
				(SELECT pricing_counties.unitprice FROM pricing_counties WHERE pricing_counties.county_id = zip_code_state_county.zip_code_state_id AND tourtype_id = ' . $tour['tourTypeID'] . ' LIMIT 1) AS ' . $tour['tourTypeID'] . '_county_price,' . Chr(10) . '
				(SELECT pricing_states.unitprice FROM pricing_states WHERE pricing_states.state_id = zip_code_state.zip_code_state_id AND tourtype_id = ' . $tour['tourTypeID'] . ' LIMIT 1) AS ' . $tour['tourTypeID'] . '_state_price,';
			}
	
			// Remove the last character, being the comma, from the string.
			// We have to move on to the FROM.
			$query = substr($query,0,-1);
	
			$query .= '
			FROM ((' . Chr(10) . '
				zip_code' . Chr(10) . '
				LEFT JOIN zip_code_state_county_city' . Chr(10) . '
				ON zip_code.state_prefix = zip_code_state_county_city.state' . Chr(10) . '
				AND zip_code.county = zip_code_state_county_city.county' . Chr(10) . '
				AND zip_code.city = zip_code_state_county_city.city)' . Chr(10) . '
					LEFT JOIN zip_code_state_county' . Chr(10) . '
					ON zip_code.state_prefix = zip_code_state_county.state' . Chr(10) . '
					AND zip_code.county = zip_code_state_county.county)' . Chr(10) . '
						LEFT JOIN zip_code_state' . Chr(10) . '
						ON zip_code.state_prefix = zip_code_state.state' . Chr(10) . '
			WHERE zip_code.zip_code = "' . $zip . '"' . Chr(10) . '
			AND zip_code.city = "' . $city . '"' . Chr(10) . '
			LIMIT 1;' . Chr(10) . '
			';
	
			$tp = mysql_query($query) or $error_text .= "Query failed with error: " . mysql_error() . Chr(10) . "Query being run: " . $query . Chr(10);
			$tpricing = mysql_fetch_array($tp);
			
			for ($i = 0; $i < sizeof($tourtypes); $i++) {
				// Get the price of the tour.
				$price = -1;
				if ($tpricing[$tourtypes[$i]['id'] . '_broker_price'] != null) {
					$price = $tpricing[$tourtypes[$i]['id'] . '_broker_price'];
				} elseif ($tpricing[$tourtypes[$i]['id'] . '_zip_price'] != null) {
					$price = $tpricing[$tourtypes[$i]['id'] . '_zip_price'];
				} elseif ($tpricing[$tourtypes[$i]['id'] . '_city_price'] != null) {
					$price = $tpricing[$tourtypes[$i]['id'] . '_city_price'];
				} elseif ($tpricing[$tourtypes[$i]['id'] . '_county_price'] != null) {
					$price = $tpricing[$tourtypes[$i]['id'] . '_county_price'];
				} elseif ($tpricing[$tourtypes[$i]['id'] . '_state_price'] != null) {
					$price = $tpricing[$tourtypes[$i]['id'] . '_state_price'];
				} elseif (intval($tourInfo[intval($tourtypes[$i]['id'] )]['persistence'] ) == 1) {
					$price = $tourInfo[intval($tourtypes[$i]['price'])];
				} 
				
				// It's free for DIY users!  Not really ...
				if ($tourInfo[$tourtypes[$i]['id']]['expressdiscount'] == '1' && $_SESSION['express_user']) {
					$price = 0;
				}
				
				$tourInfo[$tourtypes[$i]['id']]['id'] = $tourtypes[$i]['id'];
				$tourInfo[$tourtypes[$i]['id']]['price'] = $price;
				$tourInfo[$tourtypes[$i]['id']]['brokerbillable'] = $tpricing[$tourtypes[$i]['id'] . '_broker_billable'];
				$tourInfo[$tourtypes[$i]['id']]['qty'] = $tourtypes[$i]['qty'];
				
			}

			foreach ($tourInfo as $tInfo) {
				// Push array (Item Name, Quantity, Price, Taxable, Monthly, Broker BIllable) 
				array_push($items, array('type' => 'tour', 'id' => $tInfo['id'], 'name' => $tInfo['name'], 'qty' => $tInfo['qty'], 'price' => $tInfo['price'], 'taxable' => 0, 'monthly' => $tInfo['monthly'], 'brokerbillable' => $tInfo['brokerbillable'] ));
			}
		}
		
		// Moving on to the products.
		
		// Build the conditions for the query
		// This will go through each item in the array and make a condiotion for getting the product prices that we need.
		$first = true;
		$query_conditions = ' (';
		for ($i = 0; $i < sizeof($add_prod); $i++) {
			if ($first) {
				$first = false;
			} else {
				$query_conditions .= ' OR ';
			}
			$query_conditions .= ' productID = "' . $add_prod[$i]['id'] . '" ';
		}
		$query_conditions .= ') ';
		
		if (sizeof($add_prod) > 0) { // If we have some additional products.
		
			// Create and run a query to get the information on the specified products.
			$query = '
				SELECT productID, productName, unitPrice, chargeSalesTax, monthly, checkoutFormName
				FROM products 
				WHERE productName IS NOT NULL 
				AND ' . $query_conditions;
			
			$prod = mysql_query($query) or $error_text .= "Query failed with error: " . mysql_error() . Chr(10) . "Query being run: " . $query . Chr(10);
			
			$price_query = 'SELECT zip_code.city, zip_code.zip_code,';
			
			// Array to stash product information.
			$productInfo = array();
			
			while($product = mysql_fetch_array($prod)){
				// While we run through the returned products, add their product information to the array using their productID as the array index.
				$productInfo[intval($product['productID'])]['name'] = $product['productName'];
				$productInfo[intval($product['productID'])]['price'] = $product['unitPrice'];
				$productInfo[intval($product['productID'])]['tax'] = $product['chargeSalesTax'];
				$productInfo[intval($product['productID'])]['monthly'] = $product['monthly'];
				$productInfo[intval($product['productID'])]['formname'] = $product['checkoutFormName'];
				
				// Build the secion of query for that products pricing information.
				$price_query .= '
					(SELECT products.unitPrice FROM products WHERE products.productID = ' . $product['productID'] . ' LIMIT 1) AS ' . $product['productID'] . '_default_price,
					(SELECT pricing_brokers_additional.unitPrice FROM pricing_brokers_additional WHERE pricing_brokers_additional.product_id = ' . $product['productID'] . ' AND pricing_brokers_additional.brokerage_id = ' . $brokerid . ' LIMIT 1) AS ' . $product['productID'] . '_broker_price,
					(SELECT pricing_brokers_additional.broker_billable FROM pricing_brokers_additional WHERE pricing_brokers_additional.product_id = ' . $product['productID'] . ' AND pricing_brokers_additional.brokerage_id = ' . $brokerid . ' LIMIT 1) AS ' . $product['productID'] . '_broker_billable,
					(SELECT pricing_cities_additional.unitprice FROM pricing_cities_additional WHERE pricing_cities_additional.city_id = zip_code_state_county_city.zip_code_state_id AND tourtype_id = ' . $product['productID'] . ' LIMIT 1) AS ' . $product['productID'] . '_city_price,
					(SELECT pricing_zips_additional.unitprice FROM pricing_zips_additional WHERE pricing_zips_additional.zip_id = zip_code.zipid AND tourtype_id = ' . $product['productID'] . ' LIMIT 1) as zip_price,
					(SELECT pricing_counties_additional.unitprice FROM pricing_counties_additional WHERE pricing_counties_additional.county_id = zip_code_state_county.zip_code_state_id AND tourtype_id = ' . $product['productID'] . ' LIMIT 1) AS ' . $product['productID'] . '_county_price,
					(SELECT pricing_states_additional.unitprice FROM pricing_states_additional WHERE pricing_states_additional.state_id = zip_code_state.zip_code_state_id AND tourtype_id = ' . $product['productID'] . ' LIMIT 1) AS ' . $product['productID'] . '_state_price,';
			}
			
			// Remove the last character, being the comma, from the string.
			// We have to move on to the FROM.
			$price_query = substr($price_query,0,-1);
			
			$price_query .= '
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
			$prodp = mysql_query($price_query) or $error_text .= "Query failed with error: " . mysql_error() . Chr(10) . "Query being run: " . $query . Chr(10);
			$productpricing = mysql_fetch_array($prodp);
			
			for ($i = 0; $i < sizeof($add_prod); $i++) {
				// Get the price of the product.
				if ($productpricing[$add_prod[$i]['id'] . '_broker_price'] != null) {
					$price = $productpricing[$add_prod[$i]['id'] . '_broker_price'];
				} elseif ($productpricing[$add_prod[$i]['id'] . '_zip_price'] != null) {
					$price = $productpricing[$add_prod[$i]['id'] . '_zip_price'];
				} elseif ($productpricing[$add_prod[$i]['id'] . '_city_price'] != null) {
					$price = $productpricing[$add_prod[$i]['id'] . '_city_price'];
				} elseif ($productpricing[$add_prod[$i]['id'] . '_county_price'] != null) {
					$price = $productpricing[$add_prod[$i]['id'] . '_county_price'];
				} elseif ($productpricing[$add_prod[$i]['id'] . '_state_price'] != null) {
					$price = $productpricing[$add_prod[$i]['id'] . '_state_price'];
				} else {
					$price = $productpricing[$add_prod[$i]['id'] . '_default_price'];
				}
				// if they are realtor.com showcase members, set the price to zero
				if ($productInfo[$add_prod[$i]['id']]['formname'] == 'realtordotcom' && $_SESSION['realtordotcom']) {
					$price = 0;
				}
				
				$productInfo[$add_prod[$i]['id']]['id'] = $add_prod[$i]['id'];
				$productInfo[$add_prod[$i]['id']]['price'] = $price;
				$productInfo[$add_prod[$i]['id']]['brokerbillable'] = $productpricing[$add_prod[$i]['id'] . '_broker_billable'];
				$productInfo[$add_prod[$i]['id']]['qty'] = $add_prod[$i]['qty'];
				
				if ($productInfo[$add_prod[$i]['id']]['tax'] == 1 && $_SESSION['state'] == 'UT') {
					$productInfo[$add_prod[$i]['id']]['tax'] = 1;
				} else {
					$productInfo[$add_prod[$i]['id']]['tax'] = 0;
				}
				
			}
			
			foreach ($productInfo as $pInfo) {
				// Push array (Item Name, Quantity, Price, Taxable, Monthly, Broker BIllable) 
				array_push($items, array('type' => 'product', 'id' => $pInfo['id'], 'name' => $pInfo['name'], 'qty' => $pInfo['qty'], 'price' => $pInfo['price'], 'taxable' => $pInfo['tax'], 'monthly' => $pInfo['monthly'], 'brokerbillable' => $pInfo['brokerbillable'] ));
			}
			
		}
		if (strlen($error_text) > 0) {
			return array(-1, $error_text);
		} else {
			return $items;
		}
	}
	
	// $items - Array of items to be listed
	// $coupon - Coupon code for discount
	// $class - Class of the table for CSS purposes.
	function BuildTable($items, $coupon, $class) {
		$items = ApplyItemDiscounts($items, $coupon);
		$OrderInfo = GetOrderTotals($items, $coupon);
		
		$output = Chr(10);
		$output .= '<table class="' . $class . '" >																' . Chr(10);
		$output .= '	<tr>																					' . Chr(10);
		$output .= '		<th class="cap_left" >Your Order Summary</th>															' . Chr(10);
		$output .= '		<th>Notes</th>																		' . Chr(10);
		$output .= '		<th>Quantity</th>																	' . Chr(10);
		$output .= '		<th>Unit Price</th>																	' . Chr(10);
		$output .= '		<th class="cap_right">Total Price</th>																' . Chr(10);
		$output .= '	</tr>
																												' . Chr(10);
		foreach ($items as $item) {
			$output .= '	<tr class="standard" >																				' . Chr(10);
			$output .= '		<td>' . $item['name'] . '</td>													' . Chr(10);
			$output .= '		<td>' . str_replace(chr(10), '<br />', $item['notes']) . '</td>											' . Chr(10);
			$output .= '		<td>' . $item['qty'] . '</td>													' . Chr(10);
			$output .= '		<td>$' .  number_format($item['finalprice'], 2, '.', '') . '</td>					' . Chr(10);
			$output .= '		<td>$' .  number_format($item['finalprice'] * $item['qty'], 2, '.', '') . '</td>		' . Chr(10);
			$output .= '	</tr>																				' . Chr(10);
		}
		
		if ($OrderInfo['monthlybillable'] > 0) {
			$output .= '	<tr> <!--- Monthly Billable Value --->																	' . Chr(10);
			$output .= '		<td colspan=3 ></td>																				' . Chr(10);
			$output .= '		<td class="total_text" >Monthly Billed:</td>														' . Chr(10);
			$output .= '		<td class="total_value" >$' . number_format($OrderInfo['monthlybillable'], 2, '.', '') . '</td>					' . Chr(10);
			$output .= '	</tr>																									' . Chr(10);
		}
		
		if ($OrderInfo['newbroker'] > 0) {
			$output .= '	<tr> <!--- Total Billable --->																				' . Chr(10);
			$output .= '		<td colspan=3 ></td>																					' . Chr(10);
			$output .= '		<td class="total_text" >Current Billable:</td>																	' . Chr(10);
			$output .= '		<td class="total_value" >$' . number_format($OrderInfo['adtotal'], 2, '.', '') . '</td>						' . Chr(10);
			$output .= '	</tr>																										' . Chr(10);
			$output .= '	<tr> <!--- Broker Billable Value --->																	' . Chr(10);
			$output .= '		<td colspan=3 ></td>																				' . Chr(10);
			$output .= '		<td class="total_text" >Broker Charged:</td>														' . Chr(10);
			$output .= '		<td class="total_value" >-$' . number_format($OrderInfo['newbroker'], 2, '.', '') . '</td>						' . Chr(10);
			$output .= '	</tr>																									' . Chr(10);
		}
		
		if (strlen($OrderInfo['couponapplied']) > 0) {
			$output .= '	<tr> <!--- Coupon Code --->														' . Chr(10);
			$output .= '		<td colspan=3 ></td>															' . Chr(10);
			$output .= '		<td class="total_text" >Coupon Applied:</td>																' . Chr(10);
			$output .= '		<td class="total_value">' . $OrderInfo['couponapplied'] . '</td>						' . Chr(10);
			$output .= '	</tr>																				' . Chr(10);
		}
		
		if (strlen($OrderInfo['notes']) > 0) {
			$output .= '	<tr> <!--- Notes --->														' . Chr(10);
			$output .= '		<td colspan=3 ></td>																				' . Chr(10);
			$output .= '		<td class="total_text" >Notes:</td>																	' . Chr(10);
			$output .= '		<td class="total_value">' . $OrderInfo['notes'] . '</td>															' . Chr(10);
			$output .= '	</tr>																									' . Chr(10);
		}
		
		$output .= '	<tr> <!--- Subtotal --->																					' . Chr(10);
		$output .= '		<td colspan=3 ></td>																					' . Chr(10);
		$output .= '		<td class="total_text" >Subtotal:</td>																	' . Chr(10);
		$output .= '		<td class="total_value" >$' . number_format($OrderInfo['newsubtotal'], 2, '.', '') . '</td>								' . Chr(10);
		$output .= '	</tr>																										' . Chr(10);
		$output .= '	<tr> <!--- Sales Tax --->																					' . Chr(10);
		$output .= '		<td colspan=3 ></td>																					' . Chr(10);
		$output .= '		<td class="total_text" >Sales Tax:</td>																	' . Chr(10);
		$output .= '		<td class="total_value" >$' . number_format($OrderInfo['newtax'], 2, '.', '') . '</td>									' . Chr(10);
		$output .= '	</tr>																										' . Chr(10);
		$output .= '	<tr> <!--- Total --->																						' . Chr(10);
		$output .= '		<td colspan=3 ></td>																					' . Chr(10);
		$output .= '		<td class="total_text" >Total:</td>																		' . Chr(10);
		$output .= '		<td class="total_value" >$' . number_format($OrderInfo['newtotal'], 2, '.', '') . '</td>							' . Chr(10);
		$output .= '	</tr>																										' . Chr(10);
		$output .= '</table>																										' . Chr(10);
		$output .= '<input id="grandtotal" type="hidden" value="' . ($OrderInfo['newtotal'] + $OrderInfo['monthlybillable']) . '" />					' . Chr(10);
		
		return $output;
	}
	
	function ApplyItemDiscounts($items, $couponcode) {
		$validCoupon = false;
		
		if (isset($couponcode)) {
			
			// Determine if we have a valid coupon.
			$query = "
				SELECT * 
				FROM promocodes pc
				WHERE pc.codestr = '" . $couponcode . "' AND pc.expdate > date('" . date('Y-m-d H:i:s') . "') LIMIT 1";
			$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
			if ($result = mysql_fetch_array($r)) {
				if (intval($result['limits']) > 0) {
					$query = "
						SELECT count(coupon) as timesUsed
						FROM orders
						WHERE coupon = '" . $couponcode . "'";
					$r2 = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
					$result2 = mysql_fetch_array($r2);
					if ($result['limits'] > $result2['timesUsed']) {
						$validCoupon = true;
					}
				} elseif (intval($result['limits']) == 0) {
					$validCoupon = true;
				}
			}
			
			// Create fields for notes and move over final price from price.
			for ($i = 0; $i < sizeof($items); $i++) {
				$items[$i]['finalprice'] = floatval($items[$i]['price']);
				$items[$i]['notes'] = "";
			}
			
			// If the coupon is valid, get the complete details.
			// Apply the discounts to the items.
			if ($validCoupon) {
				$query = "
					SELECT type, Id, dollarValue, percentValue, dayValue 
					FROM promocodes pc
					RIGHT JOIN promocode_values pcv ON pc.codestr = pcv.codestr  
					WHERE pc.codestr = '" . $couponcode . "' AND pc.expdate > date('" . date('Y-m-d H:i:s') . "')";
				$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
				while ($result = mysql_fetch_array($r)) {
					
					// Loop through the list of items.
					for ($i = 0; $i < sizeof($items); $i++) {
						
						// Check to see if the coupon applies to the item.
						if($result['type']	== $items[$i]['type'] && $result['Id']	== $items[$i]['id']) {
							
							if (floatval($items[$i]['price']) > 0) {
								// Apply the day offset for montly billing.
								if ($items[$i]['monthly'] == 1 && intval($result['dayValue']) > 0) {	
									$items[$i]['billingdate'] = date('Ymd', strtotime("+" . $result['dayValue'] . " days"));
									$items[$i]['notes'] .= "Billing postponed: " . $result['dayValue'] . " days" . chr(10);
									$items[$i]['notes'] .= "Billing begins: " . date("F j, Y", strtotime("+" . $result['dayValue'] . " days")) . chr(10);
								}
								
								// Apply dollar and percentage discounts to the price.
								if (floatval($result['dollarValue']) > 0) {
									$items[$i]['finalprice'] = floatval(floatval($items[$i]['price']) - floatval($result['dollarValue']));
									$items[$i]['notes'] .= "Price reduced: $" . $result['dollarValue'] . chr(10);
								} elseif (floatval($result['percentValue']) > 0) {
									$items[$i]['finalprice'] = round(floatval(floatval($items[$i]['price']) - (floatval($result['percentValue']) * floatval($items[$i]['price']))), 2);
									$items[$i]['notes'] .= "Price reduced: " . intval(floatval($result['percentValue']) * 100) . '%' . chr(10);
								}
								
								// Make sure the final price has not been reduced past zero.
								// We aren't in the markeet of giving people money.
								if (floatval($items[$i]['finalprice']) < 0) {
									$items[$i]['finalprice'] = 0;
								}
							}
						}
					}
				}
			}
		}
		return $items;	
	}
	
	function GetOrderTotals($items, $couponcode) {
		$validCoupon = false;
		
		$taxrate = 0.0685;
		
		$subtotal = 0;
		$tax = 0;
		$brokertotal = 0;
		$couponammt = 0;
		$discounts = 0;
		
		// Determine if we have a valid coupon.
		$query = "
			SELECT * 
			FROM promocodes pc
			WHERE pc.codestr = '" . $couponcode . "' AND pc.expdate > date('" . date('Y-m-d H:i:s') . "') LIMIT 1";
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		if ($result = mysql_fetch_array($r)) {
			if (intval($result['limits']) > 0) {
				$query = "
					SELECT count(coupon) as timesUsed
					FROM orders
					WHERE coupon = '" . $couponcode . "'";
				$r2 = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
				$result2 = mysql_fetch_array($r2);
				if ($result['limits'] > $result2['timesUsed']) {
					$validCoupon = true;
				}
			} elseif (intval($result['limits']) == 0) {
				$validCoupon = true;
			}
			
		}
		
		$montlybillable = 0;
		
		// Add up the subtotal and the tax
		foreach ($items as $item) {
			if (intval($item['monthly']) != 1) {
				if (intval($item['brokerbillable']) == 1) {
					$brokertotal += floatval($item['finalprice'] * $item['qty']);
					if (intval($item['taxable']) == 1) {
						$brokertotal += floatval($item['finalprice']  * $item['qty'] * $taxrate);
					}
				} else {
					$subtotal += floatval($item['finalprice'] * $item['qty']);
					if (intval($item['taxable']) == 1) {
						$tax += floatval($item['finalprice'] * $item['qty'] * $taxrate);
					}
				}
			} else {
				$montlybillable += floatval($item['finalprice'] * $item['qty']);
			}
		}
		
		$oldsub = $subtotal;
		$oldtax = $tax;
		$oldbroker = $brokertotal;
		
		$couponammt = 0;
		$couponapplied = '';
		$notes = '';
		$completetotal = $subtotal + $tax + $brokertotal;
		
		// If the coupon is valid, get the complete details.
		// Apply the discounts to the items.
		if ($validCoupon) {
			$couponapplied = $couponcode;
			
			$query = "
				SELECT dollarValue, percentValue 
				FROM promocodes pc
				RIGHT JOIN promocode_values pcv ON pc.codestr = pcv.codestr  
				WHERE pc.codestr = '" . $couponcode . "' AND pc.expdate > date('" . date('Y-m-d H:i:s') . "') AND type = 'order' LIMIT 1";
			$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
			if ($result = mysql_fetch_array($r)) {
				if (floatval($result['dollarValue']) > 0) {
					$couponammt = floatval($result['dollarValue']);
					
					$notes .= "-$" . $result['dollarValue'] . chr(10);
					
					// Apply dollar discount to the order totals
					$discounts = $couponammt;
					if ($subtotal > $discounts) {
						$subtotal -= $discounts;
					} else {
						$discounts -= $subtotal;
						$subtotal = 0;
						// Apply what's left over to the tax.
						if ($tax > $discounts) {
							$tax  -= $discounts;
						} else {
							$discounts -= $tax;
							$tax = 0;
							// Apply what's left over to the brokertotal.
							if ($brokertotal > $discounts) {
								$brokertotal -= $discounts;
							} else {
								$brokertotal = 0;
							}
						}
					}
				} elseif (floatval($result['percentValue']) > 0) {
					$brokertotal -= (floatval($result['percentValue']) * $brokertotal);
					$subtotal -= (floatval($result['percentValue']) * $subtotal);
					$tax -= (floatval($result['percentValue']) * $tax);
					$notes .= "-" . intval(floatval($result['percentValue']) * 100) . '%' . chr(10);
				}
			}
		}
		
		// Prepare the data for output.
		$subtotal = floatval(number_format($subtotal, 2, '.', ''));
		$tax = floatval(number_format($tax, 2, '.', ''));
		$brokertotal = floatval(number_format($brokertotal, 2, '.', ''));
		$couponammt = floatval(number_format($couponammt, 2, '.', ''));
		
		return array('bdtotal' => round($completetotal ,2), 
					 'couponapplied' => $couponapplied, 
					 'couponammt' => round($couponammt, 2), 
					 'adtotal' => round(($subtotal + $tax + $brokertotal), 2), 
					 'oldsubtotal' => round($oldsub, 2), 
					 'newsubtotal' => round($subtotal, 2), 
					 'oldtax' => round($oldtax, 2), 
					 'newtax' => round($tax, 2), 
					 'oldtotal' => round(($oldsub + $oldtax), 2), 
					 'newtotal' => round(($subtotal + $tax), 2),  
					 'oldbroker' => round($oldbroker, 2), 
					 'newbroker' => round($brokertotal, 2), 
					 'monthlybillable' => round($montlybillable, 2), 
					 'notes' => $notes);
		
	}
	
	function GetMileagePrice($city, $zip) {
		
		$query = '
		SELECT zc.city, zc.zip_code,
		(SELECT unitprice FROM pricing_cities_mileage pc WHERE pc.city_id = zcscc.zip_code_state_id AND tourtype_id = 1 LIMIT 1) AS city_price,
		(SELECT unitprice FROM pricing_zips_mileage pz WHERE pz.zip_id = zc.zipid AND tourtype_id = 1 LIMIT 1) as zip_price,
		(SELECT unitprice FROM pricing_counties_mileage pco WHERE pco.county_id = zcsc.zip_code_state_id AND tourtype_id = 1 LIMIT 1) AS county_price,
		(SELECT unitprice FROM pricing_states_mileage ps WHERE ps.state_id = zcs.zip_code_state_id AND tourtype_id = 1 LIMIT 1) AS state_price
		FROM ((
			zip_code zc
			LEFT JOIN zip_code_state_county_city zcscc
			ON zc.state_prefix = zcscc.state
			AND zc.county = zcscc.county
			AND zc.city = zcscc.city)
				LEFT JOIN zip_code_state_county zcsc
				ON zc.state_prefix = zcsc.state
				AND zc.county = zcsc.county)
					LEFT JOIN zip_code_state zcs
					ON zc.state_prefix = zcs.state
		WHERE zc.zip_code = "' . $zip . '"
		AND zc.city = "' . $city . '"';

		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . Chr(10) . "Query being run: " . $query);
		$result = mysql_fetch_array($r);
		
		$price = 0;
		if ($result['zip_price'] != null) {
			$price = $result['zip_price'];
		} elseif ($result['city_price'] != null) {
			$price = $result['city_price'];
		} elseif ($result['county_price'] != null) {
			$price = $result['county_price'];
		} elseif ($result['state_price'] != null) {
			$price = $result['state_price'];
		}
		
		return floatval($price);
		
	}
	
	function ApplyMileage($items, $city, $zip) {
		$found = false;
		$mileage = GetMileagePrice($city, $zip);
		if ($mileage > 0) {
			foreach ($items as $item) {
				if (!$found && $item['type'] == 'tour') {
					array_push($items, array('type' => 'mileage', 'id' => 0, 'name' => 'Mileage: ' . $city . ' (' . $zip . ')' , 'qty' => 1, 'price' => $mileage, 'taxable' => 0, 'monthly' => 0, 'brokerbillable' => 0 ));
					$found = true;
				}
			}
		}
		
		return $items;
	}
	
?>