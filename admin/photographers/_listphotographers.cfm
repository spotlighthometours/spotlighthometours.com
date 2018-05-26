<cfquery name="qSalesReps" datasource="#request.db.dsn#">
	select photographerID, fullName, isAffiliate, active from photographers order by fullName
</cfquery>
<html>
<head>
<title>Photographers</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../includes/admin_styles.css" type="text/css">
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this Photographer?"))
		return false;
}
function loginAsAffiliate(pid){
    location.href='/admin/affiliate-login.php?pid=' + pid;
}
</script>
</head>

<body>
<cfoutput>
<div class="msg">#msg#</div>
<a href="photographers.cfm?pg=editphotographers">Add a Photographer</a>
<table width="60%" border="0" cellspacing="2" cellpadding="2">
  <th width="5%">Photographer ID</th>
  <th width="45%">Name</th>
  <th align="center" width="1%">Active</th>
  <th align="center" width="1%">Affiliate</th>
  <th width="5%">&nbsp;</th>
  <th width="5%">&nbsp;</th>
  <cfloop query="qSalesReps">
	  <tr bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <td>#photographerID#</td>
		 <td><a href="#cgi.script_name#?pg=editphotographers&rep=#photographerID#">#fullName#</a></td>
         <td align="center">
		 	<cfif #qSalesReps.active# eq 1><IMG SRC="../images/check_mark.png" TITLE="Active Photographer" ></cfif>
         </td>
         <td align="center">
		 	<cfif #qSalesReps.isAffiliate# eq 1><IMG SRC="../images/check_mark.png" TITLE="Affiliate Photographer" ></cfif>
         </td>
		 <td align="center">
         	<a onClick="return confirmDelete();" href="#cgi.script_name#?action=deletephotographer&rep=#photographerID#">delete</a>
         </td>
		 <td align="center">
         	<a onClick="loginAsAffiliate(#photographerID#);" href="javascript:void(0);">login</a>
            </td>
	  </tr>
  </cfloop>
</table>
</cfoutput>
</body>
</html>
<IMG SRC="../images/check_mark.png" TITLE="Scheduled Attempt" >
