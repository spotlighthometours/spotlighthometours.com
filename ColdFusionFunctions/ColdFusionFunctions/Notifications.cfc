<cfcomponent>
	<CFFUNCTION name="sendEmailNotificationBroker" access="public" hint="Send Email Notification To User">
	<CFARGUMENT name="notifyType" type="string" required="Yes">
    <CFARGUMENT name="notifyString" type="string" required="Yes">
    <CFARGUMENT name="userID" type="string" required="Yes">
    <CFARGUMENT name="brokerid" type="string" required="Yes">
    <CFARGUMENT name="email" type="string" required="Yes">
    <CFARGUMENT name="tourid" type="string" required="Yes">
    
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
<!-- get mls video info if it exists -->
    <cfhttp url="/repository_queries/notify_mls_email.php?tourId=#tourid#" method="GET" name="mlsEmail" />
                <cfset testAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="william@spotlighthometours.com",
                    subject="Spotlight Schedule Attempt: #qTours.address#"
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#testAttributes#">
#qGetBrokerDetails.BrokerageName#
#qGetUserDetails.firstname# #qGetUserDetails.lastname#

We have received your tour order for #qTours.address#.
#mlsEmail#

An attempt was made to contact you regarding your order on #qGetTourDetails.ScheduleAtton# at #qGetTourDetails.ScheduleAttat#. Please call us back to schedule a shoot with a photographer or if you need to cancel your order.

Thank you.

SpotlightHomeTours
(801) 466-4074
(888) 838-8810

                </cfmail>



    
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
            t.address,t.title,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN 
        from tours t, tourtypes tt
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#">
        	AND t.tourTypeID = tt.tourTypeID
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

We have received your tour order for #qTours.address#. 

An attempt was made to contact you regarding your order on #qGetTourDetails.ScheduleAtton# at #qGetTourDetails.ScheduleAttat#. Please call us back to schedule a shoot with a photographer or if you need to cancel your order. 

Thank you. 

SpotlightHomeTours 
(801) 466-4074 
(888) 838-8810 

                </cfmail>
            </CFCASE>
                
            <CFCASE value="scheduled">
    
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    subject="Spotlight Appointment Confirmation: #qTours.address#"
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Hello, #qGetBrokerDetails.BrokerageName# #qGetUserDetails.firstname# #qGetUserDetails.lastname#

Thank you for making an appointment with us.  You are confirmed for the appointment(s) listed below. 

Tour Title: #qTours.title#
Address:  #qTours.address#
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
                    
*PLEASE NOTE* appointment cancellations cannot be accepted via email. 

If you need to cancel or reschedule your appointment, please give us a call 24 hours prior to the appointment. Any cancellations within 24 hours may be subject to a $25 cancellation fee. 

*This is an automated email. PLEASE DO NOT REPLY.
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
#qGetBrokerDetails.BrokerageName#
#qGetUserDetails.firstname# #qGetUserDetails.lastname#,

We are pleased to inform your virtual tour for #qTours.address# is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
#mlsEmail#
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
#qGetBrokerDetails.BrokerageName#
#qGetUserDetails.firstname# #qGetUserDetails.lastname#,

We are pleased to inform you that your video for #qTours.address# is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#

#mlsEmail#
                
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
	
Your tour has been updated for #qTours.address# and is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
#mlsEmail#
                
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
            t.address,t.title,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN 
        from tours t, tourtypes tt
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#">
        	AND t.tourTypeID = tt.tourTypeID
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
An attempt has been made to schedule a tour for #qTours.address#. on #qGetTourDetails.ScheduleAtton# at #qGetTourDetails.ScheduleAttat#
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
Your tour for #qTours.address#. is now available
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
The Video for #qTours.address#. is now available
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
Your tour has been updated for #qTours.address# and is online and available for viewing by clicking the link below:
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

	<CFQUERY name="qUser" datasource="#request.db.dsn#">
        SELECT userID FROM tours WHERE tourID = #arguments.tourid#
    </CFQUERY>
    
    <CFQUERY name="getteams" datasource="#request.db.dsn#">
		Select team_id,brokerage_id from teams_to_brokerages where brokerage_id=(select brokerageID from users where userID='#qUser.userID#' limit 1) 
	</CFQUERY>

