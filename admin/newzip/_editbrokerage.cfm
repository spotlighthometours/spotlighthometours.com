<cfset editMode = iif(isDefined("url.brokerage"), true, false)>
<cfif editMode>
	<cfquery name="qBrokerages" datasource="#request.db.dsn#">
		select * from brokerages where brokerageID = #url.brokerage#
	</cfquery>
</cfif>
<cfquery name="qSalesReps" datasource="#request.db.dsn#">
	select * from salesReps order by fullName
</cfquery>

<cfquery name="qThemes" datasource="#request.db.dsn#">
	select * from themes order by id
</cfquery>

<html>
<head>
<title>Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<cfoutput>
<form enctype="multipart/form-data" action="#cgi.sript_name#?action=<cfif editMode>updateBrokerage<cfelse>insertBrokerage</cfif>" method="post">
    <table width="500" border="0" cellspacing="2" cellpadding="4">
      <tr> 
        <td class="rowHead">Brokerage Name</td>
        <td class="rowData"><input name="brokerageName" type="text" size="32" maxlength="50"<cfif editMode> value="#qBrokerages.brokerageName#"</cfif>></td>
      </tr>
       <tr> 
        <td class="rowHead">Brokerage Description</td>
        <td class="rowData"><input name="brokerageDesc" type="text" size="32" maxlength="50"<cfif editMode> value="#qBrokerages.brokerageDesc#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Client ID</td>
        <td class="rowData"><input name="brokerageClientId" type="text" id="brokerageClientId" size="32" maxlength="50"<cfif editMode> value="#qBrokerages.brokerageClientId#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Contact Phone</td>
        <td class="rowData"><input name="brokerageContactPhone" type="text" id="brokerageContactPhone" size="32" maxlength="50"<cfif editMode> value="#qBrokerages.brokerageContactPhone#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Contact Email</td>
        <td class="rowData"><input name="brokerageContactEmail" type="text" id="brokerageContactEmail" size="32" maxlength="50"<cfif editMode> value="#qBrokerages.brokerageContactEmail#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Notify Phone</td>
        <td class="rowData"><input name="brokerageNotifyPhone" type="text" id="brokerageNotifyPhone" size="32" maxlength="50"<cfif editMode> value="#qBrokerages.brokerageNotifyPhone#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Notify Email</td>
        <td class="rowData"><input name="brokerageNotifyEmail" type="text" id="brokerageNotifyEmail" size="32" maxlength="50"<cfif editMode> value="#qBrokerages.brokerageNotifyEmail#"</cfif>></td>
      </tr>
      
      
      
      <tr>
        <td class="rowHead">Country</td>
        <td class="rowData"><label>
          <select name="brokerageCountry" id="brokerageCountry">
          	<cfif editMode>
            	<cfif qBrokerages.brokerageCountry eq 1 >
                	 <option value="1" selected>United States of America</option>
                     <option value="0">Canada</option>
                <cfelse>
                	  <option value="1">United States of America</option>
                     <option value="0"  selected>Canada</option>
                </cfif>
            
            <cfelse>
                <option value="1" selected>United States of America</option>
                <option value="0">Canada</option>
            </cfif>
          </select>
        </label></td>
      </tr>
			<cfif editMode>
			 <tr> 
        <td class="rowHead">Key</td>
        <td class="rowData">#qBrokerages.api_key#</td>
      </tr>
			</cfif>
			<tr> 
        <td class="rowHead">Logo</td>
        <td class="rowData"><input type="file" name="logo_file">
				<cfif editMode and fileExists(expandPath('/images/logos/#qBrokerages.logo#'))>
				<br><img src="/images/logos/#qBrokerages.logo#">
				</cfif>				</td>
      </tr>
			<tr> 
        <td class="rowHead">Theme</td>
        <td class="rowData">
				<select name="theme_id">
				<cfloop query="qThemes">
					<option value="#id#"<cfif editMode and id eq qBrokerages.theme_id> selected</cfif>>#name#</option>
					</cfloop>
				</select>				</td>
      </tr>
      <tr> 
        <td class="rowHead">Sales Rep</td>
        <td class="rowData"><select name="salesRepID">
           <option value="0"<cfif editMode and not qBrokerages.salesRepID> selected</cfif>>Unassigned</option>
            <cfloop query="qSalesReps">
              <option value="#salesRepID#"<cfif editMode and salesRepID eq qBrokerages.salesRepID> selected</cfif>>#fullName#</option>
            </cfloop>
          </select></td>
      </tr>
      <tr> 
        <td class="rowHead">Lonewolf</td>
        <td class="rowData"><select name="lonewolfData">
           <option value="0"<cfif editMode and not qBrokerages.lonewolfData> selected</cfif>>Unassigned</option>
            <cfloop query="qSalesReps">
              <option value="#salesRepID#"<cfif editMode and salesRepID eq qBrokerages.salesRepID> selected</cfif>>#fullName#</option>
            </cfloop>
          </select></td>
      </tr>
       <tr>
        <td class="rowHead">Unsubscribe Marketing</td>
        <td class="rowData"><input name="emailUnsuscribe" type="checkbox" id="emailUnsuscribe" value="1"
			<cfif editMode and qBrokerages.emailUnsuscribe eq 1> checked="true"</cfif>>
		</td>
      </tr>      
      <tr> 
        <td class="rowHead"><cfif editMode>
            <input type="hidden" name="brokerageID" value="#qBrokerages.brokerageID#">
          </cfif></td>
        <td class="rowData"><input type="submit" value="<cfif EditMode>Update<cfelse>Add</cfif> Brokerage"></td>
      </tr>
      

    </table>
</form>
</cfoutput>
</body>
</html>
