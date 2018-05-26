<?php
/*
 * Admin: Tour Progress
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$reports = new reports();
$tourtypes = new tourtypes();
$users = new users();

// Require admin
//$users->authenticateAdmin();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tour Progress</title>
<script src="../../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-progress.js" type="text/javascript"></script><!-- Admin Progress JS file -->
<style type="text/css" media="screen">
@import "../../repository_css/template.css";
 @import "../../repository_css/admin-v2.css";
 @import "../../repository_css/jquery-ui-1.8.16.custom.css";
</style>
</head>
<body style="width:1300px;">
<h1>Photo/Video Tours Queue</h1>
<h2>Photo Tours Queue</h2>
<table style="margin-left:20px;">
	<tr>
    	<td>
            <div class="button_new button_blue_big button_mid" onclick="window.location='photo/'">
                <div class="curve curve_left"></div>
                <span class="button_caption">All</span>
                <div class="curve curve_right"></div>
            </div>
        </td>
        <td>
            <div class="button_new button_blue_big button_mid" onclick="window.location='photo/?filterTemplate=4'">
              <div class="curve curve_left"></div>
              <span class="button_caption">Scheduling</span>
              <div class="curve curve_right"></div>
        </div></td>
        <td>
            <div class="button_new button_blue_big button_mid" onclick="window.location='photo/?filterTemplate=2'">
              <div class="curve curve_left"></div>
              <span class="button_caption">Editing</span>
              <div class="curve curve_right"></div>
        </div></td>
        <td><div class="button_new button_blue_big button_mid" onclick="window.location='photo/?filterTemplate=3'">
          <div class="curve curve_left"></div>
          <span class="button_caption">Media</span>
          <div class="curve curve_right"></div>
        </div></td>
    </tr>
</table>
<h2>Video Tours Queue</h2>
<table style="margin-left:20px;">
	<tr>
    	<td>
            <div class="button_new button_blue_big button_mid" onclick="window.location='video/'">
                <div class="curve curve_left"></div>
                <span class="button_caption">All</span>
                <div class="curve curve_right"></div>
            </div>
        </td>
        <td>
            <div class="button_new button_blue_big button_mid" onclick="window.location='video/?filterTemplate=4'">
              <div class="curve curve_left"></div>
              <span class="button_caption">Scheduling</span>
              <div class="curve curve_right"></div>
        </div></td>
        <td>
            <div class="button_new button_blue_big button_mid" onclick="window.location='video/?filterTemplate=2'">
              <div class="curve curve_left"></div>
              <span class="button_caption">Editing</span>
              <div class="curve curve_right"></div>
        </div></td>
        <td><div class="button_new button_blue_big button_mid" onclick="window.location='video/?filterTemplate=3'">
          <div class="curve curve_left"></div>
          <span class="button_caption">Media</span>
          <div class="curve curve_right"></div>
        </div></td>
    </tr>
</table>
<?PHP
	include('../repository_inc/html/modal.html');
?>
</body>
</html>