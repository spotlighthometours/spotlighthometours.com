<?php
/*
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed objects
$users = new users($db);

// Require admin
  $users->authenticateAdmin();




  

?>
<html>
<head>
<title>Realtor Website Setting</title> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../includes/admin_styles.css" type="text/css">
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../jwplayer/7/jwplayer.js" type="text/javascript"></script><!-- User CP Realtor Website JS file -->
<script>jwplayer.key="Qp76XSwnVr3E0zH3GS7MY/A2R11i9fODdXIarfr5nHA="</script>
<script src="../../swfobject.js" language="JavaScript" type="text/javascript" /></script>



</head>
<body> 
    <p class="heading">Create New Community Details.</p>
<table width="400" border="1" cellspacing="0" cellpadding="20" bgcolor="f5f5f5">
 <tr> 
  <td height="67"> 
   <p class="heading"><a href="add-info.php">Add New Background Photos/Videos.</a></p>
   <p class="heading"><a href="add-comm.php">Add New Community Details.</a></p></td>
 </tr>
</table> 

</body>
</html>
