<cfsilent>
	<cfparam name="url.provider" default="" />
	<cfparam name="url.msg" default="" />

	<!--- combine, with a left join, all keyword info recorded with the keyword info present --->
	<cfquery name="qPlans" datasource="#request.dsn#">
		select
		a.id,
		a.name,
		a.description,
        a.mls_service,
		a.region
		from
		lonewolf_providers a
    	where a.mls_service = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.provider#" />
	</cfquery>

</cfsilent>
<cfoutput>
	<cfif url.msg neq "">
		<div id="msg">#url.msg#</div>
	</cfif>
	<cfif qPlans.RecordCount eq 1>
		<form action="?action=EditLonewolfProvider" method="post">
			<input type="hidden" name="id" value="#qPlans.id#">
		<table width="500" border="0" cellspacing="2" cellpadding="4">
			<tr>
				<td class="rowHead">Name*</td>
				<td class="rowData"><input type="text" name="Name" value="#qPlans.name#"></td>
			</tr>
			<tr>
				<td class="rowHead">Abbr*</td>
				<td><input type="text" name="mls_service" value="#qPlans.mls_service#" readonly="true" style="background-color:##ccc;"></td>
			</tr>
			<tr>
				<td class="rowHead">Description</td>
				<td class="rowData"><input type="text" name="description" value="#qPlans.description#"></td>
			</tr>
			<tr>
				<td class="rowHead">Region</td>
				<td><input type="text" name="region" value="#qPlans.region#"></td>
			</tr>
			<tr>
				<td class="rowHead">&nbsp;</td>
				<td class="rowData">
					<input type="submit" value="Update" />
				</td>
			</tr>
		</table>
		</form>
		<div style="margin-top:30px;">
			* Name and Abbr are required fields
		</div>
	<cfelse>
		There are no providers found! Please <a href="?pg=addProvider">go back</a> and try again.
	</cfif>
</cfoutput>