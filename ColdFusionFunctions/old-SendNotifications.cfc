<cfcomponent>
	<CFFUNCTION name="sendEmailNotificationBroker" access="public" hint="Send Email Notification To User">
	<CFARGUMENT name="notifyType" type="string" required="Yes">
    <CFARGUMENT name="notifyString" type="string" required="Yes">
    <CFARGUMENT name="userID" type="string" required="Yes">
    <CFARGUMENT name="brokerid" type="string" required="Yes">
    <CFARGUMENT name="email" type="string" required="Yes">
    <CFARGUMENT name="tourid" type="string" required="Yes">
    <CFARGUMENT name="tourt" type="string" required="No" default="0">
    
    <CFQUERY name="qUser" datasource="#request.db.dsn#">
        SELECT userID FROM tours WHERE tourID = #arguments.tourid#
    </CFQUERY>
    
    <CFQUERY name="qGetUserDetails" datasource="#request.db.dsn#">
    	Select firstname,lastname,username from users where userID='#qUser.userID#' limit 1
    </CFQUERY>
    
    <CFQUERY name="qGetBrokerDetails" datasource="#request.db.dsn#">
    	Select BrokerageName from brokerages where brokerageid='#arguments.brokerid#' limit 1
    </CFQUERY>
 
<!---<cfdump var="#qGetBrokerDetails#" />--->
    <CFSET emailTo =arguments.email/>
   
    <CFOUTPUT>Teams email:#emailTo#<br /></CFOUTPUT>
    
    <CFQUERY name="qGetTourDetails" datasource="#request.db.dsn#">
    	SELECT tp.*,
            p1.fullname as ScheduledPhotographer,
            p2.fullname as ReScheduledPhotographer,
            p3.fullname as VideoScheduledPhotographer,
            p4.fullname as VideoReScheduledPhotographer,
            
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(Scheduledon) THEN NOW() ELSE Scheduledon END) as ScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(ReScheduledon) THEN NOW() ELSE ReScheduledon END) as ReScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoScheduledOn) THEN NOW() ELSE VideoScheduledOn END) as VideoScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoReScheduledOn) THEN NOW() ELSE VideoReScheduledOn END) as VideoReScheduledDiff,
            
			date_format(ScheduleAttemptedon,'%h:%i:%s %p') as ScheduleAttat,
            date_format(ScheduleAttemptedon,'%W, %M %D %Y') as ScheduleAtton,
            
            date_format(Scheduledon,'%h:%i:%s %p') as Scheduledat,
            date_format(Scheduledon,'%W, %M %D %Y') as ScheduledDate,
            date_format(ReScheduledon,'%h:%i:%s %p') as ReScheduledat,
            date_format(ReScheduledon,'%W, %M %D %Y') as ReScheduledDate,
            
            date_format(VideoScheduledOn,'%h:%i:%s %p') as VideoScheduledat,
            date_format(VideoScheduledOn,'%W, %M %D %Y') as VideoScheduledDate,
            date_format(VideoReScheduledOn,'%h:%i:%s %p') as VideoReScheduledat,
            date_format(VideoReScheduledOn,'%W, %M %D %Y') as VideoReScheduledDate
        FROM tourprogress tp
	        LEFT JOIN photographers p1 ON tp.photographer = p1.photographerID
    	    	LEFT JOIN photographers p2 ON tp.rephotographer = p2.photographerID
        	LEFT JOIN photographers p3 ON tp.VideoPhotographer = p3.photographerID
         	LEFT JOIN photographers p4 ON tp.VideoRePhotographer = p4.photographerID
        WHERE tp.tourid='#arguments.tourid#' <cfif tourt gt 0>AND tp.tourTypeID = '#arguments.tourt#'</cfif> limit 1
    </CFQUERY>
    <CFQUERY name="qTours" datasource="#request.db.dsn#">
        select t.tourid, t.tourTypeID, tt.tourCategory,
        	(SELECT group_concat(mlsID separator ', ') FROM tour_to_mls WHERE tourID=t.tourID) as mls,
            t.address,t.city,t.state,t.zipCode,t.title,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN 
        from tours t, tourtypes tt<cfif tourt gt 0>, tourprogress tp</cfif>
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#">
        	<cfif tourt gt 0> AND tt.tourTypeID = '#arguments.tourt#'<cfelse> AND t.tourTypeID = tt.tourTypeID</cfif>
    </CFQUERY>
    
	<CFIF emailTo neq "">
        <CFSWITCH expression="#arguments.notifyString#">
            <CFCASE value="scheduleattempt">
    
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Schedule Attempt: #qTours.address#"
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
#qGetBrokerDetails.BrokerageName#
#qGetUserDetails.firstname# #qGetUserDetails.lastname#

We have received your tour order for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode# with Tour Type: #qTours.tourTypeN#

An attempt was made to contact you regarding your order on #qGetTourDetails.ScheduleAtton# at #qGetTourDetails.ScheduleAttat#. Please call us back to schedule a shoot with a photographer or if you need to cancel your order. 

Thank you. 

SpotlightHomeTours 
(801) 466-4074 
(888) 838-8810 

                </cfmail>
            </CFCASE>
                
            <CFCASE value="scheduled">
                <cfsavecontent variable="mailText">
                    <cfoutput>
                    <p>
Hello, #qGetBrokerDetails.BrokerageName# #qGetUserDetails.firstname# #qGetUserDetails.lastname#
</p>
<p>
Thank you for making an appointment with us.  You are confirmed for the appointment(s) listed below. 
</p>
Tour Title: #qTours.title#<br>
Address:  #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#<br>
Tour Type: #qTours.tourTypeN#<br>
<br>
Photo Shoot Appointment <cfif #qGetTourDetails.ReScheduledDiff# neq 0>(Re Shoot)</cfif><br>
    <ul style='list-style: none;margin:0px;'>
     <li>Date:  <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledDate#<cfelse>#qGetTourDetails.ScheduledDate#</cfif></li>
     <li>Time: <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledat#<cfelse>#qGetTourDetails.Scheduledat#</cfif> </li>
     <li>Photographer: <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledPhotographer#<cfelse>#qGetTourDetails.ScheduledPhotographer#</cfif></li>
    </ul>
					<CFIF #qGetTourDetails.isVideoTour# eq 1>
<br>                    
Video Shoot Appointment <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>(Re Shoot)</cfif><br>
    <ul style='list-style: none;margin:0px;'>
     <li>Date:  <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledDate#<cfelse>#qGetTourDetails.VideoScheduledDate#</cfif></li>
     <li>Time: <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledat#<cfelse>#qGetTourDetails.VideoScheduledat#</cfif></li> 
     <li>Video Photographer: <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledPhotographer#<cfelse>#qGetTourDetails.VideoScheduledPhotographer#</cfif></li>
     </ul>
					</CFIF>
<p>
See attachment below for a Home Photography Checklist. Make sure your property is photo-ready for it's best presentation! Click <a href='http://www.spotlighthometours.com/homeowner_email.php?tourId=#arguments.tourid#'>here</a> to send both the appointment confirmation and the photo-ready checklist to the homeowner or tenant.
</p>
<p>
*PLEASE NOTE* If you need to cancel or reschedule your appointment, please notify 24 hours prior to the appointment. Any cancellations within 24 hours may be subject to a $25 cancellation fee.
</p>
<p>
*This is an automated email. PLEASE DO NOT REPLY. For questions or comments, email support@spotlighthometours.com. 
</p>
                    </cfoutput>
                </cfsavecontent>       
<cfhttp url="http://spotlighthometours.com/repository_queries/admin_send_notification.php" method="post" result="result" charset="utf-8"> 
    <cfhttpparam type="formfield" name="tourId" value="#arguments.tourid#">
    <cfhttpparam type="formfield" name="type" value="scheduled"> 
    <cfhttpparam type="formfield" name="level" value="broker"> 
    <cfhttpparam type="formfield" name="form" value="email"> 
    <cfhttpparam type="formfield" name="to" value="#emailTo#"> 
    <cfhttpparam type="formfield" name="mailText" value="#mailText#">
    <cfhttpparam type="formfield" name="subject" value="Spotlight Appointment Confirmation: #qTours.address#">
