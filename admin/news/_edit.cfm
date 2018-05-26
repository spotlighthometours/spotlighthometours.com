<!--- :: set default field values for a user add :: --->
<cfparam name="editMode" default="false">
<cfparam name="qTableData.display" default="false">
<cfquery name="qTblDesc" datasource="#request.dsn#">
	DESCRIBE news
</cfquery>
<cfloop query="qTblDesc">
	<cfparam name="qTableData.#field#" default="">
</cfloop>

<cfif isDefined('url.uid')>
	<cfquery name="qTableData" datasource="#request.dsn#">
		SELECT * FROM news WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.uid#" maxlength="6">
	</cfquery>
	<cfset editMode = true>
</cfif>

<cfoutput>
<form onsubmit="return confirmFeatured(this);" enctype="multipart/form-data" action="index.cfm?action=<cfif editMode>update<cfelse>insert</cfif>" method="post">
<table class="form">
<tr>
	<th>Title</th>
	<td><input type="text" name="title" value="#qTableData.title#" size="75" maxlength="100" /></td>
</tr>
<tr>
	<th>Display</th>
	<td><input type="checkbox" name="display" value="1"<cfif qTableData.display> checked</cfif> /></td>
</tr>
<tr>
	<th>Body</th>
	<td><textarea name="body" style="width:80%; height:5em;">#qTableData.body#</textarea></td>
</tr>
<tr>
	<th><input type="hidden" name="id" value="#qTableData.id#"></th>
	<td><input type="submit" value="Save Changes" /></td>
</tr>
</table>
</form>
</cfoutput>