
<CFPARAM name="url.print" default="0" />

<CFSILENT>
<CFQUERY name="qTourProgressCheck" datasource="#request.db.dsn#">
Select tourid from tourprogress where tourid=<cfqueryparam value="#url.tour#" cfsqltype="cf_sql_integer" />
</CFQUERY>
<CFIF qTourProgressCheck.RecordCount eq 0>
	<CFQUERY name="qTourProgressCheck" datasource="#request.db.dsn#">
    INSERT into tourprogress (tourid)values(<cfqueryparam value="#url.tour#" cfsqltype="cf_sql_integer" />)
    </CFQUERY>
</CFIF>

<CFQUERY name="qTours" datasource="#request.db.dsn#">
	select t.*,
    date_format(createdOn,'%m/%d/%Y - %h:%i:%s %p') as dateord, 
    tt.tourTypeName as tourTypeN, 
    (SELECT group_concat(mlsID separator ', ') FROM tour_to_mls WHERE tourID=t.tourID) as mlsn 
    from tours t, tourtypes tt
    where t.tourID =<cfqueryparam cfsqltype="cf_sql_int" value="#url.tour#">
         AND t.tourTypeID = tt.tourTypeID
</CFQUERY>
<CFQUERY name="qUser" datasource="#request.db.dsn#">
	select u.*,(select brokerageName from brokerages where brokerageid = u.brokerageid) as brokerageName,
    (select BrokerageDesc from brokerages where brokerageid = u.brokerageid) as BrokerageDesc,
    (select brokerageSchedulePhone from brokerages where brokerageid = u.brokerageid) as brokerSPhone
    from users u
	where u.userid =<cfqueryparam cfsqltype="cf_sql_int" value="#url.user#">
</CFQUERY>

<CFIF qUser.salesRepID neq 0>
    <CFQUERY name="qSalesRep" datasource="#request.db.dsn#">
        select s.fullName from salesreps s where s.salesRepID = <cfqueryparam cfsqltype="cf_sql_integer" value="#qUser.salesRepID#" />
    </CFQUERY>           
    <CFSET salerep =  qSalesRep.fullName />        
<CFELSE>
    <CFIF qUser.BrokerageID gt 0>
        <CFQUERY name="qSalesRep" datasource="#request.db.dsn#">
            select s.fullName from salesreps s where s.salesRepID = (select salesRepID from brokerages where brokerageID='#qUser.BrokerageID#' limit 1 ) ;
        </CFQUERY>    
        <CFSET salerep =  qSalesRep.fullName />                     
    <CFELSE>
        <CFSET salerep =  "" />    
    </CFIF> 	
</CFIF>


<CFQUERY name="qTourPro" datasource="#request.db.dsn#">
	select t.*, 
    DATE_FORMAT(edited_start,'%d %b %Y %k:%i') as e_start,
    DATE_FORMAT(editedon,'%d %b %Y %k:%i') as e_on,
    DATE_FORMAT(VideoEditedStart,'%d %b %Y %k:%i') as ve_start,
    DATE_FORMAT(VideoEditedOn,'%d %b %Y %k:%i') as ve_on,
    DATE_FORMAT(ReEditedStart,'%d %b %Y %k:%i') as re_start,
    DATE_FORMAT(ReEditedOn,'%d %b %Y %k:%i') as re_on,
    DATE_FORMAT(VideoReEditedStart,'%d %b %Y %k:%i') as vre_start,
    DATE_FORMAT(VideoReEditedOn,'%d %b %Y %k:%i') as vre_on
    from tourprogress t
	where t.tourid =<cfqueryparam cfsqltype="cf_sql_int" value="#url.tour#">
</CFQUERY>

<CFQUERY name="qPhotographers" datasource="#request.db.dsn#">
	select p.*
    from Photographers p
    order by p.state asc
</CFQUERY>

<CFQUERY name="qEditors" datasource="#request.db.dsn#">
	select e.*
    from editors e
    order by e.state asc
</CFQUERY>

