<cfquery name="qProducts" datasource="#request.db.dsn#">
	select productID, productName, unitPrice from products where tourTypeID = 0
</cfquery>
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
<cfoutput>
<div class="msg">#msg#</div>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
  <th width="5%">ProductID</th>
  <th width="45%">Name</th>
  <th width="35%">Unit Price</th>
  <th width="5%">&nbsp;</th>
  <cfloop query="qProducts">
	  <tr bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <td>#productID#</td>
		 <td><a href="#cgi.script_name#?pg=editproduct&product=#productID#">#productName#</a></td>
		<td>#dollarFormat(unitPrice)#</td>
		<td><a onClick="return confirmDelete();" href="#cgi.script_name#?action=deleteProduct&product=#productID#">delete</a></td>
	  </tr>
  </cfloop>
</table>
</cfoutput>
</body>
</html>
