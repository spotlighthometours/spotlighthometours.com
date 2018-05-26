<?php
/*
 * Admin: Packages (User Credits)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$credits = new credits();
$users = new users($db);

// Require admin
$users->authenticateAdmin();

// Load user
$users->loadUser($_REQUEST['userID']);

// Pull user package credits
$userPackageCredits = $credits->getUserCredits($_REQUEST['userID'], 'package');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Packages</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-packages.js" type="text/javascript"></script><!-- Admin Package JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<?PHP
print '<h1>'.$users->firstName.' '.$users->lastName.'\'s Credits</h1>';
include('../../users/new/templates/list/package-credits.php');	
?>
<script>
	loadListEffects()
</script>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>