<?php

$rets_login_url = "http://rets18.utahrealestate.com/contact/rets/login";
$rets_username = "spotlightidx";
$rets_password = "bg2225";

//////////////////////////////

require_once("phrets.php");

// start rets connection
$rets = new phRETS;

// Uncomment and change the following if you're connecting
// to a server that supports a version other than RETS 1.5

//$rets->AddHeader("RETS-Version", "RETS/1.7.2");

echo "+ Connecting to {$rets_login_url} as {$rets_username}<br>\n";
$connect = $rets->Connect($rets_login_url, $rets_username, $rets_password);

// check for errors
if ($connect) {
        echo "  + Connected<br>\n";
}
else {
        echo "  + Not connected:<br>\n";
        print_r($rets->Error());
        exit;
}

$types = $rets->GetMetadataTypes();

// check for errors
if (!$types) {
        print_r($rets->Error());
}
else {
        foreach ($types as $type) {
                echo "+ Resource {$type['Resource']}<br>\n";

                foreach ($type['Data'] as $class) {
                        echo "  + Class {$class['ClassName']}<br>\n";
                }
        }
}

$photos = $rets->GetObject("Property", "Photo", "1146500", "*", 1);
foreach ($photos as $photo) {
	$listing = $photo['Content-ID'];
	$number = $photo['Object-ID'];

	if ($photo['Success'] == true) {
		echo "{$listing}'s #{$number} photo is at {$photo['Location']}\n";
	}
	else {
		echo "({$listing}-{$number}): {$photo['ReplyCode']} = {$photo['ReplyText']}\n";
	}
}
exit;

//$fields = $rets->FirewallTest();
//echo $fields = $rets->GetMetadataClasses(1418882);
$resources = $rets->GetMetadataResources();
foreach ($resources as $resource) {
	echo "+ Resource {$resource['ResourceID']}\n";
	$classes = $rets->GetMetadataClasses($resource['ResourceID']);
	foreach ($classes as $class) {
		echo "   + Class {$class['ClassName']} described as " . $class['Description'] . "\n";
	}
}
//var_dump($fields);
 $rets_resource_info = $rets->GetMetadataInfo();
print_r($rets_resource_info);

echo "<br />";

$resources = $rets->GetMetadataResources();
foreach ($resources as $resource) {
	echo "+ Resource {$resource['ResourceID']}\n";
	$classes = $rets->GetMetadataClasses($resource['ResourceID']);
	foreach ($classes as $class) {
		echo "   + Class {$class['ClassName']} described as " . $class['Description'] . "\n";
	}
}

$resource = "Property";
$class = "RES";
// or set through a loop

// pull field format information for this class
echo $rets_metadata = $rets->GetMetadataTable($resource, $class);

echo "+ Disconnecting<br>\n";
$rets->Disconnect();

/*
date_default_timezone_set('America/New_York');

require_once("vendor/autoload.php");

$log = new \Monolog\Logger('PHRETS');
$log->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG));

$config = new \PHRETS\Configuration;
$config->setLoginUrl('http://rets18.utahrealestate.com/contact/rets/login')
->setUsername('spotlightidx')
->setPassword('bg2225')
->setRetsVersion('1.5');

$rets = new \PHRETS\Session($config);
$rets->setLogger($log);

$connect = $rets->Login();

$system = $rets->GetSystemMetadata();
var_dump($system);

$resources = $system->getResources();
$classes = $resources->first()->getClasses();
var_dump($classes);

$classes = $rets->GetClassesMetadata('Property');
var_dump($classes->first());

$objects = $rets->GetObject('Property', 'Photo', '00-1669', '*', 1);
var_dump($objects);

$fields = $rets->GetTableMetadata('Property', 'A');
var_dump($fields[0]);

$results = $rets->Search('Property', 'A', '*', ['Limit' => 3, 'Select' => 'LIST_1,LIST_105,LIST_15,LIST_22,LIST_87,LIST_133,LIST_134']);
foreach ($results as $r) {
	var_dump($r);
}*/
