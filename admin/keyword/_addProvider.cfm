<cfsilent>
	<cfparam name="url.msg" default="" />
	<!--- combine, with a left join, all keyword info recorded with the keyword info present --->
	<cfquery name="qPlans" datasource="#request.dsn#">
		select
		a.id,
		a.name,
		a.description,
		a.region,
		p.mls_service
		from
		( select distinct mls_service
			from listhub_property ) p
		left join
		listhub_providers a
		on p.mls_service = a.mls_service
		order by p.mls_service asc
	</cfquery>
</cfsilent>
<h3>Current Spotlight Preview Keyword Providers</h3>
<cfif url.msg neq "">
	<cfoutput><div id="msg">#url.msg#</div></cfoutput>
</cfif>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Name</th>
		<th>Abbr</th>
		<th>Description</th>
		<th>Region</th>
		<th>Actions</th>
	</tr>
	<cfset strTemp ="">
	<cfset bZebra = 0 />
	<cfif qPlans.RecordCount eq 0>
		<tr>
			<td colspan="5">There are no providers currently configured.</td>
		</tr>
	<cfelse>
		<cfoutput query="qPlans">
			<tr style="background-color:###IIF(qPlans.CurrentRow mod 2,DE('fff'),DE('f3f3ff'))#;">
				<td>#qPlans.name#</td>
				<td>
					#qPlans.mls_service#
					<cfif qPlans.id eq "">
					*
					</cfif>
				</td>
				<td>#qPlans.description#</td>
				<td>#qPlans.region#</td>
				<td><a href="?pg=editProvider&provider=#qPlans.mls_service#">Update</a></td>
			</tr>
		</cfoutput>
	</cfif>
</table>

<div style="margin-top:30px;">*Note that a Provider can't be assigned to a keyword/800# pair until at least a name has been defined for it.</div>


