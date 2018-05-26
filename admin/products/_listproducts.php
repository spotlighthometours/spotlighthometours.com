<?php
    /**
     * @author William Merfalen
     * @date 2014-11-07
     * @purpose Porting coldfusion code to PHP
     */
    global $db;
    $res = $db->select("products","tourTypeID=0");
?>
<html>
<head>
<title>Products</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../includes/admin_styles.css" type="text/css">
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this product?"))
		return false;
}
</script>
</head>

<body>
<div class="msg"><?php echo isset($msg) ? $msg : "" ; ?></div>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
  <th width="5%">ProductID</th>
  <th width="45%">Name</th>
  <th width="35%">Unit Price</th>
  <th width="5%">&nbsp;</th>
    <?php $i=0; ?>
    <?php foreach($res as $index=>$info): ?>
            <tr bgcolor="<?php echo ($i++ % 2)? "E8EEF7":"ffffff"; ?>">
            <td><?php echo $info['productID']; ?></td>
            <td><a href="?pg=editproduct&product=<?php echo $info['productID']; ?>"><?php echo $info['productName'];?></a></td>
            <td><?php echo $info['unitPrice']; ?></td>
            <td><a onClick="return confirmDelete();" href="?action=deleteProduct&product=<?php echo $info['productID'];?>">delete</a></td>
	        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
