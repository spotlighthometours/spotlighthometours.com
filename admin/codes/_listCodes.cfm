<cfquery name="qPromoCodes" datasource="#request.db.dsn#">
	select * from promoCodes order by expDate desc
</cfquery>
<html>
<head>
<title>Promo Codes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this code?"))
		return false;
}
</script>
</head>

<body>
<cfoutput>
<div class="msg">#msg#</div>
<table width="60%" border="0" cellspacing="2" cellpadding="2">
  <th width="50%">Code</th>
  <th width="20%">Value</th>
  <th width="25%">Expiration</th>
  <th width="5%">&nbsp;</th>
  <cfloop query="qPromoCodes">
	  <tr bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <td><a href="#cgi.script_name#?pg=editCode&code=#codestr#">#codeStr#</a></td>
		 <td>#iif(qPromoCodes.value lte 1,de(numberFormat(qPromoCodes.value * 100, '999') & '%'), de(dollarFormat(qPromoCodes.value)))#</td>
		 <td>#expDate#</td>
		 <td><a onClick="return confirmDelete();" href="#cgi.script_name#?action=deleteCode&code=#codestr#">delete</a></td>
	  </tr>
  </cfloop>
</table>
</cfoutput>
</body>
</html>