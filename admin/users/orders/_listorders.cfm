
<cfquery name="qOrders" datasource="#request.db.dsn#">
	select orderID, createdOn, total from orders where userID = #url.user# order by createdOn desc
</cfquery>
<html>
<head>
<title>Orders</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this order and associated tours?"))
		return false;
}
</script>
</head>

<body>
<cfoutput>
<div class="msg">#msg#</div>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
  <th width="45%">Order</th>
  <th width="25%">Date</th>
  <th width="25%">Order Total</th>
  <th width="5%">&nbsp;</th>
  <cfloop query="qOrders">
  <tr bgcolor="###iif(currentRow mod 2, de("f5f5f5"), de("ffffff"))#">
    <td>ORDER###orderID#</td>
    <td>#dateFormat(createdOn, "m/d/yyyy")#</td>
	<td>#dollarFormat(total)#</td>
	<td><a onClick="return confirmDelete();" href="#cgi.script_name#?action=deleteUser&user=#userID#">delete</a></td>
  </tr>
  </cfloop>
  <cfif not qOrders.recordCount>
  	<tr>
		  <td colspan="5">This user has no orders. <a href="#cgi.script_name#?pg=editOrder&user=#url.user#">Add an order</a></td>
	</tr>
  </cfif>
</table>
</cfoutput>
</body>
</html>
