<?php
session_start();

require_once ('../transactional/transactional_pricing.php');
require_once ('../repository_inc/clean_query.php');

$additional_product = false;
if(isset($_POST['additional_product'])) {
	$additional_product = ($_POST['additional_product']=="true")?true:false;
} elseif (isset($_GET['additional_product'])) {
	$additional_product = ($_GET['additional_product']=="true")?true:false;
}

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

$paymentPlanID = 0;
if(isset($_POST['paymentPlanID'])) {
	$paymentPlanID = CleanQuery($_POST['paymentPlanID']);
} elseif (isset($_GET['paymentPlanID'])) {
	$paymentPlanID = CleanQuery($_GET['paymentPlanID']);
}

$userid = $_SESSION['user_id'];
$brokerid = $_SESSION['broker_id'];
	
$tourtypes[0]['id'] = $tourtype;
$tourtypes[0]['qty'] = 1;

$items = array();
$products = explode(";",$products);
foreach ($products as $prod) {
	if(!empty($prod)){
		$item = explode(",",$prod);
		$index = sizeof($items);
		$items[$index]['id'] = intval($item[0]);
		$items[$index]['qty'] = intval($item[1]);
	}
}
$return = order( $tourtypes, $items, $city, $zip, $brokerid, $userid, $coupon, $paySold, $listprice, $sqft );
// echo 'tour types' . var_dump($tourtypes);
// echo 'items' . var_dump($items);
// echo $city .'<br />';
// echo $zip .'<br />';
// echo $brokerid .'<br />';
// echo $userid .'<br />';
// echo $coupon .'<br />';
echo table($return, $userid, $paySold);
//echo socialHubTable($return);
?>