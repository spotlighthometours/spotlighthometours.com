<cfsilent>
<cfset editMode = iif(StructKeyExists(url,"team"), true, false)>
<cfif editMode>
	<cfquery name="qTeam" datasource="#request.db.dsn#">
		select * from teams where userid = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.team#" />
	</cfquery>
	<!--- need to get a list of all brokerages associated with this team --->
	<cfquery name="qTeamBrokerages" datasource="#request.db.dsn#">
		select brokerage_id
		from teams_to_brokerages
		where team_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.team#" />
	</cfquery>
	
	<cfset lUsedBrokerages = ArrayToList(qTeamBrokerages['brokerage_id']) />
</cfif>
	<cfquery name="qBrokerages" datasource="#request.db.dsn#">
		select brokerageID, brokerageName, brokerageDesc
		from brokerages
		order by brokerageName asc
	</cfquery>
</cfsilent>
<html>
<head>
<title>Edit Team</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<cfoutput>
<form action="#cgi.sript_name#?action=<cfif editMode>updateTeam<cfelse>insertTeam</cfif>" method="post">
    <table width="500" border="0" cellspacing="2" cellpadding="4">
      <tr> 
        <td class="rowHead">Team Login</td>
        <td class="rowData"><input name="username" type="text" size="32" maxlength="50"<cfif editMode> value="#qTeam.username#"</cfif>></td>
      </tr>
      <cfif editMode>
	  <tr> 
        <td class="rowHead">Current Password</td>
        <td class="rowData">#qTeam.password#</td>
      </tr>
	  </cfif>
      <tr> 
        <td class="rowHead"><cfif editMode>New Password<cfelse>Password</cfif></td>
        <td class="rowData"><input name="password" type="text" size="32" maxlength="32" /> <cfif editMode>(leave blank to keep the same)</cfif></td>
      </tr>
      <tr> 
        <td class="rowHead">Contact Right</td>
        <td class="rowData"><input name="contactright" type="checkbox" <cfif editMode and qTeam.contactRight> checked</cfif> /></td>
      </tr>
      <tr> 
        <td class="rowHead">Social Hub</td>
        <td class="rowData"><input name="socialHub" type="checkbox" <cfif editMode and qTeam.socialHub> checked</cfif> /></td>
      </tr>
	<tr> 
        <td class="rowHead">Brokerages</td>
        <td class="rowData">
				<select name="brokerage" multiple="true" size="30">
				<cfloop query="qBrokerages">
					<option value="#qBrokerages.brokerageid#"<cfif editMode and ListFind(lUsedBrokerages,qBrokerages.brokerageid)> selected</cfif>>#qBrokerages.brokerageName# <cfif len(trim(qBrokerages.BrokerageDesc)) gt 0>- #qBrokerages.BrokerageDesc#</cfif></option>
				</cfloop>
				</select>
				</td>
      </tr>
	  <tr> 
        <td class="rowHead">API Key</td>
        <td class="rowData"><cfif editMode> #qTeam.api_key#</cfif></td>
      </tr>
      <tr> 
        <td class="rowHead"><cfif editMode>
            <input type="hidden" name="userID" value="#qTeam.userID#">
          </cfif></td>
        <td class="rowData"><input type="submit" value="<cfif EditMode>Update<cfelse>Add</cfif> Team"></td>
      </tr>
    </table>
</form>
</cfoutput>
</body>
</html>