<CFIF getteams.RecordCount gt 0>
    <CFLOOP query="getteams">
    
        <CFQUERY name="qSendTeamNotification" datasource="#request.db.dsn#">
            Select phone,carrier,email from teamsnotifications where teamid='#getteams.team_id#' and action='#arguments.notifyType#'  and brokerageID=(select brokerageID from users where userID='#qUser.userID#' limit 1)
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
            			<CFSET temp1= sendTextNotificationBroker(arguments.notifyType,arguments.notifyString,qUser.userID,getteams.brokerage_id,strTo,arguments.tourid) />
	            	</CFIF>
                
        			<CFIF qSendTeamNotification.email neq "">
       					<CFSET temp2=sendEmailNotificationBroker(arguments.notifyType,arguments.notifyString,qUser.userID,getteams.brokerage_id,qSendTeamNotification.email,arguments.tourid) />
            		</CFIF>
        		<CFCATCH>
        		</CFCATCH>
        		</CFTRY>        
        	</CFLOOP>
    	</CFIF>
    </CFLOOP> 
</CFIF>

</CFFUNCTION>

<CFFUNCTION name="sendEmailNotification" access="public" hint="Send Email Notification To User">
	<CFARGUMENT name="notifyType" type="string" required="Yes">
    <CFARGUMENT name="notifyString" type="string" required="Yes">
    <CFARGUMENT name="userID" type="string" required="Yes">
    <CFARGUMENT name="email" type="string" required="Yes">
    <CFARGUMENT name="tourid" type="string" required="Yes">
    
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
            t.address,t.title,t.tourTypeID,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN, tt.tourCategory
        from tours t, tourTypes tt
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#"> AND
        	tt.tourTypeID = t.tourTypeID
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

We have received your tour order for #qTours.address#. 

An attempt was made to contact you regarding your order on #qGetTourDetails.ScheduleAtton# at #qGetTourDetails.ScheduleAttat#. Please call us back to schedule a shoot with a photographer or if you need to cancel your order. 

Thank you. 

SpotlightHomeTours 
(801) 466-4074 
(888) 838-8810 


*This is an automated email. PLEASE DO NOT REPLY.  
                </cfmail>   
            </CFCASE>
                
            <CFCASE value="scheduled">
                <cfset mailAttributes = {
                    server="smtp.gmail.com",
                    username="info@spotlighthometours.com",
                    password="Spotlight01",
                    from="info@spotlighthometours.com",
                    to="#emailTo#",
                    bcc="notifications@spotlighthometours.com",
                    subject="Spotlight Appointment Confirmation: #qTours.address#"
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Hello, #qGetUserDetails.firstname#

Thank you for making an appointment with us.  You are confirmed for the appointment(s) listed below. 

Tour Title: #qTours.title#
Address:  #qTours.address#
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
                    
*PLEASE NOTE* appointment cancellations cannot be accepted via email. 

If you need to cancel or reschedule your appointment, please give us a call 24 hours prior to the appointment. Any cancellations within 24 hours may be subject to a $25 cancellation fee. 

*This is an automated email. PLEASE DO NOT REPLY. 
                </cfmail>
            </CFCASE>
             
             
            <CFCASE value="finalized">
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

We are pleased to inform your virtual tour for #qTours.address# is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
#mlsEmail#
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

We are pleased to inform you that your video for #qTours.address# is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
#mlsEmail#
                
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

Your tour has been updated for #qTours.address# and is online and available for viewing by clicking the link below:

http://www.spotlighthometours.com/tours/tour.cfm?tourid=#qTours.tourid#

You can also download the high and low resolution images by clicking on one of the links below: 

[ Small Resolution ] http://www.spotlighthometours.com/download-low.php?id=#qTours.tourid#
[ Medium Resolution ] http://www.spotlighthometours.com/download-med.php?id=#qTours.tourid#
[ High Resolution ] http://www.spotlighthometours.com/download-high.php?id=#qTours.tourid#
#mlsEmail#
                
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
            t.address,t.title,date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord,
            tt.tourTypeName as tourTypeN 
        from tours t, tourtypes tt
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#arguments.tourid#">
        	AND t.tourTypeID = tt.tourTypeID
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
An attempt has been made to schedule a tour for #qTours.address#. on #qGetTourDetails.ScheduleAtton# at #qGetTourDetails.ScheduleAttat#
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
                }/>
                <cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
Your tour for #qTours.address#. is now available
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
The Video for #qTours.address#. is now available
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
Your tour has been updated for #qTours.address# and is online and available for viewing by clicking the link below:
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
</cfcomponent>
