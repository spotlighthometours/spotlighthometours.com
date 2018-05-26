<?php

// Log data
require_once ('../repository_inc/write_log.php');

$name = '';
if(isset($_POST['name'])) {
	$name = $_POST['name'];
} elseif (isset($_GET['name'])) {
	$name = $_GET['name'];
}

header("Content-type: text/xml");

//create the xml document
$xmlDoc = new DOMDocument();

//create the root element
$root = $xmlDoc->appendChild($xmlDoc->createElement("agentid"));

// Create a MySQL PDO
include ('../repository_inc/data.php');
$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = 'SELECT userID FROM users WHERE CONCAT(firstName, " ", lastName) = :name AND userType = "agent"';
if($stmt = $dbh->prepare($query)) {
	$stmt->bindParam(':name', $name);
	try {
		$stmt->execute();
	} catch (PDOException $e){
		WriteLog("checkout_xml_agent_id", $e->getMessage());
	}
	while($result = $stmt->fetch()) {
		$root->appendChild(
			$xmlDoc->createElement("id", intval($result['userID'])));
	}
}

// Make the output pretty
$xmlDoc->formatOutput = true;

// Output!
echo $xmlDoc->saveXML();



?>