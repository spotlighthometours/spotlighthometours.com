<!--- :: set default field values for a user add :: --->
<cfparam name="editMode" default="false">
<cfquery name="qTblDesc" datasource="#request.dsn#">
	DESCRIBE themes
</cfquery>
<cfloop query="qTblDesc">
	<cfparam name="qTableData.#field#" default="">
</cfloop>

<cfif isDefined('url.uid')>
	<cfquery name="qTableData" datasource="#request.dsn#">
		SELECT * FROM themes WHERE id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.uid#" maxlength="6">
	</cfquery>
	<cfset editMode = true>
</cfif>
<cfoutput>
<p>All values are HEX values. You can find these in the Photoshop color picker.</p>
<form onsubmit="return confirmFeatured(this);" enctype="multipart/form-data" action="index.cfm?action=<cfif editMode>update<cfelse>insert</cfif>" method="post">
<table class="form">
<tr>
	<th>Name</th>
	<td><input type="text" name="name" value="#qTableData.name#" size="15" maxlength="45" /></td>
</tr>
<tr>
	<th>background_color</th>
	<td><input type="text" name="background_color" value="#qTableData.background_color#" size="15" maxlength="6" /></td>
</tr>
<tr>
	<th>agent_details_text_color</th>
	<td><input type="text" name="agent_details_text_color" value="#qTableData.agent_details_text_color#" size="15" maxlength="6" /></td>
</tr>
<tr>
	<th>tour_info_text_color</th>
	<td><input type="text" name="tour_info_text_color" value="#qTableData.tour_info_text_color#" size="15" maxlength="6" /></td>
</tr>
<tr>
	<th>media_text_color</th>
	<td><input type="text" name="media_text_color" value="#qTableData.media_text_color#" size="15" maxlength="6" /></td>
</tr>
<tr>
	<th>tab_text_color</th>
	<td><input type="text" name="tab_text_color" value="#qTableData.tab_text_color#" size="15" maxlength="6" /></td>
</tr>
<tr>
	<th>tour_panel_background_color</th>
	<td><input type="text" name="tour_panel_background_color" value="#qTableData.tour_panel_background_color#" size="15" maxlength="6" /></td>
</tr>
<tr>
	<th>media_panel_background_color</th>
	<td><input type="text" name="media_panel_background_color" value="#qTableData.media_panel_background_color#" size="15" maxlength="6" /></td>
</tr>
<tr>
	<th>tab_background_color</th>
	<td><input type="text" name="tab_background_color" value="#qTableData.tab_background_color#" size="15" maxlength="6" /></td>
</tr>
<tr>
	<th>tab_inactive_color</th>
	<td><input type="text" name="tab_inactive_color" value="#qTableData.tab_inactive_color#" size="15" maxlength="6" /></td>
</tr>
<tr>
	<th><input type="hidden" name="id" value="#qTableData.id#"></th>
	<td><input type="submit" value="Save Changes" /></td>
</tr>
</table>
</form>
</cfoutput>