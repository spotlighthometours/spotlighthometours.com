<cfset editMode = iif(isDefined("url.media"), true, false)>
<cfif editMode>
	<cfquery name="qMedia" datasource="#request.db.dsn#" maxrows="1">
		select * from media where mediaID = #url.media#
	</cfquery>
	<cfset form.mediaType = qMedia.mediaType>
	<cfset url.tour = qMedia.tourID>
</cfif>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Tour Media</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/admin/includes/admin_styles.css" rel="stylesheet" type="text/css">

</head>

<body>
<cfoutput>
	  <cfif listContains("video,walkthru",form.mediaType)>
	  <form action="#cgi.script_name#?action=<cfif editMode>updateMedia<cfelse>uploadMedia</cfif>" method="post" enctype="multipart/form-data">
	  <table width="450" cellpadding="5" cellspacing="2">
 		 <tr>
  		  <td class="sectionHead" colspan="2">Flash Video File</td>
		 </tr>
 		 <tr>
  		  <td class="rowHead">Select File</td>
		  	<td class="rowData"><input type="file" name="flv_file" /></td>
		 </tr>
		 <tr>
  		  <td  class="sectionHead" colspan="2">Media Description</td>
		 </tr>
		 <tr>
  		  <td class="rowHead">Room</td>
		  <td class="rowData"><input type="text" name="room"<cfif editMode> value="#qMedia.room#"</cfif> maxlength="50" size="30" /></td>
		 </tr>
		 <tr>
  		  <td class="rowHead" valign="top">Description</td>
		  <td class="rowData"><textarea name="description" rows="4" style="width: 300px;"><cfif editMode>#qMedia.description#</cfif></textarea></td>
		 </tr>
		 <tr>
		 	<td class="rowHead">&nbsp;</td>
		 	<td class="rowData">
			<input type="hidden" name="mediaType" value="#form.mediaType#" />
			<cfif editMode>
			<input type="hidden" name="mediaID" value="#url.media#" />
			</cfif>
		<input type="hidden" name="tourID" value="#url.tour#" /><input type="submit" value="Upload Files" />
			</td>
		 </tr>
		</table>
		</form>
	  <cfelseif isDefined("form.mediaType")>
	  <cfif not len(form.mediaType)>
			<cfthrow type="fileUpload" message="Invalid media type.">
	  </cfif>
	  <form action="#cgi.script_name#?action=<cfif editMode>updateMedia<cfelse>uploadMedia</cfif>" method="post" enctype="multipart/form-data">
	   <table width="450" cellpadding="5" cellspacing="2">
        <tr> 
          <td  class="sectionHead" colspan="2">Upload #form.mediatype#</td>
        </tr>
        <tr> 
          <td class="rowHead">Select file to upload</td>
          <td class="rowData"><input type="file" name="mediaFile" /></td>
        </tr>
        <tr>
          <td class="rowHead">Make Tour Icon</td>
          <td class="rowData"><input name="tourIcon" type="checkbox" id="tourIcon" value="1"<cfif editMode and qMedia.tourIcon> checked</cfif> /></td>
        </tr>
        <tr> 
          <td class="rowHead">Room</td>
          <td class="rowData"><input type="text" name="room"<cfif editMode> value="#qMedia.room#"</cfif> maxlength="50" size="30" /></td>
        </tr>
        <tr> 
          <td class="rowHead" valign="top">Description</td>
          <td class="rowData"><textarea name="description" rows="4" style="width: 300px;"><cfif editMode>#qMedia.description#</cfif></textarea></td>
        </tr>
        <tr> 
          <td class="rowHead">&nbsp;</td>
          <td class="rowData"> <input type="hidden" name="mediaType" value="#lcase(form.mediaType)#" /> 
            <cfif editMode>
              <input type="hidden" name="mediaID" value="#url.media#" />
            </cfif> &nbsp;
            <input type="hidden" name="tourID" value="#url.tour#" />
            <input type="submit" value="Upload/Update File" /> </td>
        </tr>
      </table>
	  
	  </form>
	  </cfif>
</cfoutput>
</body>
</html>
