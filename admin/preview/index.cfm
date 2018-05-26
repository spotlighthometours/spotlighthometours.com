<cfsilent>
	<cfparam name="url.action" default="">
	<cfparam name="url.pg" default="showExistingPlans">
	<cfparam name="strMessage" default="" />

</cfsilent>
<CFOBJECT name = "members" component = "ColdFusionFunctions.Members">

<cfswitch expression="#url.action#">
	<cfcase value="UpdateAgents">
		<!--- loop through the fieldnames passed in the form, update appropriate agent by splitting fieldname --->
		<cfset lFormFields = form.fieldnames />
		<cfset lAgentsOn = "" />
		<cfloop index="i" list="#lFormFields#">
			<cfif ListLen(i,'_') gt 1>
				<cfset strAgentID = ListGetAt(i,2,'_') />
				<cfset lAgentsOn = ListAppend(lAgentsOn,strAgentID) />
				<CFSET iRet = members.SetMembersActive(#strAgentID#, '2', '1')>
    
			</cfif>
		</cfloop>
		<!--- update the remaining agents for this brokerage with 'off'--->
        <CFQUERY name = "qNotMembers" datasource="#request.dsn#" >
        	SELECT userID FROM users WHERE brokerageid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageid#">
				and userid NOT IN (#lAgentsOn#)
        </CFQUERY>
        <CFLOOP query="qNotMembers">
        	<CFSET iRet = members.SetMembersActive(#qNotMembers.userID#, '2', '0')>
        </CFLOOP>
	</cfcase>
	<cfcase value="addPlan">
		<!--- take BROKERAGESELECT, PLANSELECT and save them to the db --->
		<cfquery name="insertDB" datasource="#request.dsn#">
			insert into mobile_brokerage_signup (brokerage_id,mobile_account_type_id,createdon)
			values (
				<cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageselect#">,
				<cfqueryparam cfsqltype="cf_sql_integer" value="#form.planselect#">,
				<cfqueryparam cfsqltype="cf_sql_timestamp" value="#now()#" />
			)
		</cfquery>
		<!--- if there is a new price listed, need to register the new price --->
		<cfif StructKeyExists(form,'pricing') and trim(form.pricing) neq "">
			<cfquery name="updatePricing" datasource="#request.dsn#">
				insert into pricing_brokers_service (brokerage_id,service_id,unitprice) 
				values (
					<cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageselect#">,
					1,
					<cfqueryparam cfsqltype="cf_sql_float" value="#form.pricing#" />
				)
			</cfquery>
		</cfif>
		<!--- if plan was blanket coverage add everybody automatically to the new plan --->
		<cfif form.planselect eq 1>
			<CFQUERY name = "qMembers" datasource="#request.dsn#" >
        	SELECT userID FROM users WHERE brokerageid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageid#">
        </CFQUERY>
        <CFLOOP query="qMembers">
        	<CFSET iRet = members.SetMembersActive(#qNotMembers.userID#, '2', '1')>
        </CFLOOP>
		</cfif>
	</cfcase>
	<cfcase value="deletePlan">
		<!--- remove the plan from the table, then set all agents preview membership values to zero --->
		<cfquery name="qDelete" datasource="#request.dsn#">
			delete from mobile_brokerage_signup
			where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.brokerageid#">
		</cfquery>
		<!--- delete any custom pricing associated with the brokerage --->
		<cfquery name="qDelete" datasource="#request.dsn#">
			delete from pricing_brokers_service
			where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.brokerageid#">
			and service_id = 1
		</cfquery>
		
		<CFQUERY name = "qNotMembers" datasource="#request.dsn#" >
        	SELECT userID FROM users WHERE brokerageid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageid#">
        </CFQUERY>
        <CFLOOP query="qNotMembers">
        	<CFSET iRet = members.SetMembersActive(#qNotMembers.userID#, '2', '0')>
        </CFLOOP>
	</cfcase>
	<cfcase value="updateMobileRange">
		<!--- form values for the housecode/ivr mailboxes must have a start and an end, and be numeric --->
		<cfif StructKeyExists(form,'start') AND StructKeyExists(form,'end') And IsNumeric(form.start) And IsNumeric(form.end)>
			<cfquery name="qUpdate" datasource="#request.dsn#">
				update settings set string = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.start#">
				where name = 'Housecode-start'
			</cfquery>
			<cfquery name="qUpdate" datasource="#request.dsn#">
				update settings set string = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.end#">
				where name = 'Housecode-end'
			</cfquery>
			<cfset msg = "The update has been made.">
		<cfelse>
			<cfset msg = "There was a problem making the update. Make sure the values are numeric and try again.">
		</cfif>
		<cfset url.pg = "editHouseCodes" />
	</cfcase>
</cfswitch>

<!--- :: Display the page :: --->
<html>
<head>
<title>Admin</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<cfinclude template="_subnav.cfm">
<cfinclude template="_#url.pg#.cfm">
</body>
</html>