</cfhttp>  
            </CFCASE>
         	<CFCASE value="finalized">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour is ready! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#" type="html">
#qGetBrokerDetails.BrokerageName#<br/>
#qGetUserDetails.firstname# #qGetUserDetails.lastname#,<br/><br/>

We are pleased to inform you that your virtual tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# is online and available for viewing by clicking the link below:<br/><br/>

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#<br/><br/>

You can also download the high and low resolution images by clicking on one of the links below: <br/><br/>

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#<br/>
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#<br/>
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#<br/><br/>
[ 1800 x 1200 ] http://www.spotlighthometours.com/download-1800.php?id=#qTours.tourid#
				<CFIF #qTours.tourCategory# eq 'Video Tours'>
                
You will receive a second email confirmation when the video is available for viewing.<br/><br/>
				</CFIF>                    
                
We will attempt to add the tour to the MLS for you <strong><font color="FF0000">if your local MLS allows it</font></strong>. Make sure your MLS number is input in your Spotlight account. If you did not have a MLS number when the tour was ordered you can login to your Spotlight account to update this information and we will receive an automatic notification of the change.<br/><br/> 

Please feel free to contact us with any questions or problems with your tour.<br/><br/>

Spotlight Home Tours<br/>
801-466-4074<br/>
888-838-8810<br/>
support@spotlighthometours.com
                </cfmail>

            </CFCASE>
            
            <CFCASE value="video finalized">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour Video is ready! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
#qGetBrokerDetails.BrokerageName#
#qGetUserDetails.firstname# #qGetUserDetails.lastname#,

We are pleased to inform you that your video for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
[ 1800 x 1200 ] http://www.spotlighthometours.com/download-1800.php?id=#qTours.tourid#
                
Please feel free to contact us with any questions or problems with your tour.

Spotlight Home Tours
801-466-4074
888-838-8810
support@spotlighthometours.com
                </cfmail>
            </CFCASE>
            
            <CFCASE value="tour updated">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour has been updated! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
#qGetBrokerDetails.BrokerageName#
#qGetUserDetails.firstname# #qGetUserDetails.lastname#,
	
Your tour has been updated for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# and is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
[ 1800 x 1200 ] http://www.spotlighthometours.com/download-1800.php?id=#qTours.tourid#
                
Please feel free to contact us with any questions or problems with your tour.

Spotlight Home Tours
801-466-4074
888-838-8810
support@spotlighthometours.com
                </cfmail>
            </CFCASE>
        
            <CFDEFAULTCASE>
                <CFSET strTo = "" />
            </CFDEFAULTCASE>
        </CFSWITCH>
   		</CFIF>
</CFFUNCTION>

<CFFUNCTION name="sendTextNotificationBroker" access="public" hint="Send Email Notification To User">
	<CFARGUMENT name="notifyType" type="string" required="Yes">
    <CFARGUMENT name="notifyString" type="string" required="Yes">
    <CFARGUMENT name="userID" type="string" required="Yes">
    <CFARGUMENT name="brokerid" type="string" required="Yes">
    <CFARGUMENT name="email" type="string" required="Yes">
    <CFARGUMENT name="tourid" type="string" required="Yes">
    <CFARGUMENT name="tourt" type="string" required="No" default="0">

  	<CFSET emailTo =arguments.email />
    
	<CFQUERY name="qGetTourDetails" datasource="#request.db.dsn#">
    	SELECT tp.*,
            p1.fullname as ScheduledPhotographer,
            p2.fullname as ReScheduledPhotographer,
            p3.fullname as VideoScheduledPhotographer,
            p4.fullname as VideoReScheduledPhotographer,
            
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(Scheduledon) THEN NOW() ELSE Scheduledon END) as ScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(ReScheduledon) THEN NOW() ELSE ReScheduledon END) as ReScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoScheduledOn) THEN NOW() ELSE VideoScheduledOn END) as VideoScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoReScheduledOn) THEN NOW() ELSE VideoReScheduledOn END) as VideoReScheduledDiff,
            
			date_format(ScheduleAttemptedon,'%h:%i:%s %p') as ScheduleAttat,
            date_format(ScheduleAttemptedon,'%W, %M %D %Y') as ScheduleAtton,
            
            date_format(Scheduledon,'%h:%i:%s %p') as Scheduledat,
            date_format(Scheduledon,'%W, %M %D %Y') as ScheduledDate,
            date_format(ReScheduledon,'%h:%i:%s %p') as ReScheduledat,
            date_format(ReScheduledon,'%W, %M %D %Y') as ReScheduledDate,
            
            date_format(VideoScheduledOn,'%h:%i:%s %p') as VideoScheduledat,
            date_format(VideoScheduledOn,'%W, %M %D %Y') as VideoScheduledDate,
            date_format(VideoReScheduledOn,'%h:%i:%s %p') as VideoReScheduledat,
            date_format(VideoReScheduledOn,'%W, %M %D %Y') as VideoReScheduledDate
        FROM tourprogress tp
	        LEFT JOIN photographers p1 ON tp.photographer = p1.photographerID
    	    	LEFT JOIN photographers p2 ON tp.rephotographer = p2.photographerID
        	LEFT JOIN photographers p3 ON tp.VideoPhotographer = p3.photographerID
         	LEFT JOIN photographers p4 ON tp.VideoRePhotographer = p4.photographerID
        WHERE tp.tourid='#arguments.tourid#' limit 1
    </CFQUERY>
    
    <CFQUERY name="qTours" datasource="#request.db.dsn#">
        select t.tourid, t.tourTypeID, tt.tourCategory,
        	(SELECT group_concat(mlsID separator ', ') FROM tour_to_mls WHERE tourID=t.tourID) as mls,
            t.address,t.city,t.state,t.zipCode,t.title,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN 
        from tours t, tourtypes tt
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#">
        	<cfif tourt gt 0> AND tt.tourTypeID = '#arguments.tourt#'<cfelse> AND t.tourTypeID = tt.tourTypeID</cfif>
    </CFQUERY>
    
   <CFIF emailTo neq "">
   <CFSWITCH expression="#arguments.notifyString#">
            <CFCASE value="scheduleattempt">

				<cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Schedule Attempt: #qTours.address#"
                }
                />
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
An attempt has been made to schedule a tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. on #qGetTourDetails.ScheduleAtton# at #qGetTourDetails.ScheduleAttat#
Please contact us to schedule or cancel
801.466.4074
				</cfmail>
         	</CFCASE>
            
            <CFCASE value="scheduled">
				<cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject=""
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Appt:#qTours.address#                
Photo
Date:<cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledon#<cfelse>#qGetTourDetails.Scheduledon#</cfif>
Photographer: <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledPhotographer#<cfelse>#qGetTourDetails.ScheduledPhotographer#</cfif>
				<CFIF #qGetTourDetails.isVideoTour# eq 1>
Video
Date:<cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledOn#<cfelse>#qGetTourDetails.VideoScheduledOn#</cfif>
Photographer: <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledPhotographer#<cfelse>#qGetTourDetails.VideoScheduledPhotographer#</cfif>
				</CFIF>
Questions? 801.466.4074
                </cfmail>       
            </CFCASE>
         
         	<CFCASE value="finalized">
				<cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour is ready! #qTours.address#"
                }
                />
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Your tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. is now available
go to your Spotlight account to view
questions? call us at 801.466.4074
				</cfmail>  
         	</CFCASE>
            
            <CFCASE value="video finalized">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour is ready! #qTours.address#"
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
The Video for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. is now available
go to your Spotlight account to view
questions? call us at 801.466.4074
                </cfmail>       
            </CFCASE>
                    
            <CFCASE value="tour updated">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour has been updated! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Your tour has been updated for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# and is online and available for viewing by clicking the link below:
