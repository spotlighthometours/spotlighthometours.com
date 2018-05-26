<cfquery name="qTable" datasource="#request.dsn#">
 SELECT * FROM testimonials ORDER BY id
</cfquery>
<cfoutput>
<script type="text/javascript">
function confirmDelete() {
	if(!confirm('Are you sure you want to delete this testimonial?')) return false;
}
</script>
<table class="tabular">
<tr>
	<th style="text-align:center;">ID</th>
	<th>Author</th>
	<th width="5%"></th>
</tr>
<cfloop query="qTable">
<tr<cfif currentRow mod 2> class="alt"</cfif>>
	<td style="text-align:center;">#id#</td>
	<td><a href="?pg=edit&uid=#id#" title="Edit testimonial">#name#</a></td>

	<td align="center"><a href="?action=delete&uid=#id#" title="Delete testimonial" onclick="return confirmDelete();">remove</a></td>
</tr>
</cfloop>
</table>
</cfoutput>