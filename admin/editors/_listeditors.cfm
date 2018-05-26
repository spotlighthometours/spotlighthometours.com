<cfquery name="qSalesReps" datasource="#request.db.dsn#">
	select id, fullName from editors order by fullName
</cfquery>
<html>
<head>
<title>Editors</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../includes/admin_styles.css" type="text/css">
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this Editor?"))
		return false;
}
</script>
</head>

<body>
<cfoutput>
<div class="msg">#msg#</div>
<a href="editors.cfm?pg=editeditors">Add a Editor</a>
<table width="60%" border="0" cellspacing="2" cellpadding="2">
  <th width="5%">Editor ID</th>
  <th width="45%">Name</th>
  <th width="5%">&nbsp;</th>
  <cfloop query="qSalesReps">
	  <tr bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <td>#id#</td>
		 <td><a href="#cgi.script_name#?pg=editeditors&rep=#id#">#fullName#</a></td>
		<td><a onClick="return confirmDelete();" href="#cgi.script_name#?action=deleteeditor&rep=#id#">delete</a></td>
	  </tr>
  </cfloop>
</table>
</cfoutput>
</body>
</html>
