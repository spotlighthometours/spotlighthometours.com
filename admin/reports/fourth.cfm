<cfsilent>
	<cfparam name="url.start" default="0" />
	<cfparam name="url.rows" default= "25" />
    <cfparam name="form.startdate" default="#DateAdd("m", -1, Now())#">
    <cfparam name="form.enddate" default="#Now()#">

	<cfquery name="qBrokerages" datasource="#request.dsn#">
		SELECT BrokerageID, brokerageName FROM brokerages b;
	</cfquery>
	<cfquery name="qUsers" datasource="#request.dsn#">
        SELECT BrokerageID FROM users u
        WHERE userType = 'Agent'
	</cfquery>
	<cfquery name="qPreviewAgents" datasource="#request.dsn#">
        SELECT BrokerageID FROM users u, members m 
        WHERE userType = 'Agent' 
        AND u.userid = m.userID AND m.typeID = 2 AND m.active = 1
	</cfquery>
	<cfquery name="qNewAgents" datasource="#request.dsn#">
        SELECT BrokerageID FROM users u
        JOIN invoices i ON u.userID = i.userID_fk
        WHERE userType = 'Agent'
        AND notes = 'Reoccuring Mobile Preview Payment'
        AND i.createdOn >= #CreateODBCDateTime(startdate)#
        AND i.createdOn <= #CreateODBCDateTime(enddate)#
	</cfquery>
	<cfquery name="qDefaultPrice" datasource="#request.dsn#">
        SELECT default_amount FROM services s;
	</cfquery>
	<cfquery name="qBrokerPricing" datasource="#request.dsn#">
        SELECT brokerage_id, unitprice FROM pricing_brokers_service p;
	</cfquery>
	<cfquery name="qGross" datasource="#request.dsn#">
        SELECT b.brokerageID, tourID FROM tours t
        JOIN users u ON u.userID = t.userID
        JOIN brokerages b ON b.brokerageID = u.brokerageID
        WHERE t.createdOn >= #CreateODBCDateTime(startdate)#
        AND t.createdOn <= #CreateODBCDateTime(enddate)#
	</cfquery>
</cfsilent><html>
<head>
<title>Brokerage Reports</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="/admin/includes/admin_styles.css" rel="stylesheet" type="text/css">
<link href="tabs.css" rel="stylesheet" type="text/css">
<style type="text/css">
td {
	text-align: right;
}
</style>
<link rel="stylesheet" href="elitezebras-2-0.css" type="text/css" media="screen" />
<script type="text/javascript" src="elitezebras-2-0.js"></script>
</head>

<body id="tab4">

<cfinclude template="tabs.cfm">
    
<cfform>
<cfinput name="startdate" type="datefield" label="date:" mask="mm/dd/yyyy" value="#startdate#" validate="date">
<cfinput name="enddate" type="datefield" label="date:" mask="mm/dd/yyyy" value="#enddate#" validate="date">
<input type="submit" value="Go">
</cfform>

<cfoutput>
<table id="t1" class="sortable striped selectable" width="90%" border="0" cellspacing="2" cellpadding="2">
    <thead>
	<tr>
		<td class="sortable" style="text-align: left;">Brokerage</td>
		<td class="sortable" style="text-align: left;">Total Agents</td>
		<td class="sortable" style="text-align: left;">Preview Agents</td>
		<td class="sortable" style="text-align: left;">New Agents</td>
		<td class="sortable" style="text-align: left;">Gross</td>
	</tr>
    </thead>
    
<tbody>
<cfloop query="qBrokerages">
    <cfquery name="qUsers2" dbtype="query">
        SELECT COUNT(BrokerageID) AS totalAgents
        FROM qUsers
        WHERE BrokerageID = #qBrokerages.BrokerageID#
    </cfquery>
    <cfquery name="qPreviewAgents2" dbtype="query">
        SELECT COUNT(BrokerageID) AS totalAgents
        FROM qPreviewAgents
        WHERE BrokerageID = #qBrokerages.BrokerageID#
    </cfquery>
    <cfquery name="qNewAgents2" dbtype="query">
        SELECT COUNT(BrokerageID) AS newAgents
        FROM qNewAgents
        WHERE BrokerageID = #qBrokerages.BrokerageID#
    </cfquery>
    <cfquery name="qBrokerPricing2" dbtype="query">
        SELECT unitprice
        FROM qBrokerPricing
        WHERE brokerage_id = #qBrokerages.BrokerageID#
    </cfquery>
    <cfquery name="qGross2" dbtype="query">
        SELECT COUNT(tourID) AS tourCount
        FROM qGross
        WHERE BrokerageID = #qBrokerages.BrokerageID#
    </cfquery>
	<tr bgcolor="###iif(qBrokerages.currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		<td style="text-align: left;">#qBrokerages.brokerageName#</td>
		<td>#Val(qUsers2.totalAgents)#</td>
		<td>#Val(qPreviewAgents2.totalAgents)#</td>
		<td>#Val(qNewAgents2.newAgents)#</td>
        <cfif Val(qBrokerPricing2.unitprice) Is 0>
		<td>#DollarFormat(Val(qGross2.tourCount)*qDefaultPrice.default_amount)#</td>
        <cfelse>
		<td>#DollarFormat(Val(qGross2.tourCount*qBrokerPricing2.unitprice))#</td>
        </cfif>
	</tr>
</cfloop>
</tbody>

	</table>
</cfoutput>
</body>
</html>