go to your Spotlight account to view
questions? call us at 801.466.4074
                </cfmail>
            </CFCASE>
        
            <CFDEFAULTCASE>
                <CFSET strTo = "" />
            </CFDEFAULTCASE>
        </CFSWITCH>
   </CFIF>
</CFFUNCTION>



<CFFUNCTION name="sendNotificationTeams" access="remote" hint="Send Notification To User">
<CFARGUMENT name="notifyType" type="string" required="Yes">
<CFARGUMENT name="notifyString" type="string" required="Yes">
<CFARGUMENT name="tourid" type="string" required="Yes">
<CFARGUMENT name="tourt" type="string" required="No" default="0">

	<CFQUERY name="qSendTeamNotification" datasource="#request.db.dsn#">
        		SELECT tf.phone, tf.carrier, tf.email, u.userID, u.brokerageID
                FROM teamsnotifications tf, tours t, users u, teams_to_brokerages tb
                WHERE t.tourid = #arguments.tourid#
                AND t.userID = u.userID
                AND u.brokerageID = tf.brokerageID 
                AND u.brokerageID = tb.brokerage_id
                AND tf.teamid = tb.team_id 
                AND action= '#arguments.notifyType#'
    </CFQUERY>
     
	<CFSET strTo = ""/>
    <CFSET strToe = ""/>
    
    <CFIF qSendTeamNotification.RecordCount gt 0>
        <CFLOOP query="qSendTeamNotification">
          
            <CFIF len(qSendTeamNotification.phone) eq 10 and qSendTeamNotification.carrier neq "">
                <CFSET mycarrier = qSendTeamNotification.carrier />
                <CFSET notifynumber = qSendTeamNotification.phone/>
                <CFSET strToe = "" />
                <CFSET strTo = ""/>
                
                <CFSET temp1 = "" />
                <CFSET temp2 = "" />
                
                
                <CFSWITCH expression="#qSendTeamNotification.carrier#">
                   
                    <CFCASE value="VERIZONUS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.VERIZONUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="ATTUS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.ATTUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="CINGULARUS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.CINGULARUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="TMOBILEUS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.TMOBILEUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="SPRINTUS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.SPRINTUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="ROGERS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.ROGERS.emailtotext />
                    </CFCASE>
                    <CFCASE value="VIRGIN">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.VIRGIN.emailtotext />
                    </CFCASE>
                    <CFCASE value="TELUS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.TELUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="PCMOBILE">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.PCMOBILE.emailtotext />
                    </CFCASE>
                    <CFCASE value="USCELLULARUS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.USCELLULARUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="NEXTELUS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.NEXTELUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="FIDO">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.FIDO.emailtotext />
                    </CFCASE>
                    <CFCASE value="BELL">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.BELL.emailtotext />
                    </CFCASE>
                    <CFCASE value="KOODOMOBILE">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.KOODOMOBILE.emailtotext />
                    </CFCASE>
                    <CFCASE value="SASKTEL">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.SASKTEL.emailtotext />
                    </CFCASE>
                    <CFCASE value="VIRGINCA">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.VIRGINCA.emailtotext />
                    </CFCASE>
                    <CFCASE value="ALIANT">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.ALIANT.emailtotext />
                    </CFCASE>
                    <CFCASE value="CELLULARSOUTHUS">
                        <CFSET strTo = qSendTeamNotification.phone & application.smscarriers.CELLULARSOUTHUS.emailtotext />
                    </CFCASE>
                        
                    <CFDEFAULTCASE>
                        <CFSET strTo = "" />
                    </CFDEFAULTCASE>
                </CFSWITCH>
               
            </CFIF>
            
            <CFTRY>
                <CFIF qSendTeamNotification.phone neq "" && qSendTeamNotification.carrier neq "">
                    <CFSET temp1= sendTextNotificationBroker(arguments.notifyType,arguments.notifyString,qSendTeamNotification.userID,qSendTeamNotification.brokerageID,strTo,arguments.tourid) />
                </CFIF>
            
                <CFIF qSendTeamNotification.email neq "">
                    <CFSET temp2=sendEmailNotificationBroker(arguments.notifyType,arguments.notifyString,qSendTeamNotification.userID,qSendTeamNotification.brokerageID,qSendTeamNotification.email,arguments.tourid) />
                </CFIF>
            <CFCATCH>
            </CFCATCH>
            </CFTRY>        
        </CFLOOP>
    </CFIF>
    
</CFFUNCTION>

<CFFUNCTION name="sendEmailNotification" access="public" hint="Send Email Notification To User">
	<CFARGUMENT name="notifyType" type="string" required="Yes">
    <CFARGUMENT name="notifyString" type="string" required="Yes">
    <CFARGUMENT name="userID" type="string" required="Yes">
    <CFARGUMENT name="email" type="string" required="Yes">
    <CFARGUMENT name="tourid" type="string" required="Yes">
    <CFARGUMENT name="tourt" type="string" required="No" default="0">
    
    <CFQUERY name="qUser" datasource="#request.db.dsn#">
        SELECT userID FROM tours WHERE tourID = #arguments.tourid#
    </CFQUERY>
    
    <CFQUERY name="qGetUserDetails" datasource="#request.db.dsn#">
    	Select firstname,lastname,username from users where userID='#qUser.userID#' limit 1
    </CFQUERY>
 
    <CFSET emailTo = arguments.email/>
   
    <CFQUERY name="qGetTourDetails" datasource="#request.db.dsn#">
    	SELECT tp.*,
            p1.fullname as ScheduledPhotographer,
            p2.fullname as ReScheduledPhotographer,
            p3.fullname as VideoScheduledPhotographer,
            p4.fullname as VideoReScheduledPhotographer,
            
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(Scheduledon) THEN NOW() ELSE Scheduledon END) as ScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(ReScheduledon) THEN NOW() ELSE ReScheduledon END) as ReScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoScheduledOn) THEN NOW() ELSE VideoScheduledOn END) as VideoScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoReScheduledOn) THEN NOW() ELSE VideoReScheduledOn END) as VideoReScheduledDiff,
            
			date_format(ScheduleAttemptedon,'%h:%i:%s %p') as ScheduleAttat,
            date_format(ScheduleAttemptedon,'%W, %M %D %Y') as ScheduleAtton,
            
            date_format(Scheduledon,'%h:%i:%s %p') as Scheduledat,
            date_format(Scheduledon,'%W, %M %D %Y') as ScheduledDate,
            date_format(ReScheduledon,'%h:%i:%s %p') as ReScheduledat,
            date_format(ReScheduledon,'%W, %M %D %Y') as ReScheduledDate,
            
            date_format(VideoScheduledOn,'%h:%i:%s %p') as VideoScheduledat,
            date_format(VideoScheduledOn,'%W, %M %D %Y') as VideoScheduledDate,
            date_format(VideoReScheduledOn,'%h:%i:%s %p') as VideoReScheduledat,
            date_format(VideoReScheduledOn,'%W, %M %D %Y') as VideoReScheduledDate
        FROM tourprogress tp
	        LEFT JOIN photographers p1 ON tp.photographer = p1.photographerID
    	    	LEFT JOIN photographers p2 ON tp.rephotographer = p2.photographerID
        	LEFT JOIN photographers p3 ON tp.VideoPhotographer = p3.photographerID
         	LEFT JOIN photographers p4 ON tp.VideoRePhotographer = p4.photographerID
        WHERE tp.tourid='#arguments.tourid#' limit 1
    </CFQUERY> 
    <CFQUERY name="qTours" datasource="#request.db.dsn#">
        select t.tourid,
        	(SELECT group_concat(mlsID separator ', ') FROM tour_to_mls WHERE tourID=t.tourID) as mls,
            t.address,t.city,t.state,t.zipCode,t.title,t.tourTypeID,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN, tt.tourCategory
        from tours t, tourtypes tt
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#"> AND
        	<cfif arguments.tourt gt 0> tt.tourTypeID = '#arguments.tourt#'<cfelse> t.tourTypeID = tt.tourTypeID</cfif>
    </CFQUERY>
    
    <CFOUTPUT>Agent email:#emailTo#<br /></CFOUTPUT>
    
    <CFIF emailTo neq "">
        <CFSWITCH expression="#arguments.notifyString#">
            <CFCASE value="scheduleattempt">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    bcc="notifications@spotlighthometours.com",
                    subject="Spotlight Schedule Attempt: #qTours.address#"
                }/>
    			<cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Hello, #qGetUserDetails.firstname#

