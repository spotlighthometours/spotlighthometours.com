<cfsilent>
	<cfparam name="msg" default="" />
	<cfquery name="qteams" datasource="#request.db.dsn#">
		select t.userid, t.username, t.createdon, t.contactRight, b.brokerageName, t.password
		from teams t 
        join teams_to_brokerages tb on t.userid = tb.team_id
		join brokerages b on tb.brokerage_id = b.brokerageID
		order by t.username ASC
	</cfquery>
	
	<!--- query of queries are case sensitive, note the useage of upper() function for ordering --->
	<cfquery name="qUniqueTeams" dbtype="query">
		select count(brokerageName), username, upper(username) as UUsername, userid, contactRight, password
		from qteams 
		group by username,UUsername,userid,contactRight,password
		order by UUsername ASC
	</cfquery>
</cfsilent><html>
<head>
<title>Teams</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<link href="tabs.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../../repository_inc/template.js?rand=rand(99999,79999)"></script>
<script type="text/javascript">
	<!--
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this team?"))
		return false;
}
	-->
</script>
</head>

<body id="tab1">
<cfinclude template="tabs.cfm">	
<cfoutput>
<div class="msg">#msg#</div>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
	  <th>Team Login(s)</th>
	  <th>Associated Brokerages</th>
		<th>Contact Right?</th>
		<th>Actions</th>
	</tr>
	<cfloop query="qUniqueTeams">
		<!--- get entries for this team --->
		<cfquery name="qBrokerages" dbtype="query">
			select distinct brokeragename
			from qteams
			where userid = #qUniqueTeams.userid#
		</cfquery>
		<tr bgcolor="###iif(qUniqueTeams.currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
			<td valign="top"><a href="/teams/users.cfm?action=login&username=#qUniqueTeams.username#&password=#qUniqueTeams.password#">
            	#qUniqueTeams.username#</a>
            </td>
			<td valign="top">#ArrayToList(qBrokerages['brokeragename'],"<br />")#</td>
			<td valign="top"><cfif qUniqueTeams.ContactRight eq 1>Yes<cfelse>No</cfif></td>
			<td valign="top">
				<a href="team.cfm?pg=editteam&team=#qUniqueTeams.userid#" style="margin-right:5px;">Edit</a> 
				<a onClick="return confirmDelete();" href="team.cfm?action=deleteTeam&team=#qUniqueTeams.userid#">Delete</a>
			</td>
		</tr>
	</cfloop>
</table>
</cfoutput>
</body>
</html>
