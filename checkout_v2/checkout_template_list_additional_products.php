<?php
session_start();

// Reset the product credit tracking (we do not track credits for tour type since there can only be one it's not needed).
unset($_SESSION['usedCredits']);

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');

require_once ('../transactional/transactional_pricing.php');
require_once ('../repository_inc/clean_query.php');

// Create instances of needed objects
$media = new media();

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

$tourtype = '';
if(isset($_POST['tourtype'])) {
	$tourtype = CleanQuery($_POST['tourtype']);
} elseif (isset($_GET['tourtype'])) {
	$tourtype = CleanQuery($_GET['tourtype']);
}

$userid = $_SESSION['user_id'];
$brokerid = $_SESSION['broker_id'];
$coupon = '';

// Create a MySQL PDO
include ('../repository_inc/data.php');
$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = '
	SELECT p.productId AS id, 1 AS qty, p.productName AS title, p.tagline, p.onePerOrder, productIcon AS icon 
	FROM tour_products tp
	LEFT JOIN products p ON  tp.productID = p.productID
	WHERE p.productName IS NOT NULL
	AND p.visible = 1 
	AND p.parentProduct IS NULL
	AND tp.tourTypeID = :tourtype
	ORDER BY p.sort ASC';

if($stmt = $dbh->prepare($query)) {
	$stmt->bindParam(':tourtype', $tourtype);
	try {
		$stmt->execute();
	} catch (PDOException $e){
		WriteLog("checkout_template_list_additional_products", $e->getMessage());
	}
	if($additional_products = $stmt->fetchAll()) {

		$return = order(null, $additional_products, $city, $zip, $brokerid, $userid, $coupon, 0 );
		
		$new_list = array();
		for ($i = 0; $i < sizeof($additional_products); $i++) {
			for ($j = 0; $j < sizeof($return['lines']); $j++) {
				if(intval($additional_products[$i]['id']) == intval($return['lines'][$j]['itemID'])) {
					$additional_products[$i]['price'] = $return['lines'][$j]['ub_item'];
					
					// Only set if package pricing exist
					if(isset($return['lines'][$j]['ub_item_retail'])){
						$additional_products[$i]['retail_price'] = $return['lines'][$j]['ub_item_retail'];
					}
					
					$requiredFields = $pricing->getAdditionalRequired('product', intval($additional_products[$i]['id']));
					if(count($requiredFields)>0){
						$additional_products[$i]['required_fields'] = $requiredFields;
					}
					
					$new_list[] = $additional_products[$i];
				}
			}
		}
		$additional_products = $new_list;
		unset($new_list);
		
		$counter = 0;
		
		foreach($additional_products as $product) {
			if(($counter % 2) == 0) {
				$style = "col_left";
				echo '
					<div class="add_prod_line" >
				';
			} else {
				$style = "col_right";
			}
			
			include('checkout_template_additional_product.php');
			
			if(($counter % 2) == 1) {
				echo '
					</div>	
				';
			}
			
			$counter ++;
		}
		
		if(($counter % 2) == 0) {
			echo '
				</div>	
			';
		}
		
	}
}

?>