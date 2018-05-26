<cfquery name="qTourTypes" datasource="#request.db.dsn#">
	select tourTypeID, tourTypeName, unitPrice, hidden,tourCategory from tourTypes order by unitPrice desc
</cfquery>
<html>
<head>
<title>Tour Types</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this Tour Type?"))
		return false;
}
</script>
</head>

<body>
<cfoutput>
<div class="msg">#msg#</div>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
  <th width="10%">Tour Type ID</a></th>
  <th width="40%">Tour Type Name</th>
  <th width="20%">Tour Category</th>
  <th width="15%">Unit Price</th>
  <th width="10%">Hidden</th>
  <th width="5%">&nbsp;</th>
  <cfloop query="qTourTypes">
	  <tr bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <td>#tourTypeID#</td>
		 <td><a href="#cgi.script_name#?pg=editTourType&tourtype=#tourTypeID#">#tourTypeName#</a></td>
		<td>#tourCategory#</td>
        <td>#dollarFormat(unitPrice)#</td>
		<td>#iif(qTourTypes.hidden eq 0,DE('No'),DE('Yes'))#</td>
		<td><a onClick="return confirmDelete();" href="#cgi.script_name#?action=deleteTourType&tourtype=#tourtypeID#">delete</a></td>
	  </tr>
  </cfloop>
</table>
</cfoutput>
</body>
</html>