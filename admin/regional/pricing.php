<?php
/*
 * Admin: Regional Pricing
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
//showErrors();

clearCache();

// Create instances of needed objects
$users = new users($db);
$floorplans = new floorplans();
$tours = new tours();

// Require admin
$users->authenticateAdmin();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Regional Pricing</title>
<script src="../../repository_inc/jquery-1.8.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-regional-pricing.js" type="text/javascript"></script><!-- Regional Pricing JS file -->
<!-- HighMaps -->
<script src="../../repository_inc/highmaps/js/highmaps.js"></script>
<script src="../../repository_inc/highmaps/js/modules/data.js"></script>
<script src="../../repository_inc/highmaps/js/modules/drilldown.js"></script>
<script src="http://code.highcharts.com/mapdata/custom/world-eckert3-highres.js"></script>
<!-- Styles -->
<link href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Regional Pricing</h1>
<div id="regionalPricingMsg"></div>
<div id="container" style="height: 500px; min-width: 310px; max-width: 800px; margin: 0 auto"></div>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>