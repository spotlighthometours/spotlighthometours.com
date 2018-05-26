<cfparam name="url.action" default="">
<cfparam name="url.pg" default="list">
<cfparam name="rel_filepath" default="../../../img/friends">
<cfparam name="filepath" default="#expandPath(rel_filepath)#">
<cfparam name="form.featured" default="0">

<cfswitch expression="#url.action#">
	<cfcase value="insert">
		<!--- :: perform main data insertion :: --->
		<cfquery datasource="#request.dsn#">
			INSERT INTO testimonials (name, body)
			VALUES (			
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.name#" maxlength="45">,
				 <cfqueryparam cfsqltype="cf_sql_longvarchar" value="#form.body#">
			)
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>

	<cfcase value="update">		
		<!--- :: perform main data update :: --->
		<cfquery datasource="#request.dsn#">
			UPDATE testimonials SET			
				name = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.name#" maxlength="100">,
				body = <cfqueryparam cfsqltype="cf_sql_longvarchar" value="#form.body#">
			WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.id#">
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>
	
	<cfcase value="delete">
		<!--- :: remove entry from database, more deletes will need to happen here :: --->
		<cfquery datasource="#request.dsn#">
			DELETE FROM testimonials WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.uid#">
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>	
</cfswitch>

<!--- :: Display the page :: --->
<html>
<head>
<title>Testimonials</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<cfinclude template="_subnav.cfm">
<cfinclude template="_#url.pg#.cfm">
</body>
</html>