<cfif isDefined("form.adminUsername")>
	<cfquery name="administrators" datasource="#request.db.dsn#" username="" password="">
	 SELECT administratorID, permissions FROM administrators
	 WHERE username = <cfqueryparam value="#form.adminUsername#" cfsqltype="CF_SQL_VARCHAR" maxlength="10">
	 AND password   = <cfqueryparam value="#form.password#" cfsqltype="CF_SQL_VARCHAR" maxlength="12">
	</cfquery>
	<cfif administrators.recordCount>
		<cflock scope="session" type="exclusive" timeout="30">
			<cfset session.administratorID = administrators.administratorID>
			<cfset session.adminPermissions = administrators.permissions>
		</cflock>
	<cfelse>
		<cflocation url="login.cfm?invalid=" addtoken="no">
	</cfif>
</cfif>