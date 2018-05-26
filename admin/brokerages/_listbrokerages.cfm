<cfparam name="url.orderby" default="brokerageName">
<cfquery name="qBrokerages" datasource="#request.db.dsn#">
	select b.*, s.fullName as salesRep,brokerageDesc
	from brokerages b left outer join salesReps s on b.salesRepID = s.salesRepID
	order by #url.orderby#
</cfquery>
<html>
<head>
<title>Brokerages</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this brokerage?"))
		return false;
}
</script>
</head>

<body>
<cfoutput>
<div class="msg">#msg#</div>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
  <th width="5%"><a href="#cgi.script_name#?orderby=brokerageID<cfif url.orderby eq "brokerageID">%20desc</cfif>">BrokerageID</a></th>
  <th width="45%"><a href="#cgi.script_name#?orderby=brokerageName<cfif url.orderby eq "brokerageName">%20desc</cfif>">Name</a></th>
  <th width="17.5%"><a href="#cgi.script_name#?orderby=brokerageDesc<cfif url.orderby eq "brokerageDesc">%20desc</cfif>">Desc</a></th>
  <th width="17.5%"><a href="#cgi.script_name#?orderby=salesRep<cfif url.orderby eq "salesRep">%20desc</cfif>">SalesRep</a></th>
  <th width="5%">&nbsp;</th>
  <cfloop query="qBrokerages">
	  <tr bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <td>#brokerageID#</td>
		 <td><a href="#cgi.script_name#?pg=editBrokerage&brokerage=#brokerageID#">#brokerageName#</a></td>
		<td><cfif len(brokerageDesc)>#brokerageDesc#<cfelse>--</cfif></td>
        <td><cfif len(salesRep)>#salesRep#<cfelse>Unassigned</cfif></td>
		<td><a onClick="return confirmDelete();" href="#cgi.script_name#?action=deleteBrokerage&brokerage=#brokerageID#">delete</a></td>
	  </tr>
  </cfloop>
</table>
</cfoutput>
</body>
</html>
