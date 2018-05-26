<?PHP
/* Author:  Jacob Edmond Kerr
 * Date:	4/17/2014
 * Show details for a tour type such as description and how many photos are allowed etc.
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
ShowErrors();

// Create instance of needed objects
$tourtypes = new tourtypes();

if(isset($_REQUEST['tourTypeID'])&&!empty($_REQUEST['tourTypeID'])){
	$tourTypeID = intval($_REQUEST['tourTypeID']);
	$tourtypes->load($tourTypeID);
}else{
	die('<h1>tourTypeID required! Please pass the tourTypeID as a parameter to this page!</h1>');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tour Type Details</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body style="width:auto;">
<h1><?PHP echo $tourtypes->tourTypeName ?></h1>
<h3><?PHP echo $tourtypes->tagline ?></h3>
<p><i><?PHP echo $tourtypes->description ?></i></p>
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><strong>Category</strong></td>
    <td><?PHP echo $tourtypes->tourCategory ?></td>
  </tr>
  <tr>
    <td><strong>Walkthrus</strong></td>
    <td><?PHP echo $tourtypes->walkthrus ?></td>
  </tr>
  <tr>
    <td><strong>Videos</strong></td>
    <td><?PHP echo $tourtypes->videos ?></td>
  </tr>
  <tr>
    <td><strong>Motion Scenes</strong></td>
    <td><?PHP echo $tourtypes->motion ?></td>
  </tr>
  <tr>
    <td><strong>Panoramics</strong></td>
    <td><?PHP echo $tourtypes->panoramics ?></td>
  </tr>
  <tr>
    <td><strong>Photos</strong></td>
    <td><?PHP echo $tourtypes->photos ?></td>
  </tr>
  <tr>
    <td><strong>HDR Photos</strong></td>
    <td><?PHP echo $tourtypes->hdr_photos ?></td>
  </tr>
</table>
</body>
</html>