<cfparam name="url.action" default="">
<cfparam name="url.pg" default="list">
<cfparam name="rel_filepath" default="../../../img/friends">
<cfparam name="filepath" default="#expandPath(rel_filepath)#">
<cfparam name="form.featured" default="0">

<cfswitch expression="#url.action#">
	<cfcase value="insert">
		<!--- :: perform main data insertion :: --->
		<cfquery datasource="#request.dsn#">
			INSERT INTO news (title, body, display)
			VALUES (			
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.title#" maxlength="100">,
				<cfqueryparam cfsqltype="cf_sql_longvarchar" value="#form.body#">,
				<cfif isDefined("form.display")>1<cfelse>0</cfif>
			)
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>

	<cfcase value="update">		
		<!--- :: perform main data update :: --->
		<cfquery datasource="#request.dsn#">
			UPDATE news SET			
				title = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.title#" maxlength="100">,
				body = <cfqueryparam cfsqltype="cf_sql_longvarchar" value="#form.body#">,
				display = <cfif isDefined("form.display")>1<cfelse>0</cfif>
			WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.id#">
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>
	
	<cfcase value="delete">
		<!--- :: remove entry from database, more deletes will need to happen here :: --->
		<cfquery datasource="#request.dsn#">
			DELETE FROM news WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.uid#">
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>	
</cfswitch>

<!--- :: Display the page :: --->
<html>
<head>
<title>Zip Codes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<cfinclude template="_subnav.cfm">
<cfinclude template="_#url.pg#.cfm">
</body>
</html>