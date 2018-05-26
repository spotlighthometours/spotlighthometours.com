<CFQUERY name="qTable" datasource="#request.dsn#">
 SELECT * FROM lonewolf_keyword_theme_types ORDER BY themeid
</CFQUERY>
<CFOUTPUT>
<script type="text/javascript">
function confirmDelete() {
	if(!confirm('Are you sure you want to delete this theme?')) return false;
}
</script>
<table class="tabular">
<tr>
	<th style="text-align:center;">ID</th>
	<th>Theme Name</th>
    <th>Specifications</th>
    <th>Active</th>
	<th width="5%"></th>
</tr>
<CFLOOP query="qTable">
<tr<cfif currentRow mod 2> class="alt"</cfif>>
	<td style="text-align:center;">#themeid#</td>
	<td><a href="?pg=edit&themeid=#themeid#" title="Edit Theme">#themename#</a></td>
    <td><a href="?pg=editspedifications&themeid=#themeid#" title="Edit Theme">Edit</a></td>
    <td><a href="?pg=edit&themeid=#themeid#" title="Edit zipcode">#active#</a></td>

	<td align="center"><a href="?action=delete&themeid=#themeid#" title="Delete zipcode" onclick="return confirmDelete();">remove</a></td>
</tr>
</CFLOOP>
</table>
</CFOUTPUT>