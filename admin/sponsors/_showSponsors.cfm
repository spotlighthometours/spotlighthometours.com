<cfsilent>
	<cfparam name="url.msg" default="" />
	<!--- display all current brokerage plans --->
	<cfquery name="qSponsors" datasource="#request.dsn#">
		select
        s.id,
		s.Name,
		(Select Name from sponsorMain WHERE id=s.mainSponsorId limit 1) as mainSponsor,
        (Select count(userID) from sponsor_officers WHERE SponsorId=s.id) as officerCount,
        (Select count(id) from lonewolf_keywords WHERE SponsorId=s.id) as keywordCount
		from sponsorSub s
        order by s.id desc
		
	</cfquery>
</cfsilent>
<script type="text/javascript">
	<!--
	function confirmDelete(strName,strID) {
		var answer = confirm('Are you sure you want to Inactivate '+strName+'?');
		if(answer) {
			window.location = "?action=inativateSponsor&id=" + strID;
		}
	}
	-->
</script>
<h3>Current Sponsors</h3>
<cfif url.msg neq "">
	<div id="msg"><cfoutput>#url.msg#</cfoutput></div>
</cfif>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		
		<th>Sponsor</th>
        <th>Main Sponsor</th>
        <th>Keyword Count</th>
        <th>Officer Count</th>
        <th></th>
	</tr>
	<cfset strTemp ="">
	<cfset bZebra = 0 />
	<cfif qSponsors.RecordCount eq 0>
		<tr>
			<td colspan="5">There are no Sponsors currently configured.</td>
		</tr>
	<cfelse>
		<cfset i = 0 />
		<cfoutput query="qSponsors" group="Name">
			<cfset i = i + 1 />
			<tr style="background-color:###IIF(i mod 2,DE('fff'),DE('f3f3ff'))#;">
				<td>#qSponsors.Name#</td>
				<td>#qSponsors.mainSponsor#</td>
                <td>#qSponsors.keywordCount#</td>
                <td>#qSponsors.officerCount#</td>
				<td>
					<a href="?pg=addSponsor&id=#qSponsors.id#">Update/Sponsor Keyword</a>
                    <a href="?pg=addBanner&id=#qSponsors.id#">Banner Select</a>
                    <a href="?pg=showSponsorOfficers&id=#qSponsors.id#">Add/Edit Officers</a>
                                    			
					<a href="javascript:void(0)" onclick="confirmDelete('#qSponsors.Name#','#qSponsors.id#');return false;">Inactivate</a>
					
				</td>
			</tr>
		</cfoutput>
	</cfif>
</table>
