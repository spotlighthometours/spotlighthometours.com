<CFSILENT>
	<CFPARAM name="url.msg" default="" />
	<!--- display all current brokerage plans --->
	<CFQUERY name="qPlans" datasource="#request.dsn#">
		select
		k.id,
		k.keyword,
		k.800Number,
		k.createdOn,
        k.shortcode,
        k.exclusive,
        k.notification,
        k.notifycarrier as carrier,
		concat(k.firstname,' ',k.lastname) as name 
		from lonewolf_keywords k 
		order by shortcode desc,name asc
	</CFQUERY>
</CFSILENT>
<script type="text/javascript">
	<!--
	function confirmDelete(strName,strID) {
		var answer = confirm('Are you sure you want to remove the keyword for ' + strName + '?');
		if(answer) {
			window.location = "?action=deletelonewolfKeyword&Keyword=" + strID;
		}
	}
	-->
</script>
<h3>Current Lone Wolf Mobile Keyword Users</h3>
<CFIF url.msg neq "">
	<div id="msg"><CFOUTPUT>#url.msg#</CFOUTPUT></div>
</CFIF>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Name</th>
		<th>Keyword</th>
        <th>Shortcode</th>
        <th>Exclusive</th>
        <th>Notification</th>
		<th>CARRIER</th>
        <th>800 #</th>
		
		<th>Created On</th>
		<th>Actions</th>
	</tr>
	<CFSET strTemp ="">
	<CFSET bZebra = 0 />
	<CFIF qPlans.RecordCount eq 0>
		<tr>
			<td colspan="5">There are no keywords currently configured.</td>
		</tr>
	<CFELSE>
		<CFSET i = 0 />
		<CFOUTPUT query="qPlans" >
			<CFSET i = i + 1 />
			<tr style="background-color:###IIF(i mod 2,DE('fff'),DE('f3f3ff'))#;">
				<td>#qPlans.name#</td>
				<td>#qPlans.keyword#</td>
                <td>#qPlans.shortcode#</td>
                <td><CFIF qPlans.exclusive eq 1><font color="##006633">Yes</font><CFELSE><font color="##FF0000">No</font></CFIF></td>
                <td><CFIF qPlans.notification eq 1><font color="##006633">Yes</font><CFELSE><font color="##FF0000">No</font></CFIF></td>
				<td>#qPlans.carrier#</td>
                <td>#qPlans.800number#</td>
			
				<td>#DateFormat(qPlans.createdOn,"mmm dd, yy")#</td>
				<td>
					<a href="?pg=addlonewolfPlan&keyword=#qPlans.id#">Update</a>
					<CFIF qPlans.keyword neq "default">
					<a href="##" onclick="confirmDelete('#qPlans.name#','#qPlans.id#');return false;">Delete</a>
					<CFELSE>
					<span title="Can't delete the default keyword.">Delete</span>
					</CFIF>
				</td>
			</tr>
		</CFOUTPUT>
	</CFIF>
</table>
