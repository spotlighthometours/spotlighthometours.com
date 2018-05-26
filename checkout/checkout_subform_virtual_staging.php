<?php
/**********************************************************************************************
Document: checkout_subform_virtual_staging.php
Creator: Brandon Freeman
Date: 02-16-11
Purpose: Subform for virtual staging.
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();

	$vs_index = $_POST['index'];
		
//=======================================================================
// Document
//=======================================================================	
		
	if (isset($_POST['index'])) {
		$index = $_POST['index'];
	} elseif (isset($_GET['index'])) {
		$index = $_GET['index'];
	}
	
	if (isset($_POST['zip'])) {
		$zip = $_POST['zip'];
	} elseif (isset($_GET['zip'])) {
		$zip = $_GET['zip'];
	}
	
	if (isset($_POST['city'])) {
		$city = $_POST['city'];
	} elseif (isset($_GET['city'])) {
		$city = $_GET['city'];
	}
	
	if (isset($_POST['brokerid'])) {
		$brokerid = $_POST['brokerid'];
	} elseif (isset($_GET['brokerid'])) {
		$brokerid = $_GET['brokerid'];
	}

	if (!isset($dbc)) {
		require_once ('../repository_inc/connect.php');
		require_once ('../repository_inc/clean_query.php');
	}
	
	$query = '
		SELECT productID, unitPrice 
		FROM products
		WHERE productName = "Virtual Staging" 
	';
		
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
		if ($pricing[$product['productID'] . '_broker_price'] > 0) {
			$price = $pricing[$product['productID'] . '_broker_price'];
		} elseif ($pricing[$product['productID'] . '_zip_price'] > 0 ) {
			$price = $pricing[$product['productID'] . '_zip_price'];
		} elseif ($pricing[$product['productID'] . '_city_price'] > 0 ) {
			$price = $pricing[$product['productID'] . '_city_price'];
		} elseif ($pricing[$product['productID'] . '_county_price'] > 0 ) {
			$price = $pricing[$product['productID'] . '_county_price'];
		} elseif ($pricing[$product['productID'] . '_state_price'] > 0 ) {
			$price = $pricing[$product['productID'] . '_state_price'];
		} else {
			$price = $product['unitPrice'];
		}
		
		if (!isset($single)) {
			$single = $price;
		} elseif (!isset($multi)) {
			$multi = $price;
		}
	}
	
	echo '
					<div id="' . $index . '-subform-mini" class="hidden"  >
						<div class="form_sub_cap" >
							<img class="form_corner left" src="../repository_images/checkout/sub-tl.png" />
							<div class="form_sub_cap_spacer" ></div>
							<img class="form_corner right" src="../repository_images/checkout/sub-tr.png" />
						</div>
						<div class="form_sub_frame" >
							<div class="mini_box_text" >
								<div id="' . $index . '-room-mini" class="mini_box_text_space left" onclick="SelectForm(' . $index . ');" >Room</div>
								<div class="mini_box_text_space left" >></div>
								<div id="' . $index . '-style-mini" class="mini_box_text_space left" onclick="SelectForm(' . $index . ');" >Style</div>
								<div class="mini_box_text_space left" >></div>
								<img id="' . $index . '-photo-mini" class="mini_box_photo left" src="../repository_images/pixel.png" onclick="SelectForm(' . $index . ');" />
								<div class="optionbutton right" style="margin-top: 0px;" onclick="RemoveForm(' . chr(39) . $index . chr(39) . ');" >
									<div class="btncap btncapl" ></div>
									<div class="btnicon btnbody" >X</div>
									<div class="btntxt btnbody" >Remove</div>
									<div class="btncap btncapr" ></div>
								</div>
								<div class="optionbutton right" style="margin-top: 0px; margin-right: 10px;" onclick="SelectForm(' . $index . ');" >
									<div class="btncap btncapl" ></div>
									<div class="btnicon btnbody" >
										<img class="iconimage" src="../repository_images/demo.png" />
									</div>
									<div class="btntxt btnbody" >Edit</div>
									<div class="btncap btncapr" ></div>
								</div>
								<div id="' . $index . '-price-mini" class="mini_box_text_space right" style="color: #339933" >$' . number_format($single, 2, '.', '') . '</div>
							</div>
						</div>
						<div class="form_sub_cap" >
							<img class="form_corner left" src="../repository_images/checkout/sub-bl.png" />
							<div class="form_sub_cap_spacer" ></div>
							<img class="form_corner right" src="../repository_images/checkout/sub-br.png" />
						</div>
						<div class="form_line" ></div>
					</div>
					
					<div id="' . $index . '-subform" class="visible" >
						<div class="form_sub_cap" >
							<img class="form_corner left" src="../repository_images/checkout/sub-tl.png" />
							<div class="form_sub_cap_spacer" ></div>
							<img class="form_corner right" src="../repository_images/checkout/sub-tr.png" />
						</div>
						<div class="form_sub_frame" >
							<div class="form_steps_frame" > 
								<div id="' . $index . '-step1" class="form_step_frame left" >
									<div class="form_step_title" >Step 1: Select Room</div>
									<div class="form_select_cap" >
										<img class="form_select_corner left" src="../repository_images/checkout/sb-tl.png" />
										<div class="form_select_spacer form_select_spacer_top" ></div>
										<img class="form_select_corner right" src="../repository_images/checkout/sb-tr.png" />
									</div>
									<div class="form_select" >
	';
	
	$query = "SELECT DISTINCT room_name FROM vs_designsets";
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error());
	$count = 0;
	while($result = mysql_fetch_array($r)){
		echo '
										<div id="' . $index . '-step1-' . $count . '" class="form_select_line form_select_line_deselected" onclick="SelectRoom(' . $index . ', ' . Chr(39) . '' . $index . '-step1-' . $count . Chr(39) . ');" >' . $result['room_name'] . '</div>
		';
		$count++;
	}
	
	echo '
										<input id="' . $index . '-rooms" type="hidden" value="' . $count . '" />
									</div>
									<div class="form_select_cap" >
										<img class="form_select_corner left" src="../repository_images/checkout/sb-bl.png" />
										<div class="form_select_spacer form_select_spacer_bottom" ></div>
										<img class="form_select_corner right" src="../repository_images/checkout/sb-br.png" />
									</div>
								</div>
								<div id="' . $index . '-step2" class="form_step_frame left form_step_middle disabled" >
									<div class="form_step_title" >Step 2: Select Design Style</div>
									<div class="form_select_cap" >
										<img class="form_select_corner left" src="../repository_images/checkout/sb-tl.png" />
										<div class="form_select_spacer form_select_spacer_top" ></div>
										<img class="form_select_corner right" src="../repository_images/checkout/sb-tr.png" />
									</div>
									<div id="' . $index . '-step2-select" class="form_select" ></div>
									<div class="form_select_cap" >
										<img class="form_select_corner left" src="../repository_images/checkout/sb-bl.png" />
										<div class="form_select_spacer form_select_spacer_bottom" ></div>
										<img class="form_select_corner right" src="../repository_images/checkout/sb-br.png" />
									</div>
								</div>
								<div id="' . $index . '-design" class="form_step_frame right disabled" >
									<div class="form_step_title" >Selected Design Set</div>
									<div class="form_select_cap" >
										<img class="form_select_corner left" src="../repository_images/checkout/sb-tl.png" />
										<div class="form_select_spacer form_select_dotted_spacer_top" ></div>
										<img class="form_select_corner right" src="../repository_images/checkout/sb-tr.png" />
									</div>
									<div class="form_select form_select_dotted" >
										<img id="' . $index . '-selected" class="form_selected_design" src="../repository_images/pixel.png" />
									</div>
									<div class="form_select_cap" >
										<img class="form_select_corner left" src="../repository_images/checkout/sb-bl.png" />
										<div class="form_select_spacer form_select_dotted_spacer_bottom" ></div>
										<img class="form_select_corner right" src="../repository_images/checkout/sb-br.png" />
									</div>
								</div>
							</div>
						</div>
						<div id="' . $index . '-slider" class="form_sub_frame hidden" >
							<img class="form_slider_arrow" src="../repository_images/checkout/arrow.png" />
							<div class="form_step_title" style="margin-left: 40px; width: 200px" >Step 3: Select Set</div>
							<div class="form_slider_cap" >
								<img class="form_corner left" src="../repository_images/checkout/white-tl.png" />
								<div class="form_slider_cap_spacer" ></div>
								<img class="form_corner right" src="../repository_images/checkout/white-tr.png" />
							</div>
							<div id="' . $index . '-slider_frame" class="form_slider_frame" >
								<!--- ADD A SLIDER WITH AJAX --->
							</div>
							<div class="form_slider_cap" >
								<img class="form_corner left" src="../repository_images/checkout/white-bl.png" />
								<div class="form_slider_cap_spacer" >
									<div id="' . $index . '-slider_bar" class="form_slider_bar" onmouseup="MouseUp(event);" onmousedown="MouseDown(' . $index . ', event);" ></div>
								</div>
								<img class="form_corner right" src="../repository_images/checkout/white-br.png" />
							</div>
							<div id="' . $index . '-info" class="form_sub_line_no_height form_sub_line_fancy" ></div>
						</div>
						<div class="form_sub_frame" >
							<div class="form_sub_line" ></div>
							<div class="form_sub_line" >
								<input name="' . $index . '-pricing" type="radio" value="' . $single . '" checked onclick="SelectPackage(' . $index . ', ' . $single . ')" /> Order as a Single Photo Order ($' . number_format($single, 2, '.', '') . ')
							</div>
							<div class="form_sub_line" style="height: 35px;" >
								<input name="' . $index . '-pricing" type="radio" value="' . $multi . '" onclick="SelectPackage(' . $index . ', ' . $multi . ')" /> Order as a Motion Scene (Up to 4 photos staged $' . number_format($multi, 2, '.', '') . ')
								
								<div class="optionbutton right" style="margin-top: 0px;" onclick="RemoveForm(' . chr(39) . $index . chr(39) . ');" >
									<div class="btncap btncapl" ></div>
									<div class="btnicon btnbody" >X</div>
									<div class="btntxt btnbody" >Remove</div>
									<div class="btncap btncapr" ></div>
								</div>
								<div class="optionbutton right" style="margin-top: 0px; margin-right: 10px;" onclick="CheckForm(' . $index . ');" >
									<div class="btncap btncapl" ></div>
									<div class="btnicon btnbody" >
										<img class="iconimage" src="../repository_images/apply.png" />
									</div>
									<div class="btntxt btnbody" >Done</div>
									<div class="btncap btncapr" ></div>
								</div>
							</div>
						</div>
						<div class="form_sub_cap" >
							<img class="form_corner left" src="../repository_images/checkout/sub-bl.png" />
							<div class="form_sub_cap_spacer" ></div>
							<img class="form_corner right" src="../repository_images/checkout/sub-br.png" />
						</div>
						<div class="form_line" ></div>
					</div>
	';
					
?>