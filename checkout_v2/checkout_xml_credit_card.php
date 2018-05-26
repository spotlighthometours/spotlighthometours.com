<?php

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');

// Create user object
$users = new users($db);

if($users->authenticate(false,false)&&!$_SESSION['quick_login']){
	// Log data
	require_once ('../repository_inc/write_log.php');
	require_once ('../repository_inc/classes/class.security.php');
	
	$security = new security();
	
	$cardid = '';
	if(isset($_POST['cardid'])) {
		$cardid = intval($_POST['cardid']);
	} elseif (isset($_GET['cardid'])) {
		$cardid = intval($_GET['cardid']);
	}
	
	header("Content-type: text/xml");
	
	//create the xml document
	$xmlDoc = new DOMDocument();
	
	//create the root element
	$root = $xmlDoc->appendChild($xmlDoc->createElement("card_info"));
	
	$add = '';
	
	if(!$users->authenticateAdmin(false)){
		$add = "AND userid = :userid";
	}
	
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = 'SELECT cardName, cardAddress, cardCity, cardState, cardZip, cardType, cardNumber, cardMonth, cardYear FROM usercreditcards where crardId = :cardid '.$add.' LIMIT 1';
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam(':cardid', $cardid);
		if(!$users->authenticateAdmin(false)){
			$stmt->bindParam(':userid', $users->userID);
		}
		try {
			$stmt->execute();
		} catch (PDOException $e){
			WriteLog("checkout_xml_credit_card", $e->getMessage());
		}
		$result = $stmt->fetch();
			$root->appendChild(
				$xmlDoc->createElement("name", $result['cardName']));
			$root->appendChild(
				$xmlDoc->createElement("address", $result['cardAddress']));
			$root->appendChild(
				$xmlDoc->createElement("city", $result['cardCity']));
			$root->appendChild(
				$xmlDoc->createElement("state", $result['cardState']));
			$root->appendChild(
				$xmlDoc->createElement("zip", $result['cardZip']));
			$root->appendChild(
				$xmlDoc->createElement("type", $result['cardType']));
			$root->appendChild(
				$xmlDoc->createElement("number", $security->decrypt($result['cardNumber'])));
			$root->appendChild(
				$xmlDoc->createElement("month", $result['cardMonth']));
			$root->appendChild(
				$xmlDoc->createElement("year", $result['cardYear']));
	}
	
	// Make the output pretty
	$xmlDoc->formatOutput = true;
	
	// Output!
	echo $xmlDoc->saveXML();

}else{
	header('HTTP/1.1 403 Forbidden');
}

?>