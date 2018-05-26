<?php
require 'repository_inc/classes/inc.global.php';

$yt = new youtube;
$brokerIds = [987];

$br = $yt->getAccounts($brokerageID=987,"broker");
var_dump($br);
?>