We have received your tour order for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. 

An attempt was made to contact you regarding your order on #qGetTourDetails.ScheduleAtton# at #qGetTourDetails.ScheduleAttat#. Please call us back to schedule a shoot with a photographer or if you need to cancel your order. 

Thank you. 

SpotlightHomeTours 
(801) 466-4074 
(888) 838-8810 


*This is an automated email. PLEASE DO NOT REPLY.  
                </cfmail>   
            </CFCASE>
                
            <CFCASE value="scheduled">
                <cfsavecontent variable="mailText">
                    <cfoutput>
                    <p>
Hello #qGetUserDetails.firstname#,
</p>
<p>
Thank you for making an appointment with us.  You are confirmed for the appointment(s) listed below. 
</p>
<p>
Tour Title: #qTours.title#<br>
Address:  #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#<br>
Tour Type: #qTours.tourTypeN#<br>
</p>

Photo Shoot Appointment <cfif #qGetTourDetails.ReScheduledDiff# neq 0>(Re Shoot)</cfif>
<ul style='list-style:none;margin:0px;'>
     <li>Date:  <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledDate#<cfelse>#qGetTourDetails.ScheduledDate#</cfif></li>
     <li>Time: <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledat#<cfelse>#qGetTourDetails.Scheduledat#</cfif></li> 
     <li>Photographer: <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledPhotographer#<cfelse>#qGetTourDetails.ScheduledPhotographer#</cfif></li>
</ul>
                 <CFIF #qGetTourDetails.isVideoTour# eq 1>

Video Shoot Appointment <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>(Re Shoot)</cfif>
<ul style='list-style:none;margin:0px;'>
     <li>Date:  <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledDate#<cfelse>#qGetTourDetails.VideoScheduledDate#</cfif></li>
     <li>Time: <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledat#<cfelse>#qGetTourDetails.VideoScheduledat#</cfif> </li>
     <li>Video Photographer: <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledPhotographer#<cfelse>#qGetTourDetails.VideoScheduledPhotographer#</cfif></li>
</ul>

					</CFIF>
<p>
See attachment below for a Home Photography Checklist. Make sure your property is photo-ready for it's best presentation! Click <a href='http://www.spotlighthometours.com/homeowner_email.php?tourId=#arguments.tourid#'>here</a> to send both the appointment confirmation and the photo-ready checklist to the homeowner or tenant.                    
</p>
<p>
*PLEASE NOTE* If you need to cancel or reschedule your appointment, please notify 24 hours prior to the appointment. Any cancellations within 24 hours may be subject to a $25 cancellation fee.
</p>
<p>
*This is an automated email. PLEASE DO NOT REPLY. For questions or comments, email support@spotlighthometours.com. 
</p>
                    </cfoutput>
                </cfsavecontent>
<cfhttp url="http://spotlighthometours.com/repository_queries/admin_send_notification.php" method="post" result="result" charset="utf-8"> 
    <cfhttpparam type="formfield" name="tourId" value="#arguments.tourid#">
    <cfhttpparam type="formfield" name="type" value="scheduled"> 
    <cfhttpparam type="formfield" name="level" value="user"> 
    <cfhttpparam type="formfield" name="form" value="email">
    <cfhttpparam type="formfield" name="to" value="#emailTo#"> 
    <cfhttpparam type="formfield" name="mailText" value="#mailText#"> 
    <cfhttpparam type="formfield" name="subject" value="Spotlight Appointment Confirmation: #qTours.address#">
</cfhttp>  
            </CFCASE>
             
             
            <CFCASE value="finalized">
                <CFOUTPUT>
                FINALIZED TOUR EMAIL SENDING.... TO: #emailTo#
                </CFOUTPUT>
				<cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    bcc="notifications@spotlighthometours.com",
                    subject="Spotlight Tour is ready! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Hello #qGetUserDetails.firstname#,

We are pleased to inform you that your virtual tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
[ 1800 x 1200 ] http://www.spotlighthometours.com/download-1800.php?id=#qTours.tourid#
				<CFIF #qTours.tourCategory# eq 'Video Tours'>
                
You will receive a second email confirmation when the video is available for viewing.
				</CFIF>
                                    
We will attempt to add the tour to the MLS for you if your local MLS allows it. Make sure your MLS number is input in your Spotlight account. If you did not have a MLS number when the tour was ordered you can login to your Spotlight account to update this information and we will receive an automatic notification of the change. 

Please feel free to contact us with any questions or problems with your tour.

Spotlight Home Tours
801-466-4074
888-838-8810
support@spotlighthometours.com
                </cfmail>
            </CFCASE>
            
            <CFCASE value="video finalized">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    bcc="notifications@spotlighthometours.com",
                    subject="Spotlight Tour Video is ready! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Hello #qGetUserDetails.firstname#,

We are pleased to inform you that your video for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
[ 1800 x 1200 ] http://www.spotlighthometours.com/download-1800.php?id=#qTours.tourid#
                
Please feel free to contact us with any questions or problems with your tour.

Spotlight Home Tours
801-466-4074
888-838-8810
support@spotlighthometours.com
                </cfmail>
            </CFCASE>
            
            <CFCASE value="tour updated">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    bcc="notifications@spotlighthometours.com",
                    subject="Spotlight Tour has been updated! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Hello #qGetUserDetails.firstname#,

Your tour has been updated for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# and is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
[ 1800 x 1200 ] http://www.spotlighthometours.com/download-1800.php?id=#qTours.tourid#
                
Please feel free to contact us with any questions or problems with your tour.

Spotlight Home Tours
801-466-4074
888-838-8810
support@spotlighthometours.com
                </cfmail>
            </CFCASE>
            
            <CFDEFAULTCASE>
                <CFSET strTo = "" />
            </CFDEFAULTCASE>
        </CFSWITCH>    
   	 </CFIF>
</CFFUNCTION>

