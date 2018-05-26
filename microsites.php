<?php
require_once(dirname(__FILE__) . '/repository_inc/classes/inc.global.php');
$sub = new subdomains;
if( $sub->processSubDomain($_SERVER['HTTP_HOST']) ){
	
	header("Location: http://www.spotlighthometours.com/microsites/index-new.php?section=1&tourID=" . $sub->tourId);
}
die;
/*
	$micro = new microsites;
	$micro->loadSite($_GET['tourId'],true);	
	die;
}
$sub = new subdomains;
if( $sub->processSubDomain($_SERVER['HTTP_HOST']) ){
	$sub->loadMicroSite();
}else{
	echo "<h1>That page doesn't exist :/</h1>";
}

*/
