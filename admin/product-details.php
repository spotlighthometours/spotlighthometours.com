<?PHP
/* Author:  Jacob Edmond Kerr
 * Date:	4/17/2014
 * Show details for a product such as description and how many photos are allowed etc.
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
ShowErrors();

// Create instance of needed objects
$products = new products();

if(isset($_REQUEST['productID'])&&!empty($_REQUEST['productID'])){
	$productID = intval($_REQUEST['productID']);
	$products->load($productID);
}else{
	die('<h1>productID required! Please pass the productID as a parameter to this page!</h1>');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Product Details</title>
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
<h1><?PHP echo $products->productName ?></h1>
<h3><?PHP echo $products->tagline ?></h3>
<p><i><?PHP echo $products->description ?></i></p>
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><strong>Walkthrus</strong></td>
    <td><?PHP echo $products->walkthrus ?></td>
  </tr>
  <tr>
    <td><strong>Videos</strong></td>
    <td><?PHP echo $products->videos ?></td>
  </tr>
  <tr>
    <td><strong>Motion Scenes</strong></td>
    <td><?PHP echo $products->motion ?></td>
  </tr>
  <tr>
    <td><strong>Photos</strong></td>
    <td><?PHP echo $products->photos ?></td>
  </tr>
  <tr>
    <td><strong>HDR Photos</strong></td>
    <td><?PHP echo $products->hdr_photos ?></td>
  </tr>
</table>
</body>
</html>