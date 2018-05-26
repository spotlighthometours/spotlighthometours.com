<CFSILENT>
	<CFPARAM name="url.keyword" default="" />

	<!--- only get those brokerages not currently assigned to a plan --->
	<CFQUERY name="qProviders" datasource="#request.dsn#">
		select
		a.id,
		a.name,
		a.region
		from
		lonewolf_providers a
		order by a.name asc
	</CFQUERY>

	<!--- get list of users --->
	<CFQUERY name="qUsers" datasource="#request.dsn#">
		select firstname, lastname, userid
		from users
        where lonewolfAgent = '1'
		order by lastname asc
	</CFQUERY>

	<!--- get list of brokers --->
	<CFQUERY name="qBrokerage" datasource="#request.dsn#">
		select brokerageName, brokerageID,brokerageDesc,brokerageClientId,password
		from brokerages
        where lonewolfData='1'
		order by brokerageName asc
	</CFQUERY>

 	<CFQUERY name="qBanner" datasource="#request.dsn#">
		select
        b.id,
		b.bannerName,
		b.bannerImage
		from sponsor_banners b
	</CFQUERY>
	<!--- if there is a keyword defined, get the previous info --->
	<CFQUERY name="qPlans" datasource="#request.dsn#">
		select
                k.id,
                k.firstname,
                k.lastname,
                k.keyword,
                k.phone,
                k.email,
                k.800Number,
                k.agentImage,
                k.createdOn,
                k.bannerImageID,
                k.notifycarrier,
                k.brokerageid_fk,
                k.userid_fk,
                k.password,
                k.notification,
                k.contactPhone,
                k.exclusive,
                 k.agenticon,
                k.shortcode,
                k.leaddistribution		
		from 	lonewolf_keywords k
		where	k.id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.keyword#" />
	</CFQUERY>
<CFIF qPlans.userid_fk neq "">
    <CFQUERY name="qKeywordUser" datasource="#request.dsn#">
        select firstname, lastname, userid,password,username
        from users
        where userid='#qPlans.userid_fk#'
        limit 1
    </CFQUERY>
</CFIF>    

	<CFQUERY name="qgetBrokerage" datasource="#request.dsn#">
		select brokerageName, brokerageID,brokerageDesc,brokerageClientId,password
		from brokerages
        where brokerageID='#qPlans.brokerageid_fk#'
	</CFQUERY>


	<CFIF qPlans.RecordCount eq 0>
		<CFSET QueryAddRow(qPlans) />
	</CFIF>
</CFSILENT>
<CFOUTPUT>
<form name="AddPlan" action="?action=addlonewolfPlan" method="post" enctype="multipart/form-data">
	<input type="hidden" value="#qPlans.id#" name="id">
