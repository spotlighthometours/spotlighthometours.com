<!--- :: set default field values for a user add :: --->
<CFPARAM name="editMode" default="false">


<CFIF isDefined('url.themeid')>
	<CFQUERY name="qTableData" datasource="#request.dsn#">
		SELECT * FROM lonewolf_keyword_theme_types  WHERE themeid = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.themeid#" maxlength="6">
	</CFQUERY>
	<CFSET editMode = true>
</CFIF>
<CFOUTPUT>
<form onsubmit="return confirmFeatured(this);" enctype="multipart/form-data" action="index.cfm?action=<cfif editMode>updateTheme<cfelse>insertTheme</cfif>" method="post">
<table class="form">
<tr>
	<th>Name</th>
	<td><input type="text" name="themename" value="<cfif editMode>#qTableData.themename#</cfif>" size="15" maxlength="45" /></td>
</tr>
<tr>
	<th>active</th>
	<td><label>
	  <input type="checkbox" name="themeactive" id="themeactive"  <cfif editMode><cfif qTableData.active eq '1'>checked="checked"</cfif></cfif>/>
	</label></td>
</tr>

<tr>
	<th><input type="hidden" name="themeid" value="<cfif editMode>#qTableData.themeid#</cfif>"></th>
	<td><input type="submit" value="Save Changes" /></td>
</tr>
</table>
</form>
</CFOUTPUT>