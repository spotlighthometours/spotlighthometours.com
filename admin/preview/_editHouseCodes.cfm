<cfsilent>
	<cfquery name="qDefaults" datasource="#request.dsn#">
		select name, string from settings
		where name = 'Housecode-start'
		or name = 'Housecode-end'
	</cfquery>
	<cfloop query="qDefaults">
		<cfif qDefaults.name eq 'Housecode-start'>
			<cfset sStart = qDefaults.string />
		<cfelseif qDefaults.name eq 'Housecode-end'>
			<cfset sEnd = qDefaults.string />
		</cfif>
	</cfloop>
</cfsilent>
<cfoutput>
<h3>House Code Management</h3>
<p>Enter the beginning and ending numbers that exist on the printed signage and given to agents.</p>
<p>It is up to the agents to see that these codes are assigned properly in the agent's mobile preview management.</p>
<form name="agentCheck" action="index.cfm?action=UpdateMobileRange" method="post">
	<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Start Number</th>
		<th>End Number</th>
	</tr>
	<tr>
		<td><input type="text" name="start" value="#sStart#"></td>
		<td><input type="text" name="end" value="#sEnd#"/></td>
	</tr>
	<tr><td>&nbsp;</td><td><input type="submit" value="Update" /></td></tr>
</table>
</form>
</cfoutput> 