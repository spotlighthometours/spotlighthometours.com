<?php
/*
DemoLinkLink
tourTypeID
iconImage
tourTypeName
description
tagline


*/


// Log data
require_once ('../repository_inc/write_log.php');

header("Content-type: text/xml");

//create the xml document
$xmlDoc = new DOMDocument();

//create the root element
$root = $xmlDoc->appendChild($xmlDoc->createElement("agents"));

// Create a MySQL PDO
include ('../repository_inc/data.php');
$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = 'SELECT CONCAT(firstName, " ", lastName) AS name FROM users WHERE userType = "agent"';
if($stmt = $dbh->prepare($query)) {
	try {
		$stmt->execute();
	} catch (PDOException $e){
		WriteLog("checkout_xml_agents", $e->getMessage());
	}
	while($result = $stmt->fetch()) {
		$root->appendChild(
			$xmlDoc->createElement("agent", $result['name']));
	}
}

// Make the output pretty
$xmlDoc->formatOutput = true;

// Output!
echo $xmlDoc->saveXML();



?>