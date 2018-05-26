<?php
    /**
     * @author William Merfalen
     * @date 2014-11-07
     * @purpose Port CF code to PHP
     */
    global $db;
    $pid = isset($_GET['product'])?intval($_GET['product']):null;
    $res = $db->select("products","productID=$pid")[0];
?>
<html>
<head>
<title>Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<form action="?action=<?php if($pid){ echo 'updateProduct'; }else{ echo 'insertProduct';}?>" method="post">
    <table width="500" border="0" cellspacing="2" cellpadding="4">
      <tr> 
        <td class="rowHead">Product Name</td>
        <td class="rowData"><input name="productName" type="text" size="32" maxlength="50" <?php echo ($pid ? "value='" . htmlentities($res['productName'],ENT_QUOTES) . "'" : "");?>></td>
      </tr>

		<tr> 
        <td class="rowHead">Unit Price</td>
        <td class="rowData"><input name="unitPrice" type="text" size="32" maxlength="50" <?php echo ($pid? "value='" . $res['unitPrice'] . "'" : "");?>></td>
      </tr>
		<tr> 
        <td class="rowHead">No Multiple Quantities</td>
        <td class="rowData"><input name="onePerOrder" type="checkbox" <?php echo ($res['onePerOrder'] == 1 ? "checked=checked" : "");?>></td>
      </tr>
		<tr> 
        <td class="rowHead">Charge Sales Tax</td>
        <td class="rowData"><input name="chargeSalesTax" type="checkbox" <?php echo ($res['chargeSalesTax'] == 1 ?"checked=checked":"");?>></td>
      </tr>
		<tr> 
        <td class="rowHead">Description</td>
        <td class="rowData">
        <textarea name="description" style="width: 300px; height:100px;"><?php echo ($pid? htmlentities($res['description'],ENT_QUOTES) :"");?></textarea>
		  </td>
      </tr>
      <tr> 
      <td class="rowHead">
    <?php if($pid): ?>
      <input type="hidden" name="productID" value="<?php echo $res['productID'];?>">
    <?php endif; ?>
          </td>
          <td class="rowData"><input type="submit" value="<?php echo ($pid?"Update":"Add Product");?>"></td>
      </tr>
    </table>
</form>
</body>
</html>
