<?php
session_start();

unset($_SESSION['tourTypeCredits']);

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');

require_once ('../transactional/transactional_pricing.php');
require_once ('../repository_inc/clean_query.php');
require_once ('../repository_inc/write_log.php');

// Create instances of needed objects
$pricing = new pricing();

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

$userid = $_SESSION['user_id'];
$brokerid = $_SESSION['broker_id'];
$coupon = '';

// Create a MySQL PDO
include ('../repository_inc/data.php');
$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = '
	SELECT tt.tourTypeID AS id, 1 as qty, tt.tourCategory AS category, tt.tourTypeName AS title, tt.tagline, tt.description, tt.upgradeId, tt.upgradeDoc, tt.iconImage AS icon, tt.DemoLinkLink AS demo
	FROM tourtypes tt
	LEFT JOIN tour_category tc ON tt.tourCategory = tc.category_name WHERE hidden = 0
';
if (!$_SESSION['DIYActive']) {
	$query .= ' AND tt.expressOnly != 1 ';	
}
$query .= ' ORDER BY tc.category_order, tt.tour_order';

if($stmt = $dbh->prepare($query)) {
	try {
		$stmt->execute();
	} catch (PDOException $e){
		WriteLog("checkout_template_list_tour_packages", $e->getMessage());
	}
	
	if($tourtypesR = $stmt->fetchAll()) {
		$tourtypes = $tourtypesR;
		//var_dump($tourtypes);
		unset($return);
		//print_r($tourtypes);
		$return = order( $tourtypes, $items, $city, $zip, $brokerid, $userid, $coupon, 0 );
		//var_dump($return);
		
		$new_list = array();
		for ($i = 0; $i < sizeof($tourtypes); $i++) {
			for ($j = 0; $j < sizeof($return['lines']); $j++) {
				if(intval($tourtypes[$i]['id']) == intval($return['lines'][$j]['itemID'])) {
					$tourtypes[$i]['price'] = ($return['lines'][$j]['ub_item'] + $return['lines'][$j]['mb_item']);
					
					// Only set if package pricing exist
					if(isset($return['lines'][$j]['ub_item_retail'])&&isset($return['lines'][$j]['mb_item_retail'])){
						$tourtypes[$i]['retail_price'] = ($return['lines'][$j]['ub_item_retail'] + $return['lines'][$j]['mb_item_retail']);
					}elseif(isset($return['lines'][$j]['ub_item_retail'])){
						$tourtypes[$i]['retail_price'] = ($return['lines'][$j]['ub_item_retail'] + $return['lines'][$j]['mb_item']);
					}elseif(isset($return['lines'][$j]['mb_item_retail'])){
						$tourtypes[$i]['retail_price'] = ($return['lines'][$j]['ub_item'] + $return['lines'][$j]['mb_item_retail']);
					}
					
					$requiredFields = $pricing->getAdditionalRequired('tour', intval($tourtypes[$i]['id']));
					if(count($requiredFields)>0){
						$tourtypes[$i]['required_fields'] = $requiredFields;
					}
					
					$new_list[] = $tourtypes[$i];
				}
			}
		}
		$tourtypes = $new_list;
		unset($new_list);
		
		// Move DIY cat to top if express user
		/*if($_SESSION['DIYActive']){
			$diyIndexes = array();
			foreach($tourtypes as $row => $array){
				if(in_array("Do It Yourself Tours", $array)){
					$diyIndexes[] = $row;
				}
			}
			$diyCat = array();
			foreach($diyIndexes as $row => $index){
				$diyCat[] = $tourtypes[$index];
				unset($tourtypes[$index]);
			}
			$tourtypes = $diyCat + $tourtypes;
		}else{*/
			$tourtypes = $tourtypes;
		//}
		
		//print_r($tourtypes);
		$category = "";
		foreach($tourtypes as $package) {
			if($category != $package['category']) {
				$category = $package['category'];
				echo '
					<div class="package_section" >
						<div class="cap left" ></div>
						<div class="body" >' . $category . '</div>
						<div class="cap right"></div>
					</div>
				';
			}
			
			include('checkout_template_tour_package.php');
		}
		
	}
}

//$return = order( $tourtypes, $items, $city, $zip, $brokerid, $userid, $coupon );

//for ($i = 0, $i < sizeof($tourtypes)
?>