<table width="500" border="0" cellspacing="2" cellpadding="4">

	<tr>
		<td class="rowHead">User*</td>
		<td class="rowData">
			<select name="userID" style="width:170px;">
				<option value="-">-</option>
				<CFLOOP query="qUsers">
					<option value="#qUsers.userID#" #IIF(qPlans.userid_fk eq qUsers.userid,DE('selected="true"'),DE(''))#>#qUsers.lastname#, #qUsers.firstname#</option>
				</CFLOOP>
			</select>
			
			<select name="brokerageID" style="width:170px;">
				<option value="-">-</option>
				<CFLOOP query="qBrokerage">
					<option value="#qBrokerage.brokerageID#" #IIF(qBrokerage.brokerageID eq qPlans.brokerageid_fk,DE('selected="true"'),DE(''))#>#qBrokerage.brokerageName# - #qBrokerage.brokerageDesc#</option>
				</CFLOOP>
			</select>		</td>
	</tr>
	<tr>
		<td class="rowHead">First Name*</td>
		<td class="rowData">
			<input type="text" value="#qPlans.firstname#" name="firstname" />		</td>
	</tr>
	<tr>
		<td class="rowHead">Last Name*</td>
		<td>
			<input type="text" value="#qPlans.lastname#" name="lastname" />		</td>
	</tr>
	<tr>
		<td class="rowHead">Contact Phone</td>
		<td class="rowData">
			<input type="text" value="#qPlans.contactPhone#" name="contactPhone" maxlength="10" /> (Numbers Only)		</td>
	</tr>
    <tr>
		<td class="rowHead">Notification Phone*</td>
		<td class="rowData">
			<input type="text" value="#qPlans.phone#" name="phone" maxlength="10" /> (Numbers Only)		</td>
	</tr>
    <tr>
		<td class="rowHead">SMS Notification*</td>
		<td class="rowData">
			<input name="notification" type="checkbox" id="notification" value="1"  #IIF(qPlans.notification eq 1,DE('checked="checked"'),DE(''))# /></td>
	</tr>
    <tr>
		<td class="rowHead">Exclusive*</td>
		<td class="rowData">
			<input name="exclusive" type="checkbox" id="exclusive" value="1"  #IIF(qPlans.exclusive eq 1,DE('checked="checked"'),DE(''))# /></td>
	</tr>
    <tr>
		<td class="rowHead">Lead Distribution</td>
		<td class="rowData">
			<input name="leaddistribution" type="checkbox" id="leaddistribution" value="1"  #IIF(qPlans.leaddistribution eq 1,DE('checked="checked"'),DE(''))# /></td>
	</tr>
	<tr>
		<td class="rowHead">Notification Email*</td>
		<td>
			<input type="text" value="#qPlans.email#" name="email" />		</td>
	</tr>
	<tr>
		<td class="rowHead">Phone Carrier*</td>
  <td class="rowData">
			<select name="CarrierSelect">
            	<CFSET foundCarrier=false>	
				<CFLOOP index="i" list="#StructKeyList(application.smscarriers)#">
					<option  #IIF(qPlans.notifyCarrier eq i,DE('selected="selected" selected="true"'),DE(''))# value="#i#">#application.smscarriers[i].displayname#</option>
					<CFIF qPlans.notifyCarrier eq i>
                    	<CFSET foundCarrier=true>
                    </CFIF>
                </CFLOOP>
                <CFIF foundCarrier eq false>
                	<option selected="selected" selected="true" value="0">FALIURE</option>
                </CFIF>
			</select>		</td>
	</tr>
    <tr>
		<td class="rowHead">Sponsor Banner</td>
		<td class="rowData">
			<select name="bannerImageID">
				<CFLOOP query="qBanner">
					<option value="#qBanner.id#" #IIF(qBanner.id eq qPlans.bannerImageID,DE('selected="true"'),DE(''))#>#qBanner.bannerName# </option>
				</CFLOOP>
			</select>		</td>
	</tr>    
	<tr>
		<td class="rowHead">Branded Image</td>
		<td>
			<CFIF qPlans.agentImage neq "">
				<div>Current Image:</div>
				<div><img src="http://www.spotlightpreview.com/images/previewAgentPhotos/#qPlans.agentImage#" /></div>
				<div>&nbsp;</div>
			</CFIF>
			<div><input type="file" value="" name="agentfile" /> (250px X 75px)</div>		</td>
	</tr>
	<tr>
		<td class="rowHead">Mobile Icon</td>
		<td>
			<CFIF qPlans.agentIcon neq "">
				<div>Current Icon:</div>
				<div><img src="http://www.spotlightpreview.com/images/previewAgentIcon/#qPlans.agentIcon#" /></div>
				<div>&nbsp;</div>
			</CFIF>
			<div><input type="file" value="" name="agenticon" /> (45px X 45px)</div>		</td>
	</tr>
	
	<tr>
		<td class="rowHead">Keyword**</td>
		<td>
			<input type="text" value="#qPlans.keyword#" name="keyword" maxlength="12"/>		</td>
	</tr>
    <tr>
		<td class="rowHead">Short Code**</td>
		<td>
			<select name="shortcode">
				<CFIF qPlans.shortcode eq '32323'>
                <option value="32323" selected="selected">32323</option>
                <CFELSE>
                <option value="32323">32323</option>
				</CFIF>
                <CFIF qPlans.shortcode eq '65656'>
                <option value="65656" selected="selected">65656</option>
                <CFELSE>
                <option value="65656">65656</option>
				</CFIF>
                 <CFIF qPlans.shortcode eq '95495'>
                <option value="95495" selected="selected">95495</option>
                <CFELSE>
                <option value="95495">95495</option>
				</CFIF>
				
			</select>		</td>
	</tr>
    <tr>
		<td class="rowHead">Password</td>
	  <td>
			<input type="text" value="#qPlans.password#" name="password" maxlength="4"/>
			(Leave blank to auto generate)</td>
	</tr>
    
    <tr>
		<td class="rowHead">Mobilewolfhp.com credentials</td>
	  <td>
      <CFIF qPlans.userid_fk neq "">
         	<b>Agent Credentials</b><br />
            Username:#qKeywordUser.username#<br />
            Password:#qKeywordUser.password#<br />
       </CFIF>     
          	<b>Keyword Admin Credentials</b><br />
            Username:#qgetBrokerage.brokerageClientId#<br />
            Password:#qgetBrokerage.password#<br />
       
            
            </td>
	</tr>
    
    
	<tr>
		<td class="rowHead">800 Number**</td>
		<td class="rowData">
			<input type="text" value="#qPlans.800Number#" name="800Number" maxlength="10" /> (Numbers Only)		</td>
	</tr>

	<tr>
		<td class="rowHead"></td>
		<td><label><input name="emailProvider" type="checkbox" id="emailProvider" value="1"  />Email keyword holder</label></td></td>
	</tr>
    <tr>
		<td class="rowHead">&nbsp;</td>
		<td><input type="submit" value="Save Plan"></td>
	</tr>
</table>
</form>
</CFOUTPUT>

<div >* Required Fields</div>
<div >** Required and Must Be Unique Across All Accounts</div>