<CFFUNCTION name="sendTextNotification" access="public" hint="Send Email Notification To User">
	<CFARGUMENT name="notifyType" type="string" required="Yes">
    <CFARGUMENT name="notifyString" type="string" required="Yes">
    <CFARGUMENT name="userID" type="string" required="Yes">
    <CFARGUMENT name="email" type="string" required="Yes">
    <CFARGUMENT name="tourid" type="string" required="Yes">
    <CFARGUMENT name="tourt" type="string" required="No" default="0">
    
 	<CFSET emailTo =arguments.email />
    
    <CFQUERY name="qGetTourDetails" datasource="#request.db.dsn#">
    	SELECT tp.*,
            p1.fullname as ScheduledPhotographer,
            p2.fullname as ReScheduledPhotographer,
            p3.fullname as VideoScheduledPhotographer,
            p4.fullname as VideoReScheduledPhotographer,
            
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(Scheduledon) THEN NOW() ELSE Scheduledon END) as ScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(ReScheduledon) THEN NOW() ELSE ReScheduledon END) as ReScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoScheduledOn) THEN NOW() ELSE VideoScheduledOn END) as VideoScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoReScheduledOn) THEN NOW() ELSE VideoReScheduledOn END) as VideoReScheduledDiff,
            
			date_format(ScheduleAttemptedon,'%h:%i:%s %p') as ScheduleAttat,
            date_format(ScheduleAttemptedon,'%W, %M %D %Y') as ScheduleAtton,
            
            date_format(Scheduledon,'%h:%i:%s %p') as Scheduledat,
            date_format(Scheduledon,'%W, %M %D %Y') as ScheduledDate,
            date_format(ReScheduledon,'%h:%i:%s %p') as ReScheduledat,
            date_format(ReScheduledon,'%W, %M %D %Y') as ReScheduledDate,
            
            date_format(VideoScheduledOn,'%h:%i:%s %p') as VideoScheduledat,
            date_format(VideoScheduledOn,'%W, %M %D %Y') as VideoScheduledDate,
            date_format(VideoReScheduledOn,'%h:%i:%s %p') as VideoReScheduledat,
            date_format(VideoReScheduledOn,'%W, %M %D %Y') as VideoReScheduledDate
        FROM tourprogress tp
	        LEFT JOIN photographers p1 ON tp.photographer = p1.photographerID
    	    	LEFT JOIN photographers p2 ON tp.rephotographer = p2.photographerID
        	LEFT JOIN photographers p3 ON tp.VideoPhotographer = p3.photographerID
         	LEFT JOIN photographers p4 ON tp.VideoRePhotographer = p4.photographerID
        WHERE tp.tourid='#arguments.tourid#' limit 1
    </CFQUERY>
    <CFQUERY name="qTours" datasource="#request.db.dsn#">
        select t.tourid, t.tourTypeID, tt.tourCategory,
        	(SELECT group_concat(mlsID separator ', ') FROM tour_to_mls WHERE tourID=t.tourID) as mls,
            t.address,t.city,t.state,t.zipCode,t.title,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN 
        from tours t, tourtypes tt
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#">
        	<cfif tourt gt 0> AND tt.tourTypeID = '#arguments.tourt#'<cfelse> AND t.tourTypeID = tt.tourTypeID</cfif>
    </CFQUERY>
    
	<CFIF emailTo neq "">
        <CFSWITCH expression="#arguments.notifyString#">
            <CFCASE value="scheduleattempt">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Schedule Attempt: #qTours.address#"
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Your tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. is now available
An attempt has been made to schedule a tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. on #qGetTourDetails.ScheduleAtton# at #qGetTourDetails.ScheduleAttat#
Please contact us to schedule or cancel
801.466.4074
                </cfmail>
            </CFCASE>
                
            <CFCASE value="scheduled">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject=""
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Your tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. is now available
Appt:#qTours.address# 
Tour Type: #qTours.tourTypeN#               
Photo
Date:<cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledon#<cfelse>#qGetTourDetails.Scheduledon#</cfif>
Photographer: <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledPhotographer#<cfelse>#qGetTourDetails.ScheduledPhotographer#</cfif>
				<CFIF #qGetTourDetails.isVideoTour# eq 1>
Video
Date:<cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledOn#<cfelse>#qGetTourDetails.VideoScheduledOn#</cfif>
Photographer: <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledPhotographer#<cfelse>#qGetTourDetails.VideoScheduledPhotographer#</cfif>
				</CFIF>
Questions? 801.466.4074
                </cfmail>       
            </CFCASE>
              
            <CFCASE value="finalized">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour is ready! #qTours.address#"
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Your tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. is now available
go to your Spotlight account to view
questions? call us at 801.466.4074
                </cfmail>  
            </CFCASE>
            
            <CFCASE value="video finalized">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour is ready! #qTours.address#"
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
The Video for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. is now available
go to your Spotlight account to view
questions? call us at 801.466.4074
                </cfmail>       
            </CFCASE>
            
            <CFCASE value="tour updated">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour has been updated! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Your tour has been updated for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# and is online and available for viewing by clicking the link below:
go to your Spotlight account to view
questions? call us at 801.466.4074
                </cfmail>
            </CFCASE>
            
            <CFDEFAULTCASE>
                <CFSET strTo = "" />
            </CFDEFAULTCASE>
        </CFSWITCH>
   	</CFIF>
</CFFUNCTION>

