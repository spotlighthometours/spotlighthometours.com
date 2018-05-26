<?php
require_once ('../transactional/transactional_pricing.php');
require_once ('../repository_inc/clean_query.php');

session_start();

if(isset($_POST['usePaySold'])) {
	$paySold = intval($_POST['usePaySold']);
} elseif (isset($_GET['usePaySold'])) {
	$paySold = intval($_GET['usePaySold']);
} else 
	$paySold = 0;

$tourtype = '-1';
if(isset($_POST['tourtype'])) {
	$tourtype = intval($_POST['tourtype']);
} elseif (isset($_GET['tourtype'])) {
	$tourtype = intval($_GET['tourtype']);
}

$products = '-1';
if(isset($_POST['products'])) {
	$products = $_POST['products'];
} elseif (isset($_GET['products'])) {
	$products = $_GET['products'];
}

$city = '';
if(isset($_POST['city'])) {
	$city = CleanQuery($_POST['city']);
} elseif (isset($_GET['city'])) {
	$city = CleanQuery($_GET['city']);
}

$zip = '';
if(isset($_POST['zip'])) {
	$zip = CleanQuery($_POST['zip']);
} elseif (isset($_GET['zip'])) {
	$zip = CleanQuery($_GET['zip']);
}

$coupon = '';
if(isset($_POST['coupon'])) {
	$coupon = CleanQuery($_POST['coupon']);
} elseif (isset($_GET['coupon'])) {
	$coupon = CleanQuery($_GET['coupon']);
}

$additional_product = false;
if(isset($_POST['additional_product'])) {
	$additional_product = ($_POST['additional_product']=="true")?true:false;
} elseif (isset($_GET['additional_product'])) {
	$additional_product = ($_GET['additional_product']=="true")?true:false;
}

$sqft = '';
if(isset($_POST['sqft'])) {
	$sqft = CleanQuery($_POST['sqft']);
} elseif (isset($_GET['sqft'])) {
	$sqft = CleanQuery($_GET['sqft']);
}

$listprice = '';
if(isset($_POST['price'])) {
	$listprice = CleanQuery($_POST['price']);
} elseif (isset($_GET['price'])) {
	$listprice = CleanQuery($_GET['price']);
}

$userid = $_SESSION['user_id'];
$brokerid = $_SESSION['broker_id'];

$tourtypes[0]['id'] = $tourtype;
$tourtypes[0]['qty'] = 1;

$items = array();
$products = explode(";",$products);
foreach ($products as $prod) {
	$item = explode(",",$prod);
	$index = sizeof($items);
	$items[$index]['id'] = intval($item[0]);
	$items[$index]['qty'] = intval($item[1]);
}

$return = order($tourtypes, $items, $city, $zip, $brokerid, $userid, $coupon, $paySold, $listprice, $sqft);

$total = floatval($return['totals']['f_mb_total'] + $return['totals']['f_ub_total']);

header("Content-type: text/xml");

//create the xml document
$xmlDoc = new DOMDocument();

//create the root element
$xmlDoc->appendChild($xmlDoc->createElement("total", $total));

// Make the output pretty
$xmlDoc->formatOutput = true;


// Output!
echo $xmlDoc->saveXML();

?>