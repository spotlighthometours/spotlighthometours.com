
<cfquery name="qTours" datasource="#request.db.dsn#">
	select title, t.walkthrus + tt.walkthrus as walkthrus, t.videos + tt.videos as videos, t.panoramics as panoramics, t.photos + tt.photos as photos
	from tours t inner join tourtypes tt on t.tourTypeID = tt.tourTypeID
	where tourID = #url.tour#
</cfquery>
<cfquery name="qMedia" datasource="#request.db.dsn#">
	select * from media where tourID = #url.tour# order by mediaType desc, sortOrder, createdOn desc
</cfquery>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Tour Media</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="PRAGMA" content="NO-CACHE">
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<link href="/admin/includes/admin_styles.css" rel="stylesheet" type="text/css">
<script src="/repository_inc/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove these files?")){
		return false;
    }else{
        $("input[type='checkbox']").each(function(index){
			if( $(this).prop('name') == 'mediaIDs' ){
				if( $(this).prop('checked') ){
					$.ajax({
						url: '/repository_queries/admin_delete_media.php?mediaID=' + $(this).prop('value')
					}).done(function(){
						//
					});
				}
			}
        });
    }
    return false;
}

function checkForm(formElement) {
	if(formElement.value == '' || isNaN(formElement.value)) {
		alert('Please make sure you have a valid sort order for each media item.');
		return formElement.focus();
	}		
}
</script>
</head>

<body>
<cfoutput>
<div class="msg">#msg#</div>
<div align="right" style="width: 600px;">

<form action="#cgi.script_name#?pg=editMedia&tour=#url.tour#" method="post">
Select media type to add media to this tour.
<select name="mediaType">
<option value="" selected="selected">Select One..</option>
<option value="walkthru">Video Walk Thru</option>
<option value="video">Room/Scene Video</option>
<option value="panoramic">Panoramic</option>
<option value="photo">Photo</option>
</select>
&nbsp;<input type="submit" value="Add Media" />
</form>
</div>
<form action="#cgi.script_name#?action=media&tour=#url.tour#" method="post">
 <table width="600" cellpadding="5" cellspacing="2">
  <tr>
   <td class="sectionHead" colspan="2">Media Limitations</td>
  </tr>
  <tr>
   <td colspan="2" class="rowData">
  Video Walk Thrus(<strong>#qTours.walkthrus#</strong>)&nbsp;&nbsp;&nbsp;
  Room/Scene Videos(<strong>#qTours.videos#</strong>)&nbsp;&nbsp;&nbsp;
  Panoramics(<strong>#qTours.panoramics#</strong>)&nbsp;&nbsp;&nbsp;
  Photos(<strong>#qTours.photos#</strong>)
  </td>
  </tr>
  <tr>
   <td class="sectionHead" colspan="2">
	
	<input type="submit" name="delete" value="delete selected media" onclick="return confirmDelete();" />
	<input type="submit" name="updatesortorder" value="update media display order" />
	<input type="submit" name="updateshowontab" value="save show photos on tab" />
	
	</td>
  </tr>
  <cfloop query="qMedia">
		<tr>
		<td width="35%" class="rowHead" align="center">
		<a href="#cgi.script_name#?pg=editMedia&media=#mediaID#">
			<cfif mediaType eq "walkthru" or mediaType eq "video">
				<img src="../images/icon_video.gif" border="1" />
			<cfelseif mediaType eq "panoramic">
				<img src="../images/icon_quicktimevr.gif" border="1" />
			<cfelse>
				<img src="../../images/tours/#url.tour#/#mediaType#_th_#mediaID#.#fileExt#" border="1" />
			</cfif>
			</a><br />
			<input type="checkbox" name="mediaIDs" value="#mediaID#" /><br>
			<input type="text" name="sortOrder" value="#sortOrder#" size="3" onBlur="checkForm(this);">
			<input type="hidden" name="mediaID" value="#mediaID#">
			</td>
		 <td width="65%" valign="top" class="rowData">
		 <strong>#room# (Media ID: #mediaID#)</strong><br />
		 <div class="description">#left(description, 300)#</div>
		 <cfif mediaType eq "photo">
		 <input type="checkbox" name="show_on_tab"  value="#mediaID#"<cfif show_on_tab> checked="checked"</cfif> /> Show on photos tab
		 </cfif>
		 </td>
		</tr>
	</cfloop>
	
  <cfif not qMedia.recordCount>
  <tr>
  	<td class="rowData" colspan="2" height="50" align="center">There is no media associated with this tour.</td>
  </tr>
  </cfif>
  <tr>
   <td class="sectionHead" colspan="2">
	<input type="hidden" name="tourid" value="#url.tour#" />
	<input type="submit" name="delete" value="delete selected media" onclick="return confirmDelete();" />
	<input type="submit" name="updatesortorder" value="update media display order" />
	<input type="submit" name="updateshowontab" value="save show photos on tab" />
	
	</td>
  </tr>
</table>
</form>
</cfoutput>
</body>
</html>
