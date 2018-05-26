<?php

// Log data
require_once ('../repository_inc/write_log.php');
include ('../repository_inc/data.php');

header("Content-type: text/xml");

$state = '';
if(isset($_POST['state'])) {
	$state = $_POST['state'];
} elseif (isset($_GET['state'])) {
	$state = $_GET['state'];
}

$city = '';
if(isset($_POST['city'])) {
	$city = $_POST['city'];
} elseif (isset($_GET['city'])) {
	$city = $_GET['city'];
}

$zip = '';
if(isset($_POST['zip'])) {
	$zip = $_POST['zip'];
} elseif (isset($_GET['zip'])) {
	$zip = $_GET['zip'];
}

//create the xml document
$xmlDoc = new DOMDocument();

//create the root element
$root = $xmlDoc->appendChild($xmlDoc->createElement("locations"));

// Create a MySQL PDO
$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = 'SELECT city, state_prefix, zip_code FROM nf_locations WHERE city = :city AND state_prefix = :state AND zip_code = :zip';
if($stmt = $dbh->prepare($query)) {
	$stmt->bindParam(':city', $city);
	$stmt->bindParam(':state', $state);
	$stmt->bindParam(':zip', $zip);
	try {
		$stmt->execute();
	} catch (PDOException $e){
		WriteLog("checkout_xml_location_validation", $e->getMessage());
		$root->appendChild($xmlDoc->createElement("error"));
	}
	
	if($result = $stmt->fetch()) {
		$root->appendChild($xmlDoc->createElement("valid"));
		$loc = $root->appendChild($xmlDoc->createElement("location"));
		$loc->appendChild(
			$xmlDoc->createElement("state", $result['state_prefix']));
		$loc->appendChild(
			$xmlDoc->createElement("city", $result['city']));
		$loc->appendChild(
			$xmlDoc->createElement("zip", $result['zip_code']));
	} else {
		$query = '
			SELECT city, state_prefix, zip_code FROM nf_locations
			WHERE 
			city = :city AND state_prefix = :state
			OR
			city = :city AND zip_code = :zip
			OR
			state_prefix = :state AND zip_code = :zip
			OR
			zip_code = :zip
		';
		if($stmt = $dbh->prepare($query)) {
			$stmt->bindParam(':city', $city);
			$stmt->bindParam(':state', $state);
			$stmt->bindParam(':zip', $zip);
			try {
				$stmt->execute();
			} catch (PDOException $e){
				WriteLog("checkout_xml_location_validation", $e->getMessage());
				$root->appendChild($xmlDoc->createElement("error"));
			}
			$root->appendChild($xmlDoc->createElement("invalid"));
			while ($result = $stmt->fetch()) {
				$loc = $root->appendChild($xmlDoc->createElement("location"));
				$loc->appendChild(
					$xmlDoc->createElement("state", $result['state_prefix']));
				$loc->appendChild(
					$xmlDoc->createElement("city", $result['city']));
				$loc->appendChild(
					$xmlDoc->createElement("zip", $result['zip_code']));
			}
		}
	}
	
}

// Make the output pretty
$xmlDoc->formatOutput = true;

// Output!
echo $xmlDoc->saveXML();



?>