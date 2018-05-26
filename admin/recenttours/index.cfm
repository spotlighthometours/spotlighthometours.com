<CFSILENT>
    <CFPARAM name="url.rows" default="100" />
    <CFPARAM name="url.start" default="0" />
	<CFPARAM name="url.orderby" default="orders.createdOn desc">
	<CFPARAM name="url.progorderby" default="">
	<CFPARAM name="url.tourid" default="" />
	<CFPARAM name="url.touraddress" default="" />
	<CFPARAM name="url.tourcity" default="" />
    <CFPARAM name="url.brokerageID" default="" />
    <CFPARAM name="url.editorID" default="" />
    <CFPARAM name="url.tourTypeID" default="" />
	<CFPARAM name="url.startdate" default="" />
	<CFPARAM name="url.enddate" default="" />
    <CFPARAM name="url.ostartdate" default="" />
	<CFPARAM name="url.oenddate" default="" />
	<CFPARAM name="url.videostartdate" default="" />
	<CFPARAM name="url.videoenddate" default="" />
	<CFPARAM name="tourTypeID" default="" />
	<CFPARAM name="state" default="" />
	
	<cfif len(#tourTypeID#) gt 0> 
    	<cfset tt = ' AND tt.tourTypeID = "#tourTypeID#"' /> 
    <cfelse>
    	<cfset tt = '' />
    </cfif>
	<cfif len(#state#) gt 0> 
    	<cfset st = ' AND tours.state = "#state#"' /> 
    <cfelse>
    	<cfset st = '' />
    </cfif>
    
    <cfquery name="qBrokerages" datasource="#request.db.dsn#">
		select * from brokerages order by BrokerageName
	</cfquery>
    <cfquery name="qEditors" datasource="#request.db.dsn#">
		select * from editors order by fullName
	</cfquery>
    
	<!--- A subquery had to be used because tourid is being used to join tourprogress and orders --->	
	<!--- The database isn't quite normalized --->
	<CFQUERY name="qTours" datasource="#request.db.dsn#">
		<cfif len(url.tourid) gt 0>
		    SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
			  	FROM tours
			  	LEFT JOIN orders ON tours.tourid = orders.tourid
			  	LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID
			  	WHERE orders.createdOn IS NOT NULL #tt# #st#
		        AND tours.tourID LIKE '%#url.tourid#%'  
                AND concierge = 0 
		        ORDER BY #url.orderby# 
			    LIMIT
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.start#">,
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.rows#">
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
				ORDER BY #url.progorderby#
			</cfif>
		<cfelseif len(url.touraddress) gt 0>
			SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
			  	FROM tours
			  	LEFT JOIN orders ON tours.tourid = orders.tourid
			  	LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID
			  	WHERE orders.createdOn IS NOT NULL #tt# #st#
		        AND tours.address LIKE '%#url.touraddress#%' 
                AND concierge = 0 
		        ORDER BY #url.orderby#
			    LIMIT
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.start#">,
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.rows#">
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
			ORDER BY #url.progorderby#
			</cfif>
		<cfelseif len(url.tourcity) gt 0>
			SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
			  	FROM tours
			  	LEFT JOIN orders ON tours.tourid = orders.tourid
			  	LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID
			  	WHERE orders.createdOn IS NOT NULL #tt# #st#
		        AND tours.city LIKE '%#url.tourcity#%' 
                AND concierge = 0 
		        ORDER BY #url.orderby#
			    LIMIT
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.start#">,
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.rows#">
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
			ORDER BY #url.progorderby#
			</cfif>
		<cfelseif len(url.startdate) gt 0 and len(url.enddate) gt 0>
			SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
			    FROM tourtypes tt, tourprogress tp, tours 
				LEFT JOIN orders ON tours.tourID = orders.tourid
	            WHERE tours.tourTypeID = tt.tourTypeID #tt# #st#
			    AND tp.tourid = tours.tourid 
                AND concierge = 0 
			    AND ((tp.ReScheduledon IS NULL AND tp.Scheduledon BETWEEN date('#url.startdate#') AND DATE_ADD('#url.enddate#',INTERVAL 1 DAY))                 	OR (tp.ReScheduledon BETWEEN date('#url.startdate#') AND DATE_ADD('#url.enddate#',INTERVAL 1 DAY))) 
                ORDER BY tp.Scheduledon DESC 
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
			ORDER BY #url.progorderby#
			</cfif>
        <cfelseif len(url.ostartdate) gt 0 and len(url.oenddate) gt 0>
			SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
			    FROM tourtypes tt, tourprogress tp, tours 
				LEFT JOIN orders ON tours.tourID = orders.tourid
	            WHERE tours.tourTypeID = tt.tourTypeID #tt# #st#
			    AND tp.tourid = tours.tourid 
                AND concierge = 0 
			    AND ( orders.createdOn BETWEEN date('#url.ostartdate#') AND DATE_ADD('#url.oenddate#',INTERVAL 1 DAY) ) 
                ORDER BY orders.createdOn DESC 
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
			ORDER BY #url.progorderby#
			</cfif>
		<cfelseif len(url.videostartdate) gt 0 and len(url.videoenddate) gt 0>
			SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
			    FROM tourtypes tt, tourprogress tp, tours 
				LEFT JOIN orders ON tours.tourID = orders.tourid
	            WHERE tours.tourTypeID = tt.tourTypeID #tt# #st#
			    AND tp.tourid = tours.tourid  
                AND concierge = 0 
                AND ((tp.VideoReScheduledOn IS NULL AND 
                	tp.VideoScheduledOn BETWEEN date('#url.videostartdate#') AND DATE_ADD('#url.videoenddate#',INTERVAL 1 DAY))
                    OR (tp.VideoReScheduledOn BETWEEN date('#url.videostartdate#') AND DATE_ADD('#url.videoenddate#',INTERVAL 1 DAY)))
		        ORDER BY tp.Scheduledon DESC 
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
			ORDER BY #url.progorderby#
			</cfif>
		<cfelseif len(url.brokerageID) gt 0>
			SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID,
                u.brokerageID
			  	FROM tours
			  	LEFT JOIN orders ON tours.tourid = orders.tourid
			  	LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID
                LEFT JOIN users u ON tours.userID = u.userID
			  	WHERE orders.createdOn IS NOT NULL #tt# #st#
                AND u.brokerageID = '#url.brokerageID#'  
                AND concierge = 0 
                ORDER BY #url.orderby#
			    LIMIT
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.start#">,
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.rows#">
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
			ORDER BY #url.progorderby#
			</cfif>
        <cfelseif len(url.editorID) gt 0>
			SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID,
                tp.editphotographer, tp.VideoEditPhotographer
			  	FROM tours
			  	LEFT JOIN orders ON tours.tourid = orders.tourid
			  	LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID
                LEFT JOIN tourprogress tp ON tours.tourid = tp.tourid
			  	WHERE orders.createdOn IS NOT NULL #tt# #st#
                AND (tp.editphotographer = '#url.editorID#' OR VideoEditPhotographer='#url.editorID#')  
                AND concierge = 0 
                ORDER BY tp.EditedOn DESC
			    LIMIT
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.start#">,
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.rows#">
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
			ORDER BY #url.progorderby#
			</cfif>
		<cfelseif len(url.tourTypeID) gt 0>
			SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
			  	FROM tours
			  	LEFT JOIN orders ON tours.tourid = orders.tourid
			  	LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID
                LEFT JOIN tourprogress tp ON tours.tourid = tp.tourid
			  	WHERE orders.createdOn IS NOT NULL #tt# #st#
                AND tours.tourTypeID = '#url.tourTypeID#' 
                AND concierge = 0 
                ORDER BY tp.EditedOn DESC
			    LIMIT
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.start#">,
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.rows#">
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
			ORDER BY #url.progorderby#
			</cfif>
		<cfelse>
		    SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
                CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
                t.oCreatedOn, t.oID, t.userid,
                tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
                tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
                tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
                tp.VideoReScheduledOn, tp.EditedOn
			FROM (
			    SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
                tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
			  	FROM tours
			  	LEFT JOIN orders ON tours.tourid = orders.tourid 
			  	LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID 
			  	WHERE orders.createdOn IS NOT NULL #tt# #st#  
                AND concierge = 0 
                ORDER BY #url.orderby#
			    LIMIT
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.start#">,
			    <cfqueryparam cfsqltype="cf_sql_numeric" value="#url.rows#"> 
		    ) as t
			LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
			<cfif len(trim(url.progorderby)) GT 0>
			ORDER BY #url.progorderby#
			</cfif>
		</cfif>
	</CFQUERY>
	
	<CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
	    SELECT tourTypeID, tourTypeName FROM tourtypes
	</CFQUERY>
    
    <CFQUERY name="qStates" datasource="#request.db.dsn#">
	    select stateAbbrName from states
	</CFQUERY>
   	
	<cfset range = 0>
	<cfif len(url.startdate) gt 0 and len(url.enddate) gt 0>
	    <cfset url.rows = qTours.RecordCount>
		<cfset range = 1>
	</cfif>
	
    
</CFSILENT>
<HTML>
<HEAD>
    <TITLE>Users</TITLE>
    <META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8">
    <LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
	<script src="/javascripts/javascript.js"></script>
    
	<link type="text/css" href="/admin/includes/jquery-ui-1.8.9/css/ui-lightness/jquery-ui-1.8.9.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-ui-1.8.9.custom.min.js"></script> 
    <script type="text/javascript">
	    $(function() {
		    $( "#startdate" ).datepicker();
		    $( "#startdate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#enddate" ).datepicker();
		    $( "#enddate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#ostartdate" ).datepicker();
		    $( "#ostartdate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#oenddate" ).datepicker();
		    $( "#oenddate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		    $( "#videostartdate" ).datepicker();
		    $( "#videostartdate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#videoenddate" ).datepicker();
		    $( "#videoenddate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	    });
	</script>
    <SCRIPT TYPE="text/javascript">
        function confirmDelete() {
	        if(!confirm("Are you sure you want to remove this tour?"))
			return false;
		}

		function confirmProcess() {
		    if(!confirm("This process may take several minutes depending on the number of files to process.\nAre you sure you want to continue?"))
			    return false;
		}
		
		function ShowTourType(tourTypeID, startDate, endDate) {
			var form = document.createElement("form");
			form.setAttribute("method", "post");
			form.setAttribute("action", "");
		
			var hiddenField1 = document.createElement("input");
			hiddenField1.setAttribute("type", "hidden");
			hiddenField1.setAttribute("name", "tourTypeID");
			hiddenField1.setAttribute("value", tourTypeID);
			form.appendChild(hiddenField1);
		
			if (startDate.length > 0 && endDate.length > 0) {
				var hiddenField2 = document.createElement("input");
				hiddenField2.setAttribute("type", "hidden");
				hiddenField2.setAttribute("name", "startdate");
				hiddenField2.setAttribute("value", startDate);
				form.appendChild(hiddenField2);
			
				var hiddenField3 = document.createElement("input");
				hiddenField3.setAttribute("type", "hidden");
				hiddenField3.setAttribute("name", "enddate");
				hiddenField3.setAttribute("value", endDate);
				form.appendChild(hiddenField3);
			}
			
			document.body.appendChild(form);
			form.submit();
		}
		
		function ShowState(state, startDate, endDate) {
			//alert(state);
			var form = document.createElement("form");
			form.setAttribute("method", "post");
			form.setAttribute("action", "");
		
			var hiddenField1 = document.createElement("input");
			hiddenField1.setAttribute("type", "hidden");
			hiddenField1.setAttribute("name", "state");
			hiddenField1.setAttribute("value", state);
			form.appendChild(hiddenField1);
		
			if (startDate.length > 0 && endDate.length > 0) {
				var hiddenField2 = document.createElement("input");
				hiddenField2.setAttribute("type", "hidden");
				hiddenField2.setAttribute("name", "startdate");
				hiddenField2.setAttribute("value", startDate);
				form.appendChild(hiddenField2);
			
				var hiddenField3 = document.createElement("input");
				hiddenField3.setAttribute("type", "hidden");
				hiddenField3.setAttribute("name", "enddate");
				hiddenField3.setAttribute("value", endDate);
				form.appendChild(hiddenField3);
			}
		
			document.body.appendChild(form);
			form.submit();
		}
	</SCRIPT>
    <STYLE TYPE="text/css">
        <!--
        .style1 {
	    color: #0000CC;
	    font-weight: bold; 
    	}
	    -->
	</STYLE>
</HEAD>

<BODY>
<CFOUTPUT>

<H3>#url.rows# Most Recent Tours, Starting with Row #Evaluate(url.start + 1)#</H3>

<TABLE WIDTH="100%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
    <TR>
	    <TD HEIGHT="65" COLSPAN="17" VALIGN="top"><A HREF="proc_media.cfm?initiate=true" onClick="return confirmProcess();"></A>
	        <TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
                <TR>
                    <TD>
		                
		  			</TD>
                    <TD>
		                <FORM ACTION="" METHOD="get">
			                Show Daily Queue (Photo):<BR> 
                            <INPUT NAME="startdate" TYPE="text" ID="startdate" VALUE="#url.startdate#"/>
                            <INPUT NAME="enddate" TYPE="text" ID="enddate" VALUE="#url.enddate#" />            
			                <INPUT TYPE="submit" NAME="GO" ID="GO" VALUE="GO" />
			           	</FORM>
		  			</TD>
                    <TD><FORM ACTION="" METHOD="get">
			                Show Daily Queue (Video):<BR> 
                            <INPUT NAME="videostartdate" TYPE="text" ID="videostartdate" VALUE="#url.videostartdate#"/>
                            <INPUT NAME="videoenddate" TYPE="text" ID="videoenddate" VALUE="#url.videoenddate#" />            
			                <INPUT TYPE="submit" NAME="GO" ID="GO" VALUE="GO" />
			           	</FORM>
		  			</TD>
                    <TD><FORM ACTION="" METHOD="get">
                    	Search Tour ID<BR>
                    	<INPUT NAME="tourid" TYPE="text" ID="tourid" VALUE="#url.tourid#" />
                    	<INPUT TYPE="submit" NAME="GO2" ID="GO2" VALUE="GO">
                    	<BR>
                    	</FORM></TD>
            		<TD>
		  			    <FORM ACTION="" METHOD="get">
		  				Search Tour Address<BR>
            			<INPUT NAME="touraddress" TYPE="text" ID="touraddress" VALUE="#url.touraddress#" />
			            <INPUT TYPE="submit" NAME="GO2" ID="GO2" VALUE="GO">
            			<BR>
						</FORM>
		  			</TD>
					<TD>
		  			    <FORM ACTION="" METHOD="get">
		  				Search Tour City<BR>
            			<INPUT NAME="tourcity" TYPE="text" ID="tourcity" VALUE="#url.tourcity#" />
			            <INPUT TYPE="submit" NAME="GO2" ID="GO2" VALUE="GO">
            			<BR>
						</FORM>
		  			</TD>
          		</TR>
                <TR>
                	<TD colspan="3">
                        <FORM ACTION="" METHOD="get">
                            Search Brokerage<BR>
                            <select name="brokerageID">
                                <option value="">Select One...</option>
                                <cfloop query="qBrokerages">
                                <option value="#BrokerageID#" <cfif BrokerageID eq url.BrokerageID> selected</cfif>>#BrokerageName# (desc:#brokerageDesc#)</option>
                                </cfloop>
                            </select>
                            <INPUT TYPE="submit" NAME="GO2" ID="GO2" VALUE="GO">
                        </FORM>
                    </TD>
                    <TD colspan="2">
                        <FORM ACTION="" METHOD="get">
			                Show Order Queue (All):<BR> 
                            <INPUT NAME="ostartdate" TYPE="text" ID="ostartdate" VALUE="#url.startdate#"/>
                            <INPUT NAME="oenddate" TYPE="text" ID="oenddate" VALUE="#url.enddate#" />            
			                <INPUT TYPE="submit" NAME="GO" ID="GO" VALUE="GO" />
			           	</FORM>
                    </TD>
                </TR>
                <TR>
                	<TD colspan="2">
                        <FORM ACTION="" METHOD="get">
                            Search Editor<BR>
                            <select name="editorID">
                                <option value="">Select One...</option>
                                <cfloop query="qEditors">
                                <option value="#id#" <cfif id eq url.editorID> selected</cfif>>#fullName#</option>
                                </cfloop>
                            </select>
                            <INPUT TYPE="submit" NAME="GO2" ID="GO2" VALUE="GO">
                        </FORM>
                    </TD>
                    <TD colspan="3">
                        <FORM ACTION="" METHOD="get">
                            Search Tour Type<BR>
                            <select name="tourTypeID">
                                <option value="">Select One...</option>
                                <cfloop query="qTourTypes">
                                <option value="#tourTypeID#" <cfif tourTypeID eq url.tourTypeID> selected</cfif>>#tourTypeName#</option>
                                </cfloop>
                            </select>
                            <INPUT TYPE="submit" NAME="GO2" ID="GO2" VALUE="GO">
                        </FORM>
                    </TD>
                </TR>
    		</TABLE>
		</TD>
    	<TD COLSPAN="4">
		    <DIV ALIGN="right">
				<select onChange="window.location='?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&orderby=#url.orderby#&progorderby=#url.progorderby#&rows='+this.value">
					<option value="50" <cfif url.rows eq 50>selected="selected"</cfif>>50</option>
					<option value="100" <cfif url.rows eq 100>selected="selected"</cfif>>100</option>
					<option value="150" <cfif url.rows eq 150>selected="selected"</cfif>>150</option>
					<option value="200" <cfif url.rows eq 200>selected="selected"</cfif>>200</option>
					<option value="300" <cfif url.rows eq 300>selected="selected"</cfif>>300</option>
					<option value="400" <cfif url.rows eq 400>selected="selected"</cfif>>400</option>
				</select>
				per page | 
				<cfif range neq 1>
				    <CFIF url.start gt 0>
		                <A HREF="?start=#Evaluate(url.start - url.rows)#&rows=#url.rows#&editorID=#url.editorID#&tourTypeID=#url.tourTypeID#">Previous Page</A>
				    </CFIF>
				    <CFIF qTours.RecordCount eq url.rows>
			            <A HREF="?start=#Evaluate(url.start + url.rows)#&rows=#url.rows#&editorID=#url.editorID#&tourTypeID=#url.tourTypeID#">Next Page</A>
				    </CFIF>
				</cfif>
			</DIV>
		</TD>
  	</TR>
	<tr>
		<td colspan=17>
			<h3>
			    [Results : #qTours.RecordCount#] 
				<CFLOOP query="qTourTypes">
                	<cfset ttName = replace(#qTourTypes.tourTypeName#,"'","\'")>
	                <cfset count = 0>
					<CFLOOP query="qTours">
	        		    <cfif qTours.tourTypeName eq qTourTypes.tourTypeName>
			    		    <cfset count = Evaluate(count + 1)>
						</cfif>
	    			</CFLOOP>
					<cfif count gt 0>
		     		    <a style="text-decoration: none;" href="javascript:void(0)" onClick="ShowTourType('#qTourTypes.tourTypeID#','#url.startdate#','#url.enddate#');">			
                        	[#qTourTypes.tourTypeName# : #count#]
                        </a>
					</cfif> 
				</CFLOOP><br /><br />
               	States:
                <CFLOOP query="qStates">
	                <cfset count = 0>
					<CFLOOP query="qTours">
	        		    <cfif qTours.state eq qStates.stateAbbrName>
			    		    <cfset count = Evaluate(count + 1)>
						</cfif>
	    			</CFLOOP>
					<cfif count gt 0>
                    	<a style="text-decoration: none;" href="javascript:void(0)" onClick="ShowState('#qStates.stateAbbrName#','#url.startdate#','#url.enddate#');">
			     		    [#qStates.stateAbbrName# : #count#]
                        </a>
					</cfif> 
				</CFLOOP>
			</h3>
		</td>
	</tr>
  	<TR style="white-space:nowrap; text-align:center">
  	    <TH valign="bottom" WIDTH="4%"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=tours.tourid<cfif url.orderby eq "tours.tourid">%20desc</cfif>">TourID</A></TH>
  		<TH valign="bottom" WIDTH="19%"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=tours.address<cfif url.orderby eq "tours.address">%20desc</cfif>">Address</A></TH>
  		<TH valign="bottom" WIDTH="2%">Conc.<BR>Level</TH>
  		<TH valign="bottom" WIDTH="12%">Tour Type</TH>
  		<TH valign="bottom" WIDTH="2%" align="center">A</TH>
  		<TH valign="bottom" WIDTH="5%"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=orders.createdOn<cfif url.orderby eq "orders.createdOn">%20desc</cfif>">Created</A></TH>
		<TH valign="bottom" WIDTH="5%"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.Scheduledon<cfif url.progorderby eq "tp.Scheduledon">%20desc</cfif>">Scheduled<BR>On</A></TH>
		<TH valign="bottom" WIDTH="5%"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.Editedon<cfif url.progorderby eq "tp.Scheduledon">%20desc</cfif>">Edited<BR>On</A></TH>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.ScheduleAttempted<cfif url.progorderby neq "tp.ScheduleAttempted%20desc">%20desc</cfif>"><SPAN CLASS="style1">Schedule<BR>Attempt </SPAN></A></TD>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.Scheduled<cfif url.progorderby neq "tp.Scheduled desc">%20desc</cfif>"><SPAN CLASS="style1">Scheduled</SPAN></A></TD>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.MediaReceived<cfif url.progorderby neq "tp.MediaReceived desc">%20desc</cfif>"><SPAN CLASS="style1">Photo<BR>Media<BR>Received</SPAN></A></TD>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tourCategory<cfif url.progorderby neq "tourCategory desc, tp.VideoMediaReceived desc">%20desc</cfif>, tp.VideoMediaReceived<cfif url.progorderby neq "tourCategory desc, tp.VideoMediaReceived desc">%20desc</cfif>"><SPAN CLASS="style1">Video<BR>Media<BR>Received</SPAN></A></TD>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.Edited<cfif url.progorderby neq "tp.Edited desc">%20desc</cfif>"><SPAN CLASS="style1">Photo<BR>Edited</SPAN></A></TD>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tourCategory<cfif url.progorderby neq "tourCategory desc, tp.VideoEdited desc">%20desc</cfif>, tp.VideoEdited<cfif url.progorderby neq "tourCategory desc, tp.VideoEdited desc">%20desc</cfif>"><SPAN CLASS="style1">Video<BR>Edited</SPAN></A></TD>
		<!---<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.TourBuilt<cfif url.progorderby neq "tp.TourBuilt desc">%20desc</cfif>"><SPAN CLASS="style1">Tour Built </SPAN></A></TD>--->
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.Realtorcom<cfif url.progorderby neq "tp.Realtorcom desc">%20desc</cfif>"><SPAN CLASS="style1">Realtor<BR>.com </SPAN></A></TD>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.mls<cfif url.progorderby neq "tp.mls desc">%20desc</cfif>"><SPAN CLASS="style1">MLS</SPAN></A></TD>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.finalized<cfif url.progorderby neq "tp.finalized desc">%20desc</cfif>"><SPAN CLASS="style1">Photo<BR>Tour<BR>Finalized </SPAN></A></TD>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tourCategory<cfif url.progorderby neq "tourCategory desc, tp.VideoFinalized desc">%20desc</cfif>, tp.VideoFinalized<cfif url.progorderby neq "tourCategory desc, tp.VideoFinalized desc">%20desc</cfif>"><SPAN CLASS="style1">Video<BR>Tour<BR>Finalized </SPAN></A></TD>
		<TD valign="bottom" WIDTH="4%" BGCOLOR="##C3D9FF"><A HREF="#cgi.script_name#?startdate=#url.startdate#&enddate=#url.enddate#&brokerageID=#url.brokerageID#&rows=#url.rows#&orderby=#url.orderby#&progorderby=tp.follow_up<cfif url.progorderby neq "tp.follow_up desc">%20desc</cfif>"><SPAN CLASS="style1">Followed<BR>Up </SPAN></A></TD>
        <TH WIDTH="6%"></TH>
        <TH WIDTH="4%"></TH>
        <TH WIDTH="4%"></TH>
        <TH WIDTH="4%"></TH>
  		<TH valign="bottom" WIDTH="4%">(beta)</TH>
  		<TH WIDTH="10%"></TH>
	</TR>
    <CFLOOP query="qTours">
        <cftry>
        <CFQUERY name="qOrderType" datasource="#request.db.dsn#">
	    	SELECT type FROM orderdetails WHERE orderID = #qTours.oID#
		</CFQUERY>
        <cfif qOrderType.type eq "product">
			<cfset additional ='Y'>
		<cfelse>
			<cfset additional =''>
		</cfif>
        <CFQUERY name="qConcierge" datasource="#request.db.dsn#">
            SELECT CASE WHEN ISNULL(ms.membershipType) THEN 'N/A' ELSE SUBSTRING(ms.membershipType,1,1) END as ConciergeLevel 
                FROM users u
                LEFT JOIN members as m ON m.userID = u.userID AND m.active = 1 AND m.typeID IN (4,5,6)
                LEFT JOIN memberships as ms ON ms.id = m.typeID
                WHERE u.userID = #qTours.userID# 
		</CFQUERY>
        <cfcatch type="any">
        	<cfset additional =''>
            <cfset qConcierge.ConciergeLevel ='N/A'>
        </cfcatch>
        </cftry>
  		<TR BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
    	    <TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
			    <cfif createdOn lt '2009-03-22'>
				    <a href="javascript:void(0);" onClick="openPopup('/../../tours/tour.cfm?tourid=#tourid#',780,570);">#tourID#</a>
				<cfelse>
		    	    <a href="javascript:void(0);" onClick="openPopup('/../../tours/tour.cfm?tourid=#qTours.tourid#',980,740);" >#tourID#</a>
				</cfif>
			</TD>
    		<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A HREF="../users/users.cfm?pg=editTour&tour=#tourID#">#address#
						<cfif qTours.unitNumber neq "">, Unit:#qTours.unitNumber#</cfif><br />#city#, #state#</A></TD>
			<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A HREF="../users/users.cfm?pg=tours&user=#qTours.userID#">
						#qConcierge.ConciergeLevel#</TD>
			<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">#tourTypeName#</TD>
			<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#" align="center">#additional#</TD>
    		<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">#dateFormat(createdOn, "m/d/yyyy")#</TD>
			<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
				<CFIF #len(ReScheduledon)# gt 0>*#dateFormat(ReScheduledon, "m/d/yyyy")#<CFELSE>#dateFormat(Scheduledon, "m/d/yyyy")#</CFIF>
                <cfif #isVideoTour# eq 1>
	                <BR>
                    <font color="blue">
    	        	<CFIF #len(VideoReScheduledOn)# gt 0>*#dateFormat(VideoReScheduledOn, "m/d/yyyy")#
        	        <CFELSE>#dateFormat(VideoScheduledOn, "m/d/yyyy")#</CFIF>
                    </font>
                </cfif>
            </TD>
    		<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">





                #qTours.EditedOn#




			</TD>
    		<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    	    <CFIF ScheduleAttempted eq 1>
		            <CFIF ScheduleAttemptednotify eq 1>
			            <IMG SRC="../images/check_mark.png" TITLE="Scheduled Attempt" >
			        <CFELSE>
			            <IMG SRC="../images/check_mark-no.png" TITLE="Schedule Attempt not emailed" >
			        </CFIF>
		        </CFIF>
			</TD>
	<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <CFIF Scheduled eq 1>
		    <CFIF Schedulednotify eq 1>
			    <IMG SRC="../images/check_mark.png" TITLE="Scheduled" >
			<CFELSE>
			    <IMG SRC="../images/check_mark-no.png" TITLE="Scheduled Not Emailed" >
			</CFIF>
		</CFIF>
	</TD>
	<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <CFIF MediaReceived eq 1>
		    <IMG SRC="../images/check_mark.png" TITLE="Photo Media Received" >
		</CFIF>
	</TD>
	<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
    	<cfif tourCategory eq 1>
			<CFIF VideoMediaReceived eq 1>
                <IMG SRC="../images/check_mark.png" TITLE="Video Media Received" >
            </CFIF>
        <cfelse>
        	N/A
        </cfif>
	</TD>
	<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <CFIF Edited eq 1>
		    <IMG SRC="../images/check_mark.png" TITLE="Photo Edited" >
		</CFIF>
	</TD>
	<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <cfif tourCategory eq 1>
			<CFIF VideoEdited eq 1>
		    	<IMG SRC="../images/check_mark.png" TITLE="Video Edited" >
			</CFIF>
        <cfelse>
        	N/A
        </cfif>
	</TD>
	<!---<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <CFIF TourBuilt eq 1>
	        <IMG SRC="../images/check_mark.png" TITLE="Tour Built" >
		</CFIF>
	</TD>--->
	<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <CFIF Realtorcom eq 1>
		    <IMG SRC="../images/check_mark.png" TITLE="Realtor.com" >
		</CFIF>
	</TD>
	<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <CFIF mls eq 1>
		    <IMG SRC="../images/check_mark.png" TITLE="MLS" >
		</CFIF>
	</TD>
	<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <CFIF finalized eq 1>
		    <CFIF finalizednotify eq 1>
		        <IMG SRC="../images/check_mark.png" TITLE="Photo Tour Finalized" >
		    <CFELSE>
		        <IMG SRC="../images/check_mark-no.png" TITLE="Photo Tour Finalized not emailed" >
			</CFIF>
		</CFIF>
	</TD>
	<TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <cfif tourCategory eq 1>
			<CFIF VideoFinalized eq 1>
                <IMG SRC="../images/check_mark.png" TITLE="Video Finalized" >
            </CFIF>
        <cfelse>
        	N/A
        </cfif>
	</TD>
    <TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
	    <CFIF follow_up eq 1>
			<IMG SRC="../images/check_mark.png" TITLE="Followed Up" >
		</CFIF>
	</TD>
    <TD ALIGN="center" BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A href="javascipt:openPopup('http://www.spotlighthometours.com/admin/photographers/assign-tour-affiliate.php?tourID=#tourID#',800,400)" onClick="openPopup('http://www.spotlighthometours.com/admin/photographers/assign-tour-affiliate.php?tourID=#tourID#',800,400)">affiliate</A></TD>
    <!---<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A HREF="../../users/new/slideshow-manager.php?tourID=#tourID#">slideshows</A></TD>--->
	<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A HREF="../users/users.cfm?pg=slideshows&tourid=#tourID#">slideshows</A></TD>
	<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A HREF="http://www.spotlighthometours.com/admin/floorplans/index.php?tourID=#tourID#">floorplans</A></TD>
	<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A HREF="../users/users.cfm?pg=media&tour=#tourID#">media</A></TD>
	<TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A HREF="../users/users.cfm?pg=reorder&tour=#tourID#">reorder</A></TD>
	<!---<td bgcolor="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><a onClick="return confirmDelete();" href="../users/users.cfm?action=deleteTour&tour=#tourID#&user=#userID#">delete</a></td>--->
    <TD BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#"><A  href="../users/users.cfm?pg=toursheet&tour=#tourID#&user=#userID#">Tour Sheet</A></TD>
  </TR>
  </CFLOOP>
  <TR>
    <TD COLSPAN="12" ></TD>
    <TD COLSPAN="4">
	    <DIV ALIGN="right">
		    <cfif range neq 1>
			    <CFIF url.start gt 0>
		            <A HREF="?start=#Evaluate(url.start - url.rows)#&rows=#url.rows#&editorID=#url.editorID#&tourTypeID=#url.tourTypeID#">Previous Page</A>
				</CFIF>
				<CFIF qTours.RecordCount eq url.rows>
			        <A HREF="?start=#Evaluate(url.start + url.rows)#&rows=#url.rows#&editorID=#url.editorID#&tourTypeID=#url.tourTypeID#">Next Page</A>
				</CFIF>
			</cfif>
		</DIV>
	</TD>
  </TR>
</TABLE>
</CFOUTPUT>
</BODY>
</HTML>
