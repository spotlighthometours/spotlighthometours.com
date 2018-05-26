<cfsilent>
	<cfparam name="url.start" default="0" />
	<cfparam name="url.rows" default= "25" />
    <cfparam name="url.uLastName" default= "0" />
    <cfparam name="url.ivNumber" default= "0" />
    <cfparam name="url.tourID" default= "0" />
    <cfif url.ivNumber eq "invoice Number/Title">
        <cfset "url.ivNumber" = 0>
    </cfif>
    <cfif url.uLastName eq "user last name">
        <cfset "url.uLastName" = 0>
    </cfif>
    <cfif url.tourID eq "tour ID">
        <cfset "url.tourID" = 0>
    </cfif>
    <cfquery name="qInvoices" datasource="#request.dsn#">
        select 
            i.number,
            i.amount,
            i.notes,
            i.createdOn,
            u.firstname,
            u.lastname,
            u.userID
        from invoices i join users u on i.userID_fk = u.userID
        where i.completed = 1
        <cfif url.uLastName gt 0>AND u.userID IN (SELECT userID FROM users WHERE lastName LIKE <cfqueryparam cfsqltype="CF_SQL_VARCHAR" value="%#url.uLastName#%">)</cfif>
        <cfif url.ivNumber gt 0>AND i.number LIKE <cfqueryparam cfsqltype="CF_SQL_VARCHAR" value="%#url.ivNumber#%"></cfif>
        <cfif url.tourID gt 0>AND i.invoiceID IN (SELECT invoiceID FROM invoices_tour_reference WHERE tourID = <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.tourID#">)</cfif>
        order by i.createdOn DESC
        LIMIT 
        <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.start#">,
        <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.rows#">
    </cfquery>
</cfsilent><html>
<head>
<title>Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="/admin/includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<cfoutput>
<h3>#url.rows# Most Recent Invoices, Starting with Row #Evaluate(url.start + 1)#</h3>
<div class="tourlist_nav">
	<cfif url.start gt 0>
		<a href="?start=#Evaluate(url.start - url.rows)#&rows=#url.rows#">Previous Page</a>
	</cfif>
	<cfif qInvoices.RecordCount eq url.rows>
		<a href="?start=#Evaluate(url.start + url.rows)#&rows=#url.rows#">Next Page</a>
	</cfif>
</div>
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><form action="" method="get">
      <input type="text" name="uLastName" value="<cfif url.uLastName gt 0>#url.uLastName#<cfelse>user last name</cfif>" style="padding:5px;" onclick="if(this.value=='user last name'){this.value='';this.style='color:black;'}" onblur="if(this.value==''){this.value='user last name';}"/>
      <input type="submit" value="search" style="padding:5px;" />
    </form></td>
    <td style="padding-left:20px;"><form action="" method="get">
      <input type="text" name="ivNumber" value="<cfif url.ivNumber gt 0>#url.ivNumber#<cfelse>invoice Number/Title</cfif>" style="padding:5px;" onclick="if(this.value=='invoice Number/Title'){this.value='';this.style='color:black;'}" onblur="if(this.value==''){this.value='invoice Number/Title';}"/>
      <input type="submit" value="search" style="padding:5px;" />
    </form></td>
    <td style="padding-left:20px;"><form action="" method="get">
      <input type="text" name="tourID" value="<cfif url.tourID gt 0>#url.tourID#<cfelse>tour ID</cfif>" style="padding:5px;" onclick="if(this.value=='tour ID'){this.value='';this.style='color:black;'}" onblur="if(this.value==''){this.value='tour ID';}"/>
      <input type="submit" value="search" style="padding:5px;" />
    </form></td>
  </tr>
</table>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Name</th>
		<th>UserID</th>
		<th>Invoice Number/Title</th>
		<th>Amount</th>
		<th>Created</th>
		<th>Notes</th>
	</tr>
<cfloop query="qInvoices">
	<tr bgcolor="###iif(qInvoices.currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		<td>#qInvoices.firstname# #qInvoices.lastname#</td>
		<td>#qInvoices.userID#</td>
		<td>#qInvoices.number#</td>
		<td>#DollarFormat(qInvoices.amount)#</td>
		<td>#DateFormat(qInvoices.CreatedOn,'mm/dd yy')#</td>
		<td width="25%"><div title="#qInvoices.notes#" onclick="this.innerHTML='#REReplace(qInvoices.notes,"#chr(13)#|#chr(9)#|\n|\r","","ALL")#'" style="cursor:pointer;">#Left(qInvoices.notes,30)#...</div></td>
	</tr>
</cfloop>
	</table>
<div class="tourlist_nav">
	<cfif url.start gt 0>
		<a href="?start=#Evaluate(url.start - url.rows)#&rows=#url.rows#&uLastName=#url.uLastName#&ivNumber=#url.ivNumber#&tourID=#url.tourID#">Previous Page</a>
	</cfif>
	<cfif qInvoices.RecordCount eq url.rows>
		<a href="?start=#Evaluate(url.start + url.rows)#&rows=#url.rows#&uLastName=#url.uLastName#&ivNumber=#url.ivNumber#&tourID=#url.tourID#">Next Page</a>
	</cfif>
</div>

</cfoutput>
</body>
</html>