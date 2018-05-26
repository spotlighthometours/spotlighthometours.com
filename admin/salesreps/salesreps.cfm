<cfparam name="url.action" default="">
<cfparam name="msg" default="">
<cfparam name="url.pg" default="listreps">

<cfswitch expression="#url.action#">
	<cfcase value="insertRep">
		<cfquery datasource="#request.db.dsn#">
			insert into salesReps (fullName, email) values ('#trim(form.fullName)#','#trim(form.email)#')
		</cfquery>
		<cfset msg = "Sales Rep has been added successfully.">
	</cfcase>
	<cfcase value="updateRep">
		<cfquery datasource="#request.db.dsn#">
			update salesReps set
				fullname = '#trim(form.fullName)#',
				email = '#trim(form.email)#'
			where salesRepID = #form.salesRepID#
		</cfquery>
		<cfset msg = "Sales Rep has been updated successfully.">
	</cfcase>
	<cfcase value="deleterep">
		<cfquery datasource="#request.db.dsn#">
			delete from salesReps where salesRepID = #url.rep#
		</cfquery>
		<cfset msg = "Sales Rep has been removed successfully.">
	</cfcase>
</cfswitch>
<cfinclude template="_#url.pg#.cfm">