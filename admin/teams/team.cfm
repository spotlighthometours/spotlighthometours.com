<cfsilent>
<cfparam name="url.action" default="">
<cfparam name="url.pg" default="listTeam">
<cfparam name="msg" default="">

<cfswitch expression="#url.action#">
<cfcase value="insertTeam">
	<!--- required values: username, password defined, at least one brokerage listed --->
	<!--- get the max userid and add 1 --->
	<cflock name="AddingTeam" timeout="60">
		<cfquery name="qNextUser" datasource="#request.db.dsn#">
			select max(userid) + 1 as nextnum
			from teams
			limit 1
		</cfquery>
		
		<cfquery name="qInsert" datasource="#request.db.dsn#">
			insert into teams (userid,username,password,brokerageid,createdon,contactRight,socialHub,api_key) values (
				<cfqueryparam cfsqltype="cf_sql_integer" value="#qNextUser.nextnum#" />,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.username#" />,
				<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.password#" />,
				<cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(form.brokerage,1)#" />,
				<cfqueryparam cfsqltype="cf_sql_timestamp" value="#now()#" />,
				<cfqueryparam cfsqltype="cf_sql_integer" value="#iif(StructKeyExists(form,'contactright'),1,0)#" />,
                <cfqueryparam cfsqltype="cf_sql_integer" value="#iif(StructKeyExists(form,'socialHub'),1,0)#" />,
				'#UCase(left(toBase64(encrypt('#form.username##randRange(1,9999)#', 'spht1987')),16))#'
			)
		</cfquery>
		
		<!--- now add all the team connections --->
		<cfloop index="i" list="#form.brokerage#">
			<cfquery name="qTeamToBrokerageUpdate" datasource="#request.db.dsn#">
				insert into teams_to_brokerages (id,team_id,brokerage_id) values (
					<cfqueryparam cfsqltype="cf_sql_varchar" value="#createUUID()#" />,
					<cfqueryparam cfsqltype="cf_sql_integer" value="#qNextUser.nextnum#" />,
					<cfqueryparam cfsqltype="cf_sql_integer" value="#i#" />
				)
			</cfquery>
		</cfloop>
	</cflock>
	
	<cfset msg = "The team was successfully added.">
	<cfset pg = "listteam" />		
</cfcase>
<cfcase value="updateTeam">
	<!--- need to update the team table, delete and rebuild the team_to_broker table --->
	<cflock name="AddingTeam" timeout="60">
		<cfquery name="qUpdate" datasource="#request.db.dsn#">
			update teams
			set username = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.username#">,
			<cfif trim(form.password) neq "">
				password = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.password#">,
			</cfif>
			contactRight = <cfqueryparam cfsqltype="cf_sql_integer" value="#iif(StructKeyExists(form,'contactright'),1,0)#" />,
            socialHub = <cfqueryparam cfsqltype="cf_sql_integer" value="#iif(StructKeyExists(form,'socialHub'),1,0)#" />
			where userid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.userID#" />
		</cfquery>
		
		<cfquery name="qDelete" datasource="#request.db.dsn#">
			delete from teams_to_brokerages
			where team_id = <cfqueryparam cfsqltype="cf_sql_INteger" value="#form.userID#" />
		</cfquery>

		<!--- now add all the team connections --->
		<cfloop index="i" list="#form.brokerage#">
			<cfquery name="qTeamToBrokerageUpdate" datasource="#request.db.dsn#">
				insert into teams_to_brokerages (id,team_id,brokerage_id) values (
					<cfqueryparam cfsqltype="cf_sql_varchar" value="#createUUID()#" />,
					<cfqueryparam cfsqltype="cf_sql_integer" value="#form.userID#" />,
					<cfqueryparam cfsqltype="cf_sql_integer" value="#i#" />
				)
			</cfquery>
		</cfloop>
	</cflock>
	
	<cfset msg = "The team was successfully updated.">
	<cfset pg = "listteam">
</cfcase>
<cfcase value="deleteTeam">
	<!--- should be a url variable labeled  team --->
	<cflock name="AddingTeam" timeout="60">
		<!--- delete all references for this team from the teams_brokerage --->
		<cfquery name="qDelete" datasource="#request.db.dsn#">
			delete from teams_to_brokerages
			where team_id = <cfqueryparam cfsqltype="cf_sql_INteger" value="#url.team#" />
		</cfquery>
		
		<!--- delete from original table --->
		<cfquery name="qDelete" datasource="#request.db.dsn#">
			delete from teams
			where userid = <cfqueryparam cfsqltype="cf_sql_INteger" value="#url.team#" />
		</cfquery>
	</cflock>
	<cfset msg = "The team was successfully deleted.">
	<cfset pg = "listteam">
</cfcase>
</cfswitch>
</cfsilent>

<cfif url.pg eq "editteam">
	<cfinclude template="_editteam.cfm">
<cfelse>
	<cfset strPage = "_" & url.pg & ".cfm" />
	<cfinclude template="#strPage#" />
</cfif>