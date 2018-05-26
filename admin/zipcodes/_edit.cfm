<!--- :: set default field values for a user add :: --->
<cfparam name="editMode" default="false">
<cfquery name="qTblDesc" datasource="#request.dsn#">
	DESCRIBE zipcodes
</cfquery>
<cfloop query="qTblDesc">
	<cfparam name="qTableData.#field#" default="">
</cfloop>

<cfif isDefined('url.uid')>
	<cfquery name="qTableData" datasource="#request.dsn#">
		SELECT * FROM zipcodes WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.uid#" maxlength="6">
	</cfquery>
	<cfset editMode = true>
</cfif>

<cfoutput>
<form onsubmit="return confirmFeatured(this);" enctype="multipart/form-data" action="index.cfm?action=<cfif editMode>update<cfelse>insert</cfif>" method="post">
<table class="form">
<tr>
	<th>Zip Code</th>
	<td><input type="text" name="zip" value="#qTableData.zip#" size="75" maxlength="7" /></td>
</tr>
<tr>
	<th><input type="hidden" name="id" value="#qTableData.id#"></th>
	<td><input type="submit" value="Save Changes" /></td>
</tr>
</table>
</form>
</cfoutput>