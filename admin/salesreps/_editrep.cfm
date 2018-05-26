<cfset editMode = iif(isDefined("url.rep"), true, false)>
<cfif editMode>
	<cfquery name="qSalesReps" datasource="#request.db.dsn#">
		select * from SalesReps where salesrepID = #url.rep#
	</cfquery>
</cfif>
<html>
<head>
<title>Sales Reps</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<cfoutput>
<form action="#cgi.sript_name#?action=<cfif editMode>updateRep<cfelse>insertRep</cfif>" method="post">
    <table width="500" border="0" cellspacing="2" cellpadding="4">
      <tr> 
        <td class="rowHead">Sales Rep Name</td>
        <td class="rowData"><input name="fullName" type="text" size="32" maxlength="50"<cfif editMode> value="#qSalesReps.fullName#"</cfif>></td>
      </tr>
		<tr> 
        <td class="rowHead">Email Address</td>
        <td class="rowData"><input name="email" type="text" size="32" maxlength="50"<cfif editMode> value="#qSalesReps.email#"</cfif>></td>
      </tr>
      <tr> 
        <td class="rowHead"><cfif editMode>
            <input type="hidden" name="salesRepID" value="#qSalesReps.salesRepID#">
          </cfif></td>
        <td class="rowData"><input type="submit" value="<cfif EditMode>Update<cfelse>Add</cfif> Sales Rep"></td>
      </tr>
    </table>
</form>
</cfoutput>
</body>
</html>