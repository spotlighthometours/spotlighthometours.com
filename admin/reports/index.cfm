<cfsilent>
	<cfparam name="url.start" default="0" />
	<cfparam name="url.rows" default= "25" />

    <cfparam name="form.startdate" default="#DateAdd("m", -1, Now())#">
    <cfparam name="form.enddate" default="#Now()#">
	<cfquery name="qSalesreps" datasource="#request.dsn#">
		SELECT * FROM salesreps s;
	</cfquery>
    <cfset CountRecs=#qSalesreps.RecordCount# +1>

    <!--- Add a row. --->
    <cfset Temp=QueryAddRow(qSalesreps)>
    <!--- Set the values of the cells in the roll. --->
    <cfset Temp=QuerySetCell(qSalesreps, "salesRepID", 0)>
    <cfset Temp=QuerySetCell(qSalesreps, "fullName", "No Sales Rep")>
    <cfset Temp=QuerySetCell(qSalesreps, "email", "dummy@spotlighthometours.com")>
	<cfquery name="qBrokerages" datasource="#request.dsn#">
		SELECT * FROM brokerages b
	</cfquery>
    <cfquery name="qGrossSales" datasource="#request.db.dsn#">
        SELECT o.orderID, salesRepID, BrokerageID, od.unitPrice, salesRepID
        FROM orders o, users u, orderdetails od
        WHERE o.userID = u.userID
        AND o.orderID = od.orderID
        AND o.createdOn >= #CreateODBCDateTime(startdate)#
        AND o.createdOn <= #CreateODBCDateTime(enddate)#
    </cfquery>
	<cfquery name="qTotalTours" datasource="#request.dsn#">
        SELECT BrokerageID, salesRepID, tourID
        FROM tours t, users u
        WHERE t.userID = u.userID
        AND t.createdOn >= #CreateODBCDateTime(startdate)#
        AND t.createdOn <= #CreateODBCDateTime(enddate)#
    </cfquery>
	<cfquery name="qPreviewSignups" datasource="#request.dsn#">
        SELECT BrokerageID, salesRepID, userID
        FROM users u, invoices i
        WHERE u.userID = i.userID_fk
        AND i.createdOn >= #CreateODBCDateTime(startdate)#
        AND i.createdOn <= #CreateODBCDateTime(enddate)#
    </cfquery>
	<cfquery name="qNewUsers" datasource="#request.dsn#">
        SELECT BrokerageID, salesRepID, userID
        FROM users u
        WHERE u.dateCreated >= #CreateODBCDateTime(startdate)#
        AND u.dateCreated <= #CreateODBCDateTime(enddate)#
    </cfquery>
	<cfquery name="qTotalSignups" datasource="#request.dsn#">
        SELECT BrokerageID, salesRepID, userID
        FROM users u
        WHERE u.dateCreated <= #CreateODBCDateTime(enddate)#
    </cfquery>
</cfsilent><html>
<head>
<title>Sales Rep</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="/admin/includes/admin_styles.css" rel="stylesheet" type="text/css">
<link href="tabs.css" rel="stylesheet" type="text/css">
</head>

<body id="tab1">
<cfinclude template="tabs.cfm">

<cfform>
<cfinput name="startdate" type="datefield" label="date:" mask="mm/dd/yyyy" value="#startdate#" validate="date">
<cfinput name="enddate" type="datefield" label="date:" mask="mm/dd/yyyy" value="#enddate#" validate="date">
<input type="submit" value="Go">
</cfform>
<cfparam name = "qGrossSales2.orderID" default = "0">


<cfoutput>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Rep Name</th>
		<th>Gross Sales</th>
		<th>## Orders</th>
		<th>Total Tours</th>
		<th>Preview Signups</th>
		<th>New Signups</th>
		<th>Total Signups</th>
	</tr>
<cfloop query="qSalesreps">
    <cfquery name="qBrokerages2" dbtype="query">
        SELECT brokerageID FROM qBrokerages
        WHERE salesRepID = #qSalesreps.salesRepID#
    </cfquery>
    <cfif #qBrokerages2.RecordCount# Is 0>
        <cfset vList = -1>
    <cfelse>
        <cfset vList = ValueList(qBrokerages2.brokerageID)>
    </cfif>
    <cfquery name="qGrossSales2" dbtype="query">
        SELECT COUNT(orderID) AS orderID, SUM(unitPrice) AS gSales, salesRepID
        FROM qGrossSales
        WHERE (salesRepID = #qSalesreps.salesRepID# OR BrokerageID IN (#vList#))
        GROUP BY salesRepID
    </cfquery>
	<cfquery name="qTotalTours2" dbtype="query">
        SELECT salesRepID, COUNT(tourID) AS tourCount
        FROM qTotalTours
        WHERE (salesRepID = #qSalesreps.salesRepID# OR BrokerageID IN (#vList#))
        GROUP BY salesRepID
    </cfquery>
	<cfquery name="qPreviewSignups2" dbtype="query">
        SELECT salesRepID, COUNT(userID) AS previewSignups
        FROM qPreviewSignups
        WHERE (salesRepID = #qSalesreps.salesRepID# OR BrokerageID IN (#vList#))
        GROUP BY salesRepID
    </cfquery>
	<cfquery name="qNewUsers2" dbtype="query">
        SELECT salesRepID, COUNT(userID) AS newUsers
        FROM qNewUsers
        WHERE (salesRepID = #qSalesreps.salesRepID# OR BrokerageID IN (#vList#))
        GROUP BY salesRepID
    </cfquery>
	<cfquery name="qTotalSignups2" dbtype="query">
        SELECT salesRepID, COUNT(userID) AS newUsers
        FROM qTotalSignups
        WHERE (salesRepID = #qSalesreps.salesRepID# OR BrokerageID IN (#vList#))
        GROUP BY salesRepID
    </cfquery>
    
	<tr bgcolor="###iif(qSalesreps.currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		<td>#qSalesreps.fullName#</td>
		<td align="right">#DollarFormat(qGrossSales2.gSales)#</td>
		<td align="right">#NumberFormat(qGrossSales2.orderID)#</td>
		<td align="right">#NumberFormat(qTotalTours2.tourCount)#</td>
		<td align="right">#NumberFormat(qPreviewSignups2.previewSignups)#</td>
		<td align="right">#NumberFormat(qNewUsers2.newUsers)#</td>
		<td align="right">#NumberFormat(qTotalSignups2.newUsers)#</td>
	</tr>
</cfloop>
	</table>
</cfoutput>
</body>
</html>