<CFFUNCTION name="sendNotification" access="remote" hint="Send Notification To User">
    <CFARGUMENT name="notifyType" type="string" required="Yes">
    <CFARGUMENT name="notifyString" type="string" required="Yes">
    <CFARGUMENT name="tourid" type="string" required="Yes">
    <CFARGUMENT name="tourt" type="string" required="No" default="0">
    
    <CFQUERY name="qCoUser" datasource="#request.db.dsn#">
        SELECT couserID FROM tours WHERE tourID = #arguments.tourid# AND couserID>0
    </CFQUERY>
    
    <CFSET strTo = ""/>
    <CFSET strToe = ""/>
    
    <CFIF qCoUser.RecordCount gt 0>
    	<CFQUERY name="qSendCoUserNotification" datasource="#request.db.dsn#">
        	Select phone,carrier,email from usernotifications where userID='#qCoUser.couserID#' and action='#arguments.notifyType#'
    	</CFQUERY>
		<CFIF qSendCoUserNotification.RecordCount gt 0>
            <CFLOOP query="qSendCoUserNotification">
                <CFIF len(qSendCoUserNotification.phone) eq 10 and qSendCoUserNotification.carrier neq "">
                    <CFSET mycarrier = qSendCoUserNotification.carrier />
                    <CFSET notifynumber = qSendCoUserNotification.phone/>
                    <CFSET strToe = "" />
                    <CFSET strTo = ""/>
                    
                    <CFSET temp1 = "" />
                    <CFSET temp2 = "" />
                    
                    <CFSWITCH expression="#qSendCoUserNotification.carrier#">
                        <CFCASE value="VERIZONUS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.VERIZONUS.emailtotext />
                        </CFCASE>
                        <CFCASE value="ATTUS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.ATTUS.emailtotext />
                        </CFCASE>
                        <CFCASE value="CINGULARUS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.CINGULARUS.emailtotext />
                        </CFCASE>
                        <CFCASE value="TMOBILEUS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.TMOBILEUS.emailtotext />
                        </CFCASE>
                        <CFCASE value="SPRINTUS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.SPRINTUS.emailtotext />
                        </CFCASE>
                         <CFCASE value="ROGERS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.ROGERS.emailtotext />
                        </CFCASE>
                        <CFCASE value="VIRGIN">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.VIRGIN.emailtotext />
                        </CFCASE>
                        <CFCASE value="TELUS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.TELUS.emailtotext />
                        </CFCASE>
                        <CFCASE value="PCMOBILE">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.PCMOBILE.emailtotext />
                        </CFCASE>
                        <CFCASE value="USCELLULARUS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.USCELLULARUS.emailtotext />
                        </CFCASE>
                        <CFCASE value="NEXTELUS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.NEXTELUS.emailtotext />
                        </CFCASE>
                        <CFCASE value="FIDO">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.FIDO.emailtotext />
                        </CFCASE>
                        <CFCASE value="BELL">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.BELL.emailtotext />
                        </CFCASE>
                        <CFCASE value="KOODOMOBILE">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.KOODOMOBILE.emailtotext />
                        </CFCASE>
                        <CFCASE value="SASKTEL">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.SASKTEL.emailtotext />
                        </CFCASE>
                        <CFCASE value="VIRGINCA">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.VIRGINCA.emailtotext />
                        </CFCASE>
                        <CFCASE value="ALIANT">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.ALIANT.emailtotext />
                        </CFCASE>
                        <CFCASE value="CELLULARSOUTHUS">
                            <CFSET strTo = qSendCoUserNotification.phone & application.smscarriers.CELLULARSOUTHUS.emailtotext />
                        </CFCASE>
                        <CFDEFAULTCASE>
                            <CFSET strTo = "" />
                        </CFDEFAULTCASE>
                    </CFSWITCH>
                </CFIF>
                <CFTRY>
                        <CFIF qSendCoUserNotification.phone neq "" && qSendCoUserNotification.carrier neq "">
                            <CFSET temp1= sendTextNotification(arguments.notifyType,arguments.notifyString,qCoUser.couserID,strTo,arguments.tourid) />
                        </CFIF>
                        <CFIF qSendCoUserNotification.email neq "">
                            <CFSET temp2=sendEmailNotification(arguments.notifyType,arguments.notifyString,qCoUser.couserID,qSendCoUserNotification.email,arguments.tourid) />
                        </CFIF>
                    <CFCATCH>
                    </CFCATCH>
                </CFTRY>
            </CFLOOP>
        <CFELSE>
            <CFSET emailTo =""/>
            <CFQUERY name="qGetCoUserDetails" datasource="#request.db.dsn#">
                Select firstname,lastname,username,REPLACE(phone, '.', '') as phonenum,phonecarrier from users where userID='#qCoUser.couserID#' limit 1
            </CFQUERY>
            <CFIF len(qGetCoUserDetails.username) gt 0>
                <CFSET sendEmailNotification(arguments.notifyType,arguments.notifyString,qCoUser.couserID,qGetCoUserDetails.username,arguments.tourid) />
            </CFIF>	
        </CFIF>
    </CFIF>
    
    <CFQUERY name="qUser" datasource="#request.db.dsn#">
        SELECT userID FROM tours WHERE tourID = #arguments.tourid#
    </CFQUERY>
    
    <CFQUERY name="qSendUserNotification" datasource="#request.db.dsn#">
        Select phone,carrier,email from usernotifications where userID='#qUser.userID#' and action='#arguments.notifyType#'
    </CFQUERY>
    
    <CFSET strTo = ""/>
    <CFSET strToe = ""/>
    
    <CFIF qSendUserNotification.RecordCount gt 0>
        <CFLOOP query="qSendUserNotification">
          
            <CFIF len(qSendUserNotification.phone) eq 10 and qSendUserNotification.carrier neq "">
                <CFSET mycarrier = qSendUserNotification.carrier />
                <CFSET notifynumber = qSendUserNotification.phone/>
                <CFSET strToe = "" />
                <CFSET strTo = ""/>
                
                <CFSET temp1 = "" />
                <CFSET temp2 = "" />
                
                <CFSWITCH expression="#qSendUserNotification.carrier#">
                    <CFCASE value="VERIZONUS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.VERIZONUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="ATTUS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.ATTUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="CINGULARUS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.CINGULARUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="TMOBILEUS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.TMOBILEUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="SPRINTUS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.SPRINTUS.emailtotext />
                    </CFCASE>
                     <CFCASE value="ROGERS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.ROGERS.emailtotext />
                    </CFCASE>
                    <CFCASE value="VIRGIN">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.VIRGIN.emailtotext />
                    </CFCASE>
                    <CFCASE value="TELUS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.TELUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="PCMOBILE">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.PCMOBILE.emailtotext />
                    </CFCASE>
                    <CFCASE value="USCELLULARUS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.USCELLULARUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="NEXTELUS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.NEXTELUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="FIDO">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.FIDO.emailtotext />
                    </CFCASE>
                    <CFCASE value="BELL">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.BELL.emailtotext />
                    </CFCASE>
                    <CFCASE value="KOODOMOBILE">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.KOODOMOBILE.emailtotext />
                    </CFCASE>
                    <CFCASE value="SASKTEL">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.SASKTEL.emailtotext />
                    </CFCASE>
                    <CFCASE value="VIRGINCA">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.VIRGINCA.emailtotext />
                    </CFCASE>
                    <CFCASE value="ALIANT">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.ALIANT.emailtotext />
                    </CFCASE>
                    <CFCASE value="CELLULARSOUTHUS">
                        <CFSET strTo = qSendUserNotification.phone & application.smscarriers.CELLULARSOUTHUS.emailtotext />
                    </CFCASE>
                    <CFDEFAULTCASE>
                        <CFSET strTo = "" />
                    </CFDEFAULTCASE>
                </CFSWITCH>
            </CFIF>
             
            <CFTRY>
                
                    <CFIF qSendUserNotification.phone neq "" && qSendUserNotification.carrier neq "">
                    	<CFSET temp1= sendTextNotification(arguments.notifyType,arguments.notifyString,qUser.userID,strTo,arguments.tourid) />
                    </CFIF>
                    <CFIF qSendUserNotification.email neq "">
                    	<CFSET temp2=sendEmailNotification(arguments.notifyType,arguments.notifyString,qUser.userID,qSendUserNotification.email,arguments.tourid) />
                    </CFIF>
                <CFCATCH>
                </CFCATCH>
            </CFTRY>
        </CFLOOP>
        
    <CFELSE>
        <CFSET emailTo =""/>
    
        <CFQUERY name="qGetUserDetails" datasource="#request.db.dsn#">
            Select firstname,lastname,username,REPLACE(phone, '.', '') as phonenum,phonecarrier from users where userID='#qUser.userID#' limit 1
        </CFQUERY>
         
        <CFIF len(qGetUserDetails.username) gt 0>
            <CFSET sendEmailNotification(arguments.notifyType,arguments.notifyString,qUser.userID,qGetUserDetails.username,arguments.tourid) />
        </CFIF>	
    </CFIF>
</CFFUNCTION>

	<CFFUNCTION name="sendEmailNotificationAffiliate" access="public" hint="Send Email Notification To User">
	<CFARGUMENT name="notifyType" type="string" required="Yes">
    <CFARGUMENT name="notifyString" type="string" required="Yes">
    <CFARGUMENT name="userID" type="string" required="Yes">
    <CFARGUMENT name="affiliatePhotographerID" type="string" required="Yes">
    <CFARGUMENT name="email" type="string" required="Yes">
    <CFARGUMENT name="tourid" type="string" required="Yes">
    <CFARGUMENT name="tourt" type="string" required="No" default="0">
    
    <CFQUERY name="qUser" datasource="#request.db.dsn#">
        SELECT userID FROM tours WHERE tourID = #arguments.tourid#
    </CFQUERY>
    
    <CFQUERY name="qGetUserDetails" datasource="#request.db.dsn#">
    	Select firstname,lastname,username from users where userID='#qUser.userID#' limit 1
    </CFQUERY>
    
    <CFQUERY name="qGetAffiliateDetails" datasource="#request.db.dsn#">
    	Select fullName, email from photographers where photographerID='#arguments.affiliatePhotographerID#' limit 1
	</CFQUERY>
 
<!---<cfdump var="#qGetBrokerDetails#" />--->
    <CFSET emailTo = arguments.email/>
   
    <CFOUTPUT>Affiliate email:#emailTo#<br /></CFOUTPUT>
    
    <CFQUERY name="qGetTourDetails" datasource="#request.db.dsn#">
    	SELECT tp.*,
            p1.fullname as ScheduledPhotographer,
            p2.fullname as ReScheduledPhotographer,
            p3.fullname as VideoScheduledPhotographer,
            p4.fullname as VideoReScheduledPhotographer,
            
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(Scheduledon) THEN NOW() ELSE Scheduledon END) as ScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(ReScheduledon) THEN NOW() ELSE ReScheduledon END) as ReScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoScheduledOn) THEN NOW() ELSE VideoScheduledOn END) as VideoScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoReScheduledOn) THEN NOW() ELSE VideoReScheduledOn END) as VideoReScheduledDiff,
            
			date_format(ScheduleAttemptedon,'%h:%i:%s %p') as ScheduleAttat,
            date_format(ScheduleAttemptedon,'%W, %M %D %Y') as ScheduleAtton,
            
            date_format(Scheduledon,'%h:%i:%s %p') as Scheduledat,
            date_format(Scheduledon,'%W, %M %D %Y') as ScheduledDate,
            date_format(ReScheduledon,'%h:%i:%s %p') as ReScheduledat,
            date_format(ReScheduledon,'%W, %M %D %Y') as ReScheduledDate,
            
            date_format(VideoScheduledOn,'%h:%i:%s %p') as VideoScheduledat,
            date_format(VideoScheduledOn,'%W, %M %D %Y') as VideoScheduledDate,
            date_format(VideoReScheduledOn,'%h:%i:%s %p') as VideoReScheduledat,
            date_format(VideoReScheduledOn,'%W, %M %D %Y') as VideoReScheduledDate
        FROM tourprogress tp
	        LEFT JOIN photographers p1 ON tp.photographer = p1.photographerID
    	    	LEFT JOIN photographers p2 ON tp.rephotographer = p2.photographerID
        	LEFT JOIN photographers p3 ON tp.VideoPhotographer = p3.photographerID
         	LEFT JOIN photographers p4 ON tp.VideoRePhotographer = p4.photographerID
        WHERE tp.tourid='#arguments.tourid#' limit 1
    </CFQUERY>
    <CFQUERY name="qTours" datasource="#request.db.dsn#">
        select t.tourid, t.tourTypeID, tt.tourCategory,
        	(SELECT group_concat(mlsID separator ', ') FROM tour_to_mls WHERE tourID=t.tourID) as mls,
            t.address,t.city,t.state,t.zipCode,t.title,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN 
        from tours t, tourtypes tt
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#">
        	<cfif tourt gt 0> AND tt.tourTypeID = '#arguments.tourt#'<cfelse> AND t.tourTypeID = tt.tourTypeID</cfif>
    </CFQUERY>
    
	<CFIF emailTo neq "">
        <CFSWITCH expression="#arguments.notifyString#">
                
            <CFCASE value="scheduled">
                <cfsavecontent variable="mailText">
                    <cfoutput>
