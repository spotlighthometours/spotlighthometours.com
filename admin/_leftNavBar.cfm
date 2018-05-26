<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Left Nav Bar</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
<STYLE TYPE="text/css">
<!--
#container {
	height: 100%;
	width: 179px;
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #A2A2A2;
	background-color: #f4f4f4;
	text-align: center;
	padding-top: 15px;
}

body { padding: 0px; }

.divlink {
	font-size: 1em;
	text-align: center;
	color: blue;
	background: none;
	margin: 0;
	padding: 0;
	border: none;
	cursor: pointer;
	text-decoration: underline;
}

-->
</STYLE>

<script language = "javascript">
	var XMLHttpRequestObject = false; // Ajax http request object
	
	// Create the http request object
	if (window.XMLHttpRequest) {
		XMLHttpRequestObject = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// Destroy the PHP session then move on to logout of the CF session.
	function PHP_Logout() {
		try {
			var url = "../repository_inc/login_logic.php";
			var params = "logout=true";
			
			if(XMLHttpRequestObject) {
				XMLHttpRequestObject.open("POST", url, true);
				XMLHttpRequestObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				XMLHttpRequestObject.setRequestHeader("Content-length", params.length);
				XMLHttpRequestObject.setRequestHeader("Connection", "close");

				XMLHttpRequestObject.onreadystatechange = function() { 
					if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
						parent.location = "login.cfm?logout=";
					}
				}
				XMLHttpRequestObject.send(params);
				
			}
		} catch(err) {
			window.alert(err);
		}
	}
	
</script>

