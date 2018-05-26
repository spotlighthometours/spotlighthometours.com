<cfquery name="qSalesReps" datasource="#request.db.dsn#">
	select salesRepID, fullName from salesReps order by fullName
</cfquery>
<html>
<head>
<title>Sales Reps</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../includes/admin_styles.css" type="text/css">
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this sales rep?"))
		return false;
}
</script>
</head>

<body>
<cfoutput>
<div class="msg">#msg#</div>
<a href="salesreps.cfm?pg=editrep">Add a Sales Rep</a>
<table width="60%" border="0" cellspacing="2" cellpadding="2">
  <th width="5%">RepID</th>
  <th width="45%">Name</th>
  <th width="5%">&nbsp;</th>
  <cfloop query="qSalesReps">
	  <tr bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <td>#salesRepID#</td>
		 <td><a href="#cgi.script_name#?pg=editrep&rep=#salesRepID#">#fullName#</a></td>
		<td><a onClick="return confirmDelete();" href="#cgi.script_name#?action=deleterep&rep=#salesRepID#">delete</a></td>
	  </tr>
  </cfloop>
</table>
</cfoutput>
</body>
</html>