Hello photographer: #qGetAffiliateDetails.fullName# 
Tour agent: #qGetUserDetails.firstname# #qGetUserDetails.lastname#

Thank you for making an appointment with us.  You are confirmed for the appointment(s) listed below. 

Tour Title: #qTours.title#
Address:  #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#
Tour Type: #qTours.tourTypeN#
					
Photo Shoot Appointment <cfif #qGetTourDetails.ReScheduledDiff# neq 0>(Re Shoot)</cfif>
Date:  <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledDate#<cfelse>#qGetTourDetails.ScheduledDate#</cfif>
Time: <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledat#<cfelse>#qGetTourDetails.Scheduledat#</cfif> 
Photographer: <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledPhotographer#<cfelse>#qGetTourDetails.ScheduledPhotographer#</cfif>
					<CFIF #qGetTourDetails.isVideoTour# eq 1>
                    
Video Shoot Appointment <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>(Re Shoot)</cfif>
Date:  <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledDate#<cfelse>#qGetTourDetails.VideoScheduledDate#</cfif>
Time: <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledat#<cfelse>#qGetTourDetails.VideoScheduledat#</cfif> 
Video Photographer: <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledPhotographer#<cfelse>#qGetTourDetails.VideoScheduledPhotographer#</cfif>
					</CFIF>
See attachment below for a Home Photography Checklist. Make sure your property is photo-ready for it's best presentation! Click <a href='http://www.spotlighthometours.com/homeowner_email.php?tourId=#arguments.tourid#'>here</a> to send both the appointment confirmation and the photo-ready checklist to the homeowner or tenant.                    
                    
*PLEASE NOTE* appointment cancellations cannot be accepted via email. 

If you need to cancel or reschedule your appointment, please give us a call 24 hours prior to the appointment. Any cancellations within 24 hours may be subject to a $25 cancellation fee. 

*This is an automated email. PLEASE DO NOT REPLY.
                    </cfoutput>
                </cfsavecontent>       
<cfhttp url="http://spotlighthometours.com/repository_queries/admin_send_notification.php" method="post" result="result" charset="utf-8"> 
    <cfhttpparam type="formfield" name="tourId" value="#arguments.tourid#">
    <cfhttpparam type="formfield" name="type" value="scheduled"> 
    <cfhttpparam type="formfield" name="level" value="affiliate"> 
    <cfhttpparam type="formfield" name="form" value="email">
    <cfhttpparam type="formfield" name="to" value="#emailTo#"> 
    <cfhttpparam type="formfield" name="mailText" value="#mailText#"> 
    <cfhttpparam type="formfield" name="subject" value="Spotlight Appointment Confirmation: #qTours.address#">
</cfhttp>  
            </CFCASE>
         
         	<CFCASE value="finalized">
				<cfset mailAttributes = {

                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour is ready! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Photographer: #qGetAffiliateDetails.fullName#
Tour Agent: #qGetUserDetails.firstname# #qGetUserDetails.lastname#,

We are pleased to inform you that your virtual tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
[ 1800 x 1200 ] http://www.spotlighthometours.com/download-1800.php?id=#qTours.tourid#
				<CFIF #qTours.tourCategory# eq 'Video Tours'>
                
You will receive a second email confirmation when the video is available for viewing.
				</CFIF>                    
                
We will attempt to add the tour to the MLS for you if your local MLS allows it. Make sure your MLS number is input in your Spotlight account. If you did not have a MLS number when the tour was ordered you can login to your Spotlight account to update this information and we will receive an automatic notification of the change. 

Please feel free to contact us with any questions or problems with your tour.

Spotlight Home Tours
801-466-4074
888-838-8810
support@spotlighthometours.com
                </cfmail>
            </CFCASE>
            
            <CFCASE value="video finalized">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour Video is ready! #qTours.address#"
                }/>
                
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Photographer: #qGetAffiliateDetails.fullName#
Tour Agent: #qGetUserDetails.firstname# #qGetUserDetails.lastname#,

We are pleased to inform you that your video for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN# is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
[ 1800 x 1200 ] http://www.spotlighthometours.com/download-1800.php?id=#qTours.tourid#
                
Please feel free to contact us with any questions or problems with your tour.

Spotlight Home Tours
801-466-4074
888-838-8810
support@spotlighthometours.com
                </cfmail>
            </CFCASE>
            <CFDEFAULTCASE>
                <CFSET strTo = "" />
            </CFDEFAULTCASE>
        </CFSWITCH>
   		</CFIF>
</CFFUNCTION>
<CFFUNCTION name="sendTextNotificationAffiliate" access="public" hint="Send Email Notification To User">
	<CFARGUMENT name="notifyType" type="string" required="Yes">
    <CFARGUMENT name="notifyString" type="string" required="Yes">
    <CFARGUMENT name="email" type="string" required="Yes">
    <CFARGUMENT name="tourid" type="string" required="Yes">
    <CFARGUMENT name="tourt" type="string" required="No" default="0">

  	<CFSET emailTo =arguments.email />
    
	<CFQUERY name="qGetTourDetails" datasource="#request.db.dsn#">
    	SELECT tp.*,
            p1.fullname as ScheduledPhotographer,
            p2.fullname as ReScheduledPhotographer,
            p3.fullname as VideoScheduledPhotographer,
            p4.fullname as VideoReScheduledPhotographer,
            
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(Scheduledon) THEN NOW() ELSE Scheduledon END) as ScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(ReScheduledon) THEN NOW() ELSE ReScheduledon END) as ReScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoScheduledOn) THEN NOW() ELSE VideoScheduledOn END) as VideoScheduledDiff,
            TIMESTAMPDIFF(MINUTE, NOW(), CASE WHEN ISNULL(VideoReScheduledOn) THEN NOW() ELSE VideoReScheduledOn END) as VideoReScheduledDiff,
            
			date_format(ScheduleAttemptedon,'%h:%i:%s %p') as ScheduleAttat,
            date_format(ScheduleAttemptedon,'%W, %M %D %Y') as ScheduleAtton,
            
            date_format(Scheduledon,'%h:%i:%s %p') as Scheduledat,
            date_format(Scheduledon,'%W, %M %D %Y') as ScheduledDate,
            date_format(ReScheduledon,'%h:%i:%s %p') as ReScheduledat,
            date_format(ReScheduledon,'%W, %M %D %Y') as ReScheduledDate,
            
            date_format(VideoScheduledOn,'%h:%i:%s %p') as VideoScheduledat,
            date_format(VideoScheduledOn,'%W, %M %D %Y') as VideoScheduledDate,
            date_format(VideoReScheduledOn,'%h:%i:%s %p') as VideoReScheduledat,
            date_format(VideoReScheduledOn,'%W, %M %D %Y') as VideoReScheduledDate
        FROM tourprogress tp
	        LEFT JOIN photographers p1 ON tp.photographer = p1.photographerID
    	    	LEFT JOIN photographers p2 ON tp.rephotographer = p2.photographerID
        	LEFT JOIN photographers p3 ON tp.VideoPhotographer = p3.photographerID
         	LEFT JOIN photographers p4 ON tp.VideoRePhotographer = p4.photographerID
        WHERE tp.tourid='#arguments.tourid#' limit 1
    </CFQUERY>
    
    <CFQUERY name="qTours" datasource="#request.db.dsn#">
        select t.tourid, t.tourTypeID, tt.tourCategory,
        	(SELECT group_concat(mlsID separator ', ') FROM tour_to_mls WHERE tourID=t.tourID) as mls,
            t.address,t.city,t.state,t.zipCode,t.title,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN 
        from tours t, tourtypes tt
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#">
        	<cfif tourt gt 0> AND tt.tourTypeID = '#arguments.tourt#'<cfelse> AND t.tourTypeID = tt.tourTypeID</cfif>
    </CFQUERY>
    
   <CFIF emailTo neq "">
   <CFSWITCH expression="#arguments.notifyString#">
            
            <CFCASE value="scheduled">
				<cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject=""
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Appt:#qTours.address#                
Photo
Date:<cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledon#<cfelse>#qGetTourDetails.Scheduledon#</cfif>
Photographer: <cfif #qGetTourDetails.ReScheduledDiff# neq 0>#qGetTourDetails.ReScheduledPhotographer#<cfelse>#qGetTourDetails.ScheduledPhotographer#</cfif>
				<CFIF #qGetTourDetails.isVideoTour# eq 1>
