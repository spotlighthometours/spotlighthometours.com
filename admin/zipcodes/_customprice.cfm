<cfsilent>
	<cfquery name="qTourTypes" datasource="#request.db.dsn#">
		select tourTypeID, tourTypeName, unitPrice
		from tourTypes
		order by tourTypeName ASC
	</cfquery>

	<cfquery name="qTable" datasource="#request.db.dsn#">
	 SELECT id,zip FROM zipcodes 
	 where id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.uid#" />
	</cfquery>

	<cfquery name="qZipPricing" datasource="#request.db.dsn#">
		select id, zip_id_fk, tour_id_fk, price
		from pricing_zip
		where zip_id_fk = <cfqueryparam cfsqltype="cf_sql_integer" value="#qTable.zip#">
	</cfquery>

</cfsilent>

<cfoutput>
<h3>Default Tour Pricing</h3>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<cfloop query="qTourTypes">
			<th>#qTourTypes.tourTypeName#</th>
		</cfloop>
	</tr>
	<tr>
		<cfloop query="qTourTypes">
			<td>#dollarFormat(qTourTypes.unitPrice)#</td>
		</cfloop>
	</tr>
</table>

<h3>Custom Pricing for #qTable.zip#</h3>
(Leave blank to include the default pricing.)
<table>
	<tr>
		<th>Tour Name</th>
		<th>Pricing</th>
	</tr>
<form action="index.cfm?action=changeprice" method="post">
	<input type="hidden" name="zip" value="#qTable.zip#">
	<cfloop query="qTourtypes">
		<tr>
			<td>#qTourtypes.tourTypeName#</td>
			<cfquery name="qTemp" dbtype="query">
				select id, zip_id_fk, tour_id_fk, price
				from qZipPricing
				where tour_id_fk = #qTourTypes.tourtypeID#
			</cfquery>
			<cfif qTemp.RecordCount eq 0>
				<cfset nPrice = "" />
			<cfelse>
				<cfset nPrice = qTemp.price />
			</cfif>
			<td><input type="text" name="tour_#qTourtypes.tourTypeID#" value="#nPrice#" /></td>
		</tr>
	</cfloop>
	<tr>
		<td colspan="2"><input type="submit" value="Submit" /></td>
	</tr>
</form>
</table>
</cfoutput>