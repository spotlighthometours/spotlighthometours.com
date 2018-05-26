<?php
/*
 * Admin/Photographer/assign-tour-affiliate.php
 * this page is for assigning a tour to an affiliate
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

if(isset($_REQUEST['tourID'])&&!empty($_REQUEST['tourID'])){
	$tourID = $_REQUEST['tourID'];
}else{
	echo '<h1>tourID is required please pass this parameter to this page!</h1>';
	exit();
}

// Create instances of needed objects
$users = new users($db);
$tours = new tours();
$affiliates = new affiliates();

// Require admin
$users->authenticateAdmin();
$tours->loadTour($tourID);
if(isset($_REQUEST['action'])&&$_REQUEST['action']=="setAffiliate"){
	$tours->assignTourToAffiliate($_REQUEST['photographerID']);
	$tours->tourAddedNotifyAffiliate($_REQUEST['photographerID'], $tourID);
}
$currentAffiliate = $tours->getAffiliate();
$affiliateList = $affiliates->getPhotographers();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Assign Affiliate to Tour</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
	body{
		width:760px;
		margin:10px;
	}
</style>
</head>
<body>
<?PHP
	if(isset($_REQUEST['action'])&&$_REQUEST['action']=="setAffiliate"){
?>
<div class="alert">This tour has been assigned to affiliate: <?PHP echo $currentAffiliate['fullName']; ?></div>
<?PHP
	}
?>
<h1>Assign an affiliate to this tour: <?PHP echo $tours->address.', '.$tours->city.', '.$tours->state.' '.$tours->zipCode; ?></h1>
<p>Please select which affiliate your would like assigned to this tour below:</p>
<form action="<?PHP echo $_SERVER['PHP_SELF'] ?>" method="GET">
	<input type="hidden" name="tourID" value="<?PHP echo $tourID; ?>" />
    <input type="hidden" name="action" value="setAffiliate" />
	<select name="photographerID">
		<option value="0">Please select an affiliate</option>
<?PHP
	foreach($affiliateList as $row => $column){
?>
		<option value="<?PHP echo $column['photographerID'] ?>" <?PHP echo ($currentAffiliate['photographerID']==$column['photographerID'])?'selected':''; ?>><?PHP echo $column['fullName'] ?></option>
<?PHP
	}
?>
    </select>
    <input type='submit' name='select' value='select' />
</form>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>