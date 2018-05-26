<cfsilent>
	<cfparam name="url.msg" default="" />
	<!--- display all current brokerage plans --->
	<cfquery name="qPlans" datasource="#request.dsn#">
		select
        b.id,
		b.bannerName,
		b.bannerImage
		from sponsor_banners b
        order by b.id desc
		
	</cfquery>
</cfsilent>
<script type="text/javascript">
	<!--
	function confirmDelete(strName,strID) {
		var answer = confirm('Are you sure you want to remove the Banner '+strName+'?');
		if(answer) {
			window.location = "?action=deleteBanner&id=" + strID;
		}
	}
	-->
</script>
<h3>Current Banners</h3>
<cfif url.msg neq "">
	<div id="msg"><cfoutput>#url.msg#</cfoutput></div>
</cfif>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Name</th>		
		<th>Banners</th>
	</tr>
	<cfset strTemp ="">
	<cfset bZebra = 0 />
	<cfif qPlans.RecordCount eq 0>
		<tr>
			<td colspan="5">There are no keywords currently configured.</td>
		</tr>
	<cfelse>
		<cfset i = 0 />
		<cfoutput query="qPlans" group="bannerName">
			<cfset i = i + 1 />
			<tr style="background-color:###IIF(i mod 2,DE('fff'),DE('f3f3ff'))#;">
				<td>#qPlans.bannerName#</td>
				<td><img src="/images/bannerImages/#qPlans.bannerImage#" border="0"  /></td>
				<td>
					<a href="?pg=addBanner&id=#qPlans.id#">Update</a>
					
					<a href="javascript:void(0)" onclick="confirmDelete('#qPlans.bannerName#','#qPlans.id#');return false;">Delete</a>
					
				</td>
			</tr>
		</cfoutput>
	</cfif>
</table>
