<CFSILENT>
    <CFPARAM name="url.orderby" default="userID desc">
    <CFPARAM name="url.page" default="1" />
    <CFPARAM name="url.itemsperpage" default="50" />
    <CFPARAM name="nStart" default="#((url.page - 1) * url.itemsperpage + 1)#" />
    <CFPARAM name="nEnd" default="#(nStart + url.itemsperpage - 1)#" />
    <CFPARAM name="pIndex" default="#(url.page * url.itemsperpage - url.itemsperpage)#" />
    
    <CFPARAM name="url.name" default="" />
    <CFPARAM name="url.userid" default="" />
    <CFPARAM name="url.BrokerageID" default="" />
    <cfif len(url.BrokerageID) gt 0>
        <CFSET url.itemsperpage = 500 />
    <cfelse>
        <CFSET url.itemsperpage = 50 />
    </cfif>
    
    <CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
        select * from brokerages WHERE BrokerageName="Coldwell Banker Colorado" order by BrokerageName
    </CFQUERY>
    
    <CFQUERY name="qUsers" datasource="#request.db.dsn#">
        SELECT SQL_CALC_FOUND_ROWS userID, firstName, lastName, username, u.brokerageID, brokerageName, otherBrokerage, brokerageDesc, dateCreated, lonewolfAgent 
        FROM users u LEFT OUTER JOIN brokerages b ON u.brokerageID = b.brokerageID
        <cfif len(url.name) gt 0>
            <cfif FindNoCase(" ", Trim(url.name)) gt 0>
            	<!--- If there is a space in the name form input, --->
                <!--- separate and search first and last names --->
                WHERE (u.firstname LIKE '%#LEFT(url.name, FindNoCase(" ", Trim(url.name))-1)#%' AND u.lastname LIKE '%#Right(url.name, Len(url.name) - FindNoCase(" ", Trim(url.name)))#%')
                
            <cfelse>
            	WHERE (u.firstname LIKE '%#url.name#%' OR u.lastname LIKE '%#url.name#%')
            </cfif>
        </cfif>
        <cfif len(url.userid) gt 0>
        	WHERE u.userID LIKE '%#url.userid#%'
        </cfif>
        <cfif len(url.BrokerageID) gt 0>
            <cfif len(url.name) gt 0 OR len(url.userid) gt 0>
                AND
            <cfelse>
                WHERE
            </cfif> 
            u.brokerageid = '#url.BrokerageID#'
        </cfif>
        <cfif len(url.name) gt 0 OR len(url.userid) gt 0 OR len(url.BrokerageID) gt 0>
                AND
            <cfelse>
                WHERE
            </cfif> 
		b.BrokerageName="Coldwell Banker Colorado" ORDER BY #url.orderby# LIMIT #pIndex#,#url.itemsperpage#
    </CFQUERY>
    <CFQUERY name="qResultRows" datasource="#request.db.dsn#">
        SELECT FOUND_ROWS() as totalPages;
    </CFQUERY>
    
    <CFSET url.totalpages = qResultRows.totalPages />

</CFSILENT>

<HTML>
<HEAD>
<TITLE>Users</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
<script src='/repository_inc/jquery-2.1.4.min.js'></script>
<SCRIPT TYPE="text/javascript">
$(document).ready(function(){
	$("#name").trigger("focus");	
});
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this user?"))
		return false;
}
</SCRIPT>
</HEAD>

<BODY>
<CFOUTPUT>
<CFINCLUDE template="_pagination.cfm" />
<FORM ACTION="" METHOD="get">


<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD>Filter by Name :</TD>
    <TD><INPUT NAME="name" TYPE="text" id="name" value="#url.name#"></TD>
  </TR>
  <TR>
    <TD WIDTH="10%">Filter by User ID:</TD>
    <TD WIDTH="90%"><INPUT NAME="userid" TYPE="text" value="#url.userid#"></TD>
  </TR>
  <TR>
    <TD>Filter by Brokerage :</TD>
    <TD><SELECT NAME="BrokerageID">
    		  <OPTION VALUE="" <cfif len(url.BrokerageID) eq 0> selected</cfif>>Select One...</OPTION>
            <CFLOOP query="qBrokerages">
              <OPTION VALUE="#BrokerageID#"<cfif url.BrokerageID eq BrokerageID> selected</cfif>>#BrokerageName# (desc:#brokerageDesc#)</OPTION>
            </CFLOOP>
          </SELECT></TD>
  </TR>
  <TR>
    <TD>&nbsp;</TD>
    <TD><LABEL>
      <INPUT TYPE="submit" NAME="button" ID="button" VALUE="Go">
    </LABEL></TD>
  </TR>
