<?php
error_reporting(-1);
ini_set('display_errors','1');
require 'repository_inc/classes/inc.global.php';

$users = [	20846, 20060, 20834, 16870, 19874, 19385, 19791, 17395, 21765 ];

global $db;
foreach($users as $index => $id){
	$db->insert("usernotifications",array('userID' => $id,'BrokerageID'=>0,'action'=> 'finalized', 'email' => 'kevin.flowers@coloradohomes.com','disabled' => '0'));
}