Video
Date:<cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledOn#<cfelse>#qGetTourDetails.VideoScheduledOn#</cfif>
Photographer: <cfif #qGetTourDetails.VideoReScheduledDiff# neq 0>#qGetTourDetails.VideoReScheduledPhotographer#<cfelse>#qGetTourDetails.VideoScheduledPhotographer#</cfif>
				</CFIF>
Questions? 801.466.4074
                </cfmail>       
            </CFCASE>
         
         	<CFCASE value="finalized">
				<cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour is ready! #qTours.address#"
                }
                />
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Your tour for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. is now available
go to your Spotlight account to view
questions? call us at 801.466.4074
				</cfmail>  
         	</CFCASE>
            
            <CFCASE value="video finalized">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Tour is ready! #qTours.address#"
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
The Video for Tour Address: #qTours.address# #qTours.city#, #qTours.state# #qTours.zipCode#  with Tour Type: #qTours.tourTypeN#. is now available
go to your Spotlight account to view
questions? call us at 801.466.4074
                </cfmail>       
            </CFCASE>
             
            <CFDEFAULTCASE>
                <CFSET strTo = "" />
            </CFDEFAULTCASE>
        </CFSWITCH>
   </CFIF>
</CFFUNCTION>
<CFFUNCTION name="sendNotificationAffiliate" access="remote" hint="Send Notification To User">
<CFARGUMENT name="notifyType" type="string" required="Yes">
<CFARGUMENT name="notifyString" type="string" required="Yes">
<CFARGUMENT name="tourid" type="string" required="Yes">
<CFARGUMENT name="tourt" type="string" required="No" default="0">

	<CFQUERY name="qTourAssignedToAffiliate" datasource="#request.db.dsn#">
    	SELECT userID FROM tour_to_user WHERE userType='affiliate' AND tourID='#arguments.tourid#' LIMIT 1
    </CFQUERY>
	<CFIF qTourAssignedToAffiliate.RecordCount gt 0>
        <CFQUERY name="qSendAffiliateNotification" datasource="#request.db.dsn#">
                    SELECT af.photographerID, af.phone, af.carrier, af.email, u.userID, u.brokerageID
                    FROM affiliatenotifications af, tours t, brokerages b, users u
                    WHERE t.tourid = #arguments.tourid#
                    AND t.userID = u.userID
                    AND u.brokerageID = b.brokerageID
                    AND af.photographerID = #qTourAssignedToAffiliate.userID#
                    AND af.action = '#arguments.notifyType#'
        </CFQUERY>
    <CFELSE>
        <CFQUERY name="qSendAffiliateNotification" datasource="#request.db.dsn#">
                    SELECT af.photographerID, af.phone, af.carrier, af.email, u.userID, u.brokerageID
                    FROM affiliatenotifications af, tours t, brokerages b, users u
                    WHERE t.tourid = #arguments.tourid#
                    AND t.userID = u.userID
                    AND u.brokerageID = b.brokerageID
                    AND b.affiliatePhotographerID = af.photographerID
                    AND af.action = '#arguments.notifyType#'
        </CFQUERY>
    </CFIF>
     
	<CFSET strTo = ""/>
    <CFSET strToe = ""/>
    <CFIF qSendAffiliateNotification.RecordCount gt 0>
        <CFLOOP query="qSendAffiliateNotification">
            <CFIF len(qSendAffiliateNotification.phone) eq 10 and qSendAffiliateNotification.carrier neq "">
                <CFSET mycarrier = qSendAffiliateNotification.carrier />
                <CFSET notifynumber = qSendAffiliateNotification.phone/>
                <CFSET strToe = "" />
                <CFSET strTo = ""/>
                
                <CFSET temp1 = "" />
                <CFSET temp2 = "" />
                
                
                <CFSWITCH expression="#qSendAffiliateNotification.carrier#">
                   
                    <CFCASE value="VERIZONUS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.VERIZONUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="ATTUS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.ATTUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="CINGULARUS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.CINGULARUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="TMOBILEUS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.TMOBILEUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="SPRINTUS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.SPRINTUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="ROGERS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.ROGERS.emailtotext />
                    </CFCASE>
                    <CFCASE value="VIRGIN">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.VIRGIN.emailtotext />
                    </CFCASE>
                    <CFCASE value="TELUS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.TELUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="PCMOBILE">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.PCMOBILE.emailtotext />
                    </CFCASE>
                    <CFCASE value="USCELLULARUS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.USCELLULARUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="NEXTELUS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.NEXTELUS.emailtotext />
                    </CFCASE>
                    <CFCASE value="FIDO">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.FIDO.emailtotext />
                    </CFCASE>
                    <CFCASE value="BELL">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.BELL.emailtotext />
                    </CFCASE>
                    <CFCASE value="KOODOMOBILE">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.KOODOMOBILE.emailtotext />
                    </CFCASE>
                    <CFCASE value="SASKTEL">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.SASKTEL.emailtotext />
                    </CFCASE>
                    <CFCASE value="VIRGINCA">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.VIRGINCA.emailtotext />
                    </CFCASE>
                    <CFCASE value="ALIANT">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.ALIANT.emailtotext />
                    </CFCASE>
                    <CFCASE value="CELLULARSOUTHUS">
                        <CFSET strTo = qSendAffiliateNotification.phone & application.smscarriers.CELLULARSOUTHUS.emailtotext />
                    </CFCASE>
                        
                    <CFDEFAULTCASE>
                        <CFSET strTo = "" />
                    </CFDEFAULTCASE>
                </CFSWITCH>
               
            </CFIF>
            
            <CFTRY>
                <CFIF qSendAffiliateNotification.phone neq "" && qSendAffiliateNotification.carrier neq "">
                    <CFSET temp1= sendTextNotificationAffiliate(arguments.notifyType,arguments.notifyString,strTo,arguments.tourid) />
                </CFIF>
            
                <CFIF qSendAffiliateNotification.email neq "">
                    <CFSET temp2=sendEmailNotificationAffiliate(arguments.notifyType,arguments.notifyString,qSendAffiliateNotification.userID,qSendAffiliateNotification.photographerID,qSendAffiliateNotification.email,arguments.tourid) />
                </CFIF>
            <CFCATCH>
            </CFCATCH>
            </CFTRY>        
        </CFLOOP>
    </CFIF>
</CFFUNCTION>
</cfcomponent>
