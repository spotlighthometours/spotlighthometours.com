<cfsilent>
	<cfparam name="url.msg" default="" />
	<!--- display all current brokerage plans --->
	<cfquery name="qPlans" datasource="#request.dsn#">
		select
		k.id,
		k.keyword,
		k.800Number,
		k.createdOn,
		concat(k.firstname,' ',k.lastname) as name,
		p.name as provider
		from listhub_keywords k join listhub_keywords_to_providers ktp
			on k.id = ktp.keyword_fk
		join listhub_providers p
			on ktp.provider_fk = p.id
		order by name asc
	</cfquery>
</cfsilent>
<script type="text/javascript">
	<!--
	function confirmDelete(strName,strID) {
		var answer = confirm('Are you sure you want to remove the keyword for ' + strName + '?');
		if(answer) {
			window.location = "?action=deleteKeyword&Keyword=" + strID;
		}
	}
	-->
</script>
<h3>Current Spotlight Preview Keyword Users</h3>
<cfif url.msg neq "">
	<div id="msg"><cfoutput>#url.msg#</cfoutput></div>
</cfif>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Name</th>
		<th>Keyword</th>
		<th>800 #</th>
		<th>MLS Providers</th>
		<th>Created On</th>
		<th>Actions</th>
	</tr>
	<cfset strTemp ="">
	<cfset bZebra = 0 />
	<cfif qPlans.RecordCount eq 0>
		<tr>
			<td colspan="5">There are no keywords currently configured.</td>
		</tr>
	<cfelse>
		<cfset i = 0 />
		<cfoutput query="qPlans" group="name">
			<cfset i = i + 1 />
			<tr style="background-color:###IIF(i mod 2,DE('fff'),DE('f3f3ff'))#;">
				<td>#qPlans.name#</td>
				<td>#qPlans.keyword#</td>
				<td>#qPlans.800number#</td>
				<td>
					<cfset aProviders = ArrayNew(1) />
					<cfoutput>
						<cfset ArrayAppend(aProviders,qPlans.provider) />
					</cfoutput>
					#ReReplace(ArrayToList(aProviders),",",", ","ALL")#
				</td>
				<td>#DateFormat(qPlans.createdOn,"mmm dd, yy")#</td>
				<td>
					<a href="?pg=addPlan&keyword=#qPlans.id#">Update</a>
					<cfif qPlans.keyword neq "default">
					<a href="##" onclick="confirmDelete('#qPlans.name#','#qPlans.id#');return false;">Delete</a>
					<cfelse>
					<span title="Can't delete the default keyword.">Delete</span>
					</cfif>
				</td>
			</tr>
		</cfoutput>
	</cfif>
</table>
