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
	select t.*
    from tourprogress t
	where t.tourid =<cfqueryparam cfsqltype="cf_sql_int" value="#url.tour#">
</CFQUERY>

<CFQUERY name="qPhotographers" datasource="#request.db.dsn#">
	select p.*
    from photographers p
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

<HTML>
<HEAD>
<TITLE>Tour Sheet</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
<LINK HREF="../../repository_css/jquery-ui-1.8.16.custom.css" REL="stylesheet" TYPE="text/css">
<SCRIPT SRC="../../repository_inc/jquery-1.6.2.min.js"></SCRIPT>
<SCRIPT SRC="../../repository_inc/jquery-ui-1.8.16.custom.min.js" TYPE="text/javascript"></SCRIPT>
<SCRIPT SRC="../../repository_inc/jquery-ui-timepicker-addon.js" TYPE="text/javascript"></SCRIPT>
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<style>
	/* css for timepicker */
	.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
	.ui-timepicker-div dl { text-align: left; }
	.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
	.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
	.ui-timepicker-div td { font-size: 90%; }
	.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
</style>
	<SCRIPT TYPE="text/javascript">
	/* <![CDATA[ */
		$(function() {
			$("#save").click(function(event){
				validate(event);
			});
			function validate(event){
				// See if edited is checked
				var editedRadio = $("#radio8");
				if(editedRadio.attr("checked")!="undefined"&&editedRadio.attr("checked") == "checked"){
					// Edited is checked now see if the start time and finish time is set
					if(!$('input[name=edited_start]').val()){
						alert("Please enter the start time for editing!");
						event.preventDefault();
					}
					if(!$('input[name=Editedon]').val()){
						alert("Please enter the end time for editing!");
						event.preventDefault();
					}
				}
			}
/*
			$('#ScheduleAttemptedon').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});  										
			$('#Scheduledon').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			}); 
				  					
									
			$('#ReScheduledon').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			}); 
				  					
			$('#MediaReceivedon').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			}); 
								
			$('#Editedon').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			}); 			
								
			$('#InvoiceGenerated').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			}); 			
				
			$('#PhotographerPaid').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			}); 
			$('#paidbycreditcard').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#edited_start').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#MediaReReceivedOn').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#ReEditedStart').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#ReEditedOn').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#VideoScheduledOn').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#VideoReScheduledOn').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#VideoMediaReceivedOn').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#VideoMediaReReceivedOn').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#VideoEditedOn').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#VideoEditedStart').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#VideoReEditedOn').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
			$('#VideoReEditedStart').datetimepicker({
				showSecond: true,
				dateFormat: 'yy-mm-dd',
				timeFormat: 'hh:mm:ss'
			});
*/
		});
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1;
		var yyyy = today.getFullYear();
		var hours = today.getHours();
		var minute = today.getMinutes();
		var period = "AM";
		if (hours > 12) {
			period = "PM"
		}else{
			period = "AM";
		}
		hours = ((hours > 12) ? hours - 12 : hours);
		var time = hours + ":" + minute + " " + period
		if(dd<10){dd='0'+dd}
		if(mm<10){mm='0'+mm}
		var todayDate = mm+'/'+dd+'/'+yyyy+' '+time;
		function cancelSchedule(video){
			var startIt = "";
			if (video) {
				var cancelType = "cancelVideoProgress";
				$("#VideoScheduledOn").val("");
				if($("#VideoScheduledNotes").val()){
					startIt = "\n";
				}
				$("#VideoScheduledNotes").val($("#VideoScheduledNotes").val()+startIt+"Video shoot schedule canceled on: "+todayDate);
			}
			else {
				var cancelType = "cancelPhotoProgress";
				$("#Scheduledon").val("");
				if($("#ScheduledNotes").val()){
					startIt = "\n";
				}
				$("#ScheduledNotes").val($("#ScheduledNotes").val()+startIt+"Photo shoot schedule canceled on: "+todayDate);
			}
			$('input:radio[name=stage]:nth(1)').click();
			
			SaveSubmit(0, cancelType, $("#tourId").html() );
		}

		function SaveSubmit(doSubmit, cancelType, tourId ) {
			var saveType = document.getElementById("saveType");
			var action = "?action=updateprogress";
			
			if (doSubmit == 1)
				saveType.value = "true";
			else
				saveType.value = "false";
			
			if (cancelType != "updateProgress") 
				action = "?action=" + cancelType;
				
            // Send a notification to our S3 script so that it 
            // can upload any changes to this tour ID
            //===================================================
            $.ajax({
                url: "/repository_queries/admin_s3_update_tour.php?add=1&tourId=" + $("#tourId").html()
            }).always(function(){
		        document.getElementById("toursheet").action=action;
			    document.getElementById("toursheet").submit();
            });
		}
		
		function CopyPicToVideoPhotographer(checkbox) {
			if (checkbox.checked == true) {
				var p = document.getElementById("photographer");
				
				var vsd = document.getElementById("VideoScheduledOn");
				var vp = document.getElementById("VideoPhotographer");
				
				vsd.value = document.getElementById("Scheduledon").value;

				for(var i, j = 0; i = vp.options[j]; j++) {
					if(i.value == p.value) {
						vp.selectedIndex = j;
						break;
					}
				}
			}
		}
		
		function printWin(url) {
			var docprint=window.open(url, "Tour Sheet");
			docprint.print();
			//docprint.close();
			//print(document); 
		} 

		function openPopup(url, x, y) {
			try {
				window.open(url,'Preview',"location=0,status=0,scrollbars=0, width=" + x + ",height=" + y);
			} catch(err) {
				alert("openPopup: " + err);
			}
		}
	/* ]]> */
	</SCRIPT>
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
<CFIF len(errorMsg)>
	<FONT COLOR="##FF0000">#errorMsg#</FONT>