<CFQUERY name="qToursAdditional" datasource="#request.db.dsn#">
    select po.productid,(select createdon from orders where orderid = po.orderid ) as orderedon, pt.productname,po.quantity,po.unitprice
    from orderdetails po,products pt
    where  po.orderid in (select orderid from orders where tourid=#url.tour# )
    and pt.productid = po.productid and pt.tourtypeid=0 and po.type='product' 
order by orderedon desc
</CFQUERY>

<CFQUERY name="qTourOrders" datasource="#request.db.dsn#">
    SELECT 
    orderID, subTotal, salesTax, total, broker_total, coupon, coupon_total
    FROM orders
    WHERE tourID = <cfqueryparam value="#url.tour#" cfsqltype="cf_sql_integer" />
</CFQUERY>

<CFIF qTours.state eq 'UT'>
	<CFSET color ="red" />
<CFELSEIF qTours.state eq 'CO'>
	<CFSET color ="##006633" />
<CFELSEIF qTours.state eq 'NV'>
	<CFSET color ="orange" />
<CFELSE>
	<CFSET color ="blue" />
</CFIF>

</CFSILENT>
<CFCONTENT reset="no">

<SCRIPT TYPE="text/javascript">
	window.onload=function(){
		print(document);
		history.go(-1);
	};
	
</SCRIPT>
<HTML>
<HEAD>
<TITLE>Print Tour Sheet</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
<LINK HREF="../../repository_css/jquery-ui-1.8.16.custom.css" REL="stylesheet" TYPE="text/css">
<SCRIPT SRC="../../repository_inc/jquery-1.6.2.min.js"></SCRIPT>
<SCRIPT SRC="../../repository_inc/jquery-ui-1.8.16.custom.min.js" TYPE="text/javascript"></SCRIPT>
<SCRIPT SRC="../../repository_inc/jquery-ui-timepicker-addon.js" TYPE="text/javascript"></SCRIPT>
<style>
	/* css for timepicker */
	.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
	.ui-timepicker-div dl { text-align: left; }
	.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
	.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
	.ui-timepicker-div td { font-size: 90%; }
	.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
</style>
<STYLE TYPE="text/css">
<!--
.style1 {color: #666666}
-->
</STYLE>
</HEAD>
<STYLE>
table{
color:#003399;
font-weight:bold;
}
#header td{
font-size:9px;
}
</STYLE>
<BODY>
<CFOUTPUT>
<FORM ID="toursheet"   method="post" ACTION="?action=updateprogress" >
<INPUT TYPE="hidden" NAME="tour"  value="#url.tour#">
<INPUT TYPE="hidden" NAME="user"  value="#url.user#">

<TABLE WIDTH="840" BORDER="0" CELLSPACING="0" CELLPADDING="0">
  <TR>
  	  <TD colspan="9" align="center"><font size="+1" color="#color#">#qTours.state#</font></TD>
  <TR>
      <!---<DIV style="border-style: solid;border-color: #color#; border-width:thick"><BR>--->
      <TD><DIV style="border-style: solid;border-color: #color#; border-width:thick; -webkit-print-color-adjust:exact;"><TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" BGCOLOR="#color#">
      <TR STYLE="color:##FFFFFF;font-size:10px;" ID="header">
        <TD HEIGHT="13" align="center"><LABEL style="text-align:center">
          Schedule
          </LABEL></TD>
        <TD align="center"><LABEL>
          Scheduled </LABEL></TD>
        <TD align="center" colspan="2"><LABEL>
          Media Received </LABEL></TD>
        <TD align="center" colspan="2"><LABEL>
          Edited </LABEL></TD>
        <TD align="center"><LABEL>
          Realtor.com </LABEL></TD>
        <TD align="center"><LABEL>
          MLS </LABEL></TD>
        <TD align="center" colspan="2"><LABEL>
          Media Finalized </LABEL></TD>
        <TD align="center"><LABEL>
          Billing</LABEL></TD>
        <TD align="center"><LABEL>
          Follow Up </LABEL></TD>
      </TR>
      <TR STYLE="color:##FFFFFF;font-size:10px;" ID="header">
        <TD HEIGHT="22" align="center">
          Attempt<BR>
          <INPUT NAME="stage" TYPE="radio" ID="radio5" VALUE="1" <cfif qTourPro.stage eq 1 and qTourPro.ScheduleAttempted eq 1>checked</cfif>>
        </TD>
        <TD align="center">
          <BR>
          <INPUT NAME="stage" TYPE="radio" ID="radio6" VALUE="2" <cfif qTourPro.stage eq 2 and qTourPro.Scheduled eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isPhotoTour eq 0><font color="#000000#">Photos</font><cfelse>Photos</cfif><BR>
          <INPUT NAME="MediaReceived" TYPE="checkbox" ID="MediaReceived" VALUE="1" <cfif qTourPro.MediaReceived eq 1>checked</cfif> <cfif qTourPro.isPhotoTour eq 0>disabled</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isVideoTour eq 0><font color="#000000#">Video</font><cfelse>Video</cfif><BR>
          <INPUT NAME="VideoMediaReceived" TYPE="checkbox" ID="VideoMediaReceived" VALUE="1" <cfif qTourPro.VideoMediaReceived eq 1>checked</cfif> <cfif qTourPro.isVideoTour eq 0>disabled</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isPhotoTour eq 0><font color="#000000#">Photos</font><cfelse>Photos</cfif><BR>
          <INPUT NAME="MediaEdited" TYPE="checkbox" ID="MediaEdited" VALUE=1 <cfif qTourPro.edited eq 1>checked</cfif> <cfif qTourPro.isPhotoTour eq 0>disabled</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isVideoTour eq 0><font color="#000000#">Video</font><cfelse>Video</cfif><BR>
          <INPUT NAME="VideoEdited" TYPE="checkbox" ID="VideoEdited" VALUE=1 <cfif qTourPro.VideoEdited eq 1>checked</cfif> <cfif qTourPro.isVideoTour eq 0>disabled</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <INPUT NAME="Realtorcom" TYPE="checkbox" ID="radio10" VALUE=1 <cfif qTourPro.Realtorcom eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <INPUT NAME="mls" TYPE="checkbox" ID="radio11" VALUE=1 <cfif  qTourPro.mls eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isPhotoTour eq 0><font color="#000000#">Photos</font><cfelse>Photos</cfif><BR>
          <INPUT NAME="finalized" TYPE="checkbox" ID="finalized" VALUE=1 <cfif qTourPro.finalized eq 1 and qTourPro.isPhotoTour eq 1>checked</cfif> <cfif qTourPro.isPhotoTour eq 0>disabled</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isVideoTour eq 0><font color="#000000#">Video</font><cfelse>Video</cfif><BR>
          <INPUT NAME="VideoFinalized" TYPE="checkbox" ID="VideoFinalized" VALUE=1 <cfif qTourPro.VideoFinalized>checked</cfif> <cfif qTourPro.isVideoTour eq 0>disabled</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <INPUT NAME="Billing" TYPE="checkbox" ID="Billing" VALUE=1 <cfif qTourPro.billing eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <INPUT NAME="follow_up" TYPE="checkbox" ONCLICK="if(this.checked){document.getElementById('shootNotes').value = document.getElementById('shootNotes').value+'\r\nFollowed up on: '+todayDate; document.getElementById('save').click();}else{document.getElementById('save').click();}" VALUE="1" <cfif qTourPro.follow_up eq 1>checked</cfif>>
        </TD>
      </TR>
    </TABLE></DIV></TD>
  </TR>
  <TR>
    <TD>
    <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
      <TR>
        <TD WIDTH="28%"><DIV ALIGN="center">Broker Billed:<SPAN  class="style1"><CFIF qTours.brokerbilled neq ''>#dollarFormat(round(qTours.brokerbilled))#</CFIF></SPAN></DIV></TD>
        <TD WIDTH="28%"><DIV ALIGN="center">Code Used:<SPAN  class="style1">#qTours.codestr# ($#qTours.codeval#)</SPAN></DIV></TD>
          <TD WIDTH="46%"><DIV ALIGN="center">Tour Order Date:<SPAN  class="style1">#qTours.dateord#</SPAN></DIV></TD>
          <TD WIDTH="26%"><DIV ALIGN="center">Tour ID:<SPAN  class="style1">#qTours.tourID#</SPAN></DIV></TD>
        </TR>
    </TABLE></TD></TR>
  <TR>
    <TD valign="top"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
    	<TR>
    		<TD width="40%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="2">
    			<TR>
    				<TD WIDTH="40%"><BR>
    					<font size="+1">Customer Name:</font></TD>
    				<TD WIDTH="60%" VALIGN="bottom"><BR>
    					<SPAN CLASS="style1"><a href="/admin/users/users.cfm?pg=editUser&user=#qUser.userID#" ><font size="+1">#qUser.firstName# #qUser.lastname#</font></a></SPAN></TD>
    			</TR>
				<TR>
    				<TD>
    					Agent Notes:</TD>
    				<TD VALIGN="bottom"><BR>
    					<SPAN CLASS="style1">#qUser.agentNotes#</SPAN></TD>
    				</TR>
    			<TR>
    				<TD>Contact Number:</TD>
    				<TD><SPAN CLASS="style1">#qUser.phone#</SPAN></TD>
    				</TR>
				<cfif len(trim(qUser.assistName)) GT 0>
					<TR>
    					<TD>Assistant Name:</TD>
    					<TD><SPAN CLASS="style1">#qUser.assistName#</SPAN></TD>
    				</TR>
					<TR>
    					<TD>Assistant Phone:</TD>
    					<TD><SPAN CLASS="style1">#qUser.assistPhone#</SPAN></TD>
    				</TR>
				</cfif>
    			<TR>
    				<TD>Email:</TD>
    				<TD><SPAN CLASS="style1">#qUser.email#</SPAN></TD>
    				</TR>
    			<TR>
    				<TD>Brokerage:</TD>
    				<TD><SPAN CLASS="style1">#qUser.brokerageName#
    					<CFIF len(trim(qUser.BrokerageDesc)) gt 0>
    						- #qUser.BrokerageDesc#
    						</CFIF>
    					</SPAN></TD>
    				</TR>
					<cfif len(trim(qUser.brokerSPhone)) GT 0>
					<TR>
    					<TD>Brokerage Schedule Contact:</TD>
    					<TD><SPAN CLASS="style1">#qUser.brokerSPhone#</SPAN></TD>
    				</TR>
					</cfif>
    			</TABLE></TD>
    		<TD width="40%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="2">
    			<TR>
    				<TD WIDTH="34%"><DIV ALIGN="right">Schedule Attempt</DIV></TD>
    				<TD WIDTH="66%"><INPUT NAME="ScheduleAttemptedon" TYPE="text" ID="ScheduleAttemptedon" VALUE="#qTourPro.ScheduleAttemptedon#" />
    					&nbsp;</TD>
    				</TR>
    			<TR>
    				<TD VALIGN="top"><DIV ALIGN="right">Contact Notes:</DIV></TD>
    				<TD><TEXTAREA NAME="contactNotes" ID="contactNotes" COLS="40" ROWS="5">#qTourPro.contactNotes#</TEXTAREA></TD>
    				</TR>
    			</TABLE></TD>
    		</TR>
    </TABLE></TD>
  </TR>
  <TR>
    <TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
      <TR>
        <TD WIDTH="40%"><BR><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
          <TR>
            <TD WIDTH="38%">Tour Type:</TD>
            <TD WIDTH="62%"><SPAN CLASS="style1">#qTours.tourTypeN#</SPAN></TD>
          </TR>
          <TR>
            <TD>Tour Address:</TD>
            <TD><SPAN CLASS="style1"><A HREF="http://maps.google.com/maps?q=#qTours.address#,#qTours.city# #qTours.state# #qTours.zipCode#" TARGET="_blank">#qTours.address#<cfif qTours.unitNumber neq ""> Unit:#qTours.unitNumber#</cfif><BR>#qTours.city# #qTours.state# #qTours.zipCode#</A></SPAN></TD>
          </TR>
          <TR>
            <TD>Tour Name:</TD>
            <TD><SPAN CLASS="style1">#qTours.title#</SPAN></TD>
          </TR>
          <TR>
            <TD><BR>MLS Number:</TD>
            <TD><BR><SPAN CLASS="style1">#qTours.mlsn#</SPAN></TD>
          </TR>
          <TR>
            <TD>List Price:</TD>
            <TD><SPAN CLASS="style1">#NumberFormat(qTours.listPrice, '_$___,')#</SPAN></TD>
          </TR>
        </TABLE></TD>
        <TD WIDTH="40%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
          <TR>
            <TD WIDTH="34%"><DIV ALIGN="right">Occupied:</DIV></TD>
            <TD WIDTH="66%"><INPUT NAME="housestatus1" TYPE="checkbox" ID="radio" VALUE="1" <cfif qTourPro.housestatus1 eq 1>checked</cfif>></TD>
          </TR>
          <TR>
            <TD><DIV ALIGN="right">Vacant:</DIV></TD>
            <TD><INPUT TYPE="checkbox" NAME="housestatus2" ID="radio2" VALUE="1" <cfif qTourPro.housestatus2 eq 1>checked</cfif>></TD>
          </TR>
          <TR>
            <TD><DIV ALIGN="right">Key Boxed:</DIV></TD>
            <TD><INPUT TYPE="checkbox" NAME="housestatus3" ID="radio3" VALUE="1" <cfif qTourPro.housestatus3 eq 1>checked</cfif>></TD>
          </TR>
          <TR>
            <TD><DIV ALIGN="right">Lock Box:</DIV></TD>
            <TD><INPUT TYPE="checkbox" NAME="housestatus4" ID="radio4" VALUE="1" <cfif qTourPro.housestatus4 eq 1>checked</cfif>>
            <INPUT TYPE="text" NAME="locknum" ID="locknum" VALUE="#qTourPro.locknum#"></TD>
          </TR>
        </TABLE></TD>
      </TR>
    </TABLE></TD>
  </TR>
  <TR>
    <TD><TABLE WIDTH="40%" BORDER="0" CELLSPACING="0" CELLPADDING="2">
      <TR>
        <TD HEIGHT="33">Bedrooms:<SPAN CLASS="style1">#qTours.bedrooms#</SPAN></TD>
        <TD>Bathrooms:<SPAN CLASS="style1">#qTours.bathrooms#</SPAN></TD>
        <TD>Sq. Ft.:<SPAN CLASS="style1">#qTours.sqFootage#</SPAN></TD>
      </TR>
    </TABLE></TD>
  </TR>
  <TR>
    <TD HEIGHT="69"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
      <TR>
        <TD WIDTH="19%" HEIGHT="50" VALIGN="top">Notes from Agent:</TD>
        <TD WIDTH="81%" colspan="3" VALIGN="top"><SPAN CLASS="style1">#qTours.additionalInstructions#</SPAN></TD>
      </TR>
      <TR>
        <TD VALIGN="top">Additional Products:</TD>
        <TD width="40%"><SPAN CLASS="style1">
        <CFSET showcasemember = false >
        <CFLOOP query="qToursAdditional">
        <CFIF productid eq 30 and unitprice eq 0>
        	 <CFSET showcasemember = true >
        </CFIF>
        - #productname# <CFIF quantity gt 1>(#quantity#)</CFIF> <strong>[#orderedon#]</strong><BR>
        </CFLOOP></SPAN></TD>
        <TD width="30%">Showcase Member:
          <CFIF showcasemember><IMG WIDTH="18" HEIGHT="18" ALT="Schedule Attemp" SRC="../images/check_mark.png"></CFIF></TD>
        <TD align="right">Paid:
          <INPUT TYPE="checkbox" NAME="paid" ID="action10" VALUE="1"  <cfif qTourPro.paid eq 1>checked</cfif>></TD>
      </TR>
    </TABLE></TD>
  </TR>
  <TR>
    <TD>
        <!---<DIV style="border-style: solid;border-color: ##dddddd; border-width:thick">--->
            <TABLE WIDTH="840" BORDER="0" CELLSPACING="0" CELLPADDING="0">
                <TR>
                    <TD style="-webkit-print-color-adjust:exact;" BGCOLOR="##dddddd">Scheduled:</TD>
                </TR>
            </TABLE>
        <!---</DIV>--->
    <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
      <TR>
        <TD><INPUT type="checkbox" NAME="CopyPhotoInfo" ID="CopyPhotoInfo" onClick="CopyPicToVideoPhotographer(this)" value=1 <cfif qTourPro.isVideoTour eq 0>disabled<cfelseif qTourPro.CopyPhotoInfo eq 1>checked</cfif>>Copy photographer information to Video Schedule</TD>
        <TD>&nbsp;</TD>
      </TR>
      <TR>
        <TD width="50%">
        	<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">
            	<TR>
               		<TD>Photography Scheduled Date:&nbsp;&nbsp;<input type="text" value="#qTourPro.Scheduledon#"/></TD>
              	</TR>
              	<TR>
                	<TD>Photographer:&nbsp;&nbsp;
                    	<CFLOOP query="qPhotographers">
                          	<CFIF qPhotographers.photographerID eq qTourPro.photographer>
                            	<input type="text" value="#qPhotographers.fullname#  - #qPhotographers.state#"/>
                            </CFIF> 
                        </CFLOOP>
                    </TD>
              	</TR>
                <TR>
              		<TD VALIGN="top">Notes:
                          <TEXTAREA NAME="ScheduledNotes" ID="ScheduledNotes" COLS="62" ROWS="3">#qTourPro.ScheduledNotes#</TEXTAREA></TD>
              	</TR>
                <TR>
                    <TD>Reschedule Date:&nbsp;&nbsp;<input type="text" value="#qTourPro.ReScheduledon#"/></TD>
              	</TR>
                <TR>
                    <TD>Photographer:&nbsp;&nbsp;
                        <CFLOOP query="qPhotographers">
                            <CFIF qPhotographers.photographerID eq qTourPro.RePhotographer>
                            	<input type="text" value="#qPhotographers.fullname#  - #qPhotographers.state#"/> 
                            </CFIF>
                        </CFLOOP>
          			</TD>
                </TR>
            </TABLE>
        </TD>
        <TD width="50%" valign="top" align="right">
        	<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">
            	<TR>
               		<TD>Video Scheduled Date:&nbsp;&nbsp;<input type="text" value="#qTourPro.VideoScheduledOn#" /></TD>
              	</TR>
              	<TR>
                	<TD>Photographer:&nbsp;&nbsp;
                    	<CFLOOP query="qPhotographers">
                          	<CFIF qPhotographers.photographerID eq qTourPro.VideoPhotographer>
                            	<input type="text" value="#qPhotographers.fullname#  - #qPhotographers.state#"/> 
                            </CFIF>
                        </CFLOOP>
                    </TD>
              	</TR>
                <TR>
              		<TD VALIGN="top">Notes:
                          <TEXTAREA NAME="VideoScheduledNotes" ID="VideoScheduledNotes" COLS="62" ROWS="3" <cfif qTourPro.isVideoTour eq 0>disabled</cfif>>#qTourPro.VideoScheduledNotes#</TEXTAREA></TD>
              	</TR>
                <TR>
                    <TD>Reschedule Date:&nbsp;&nbsp;<input type="text" value="#qTourPro.VideoReScheduledOn#"/></TD>
              	</TR>
                <TR>
                    <TD>Photographer:&nbsp;&nbsp;
                        <CFLOOP query="qPhotographers">
                             <CFIF qPhotographers.photographerID eq qTourPro.VideoRePhotographer>
                           		<input type="text" value="#qPhotographers.fullname#  - #qPhotographers.state#"/> 
                             </CFIF>
                        </CFLOOP>
          			</TD>
                </TR>
            </TABLE>
        </TD>
      </TR>
    </TABLE>
  <TR>
  	<TD><BR>
        <!---<DIV style="border-style: solid;border-color: ##dddddd; border-width:thick">--->
            <TABLE WIDTH="840" BORDER="0" CELLSPACING="0" CELLPADDING="0">
                <TR>
                    <TD style="-webkit-print-color-adjust:exact;" BGCOLOR="##dddddd">Media Received:</TD>
                </TR>
            </TABLE>
        <!---</DIV>--->

      <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
	  <TR>
      	<TD valign="top" WIDTH="48%">
			<CFIF qTourPro.ReScheduledon eq "">
                <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
                  <TR>
                    <TD>Photos Received  Date:&nbsp;&nbsp;#qTourPro.MediaReceivedon#
                    </TD>
                  </TR>
                  <TR>
                    <TD HEIGHT="34">Photos Received By :&nbsp;&nbsp;#qTourPro.mediaphotographer#
                    </TD>
                  </TR>
                  <TR>
                    <TD>## of Photos Received:&nbsp;&nbsp;#qTourPro.numreceived#
                    </TD>
                  </TR>
                </TABLE>
          	<CFELSE>
            	<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
                  <TR>
                    <TD>Photos Re-Received  Date:&nbsp;&nbsp;#qTourPro.MediaReReceivedOn#
                    </TD>
                  </TR>
                  <TR>
                    <TD HEIGHT="34">Photos Re-Received By :&nbsp;&nbsp;
                        <CFLOOP query="qEditors">
							<CFIF qEditors.id eq qTourPro.MediaRePhotographer>
                                #qEditors.fullname#
                            </CFIF>
                        </CFLOOP>
                    </TD>
                  </TR>
                  <TR>
                    <TD>## of Photos Re-Received:&nbsp;&nbsp;#qTourPro.NumReReceived#
                    </TD>
                  </TR>
                </TABLE>
            </CFIF>
        </TD>
      	<TD WIDTH="48%" valign="top">
        	<CFIF qTourPro.VideoReScheduledOn eq "">
                <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
                  <TR>
                    <TD>Video Received  Date:&nbsp;&nbsp;#qTourPro.VideoMediaReceivedOn#
                    </TD>
                  </TR>
                  <TR>
                    <TD HEIGHT="34">Video Received By :&nbsp;&nbsp;
                        <CFLOOP query="qEditors">
                            <CFIF qEditors.id eq qTourPro.VideoMediaPhotographer>
                                #qEditors.fullname#
                            </CFIF>
                        </CFLOOP>
                    </TD>
                  </TR>
                  <TR>
                    <TD>## of Videos Received:&nbsp;&nbsp;#qTourPro.VideoNumReceived#
                    </TD>
                  </TR>
                </TABLE>
            <CFELSE>
            	<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
                    <TR>
                        <TD>Video Re-Received  Date:&nbsp;&nbsp;#qTourPro.VideoMediaReReceivedOn#
                        </TD>
                    </TR>
                    <TR>
                        <TD HEIGHT="34">Video Re-Received By :&nbsp;&nbsp;
                            <CFLOOP query="qEditors">
                                <CFIF qEditors.id eq qTourPro.VideoMediaRePhotographer>
                                	#qEditors.fullname#
                                </CFIF>
                            </CFLOOP>
                            </SELECT>
                        </TD>
                    </TR>
                    <TR>
                        <TD>## of Video Re-Received:&nbsp;&nbsp;#qTourPro.VideoNumReReceived#
                        </TD>
                    </TR>
                </TABLE>
            </CFIF>
        </TD>
        <TD VALIGN="top">Notes:
          <TEXTAREA NAME="mediareceivedNotes" ID="mediareceivedNotes" COLS="40" ROWS="3">#qTourPro.MediareceivedNotes#</TEXTAREA></TD>
      </TR>
    </TABLE></TD>
  </TR>
  <TR>
  	<TD><BR />
        <!---<DIV style="border-style: solid;border-color: ##dddddd; border-width:thick">--->
            <TABLE WIDTH="840" BORDER="0" CELLSPACING="0" CELLPADDING="0">
                <TR>
                    <TD style="-webkit-print-color-adjust:exact;" BGCOLOR="##dddddd">Edited:</TD>
                </TR>
            </TABLE>
        <!---</DIV>--->
    <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
    	<tr>
        	<td valign="top" width="48%">
            	<CFIF qTourPro.ReScheduledon eq "">
                    <TABLE WIDTH="100%" BORDER="0"  CELLSPACING="6" CELLPADDING="0">
                        <tr>
                            <td>Photo Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <em style="color:black; font-size:12px; font-weight:normal !important;">start time</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <em style="color:black; font-size:12px; font-weight:normal !important;">finish time</em>
                                <BR>Date/Time:&nbsp;
                                #qTourPro.e_start#
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                #qTourPro.e_on#
                            </td>
                        </tr>
                        <tr>
                            <td>Photos Edited By:&nbsp;&nbsp; 
                                <CFLOOP query="qEditors">
                                    <CFIF qEditors.id eq qTourPro.editphotographer>
                                    	#qEditors.fullname#
                                    </CFIF>
                                </CFLOOP> 
                           </td>
                        </tr>
                        <tr>
                           <td>
                                ## of Photos Edited:&nbsp;&nbsp;#qTourPro.numEdited#
                           </td>
                        </tr>
                    </TABLE>
              	<CFELSE>
                    <TABLE WIDTH="100%" BORDER="0" CELLSPACING="6" CELLPADDING="0">
                        <tr>
                            <td>Photo Re-Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <em style="color:black; font-size:12px; font-weight:normal !important;">start time</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <em style="color:black; font-size:12px; font-weight:normal !important;">finish time</em>
                                <BR>Date/Time:&nbsp;
                                #qTourPro.re_start#
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                #qTourPro.re_on#
                            </td>
                        </tr>
                        <tr>
                            <td>Photos Re-Edited By:&nbsp;&nbsp;
                                <CFLOOP query="qEditors">
                                    <CFIF qEditors.id eq qTourPro.EditRePhotographer>
                                    	#qEditors.fullname#
                                    </CFIF>
                                </CFLOOP>
                           </td>
                        </tr>
                        <tr>
                           <td>
                                ## of Photos Re-Edited:&nbsp;&nbsp;#qTourPro.NumReEdited#
                           </td>
                        </tr>
                    </TABLE>
                </CFIF>
            </td> 
            <td width="48%" valign="top">
            	<CFIF qTourPro.VideoReScheduledOn eq "">
                    <TABLE WIDTH="100%" BORDER="0" CELLSPACING="6" CELLPADDING="0">
                        <tr>
                            <td>Video Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <em style="color:black; font-size:12px; font-weight:normal !important;">start time</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <em style="color:black; font-size:12px; font-weight:normal !important;">finish time</em>
                                <BR>Date/Time:&nbsp;
                                #qTourPro.ve_start#
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                #qTourPro.ve_on#
                            </td>
                        </tr>
                        <tr>
                            <td>Video Edited By:&nbsp;&nbsp; 
                                <CFLOOP query="qEditors">
                                    <CFIF qEditors.id eq qTourPro.VideoEditPhotographer>
                                    	#qEditors.fullname#
                                    </CFIF>
                                </CFLOOP>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                ## of Video Edited:&nbsp;&nbsp;#qTourPro.VideoNumEdited#
                            </td>
                        </tr>
                    </TABLE>
              	<CFELSE>
                    <TABLE WIDTH="100%" BORDER="0" CELLSPACING="6" CELLPADDING="0">
                        <TR>
                            <td>Video Re-Edit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <em style="color:black; font-size:12px; font-weight:normal !important;">start time</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <em style="color:black; font-size:12px; font-weight:normal !important;">finish time</em>
                                <BR>Date/Time:&nbsp;
                                #qTourPro.vre_start#
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                #qTourPro.vre_on#
                            </td>
                            </tr>
                            <tr>
                            <td>Video Re-Edited By:&nbsp;&nbsp
                                <CFLOOP query="qEditors">
                                    <CFIF qEditors.id eq qTourPro.VideoEditRePhotographer>
                                    	#qEditors.fullname#
                                    </CFIF>
                                </CFLOOP>
                            </td>
                            </tr>
                            <tr>
                            <td>
                                ##of Video Re-Edited:&nbsp;&nbsp#qTourPro.VideoNumReEdited#
                            </td>
                     	</TR>
                    </TABLE>
                </CFIF>
            </td>  
            <td>
            	Notes:<BR>
        	  	<TEXTAREA NAME="editedNotes" ID="editedNotes" COLS="40" ROWS="4">#qTourPro.EditedNoted#</TEXTAREA>
            </td>
          </tr>
        </TABLE>
        </TD>
    </TR>
    <TR>
    	<TD><BR />
            <!---<DIV style="border-style: solid;border-color: ##dddddd; border-width:thick">--->
                <TABLE WIDTH="840" BORDER="0" CELLSPACING="0" CELLPADDING="0">
                    <TR>
                        <TD style="-webkit-print-color-adjust:exact;" BGCOLOR="##dddddd">Billed:</TD>
                    </TR>
                </TABLE>
            <!---</DIV>--->
            <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
 			<tr>
          	  <td valign="top"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="5" CELLPADDING="0">
              	<tr>
                	<td width="50%">Invoice Sent:&nbsp;&nbsp
              			<INPUT TYPE="checkbox" NAME="invoiceSent" ID="invoiceSent" VALUE="1"  <cfif qTourPro.invoiceSent eq 1>checked</cfif>>
                    </td>
	          	</tr>
		        <TR>
        	      	<td>Paid by Credit Card:&nbsp;&nbsp#qTourPro.paidbycreditcard# </td>
                    
              		<td>Invoice Generated:&nbsp;&nbsp#qTourPro.InvoiceGenerated# </td>
                </TR>
                <tr>
                    <td>
                    	Photographer Paid:&nbsp;&nbsp#qTourPro.PhotographerPaid#
                    </td>
                </tr>
                <tr>
                    <td>Sales Rep:&nbsp;&nbsp;#salerep#</td>
                </tr>
                <tr>
                    <td colspan="2">
                    	<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
                            <tr>
                                <td width="117px" valign="middle">Post Shoot Notes:</td>
                                <td><TEXTAREA NAME="shootNotes" ID="shootNotes" COLS="45" ROWS="5">#qTourPro.shootNotes#</TEXTAREA></td>
                            </tr>
                        </TABLE>
                    </td>
                </tr>
              </TABLE>
              </td>
              <TD>
              	Cost Breakdown:
                  <div style="width: 100%; height: 156px; border: 1px solid black; overflow: scroll; font-size: 10px;" >
                    <CFSET currentorderid = -1 >
                    <CFSET startRow = true >
                    <CFLOOP query="qTourOrders">
                    	<CFIF startRow>
                        	<CFSET startRow = false>
                        <CFELSE>
							--------------------------------------<br />
                        </CFIF>
                        Order ID: #qTourOrders.orderID# <br />
                        <CFQUERY name="qTourOrderDetails" datasource="#request.db.dsn#">
                            SELECT 
                            od.quantity, od.unitPrice,
                            tt.tourTypeName,
                            p.productName
                            FROM orderdetails od 
                            LEFT JOIN products p ON od.productID = p.productID AND od.type = "product"
                            LEFT JOIN tourTypes tt ON od.productID = tt.tourTypeID AND od.type = "tour"
                            WHERE od.orderID = <cfqueryparam value="#qTourOrders.orderID#" cfsqltype="cf_sql_integer" />
                        </CFQUERY>
						<CFLOOP query="qTourOrderDetails">
							#qTourOrderDetails.tourTypeName##qTourOrderDetails.productName# - #qTourOrderDetails.quantity# @ #qTourOrderDetails.unitPrice#<br />
                        </CFLOOP>
                        Subtotal: #qTourOrders.subTotal#<br />
                        Sales Tax: #qTourOrders.salesTax#<br />
                        Coupon: #qTourOrders.coupon#<br />
                        Coupon Total: #qTourOrders.coupon_total#<br />
                        Trans Total: #qTourOrders.total#<br />
                        Broker Total: #qTourOrders.broker_total#<br />
                    </CFLOOP>
                  </div>
              </td>
          </tr>
      </table>
    </TD>
  </TR>
</TABLE>
</FORM>
</CFOUTPUT>
</BODY>
</HTML>
</cfcontent>
