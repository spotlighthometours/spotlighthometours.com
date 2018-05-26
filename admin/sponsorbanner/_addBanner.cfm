<CFSILENT>
	<CFPARAM name="url.id" default="" />

	<!--- only get those brokerages not currently assigned to a plan --->


	<!--- if there is a keyword defined, get the previous info --->
	<CFQUERY name="qPlans" datasource="#request.dsn#">
		select
        b.id,
		b.bannerName,
		b.bannerImage,
        b.bannerImageSm,
        b.SponsorID
		from sponsor_banners b
       where b.id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.id#" />
	</CFQUERY>
    <CFQUERY name="qSponsors" datasource="#request.db.dsn#">
	select * from sponsorsub order by Name
</CFQUERY>

	<CFIF qPlans.RecordCount eq 0>
		<CFSET QueryAddRow(qPlans) />
	</CFIF>
</CFSILENT>
<CFOUTPUT>
<form name="AddPlan" action="?action=addBanner" method="post" enctype="multipart/form-data">
	<input type="hidden" value="#qPlans.id#" name="id">
<table width="500" border="0" cellspacing="2" cellpadding="4">

	<tr>
		<td class="rowHead">Banner Name*</td>
		<td class="rowData">
			<input type="text" value="#qPlans.bannerName#" name="bannerName" />		</td>
	</tr>

	<tr>
        <td class="rowHead">Sponsor</td>
        <td class="rowData"> <select name="sponsorID">
      
            <CFLOOP query="qSponsors">
              <option value="#qSponsors.id#"<cfif qSponsors.id eq qPlans.SponsorID> selected</cfif>>#qSponsors.Name#</option>
            </CFLOOP>
          <option value="">No Sponsor</option>
          </select> </td>
      </tr>
      <tr>
		<td class="rowHead">Branded Image</td>
		<td>
			<CFIF qPlans.bannerImage neq "">
				<div>Current Image:</div>
				<div><img src="http://www.spotlightpreview.com/images/bannerImages/#qPlans.bannerImage#" /></div>
				<div>&nbsp;</div>
			</CFIF>
			<div><input type="file" value="" name="agentfile" /> (250px X 75px)</div>		</td>
	</tr>
    
     <tr>
		<td class="rowHead">Branded Image Small</td>
		<td>
			<CFIF qPlans.bannerImage neq "">
				<div>Current Image:</div>
				<div><img src="http://www.spotlightpreview.com/images/bannerImages/#qPlans.bannerImageSm#" /></div>
				<div>&nbsp;</div>
			</CFIF>
			<div><input type="file" value="" name="agentfileSm" /> (250px X 50px)</div>		</td>
	</tr>
	

	<tr>
		<td class="rowHead">&nbsp;</td>
		<td><input type="submit" value="Save Banner"></td>
	</tr>
</table>
</form>
</CFOUTPUT>

<div >* Required Fields</div>
<div >** Required and Must Be Unique Across All Accounts</div>
