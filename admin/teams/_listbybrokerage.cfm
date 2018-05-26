<cfsilent>
	<cfparam name="msg" default="" />
	<cfquery name="qteams" datasource="#request.db.dsn#">
		select t.userid, t.username, t.createdon, t.contactRight, t.password, b.brokerageName, b.brokerageID,b.brokerageDesc
		from teams t join teams_to_brokerages tb on t.userid = tb.team_id
		join brokerages b on tb.brokerage_id = b.brokerageID
		order by t.userid ASC
	</cfquery>

	<cfquery name="qUniqueTeams" dbtype="query">
		select distinct brokerageID, brokerageName,brokerageDesc
		from qteams 
		order by brokerageName ASC
	</cfquery>
</cfsilent><html>
<head>
<title>Teams</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<link href="tabs.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
	<!--
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this team?"))
		return false;
}
	-->
</script>
</head>

<body id="tab2">
<cfinclude template="tabs.cfm">	
<cfoutput>
<div class="msg">#msg#</div>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
	  <th>Brokerages</th>
	  <th>Associated Logins</th>
	</tr>
	<cfloop query="qUniqueTeams">
		<!--- get entries for this team --->
		<cfquery name="qLogins" dbtype="query">
			select distinct username, userid, password
			from qteams
			where brokerageid = #qUniqueTeams.brokerageid#
		</cfquery>
		<tr bgcolor="###iif(qUniqueTeams.currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
			<td valign="top">#qUniqueTeams.brokerageName# - #qUniqueTeams.brokerageDesc#</td>
			<td valign="top">
				<cfset lTemp = ArrayToList(qLogins['username']) />
				<cfloop index="j" from="1" to="#qLogins.Recordcount#">
					<div>
						<div style="float:right;width:100px;">
							<a href="team.cfm?pg=editteam&team=#qLogins['userid'][j]#" style="margin-right:5px;">Edit</a>
							<a onClick="return confirmDelete();" href="team.cfm?action=deleteTeam&team=#qLogins['userid'][j]#">Delete</a>
						</div>
						<a href="/teams/users.cfm?action=login&username=#qLogins.username#&password=#qLogins.password#">#qLogins['username'][j]#</a> 
					</div>
				</cfloop>
			</td>
		</tr>
	</cfloop>
</table>
</cfoutput>
</body>
</html>
