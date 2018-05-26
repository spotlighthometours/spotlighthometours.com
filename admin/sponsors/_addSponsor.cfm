<CFSET editMode = iif(isDefined("url.id"), true, false)>
<CFSILENT>
	<CFPARAM name="url.id" default="" />
<CFIF editMode>
	<CFQUERY name="qSponsors" datasource="#request.dsn#">
		select
        s.*,
		(Select Name from sponsorMain WHERE id=s.mainSponsorId limit 1) as mainSponsor,
        (Select count(userID) from sponsor_officers WHERE SponsorId=s.id) as officerCount,
        (Select count(id) from lonewolf_keywords WHERE SponsorId=s.id) as keywordCount
		from sponsorSub s
        WHERE id = '#url.id#' limit 1
		
	</CFQUERY>
    <CFSET cPhone = listToArray(qSponsors.contactPhone, ".")>
    <CFSET nPhone = listToArray(qSponsors.notifyPhone, ".")>
<CFELSE>
	<CFSET qSponsors = QueryNew("notifyPhoneCarrier") />

</CFIF>
<CFQUERY name="qStates" datasource="#request.db.dsn#">
	select stateFullName, stateAbbrName from states order by stateFullName
</CFQUERY>

<CFQUERY name="qKeywords" datasource="#request.db.dsn#">
	select k.id,k.keyword,k.shortcode,(select name from sponsorsub where id=k.sponsorid limit 1) as sponsorName,(select id from sponsorsub where id=k.sponsorid limit 1) as sponsorID from lonewolf_keywords k  order by keyword asc
</CFQUERY>

</CFSILENT>
<CFOUTPUT>
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<form name="AddPlan" action="#cgi.sript_name#?action=<cfif editMode>updateSponsor<cfelse>insertSponsor</cfif>" method="post" enctype="multipart/form-data">
	<CFIF editMode>
            <input type="hidden" name="sponsorid" value="#qSponsors.id#">
  </CFIF>
<table width="500" border="0" cellspacing="2" cellpadding="4">

	<tr>
		<td class="rowHead">Sponsor Name*</td>
		<td class="rowData">
			<input type="text" value="<cfif editMode>#qSponsors.Name#</cfif>" name="sponsorName" />		</td>
	</tr>
      <tr>
        <td class="rowHead">Username</td>
        <td class="rowData"><input name="username" type="text" size="24" maxlength="48"<cfif editMode> value="#qSponsors.username#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Password</td>
        <td class="rowData"><input name="password" type="text" size="24" maxlength="20"<cfif editMode> value="#qSponsors.password#"</cfif> >
        (Leave blank to auto generate)</td>
      </tr>
    
 <tr>
		<td class="rowHead">Address</td>
		<td class="rowData">
			<input type="text" value="<cfif editMode>#qSponsors.Address#</cfif>" name="Address" />		</td>
	</tr>
    <tr>
		<td class="rowHead">City</td>
		<td class="rowData">
			<input type="text" value="<cfif editMode>#qSponsors.City#</cfif>" name="City" />		</td>
	</tr>
    <tr>
		<td class="rowHead">State</td>
		<td class="rowData"><select name="state">
          <CFIF not editMode>
            <option value="">Select One...</option>
          </CFIF>
          <CFLOOP query="qStates">
          <option value="#stateAbbrName#"<cfif editMode and stateAbbrName eq qSponsors.State> selected</cfif>>#stateFullName#</option>
          </CFLOOP>
        </select></td>
	</tr>
    <tr>
		<td class="rowHead">zip</td>
		<td class="rowData">
			<input type="text" value="<cfif editMode>#qSponsors.zip#</cfif>" name="zip" maxlength="5"/>		</td>
	</tr>
    
	<tr>
		<td class="rowHead">Contact Phone</td>
		<td class="rowData"><input type="text" name="cphone_1"<cfif editMode and arrayLen(cPhone) eq 3> value="#cPhone[1]#"</cfif> maxlength="3" size="3" />
