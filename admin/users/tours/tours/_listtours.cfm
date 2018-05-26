<cfparam name="url.rows" default="100" />
<cfparam name="url.start" default="0" />
<cfparam name="url.address" default=""/>

<cfquery name="qTours" datasource="#request.db.dsn#">
	SELECT  tourID, address, unitNumber, title, createdOn, tourTypeName, userID 
	FROM tours, tourtypes
	WHERE tours.tourTypeID = tourtypes.tourTypeID
	AND userID = #url.user#
	ORDER BY createdOn desc
     LIMIT
		<cfqueryparam cfsqltype="cf_sql_numeric" value="#url.start#">,
		<cfqueryparam cfsqltype="cf_sql_numeric" value="#url.rows#">

</cfquery>
<cfquery name="qUser" datasource="#request.db.dsn#">
	SELECT *, CASE WHEN ISNULL(ms.membershipType) THEN 'N/A' ELSE ms.membershipType END as ConciergeLevel 
    	FROM users as u 
    	LEFT OUTER JOIN brokerages as b ON u.BrokerageID = b.brokerageID 
        LEFT JOIN members as m ON m.userID = u.userID AND m.active = 1 AND m.typeID IN (4,5,6)
       	LEFT JOIN memberships as ms ON ms.id = m.typeID
        WHERE u.userID = #url.user#
</cfquery>
<html>
<head>
<title>Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="/admin/includes/admin_styles.css" rel="stylesheet" type="text/css">
<script src="/javascripts/javascript.js"></script>
<script type="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this tour?"))
		return false;
}
</script>
</head>

<body>
<cfoutput>

<a href="proc_media.cfm?initiate=true" onClick="return confirmProcess();"></a>

<table width="100%" border="0" cellspacing="2" cellpadding="2">
<tr>
<th width="20%">#qUser.lastName#, #qUser.firstName#</th>
<th width="10%">Agent ID: #qUser.mls#</th>     
<th width="30%">Brokerage: #qUser.brokerageName#</th>     
<th width="14%">Concierge Level: #qUser.ConciergeLevel#</th>
</tr>
</table>
<table border="0" cellspacing="2" cellpadding="2">
<tr>
	<td height="65" colspan="12" valign="top"><a href="proc_media.cfm?initiate=true" onClick="return confirmProcess();"></a>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="26%"><input type="button" value="Add A Tour" onClick="location.href='#cgi.script_name#?pg=editTour&user=#url.user#';">
