<cfsilent>
	<cfparam name="url.start" default="0" />
	<cfparam name="url.rows" default= "25" />
    <cfparam name="form.startdate" default="#DateAdd("m", -1, Now())#">
    <cfparam name="form.enddate" default="#Now()#">

	<cfquery name="qBrokerages" datasource="#request.dsn#">
		SELECT * FROM brokerages b
	</cfquery>
    <!--- Add a row. --->
    <cfset Temp=QueryAddRow(qBrokerages)>
    <!--- Set the values of the cells in the roll. --->
    <cfset Temp=QuerySetCell(qBrokerages, "brokerageID", 0)>
    <cfset Temp=QuerySetCell(qBrokerages, "brokerageName", "No Sales Rep")>

	<cfquery name="qTourTitles" datasource="#request.dsn#">
		SELECT * FROM tourtypes t
	</cfquery>
    <cfquery name="qTotalAgents" datasource="#request.dsn#">
        SELECT b.BrokerageID, COUNT(u.userID) AS totalAgents
        FROM brokerages b
        JOIN users u ON u.BrokerageID = b.brokerageID
        GROUP BY b.BrokerageID
	</cfquery>    
    <cfquery name="qAgents" datasource="#request.dsn#">
        SELECT b.BrokerageID, COUNT(u.userID) AS numUsers
        FROM brokerages b
        JOIN users u ON u.BrokerageID = b.brokerageID
        WHERE u.dateCreated >= #CreateODBCDateTime(startdate)#
        AND u.dateCreated <= #CreateODBCDateTime(enddate)#
        GROUP BY b.BrokerageID
	</cfquery>
    <cfquery name="qBrokerageHistory" datasource="#request.dsn#">
        SELECT id, brokerage_id, user_id, change_date
        FROM brokerage_history b
        WHERE b.change_date >= #CreateODBCDateTime(startdate)#
        AND b.change_date <= #CreateODBCDateTime(enddate)#
	</cfquery>
    <cfquery name="qTempTable" datasource="#request.dsn#">
        DROP TABLE IF EXISTS temp_table;
	</cfquery>
    <cfquery name="qTotalTours" datasource="#request.dsn#">
        CREATE TABLE temp_table
        SELECT BrokerageID, u.userID, t.tourTypeID, tt.tourTypeName, t.createdOn, m.tourID AS mtourID
        FROM users u
        JOIN tours t ON u.userID = t.userID
        JOIN tourtypes tt ON t.tourTypeID = tt.tourTypeID
        LEFT JOIN media m ON t.tourID = m.tourID
        WHERE t.createdOn >= #CreateODBCDateTime(startdate)#
        AND t.createdOn <= #CreateODBCDateTime(enddate)#
        GROUP BY t.tourID
	</cfquery>
    <cfquery name="qPreviewUsers" datasource="#request.dsn#">
        SELECT u.BrokerageID, COUNT(u.userID) AS previewUsers
        FROM users u
        JOIN brokerages b ON u.BrokerageID = b.brokerageID
        JOIN members m ON u.userid = m.userID AND m.typeID = 2 AND m.active = 1
        WHERE u.dateCreated <= #CreateODBCDateTime(enddate)#
        GROUP BY BrokerageID
	</cfquery>
    <cfloop query="qBrokerageHistory">
        <cfquery name="qUpdateHistory" datasource="#request.dsn#">
            UPDATE temp_table
            SET BrokerageID = #qBrokerageHistory.brokerage_id#
            WHERE userID = #qBrokerageHistory.user_id#
            AND createdOn >= #CreateODBCDateTime(qBrokerageHistory.change_date)#
        </cfquery>
    </cfloop>
    <cfquery name="qTotalTours" datasource="#request.dsn#">
        SELECT BrokerageID, userID, tourTypeID, tourTypeName, createdOn, mtourID
        FROM temp_table
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

<body id="tab2">

<cfinclude template="tabs.cfm">
    