&nbsp;
<input type="text" name="cphone_2"<cfif editMode and arrayLen(cPhone) eq 3> value="#cPhone[2]#" maxlength="3"</cfif> size="3" />
&nbsp;
<input type="text" name="cphone_3"<cfif editMode and arrayLen(cPhone) eq 3> value="#cPhone[3]#" maxlength="4"</cfif> size="4" /></td>
	</tr>
    <tr>
		<td class="rowHead">Contact Email</td>
		<td class="rowData">
			<input type="text" value="<cfif editMode>#qSponsors.contactEmail#</cfif>" name="contactEmail" />		</td>
	</tr>
    <tr>
		<td class="rowHead">Notify Phone</td>
		<td class="rowData"><input type="text" name="nphone_1"<cfif editMode and arrayLen(nPhone) eq 3> value="#nPhone[1]#"</cfif> maxlength="3" size="3" />
&nbsp;
<input type="text" name="nphone_2"<cfif editMode and arrayLen(nPhone) eq 3> value="#nPhone[2]#" maxlength="3"</cfif> size="3" />
&nbsp;
<input type="text" name="nphone_3"<cfif editMode and arrayLen(nPhone) eq 3> value="#nPhone[3]#" maxlength="4"</cfif> size="4" /></td>
	</tr>
    <tr>
		<td class="rowHead">Notify Phone Carrier</td>
		<td class="rowData"><select name="notifyPhoneCarrier">
          <option value="" >-</option>
          <option value="ATTUS" <cfif qSponsors.notifyPhoneCarrier eq "ATTUS">selected</cfif>>AT&amp;T</option>
          <option value="CINGULARUS" <cfif qSponsors.notifyPhoneCarrier eq "CINGULARUS">selected</cfif>>Cingular</option>
          <option value="NEXTELUS" <cfif qSponsors.notifyPhoneCarrier eq "NEXTELUS">selected</cfif>>Nextel</option>
          <option value="SPRINTUS" <cfif qSponsors.notifyPhoneCarrier eq "SPRINTUS">selected</cfif>>Sprint</option>
          <option value="TMOBILEUS" <cfif qSponsors.notifyPhoneCarrier eq "TMOBILEUS">selected</cfif>>T-Mobile</option>
          <option value="VERIZONUS" <cfif qSponsors.notifyPhoneCarrier eq "VERIZONUS">selected</cfif>>Verizon</option>
          <option value="other">Other</option>
        </select></td>
	</tr>
    <tr>
		<td class="rowHead">Notify Email</td>
		<td class="rowData">
			<input type="text" value="<cfif editMode>#qSponsors.notifyEmail#</cfif>" name="notifyEmail" />		</td>
	</tr>
    <tr>
		<td class="rowHead">Keywords</td>
		<td class="rowData">
			
			<select name="keywords" multiple="true">
				<CFLOOP query="qKeywords">
				<option value="#qKeywords.id#" <cfif editMode >#IIF(ListFindNoCase(qSponsors.id,qKeywords.sponsorID),DE('selected="true"'),DE(''))#</cfif>>#qKeywords.Keyword# [#qKeywords.shortcode#](#qKeywords.sponsorName#)</option>
				</CFLOOP>
			</select>		</td>
	</tr>
    <tr>
      <td class="rowHead">Active</td>
      <td class="rowData"><input name="active" type="checkbox" id="active" value="1"
			<cfif editMode and qSponsors.active eq 1> checked="true"</cfif> /></td>
    </tr>
    <tr>
      <td class="rowHead">Send Email</td>
      <td class="rowData"><input name="sendEmail" type="checkbox" id="sendEmail" value="1"/></td>
    </tr>
    <CFIF editMode>
    <tr>
      <td class="rowHead">Created</td>
      <td class="rowData">#dateFormat(qSponsors.dateCreated,"mm/dd/yyyy")# #timeFormat(qSponsors.dateCreated,
        "hh:mm tt")#</td>
    </tr>
    <tr>
      <td class="rowHead">Last
        Modified</td>
      <td class="rowData">#dateFormat(qSponsors.dateModified, "mm/dd/yyyy")# #timeFormat(qSponsors.dateModified, "hh:mm tt")#</td>
    </tr>
   </CFIF>
	

	<tr>
		<td class="rowHead">&nbsp;</td>
		<td><input type="submit" value="Save Sponsor"></td>
	</tr>
</table>
</form>
</CFOUTPUT>

<div >* Required Fields</div>
<div >** Required and Must Be Unique Across All Accounts</div>