<BODY>
<DIV ID="container" style="overflow:auto;">
<DIV STYLE="padding: 0px;">
<DIV><A HREF="main.cfm" TARGET="mainFrame">Admin Home</A></DIV>
<DIV STYLE="background-color:#ddd;"><A HREF="progress/" TARGET="mainFrame">PHOTO/VIDEO QUEUE</A></div>
<DIV><A HREF="photographers/upload-sessions.php" TARGET="mainFrame">[NEW] Photographer Upload Sessions</A></DIV>
<cfif bitand(#session.adminPermissions#, 1)>
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
	    <DIV STYLE="background-color:#ddd;"><A HREF="admin_products.php" TARGET="mainFrame">[NEW] Additional Products</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV><A HREF="products/" TARGET="mainFrame">Additional Products</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 4) gt 0>
        <DIV STYLE="background-color:#ddd;"><A HREF="news/" TARGET="mainFrame">Announcements</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV><A HREF="newzip/" TARGET="mainFrame">Area Pricing & Travel</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV STYLE="background-color:#ddd;"><A HREF="admin_batch_process.php" TARGET="mainFrame">[NEW] Batch Process</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
        <DIV><A HREF="admin_brokerages.php" TARGET="mainFrame">[NEW] Brokerages</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV STYLE="background-color:#ddd;"><A HREF="brokerages/" TARGET="mainFrame">Brokerages</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 4) gt 0>
        <DIV><A HREF="admin_post_shoot_call_list.php" TARGET="mainFrame">[NEW] Call List</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 14) gt 0>
        <DIV STYLE="background-color:#ddd;"><A HREF="contact_sheets/" TARGET="mainFrame">Contact Sheets</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
    	<DIV><A HREF="admin_table_status.php" TARGET="mainFrame">[NEW] Database Size</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
    	<DIV STYLE="background-color:#ddd;" ><A HREF="admin_promocodes.php" TARGET="mainFrame">Discount Codes</A></DIV>
    </cfif>
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
        <DIV><A HREF="duplicatetours/" TARGET="mainFrame">Duplicate Tours</A></DIV>
    </cfif>  
    <cfif bitand(#session.adminPermissions#, 12) gt 0>
    	<DIV  style="background-color:#ddd;"><A HREF="editors/" TARGET="mainFrame">Editors</A></DIV>
    </cfif>   
    <cfif bitand(#session.adminPermissions#, 12) gt 0>
    	<DIV  ><A HREF="employees/" TARGET="mainFrame">Employee List</A></DIV>
    </cfif>   	
    <cfif bitand(#session.adminPermissions#, 12) gt 0>
    	<DIV  style="background-color:#ddd"><A HREF="fixes/trackingSystem.php" TARGET="mainFrame">[NEW] FixIt</A></DIV>
    </cfif>   		
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
    	<DIV  ><A HREF="invoices/" TARGET="mainFrame">Invoices</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="sponsorbanner/" TARGET="mainFrame">Keyword Sponsor Banners </A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV ><A HREF="keywordthemes/" TARGET="mainFrame">Keyword Themes</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="mailchimp/mc_interface.php" TARGET="mainFrame">MailChimp</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
        <DIV ><A HREF="memberships/" TARGET="mainFrame">Memberships</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
    	<DIV style="background-color:#ddd;"><A HREF="admin_mls.php" TARGET="mainFrame">[NEW] MLS Checklist</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 4) gt 0>
    	<DIV ><A HREF="admin_mls_providers.php" TARGET="mainFrame">MLS Providers</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
	    <DIV style="background-color:#ddd;"><A HREF="packages/" TARGET="mainFrame">Packages</A></div>
    </cfif>
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
        <DIV ><A HREF="payment-plans/" TARGET="mainFrame">Payment Plans</A></DIV>
    </cfif>     
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
    	<DIV style="background-color:#ddd;"><A HREF="http://www.spotlighthometours.com/scheduled_tasks/updateCellCarrier.cfm?go=1" TARGET="mainFrame">Phone Carrier Lookup</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 14) gt 0>
    	<DIV ><A HREF="photographers/" TARGET="mainFrame">Photographers</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="reassigntours/" TARGET="mainFrame">Reassign Tours</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 14) gt 0>
        <DIV ><A HREF="recenttours/" TARGET="mainFrame">Recent Tours</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="admin_regional.php" TARGET="mainFrame">Regional Pricing/Mileage</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 4) gt 0>
        <DIV ><A HREF="reports/" TARGET="mainFrame">Reports</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="reports/index-new.php" TARGET="mainFrame">[NEW] Reports</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 4) gt 0>
        <DIV ><A HREF="salesreps/" TARGET="mainFrame">Sales Reps</A></DIV>
    </cfif>
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="mail/" TARGET="mainFrame">Send Email</A></DIV>
    </cfif>
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV><A HREF="sponsors/" TARGET="mainFrame">Sponsors</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="express/" TARGET="mainFrame">Spotlight Express (Service)</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV  ><A HREF="keyword/?pg=showExistingLoneWolfPlans" TARGET="mainFrame">Spotlight Keyword (Service)</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="preview/" TARGET="mainFrame">Spotlight Preview (Service)</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV ><A HREF="stats/" TARGET="_blank">Stats (In New Window)</A></div>
	</cfif>
	<cfif bitand(#session.adminPermissions#, 6) gt 0>
    	<DIV style="background-color:#ddd;"><A HREF="teams/" TARGET="mainFrame">Teams</A></DIV>
    </cfif>
    <cfif bitand(#session.adminPermissions#, 4) gt 0>
        <DIV ><A HREF="testimonials/" TARGET="mainFrame">Testimonials</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 4) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="themes/" TARGET="mainFrame">Themes</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 4) gt 0>
        <DIV ><A HREF="tour_demos/" TARGET="mainFrame">Tour Demos</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 4) gt 0>
	    <DIV style="background-color:#ddd;"><A HREF="suggestions/" TARGET="mainFrame">Tour Suggestions</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 6) gt 0>
    	<DIV  ><A HREF="admin_tourtypes.php" TARGET="mainFrame">Tour Types</A></DIV>
    </cfif>
	<cfif bitand(#session.adminPermissions#, 14) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="../uploader/upload.php" TARGET="mainFrame">[NEW] Uploader</A></div>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 14) gt 0>
        <DIV ><A HREF="users/" TARGET="mainFrame">Users</A></DIV>
    </cfif>    
    <cfif bitand(#session.adminPermissions#, 16) gt 0>
        <DIV style="background-color:#ddd;"><A HREF="admin_virtualstaging.php" TARGET="mainFrame">[NEW] Virtual Staging</A></div>
    </cfif>
</cfif>
<DIV class="divlink" onClick="PHP_Logout();" STYLE="background-color:#ddd;">Logout</div>
</DIV>
<DIV><br></DIV>
</DIV>
</BODY>
</HTML>
