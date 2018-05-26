<cfset editMode = iif(isDefined("url.tourType"), true, false)>
<cfparam name="bHidden" default="0" />
<cfparam name="tCategory" default="0" />
<cfif editMode>
	<cfquery name="qTourTypes" datasource="#request.db.dsn#">
		select * from tourTypes where tourTypeID = #url.tourType#
	</cfquery>
    

	<cfif qTourTypes.hidden eq 1>
		<cfset bHidden = 1 />
        
	</cfif>
    <cfset tCategory=qTourTypes.tourCategory />
</cfif>

<html>
<head>
<title>Tour Types</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<cfoutput>
<form action="#cgi.sript_name#?action=<cfif editMode>updateTourType<cfelse>insertTourType</cfif>" method="post">
    <table width="500" border="0" cellspacing="2" cellpadding="4">
      <tr>
        <td class="rowHead">Tour Type Name</td>
        <td class="rowData"><input name="tourTypeName" type="text" size="34" maxlength="50"<cfif editMode> value="#qTourTypes.tourTypeName#"</cfif>></td>
      </tr>
      <tr>
        <td class="rowHead">Tour Category>/td>
        <td class="rowData">
        <select name="tourCategory">
				<option value="Do It Yourself Tours" #iif(tCategory eq 0,DE('selected="true"'),DE(''))#>Do It Yourself Tours</option>
				<option value="Motion Photo Tours" #iif(tCategory eq 'Motion Photo Tours<',DE('selected="true"'),DE(''))#>Motion Photo Tours</option>
                <option value="Still Photo Tours" #iif(tCategory eq 'Still Photo Tours',DE('selected="true"'),DE(''))#>Still Photo Tours</option>
                <option value="Video Tours" #iif(tCategory eq 'Video Tours',DE('selected="true"'),DE(''))#>Video Tours</option>
			</select>
        </td>
      </tr>
		<tr>
        <td class="rowHead">Video Walk Thrus</td>
        <td class="rowData"><input name="walkthrus" type="text" size="4" maxlength="3"<cfif editMode> value="#qTourTypes.walkthrus#"</cfif>></td>
      </tr>
		<tr>
        <td class="rowHead">Room/Scene Videos</td>
        <td class="rowData"><input name="videos" type="text" size="4" maxlength="3"<cfif editMode> value="#qTourTypes.videos#"</cfif>></td>
      </tr>
		<tr>
        <td class="rowHead">Panoramics</td>
        <td class="rowData"><input name="panoramics" type="text" size="4" maxlength="3"<cfif editMode> value="#qTourTypes.panoramics#"</cfif>></td>
      </tr>
		<tr>
        <td class="rowHead">Photos</td>
        <td class="rowData"><input name="photos" type="text" size="4" maxlength="3"<cfif editMode> value="#qTourTypes.photos#"</cfif>></td>
      </tr>
		<tr>
        <td class="rowHead">Unit Price</td>
        <td class="rowData"><input name="unitPrice" type="text" size="10" maxlength="20"<cfif editMode> value="#dollarFormat(qTourTypes.unitPrice)#"</cfif>></td>
      </tr>
		<tr>
        <td valign="top" class="rowHead">Description</td>
        <td class="rowData"><textarea name="description" rows="4" style="width: 300px;"><cfif editMode>#qTourTypes.description#</cfif></textarea></td>
      </tr>
	  <tr>
        <td valign="top" class="rowHead">Preview Only Tour?</td>
        <td class="rowData"><input type="checkbox" name="mobileonly"
			<cfif editMode>
				<cfif qtourtypes.mobileOnly eq 1>
					CHECKED
				</cfif>
			</cfif>/></td>
      </tr>
      <tr>
        <td valign="top" class="rowHead">Monthly?</td>
        <td class="rowData"><input type="checkbox" name="monthly"
			<cfif editMode>
				<cfif qtourtypes.monthly eq 1>
					CHECKED
				</cfif>
			</cfif>/></td>
      </tr>
		<tr>
        <td valign="top" class="rowHead">Hidden?</td>
        <td class="rowData">
			<select name="hidden">
				<option value="0" #iif(bHidden eq 0,DE('selected="true"'),DE(''))#>No</option>
				<option value="1" #iif(bHidden eq 1,DE('selected="true"'),DE(''))#>Yes</option>
			</select>
		</td>
      </tr>
      <tr>
        <td class="rowHead"><cfif editMode>
            <input type="hidden" name="tourTypeID" value="#qTourTypes.tourTypeID#">
          </cfif></td>
        <td class="rowData"><input type="submit" value="<cfif EditMode>Update<cfelse>Add</cfif> Tour Type"></td>
      </tr>
    </table>
</form>
</cfoutput>
</body>
</html>