</td>
          <form action="range.cfm" method="post">
          <td width="40%">&nbsp;</td>
          </form>
            <form action="" method="post">
          <td width="34%">&nbsp;</td>
          </form>
        </tr>
    </table></td>
    <td colspan="4"><div align="right"><cfif url.start gt 0>
		<a href="?pg=tours&user=#url.user#&start=#Evaluate(url.start - url.rows)#&rows=#url.rows#">Previous Page</a>
	</cfif>
	<cfif qTours.RecordCount eq url.rows>
		<a href="?pg=tours&user=#url.user#&start=#Evaluate(url.start + url.rows)#&rows=#url.rows#">Next Page</a>
	</cfif></div></td>
  </tr>
  <tr style="white-space:nowrap;">
  <th width="4%">TourID</th>
  <th width="19%">Address</th>
  <th width="10%">Tour Type</th>
  <th width="5%">Created</th>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Schedule<BR>Attempt </span></td>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Scheduled</span></td>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Photo<BR>Media<BR>Received</span></td>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Video<BR>Media<BR>Received</span></td>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Photo<BR>Edited</span></td>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Video<BR>Edited</span></td>
	<!---<td width="4%" bgcolor="##C3D9FF"><span class="style1">Tour Built </span></td>--->
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Realtor.com </span></td>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">MLS</span></td>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Photo<BR>Tour<BR>Finailzed </span></td>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Video<BR>Tour<BR>Finailzed </span></td>
    <td width="4%" bgcolor="##C3D9FF"><span class="style1">Followed<BR>Up </span></td>
	<td width="4%" bgcolor="##C3D9FF"><span class="style1">Delete </span></td>
	<th width="4%">&nbsp;</th>
  <th width="4%">&nbsp;</th>
  <th width="4%">&nbsp;</th>
  <th width="4%">(beta)</th>
  <th width="8%">&nbsp;</th>
	</tr>
  <cfloop query="qTours">
	  <cfset scheduledAtt = false/>
      <cfset scheduledAttnotify = false/>
      <cfset scheduled = false/>
      <cfset schedulednotify = false/>      
      <cfset received = false/>    
      <cfset VideoReceived = false/>
      <cfset edited = false/>
      <cfset VideoEdited = false/>
      <cfset tourbuilt = false/>
      <cfset realtor = false/>
      <cfset mls = false/>
      <cfset follow_up = false/>
      <cfset finalized = false/>
      <cfset finalizednotify = false/> 
      <cfset VideoFinalized = false/>
      <cfquery name="qTourPro" datasource="#request.db.dsn#">
          SELECT * FROM tourprogress WHERE tourid=#qTours.tourID#    
      </cfquery>
      <cfif qTourPro.RecordCount neq 0>
          
            <cfif qTourPro.ScheduleAttempted eq 1>
                <cfset scheduledAtt = true/>
            </cfif>
            <cfif qTourPro.ScheduleAttemptednotify eq 1>
                <cfset scheduledAttnotify = true/>
            </cfif>            
            <cfif qTourPro.Scheduled eq 1>
                <cfset scheduled = true/>
            </cfif>            
            <cfif qTourPro.Schedulednotify eq 1>
                <cfset schedulednotify = true/>
            </cfif>            
            <cfif qTourPro.MediaReceived eq 1>
                <cfset received = true/>
            </cfif>            
            <cfif qTourPro.VideoMediaReceived eq 1>
                <cfset VideoReceived = true/>
            </cfif>            
            <cfif qTourPro.Edited eq 1>
                <cfset edited = true/>
            </cfif>            
            <cfif qTourPro.VideoEdited eq 1>
                <cfset VideoEdited = true/>
            </cfif>            
            <cfif qTourPro.TourBuilt eq 1>
                <cfset tourbuilt = true/>
            </cfif>            
            <cfif qTourPro.Realtorcom eq 1>
                <cfset realtor = true/>
            </cfif>            
            <cfif qTourPro.mls eq 1>
                <cfset mls = true/>
            </cfif>            
            <cfif qTourPro.finalized eq 1>
                <cfset finalized = true/>
            </cfif>            
            <cfif qTourPro.VideoFinalized eq 1>
                <cfset VideoFinalized = true/>
            </cfif>         
            <cfif qTourPro.follow_up eq 1>
                <cfset follow_up = true/>	
            </cfif>            
            <cfif qTourPro.finalizednotify eq 1>
              	<cfset finalizednotify = true/>
            </cfif>
    	  
  	  </cfif>
  <tr bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
    <td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		<cfif createdOn lt '2009-03-22'>
			<a href="javascript:void(0);" onClick="openPopup('/../../tours/tour.cfm?tourid=#tourid#',780,570);">#tourID#</a>
		<cfelse>
		    <a href="javascript:void(0);" onClick="openPopup('/../../tours/tour.php?tourid=#qTours.tourid#',980,740);" >#tourID#</a>
		</cfif>
	</td>
    <td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><a href="../users/users.cfm?pg=editTour&tour=#tourID#">#address#<cfif qTours.unitNumber neq "">, Unit:#qTours.unitNumber#</cfif></a></td>
	<td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">#tourTypeName#</td>
	<td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">#dateFormat(createdOn, "m/d/yyyy")#</td>
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif scheduledAtt><cfif scheduledAttnotify><img src="../images/check_mark.png" TITLE="Scheduled Attempt" ><cfelse><img src="../images/check_mark-no.png" TITLE="Schedule Attemp not emailed" ></cfif></cfif></td>
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif scheduled><cfif schedulednotify><img src="../images/check_mark.png" TITLE="Scheduled" ><cfelse><img src="../images/check_mark-no.png" TITLE="Scheduled" ></cfif></cfif></td>
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif received><img src="../images/check_mark.png" TITLE="Media Received" ></cfif></td>
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif VideoReceived><img src="../images/check_mark.png" TITLE="Video Media Received" ></cfif></td>
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif edited><img src="../images/check_mark.png" TITLE="Edited" ></cfif></td>
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif VideoEdited><img src="../images/check_mark.png" TITLE=" Video Edited" ></cfif></td>
	<!---<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif tourbuilt><img src="../images/check_mark.png" TITLE="Tour Built" ></cfif></td>--->
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif realtor><img src="../images/check_mark.png" TITLE="Realtor.com" ></cfif></td>
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif mls><img src="../images/check_mark.png" TITLE="MLS" ></cfif></td>
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif finalized><cfif finalizednotify><img src="../images/check_mark.png" TITLE="Photo Tour Finalized" ><cfelse><img src="../images/check_mark-no.png" TITLE="Photo Tour Finalized not emailed" ></cfif></cfif></td>
	<td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif VideoFinalized><img src="../images/check_mark.png" TITLE="Video Tour Finalized" ></cfif></td>
    <td align="center" bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><cfif follow_up><img src="../images/check_mark.png" ></cfif></td>
	<td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><a href="../users/users.cfm?pg=slideshows&tourid=#tourID#">slideshows</a></td>
	<td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><a href="../users/users.cfm?pg=media&tour=#tourID#">media</a></td>
  <td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><a href="../users/users.cfm?pg=reorder&tour=#tourID#">reorder</a></td>
	<!---<td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><a onClick="return confirmDelete();" href="../users/users.cfm?action=deleteTour&tour=#tourID#&user=#userID#">delete</a></td>--->
    <td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A HREF="http://www.spotlighthometours.com/admin/floorplans/index.php?tourID=#tourID#">floorplans</A></td>
    <td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><a  href="../users/users.cfm?pg=toursheet&tour=#tourID#&user=#userID#">Tour Sheet</a></td>
    <td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><a onClick="return confirmDelete();" href="#cgi.script_name#?action=deleteTour&tour=#tourID#&user=#url.user#">delete</a></td>
  </tr>
</cfloop>
  <tr>
    <td colspan="12" >&nbsp;</td>
    <td colspan="4"><div align="right"><cfif url.start gt 0>
		<a href="?pg=tours&user=#url.user#&start=#Evaluate(url.start - url.rows)#&rows=#url.rows#">Previous Page</a>
	</cfif>
	<cfif qTours.RecordCount eq url.rows>
		<a href="?pg=tours&user=#url.user#&start=#Evaluate(url.start + url.rows)#&rows=#url.rows#">Next Page</a>
	</cfif></div></td>
  </tr>
</table>

</cfoutput>
</body>
</html>	
