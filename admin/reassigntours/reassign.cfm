<html>
<head>
<title>Reassign Tours Action</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="/admin/includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<cfset myArrayList = ListToArray(form.tourID)>
<cfloop array="#myArrayList#" index="id">
    <cfif IsNumeric(#id#)>
        <cfoutput>Tour #HTMLEditFormat(id)# has been reassigned.</cfoutput><br>
        <cfquery name="qTours" datasource="#request.db.dsn#" debug="yes">
            UPDATE tours
            SET userID = <cfqueryparam value="#form.userID#" CFSQLType="CF_SQL_INTEGER">
            WHERE tourID = <cfqueryparam value="#id#" CFSQLType="CF_SQL_INTEGER">
        </cfquery>
		<cfquery name="qTours" datasource="#request.db.dsn#" debug="yes">
            UPDATE orders
            SET userID = <cfqueryparam value="#form.userID#" CFSQLType="CF_SQL_INTEGER">
            WHERE tourid = <cfqueryparam value="#id#" CFSQLType="CF_SQL_INTEGER">
        </cfquery>
    <cfelse>
        <cfoutput>#HTMLEditFormat(id)#</cfoutput> is not a valid tour ID.<br>
    </cfif>
</cfloop>
</body>
</html>
