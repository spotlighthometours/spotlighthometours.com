<cfscript>
/**
 * Fixes text using Microsoft Latin-1 &quot;Extentions&quot;, namely ASCII characters 128-160.
 * ASCII8217 mod by Tony Brandner
 * 
 * @param text      Text to be modified. (Required)
 * @return Returns a string. 
 * @author Shawn Porter (sporter@rit.net) 
 * @version 2, September 2, 2010 
 */
function deMoronize (text) {
    var i = 0;

    // map incompatible non-ISO characters into plausible 
    // substitutes
    text = Replace(text, Chr(128), "&euro;", "All");

    text = Replace(text, Chr(130), ",", "All");
    text = Replace(text, Chr(131), "<em>f</em>", "All");
    text = Replace(text, Chr(132), ",,", "All");
    text = Replace(text, Chr(133), "...", "All");
        
    text = Replace(text, Chr(136), "^", "All");

    text = Replace(text, Chr(139), ")", "All");
    text = Replace(text, Chr(140), "Oe", "All");

    text = Replace(text, Chr(145), "`", "All");
    text = Replace(text, Chr(146), "'", "All");
    text = Replace(text, Chr(147), """", "All");
    text = Replace(text, Chr(148), """", "All");
    text = Replace(text, Chr(149), "*", "All");
    text = Replace(text, Chr(150), "-", "All");
    text = Replace(text, Chr(151), "--", "All");
    text = Replace(text, Chr(152), "~", "All");
    text = Replace(text, Chr(153), "&trade;", "All");

    text = Replace(text, Chr(155), ")", "All");
    text = Replace(text, Chr(156), "oe", "All");

    // remove any remaining ASCII 128-159 characters
    for (i = 128; i LTE 159; i = i + 1)
        text = Replace(text, Chr(i), "", "All");

    // map Latin-1 supplemental characters into
    // their &name; encoded substitutes
    text = Replace(text, Chr(160), "&nbsp;", "All");

    text = Replace(text, Chr(163), "&pound;", "All");

    text = Replace(text, Chr(169), "&copy;", "All");

    text = Replace(text, Chr(176), "&deg;", "All");

    // encode ASCII 160-255 using ? format
    for (i = 160; i LTE 255; i = i + 1)
        text = REReplace(text, "(#Chr(i)#)", "&###i#;", "All");

    for (i = 8216; i LTE 8218; i = i + 1) text = Replace(text, Chr(i), "'", "All");
      
    // supply missing semicolon at end of numeric entities
    text = ReReplace(text, "&##([0-2][[:digit:]]{2})([^;])", "&##\1;\2", "All");
    
    // fix obscure numeric rendering of &lt; &gt; &amp;
    text = ReReplace(text, "&##038;", "&amp;", "All");
    text = ReReplace(text, "&##060;", "&lt;", "All");
    text = ReReplace(text, "&##062;", "&gt;", "All");

    // supply missing semicolon at the end of &amp; &quot;
    text = ReReplace(text, "&amp(^;)", "&amp;\1", "All");
    text = ReReplace(text, "&quot(^;)", "&quot;\1", "All");

    return text;
}
</cfscript>


<cffunction name="generateFetchUserHash" output="no" returntype="string">
<cfargument name="userID" type="numeric" required="no">

<cfset string = RandString(length="20") />

<CFQUERY name="hashCheck" datasource="#request.dsn#">
        select userID from user_hash
        where hash = <cfqueryparam cfsqltype="cf_sql_varchar" value="#string#" />
    </CFQUERY>
    <CFIF hashCheck.RecordCount gt 0>
        <cfset string = generateFetchUserHash(userID=#userID#) />
    <CFELSE>
        <CFQUERY datasource="#request.db.dsn#" result="insertedHashResult">
            insert into user_hash (userID, hash)
            values (<cfqueryparam value="#userID#" cfsqltype="cf_sql_varchar" maxlength="20">,
                    <cfqueryparam value="#string#" cfsqltype="cf_sql_varchar" maxlength="50">
                    )
        </CFQUERY>
    </CFIF>
    <cfreturn string />
</cffunction>


<cffunction name="RandString" output="no" returntype="string">
<cfargument name="length" type="numeric" required="no">

<!--- Local vars --->
<cfset var result="">
<cfset var i=0>

<!--- set a default length --->
<cfparam name="arguments.length" default="#RandRange(5,9)#">

<!--- Create string --->
<cfloop index="i" from="1" to="#ARGUMENTS.length#">
<!--- Random character in range A-Z --->
<cfset result=result&Chr(RandRange(65, 90))>
</cfloop>

<!--- Return it --->
<cfreturn result>
</cffunction>









<CFPARAM name="url.action" default="">
<CFPARAM name="url.pg" default="listUsers">
<CFPARAM name="msg" default="">
<CFPARAM name="errorMsg" default="">
<CFPARAM name="jpegquality" default="100">

<CFOBJECT name = "members" component = "ColdFusionFunctions.Members">
<CFOBJECT name = "SendNote" component = "ColdFusionFunctions.SendNotifications">

<CFSET REQUEST.RequestCommitted = false />  
<!---<CFSET REQUEST.RequestCommitted1 = false />--->  
<CFSWITCH expression="#url.action#">











<CFCASE value="updateProgress,cancelPhotoProgress,cancelVideoProgress">
    <CFPARAM name="url.tour" default="" />
    <CFPARAM name="url.user" default="" />
    
    <CFPARAM name="msg" default="">
    <CFPARAM name="errorMsg" default="">
    <CFPARAM name="form.savenotify" default=0>
    <CFPARAM name="form.save" default=0>
    
	<CFSET url.tour = form.tour />
    <CFSET url.user = form.user />
    <CFSET notify = 0 />
    
    <CFIF form.saveType eq True>
        <CFSET notify = 1 />
    </CFIF>
    
    <CFPARAM name="form.ScheduleAttempt" default="" />
    <CFPARAM name="form.Scheduled" default="" />
    <CFPARAM name="form.MediaEdited" default=0 />
    <CFPARAM name="form.VideoEdited" default=0 />
    
    <CFPARAM name="form.housestatus" default="" />
    <CFPARAM name="form.locknum" default="" />

    <CFPARAM name="form.MediaReceived" default=0 />
    <CFPARAM name="form.VideoMediaReceived" default=0 />
    <CFPARAM name="form.mediareceivedNotes" default="" />
    
    <CFPARAM name="form.user" default="" />
    <CFPARAM name="form.tour" default="" />
    
    <CFPARAM name="form.stage" default="0" />
    
    <CFPARAM name="form.housestatus" default="1" />
    <CFPARAM name="form.locknum" default="" />
    
    <CFPARAM name="form.contactNotes" default="" />
    <CFPARAM name="form.ScheduledNotes" default="" />
    <CFPARAM name="form.mediareceivedNotes" default="" />
    <CFPARAM name="form.editedNotes" default="" />
    <CFPARAM name="form.shootNotes" default="" />
    <CFPARAM name="form.agentNotes" default="" />
    <CFPARAM name="form.agentNReScheduledonotes" default="" />
    
    <CFPARAM name="form.numreceived" default="0" />
    <CFPARAM name="form.numedited" default="0" />
    <CFPARAM name="form.finalized" default=0 />
    
    <CFPARAM name="form.VideoFinalized" default=0 />
    <CFPARAM name="form.ReshootFinalized" default=0 />
    <CFPARAM name="form.EditVideoPhotographer" default="" />
    <CFPARAM name="form.Editedon" default="" />
    <CFPARAM name="form.Billing" default=0 />
    <CFPARAM name="form.mls" default=0 />
    <CFPARAM name="form.Realtorcom" default=0 />    
    <CFPARAM name="form.CopyPhotoInfo" default=0 />
    
    <CFQUERY name="qTours" datasource="#request.db.dsn#">
        select t.tourid, t.tourTypeID, tt.tourCategory,
            tt.tourTypeName as tourTypeN, tp.*
        from tours t, tourtypes tt, tourprogress tp
        where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#url.tour#">
            AND t.tourTypeID = tt.tourTypeID
            AND t.tourID = tp.tourID
    </CFQUERY>

	<CFIF form.numreceived eq "">
        <CFSET form.numreceived = 0 />
    </CFIF>
    
    <CFIF form.numedited eq "">
        <CFSET form.numedited = 0 />
    </CFIF>
    
    <CFIF form.stage eq 1>
        <cfif form.ScheduleAttemptedon eq "">
            <CFSET errorMsg="Please select the date and time for Schedule Attempt">
        </cfif>
    </CFIF>
    <CFIF form.stage eq 2>
        <cfif qTours.isPhotoTour eq 1>
        	<!---<cfif form.scheduledon eq "" && qTours.isVideoTour eq 0>
            	<CFSET errorMsg="Please select the Photography Schedule Date">
        	</cfif>--->
        	<cfif form.scheduledon neq "" && form.photographer eq "0">
            	<CFSET errorMsg="Please select the Photo Photographer to Shoot the property">
        	</cfif>
            <cfif form.ReScheduledon neq "" && form.rephotographer eq "0">
    	        <CFSET errorMsg="Please select the Photo Photographer to Re-Shoot the property">
	        </cfif> 
        </cfif>  
        <cfif qTours.isVideoTour eq 1>
        	<!---<cfif form.VideoScheduledOn eq "" && qTours.isPhotoTour eq 0>
            	<CFSET errorMsg="Please select the Video Photography Schedule Date">
        	</cfif>--->
        	<CFIF form.VideoScheduledOn neq "" && form.VideoPhotographer eq "0">
	            <CFSET errorMsg="Please select the Video Photographer to Shoot the property">
            </CFIF>   
   		    <cfif form.VideoReScheduledOn neq "" && form.VideoRePhotographer eq "0">
	            <CFSET errorMsg="Please select the Video Photographer to Re-Shoot the property">
	        </cfif> 
        </cfif>
    </CFIF>
    <cfif qTours.isPhotoTour eq 1>
        <CFIF val(form.MediaReceived)>
			<cfif form.MediaReceivedon eq "">
                <CFSET errorMsg="Please select the date and time for Media Received">
            </cfif>  
        </CFIF>
        <CFIF val(form.MediaEdited) and form.Editedon eq "">
			<CFSET errorMsg="Please select the date and time for Photo Media Edited.">
        </CFIF>
    </cfif>
    <cfif qTours.isVideoTour eq 1>
		<CFIF val(form.VideoMediaReceived)>    
        	<cfif qTours.tourCategory eq "Video Tours" && form.VideoMediaReceivedOn eq "">
            	<CFSET errorMsg="Please select the date and time for Video Media Received Date/Time">
	        </cfif>
    	</CFIF>
    
	    <CFIF val(form.VideoEdited)>    
    	    <cfif qTours.tourCategory eq "Video Tours" && form.VideoMediaReceivedOn eq "">
        	    <CFSET errorMsg="Please select the date and time for Video Media Edited Date/Time">
	        </cfif>
    	</CFIF>
    </cfif>
    
    <CFIF not len(errorMsg)>
        <CFIF val(form.MediaReceived) || val(form.VideoMediaReceived) || val(form.MediaEdited) || val(form.VideoEdited) || val(form.finalized) || val(form.VideoFinalized) || val(form.ReshootFinalized)>
            <CFSET inProcess = 1>
        <CFELSE>
            <CFSET inProcess = 0>
        </CFIF>
        <CFIF #form.stage# lt 3 && inProcess eq 1>
            <CFSET stage = 8>
        <CFELSE>
        	<cfif #qTours.stage# gt 2>
            	<CFSET stage = 2>
            <cfelse>
            	<CFSET stage = #form.stage#>
            </cfif>
        </CFIF>
             
        <CFQUERY name="qTourProgressUpdate" datasource="#request.db.dsn#">
                UPDATE tourprogress set 
                    housestatus1=<cfif isDefined('form.housestatus1')>1<cfelse>0</cfif>,
                    housestatus2=<cfif isDefined('form.housestatus2')>1<cfelse>0</cfif>,
                    housestatus3=<cfif isDefined('form.housestatus3')>1<cfelse>0</cfif>,
                    housestatus4=<cfif isDefined('form.housestatus4')>1<cfelse>0</cfif>,
                    locknum=<cfqueryparam value="#form.locknum#" cfsqltype="cf_sql_varchar" />,
               		
                    contactNotes='#form.contactNotes#',
                    MediareceivedNotes='#form.mediareceivedNotes#',
                    EditedNoted='#form.editedNotes#',
                    shootNotes='#form.shootNotes#',
                    
					<cfif #form.stage# eq 1>
                        ScheduleAttempted='1',
                        ScheduleAttemptednotify='#notify#',
                        <cfif form.ScheduleAttemptedon neq "">
                            ScheduleAttemptedon='#form.ScheduleAttemptedon#',
                        </cfif>
                    </cfif>
                    
                    
                        ScheduledNotes='#form.ScheduledNotes#',
                    	photographer='#form.photographer#',
                        rephotographer='#form.rephotographer#',
                        mediaphotographer='#form.mediaphotographer#',
                        editphotographer='#form.editphotographer#',
                        MediaReceived='#val(form.MediaReceived)#',
                        numreceived='#form.numreceived#',
                        numedited='#form.numedited#',
                        Edited='#val(form.MediaEdited)#',
                        finalized='#val(form.finalized)#',
                    
						<cfif form.Scheduledon neq "">
                            Scheduledon='#form.Scheduledon#',
                        <cfelse>
                            <!---<CFIF #url.action# eq "cancelPhotoProgress">---> 
                                Scheduledon=NULL,
                            <!---</CFIF>--->
                        </cfif>
                        <cfif form.MediaReceivedon neq "">
                            MediaReceivedon='#form.MediaReceivedon#',
                        <cfelse>
                            MediaReceivedon=NULL,
                        </cfif>
                        <cfif form.edited_start neq "">
                            edited_start='#form.edited_start#',
                        <cfelse>
                            edited_start=NULL,
                        </cfif>
                        <cfif form.Editedon neq "">
                            Editedon='#form.Editedon#',
                        <cfelse>
                            Editedon=NULL,
                        </cfif>
                        <cfif form.ReScheduledon neq "">
                            ReScheduledon='#form.ReScheduledon#',
                        <cfelse>
                            ReScheduledon=NULL,
                        </cfif>
                        
                        <cfif isDefined('form.MediaRePhotographer')>
                            MediaRePhotographer='#form.MediaRePhotographer#',
                            <cfif form.NumReReceived neq "">
                                NumReReceived='#form.NumReReceived#',
                            </cfif>
                            EditRePhotographer='#form.EditRePhotographer#',
                            <cfif form.NumReEdited neq "">
                                NumReEdited='#form.NumReEdited#',
                            </cfif>
                                
                            <cfif form.MediaReReceivedOn neq "">
                                MediaReReceivedOn='#form.MediaReReceivedOn#',
                            <cfelse>
                                MediaReReceivedOn=NULL,
                            </cfif>
                            <cfif form.ReEditedStart neq "">
                                ReEditedStart='#form.ReEditedStart#',
                            <cfelse>
                                ReEditedStart=NULL,
                            </cfif>
                            <cfif form.ReEditedOn neq "">
                                ReEditedOn='#form.ReEditedOn#',
                            <cfelse>
                                ReEditedOn=NULL,
                            </cfif>
                        </cfif>
						    
						<cfif val(form.finalized) eq 1>
                            finalizednotify='#notify#',
                            <CFIF qTours.finalized eq 0>
                                finalizedon=now(),			
                            </CFIF>                    
                            TourBuilt=1,
                            <cfif qTours.TourBuilt eq 0>
                                TourBuilton=now(),			
                            </cfif>
                        </cfif>
                        
                        <cfif #form.stage# eq 2>
                            Scheduled='1',
                            Schedulednotify='#notify#',
                        </cfif>
                        
                    
					
					
                        VideoPhotographer='#form.VideoPhotographer#',
                        VideoRePhotographer='#form.VideoRePhotographer#',
                        VideoMediaReceived='#val(form.VideoMediaReceived)#',        
                        VideoMediaPhotographer='#form.VideoMediaPhotographer#',
                        <cfif form.VideoNumReceived neq "">
                            VideoNumReceived = '#form.VideoNumReceived#',
                        </cfif>
                        VideoEditPhotographer='#form.VideoEditPhotographer#',
                        VideoEdited='#val(form.VideoEdited)#',
                        <cfif form.VideoNumEdited neq "">
                        	VideoNumEdited = '#form.VideoNumEdited#',
                        </cfif>
                        VideoScheduledNotes = '#form.VideoScheduledNotes#',
                        
                        <cfif isDefined('form.VideoMediaRePhotographer')>
                            <cfif form.VideoNumReReceived neq "">
                            	VideoNumReReceived = '#form.VideoNumReReceived#',
                            </cfif>
                            VideoMediaRePhotographer='#form.VideoMediaRePhotographer#',
                            VideoEditRePhotographer='#form.VideoEditRePhotographer#',
                            <cfif form.VideoNumReEdited neq "">
                            	VideoNumReEdited = '#form.VideoNumReEdited#',
                            </cfif>
                                
                            <cfif form.VideoMediaReReceivedOn neq "">
                                VideoMediaReReceivedOn='#form.VideoMediaReReceivedOn#',
                            <cfelse>
                                VideoMediaReReceivedOn=NULL,
                            </cfif>
                            
							<cfif form.VideoReEditedOn neq "">
                                VideoReEditedOn='#form.VideoReEditedOn#',
                            <cfelse>
                                VideoReEditedOn=NULL,
                            </cfif>
                            <cfif form.VideoReEditedStart neq "">
                                VideoReEditedStart='#form.VideoReEditedStart#',
                            <cfelse>
                                VideoReEditedStart=NULL,
                            </cfif>
							<cfif form.VideoReScheduledOn neq "">
                                VideoReScheduledOn='#form.VideoReScheduledOn#',
                            <cfelse>
                                VideoReScheduledOn=NULL,
                            </cfif>
                        </cfif>
                        <cfif form.VideoMediaReceivedOn neq "">
                            VideoMediaReceivedOn='#form.VideoMediaReceivedOn#',
                        <cfelse>
                            VideoMediaReceivedOn=NULL,
                        </cfif>
                        
                        <cfif form.VideoEditedOn neq "">
                            VideoEditedOn='#form.VideoEditedOn#',
                        <cfelse>
                            VideoEditedOn=NULL,
                        </cfif>
                        <cfif form.VideoEditedStart neq "">
                            VideoEditedStart='#form.VideoEditedStart#',
                        <cfelse>
                            VideoEditedStart=NULL,
                        </cfif>

                        VideoFinalized='#val(form.VideoFinalized)#',
                        <cfif val(form.VideoFinalized) && qTours.VideoFinalized eq 0>
                            VideoFinalizedOn=now(),
                            finalizednotify='#notify#',
                            <CFIF qTours.finalized eq 0>
                                finalizedon=now(),			
                            </CFIF>     
                            <cfif qTours.TourBuilt eq 0>               
                            	TourBuilt=1,
                                TourBuilton=now(),			
                            </cfif>
                        </cfif>
                        
                        ReshootFinalized='#val(form.ReshootFinalized)#',
                        <cfif val(form.ReshootFinalized) && qTours.ReshootFinalized eq 0>
                            ReshootFinalizedOn=now(),
                            finalizednotify='#notify#',
                            <CFIF qTours.finalized eq 0>
                                finalizedon=now(),			
                            </CFIF>     
                            <cfif qTours.TourBuilt eq 0>               
                            	TourBuilt=1,
                                TourBuilton=now(),			
                            </cfif>
                        </cfif>
                        
                        <cfif #form.stage# eq 2>
	                        <cfif form.VideoScheduledOn neq "">
    	                        VideoScheduledOn='#form.VideoScheduledOn#',
        	                    VideoScheduled = 1,
            	            <cfelse>
                	            VideoScheduledOn=NULL,
	                    	    VideoScheduled = 0,
	                        </cfif>
                        </cfif> 
                      
                    <cfif form.PhotographerPaid neq "">
                        PhotographerPaid='#form.PhotographerPaid#',
                    </cfif>
                    <cfif form.InvoiceGenerated neq "">
                        InvoiceGenerated='#form.InvoiceGenerated#',
                    </cfif>
                    <cfif form.paidbycreditcard neq "">
                        paidbycreditcard='#form.paidbycreditcard#',
                    </cfif>
                    <cfif val(form.Realtorcom)>
                        Realtorcom='1',
                        Realtorcomon=now(),
                    <cfelse>
                        Realtorcom='0',
                    </cfif>
                    <cfif val(form.Billing)>
                        Billing='1',
                    <cfelse>
                        Billing='0',
                    </cfif>
                    <cfif val(form.mls) OR val(form.finalized)>
                        mls='1',
                        mlson=now(),
                    <cfelse>
                        mls='0',
                    </cfif>
                    <cfif isDefined('form.invoiceSent')>
                        invoiceSent='1',
                    <cfelse>
                        invoiceSent='0',
                    </cfif>
                    <cfif isDefined('form.follow_up')>
                        follow_up='1',
                    <cfelse>
                        follow_up='0',
                    </cfif>
                    stage='#stage#',
                    CopyPhotoInfo='#form.CopyPhotoInfo#',
                    paid = <cfif isDefined('form.paid')>1<cfelse>0</cfif>
                
                WHERE tourid=<cfqueryparam value="#form.tour#" cfsqltype="cf_sql_integer" />
        </CFQUERY>
        
        <CFIF #notify#>
            <cfif val(form.finalized)>
             	<cfhttp url="http://www.spotlighthometours.com/repository_queries/mls_send_tour.php" method="POST">
                	<cfhttpparam type="FORMFIELD" name="id" value="#form.tour#">
                </cfhttp>
            </cfif>
			<CFIF #form.stage# eq 1>
                <CFOUTPUT>Schedule Attempt<br></CFOUTPUT>
                <CFSET tempk0=SendNote.sendNotification('scheduledatt','scheduleattempt',form.tour) />
                <CFOUTPUT>Schedule Attempt Teams<br></CFOUTPUT>
                <CFSET tempk4= SendNote.sendNotificationTeams('scheduledatt','scheduleattempt',form.tour) />
            </CFIF>
            <CFIF #form.stage# eq 2 > 
                <CFOUTPUT>Scheduled<br></CFOUTPUT>
                <CFSET tempk1= SendNote.sendNotification('scheduled','scheduled',form.tour) />
                <CFOUTPUT>Scheduled Teams<br></CFOUTPUT>
                <CFSET  tempk5= SendNote.sendNotificationTeams('scheduled','scheduled',form.tour) />
                <CFOUTPUT>Scheduled Affiliate<br></CFOUTPUT>
                <CFSET  tempk8= SendNote.sendNotificationAffiliate('scheduled','scheduled',form.tour) />
            </CFIF>
            <CFIF #form.stage# gt 2 && val(form.VideoFinalized) && qTours.VideoFinalized && val(form.finalized) && qTours.finalized>
                <CFOUTPUT>Tour Updated<br></CFOUTPUT>
                <CFSET  tempk7= SendNote.sendNotification('finalized','tour updated',form.tour) />
                <CFOUTPUT>Tour Updated Teams<br></CFOUTPUT>
                <CFSET  tempk7= SendNote.sendNotificationTeams('finalized','tour updated',form.tour) />
            </CFIF>
			<CFIF val(form.VideoFinalized)>
                <cfhttp url="http://www.spotlighthometours.com/repository_queries/tour-finalized.php" method="POST" timeout="3">
                    <cfhttpparam type="FORMFIELD" name="tourID" value="#form.tour#">
                    <cfhttpparam type="FORMFIELD" name="finalizedType" value="video">
                </cfhttp>
				<CFOUTPUT>Video Finalized<br></CFOUTPUT>
                <CFSET  tempk3= SendNote.sendNotification('finalized','video finalized',form.tour) />
                <CFOUTPUT>Video Finalized Teams<br></CFOUTPUT>
                <CFSET  tempk7= SendNote.sendNotificationTeams('finalized','video finalized',form.tour) />
                <CFOUTPUT>Video Finalized Affiliate<br></CFOUTPUT>
                <CFSET  tempk9= SendNote.sendNotificationAffiliate('finalized','video finalized',form.tour) />
            </CFIF>
            <CFIF val(form.ReshootFinalized)>
                <cfhttp url="http://www.spotlighthometours.com/repository_queries/tour-finalized.php" method="POST" timeout="3">
                    <cfhttpparam type="FORMFIELD" name="tourID" value="#form.tour#">
                    <cfhttpparam type="FORMFIELD" name="finalizedType" value="reshoot">
                </cfhttp>
				<CFOUTPUT>Reshoot Finalized<br></CFOUTPUT>
                <CFSET  tempk2= SendNote.sendNotification('finalized','reshoot finalized',form.tour) />
				<CFOUTPUT>Photo Finalized Teams<br></CFOUTPUT>
                <CFSET  tempk6= SendNote.sendNotificationTeams('finalized','reshoot finalized',form.tour) />
                <CFOUTPUT>Photo Finalized Affiliate<br></CFOUTPUT>
               	<CFSET  tempk10= SendNote.sendNotificationAffiliate('finalized','reshoot finalized',form.tour) />
            </CFIF>
            <CFIF val(form.finalized)>
                <cfhttp url="http://www.spotlighthometours.com/repository_queries/tour-finalized.php" method="POST" timeout="3">
                    <cfhttpparam type="FORMFIELD" name="tourID" value="#form.tour#">
                    <cfhttpparam type="FORMFIELD" name="finalizedType" value="photo">
                </cfhttp>
				<CFOUTPUT>Photo Finalized<br></CFOUTPUT>
                <CFSET  tempk2= SendNote.sendNotification('finalized','finalized',form.tour) />
				<CFOUTPUT>Photo Finalized Teams<br></CFOUTPUT>
                <CFSET  tempk6= SendNote.sendNotificationTeams('finalized','finalized',form.tour) />
                <CFOUTPUT>Photo Finalized Affiliate<br></CFOUTPUT>
               	<CFSET  tempk10= SendNote.sendNotificationAffiliate('finalized','finalized',form.tour) />
            </CFIF>
        </CFIF>
        <CFSET msg="Tour Progress Successfully Saved">
    </CFIF>
    <CFSET url.pg = "toursheet" />
	    
</CFCASE> 









	

<CFCASE value="insertUser">
		<!--- check to see if the username already exists --->
		<CFQUERY name="qUserCheck" datasource="#request.dsn#">
			select userID from users
			where username = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.username)#" />
		</CFQUERY>
		<CFIF qUserCheck.RecordCount gt 0>
			<CFSET msg = "Username is already in use. Please use something else." />
		<CFELSE>
			<cfif #trim(form.brokerageID)# neq "">
				<!--- see if the user is a member of a brokerage with blanket sms coverage. if so, turn the ability on for them --->
                <CFQUERY name="qBrokeragePlans" datasource="#request.dsn#">
                    select id from mobile_brokerage_signup
                    where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#trim(form.brokerageID)#" />
                    and mobile_account_type_id = 1
                </CFQUERY>
                <CFIF qBrokeragePlans.RecordCount gt 0>
                    <!--- covered, update to 1 --->
                    <CFSET PreviewActive = 1 />
                <cfelse> 
                    <CFSET PreviewActive = 0 />
                </CFIF>
            <cfelse> 
                <CFSET PreviewActive = 0 />
            </cfif>
			<CFSCRIPT>
				phone    = form.phone;
				phone2   = form.phone2;
				fax      = form.fax;
			</CFSCRIPT>
            <CFSET brokerageID = 0 />
			<CFIF len(trim(form.brokerageID)) neq 0>
                <CFSET brokerageID = val(trim(form.brokerageID)) + 0 />
            </CFIF>
            <CFSET mlsProviderID = 0 />
			<CFIF len(trim(form.mlsProviderID)) neq 0>
                <CFSET mlsProviderID = val(trim(form.mlsProviderID)) + 0 />
            </CFIF>
            <CFQUERY datasource="#request.db.dsn#" result="insertedUserResult">
				insert into users (firstName, lastName, userType, salesRepID, BrokerageID, otherBrokerage, username, password, address, city, state, zipCode, phone, phonecarrier,phone2, fax, email, assistName, assistPhone, agentNotes, salesNotes, uri, uploadPrivilege, dateCreated, dateModified,emailUnsuscribe,TourWindowType,otherMLSProvider)
				values (<cfqueryparam value="#trim(form.firstName)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(form.lastName)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(form.userType)#" cfsqltype="cf_sql_varchar" maxlength="50">,
						<cfqueryparam value="#trim(form.salesRepID)#" cfsqltype="cf_sql_integer" maxlength="9">,
						<cfqueryparam value="#brokerageID#" cfsqltype="cf_sql_integer" maxlength="9">,
						<cfqueryparam value="#trim(form.otherBrokerage)#" cfsqltype="cf_sql_varchar" maxlength="50">,
						<cfqueryparam value="#trim(form.username)#" cfsqltype="cf_sql_varchar" maxlength="48">,
						<cfqueryparam value="#trim(form.password)#" cfsqltype="cf_sql_varchar" maxlength="60">,
						<cfqueryparam value="#trim(form.address)#" cfsqltype="cf_sql_varchar" maxlength="200">,
						<cfqueryparam value="#trim(form.city)#" cfsqltype="cf_sql_varchar" maxlength="50">,
						<cfqueryparam value="#trim(form.state)#" cfsqltype="cf_sql_varchar" maxlength="2">,
						<cfqueryparam value="#trim(form.zipCode)#" cfsqltype="cf_sql_varchar" maxlength="10">,
						<cfqueryparam value="#trim(phone)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#phonecarrier#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(phone2)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(fax)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(form.email)#" cfsqltype="cf_sql_varchar" maxlength="255">,
						<cfqueryparam value="#trim(form.assistName)#" cfsqltype="cf_sql_varchar" maxlength="100">,
						<cfqueryparam value="#trim(form.assistPhone)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(form.agentNotes)#" cfsqltype="cf_sql_varchar" maxlength="2000">,
						<cfqueryparam value="#trim(form.salesNotes)#" cfsqltype="cf_sql_varchar" maxlength="2000">,
						<cfqueryparam value="#replace(form.uri, "http://", "")#" cfsqltype="cf_sql_varchar" maxlength="255">,
						<cfif isDefined('form.uploadPrivilege')>1<cfelse>0</cfif>,
                        now(),
						now(),
                		<cfif isDefined('form.emailUnsuscribe')>1<cfelse>0</cfif>,
						<cfqueryparam value="#trim(form.TourWindowType)#" cfsqltype="cf_sql_varchar" maxlength="100">,
						<cfqueryparam value="#trim(form.otherMLSProvider)#" cfsqltype="cf_sql_varchar" maxlength="50">
						)
			</CFQUERY>
            
            <!--- INSERT MLS ID'S FOR USER --->
            <CFSET insteredUserID = insertedUserResult.generated_key>

            <cfset hashString = generateFetchUserHash(userID=#insteredUserID#) />

            <cfset mlsID = ListToArray(form.mls) />
        	<cfset mlsProviderID = ListToArray(form.mlsProviderID) />
            <cfloop from="1" to="#arrayLen(mlsID)#" index="i">
                <CFQUERY datasource="#request.db.dsn#">
                    INSERT INTO user_to_mls (userID, mlsID, mlsProvider)
                    VALUES (#insteredUserID#, #mlsID[i]#, #mlsProviderID[i]#)
                </CFQUERY>
			</cfloop>
            
			<CFSET msg = "The user was successfully added.">
            <CFQUERY name="qUser" datasource="#request.dsn#">
            	SELECT userID FROM users WHERE username = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.username)#" />
            </CFQUERY>
            <cfquery name = "qMemberships" datasource="#request.dsn#">
                SELECT * FROM memberships
            </CFQUERY>
            <CFLOOP query = "qMemberships">
        
                    <!-- If regular memberships and form checkbox is checked  OR Concierge membership and this one is picked on the form OR
                        the Preview membership AND the Brokerage has mobile_brokerage_signup ...  -->
                <cfif ((qMemberships.concierge eq 0 AND (isDefined('form.Membership#qMemberships.id#'))) OR 
                        (qMemberships.membershipType eq form.ConciergeLevel) OR (qMemberships.id eq 2 AND #PreviewActive# eq 1))>
                    <CFSET isMember = 1>
                <cfelse>
                    <CFSET isMember = 0>
                </cfif>
                
                <CFSET iRet = members.SetMembersActive(#qUser.userID#, #qMemberships.id#, #isMember#)>
    
            </CFLOOP>
            
		</CFIF>
	</CFCASE>
	<CFCASE value="updateUser">
		<CFSCRIPT>
			phone    = form.phone;
			phone2   = form.phone2;
			fax      = form.fax;
			PreviewActive = 0;
		</CFSCRIPT>

		<!--- get the users previous brokerage, see if it differs from the current. if differs, see if old brokerage had blanket or agent choice coverage (which they'd would then) --->
		<!--- if they are going to a brokerage w blanket coverage, include them --->
		<CFQUERY name="qOldBrokerage" datasource="#request.dsn#">
			select u.brokerageID, CASE WHEN ISNULL(mbs.mobile_account_type_id) THEN 0 ELSE mbs.mobile_account_type_id END as PreviewActive 
            	from users u
            	LEFT OUTER JOIN mobile_brokerage_signup mbs on mbs.brokerage_ID = u.brokerageID
                where u.userID = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.userID#" />
		</CFQUERY>

		<CFIF qOldBrokerage.brokerageID eq form.brokerageID>
			<!--- all is well. update as normal --->
			<CFSET PreviewActive = qOldBrokerage.PreviewActive />
		<CFELSE>
			<!--- save the brokerage history --->
			<CFQUERY name="qBrokerageHistory" datasource="#request.dsn#">
                INSERT INTO brokerage_history (id, brokerage_id, user_id, change_date)
                VALUES ('#CreateUUID()#', #qOldBrokerage.brokerageID#, <cfqueryparam cfsqltype="cf_sql_integer" value="#form.userID#" />, Now())
			</CFQUERY>
			<!--- the brokerages are different, check to see if the new brokerage has blanket coverage --->
			<CFQUERY name="qNewBrokerageCovered" datasource="#request.dsn#">
				select id from mobile_brokerage_signup
				where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#trim(form.brokerageID)#" />
				and mobile_account_type_id = 1
			</CFQUERY>
			<!--- the brokerages are different, check to see if the new brokerage has blanket coverage --->
			<CFQUERY name="qNewBrokerage" datasource="#request.dsn#">
				select affiliatePhotographerID FROM brokerages
				where brokerageID = <cfqueryparam cfsqltype="cf_sql_integer" value="#trim(form.brokerageID)#" />
			</CFQUERY>
			<CFIF qNewBrokerageCovered.RecordCount gt 0>
				<!--- covered, update to 1 --->
				<CFSET PreviewActive = 1 />
			<CFELSE>
				<!--- did they have blanket or agent choice coverage under old brokerage, in which case they loose it --->
				<CFQUERY name="qOldBrokerageCovered" datasource="#request.dsn#">
					select id from mobile_brokerage_signup
					where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#qOldBrokerage.brokerageID#" />
					and (mobile_account_type_id = 1 or mobile_account_type_id = 2)
				</CFQUERY>
				<CFIF qOldBrokerageCovered.RecordCount gt 0>
					<CFSET PreviewActive = 0 />
				</CFIF>
			</CFIF>
		</CFIF>

		<CFQUERY datasource="#request.db.dsn#">
			update users set
				firstName = <cfqueryparam value="#form.firstName#" cfsqltype="cf_sql_varchar" maxlength="20">,
				lastName = <cfqueryparam value="#form.lastName#" cfsqltype="cf_sql_varchar" maxlength="20">,
				userType = <cfqueryparam value="#trim(form.userType)#" cfsqltype="cf_sql_varchar" maxlength="50">,
				salesRepID = <cfqueryparam value="#form.salesRepID#" cfsqltype="cf_sql_integer" maxlength="9">,
				BrokerageID = <cfqueryparam value="#form.brokerageID#" cfsqltype="cf_sql_integer" maxlength="9">,
				otherBrokerage = <cfqueryparam value="#form.otherbrokerage#" cfsqltype="cf_sql_varchar" maxlength="50">,
				otherMLSProvider = <cfqueryparam value="#form.otherMLSProvider#" cfsqltype="cf_sql_varchar" maxlength="50">,
				username = <cfqueryparam value="#form.username#" cfsqltype="cf_sql_varchar" maxlength="48">,
				password = <cfqueryparam value="#form.password#" cfsqltype="cf_sql_varchar" maxlength="60">,
				address = <cfqueryparam value="#form.address#" cfsqltype="cf_sql_varchar" maxlength="200">,
				city = <cfqueryparam value="#form.city#" cfsqltype="cf_sql_varchar" maxlength="50">,
				state = <cfqueryparam value="#form.state#" cfsqltype="cf_sql_varchar" maxlength="2">,
				zipCode = <cfqueryparam value="#form.zipCode#" cfsqltype="cf_sql_varchar" maxlength="10">,
				phone = <cfqueryparam value="#phone#" cfsqltype="cf_sql_varchar" maxlength="30">,
				phonecarrier = <cfqueryparam value="#trim(phonecarrier)#" cfsqltype="cf_sql_varchar" maxlength="20">,
				phone2 = <cfqueryparam value="#phone2#" cfsqltype="cf_sql_varchar" maxlength="20">,
				fax = <cfqueryparam value="#fax#" cfsqltype="cf_sql_varchar" maxlength="20">,
				email = <cfqueryparam value="#form.email#" cfsqltype="cf_sql_varchar" maxlength="255">,
				assistName = <cfqueryparam value="#form.assistName#" cfsqltype="cf_sql_varchar" maxlength="100">,
				assistPhone = <cfqueryparam value="#form.assistPhone#" cfsqltype="cf_sql_varchar" maxlength="20">,
				agentNotes = <cfqueryparam value="#form.agentNotes#" cfsqltype="cf_sql_varchar" maxlength="2000">,
				salesNotes = <cfqueryparam value="#form.salesNotes#" cfsqltype="cf_sql_varchar" maxlength="2000">,
				uri = <cfqueryparam value="#replace(form.uri, "http://", "")#" cfsqltype="cf_sql_varchar" maxlength="255">,
				uploadPrivilege = <cfif isDefined('form.uploadPrivilege')>1<cfelse>0</cfif>,
                dateModified = now(),
                emailUnsuscribe= <cfif isDefined('form.emailUnsuscribe')>1<cfelse>0</cfif>,
				TourWindowType = <cfqueryparam value="#form.TourWindowType#" cfsqltype="cf_sql_varchar" maxlength="100">
                <CFIF qOldBrokerage.brokerageID neq form.brokerageID and qNewBrokerage.RecordCount gt 0>
	                , affiliatePhotographerID = <cfqueryparam cfsqltype="cf_sql_integer" value="#qNewBrokerage.affiliatePhotographerID#">
                </CFIF>
			where userID = #form.userID#
		</CFQUERY>
		<cfquery name = "qMemberships" datasource="#request.dsn#">
        	SELECT * FROM memberships
        </CFQUERY>
        <!--- DELETE MLS ID'S FOR USER --->
        <CFQUERY datasource="#request.db.dsn#">
            	DELETE FROM user_to_mls WHERE userID = #form.userID#
        </CFQUERY>
        <!--- INSERT MLS ID'S FOR USER --->
        <cfset mlsID = ListToArray(form.mls) />
        <cfset mlsProviderID = ListToArray(form.mlsProviderID) />
        <cfloop from="1" to="#arrayLen(mlsID)#" index="i">
        	<cfif #mlsID[i]# eq "Array">
            	<cfset mlsID[i] = "''" />
            </cfif>
            <CFQUERY datasource="#request.db.dsn#">
            	INSERT INTO user_to_mls (userID, mlsID, mlsProvider)
                VALUES (#form.userID#, #mlsID[i]#, #mlsProviderID[i]#)
            </CFQUERY>
		</cfloop>
        <CFLOOP query = "qMemberships">
        
            <!-- CHECK THE Memberships' STATUS FOR THIS USER -->
            
 				<!-- If regular memberships and form checkbox is checked  OR Concierge membership and this one is picked on the form OR
                	the Preview membership AND the Brokerage has mobile_brokerage_signup ...  -->
            <cfif ((qMemberships.concierge eq 0 AND (isDefined('form.Membership#qMemberships.id#'))) OR 
					(qMemberships.membershipType eq form.ConciergeLevel) OR (qMemberships.id eq 2 AND PreviewActive eq 1))>
                <CFSET isMember = 1>
            <cfelse>
                <CFSET isMember = 0>
            </cfif>
			
			<CFSET iRet = members.SetMembersActive(#form.userID#, #qMemberships.id#, #isMember#)>
            
        </CFLOOP>
        
		<CFSET msg = "The user was successfully updated.">
	</CFCASE>
	<CFCASE value="deleteUser">
		<CFQUERY datasource="#request.db.dsn#">
			delete from users where userID = #url.user#
		</CFQUERY>
         <!--- DELETE MLS ID'S FOR USER --->
        <CFQUERY datasource="#request.db.dsn#">
            	DELETE FROM user_to_mls WHERE userID = #form.userID#
        </CFQUERY>
		<CFSET msg = "The user was successfully deleted.">
	</CFCASE>

	<CFCASE value="insertTour">
        <cfset desc = DeMoronize(#form.description#) />
        <cfset instruct = DeMoronize(#form.additionalInstructions#) />
		<CFSET bHideAddress = iif(StructKeyExists(form,'hideAddress'),1,0) />
		<CFQUERY datasource="#request.db.dsn#">
			insert into tours (tourTypeID, title, userID, address, unitNumber, hideaddress, city, state, zipCode, sqFootage, acres, listPrice, maxPrice, bedrooms, bathrooms, mls, description, additionalInstructions, features, excerpt, walkthrus, videos, panoramics, photos, sold, featured, createdOn, modifiedOn,hideprice,hidesqfoot,hidebeds,hidebaths,hidecontact,couserID)
			values (<cfqueryparam value="#form.tourTypeID#" cfsqltype="cf_sql_integer" maxlength="20">,
					<cfqueryparam value="#form.title#" cfsqltype="cf_sql_varchar" maxlength="50">,
					<cfqueryparam value="#form.userID#" cfsqltype="cf_sql_integer" maxlength="50">,
					<cfqueryparam value="#form.address#" cfsqltype="cf_sql_varchar" maxlength="100">,
					<cfqueryparam value="#form.unitNumber#" cfsqltype="cf_sql_varchar" maxlength="20">,
					<cfqueryparam value="#bHideAddress#" cfsqltype="cf_sql_integer" >,
					<cfqueryparam value="#form.city#" cfsqltype="cf_sql_varchar" maxlength="50">,
					<cfqueryparam value="#form.state#" cfsqltype="cf_sql_varchar" maxlength="2">,
					<cfqueryparam value="#form.zipCode#" cfsqltype="cf_sql_varchar" maxlength="10">,
					<cfqueryparam value="#form.sqFootage#" cfsqltype="cf_sql_varchar" maxlength="10">,
                    <cfqueryparam value="#form.acres#" cfsqltype="cf_sql_varchar" maxlength="10">,
					<cfif len(form.listPrice)>
						<cfqueryparam value="#reReplace(form.listPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money" maxlength="20">,
					<cfelse>
						NULL,
					</cfif>
                    <cfif len(form.maxPrice)>
						<cfqueryparam value="#reReplace(form.maxPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money" maxlength="20">,
					<cfelse>
						'0.00',
					</cfif>
					<cfqueryparam value="#form.bedrooms#" cfsqltype="cf_sql_varchar" maxlength="3">,
					<cfqueryparam value="#form.bathrooms#" cfsqltype="cf_sql_varchar" maxlength="10">,
					<cfqueryparam value="#trim(form.mls)#" cfsqltype="cf_sql_text" >,
					<cfqueryparam value="#desc#" cfsqltype="cf_sql_varchar" maxlength="1000">,
					<cfqueryparam value="#instruct#" cfsqltype="cf_sql_varchar" maxlength="1000">,
					<cfqueryparam value="#form.features#" cfsqltype="cf_sql_longvarchar">,
					<cfqueryparam value="#form.excerpt#" cfsqltype="cf_sql_varchar" maxlength="1000">,
					<cfqueryparam value="#form.walkthrus#" cfsqltype="cf_sql_integer" maxlength="3">,
					<cfqueryparam value="#form.videos#" cfsqltype="cf_sql_integer" maxlength="3">,
					<cfqueryparam value="#form.panoramics#" cfsqltype="cf_sql_integer" maxlength="3">,
					<cfqueryparam value="#form.photos#" cfsqltype="cf_sql_integer" maxlength="3">,
					<cfif isDefined("form.sold")>1<cfelse>0</cfif>,
					<cfif isDefined("form.featured")>1<cfelse>0</cfif>,
					now(),
					now(),
                    <cfif isDefined("form.hideprice")>1<cfelse>0</cfif>,
					<cfif isDefined("form.hidesqfoot")>1<cfelse>0</cfif>,
                    <cfif isDefined("form.hidebeds")>1<cfelse>0</cfif>,
					<cfif isDefined("form.hidebaths")>1<cfelse>0</cfif>,
                    <cfif isDefined("form.hidecontact")>1<cfelse>0</cfif>,
                    <cfqueryparam value="#form.coagent#" cfsqltype="cf_sql_integer" maxlength="20">
			)
		</CFQUERY>

		<!--- need to send email for processing by the editorsm --->

		<CFSET url.pg = "tours">
		<CFSET url.user = form.userID>
		<CFSET msg = "The tour was successfully added.">
	</CFCASE>

	<CFCASE value="updateTour">
		<cfset desc = DeMoronize(#form.description#) />
        <cfset instruct = DeMoronize(#form.additionalInstructions#) />
		<CFSET bHideAddress = iif(StructKeyExists(form,'hideAddress'),1,0) />
		<CFSET archived = iif(StructKeyExists(form,'suspended') AND suspended eq 'on',1,0) />

		<cfhttp url="http://www.spotlighthometours.com/repository_queries/set-tour-archive.php" method="POST" timeout="3">
        	<cfhttpparam type="FORMFIELD" name="tourID" value="#form.tourID#">
            <cfhttpparam type="FORMFIELD" name="archived" value="#archived#">
        </cfhttp>


		<!--- get the mls number first. if mls number changes, need to update media modified date for sotheby's scheduled ftp tasks --->
		<CFQUERY name="getMLS" datasource="#request.db.dsn#">
			select MLS
			from tours
			where tourID = #form.tourID#
		</CFQUERY>
        
        <CFQUERY name="isVideoPhotoTourQ" datasource="#request.db.dsn#">
			SELECT videos, photos, hdr_photos FROM tourtypes WHERE tourTypeID = #form.tourTypeID#
		</CFQUERY>
        <cfset isVideoTour = 0 />
        <cfset isPhotoTour = 0 />
        <cfif isVideoPhotoTourQ.videos gt 0 >
        	<cfset isVideoTour = 1 />
        </cfif>
     	<cfif (isVideoPhotoTourQ.photos+isVideoPhotoTourQ.hdr_photos) gt 0 >
        	<cfset isPhotoTour = 1 />
        </cfif>
        <CFQUERY datasource="#request.db.dsn#">
        	UPDATE tourprogress SET isVideoTour = <cfqueryparam value="#isVideoTour#" cfsqltype="cf_sql_integer" >, isPhotoTour = <cfqueryparam value="#isPhotoTour#" cfsqltype="cf_sql_integer" > WHERE tourid = #form.tourID#
        </CFQUERY>
        
		<CFQUERY datasource="#request.db.dsn#">
			update tours set
			tourTypeID = <cfqueryparam value="#form.tourTypeID#" cfsqltype="cf_sql_integer" maxlength="20">,
			title = 		<cfqueryparam value="#form.title#" cfsqltype="cf_sql_varchar" maxlength="50">,
			address = 		<cfqueryparam value="#form.address#" cfsqltype="cf_sql_varchar" maxlength="100">,
			unitNumber = 		<cfqueryparam value="#form.unitNumber#" cfsqltype="cf_sql_varchar" maxlength="20">,
			hideaddress = <cfqueryparam value="#bHideAddress#" cfsqltype="cf_sql_integer" >,
			city = 		<cfqueryparam value="#form.city#" cfsqltype="cf_sql_varchar" maxlength="50">,
			state = 		<cfqueryparam value="#form.state#" cfsqltype="cf_sql_varchar" maxlength="2">,
			zipCode = 		<cfqueryparam value="#form.zipCode#" cfsqltype="cf_sql_varchar" maxlength="10">,
			sqFootage = <cfif len(form.sqFootage)><cfqueryparam value="#form.sqFootage#" cfsqltype="cf_sql_varchar" maxlength="10"><cfelse>0</cfif>,
            acres = <cfif len(form.acres)><cfqueryparam value="#form.acres#" cfsqltype="cf_sql_varchar" maxlength="10"><cfelse>0</cfif>,
			listPrice = 		<cfif len(form.listPrice)><cfqueryparam value="#reReplace(form.listPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money" maxlength="20"><cfelse>NULL</cfif>,
            maxPrice = 		<cfif len(form.maxPrice)><cfqueryparam value="#reReplace(form.maxPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money" maxlength="20"><cfelse>'0.00'</cfif>,
			bedrooms = <cfif len(form.bedrooms)><cfqueryparam value="#form.bedrooms#" cfsqltype="cf_sql_varchar" maxlength="3"><cfelse>0</cfif>,
			bathrooms = <cfif len(form.bathrooms)><cfqueryparam value="#form.bathrooms#" cfsqltype="cf_sql_varchar" maxlength="10"><cfelse>0</cfif>,
			<!--- mls = 		<cfqueryparam value="#trim(form.mls)#" cfsqltype="cf_sql_varchar" >, --->
			walkthrus = <cfqueryparam value="#form.walkthrus#" cfsqltype="cf_sql_integer" maxlength="3">,
			videos = 		<cfqueryparam value="#form.videos#" cfsqltype="cf_sql_integer" maxlength="3">,
			panoramics = 		<cfqueryparam value="#form.panoramics#" cfsqltype="cf_sql_integer" maxlength="3">,
			photos = 		<cfqueryparam value="#form.photos#" cfsqltype="cf_sql_integer" maxlength="3">,
			description = 		<cfqueryparam value="#desc#" cfsqltype="cf_sql_varchar" maxlength="3000">,
			additionalInstructions = <cfqueryparam value="#instruct#" cfsqltype="cf_sql_varchar" maxlength="5000">,
			features = <cfqueryparam value="#form.features#" cfsqltype="cf_sql_longvarchar">,
			excerpt = 		<cfqueryparam value="#form.excerpt#" cfsqltype="cf_sql_varchar" maxlength="1000">,
			sold = <cfif isDefined("form.sold")>1<cfelse>0</cfif>,
			featured = <cfif isDefined("form.featured")>1<cfelse>0</cfif>,
			suspended = <cfif StructKeyExists(form,'suspended') AND suspended eq 'on'>1<cfelse>NULL</cfif>,
			modifiedOn = 		<cfqueryparam value="#now()#" cfsqltype="cf_sql_timestamp" />,
            hideprice= <cfif isDefined("form.hideprice")>1<cfelse>0</cfif>,
			hidesqfoot=	<cfif isDefined("form.hidesqfoot")>1<cfelse>0</cfif>,
            hidebeds=<cfif isDefined("form.hidebeds")>1<cfelse>0</cfif>,
			hidebaths=<cfif isDefined("form.hidebaths")>1<cfelse>0</cfif>,
            <cfif isDefined("form.coagent") and len(trim(form.coagent)) gt 0>
            couserID = <cfqueryparam value="#form.coagent#" cfsqltype="cf_sql_integer" maxlength="20">,
            <cfelse>
            couserID =0,
			</cfif>
            TourWindowType='#form.TourWindowType#',
            matterportID = <cfqueryparam value="#form.matterportID#" cfsqltype="cf_sql_varchar" maxlength="200">,
            mtpTitle1 = <cfqueryparam value="#form.mtpTitle1#" cfsqltype="cf_sql_varchar" maxlength="200">,
            matterportID2 = <cfqueryparam value="#form.matterportID2#" cfsqltype="cf_sql_varchar" maxlength="200">,
            mtpTitle2 = <cfqueryparam value="#form.mtpTitle2#" cfsqltype="cf_sql_varchar" maxlength="200">,
			backgroundVideo = <cfqueryparam value="#form.backgroundVideo#" cfsqltype="cf_sql_varchar" maxlength="1000">,
            hidecontact=<cfif isDefined("form.hidecontact")>1<cfelse>0</cfif>,
			use_secondary_bkr_img=<cfif isDefined("form.use_secondary_bkr_img")>1<cfelse>0</cfif>
			where tourID = #form.tourID#
		</CFQUERY>

		<!---<CFIF (getMLS.RecordCount gt 0) and (getMLS.MLS neq trim(form.mls))>
			<!--- 'refresh' the media modified date so that it is picked up by sotheby's tasks --->
			<CFQUERY name="qUpdate" datasource="#request.db.dsn#">
				update media
				set modifiedOn = <cfqueryparam cfsqltype="cf_sql_timestamp" value="#now()#">
				where tourID = #form.tourID#
			</CFQUERY>
			<CFLOG text="Media modifiedOn date flushed for tourid #form.tourID#" />
		</CFIF> --->

		<CFSET url.pg = "tours">
		<CFSET url.user = form.userID>
		<CFSET msg = "The tour was successfully updated.">
	</CFCASE>

	<CFCASE value="deleteTour">
		<CFQUERY datasource="#request.db.dsn#">
			delete from tours where tourID = #url.tour#
		</CFQUERY>
		<CFSET url.pg = "tours">
		<CFSET msg = "The tour was successfully deleted.">
	</CFCASE>

	<CFCASE value="uploadMedia">
		<!--- todo: no longer even used anymore? --->
		<CFIF not len(form.mediaType)>
			<CFTHROW type="fileUpload" message="Invalid media type.">
		</CFIF>
		<CFSET mediaPath = expandPath("../../images/tours")>
		<!--- :: create tour media directory if one hasn't yet been created :: --->
		<CFIF not directoryExists("#mediaPath#/#form.tourID#")>
			<CFDIRECTORY action="create" directory="#mediaPath#/#form.tourID#">
		</CFIF>
		<CFSET mediaPath = "#mediaPath#/#form.tourID#">
		<CFSET validFileExt = "jpg,jpeg,mov,wmf,asf,wmv,wm,mp4,flv">

		<!--- :: if photo or panoramic do simple upload :: --->
		<CFIF isDefined("form.mediaFile")>

			<CFFILE action="upload" filefield="mediaFile" destination="#mediaPath#" nameconflict="overwrite">

			<CFIF not listContains(validFileExt, cffile.serverFileExt)>
				<CFFILE action="delete" file="#mediaPath#/#cffile.serverFile#">
				<CFTHROW type="fileUpload" message="Invalid file type.">
			</CFIF>
<!--- 			<cfif cffile.serverFileExt neq "flv">
				<CFX_DynamicImage
					NAME="IMAGE"
					ACTION = "ImageInfo"
				 	SRC = "#mediaPath#/#cffile.serverFile#">
			</cfif> --->
			<CFLOCK name="insertMedia" type="exclusive" timeout="5">
				<CFQUERY datasource="#request.db.dsn#">
					insert into media (tourID, mediaType, fileExt, room, description, tourIcon, createdOn, modifiedOn)
					values (#form.tourID#, '#form.mediaType#', '#cffile.serverFileExt#', '#form.room#', '#form.description#',<cfif isDefined("form.tourIcon")>1<cfelse>0</cfif>, #now()#, #now()#)
				</CFQUERY>
				<CFQUERY name="qMedia" datasource="#request.db.dsn#">
					select max(mediaID) as maxMediaID from media
				</CFQUERY>
			</CFLOCK>
			<CFIF cffile.serverFileExt eq "jpg" OR cffile.serverFileExt eq "jpeg">
				<!--- 120x80 --->
				<CFSET myImage=ImageNew("#mediaPath#/#cffile.serverFile#") />
				<CFSET ImageResize(myImage,"120","80","highestPerformance") />
				<CFIMAGE source="#myImage#" action="write" destination="#mediaPath#/#form.mediaType#_th_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes" />

				<!--- 214x113 --->
				<CFSET myImage=ImageNew("#mediaPath#/#cffile.serverFile#") />
				<CFSET ImageResize(myImage,"215","143","highestPerformance") />
				<CFSET ImageCrop(myImage,1,15,214,128) />
				<CFIMAGE source="#myImage#" action="write" destination="#mediaPath#/#form.mediaType#_sm_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes" />

				<!--- 400x300 --->
				<CFSET myImage=ImageNew("#mediaPath#/#cffile.serverFile#")>
				<CFSET ImageResize(myImage,"400","300","highQuality")>
				<CFIMAGE source="#myImage#" action="write" destination="#mediaPath#/#form.mediaType#_400_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes">

				<!--- 640x480 --->
				<CFSET myImage=ImageNew("#mediaPath#/#cffile.serverFile#")>
				<CFSET ImageResize(myImage,"640","480","highQuality")>
				<CFIMAGE source="#myImage#" action="write" destination="#mediaPath#/#form.mediaType#_640_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes">

				<!--- 800x600 --->
				<CFSET myImage=ImageNew("#mediaPath#/#cffile.serverFile#")>
				<CFSET ImageResize(myImage,"800","600","highQuality")>
				<CFIMAGE source="#myImage#" action="write" destination="#mediaPath#/#form.mediaType#_800_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes">

				<!--- 960x640 --->
				<CFSET myImage=ImageNew("#mediaPath#/#cffile.serverFile#")>
				<CFSET ImageResize(myImage,"960","640","highestPerformance")>
				<CFIMAGE source="#myImage#" action="write" destination="#mediaPath#/#form.mediaType#_960_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes">

				<!--- 600x400 --->
				<CFSET myImage=ImageNew("#mediaPath#/#cffile.serverFile#")>
				<CFSET ImageResize(myImage,"600","400","highQuality")>
				<CFIMAGE source="#myImage#" action="write" destination="#mediaPath#/#form.mediaType#_600_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes">

				<CFFILE action="rename" source="#mediaPath#/#cffile.serverFile#" destination="#mediaPath#/#form.mediaType#_high_#qMedia.maxMediaID#.#cffile.serverFileExt#">
			</CFIF>

		<!--- :: else upload the 4 files needed for a video :: --->
		<CFELSE>

			<CFIF not len(form.flv_file)>
				<CFTHROW message="Missing media file. Be sure you are uploading a video file.">
			</CFIF>

			<CFFILE action="upload" filefield="flv_file" destination="#mediaPath#" nameconflict="overwrite">

			<CFLOCK type="exclusive" timeout="5">
				<CFQUERY name="qMedia" datasource="#request.db.dsn#">
					insert into media (tourID, mediaType, room,  description, createdOn, modifiedOn)
					values (#form.tourID#, '#form.mediaType#', '#form.room#', '#form.description#', #now()#, #now()#)
				</CFQUERY>
				<CFQUERY name="qMedia" datasource="#request.db.dsn#">
					select max(mediaID) as maxMediaID from media
				</CFQUERY>
			</CFLOCK>


				<!--- :: throw error if invalid file type :: --->
				<CFIF cffile.serverFileExt neq 'flv'>
					<CFFILE action="delete" file="#mediaPath#/#cffile.serverFile#">
					<CFTHROW type="fileUpload" message="Invalid file type.">
				</CFIF>
				<CFFILE action="rename" source="#mediaPath#/#cffile.serverFile#" destination="#mediaPath#/#form.mediaType#_#qMedia.maxMediaID#.flv">


		</CFIF>


		<CFSET url.pg = "media">
		<CFSET url.tour = form.tourID>
		<CFLOCATION url="users.cfm?pg=media&tour=#form.tourid#" addtoken="no">
	</CFCASE>

	<CFCASE value="updateMedia">
		<CFSET mediaPath = expandPath("../../images/tours")>
		<CFSET mediaPath = "#mediaPath#/#form.tourID#">
		<CFSET validFileExt = "jpg,flv">

		<CFIF isDefined("form.tourIcon")>
			<CFQUERY datasource="#request.db.dsn#">
				UPDATE media SET tourIcon = 0
				WHERE tourID = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.tourID#">
			</CFQUERY>
		</CFIF>


		<CFQUERY datasource="#request.db.dsn#">
			update media set
			room = '#form.room#',
			description = '#form.description#',
			tourIcon = <cfif isDefined("form.tourIcon")>1<cfelse>0</cfif>,
			modifiedOn = #now()#
			where mediaID = #form.mediaID#
		</CFQUERY>

		<CFSET url.pg = "media">
		<CFSET url.tour = form.tourID>
		<CFLOCATION url="users.cfm?pg=media&tour=#form.tourid#" addtoken="no">
	</CFCASE>

	<CFCASE value="media">

	<CFIF isDefined('form.delete')>

	<CFSET mediaPath = expandPath("../../images/tours")>
            <CFQUERY name="qMedia" datasource="#request.db.dsn#">
				select * from media where mediaID in (#form.mediaIDs#)
			</CFQUERY>
			<CFSET mediaPath = expandPath("../../images/tours/#qMedia.tourID#")>
			<CFOUTPUT query="qMedia">
				<cfhttp url="http://www.spotlighthometours.com/repository_queries/admin_delete_media.php" method="GET">
            		<cfhttpparam type="FORMFIELD" name="mediaID" value="#mediaID#">
            	</cfhttp>
				<!--- <CFIF fileExists("#mediaPath#/photo_high_#mediaID#.jpg")>
					<CFFILE action="delete" file="#mediaPath#/photo_sm_#mediaID#.jpg">
					<CFFILE action="delete" file="#mediaPath#/photo_400_#mediaID#.jpg">
					<CFFILE action="delete" file="#mediaPath#/photo_th_#mediaID#.jpg">
					<CFFILE action="delete" file="#mediaPath#/photo_high_#mediaID#.jpg">
				</CFIF> --->
			</CFOUTPUT>
			<!--- <CFQUERY name="qMedia" datasource="#request.db.dsn#">
				delete from media where mediaID in (#form.mediaIDs#)
			</CFQUERY>
            <CFQUERY name="ptDelete" datasource="#request.db.dsn#">
				delete from photo_tour_images where mediaID in (#form.mediaIDs#)
			</CFQUERY> --->
			<CFSET msg = "Media was successfully deleted.">

		<CFELSEIF isDefined('form.updateshowontab')>
				<CFQUERY datasource="#request.db.dsn#">
					update media set show_on_tab = 0 where tourid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.tourid#">
				</CFQUERY>
				<CFQUERY datasource="#request.db.dsn#">
					update media set show_on_tab = 1 where mediaID in (#form.show_on_tab#)
				</CFQUERY>
			<CFSET msg = "Show on Tab info was saved.">

		<CFELSE>

			<CFSET aSortOrders = listToArray(form.sortOrder)>
			<CFSET aMediaIDs = listToArray(form.mediaID)>
		<CFLOOP from="1" to="#arrayLen(aMediaIDs)#" index="i">
			<CFQUERY datasource="#request.db.dsn#">
				update media set sortOrder = #aSortOrders[i]# where mediaID = #aMediaIDs[i]#
			</CFQUERY>

		</CFLOOP>
		<CFSET msg = "Display order updated.">
		</CFIF>
		<CFSET url.pg = "media">
	</CFCASE>
    <CFCASE value="payInvoice">
			<!--- could be coming from either the payInvoice pg or the purchaseSign pg; treat appropriately --->
			<CFSET previousPg = form.referencepage />
			<CFSET bErrorFlag = 0 />
			<CFSET errorMsg = "" />
			<!--- if previousPg is 'purchaseSign' need to compute amount and put number of signs, shipping info into the notes field--->
			<CFIF previousPg eq "purchaseSign">
				<CFIF IsNumeric(form.my12sign) AND IsNumeric(form.my16sign) AND (form.my12sign gt 0 OR form.my16sign gt 0)>
					<CFIF Trim(form.shippingAddress) neq "" and trim(form.shippingCity) neq "" and trim(form.shippingZipCode)>
						<CFSET form.amount = form.my12sign * 12 + form.my16sign * 16 />
						<CFSET form.notes = form.notes & chr(13) & chr(10) & "#form.my12sign# - 6 inch rider(s) ordered." />
						<CFSET form.notes = form.notes & chr(13) & chr(10) & "#form.my16sign# - 8 inch rider(s) ordered." />
						<CFSET form.notes = form.notes & chr(13) & chr(10) />
						<CFSET form.notes = form.notes & chr(13) & chr(10) & "Shipping Address:" />
						<CFSET form.notes = form.notes & chr(13) & chr(10) & "#form.shippingAddress#" />
						<CFSET form.notes = form.notes & chr(13) & chr(10) & "#form.shippingCity#, #form.shippingState# #form.shippingZipCode#" />
					<CFELSE>
						<CFSET errorMsg = "The shipping information needs to be included. Please try again." />
						<CFSET url.pg = previousPg />
						<CFSET bErrorFlag = 1 />
					</CFIF>
				<CFELSE>
					<CFSET errorMsg = "The sign amounts entered must be numeric values. Please try again." />
					<CFSET url.pg = previousPg />
					<CFSET bErrorFlag = 1 />
				</CFIF>
			</CFIF>
			<!--- check to see if we have a valid amount --->
			<CFIF bErrorFlag eq 0 and IsNumeric(form.amount)>
				<!--- check if there was an invoice given, if not make the form 'no invoice given'--->
				<CFIF Trim(form.invoicenumber) eq "">
					<CFSET form.invoicenumber = "No Invoice Number Given" />
				</CFIF>
				<!--- save record of payment to the database --->
				<CFSET transactionNumber = createUUID() />
				<CFQUERY name="qInsert" datasource="#request.db.dsn#">
					insert into invoices (invoiceID, number, amount, notes, createdOn,userID_fk)
					values (
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#transactionNumber#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.invoicenumber#" />,
						<cfqueryparam cfsqltype="cf_sql_float" value="#form.amount#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.notes#" />,
						<cfqueryparam cfsqltype="cf_sql_timestamp" value="#Now()#" />,
						<cfqueryparam cfsqltype="cf_sql_integer" value="#form.userid#">
					)
				</CFQUERY>
				<CFIF bErrorFlag eq 0>
				<CFTRY>
					<!--- attempt to process the transaction --->
					<CFSCRIPT>
						session.user.billing.name = trim(form.name);
						session.user.billing.address = trim(form.address);
						session.user.billing.city = trim(form.city);
						session.user.billing.state = trim(form.state);
						session.user.billing.zipCode = trim(form.zipCode);
						session.user.billing.phone = trim(form.phone1&form.phone2&form.phone3);

						session.user.creditcard.cardType = form.cardType;
						session.user.creditcard.number = reReplace(form.cardnumber,"[^0-9]","","all");
						session.user.creditcard.expMonth = form.expMonth;
						session.user.creditcard.expYear = form.expYear;
						session.user.cart.total = form.amount;
					</CFSCRIPT>

					<CFINCLUDE template="/store/_linkpoint.cfm">
					<CFCATCH type="any">
						<CFSET errorMsg = cfcatch.Message & cfcatch.Detail>
						<CFSET url.pg = previousPg />
						<CFSET bErrorFlag = 1 />
					</CFCATCH>
					</CFTRY>

					<CFIF bErrorFlag eq 0>
						<CFINCLUDE template="_invoiceemail.cfm">
						<!--- if processing is successful set the invoice entry to complete --->
						<CFQUERY name="qUpdate" datasource="#request.db.dsn#">
							update invoices set completed = 1 where invoiceID = <cfqueryparam cfsqltype="cf_sql_varchar" value="#transactionNumber#" />
						</CFQUERY>

						<CFSET url.pg = "invoiceComplete" />
					</CFIF>
				</CFIF>
			<CFELSE>
				<CFSET url.pg = previousPg />
				<CFIF errorMsg eq "">
					<CFSET errorMsg = "The amount entered was not a numeric amount. Please try again.">
				</CFIF>
			</CFIF>
		</CFCASE>
</CFSWITCH>
<CFCONTENT reset="no">
<CFIF url.pg eq "edituser">
	<CFINCLUDE template="_edituser.cfm">
<CFELSEIF url.pg eq "edituserlr">
	<CFINCLUDE template="_edituserlr.cfm">
<CFELSEIF url.pg eq "payinvoice">
	<CFINCLUDE template="_payinvoice.cfm">
<CFELSEIF url.pg eq "invoiceComplete">
	<CFINCLUDE template="_invoiceComplete.cfm">    

<CFELSEIF url.pg eq "orders">
	<CFINCLUDE template="orders/_listorders.cfm">
<CFELSEIF url.pg eq "toursheet">
	<CFINCLUDE template="tours/_toursheet.cfm">
<CFELSEIF url.pg eq "toursheetprint">
	<CFINCLUDE template="tours/_toursheetprint.cfm">
<CFELSEIF url.pg eq "editOrder">
	<CFINCLUDE template="orders/_editorder.cfm">
<CFELSEIF url.pg eq "tours">
	<CFINCLUDE template="tours/_listtours.cfm">
<CFELSEIF url.pg eq "editTour">
	<CFINCLUDE template="tours/_edittour.cfm">
<CFELSEIF url.pg eq "media">
	<CFINCLUDE template="tours/_listmedia.cfm">
<CFELSEIF url.pg eq "editMedia">
	<CFINCLUDE template="tours/_editmedia.cfm">
<CFELSEIF url.pg eq "slideshows">
	<CFINCLUDE template="tours/_slideshows.cfm">
<CFELSEIF url.pg eq "reorder">
	<CFINCLUDE template="_reorder.cfm">
<CFELSE>
	<CFINCLUDE template="_listusers.cfm">
</CFIF>
</cfcontent>