</CFIF>

<CFIF len(msg)>
	<FONT COLOR="##009900">#msg#</FONT>
</CFIF>
<FORM ID="toursheet"   method="post" ACTION="?action=updateprogress" >
<INPUT TYPE="hidden" NAME="tour"  value="#url.tour#">
<INPUT TYPE="hidden" NAME="user"  value="#url.user#">
<TABLE WIDTH="840" BORDER="0" CELLSPACING="0" CELLPADDING="0">
  	<TR>
        <TD><INPUT TYPE="button" NAME="save" ID="save" VALUE="Save" onClick="SaveSubmit(0, 'updateprogress')">
          <INPUT TYPE="button" NAME="savenotify" ID="savenotify" VALUE="Save &amp; Submit" onClick="SaveSubmit(1, 'updateprogress')">
          <INPUT TYPE="hidden" NAME="saveType" ID="saveType" VALUE=0>
	          <A HREF="users.cfm?pg=toursheetprint&tour=#url.tour#&user=#url.user#">Print</A>
              (Note:'Save &amp; Submit' sends notifications)
        </TD>
     </TR>
  <TR>
    <TD><TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" BGCOLOR="#color#">
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
        <TD align="center" style="text-align: center; cursor: pointer; color: blue;" onClick="openPopup('../admin_mls_hist.php?tourid=#qTours.tourID#', 800, 500)">
        	Realtor.com</TD>
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
        <INPUT NAME="stageOLD" TYPE="hidden" VALUE="#qTourPro.stage#"/>
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
          <INPUT NAME="MediaReceived" TYPE="checkbox" ID="MediaReceived" VALUE="1" <cfif qTourPro.MediaReceived eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isVideoTour eq 0><font color="#000000#">Video</font><cfelse>Video</cfif><BR>
          <INPUT NAME="VideoMediaReceived" TYPE="checkbox" ID="VideoMediaReceived" VALUE="1" <cfif qTourPro.VideoMediaReceived eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isPhotoTour eq 0><font color="#000000#">Photos</font><cfelse>Photos</cfif><BR>
          <INPUT NAME="MediaEdited" TYPE="checkbox" ID="MediaEdited" VALUE=1 <cfif qTourPro.edited eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isVideoTour eq 0><font color="#000000#">Video</font><cfelse>Video</cfif><BR>
          <INPUT NAME="VideoEdited" TYPE="checkbox" ID="VideoEdited" VALUE=1 <cfif qTourPro.VideoEdited eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <INPUT NAME="Realtorcom" TYPE="checkbox" ID="radio10" VALUE=1 <cfif qTourPro.Realtorcom eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <INPUT NAME="mls" TYPE="checkbox" ID="radio11" VALUE=1 <cfif  qTourPro.mls eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isPhotoTour eq 0><font color="#000000#">Photos</font><cfelse>Photos</cfif><BR>
          <INPUT NAME="finalized" TYPE="checkbox" ID="finalized" VALUE=1 <cfif qTourPro.finalized eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <cfif qTourPro.isVideoTour eq 0><font color="#000000#">Video</font><cfelse>Video</cfif><BR>
          <INPUT NAME="VideoFinalized" TYPE="checkbox" ID="VideoFinalized" VALUE=1 <cfif qTourPro.VideoFinalized>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <INPUT NAME="Billing" TYPE="checkbox" ID="Billing" VALUE=1 <cfif qTourPro.billing eq 1>checked</cfif>>
        </TD>
        <TD align="center" valign="bottom">
          <INPUT NAME="follow_up" TYPE="checkbox" ONCLICK="if(this.checked){document.getElementById('shootNotes').value = document.getElementById('shootNotes').value+'\r\nFollowed up on: '+todayDate; document.getElementById('save').click();}else{document.getElementById('save').click();}" VALUE="1" <cfif qTourPro.follow_up eq 1>checked</cfif>>
        </TD>
      </TR>
    </TABLE></TD>
  </TR>
  <TR>
    <TD>
    <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
      <TR>
        <TD WIDTH="28%"><DIV ALIGN="center">Broker Billed:<SPAN  class="style1"><CFIF qTours.brokerbilled neq ''>#dollarFormat(round(qTours.brokerbilled))#</CFIF></SPAN></DIV></TD>
        <TD WIDTH="28%"><DIV ALIGN="center">Code Used:<SPAN  class="style1">#qTours.codestr# ($#qTours.codeval#)</SPAN></DIV></TD>
          <TD WIDTH="46%"><DIV ALIGN="center">Tour Order Date:<SPAN  class="style1">#qTours.dateord#</SPAN></DIV></TD>
          <TD WIDTH="26%"><DIV ALIGN="center">Tour ID:<SPAN id='tourId' class="style1">#qTours.tourID#</SPAN></DIV></TD>
        </TR>
    </TABLE></TD></TR>
  <TR>
    <TD valign="top"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
    	<TR>
    		<TD width="40%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="2">
    			<TR>
    				
                    <TD WIDTH="38%"><BR>
    					<font size="+1">Customer Name:</font></TD>
    				<TD WIDTH="62%" VALIGN="bottom"><BR>
    					<SPAN CLASS="style1"><a href="/admin/users/users.cfm?pg=editUser&user=#qUser.userID#" ><font size="+1">#qUser.firstName# #qUser.lastname#</font></a></SPAN></TD>
                        
    			</TR>
				<TR>
    				<TD WIDTH="38%">
    					Agent Notes:</TD>
    				<TD WIDTH="62%" VALIGN="bottom"><BR>
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
    <TD><BR><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
      <TR>
        <TD WIDTH="40%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
          <TR>
            <TD WIDTH="38%">Tour Type:</TD>
            <TD WIDTH="62%"><SPAN CLASS="style1"><a href="javascript:void(0)" onClick="window.open('../tour-type-details.php?tourTypeID=#qTours.tourTypeID#','Tour Type Details','height=400,width=600')">#qTours.tourTypeN#</a></SPAN></TD>
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
        <TD WIDTH="19%" HEIGHT="56" VALIGN="top">Notes from Agent:</TD>
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
            - <a href="javascript:void(0)" onClick="window.open('../product-details.php?productID=#productid#','Product Details','height=400,width=600')">#productname#</a> <CFIF quantity gt 1>(#quantity#)</CFIF> <strong>[#orderedon#]</strong><BR>
        </CFLOOP></SPAN></TD>
        <TD width="30%">Showcase Member:
          <CFIF showcasemember><IMG WIDTH="18" HEIGHT="18" ALT="Schedule Attemp" SRC="../images/check_mark.png"></CFIF></TD>
        <TD align="right">Paid:
          <INPUT TYPE="checkbox" NAME="paid" ID="action10" VALUE="1"  <cfif qTourPro.paid eq 1>checked</cfif>></TD>
      </TR>
      <tr>
      	<td colspan="4"><div style="padding:10px; padding-left:0px; padding-bottom:5px;"><div onClick="javascript:window.open('http://www.spotlighthometours.com/admin/admin_create_youtube.php?tourID=#url.tour#','','width=800,height=400')" style="cursor:pointer;"><strong>CONVERT A SLIDESHOW TO YOUTUBE VIDEO</strong></div></div></td>
      </tr>
    </TABLE></TD>
  </TR>
  <TR>
    <TD><BR>
    <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
      <TR>
        <TD width="50%" BGCOLOR="##dddddd">Scheduled:</TD>
        <TD width="50%" BGCOLOR="##dddddd">&nbsp;</TD>
      </TR>
      <TR>
        <TD><INPUT type="checkbox" NAME="CopyPhotoInfo" ID="CopyPhotoInfo" onClick="CopyPicToVideoPhotographer(this)" value=1 <cfif qTourPro.CopyPhotoInfo eq 1>checked</cfif>>Copy photographer information to Video Schedule</TD>
        <TD>&nbsp;</TD>
      </TR>
      <TR>
        <TD width="50%">
        	<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">
            	<TR>
               		<TD>Photography Scheduled Date:
                	<INPUT NAME="Scheduledon" TYPE="text" ID="Scheduledon" VALUE="#qTourPro.Scheduledon#" /> [ <cfif qTourPro.isPhotoTour eq 1><a href="javascript: cancelSchedule(false)" >cancel</a><cfelse>cancel</cfif> ]</TD>
              	</TR>
              	<TR>
                	<TD>Photographer:
                    	<SELECT NAME="photographer" ID="photographer">
                          <OPTION VALUE="0" >----Not Set----</OPTION>
                          <CFLOOP query="qPhotographers">
                          <OPTION VALUE="#qPhotographers.photographerID#" #IIF(qPhotographers.photographerID eq qTourPro.photographer,DE('selected="true"'),DE(''))#>#qPhotographers.fullname#  - #qPhotographers.state# </OPTION>
                          </CFLOOP>
                        </SELECT></TD>
              	</TR>
                <TR>
              		<TD VALIGN="top">Notes:
                          <TEXTAREA NAME="ScheduledNotes" ID="ScheduledNotes" COLS="62" ROWS="3">#qTourPro.ScheduledNotes#</TEXTAREA></TD>
              	</TR>
                <TR>
                    <TD>Reschedule Date:
                          <INPUT NAME="ReScheduledon" TYPE="text" ID="ReScheduledon" VALUE="#qTourPro.ReScheduledon#" />
              	</TR>
                <TR>
                    <TD>Photographer:
                        <SELECT NAME="rephotographer"> 
                          	<OPTION VALUE="0" >----Not Set----</OPTION>
                            <CFLOOP query="qPhotographers">
                              
                              <OPTION VALUE="#qPhotographers.photographerID#" #IIF(qPhotographers.photographerID eq qTourPro.rephotographer,DE('selected="true"'),DE(''))#>#qPhotographers.fullname#  - #qPhotographers.state# </OPTION>
                            </CFLOOP>
          				</SELECT></TD>
                </TR>
            </TABLE>
        </TD>
        <TD width="50%" align="right">
        	<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">
            	<TR>
               		<TD>Video Scheduled Date:
                	<INPUT NAME="VideoScheduledOn" TYPE="text" ID="VideoScheduledOn" VALUE="#qTourPro.VideoScheduledOn#" /> [ <cfif qTourPro.isVideoTour eq 1><a href="javascript: cancelSchedule(true)" >cancel</a><cfelse>cancel</cfif> ]</TD>
              	</TR>
              	<TR>
                	<TD>Photographer:
                    	<SELECT NAME="VideoPhotographer" ID="VideoPhotographer">
                          <OPTION VALUE="0" >----Not Set----</OPTION>
                          <CFLOOP query="qPhotographers">
                          <OPTION VALUE="#qPhotographers.photographerID#" #IIF(qPhotographers.photographerID eq qTourPro.VideoPhotographer,DE('selected="true"'),DE(''))#>#qPhotographers.fullname#  - #qPhotographers.state# </OPTION>
                          </CFLOOP>
                        </SELECT></TD>
              	</TR>
                <TR>
              		<TD VALIGN="top">Notes:
                          <TEXTAREA NAME="VideoScheduledNotes" ID="VideoScheduledNotes" COLS="62" ROWS="3">#qTourPro.VideoScheduledNotes#</TEXTAREA></TD>
              	</TR>
                <TR>
                    <TD>Reschedule Date:
                          <INPUT NAME="VideoReScheduledOn" TYPE="text" ID="VideoReScheduledOn" VALUE="#qTourPro.VideoReScheduledOn#" />
              	</TR>
                <TR>
                    <TD>Photographer:
                        <SELECT NAME="VideoRePhotographer"> 
                          	<OPTION VALUE="0" >----Not Set----</OPTION>
                            <CFLOOP query="qPhotographers">
                              
                              <OPTION VALUE="#qPhotographers.photographerID#" #IIF(qPhotographers.photographerID eq qTourPro.VideoRePhotographer,DE('selected="true"'),DE(''))#>#qPhotographers.fullname#  - #qPhotographers.state# </OPTION>
                            </CFLOOP>
          				</SELECT></TD>
                </TR>
            </TABLE>
        </TD>
      </TR>
    </TABLE>
  <TR>
  	<TD><BR><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
      <TR>
        <TD BGCOLOR="##dddddd">Media Received:</TD>
        <TD BGCOLOR="##dddddd">&nbsp;</TD>
        <TD BGCOLOR="##dddddd">&nbsp;</TD>
      </TR>
	  <TR>
      	<TD WIDTH="48%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
          <TR>
            <TD>Photos Received  Date:
            	<INPUT NAME="MediaReceivedon" TYPE="text" ID="MediaReceivedon" VALUE="#qTourPro.MediaReceivedon#"/>
            </TD>
          </TR>
          <TR>
            <TD HEIGHT="34">Photos Received By :&nbsp;&nbsp;
            	<INPUT NAME="mediaphotographer" TYPE="text" ID="mediaphotographer" VALUE="#qTourPro.mediaphotographer#" />
       		</TD>
          </TR>
          <TR>
            <TD>## of Photos Received:
            	<INPUT NAME="numReceived" TYPE="text" ID="numReceived" SIZE="5" VALUE="#qTourPro.numreceived#">
            </TD>
          </TR>
		</TABLE></TD>
      	<TD WIDTH="48%" valign="top"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
          <TR>
            <TD>Video Received  Date:
            	<INPUT NAME="VideoMediaReceivedOn" TYPE="text" ID="VideoMediaReceivedOn" VALUE="#qTourPro.VideoMediaReceivedOn#" />
            </TD>
          </TR>
          <TR>
            <TD HEIGHT="34">Video Received By :&nbsp;&nbsp;
            	<SELECT NAME="VideoMediaPhotographer">
                    <OPTION VALUE="0" >----Not Set----</OPTION>
                    <CFLOOP query="qEditors">
                    	<OPTION VALUE="#qEditors.id#" #IIF(qEditors.id eq qTourPro.VideoMediaPhotographer,DE('selected="true"'),DE(''))#>#qEditors.fullname#</OPTION>
                    </CFLOOP>
                </SELECT>
       		</TD>
          </TR>
          <TR>
            <TD>## of Videos Received:
            	<INPUT NAME="VideoNumReceived" TYPE="text" ID="VideoNumReceived" SIZE="5" VALUE="#qTourPro.VideoNumReceived#" />
            </TD>
          </TR>
		</TABLE></TD>
        <TD VALIGN="top">Notes:
          <TEXTAREA NAME="mediareceivedNotes" ID="mediareceivedNotes" COLS="40" ROWS="4">#qTourPro.MediareceivedNotes#</TEXTAREA></TD>
      </TR>
      <CFIF qTourPro.ReScheduledon neq "" or  qTourPro.VideoReScheduledOn neq "">
      	<tr>
        	<TD colspan="2"><HR></TD>
        </tr>
        <tr>    
            <TD WIDTH="50%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
              <TR>
                <TD>Photos Re-Received  Date:
                    <INPUT NAME="MediaReReceivedOn" TYPE="text" ID="MediaReReceivedOn" VALUE="#qTourPro.MediaReReceivedOn#" />
                </TD>
              </TR>
              <TR>
                <TD HEIGHT="34">Photos Re-Received By :&nbsp;&nbsp;
                    <SELECT NAME="MediaRePhotographer">
                        <OPTION VALUE="0" >----Not Set----</OPTION>
                        <CFLOOP query="qEditors">
                        	<OPTION VALUE="#qEditors.id#" #IIF(qEditors.id eq qTourPro.MediaRePhotographer,DE('selected="true"'),DE(''))#>#qEditors.fullname#</OPTION>
                        </CFLOOP>
                    </SELECT>
                </TD>
              </TR>
              <TR>
                <TD>## of Photos Re-Received:
                    <INPUT NAME="NumReReceived" TYPE="text" ID="NumReReceived" SIZE="5" VALUE="#qTourPro.NumReReceived#">
                </TD>
              </TR>
            </TABLE></TD>
            <TD WIDTH="50%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
              	<TR>
                	<TD>Video Re-Received  Date:
                    	<INPUT NAME="VideoMediaReReceivedOn" TYPE="text" ID="VideoMediaReReceivedOn" VALUE="#qTourPro.VideoMediaReReceivedOn#" />
                    </TD>
                    </TR>
                    <TR>
                    <TD HEIGHT="34">Video Re-Received By :&nbsp;&nbsp;
                        <SELECT NAME="VideoMediaRePhotographer">
                            <OPTION VALUE="0" >----Not Set----</OPTION>
                            <CFLOOP query="qEditors">
                                <OPTION VALUE="#qEditors.id#" #IIF(qEditors.id eq qTourPro.VideoMediaRePhotographer,DE('selected="true"'),DE(''))#>#qEditors.fullname#</OPTION>
                            </CFLOOP>
                        </SELECT>
                    </TD>
                    </TR>
                    <TR>
                    <TD>## of Video Re-Received:
                            <INPUT NAME="VideoNumReReceived" TYPE="text" ID="VideoNumReReceived" SIZE="5" VALUE="#qTourPro.VideoNumReReceived#" />
                    </TD>
            	</TR>
            </TABLE></TD>
        </tr>
      </CFIF>
    </TABLE></TD>
  </TR>
  <TR>
  	<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
        <TR>
	        <TD BGCOLOR="##dddddd">Edited:</TD>
    	    <TD BGCOLOR="##dddddd">&nbsp;</TD>
        	<TD BGCOLOR="##dddddd">&nbsp;</TD>
        </TR>
    	<tr>
        	<td width="48%">
            <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
            	<tr>
                	<td>Photo Edit&nbsp;&nbsp;&nbsp;&nbsp;
            	  		<em style="color:black; font-size:12px; font-weight:normal !important;">start time</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<em style="color:black; font-size:12px; font-weight:normal !important;">finish time</em>
                        <BR>Date/Time:&nbsp;
                        <INPUT NAME="edited_start" TYPE="text" ID="edited_start" VALUE="#qTourPro.edited_start#" style="width: 90px;"/>
            			<INPUT NAME="Editedon" TYPE="text" ID="Editedon" VALUE="#qTourPro.Editedon#" style="width: 90px;" />
             
		            </td>
				</tr>
         		<tr>
                    <td>Photos Edited By: 
                    	<SELECT NAME="editphotographer">
                            <OPTION VALUE="0" >----Not Set----</OPTION>
                            <CFLOOP query="qEditors">
                            <OPTION VALUE="#qEditors.id#" #IIF(qEditors.id eq qTourPro.editphotographer,DE('selected="true"'),DE(''))#>#qEditors.fullname#</OPTION>
                            </CFLOOP>
                        </SELECT> 
                        <br><span style="font-size:11px;">[ <cfif qTourPro.isPhotoTour eq 1><a href="/admin/photographer-feedback.php?tourID=#url.tour#&video=0" target="_blank">photographer feedback</a><cfelse>video photographer feedback</cfif> ]</span>
                   </td>
                </tr>
                <tr>
                   <td>
                   		## of Photos Edited:
                   		<INPUT NAME="numEdited" TYPE="text" ID="numEdited" SIZE="5"  value="#qTourPro.numEdited#">
                   </td>
                </tr>
            </TABLE>
            </td> 
            <td width="48%" valign="top">
            <TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
            	<tr>
                	<td>Video Edit&nbsp;&nbsp;&nbsp;&nbsp;
                        <em style="color:black; font-size:12px; font-weight:normal !important;">start time</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<em style="color:black; font-size:12px; font-weight:normal !important;">finish time</em>
                        <br>
            	  		Date/Time:&nbsp;<INPUT NAME="VideoEditedStart" TYPE="text" ID="VideoEditedStart" VALUE="#qTourPro.VideoEditedStart#" style="width: 90px;" />
            			<INPUT NAME="VideoEditedOn" TYPE="text" ID="VideoEditedOn" VALUE="#qTourPro.VideoEditedOn#" style="width: 90px;" />
                	    
		            </td>
				</tr>
         		<tr>
                    <td>Video Edited By: 
                    	<SELECT NAME="VideoEditPhotographer">
                            <OPTION VALUE="0" >----Not Set----</OPTION>
                            <CFLOOP query="qEditors">
                            <OPTION VALUE="#qEditors.id#" #IIF(qEditors.id eq qTourPro.VideoEditPhotographer,DE('selected="true"'),DE(''))#>#qEditors.fullname#</OPTION>
                            </CFLOOP>
                        </SELECT> 
                        <BR><span style="font-size:11px;">[ <a href="/admin/photographer-feedback.php?tourID=#url.tour#&video=1" target="_blank">video photographer feedback</a>]</span>
                   	</td>
                </tr>
                <tr>
                    <td>
                        ## of Video Edited:
                        <INPUT NAME="VideoNumEdited" TYPE="text" ID="VideoNumEdited" SIZE="5"  value="#qTourPro.VideoNumEdited#" />
                    </td>
                </tr>
            </TABLE>
            </td>  
            <td>
            	Notes:<BR>
        	  	<TEXTAREA NAME="editedNotes" ID="editedNotes" COLS="40" ROWS="5">#qTourPro.EditedNoted#</TEXTAREA>
            </td>
          </tr>
      <CFIF qTourPro.ReScheduledon neq "" or  qTourPro.VideoReScheduledOn neq "">
      	<tr>
        	<TD colspan="2"><HR></TD>
        </tr>
        <tr>    
            <TD WIDTH="50%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
            	<tr>
                	<td>Photo Re-Edit&nbsp;&nbsp;&nbsp;&nbsp;
            	  		<em style="color:black; font-size:12px; font-weight:normal !important;">start time</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<em style="color:black; font-size:12px; font-weight:normal !important;">finish time</em>
                        <BR>Date/Time:&nbsp;
                        <INPUT NAME="ReEditedStart" TYPE="text" ID="ReEditedStart" VALUE="#qTourPro.ReEditedStart#" style="width: 90px;" />
            			<INPUT NAME="ReEditedOn" TYPE="text" ID="ReEditedOn" VALUE="#qTourPro.ReEditedOn#" style="width: 90px;" />
		            </td>
				</tr>
         		<tr>
                    <td>Photos Re-Edited By: 
                    	<SELECT NAME="EditRePhotographer">
                            <OPTION VALUE="0" >----Not Set----</OPTION>
                            <CFLOOP query="qEditors">
                            <OPTION VALUE="#qEditors.id#" #IIF(qEditors.id eq qTourPro.EditRePhotographer,DE('selected="true"'),DE(''))#>#qEditors.fullname#</OPTION>
                            </CFLOOP>
                        </SELECT> 
                        <br><span style="font-size:11px;">[ <cfif qTourPro.isPhotoTour eq 1><a href="/admin/photographer-feedback.php?tourID=#url.tour#&video=0" target="_blank">photographer feedback</a><cfelse>video photographer feedback</cfif> ]</span>
                   </td>
                </tr>
                <tr>
                   <td>
                   		## of Photos Re-Edited:
                   		<INPUT NAME="NumReEdited" TYPE="text" ID="NumReEdited" SIZE="5"  value="#qTourPro.NumReEdited#">
                   </td>
                </tr>
            </TABLE></TD>
            <TD WIDTH="50%"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
              	<TR>
                	<td>Video Re-Edit&nbsp;&nbsp;&nbsp;&nbsp;
            	  		<em style="color:black; font-size:12px; font-weight:normal !important;">start time</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<em style="color:black; font-size:12px; font-weight:normal !important;">finish time</em>
                        <BR>Date/Time:&nbsp;
                        <INPUT NAME="VideoReEditedStart" TYPE="text" ID="VideoReEditedStart" VALUE="#qTourPro.VideoReEditedStart#" style="width: 90px;" />
            			<INPUT NAME="VideoReEditedOn" TYPE="text" ID="VideoReEditedOn" VALUE="#qTourPro.VideoReEditedOn#" style="width: 90px;" />
             
		            </td>
                    </tr>
                    <tr>
                    <td>Video Re-Edited By: 
                    	<SELECT NAME="VideoEditRePhotographer">
                            <OPTION VALUE="0" >----Not Set----</OPTION>
                            <CFLOOP query="qEditors">
                            <OPTION VALUE="#qEditors.id#" #IIF(qEditors.id eq qTourPro.VideoEditRePhotographer,DE('selected="true"'),DE(''))#>#qEditors.fullname#</OPTION>
                            </CFLOOP>
                        </SELECT> 
                        <br><span style="font-size:11px;">[ <cfif qTourPro.isVideoTour eq 1><a href="/admin/photographer-feedback.php?tourID=#url.tour#&video=1" target="_blank">video photographer feedback</a><cfelse>video photographer feedback</cfif> ]</span>
                    </td>
                    </tr>
                    <tr>
                    <td>
                   		## of Video Re-Edited:
                   		<INPUT NAME="VideoNumReEdited" TYPE="text" ID="VideoNumReEdited" SIZE="5"  value="#qTourPro.VideoNumReEdited#">
                    </td>
                </TR>
            </TABLE></TD>
        </tr>
      </CFIF>
        </TABLE>
        </TD>
    </TR>
    <TR>
    	<TD><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
        	<TR>
			    <TD WIDTH="67%" BGCOLOR="##dddddd">Billed:</TD>
    			<TD WIDTH="33%" BGCOLOR="##dddddd">&nbsp;</TD>
        	</TR>
			<tr>
          	  <td valign="top"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
              	<tr>
                	<td>Invoice Sent:
              			<INPUT TYPE="checkbox" NAME="invoiceSent" ID="invoiceSent" VALUE="1"  <cfif qTourPro.invoiceSent eq 1>checked</cfif>>
                    </td>
	          	</tr>
		        <TR>
        	      	<td>Paid by Credit Card:
	              		<INPUT NAME="paidbycreditcard" TYPE="text" ID="paidbycreditcard" VALUE="#qTourPro.paidbycreditcard#" />
              			Invoice Generated:
                    	<INPUT NAME="InvoiceGenerated" TYPE="text" ID="InvoiceGenerated" VALUE="#qTourPro.InvoiceGenerated#" />
                    </td>
                </TR>
                <tr>
                    <td>
                    	Photographer Paid:&nbsp;
                    	<INPUT NAME="PhotographerPaid" TYPE="text" ID="PhotographerPaid" VALUE="#qTourPro.PhotographerPaid#" />
                    </td>
                </tr>
                <tr>
                    <td>Sales Rep:&nbsp;&nbsp;#salerep#</td>
                </tr>
                <tr>
                    <td>
                    	<br><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
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
                  <div style="width: 100%; height: 200px; border: 1px solid black; overflow: scroll; font-size: 10px;" >
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
          <tr>
              <td colspan="3" >
                <INPUT TYPE="button" NAME="save" ID="save" VALUE="Save" onClick="SaveSubmit(0, 'updateprogress')">
                <INPUT TYPE="button" NAME="savenotify" ID="savenotify" VALUE="Save &amp; Submit" onClick="SaveSubmit(1, 'updateprogress')">
                <!---<a title="DevPrint" class='printLinks' onClick="printWin('/development/admin/users/users.cfm?pg=toursheetprint&tour=#url.tour#&user=#url.user#')">DevPrint</A>--->
                <A HREF="users.cfm?pg=toursheetprint&tour=#url.tour#&user=#url.user#">Print</A>
                <!---<A HREF="http://www.spotlighthometours.com/admin/users/tours/_toursheetprint.cfm?tour=#url.tour#&user=#url.user#">Print</A>---> (Note:'Save &amp; Submit' sends notifications)
              </td>
          </tr>
      </table>
    </TD>
  </TR>
</TABLE>
</FORM>
<BR>
<BR>
</CFOUTPUT>

</BODY>
</HTML>
</cfcontent>
