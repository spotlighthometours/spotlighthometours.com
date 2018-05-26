<cfsilent>
	<cfquery name="qTourTypes" datasource="#request.db.dsn#">
		select tourTypeID, tourTypeName, unitPrice
		from tourTypes
		order by tourTypeName ASC
	</cfquery>

	<cfquery name="qTable" datasource="#request.db.dsn#">
	 SELECT * FROM zipcodes ORDER BY zip
	</cfquery>

	<cfquery name="qZipPricing" datasource="#request.db.dsn#">
		select id, zip_id_fk, tour_id_fk, price
		from pricing_zip
	</cfquery>
</cfsilent>

<cfoutput>
<script type="text/javascript">
function confirmDelete() {
	if(!confirm('Are you sure you want to delete this zipcode?')) return false;
}
</script>
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

<h3>Supported Zip Codes with Pricing</h3>
<table class="tabular">
<tr>
	<th style="text-align:center;">ID</th>
	<th>Zip Code</th>
	<cfloop query="qTourTypes">
		<th>#qTourTypes.tourTypeName#</th>
	</cfloop>
	<th>Actions</th>
</tr>
<cfloop query="qTable">
<tr <cfif currentRow mod 2> class="alt"</cfif> >
	<td style="text-align:center;">#id#</td>
	<td><a href="?pg=edit&uid=#id#" title="Edit zipcode">#zip#</a></td>
	<cfloop index="i" from="1" to="#qTourTypes.RecordCount#">
		<td>
			<cfquery name="qTemp" dbtype="query">
				select price
				from qZipPricing
				where zip_id_fk = #qTable.zip#
				and tour_id_fk = #qTourTypes['tourTypeID'][i]#
			</cfquery>
			<cfif qTemp.RecordCount gt 0>
				#dollarFormat(qTemp.Price)#
			<cfelse>
				-
			</cfif>
		</td>
	</cfloop>
	<td align="center"><a href="?action=delete&uid=#qTable.id#" title="Delete zipcode" onclick="return confirmDelete();">remove</a>
		<a href="?pg=customprice&uid=#qTable.id#">Update</a>
	</td>
</tr>
</cfloop>
</table>
</cfoutput>