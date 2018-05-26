<cfsilent>
<cfset editMode = iif(isDefined("url.user"), true, false)>
<cfif editMode>
	<cfquery name="qUsers" datasource="#request.db.dsn#">
		select *, CASE WHEN ISNULL(ms.membershipType) THEN 'N/A' ELSE ms.membershipType END as ConciergeLevel 
    	FROM users as u 
        LEFT JOIN members as m ON m.userID = u.userID AND m.active = 1 AND m.typeID IN (4,5,6)
       	LEFT JOIN memberships as ms ON ms.id = m.typeID
        where u.userID = #url.user#
	</cfquery>
	<cfquery name="qUserPackages" datasource="#request.db.dsn#">
		SELECT DISTINCT itemID, 
		(SELECT name FROM packages WHERE id=itemID) as packageName
		FROM credits 
		WHERE userID=#url.user# 
		AND itemType='package' 
		AND active='1'
	</cfquery>
	<cfset packages="">
	<CFLOOP query="qUserPackages">
		<cfset packages=packages&packageName&", ">
	</CFLOOP>
	<cfset aPhone = listToArray(qUsers.phone, ".")>
    <cfif arrayLen(aPhone) eq 1>
    	<cfif qUsers.phone neq "">
        <cfset strReadFriendlyNumber = left(qUsers.phone,3) & '.' & Mid(qUsers.phone,4,3) & '.' & right(qUsers.phone,4) />
        <cfset aPhone = listToArray(strReadFriendlyNumber, ".")>
        </cfif>
    </cfif>
	<cfset aPhone2 = listToArray(qUsers.phone2, ".")>
	<cfset aFax = listToArray(qUsers.fax, ".")>
    <cfquery name="mlsIDs" datasource="#request.db.dsn#" result="mlsIDResults">
		SELECT * FROM user_to_mls WHERE userID=#url.user#
	</cfquery>
<cfelse>
	<cfset qUsers = QueryNew("phonecarrier") />
</cfif>
<cfquery name="qMemberships" datasource="#request.db.dsn#">
    SELECT * FROM memberships WHERE concierge = 0 ORDER BY id
</cfquery>	
<cfquery name="qConcierge" datasource="#request.db.dsn#">
    SELECT * FROM memberships WHERE concierge > 0 ORDER BY concierge
</cfquery>	
<cfquery name="qStates" datasource="#request.db.dsn#">
	select stateFullName, stateAbbrName from states order by stateFullName
</cfquery>
<cfquery name="qBrokerages" datasource="#request.db.dsn#">
	select * from brokerages WHERE BrokerageName="Coldwell Banker Colorado" order by BrokerageName
</cfquery>
<cfquery name="qSalesReps" datasource="#request.db.dsn#">
	select * from salesreps order by fullName
</cfquery>
<cfquery name="qMlsProviders" datasource="#request.db.dsn#">
	select * from mls_providers order by name
</cfquery>
</cfsilent>
<html>
<head>
<title>Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<script src="../../repository_inc/jquery-1.6.2.min.js"></script>
<script>
	function addMLSField(obj){
		$(obj).parent().append($("#MLSFieldHolder").html());
	}
	function removeMLSField(obj){
		$(obj).parent().remove();
	}