<cfform>
<cfinput name="startdate" type="datefield" label="date:" mask="mm/dd/yyyy" value="#startdate#" validate="date">
<cfinput name="enddate" type="datefield" label="date:" mask="mm/dd/yyyy" value="#enddate#" validate="date">
<input type="submit" value="Go">
</cfform>
<cfparam name = "qGrossSales2.orderID" default = "0">


<cfoutput>
<table id="t1" class="sortable striped selectable" width="90%" border="0" cellspacing="2" cellpadding="2">
    <thead>
	<tr>
		<td class="sortable" style="text-align: left;">Brokerage</td>
		<td class="sortable" style="text-align: left;">Total Agents</td>
		<td class="sortable" style="text-align: left;">Total ## of New Agents</td>
		<td class="sortable" style="text-align: left;">Tours</td>
		<td class="sortable" style="text-align: left;">Unshot Tours</td>
        <cfloop query="qTourTitles">
		<td class="sortable" style="text-align: left;">#qTourTitles.tourTypeName#</td>
        </cfloop>
		<td class="sortable" style="text-align: left;">Total Preview Users</td>
	</tr>
    </thead>
    
<tbody>

<cfloop query="qBrokerages">
    <cfquery name="qBrokerages2" dbtype="query">
        SELECT brokerageName
        FROM qBrokerages
        WHERE BrokerageID = #qBrokerages.brokerageID#
    </cfquery>
    <cfquery name="qTotalAgents2" dbtype="query">
        SELECT totalAgents
        FROM qTotalAgents
        WHERE BrokerageID = #qBrokerages.brokerageID#
    </cfquery>    
    <cfquery name="qAgents2" dbtype="query">
        SELECT numUsers
        FROM qAgents
        WHERE BrokerageID = #qBrokerages.brokerageID#
    </cfquery>
    <cfquery name="qPreviewUsers2" dbtype="query">
        SELECT previewUsers
        FROM qPreviewUsers
        WHERE BrokerageID = #qBrokerages.brokerageID#
    </cfquery>
    <cfquery name="qNumTours" dbtype="query">
        SELECT COUNT(BrokerageID) AS numTours
        FROM qTotalTours
        WHERE BrokerageID = #qBrokerages.brokerageID#
        GROUP BY BrokerageID
    </cfquery>
    <cfquery name="qNumUnshotTours" dbtype="query">
        SELECT COUNT(BrokerageID) AS numTours
        FROM qTotalTours
        WHERE BrokerageID = #qBrokerages.brokerageID#
        AND mtourID IS NULL
        GROUP BY BrokerageID
    </cfquery>
    
    <!---<cfdump var="#qPreviewUsers2.previewUsers#"></cfdump>--->
    
	<tr bgcolor="###iif(qBrokerages.currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		<!---<td style="text-align: left;">#qBrokerages.brokerageName#</td>--->
		<td style="text-align: left;">#qBrokerages.brokerageName# #qBrokerages.BrokerageID#</td>
		<td>#NumberFormat(qTotalAgents2.totalAgents)#</td>
		<td>#NumberFormat(qAgents2.numUsers)#</td>
        <td>#NumberFormat(qNumTours.numTours)#</td>
        <td>#Val(qNumUnshotTours.numTours)#</td>
        <cfloop query="qTourTitles">
            <cfquery name="qNumTourTypes" dbtype="query">
                SELECT COUNT(tourTypeID) AS NumTourTypes
                FROM qTotalTours
                WHERE BrokerageID = #qBrokerages.brokerageID#
                AND tourTypeID = #qTourTitles.tourTypeID#
            </cfquery>
            <td>#NumberFormat(qNumTourTypes.NumTourTypes)#</td>
        </cfloop>
		<td>#NumberFormat(qPreviewUsers2.previewUsers)#</td>
	</tr>
</cfloop>
</tbody>

	</table>
</cfoutput>
<cfsilent>
    <cfquery name="qTempTable" datasource="#request.dsn#">
        DROP TABLE IF EXISTS temp_table;
    </cfquery>
</cfsilent>
</body>
</html>
