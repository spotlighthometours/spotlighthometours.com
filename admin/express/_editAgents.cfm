<cfsilent>
	<cfparam name="url.brokerageID" default="0" />
	<!--- get the agents associated with the brokerage --->
	<cfquery name="qAgents" datasource="#request.dsn#">
		select u.userid, u.firstname, u.lastname, 
        case when isnull(m.active) then 0 else m.active end as DIYActive, b.brokerageName
		from users u join brokerages b on u.brokerageid = b.brokerageID
             left outer join members m on u.userid = m.userID AND m.typeID = 1
		where u.brokerageID = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.brokerageID#" />
	</cfquery>
</cfsilent>
<cfoutput>
<script type="text/javascript">
	<!--
		function checkAll() {
			/* loop through and set all checkboxes to 'checked' */
			if(document.getElementById('mastercheck').checked == true) {
				for(var i=0; i<document.agentCheck.elements.length; i++) {
					if(document.agentCheck.elements[i].type=="checkbox") {
						document.agentCheck.elements[i].checked=true;
					}
				}
			}

		}
	-->
</script>
<h3>Agent Mobile Preview Management for #qAgents.brokerageName#</h3>
<form name="agentCheck" action="index.cfm?action=UpdateAgents" method="post">
	<input type="hidden" name="brokerageID" value="#url.brokerageID#" />
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Name</th>
		<th>Select All <input type="checkbox" onclick="checkAll();" id="mastercheck"></th>
	</tr>
	<cfloop query="qAgents">
		<tr bgcolor="###iif(qAgents.CurrentRow mod 2, de("E8EEF7"), de("ffffff"))#">
			<td>#qAgents.firstname# #qAgents.lastname#</td>
			<td><input type="checkbox" name="agent_#qAgents.userid#" <cfif qAgents.DIYActive eq 1>checked</cfif> /></td>
		</tr>
	</cfloop>
	<tr><td>&nbsp;</td><td><input type="submit" value="Update" /></td></tr>
</table>
</form>
</cfoutput>