<cfparam name="url.action" default="">
<cfparam name="url.pg" default="list">
<cfparam name="rel_filepath" default="../../../img/friends">
<cfparam name="filepath" default="#expandPath(rel_filepath)#">
<cfparam name="form.featured" default="0">

<cfswitch expression="#url.action#">
	<cfcase value="insert">
		<!--- :: perform main data insertion :: --->
		<cfquery datasource="#request.dsn#">
			INSERT INTO zipcodes (zip)
			VALUES (			
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.zip#" maxlength="45">
			)
		</cfquery>
		<!--- need to see if the zip is included as part of the US Zips. if not add. if so, make available --->
		<cfquery name="qFind" datasource="#request.dsn#">
			select zip from uszipcodes
			where zip = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.zip#" maxlength="45" />
		</cfquery>
		<cfif qFind.RecordCount eq 0>
			<cfquery name="qInsert" datasource="#request.dsn#">
				INSERT INTO uszipcodes (zip,available) 
				VALUES (
					<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.zip#" maxlength="45" />,
					<cfqueryparam cfsqltype="cf_sql_integer" value="1" />
				) 
			</cfquery>
		<cfelse>
			<cfquery name="qUpdate" datasource="#request.dsn#">
				update uszipcodes set available = <cfqueryparam cfsqltype="cf_sql_integer" value="1" />
				where zip = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.zip#" maxlength="45" />
			</cfquery>
		</cfif>
		
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>

	<cfcase value="changePrice">
		<cfquery name="qTourTypes" datasource="#request.db.dsn#">
			select tourTypeID, tourTypeName, unitPrice
			from tourTypes
			order by tourTypeName ASC
		</cfquery>
		
		<cfloop query="qTourTypes">
			<cfif StructKeyExists(form,'tour_#qTourTypes.tourTypeID#') 
				and Trim(Evaluate('form.tour_' & #qTourTypes.tourTypeID#)) neq "">
				<!--- need to see if it already exists, if so update, if not, insert --->
				<cfquery name="qFind" datasource="#request.dsn#">
					select id from pricing_zip
					where zip_id_fk = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.zip#">
					and tour_id_fk = <cfqueryparam cfsqltype="cf_sql_integer" value="#qTourTypes.tourTypeID#">
				</cfquery>
				<cfif qFind.RecordCount eq 0>
					<cfquery name="qInsert" datasource="#request.dsn#">
						insert into pricing_zip (id, zip_id_fk, tour_id_fk, price)
						values (
							<cfqueryparam cfsqltype="cf_sql_varchar" value="#createUUID()#" />,
							<cfqueryparam cfsqltype="cf_sql_integer" value="#form.zip#" />,
							<cfqueryparam cfsqltype="cf_sql_integer" value="#qTourTypes.tourTypeID#">,
							<cfqueryparam cfsqltype="cf_sql_decimal" value="#Trim(Evaluate('form.tour_' & qTourTypes.tourTypeID))#">
						) 
					</cfquery>
				<cfelse>
					<cfquery name="qUpdate" datasource="#request.dsn#">
						update pricing_zip
						set price = <cfqueryparam cfsqltype="cf_sql_decimal" value="#Trim(Evaluate('form.tour_' & qTourTypes.tourTypeID))#">
						where id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#qFind.id#" /> 
					</cfquery>
				</cfif>
			<cfelse>
				<!--- there was no value for this tourtype. need to check to see if there was a previous row and delete it --->
				<cfquery name="qFind" datasource="#request.dsn#">
					select id from pricing_zip
					where zip_id_fk = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.zip#">
					and tour_id_fk = <cfqueryparam cfsqltype="cf_sql_integer" value="#qTourTypes.tourTypeID#">
				</cfquery>
				<cfif qFind.RecordCount neq 0>
					<cfquery name="qDelete" datasource="#request.dsn#">
						delete from pricing_zip
						where id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#qFind.id#" />
					</cfquery>
				</cfif>				
			</cfif>
		</cfloop>		
	</cfcase>		

	<cfcase value="update">		
		<!--- :: perform main data update :: --->
		<cfquery datasource="#request.dsn#">
			UPDATE zipcodes SET			
				zip = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.name#" maxlength="100">
			WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.id#">
		</cfquery>
		<cflocation url="index.cfm?pg=list" addtoken="no">
	</cfcase>
	
	<cfcase value="delete">
		<!--- :: remove entry from database, more deletes will need to happen here :: --->
		<cfquery datasource="#request.dsn#">
			DELETE FROM zipcodes WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.uid#">
		</cfquery>
		<!--- also need to check to see if that zip code is listed in the uszipcodes. if so, make it not available --->
		<cfquery name="qFind" datasource="#request.dsn#">
			select zip from uszipcodes
			where zip = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.zip#" maxlength="45" />
		</cfquery>
		<cfif qFind.RecordCount neq 0>
			<cfquery name="qUpdate" datasource="#request.dsn#">
				update uszipcodes set available = <cfqueryparam cfsqltype="cf_sql_integer" value="0" />
				where zip = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.zip#" maxlength="45" />
			</cfquery>
		</cfif>

		
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