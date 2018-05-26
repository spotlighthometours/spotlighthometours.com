<cfsetting showdebugoutput="yes">
<cfif cgi.request_method eq "post">
	<cfset startDate = createDateTime(form.reportYear, form.reportMonth,1,0,0,0)>
	<cfset endDate = createDateTime(form.reportYear, form.reportMonth,daysInMonth(startDate),0,0,0)>
	<cfquery name="qTours" datasource="#request.db.dsn#">
		SELECT u.firstName, u.lastName, u.userType, t.tourid, t.title, b.brokerageName, o.total, p.unitPrice
		FROM tours t inner join users u on t.userid = u.userID
		inner join products p on t.tourtypeID = p.tourtypeID
		left join orderDetails od on p.productID = od.productID
		left join orders o on od.orderID = o.orderID
		left join salesreps sr on u.salesRepID = sr.salesRepID
		left join brokerages b on u.brokerageID = b.brokerageID
		WHERE t.userID <> 2 AND CASE WHEN u.salesRepID is null THEN b.salesRepID = '#form.salesRepID#' ELSE u.salesRepID = '#form.salesRepID#' END
		AND t.createdOn between #startDate# and #endDate# order by t.createdOn
	</cfquery>
</cfif>

<cfquery name="qSalesReps" datasource="#request.db.dsn#">
	select salesRepID, fullName from salesReps order by fullName
</cfquery>
<html>
<head>
<title>Sales Reps</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../includes/admin_styles.css" type="text/css">
</head>

<body>
<cfoutput>
<div class="msg">#msg#</div>
<form action="#cgi.script_name#?pg=reports" method="post">
<table width="400" border="0" cellspacing="2" cellpadding="5">
	<tr> 
        <td class="rowHead">Sales Rep Name</td>
        <td class="rowData">
		  <select name="salesRepID">
            <cfloop query="qSalesReps">
              <option value="#salesRepID#">#fullName#</option>
            </cfloop>
          </select>
		  </td>
      </tr>
		<tr> 
        <td class="rowHead">Date</td>
        <td class="rowData">
		  <select name="reportMonth">
              <cfloop from="1" to="12" index="i">
                <option value="#i#">#monthAsString(i)#</option>
              </cfloop>
            </select>
				<select name="reportYear">
					<cfloop from="2004" to="2014" index="i">
              <option>#i#</option>
				  </cfloop>
            </select> 
		  		&nbsp;&nbsp;<input type="submit" value="Get Report">
		  </td>
      </tr>
</table>
</form>
<cfif isDefined('qTours')>
<cfset periodtotal = 0.00>
<table width="600">
<cfloop query="qTours">
<cfscript>
	if(len(total))
		periodtotal = periodtotal + total;
	else
		periodtotal = periodtotal + unitprice;
</cfscript>
	<th>Customer</th>
	<th>Type</th>
	<th>Brokerage</th>
	<th>Tour</th>
	<th>Total</th>
	<tr bgcolor="###iif(currentRow mod 2, de('f5f5f5'), de('ffffff'))#">
		<td>#firstName# #lastName#</td>
		<td>#userType#</td>
		<td>#brokerageName#</td>
		<td>#title#</td>
		<td><cfif len(total)>#dollarFormat(total)#<cfelse>#dollarFormat(unitprice)#</cfif></td>
	</tr>

</cfloop>
	<tr>
		<td colspan="3">&nbsp;</td>
		<td><strong>Total</strong></td>
		<td><strong>#dollarFormat(periodTotal)#</strong></td>
	</tr>
</table>
</cfif>
</cfoutput>
</body>
</html>
