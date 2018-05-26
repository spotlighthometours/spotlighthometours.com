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
                <CFSET iRet = members.SetMembersActive(#strAgentID#, '1', '1')>
    
			</cfif>
		</cfloop>
		<!--- update the remaining agents for this brokerage with 'off'--->
        <CFQUERY name = "qNotMembers" datasource="#request.dsn#" >
        	SELECT userID FROM users WHERE brokerageid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageid#">
				and userid NOT IN (#lAgentsOn#)
        </CFQUERY>
        <CFLOOP query="qNotMembers">
        	<CFSET iRet = members.SetMembersActive(#qNotMembers.userID#, '1', '0')>
        </CFLOOP>
	</cfcase>
	<cfcase value="addPlan">
		<!--- take BROKERAGESELECT, PLANSELECT and save them to the db --->
		<cfquery name="insertDB" datasource="#request.dsn#">
			insert into brokerage_express_signup (brokerage_id,createdon)
			values (
				<cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageselect#">,
				<cfqueryparam cfsqltype="cf_sql_timestamp" value="#now()#" />
			)
		</cfquery>
		<!--- if there is a new price listed, need to register the new price --->
		<cfif StructKeyExists(form,'pricing') and trim(form.pricing) neq "">
			<cfquery name="updatePricing" datasource="#request.dsn#">
				insert into pricing_brokers_service (brokerage_id,service_id,unitprice)
				values (
					<cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageselect#">,
					2,
					<cfqueryparam cfsqltype="cf_sql_float" value="#form.pricing#" />
				)
			</cfquery>
		</cfif>
		<!--- if plan was blanket coverage add everybody automatically to the new plan --->
		<CFQUERY name = "qMembers" datasource="#request.dsn#" >
        	SELECT userID FROM users WHERE brokerageid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageid#">
        </CFQUERY>
        <CFLOOP query="qMembers">
        	<CFSET iRet = members.SetMembersActive(#qNotMembers.userID#, '1', '1')>
        </CFLOOP>
	</cfcase>
	<cfcase value="deletePlan">
		<!--- remove the plan from the table, then set all agents DIY membership values to zero --->
		<cfquery name="qDelete" datasource="#request.dsn#">
			delete from brokerage_express_signup
			where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.brokerageid#">
		</cfquery>
		<!--- delete any custom pricing associated with the brokerage --->
		<cfquery name="qDelete" datasource="#request.dsn#">
			delete from pricing_brokers_service
			where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.brokerageid#">
			and service_id = 2
		</cfquery>

		<CFQUERY name = "qNotMembers" datasource="#request.dsn#" >
        	SELECT userID FROM users WHERE brokerageid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageid#">
        </CFQUERY>
        <CFLOOP query="qNotMembers">
        	<CFSET iRet = members.SetMembersActive(#qNotMembers.userID#, '1', '0')>
        </CFLOOP>
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