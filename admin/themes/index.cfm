<cfparam name="url.action" default="">
<cfparam name="url.pg" default="list">
<cfparam name="rel_filepath" default="../../../img/friends">
<cfparam name="filepath" default="#expandPath(rel_filepath)#">
<cfparam name="form.featured" default="0">



<cfswitch expression="#url.action#">
	<cfcase value="insert">
		<!--- :: perform main data insertion :: --->
		<cfquery datasource="#request.dsn#">
			INSERT INTO themes (name,background_color,agent_details_text_color,tour_info_text_color,media_text_color,tab_text_color,tour_panel_background_color,media_panel_background_color,tab_background_color,tab_inactive_color)
			VALUES (	
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.name#" maxlength="45">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.background_color#" maxlength="6">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.agent_details_text_color#" maxlength="6">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tour_info_text_color#" maxlength="6">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.media_text_color#" maxlength="6">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tab_text_color#" maxlength="6">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tour_panel_background_color#" maxlength="6">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.media_panel_background_color#" maxlength="6">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tab_background_color#" maxlength="6">,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tab_inactive_color#" maxlength="6">
			)
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>

	<cfcase value="update">		
		<!--- :: perform main data update :: --->
		<cfquery datasource="#request.dsn#">
			UPDATE themes SET			
				name = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.name#" maxlength="45">,
				background_color = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.background_color#" maxlength="6">,
				agent_details_text_color = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.agent_details_text_color#" maxlength="6">,
				tour_info_text_color = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tour_info_text_color#" maxlength="6">,
				media_text_color = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.media_text_color#" maxlength="6">,
				tab_text_color = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tab_text_color#" maxlength="6">,
				tour_panel_background_color = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tour_panel_background_color#" maxlength="6">,
				media_panel_background_color = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.media_panel_background_color#" maxlength="6">,
				tab_background_color = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tab_background_color#" maxlength="6">,
				tab_inactive_color = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.tab_inactive_color#" maxlength="6">
			WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.id#">
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>
	
	<cfcase value="delete">
		<!--- :: remove entry from database, more deletes will need to happen here :: --->
		<cfquery datasource="#request.dsn#">
			DELETE FROM themes WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.uid#">
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>	
</cfswitch>

<!--- :: Display the page :: --->
<html>
<head>
<title>Themes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<cfinclude template="_subnav.cfm">
<cfinclude template="_#url.pg#.cfm">
</body>
</html>