</script>
</head>
<body>
<cfoutput>
<form action="#cgi.sript_name#?action=<cfif editMode>updateUser<cfelse>insertUser</cfif>" method="post">
    <table width="800" border="0" cellspacing="2" cellpadding="4">
      <cfif editMode>
          <tr>
            <td class="rowHead">User Pricing</td>
            <td class="rowData"><a href="../admin_pricingbyuser.php?userid=#qUsers.userid#" target="_blank">[Pricing]</a></td>
      	  </tr>
      </cfif>
      <tr>
        <td class="rowHead">First Name</td>
        <td class="rowData"><input name="firstName" type="text" size="50" maxlength="50"<cfif editMode> value="#qUsers.firstName#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Last Name</td>
        <td class="rowData"><input name="lastName" type="text" size="28" maxlength="24"<cfif editMode> value="#qUsers.lastName#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">User Type</td>
        <td class="rowData"> <select name="userType">
            <option<cfif editMode and qUsers.userType eq "Agent"> selected</cfif>>Agent</option>
			<option<cfif editMode and qUsers.userType eq "Commercial Agent"> selected</cfif>>Commercial Agent</option>
            <option<cfif editMode and qUsers.userType eq "Builder"> selected</cfif>>Builder</option>
            <option<cfif editMode and qUsers.userType eq "Home Owner"> selected</cfif>>Home Owner</option>
			<option<cfif editMode and qUsers.userType eq "Test"> selected</cfif>>Test</option>
          </select> </td>
      </tr>
      <tr>
        <td class="rowHead">MLS<br>(Agent ID)</td>
          <td class="rowData">
          	<cfif (editMode) AND (mlsIDResults.RecordCount gt 0)>
                <cfset firstMLS = true />
                <cfloop query="mlsIDs">
                    <cfif firstMLS >
                    <cfelse>
                    <div>
                    </cfif>
                    <input name="mls" type="text" size="20" maxlength="20" value="#mlsIDs.mlsID#"> &nbsp;MLS Provider: 
                    <select name="mlsProviderID">
                        <cfloop query="qMlsProviders">
                        <option value="#qMlsProviders.id#"<cfif mlsIDs.mlsProvider eq #qMlsProviders.id#> selected</cfif>>#qMlsProviders.name#</option>
                        </cfloop>
                    </select>
                    <cfif firstMLS >
                    	&nbsp;<div style="cursor:pointer;display:inline;" onClick="addMLSField(this)">+ Add another</div>
                    <cfelse>
                    	&nbsp;<div style="cursor:pointer;display:inline;" onClick="removeMLSField(this)">- Remove</div>
                    </div>
                    </cfif>
                    <cfset firstMLS = false />
                </cfloop>
            <cfelse>
            	<input name="mls" type="text" size="20" maxlength="20"<cfif editMode> value="#qUsers.mls#"</cfif>> &nbsp;MLS Provider: 
                <select name="mlsProviderID">
                    <option value="0"<cfif not editMode or not qUsers.mlsProviderID gt 0> selected</cfif>>Select One...</option>
                    <cfloop query="qMlsProviders">
                    <option value="#qMlsProviders.id#"<cfif editMode and qUsers.mlsProviderID eq #qMlsProviders.id#> selected</cfif>>#qMlsProviders.name#</option>
                    </cfloop>
                </select>
                &nbsp;<div style="cursor:pointer;display:inline;" onClick="addMLSField(this)">+ Add another</div>
            </cfif>
            <div style="display:none;" id="MLSFieldHolder">
            	<div>
                    <input name="mls" type="text" size="20" maxlength="20" value=""> &nbsp;MLS Provider: 
                    <select name="mlsProviderID">
                        <option value="">Select One...</option>
                        <cfloop query="qMlsProviders">
                        <option value="#qMlsProviders.id#">#qMlsProviders.name#</option>
                        </cfloop>
                    </select>
                    &nbsp;<div style="cursor:pointer;display:inline;" onClick="removeMLSField(this)">- Remove</div>
                </div>
            </div>
         </td>
      </tr>
      <tr>
        <td class="rowHead">Brokerage</td>
        <td class="rowData"> <select name="brokerageID">
            <cfif not editMode>
              <option value="">Select One...</option>
            </cfif>
            <cfloop query="qBrokerages">
              <option value="#BrokerageID#"<cfif editMode and BrokerageID eq qUsers.BrokerageID> selected</cfif>>#BrokerageName# (desc:#brokerageDesc#)</option>
            </cfloop>
            <option value="0"<cfif editMode and not qUsers.brokerageID> selected</cfif>>Other
            (Enter below)</option>
          </select> </td>
      </tr>
      <tr>
        <td class="rowHead">Other Brokerage</td>
        <td class="rowData"><input name="otherBrokerage" type="text" size="32" maxlength="50"<cfif editMode> value="#qUsers.otherBrokerage#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Concierge Level</td>
        <td class="rowData"> <select name="ConciergeLevel">
            <option <cfif not editMode or "None" eq qUsers.ConciergeLevel> selected</cfif>>None</option>
            <cfloop query="qConcierge">
            	<CFIF editMode>
	                <cfquery name="qMember" datasource="#request.db.dsn#">
    	                    SELECT * FROM members WHERE typeID = #qConcierge.id# and userID = #url.user#
        	        </cfquery>
            	</CFIF>
                <option <cfif editMode and qMember.active eq 1> selected</cfif>>#qConcierge.membershipType#</option>
            </cfloop>
          </select> </td>
      </tr>
      <tr>
        <td class="rowHead">Sales Rep.</td>
        <td class="rowData"><select name="salesRepID">
            <option value="0"<cfif editMode and not qUsers.salesRepID> selected</cfif>>Brokerage
            Sales Rep.</option>
            <cfloop query="qSalesReps">
              <option value="#salesRepID#"<cfif editMode and salesRepID eq qUsers.salesRepID> selected</cfif>>#fullName#</option>
            </cfloop>
          </select></td>
      </tr>
      <tr>
        <td class="rowHead">Username</td>
        <td class="rowData"><input name="username" type="text" size="24" maxlength="48"<cfif editMode> value="#qUsers.username#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Password</td>
        <td class="rowData"><input name="password" type="text" size="24" maxlength="20"<cfif editMode> value="#qUsers.password#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Email Address</td>
        <td class="rowData"><input name="email" type="text" size="36" maxlength="255"<cfif editMode> value="#qUsers.email#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Address</td>
        <td class="rowData"><input name="address" type="text" size="36" maxlength="200"<cfif editMode> value="#qUsers.address#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">City</td>
        <td class="rowData"><input name="city" type="text" size="24" maxlength="50"<cfif editMode> value="#qUsers.city#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">State</td>
        <td class="rowData"> <select name="state">
            <cfif not editMode>
              <option value="">Select One...</option>
            </cfif>
            <cfloop query="qStates">
              <option value="#stateAbbrName#"<cfif editMode and stateAbbrName eq qUsers.state> selected</cfif>>#stateFullName#</option>
            </cfloop>
          </select> </td>
      </tr>
      <tr>
        <td class="rowHead">Zip Code</td>
        <td class="rowData"><input name="zipCode" type="text" size="12" maxlength="10"<cfif editMode> value="#qUsers.zipCode#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Cell Phone</td>
        <td class="rowData"><input type="text" size="24" name="phone" <cfif editMode> value="#qUsers.phone#"</cfif> /></td>
      </tr>
		<tr>
			<td class="rowHead">Cell Carrier</td>
			<td class="rowData">
				<select name="phonecarrier">
					<option value="" >-</option>
	                 <cfloop index="i" list="#StructKeyList(application.smscarriers)#">
					<option  #IIF(editMode and qUsers.phonecarrier eq i,DE('selected="selected" selected="true"'),DE(''))# value="#i#">#application.smscarriers[i].displayname#</option>
				</cfloop>
                    
				</select>
			</td>
		</tr>
      <tr>
        <td class="rowHead">Alternate Phone</td>
        <td class="rowData"><input type="text" size="24" name="phone2" <cfif editMode> value="#qUsers.phone2#"</cfif> /></td>
      </tr>
      <tr>
        <td class="rowHead">Fax</td>
        <td class="rowData"><input type="text" size="24" name="fax" <cfif editMode> value="#qUsers.fax#"</cfif> /></td>
      </tr>
      <tr>
        <td class="rowHead">URI</td>
        <td class="rowData"><input name="uri" type="text" size="36"<cfif editMode> value="#qUsers.uri#"</cfif>></td>
      </tr>
      <tr>
      	<td class="rowHead">Assist Name</td>
      	<td class="rowData"><input name="assistName" type="text" size="36"<cfif editMode> value="#qUsers.assistName#"</cfif>></td>
      	</tr>
      <tr>
      	<td class="rowHead">Assist Phone</td>
      	<td class="rowData"><input name="assistPhone" type="text" size="36"<cfif editMode> value="#qUsers.assistPhone#"</cfif>></td>
      	</tr>
      <TR>
        <TD CLASS="rowHead">Tour Window</TD>
        <TD CLASS="rowData">
		  <SELECT NAME="TourWindowType">
          	<option value="Both" <cfif editMode and #qUsers.TourWindowType# eq "Both"> selected</cfif> />Both</option>
		  	<option value="Old" <cfif editMode and #qUsers.TourWindowType# eq "Old"> selected</cfif> />Old Window Type</option>
            <option value="New" <cfif editMode and #qUsers.TourWindowType# eq "New"> selected</cfif> />New Window Type</option>
            <option value="higginsgroup" <cfif editMode and #qUsers.TourWindowType# eq "higginsgroup"> selected</cfif> />higginsgroup</option>
            <option value="berkshire" <cfif editMode and #qUsers.TourWindowType# eq "berkshire"> selected</cfif> />berkshire</option>
		  </SELECT>
		</TD>
	  </TR>
	  <tr>
      	<td class="rowHead">Agent Notes</td>
      	<td class="rowData">
			<textarea name="agentNotes" cols="50" rows="5"><cfif editMode>#qUsers.agentNotes#</cfif></textarea>
		</td>
      </tr>
      <tr>
      	<td class="rowHead">Sales Notes</td>
      	<td class="rowData">
			<textarea name="salesNotes" cols="50" rows="5"><cfif editMode>#qUsers.salesNotes#</cfif></textarea>
		</td>
      </tr>
      <tr>
        <td class="rowHead">Upload Privilege</td>
        <td class="rowData">
			<cfif editMode and qUsers.uploadPrivilege eq 0>
				<input name="uploadPrivilege" type="checkbox" id="uploadPrivilege" value="1" />
			<cfelse>
				<input name="uploadPrivilege" type="checkbox" id="uploadPrivilege" value="1" checked="true" />
			</cfif>
		</td>
      </tr>
      <tr>
        <td class="rowHead">Unsubscribe Marketing</td>
        <td class="rowData"><input name="emailUnsuscribe" type="checkbox" id="emailUnsuscribe" value="1"
			<cfif editMode and qUsers.emailUnsuscribe eq 1> checked="true"</cfif>>
		</td>
      </tr>      
	  
	  <cfif editMode>
        <tr>
          <td class="rowHead">Created</td>
          <td class="rowData">#dateFormat(qUsers.dateCreated, "mm/dd/yyyy")# #timeFormat(qUsers.dateCreated,"hh:mm tt")#</td>
        </tr>
        <tr>
          <td class="rowHead">Last
            Modified</td>
          <td class="rowData">#dateFormat(qUsers.dateModified, "mm/dd/yyyy")# #timeFormat(qUsers.dateModified, "hh:mm tt")#</td>
        </tr>
        <tr>
          <td class="rowHead">User Tours</td>
          <td class="rowData"><a href="/admin/users/users.cfm?pg=tours&user=#qUsers.userID#">[VIEW TOURS]</a> </td>
        </tr>
        <CFLOOP query="qMemberships">
        	<cfquery name="qMember" datasource="#request.db.dsn#">
                    SELECT * FROM members WHERE typeID = #qMemberships.id# and userID = #url.user#
            </cfquery>
            <tr>
              <td class="rowHead">#qMemberships.name#</td>
              <td class="rowData">
                <input name="Membership#qMemberships.id#" type="checkbox" id="Membership#qMemberships.id#" value="1" <cfif qMember.active eq 1> checked="true"</cfif>>
              </td>
            </tr>
        </CFLOOP>
      	<tr>
          <td class="rowHead">Packages</td>
          <td class="rowData">
			#packages#
		</td>
      	<tr>
          <td class="rowHead">User Logo</td>
          <td class="rowData">
			    <a href='/admin/user-logo.php?userId=#qUsers.userID#'>Click here</a>
          </td>
		</td>
        
        </tr>
                <tr>
          <td class="rowHead">Intro/Outro Video</td>
          <td class="rowData">
			[ <span style="text-decoration:underline;cursor:pointer;" onClick="window.open('../admin_user_inoutvid.php?userID=#url.user#','name','height=500,width=1000')">MANAGE UPLOADS</span> ]
		</td>
        </tr>
      </cfif>
      <tr>
        <td class="rowHead"><cfif editMode>
            <input type="hidden" name="userID" value="#qUsers.userID#">
          </cfif></td>
        <td class="rowData"><input type="submit" value="<cfif EditMode>Update<cfelse>Add</cfif> User"></td>
      </tr>
    </table>
</form>
</cfoutput>
</body>
</html>
