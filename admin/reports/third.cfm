<cfsilent>
	<cfparam name="url.start" default="0" />
	<cfparam name="url.rows" default= "25" />
    <cfparam name="form.startdate" default="#DateAdd("m", -1, Now())#">
    <cfparam name="form.enddate" default="#Now()#">

	<cfquery name="qTourTypeNames" datasource="#request.dsn#">
		SELECT * FROM tourtypes t;
	</cfquery>
	<cfquery name="qTours" datasource="#request.dsn#">
		SELECT tourTypeID, m.tourID AS mtourID FROM tours t
        LEFT JOIN media m
        ON t.tourID = m.tourID
        WHERE t.createdOn >= #CreateODBCDateTime(startdate)#
        AND t.createdOn <= #CreateODBCDateTime(enddate)#
        GROUP BY t.tourID
	</cfquery>
	<cfquery name="qTourCount" datasource="#request.dsn#">
		SELECT COUNT(tourTypeID) AS totalTours FROM tours t
        WHERE t.createdOn >= #CreateODBCDateTime(startdate)#
        AND t.createdOn <= #CreateODBCDateTime(enddate)#
	</cfquery>
	<cfquery name="qGrossDollars" datasource="#request.dsn#">
        SELECT tourTypeID, SUM(total) AS grossDollars
        FROM tours t
        JOIN orders o ON o.userID = t.userID
        WHERE t.createdOn >= #CreateODBCDateTime(startdate)#
        AND t.createdOn <= #CreateODBCDateTime(enddate)#
        GROUP BY tourTypeID
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

<body id="tab3">

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
		<td class="sortable" style="text-align: left;">Tour Type Name</td>
		<td class="sortable" style="text-align: left;">Number of Tours</td>
		<td class="sortable" style="text-align: left;">Unshot Tours</td>
		<td class="sortable" style="text-align: left;">% of Total</td>
		<td class="sortable" style="text-align: left;">Gross Dollars</td>
	</tr>
    </thead>
    
<tbody>
<cfloop query="qTourTypeNames">
    <cfquery name="qNumberTours" dbtype="query">
        SELECT COUNT(tourTypeID) AS numTours
        FROM qTours
        WHERE tourTypeID = #qTourTypeNames.tourTypeID#
    </cfquery>
    <cfquery name="qNumberUnshotTours" dbtype="query">
        SELECT COUNT(tourTypeID) AS numTours
        FROM qTours
        WHERE tourTypeID = #qTourTypeNames.tourTypeID#
        AND mtourID IS NULL
    </cfquery>
    <cfquery name="qGrossDollars2" dbtype="query">
        SELECT grossDollars
        FROM qGrossDollars
        WHERE tourTypeID = #qTourTypeNames.tourTypeID#
    </cfquery>
	<tr bgcolor="###iif(qTourTypeNames.currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		<td style="text-align: left;">#qTourTypeNames.tourTypeName#</td>
		<td>#Val(qNumberTours.numTours)#</td>
		<td>#Val(qNumberUnshotTours.numTours)#</td>
		<td>#NumberFormat(Val(qNumberTours.numTours)/qTourCount.totalTours*100, ".__")#%</td>
		<td>#DollarFormat(qGrossDollars2.grossDollars)#</td>
	</tr>
</cfloop>
</tbody>

	</table>
</cfoutput>
</body>
</html>
