<CFSILENT>
	<CFPARAM name="url.msg" default="" />
	<!--- display all current brokerage plans --->
	<CFQUERY name="qPlans" datasource="#request.dsn#">
		select
        b.id,
		b.bannerName,
		b.bannerImage
		from sponsor_banners b
        order by b.id desc
		
	</CFQUERY>
</CFSILENT>
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
<CFIF url.msg neq "">
	<div id="msg"><CFOUTPUT>#url.msg#</CFOUTPUT></div>
</CFIF>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th width="15%">Name</th>
		<th width="50%">Banners</th>
        <th width="10%"></th>
	</tr>
	<CFSET strTemp ="">
	<CFSET bZebra = 0 />
	<CFIF qPlans.RecordCount eq 0>
		<tr>
			<td colspan="5">There are no keywords currently configured.</td>
		</tr>
	<CFELSE>
		<CFSET i = 0 />
		<CFOUTPUT query="qPlans" group="bannerName">
			<CFSET i = i + 1 />
			<tr style="background-color:###IIF(i mod 2,DE('fff'),DE('f3f3ff'))#;">
				<td>#qPlans.bannerName#</td>
               
				<td><img src="http://www.spotlightpreview.com/images/bannerImages/#qPlans.bannerImage#" border="0"  /></td>
				<td>
					<a href="?pg=addBanner&id=#qPlans.id#">Update</a>
					
					<a href="javascript:void(0)" onclick="confirmDelete('#qPlans.bannerName#','#qPlans.id#');return false;">Delete</a>
					
				</td>
			</tr>
		</CFOUTPUT>
	</CFIF>
</table>
