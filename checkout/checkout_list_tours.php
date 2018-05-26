<?php
/**********************************************************************************************
Document: checkout_list_tours.php
Creator: Brandon Freeman
Date: 02-05-11
Purpose: Returns the form for selecting tours. (for Ajax request).
Accepted Gets: brokerid, zip, city, debug
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

ini_set ('display_errors', 1);
error_reporting (E_ALL & ~E_NOTICE);
ob_start();

// Start the session
session_start();

//=======================================================================
// Includes
//=======================================================================

// Connect to MySQL
require_once ('../repository_inc/connect.php');
require_once ('../repository_inc/clean_query.php');
require_once ('checkout_orderpricing.php');

//=======================================================================
// Document
//=======================================================================

// Set broker id.
if (isset($_GET['brokerid'])) {
	$brokerid = CleanQuery($_GET['brokerid']);
} else {
	$brokerid = -1;
}

// Set city.
if (isset($_GET['city'])) {
	$city = CleanQuery($_GET['city']);
} else {
	$city = -1;
}

// Set zip.
if (isset($_GET['zip'])) {
	$zip = CleanQuery($_GET['zip']);
} else {
	$zip = -1;
}

$query = '
	SELECT tourTypeID as id, tourTypeName as name, tagline, iconImage, description, DemoLinkLink, tourCategory as category
	FROM tourtypes
	LEFT JOIN tour_category ON tourCategory = category_name
	WHERE hidden = 0';

// Non express users should not see the express only tours.
if (!$_SESSION['express_user']) {
	$query .= ' AND expressOnly != 1';	
}

$query .= '	
	ORDER BY category_order, tourCategory, tour_order';
	
$t = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query being run: " . $query);

// Get the entire result set into an array.
$tours = array();
while($tour = mysql_fetch_array($t)){
	array_push($tours, $tour);
}

$tourPricing = pricing( $tours, null, $city, $zip, $brokerid );

//Place the pricing information in an array indexed by tour type id.
$prices = array();
foreach ($tourPricing as $tPricing) {
		$prices[$tPricing['id']] = $tPricing['price'];
}

//print_r($tours);
//print_r($tourPricing);

// Variables for the list of tour type ids
$first = true;
$list = "";

$category = "none yet";
foreach ($tours as $tour) {
	if ($prices[$tour['id']] >= 0) {
		
		// Put the title up if we have a new category.
		if($tour['category'] != $category) {
			$category = $tour['category'];
			echo '
			<div class="tourcategorybar" >
				<div class="tourcategorycap tourcategorycapleft left" ></div>
				<div class="tourcategorycenter left" >' . $tour['category'] . '</div>
				<div class="tourcategorycap tourcategorycapright right" ></div>
			</div>';
		}
		
		// Put the information in variables that the template uses.
		// This could be cleaned up a bit.
		// The template uses column names form the database.
		// It would involve changing the admin as well.
		$tour['tourTypeID'] = $tour['id'];
		$tour['tourTypeName'] = $tour['name'];
		$price = $prices[$tour['id']];
		
		// Include the tours template
		include('checkout_tour_template.php');
		
		// Add this tour type id to the list of ids
		if (!$first) {
			$list .= ",";
		} else {
			$first = !$first;
		}
		$list .= $tour['tourTypeID'];
	}
}
// This is a hidden div that gives the list of tour IDs to the calling app.
// It's a "while we're at it" return.
echo '<input id="tourlist" type="hidden" name="tourlist' . $tour['tourTypeID'] . 'price" value="' . $list . '">';

?>











