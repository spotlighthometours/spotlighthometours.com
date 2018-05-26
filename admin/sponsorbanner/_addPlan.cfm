<cfsilent>
	<cfparam name="url.keyword" default="" />

	<!--- only get those brokerages not currently assigned to a plan --->
	<cfquery name="qProviders" datasource="#request.dsn#">
		select
		a.id,
		a.name,
		a.region
		from
		listhub_providers a
		order by a.name asc
	</cfquery>

	<!--- get list of users --->
	<cfquery name="qUsers" datasource="#request.dsn#">
		select firstname, lastname, userid
		from users
        where lonewolfAgent = '0' 
		order by lastname asc
	</cfquery>

	<!--- get list of brokers --->
	<cfquery name="qBrokerage" datasource="#request.dsn#">
		select brokerageName, brokerageID,brokerageDesc
		from brokerages
        where lonewolfData = '0'
		order by brokerageName asc
	</cfquery>

	<!--- if there is a keyword defined, get the previous info --->
	<cfquery name="qPlans" datasource="#request.dsn#">
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
		k.notifycarrier,
		k.brokerageid_fk,
		k.userid_fk,
		ktp.provider_fk as providerid
		from listhub_keywords k join listhub_keywords_to_providers ktp
			on k.id = ktp.keyword_fk
		where k.id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.keyword#" />
	</cfquery>

	<cfif qPlans.RecordCount eq 0>
		<cfset QueryAddRow(qPlans) />
	</cfif>
</cfsilent>
<cfoutput>
<form name="AddPlan" action="?action=addPlan" method="post" enctype="multipart/form-data">
	<input type="hidden" value="#qPlans.id#" name="id">
<table width="500" border="0" cellspacing="2" cellpadding="4">

	<tr>
		<td class="rowHead">User*</td>
		<td class="rowData">
			<select name="userID" style="width:170px;">
				<option value="-">-</option>
				<cfloop query="qUsers">
					<option value="#qUsers.userID#" #IIF(qPlans.userid_fk eq qUsers.userid,DE('selected="true"'),DE(''))#>#qUsers.lastname#, #qUsers.firstname#</option>
				</cfloop>
			</select>
			- or -
			<select name="brokerageID" style="width:170px;">
				<option value="-">-</option>
				<cfloop query="qBrokerage">
					<option value="#qBrokerage.brokerageID#" #IIF(qBrokerage.brokerageID eq qPlans.brokerageid_fk,DE('selected="true"'),DE(''))#>#qBrokerage.brokerageName# (desc:#qBrokerage.brokerageDesc#)</option>
				</cfloop>
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
		<td class="rowHead">Phone*</td>
		<td class="rowData">
			<input type="text" value="#qPlans.phone#" name="phone" maxlength="10" /> (Numbers Only)		</td>
	</tr>
	<tr>
		<td class="rowHead">Email*</td>
		<td>
			<input type="text" value="#qPlans.email#" name="email" />		</td>
	</tr>
	<tr>
		<td class="rowHead">Mobile Carrier*</td>
		<td class="rowData">
			<select name="CarrierSelect">
				<cfloop index="i" list="#StructKeyList(application.smscarriers)#">
					<option  #IIF(qPlans.notifyCarrier eq i,DE('selected="selected" selected="true"'),DE(''))# value="#i#">#application.smscarriers[i].displayname#</option>
				</cfloop>
			</select>		</td>
	</tr>
	<tr>
		<td class="rowHead">Branded Image</td>
		<td>
			<cfif qPlans.agentImage neq "">
				<div>Current Image:</div>
				<div><img src="/images/previewAgentPhotos/#qPlans.agentImage#" /></div>
				<div>&nbsp;</div>
			</cfif>
			<div><input type="file" value="" name="agentfile" /> (250px X 75px)</div>		</td>
	</tr>
	<tr>
		<td class="rowHead">MLS Providers*</td>
		<td class="rowData">
			<cfset lProviders = ArrayToList(qPlans['providerid']) />
			<select name="providers" multiple="true">
				<cfloop query="qProviders">
				<option value="#qProviders.id#" #IIF(ListFindNoCase(lProviders,qProviders.id),DE('selected="true"'),DE(''))#>#qProviders.Name# (#qProviders.region#)</option>
				</cfloop>
			</select>		</td>
	</tr>
	<tr>
		<td class="rowHead">Keyword**</td>
		<td>
			<input type="text" value="#qPlans.keyword#" name="keyword" maxlength="12"/>		</td>
	</tr>
	<tr>
		<td class="rowHead">800 Number**</td>
		<td class="rowData">
			<input type="text" value="#qPlans.800Number#" name="800Number" maxlength="10" /> (Numbers Only)		</td>
	</tr>

	<tr>
		<td class="rowHead">&nbsp;</td>
		<td><input type="submit" value="Save Plan"></td>
	</tr>
</table>
</form>
</cfoutput>

<div >* Required Fields</div>
<div >** Required and Must Be Unique Across All Accounts</div>
