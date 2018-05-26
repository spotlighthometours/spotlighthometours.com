<?php
/*
 * Admin: Reports (Index)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
//showErrors();
clearCache();

// Create instances of needed objects
$users = new users($db);

// Require admin
$users->authenticateAdmin();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reports | Index</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
@import "../../repository_css/admin-v2.css";
.processing {
	background-image:url('../../repository_images/loader.gif');
	background-position:15px 9px;
	background-repeat:no-repeat;
	background-color:#333;
}
</style>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Reports</h1>
<table border="0" cellspacing="0" cellpadding="10" class="list">
  <thead>
    <tr>
      <th>Report</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Affiliate Sales</td>
      <td class="list-button" style="width:86px;"><a href="regional-sales.php" onclick="ajaxMessage('Loading Report', 'processing');">View Report</a></td>
    </tr>
    <tr>
      <td>Tours by Type / Addons</td>
      <td class="list-button" style="width:86px;"><a href="http://www.spotlighthometours.com/admin/tourtypes/get-tours-by-type.php" onclick="ajaxMessage('Loading Report', 'processing');">View Report</a></td>
    </tr>
  </tbody>
</table>
<script>
	loadListEffects()
</script>
</body>
</html>