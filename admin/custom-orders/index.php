<?php
/*
 * Admin: Custom Orders
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed objects
$customorders = new customorders();

// Require admin
//$users->authenticateAdmin();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Custom Orders</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
@import "../../repository_css/admin-v2.css";
.ajaxMessage.processing {
	background-image:url('../../repository_images/loader.gif');
	background-position:15px 9px;
	background-repeat:no-repeat;
	background-color:#333;
}
</style>
</head>
<body>
	<div id="ajaxMessage"></div>
	<h1>Custom Orders</h1>
	<table border="0" cellspacing="0" cellpadding="10" class="list">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>User</th>
          <th>Items</th>
          <th>Paid</th>
        </tr>
      </thead>
      <tbody id="newTourIDs">
      	
      </tbody>
    </table>
    <script>
        loadListEffects();
    </script>
</body>
</html>