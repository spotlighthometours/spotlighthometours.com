<CFSILENT>
	<CFPARAM name="url.id" default="" />

	<!--- only get those brokerages not currently assigned to a plan --->


	<!--- if there is a keyword defined, get the previous info --->
	<CFQUERY name="qPlans" datasource="#request.dsn#">
		select
        k.id,
        k.SponsorID,
        k.BannerID,
        k.keyword,
        (select name from sponsorsub  where id=  k.SponsorID limit 1) as sponsorName
		from lonewolf_keywords k
       where k.SponsorID = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.id#" />
	</CFQUERY>
    <CFQUERY name="qBanners" datasource="#request.db.dsn#">
	select b.bannerImage,b.id from sponsor_banners b where b.SponsorID = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.id#" />
</CFQUERY>
    

	<CFIF qPlans.RecordCount eq 0>
		<CFSET QueryAddRow(qPlans) />
	</CFIF>
</CFSILENT>
<CFOUTPUT>
<form name="AddPlan" action="?action=updateBanner" method="post" enctype="multipart/form-data">
	<input type="hidden" value="#url.id#" name="sponsorid">
<table width="90%" border="0" cellspacing="2" cellpadding="4">

	  <CFLOOP query="qPlans">

	<tr>
        <td class="rowHead">Sponsor</td>
         <td class="rowHead">Keyword</td>
        <td class="rowHead">Branded Image</td>
      </tr>
		<td class="rowData">  #qPlans.sponsorName# </td>
          <td class="rowData"> 
      
           #qPlans.Keyword#
      </td>
		<td><table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td width="5"><input name="#qplans.id#" type="radio"  value=""></td>
            <td width="93%">Use Default</td>
          </tr>
        
        
          <CFLOOP query="qBanners">
		
        
          
          <tr>
            <td width="5"><input name="#qplans.id#" type="radio" <cfif qBanners.id eq qPlans.BannerID> checked="checked" </cfif> value="#qBanners.id#"></td>
            <td width="93%"><img src="http://www.spotlightpreview.com/images/bannerImages/#qBanners.bannerImage#" /></td>
          </tr>
          
				
			</CFLOOP>
        </table>				</td>
	</tr>
	</CFLOOP>

	<tr>
		<td class="rowHead">&nbsp;</td>
		<td><input type="submit" value="Save Banner"></td>
	</tr>
</table>
</form>
</CFOUTPUT>

<div >* Required Fields</div>
<div >** Required and Must Be Unique Across All Accounts</div>