</TABLE>
</FORM>
<h3>
[Results : #qUsers.RecordCount#]
<cfset diycount = 0>
<cfset lwcount = 0>
<CFLOOP query="qUsers">
	<cfquery name="qUserDIY" datasource="#request.db.dsn#">
		SELECT id FROM members WHERE typeID='1' AND active='1' AND userID='#userID#'
	</cfquery>
	<cfif qUserDIY.recordcount gt 0>
		<cfset diycount = Evaluate(diycount + 1)>
	</cfif>
	<cfif qUsers.lonewolfAgent eq 1>
		<cfset lwcount = Evaluate(lwcount + 1)>
	</cfif>
</CFLOOP>
<cfif diycount gt 0>
	[DIY Users : #diycount#]
</cfif>
<cfif lwcount gt 0>
	[LW Users : #lwcount#]
</cfif>
</h3>
<DIV CLASS="msg">#msg#</DIV>
<TABLE WIDTH="95%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
  <TH><A HREF="#cgi.script_name#?orderby=userID<cfif url.orderby eq "userID">%20desc</cfif>&name=#url.name#&brokerageid=#brokerageID#">UserID</A></TH>
  <TH><A HREF="#cgi.script_name#?orderby=lastName<cfif url.orderby eq "lastName">%20desc</cfif>&name=#url.name#&brokerageid=#brokerageID#">Name</A></TH>
  <TH><A HREF="#cgi.script_name#?orderby=username<cfif url.orderby eq "username">%20desc</cfif>&name=#url.name#&brokerageid=#brokerageID#">Username</A></TH>
  <TH><A HREF="#cgi.script_name#?orderby=brokerageName<cfif url.orderby eq "brokerageName">%20desc</cfif>&name=#url.name#&brokerageid=#brokerageID#">Brokerage</A></TH>
  <TH><A HREF="#cgi.script_name#?orderby=lonewolfAgent<cfif url.orderby eq "lonewolfAgent">%20desc</cfif>&name=#url.name#&brokerageid=#brokerageID#">LW</A></TH>
  <TH>DIY</TH>
  <TH>Preview</TH>
  <TH>Social Hub</TH>
  <TH>Concierge Level</TH>
  <TH><A HREF="#cgi.script_name#?orderby=dateCreated<cfif url.orderby eq "dateCreated">%20desc</cfif>&name=#url.name#&brokerageid=#brokerageID#">Joined</A></TH>
  <TH>&nbsp;</TH>
  <TH>&nbsp;</TH>
  <TH>&nbsp;</TH>
   <TH>&nbsp;</TH>
  <CFLOOP query="qUsers">
  	<cfquery name="qUserDIY" datasource="#request.db.dsn#">
		SELECT id FROM members WHERE typeID='1' AND active='1' AND userID='#userID#'
	</cfquery>
    <cfquery name="qUserPreview" datasource="#request.db.dsn#">
		SELECT id FROM members WHERE typeID='2' AND active='1' AND userID='#userID#'
	</cfquery>
    <cfquery name="qUserSocialHub" datasource="#request.db.dsn#">
		SELECT id FROM members WHERE typeID='3' AND active='1' AND userID='#userID#'
	</cfquery>
    <CFQUERY name="qConcierge" datasource="#request.db.dsn#">
        SELECT membershipType  
            FROM users u
            LEFT JOIN members as m ON m.userID = u.userID AND m.active = 1 AND m.typeID IN (4,5,6)
            LEFT JOIN memberships as ms ON ms.id = m.typeID
            WHERE u.userID = #qUsers.userID#
    </CFQUERY>
  <TR BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
    <TD>#userID#</TD>
    <TD><A HREF="#cgi.script_name#?pg=editUser&user=#userID#">#lastName#, #firstName#</A></TD>
	  <TD>#username#</TD>
    <TD><CFIF brokerageID>#brokerageName#<CFELSE>#otherbrokerage#</CFIF><CFIF len(brokerageDesc) gt 0>- #brokerageDesc#</CFIF></TD>
	<TD><cfif #lonewolfAgent# eq "1">LW User<cfelse></cfif></TD>
	<TD><cfif qUserDIY.recordcount gt 0>DIY User</cfif></TD>
    <TD><cfif qUserPreview.recordcount gt 0>Preview User</cfif></TD>
    <TD><cfif qUserSocialHub.recordcount gt 0>Hub User</cfif></TD>
    <TD align="center">#qConcierge.membershipType#</TD>
	<TD>#dateFormat(dateCreated, "m/d/yyyy")#</TD>
	<TD><A HREF="../../users/auto-login.php?username=#username#">login</A></TD>
	<TD><A HREF="#cgi.script_name#?pg=tours&user=#userID#">tours</A></TD>
	<TD><A onClick="return confirmDelete();" HREF="#cgi.script_name#?action=deleteUser&user=#userID#">delete</A></TD>
    <TD><A  href="/admin/admin_invoice.php?id=#userID#">invoice</A></TD>
  </TR>
  </CFLOOP>
</TABLE>
<CFINCLUDE template="_pagination.cfm" />
</CFOUTPUT>
</BODY>
</HTML>
