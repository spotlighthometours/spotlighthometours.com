<?php

// Log data
require_once ('../repository_inc/write_log.php');

$id = '';
if(isset($_POST['id'])) {
	$id = $_POST['id'];
} elseif (isset($_GET['id'])) {
	$id = $_GET['id'];
}

// Create a MySQL PDO
include ('../repository_inc/data.php');
$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = 'SELECT description, DemoLinkLink FROM products WHERE productId = :id LIMIT 1';
if($stmt = $dbh->prepare($query)) {
	$stmt->bindParam(':id', $id);
	try {
		$stmt->execute();
	} catch (PDOException $e){
		WriteLog("checkout_xml_product_description", $e->getMessage());
	}
	$result = $stmt->fetch();
	echo $result['description'];
	if(!empty($result['DemoLinkLink'])){
		echo '<br/><br/>
		<div class="button_new button_blue button_sm close" onclick="getDemo(\''.$result['DemoLinkLink'].'\');">
			<div class="curve curve_left"></div>
			<span class="button_caption">View a Demo</span>
			<div class="curve curve_right"></div>
		</div>';
	}
}

?>