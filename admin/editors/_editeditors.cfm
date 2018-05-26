<cfset editMode = iif(isDefined("url.rep"), true, false)>
<cfif editMode>
	<cfquery name="qSalesReps" datasource="#request.db.dsn#">
		select * from editors where id = #url.rep#
	</cfquery>
    <cfset aPhone = listToArray(qSalesReps.phone, ".")>
</cfif>
<cfquery name="qStates" datasource="#request.db.dsn#">
	select stateFullName, stateAbbrName from states order by stateFullName
</cfquery>
<html>
<head>
<title>Editors</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<cfoutput>
<form action="#cgi.sript_name#?action=<cfif editMode>updateeditor<cfelse>inserteditor</cfif>" method="post">
    <table width="500" border="0" cellspacing="2" cellpadding="4">
      <tr> 
        <td class="rowHead">Editor Name</td>
        <td class="rowData"><input name="fullName" type="text" size="32" maxlength="50"<cfif editMode> value="#qSalesReps.fullName#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Address</td>
        <td class="rowData"><input name="address" type="text" size="36" maxlength="200"<cfif editMode> value="#qSalesReps.address#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">City</td>
        <td class="rowData"><input name="city" type="text" size="24" maxlength="50"<cfif editMode> value="#qSalesReps.city#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">State</td>
        <td class="rowData"><select name="state">
            <cfif not editMode>
              <option value="">Select One...</option>
            </cfif>
            <cfloop query="qStates">
            <option value="#stateAbbrName#"<cfif editMode and stateAbbrName eq qSalesReps.state> selected</cfif>>#stateFullName#</option>
            </cfloop>
          </select>        </td>
      </tr>
      <tr>
        <td class="rowHead">Zip Code</td>
        <td class="rowData"><input name="zipCode" type="text" size="12" maxlength="10"<cfif editMode> value="#qSalesReps.zipCode#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Cell Phone</td>
        <td class="rowData"><input type="text" name="phone_1"<cfif editMode and arrayLen(aPhone) eq 3> value="#aPhone[1]#"</cfif> maxlength="3" size="3" />
          &nbsp;
          <input type="text" name="phone_2"<cfif editMode and arrayLen(aPhone) eq 3> value="#aPhone[2]#" maxlength="3"</cfif> size="3" />
          &nbsp;
          <input type="text" name="phone_3"<cfif editMode and arrayLen(aPhone) eq 3> value="#aPhone[3]#" maxlength="4"</cfif> size="4" /></td>
      </tr>
      <tr>
        <td class="rowHead">Cell Carrier</td>
        <td class="rowData"><select name="phonecarrier">
        
        <cfloop index="i" list="#StructKeyList(application.smscarriers)#">
					<option  #IIF(editMode and qSalesReps.phonecarrier eq i,DE('selected="selected" selected="true"'),DE(''))# value="#i#">#application.smscarriers[i].displayname#</option>
				</cfloop>
        
            
          </select>        </td>
      </tr>
		<tr> 
        <td class="rowHead">Email Address</td>
        <td class="rowData"><input name="email" type="text" size="32" maxlength="50"<cfif editMode> value="#qSalesReps.email#"</cfif>></td>
      </tr>
      <cfif editMode>
		<tr>
          <td class="rowHead">Created</td>
		  <td class="rowData">#dateFormat(qSalesReps.dateCreated, "mm/dd/yyyy")# #timeFormat(qSalesReps.dateCreated,
		    "hh:mm tt")#</td>
	  </tr>
		<tr>
          <td class="rowHead">Last
            Modified</td>
		  <td class="rowData">#dateFormat(qSalesReps.dateModified, "mm/dd/yyyy")# #timeFormat(qSalesReps.dateModified, "hh:mm tt")#</td>
	  </tr>
		<tr>
          <td class="rowHead">Active</td>
		  <td class="rowData"><input name="active" type="checkbox" id="active" value="1"
			<cfif editMode and qSalesReps.active eq 1> checked="true"</cfif>>
          </td>
	  </tr>
      </cfif>
      <tr> 
        <td class="rowHead"><cfif editMode>
            <input type="hidden" name="id" value="#qSalesReps.id#">
          </cfif></td>
        <td class="rowData"><input type="submit" value="<cfif EditMode>Update<cfelse>Add</cfif> Photographer"></td>
      </tr>
    </table>
</form>
</cfoutput>
</body>
</html>