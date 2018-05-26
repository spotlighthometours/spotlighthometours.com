<CFSILENT>
<CFSET individualMode = iif(isDefined("url.id"), true, false)>

<CFPARAM name="url.orderby" default="dateCreated">
<CFPARAM name="url.id" default="">
<CFQUERY name="qUsers" datasource="#request.db.dsn#">
	select u.userID, u.firstName, u.lastName, u.sponsorID, s.Name, u.dateCreated
	from sponsor_officers u left outer join sponsorsub s on u.SponsorID = s.id
    <cfif individualMode eq true>
    where u.sponsorID='#url.id#' 
    </cfif>
	order by #url.orderby#
</CFQUERY>

<CFFUNCTION name="getOfficerKeywords" access="public" hint="get id" returntype="STRING">
	<CFARGUMENT name="sponsorID" type="String" required="Yes">  
    <CFARGUMENT name="officerID" type="NUMERIC" required="Yes">       
    <CFQUERY name="qkeywordOk" datasource="#request.db.dsn#">
		select keyword from lonewolf_keywords where officerID='#arguments.officerID#' and sponsorid='#arguments.sponsorID#'
	</CFQUERY>
    <cfset keywords='' />
    <cfif qKeywordOk.RecordCount gt 0>
    	<cfloop QUERY="qKeywordOk">
        	<cfset keywords=keywords&keyword&',' />
        </cfloop>
    </cfif>
     
     <CFRETURN keywords/>
</CFFUNCTION>


</CFSILENT>
<HTML>
<HEAD>
<TITLE>Sponsor Officers</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
<SCRIPT TYPE="text/javascript">
function confirmDelete() {
	if(!confirm("Are you sure you want to remove this officer?"))
		return false;
}
</SCRIPT>
</HEAD>

<BODY>
<CFOUTPUT>
<TABLE WIDTH="100%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
  <TH WIDTH="5%"><A HREF="#cgi.script_name#?orderby=userID<cfif url.orderby eq "userID">%20desc</cfif>">UserID</A></TH>
  <TH WIDTH="30%"><A HREF="#cgi.script_name#?orderby=lastName<cfif url.orderby eq "lastName">%20desc</cfif>">Name</A></TH>
  <TH WIDTH="30%"><A HREF="#cgi.script_name#?orderby=Name<cfif url.orderby eq "Name">%20desc</cfif>">Sponsor</A></TH>
   <TH WIDTH="20%"><A HREF="#cgi.script_name#?orderby=Name<cfif url.orderby eq "keyword">%20desc</cfif>">Keyword</A></TH>
  <TH WIDTH="10%"><A HREF="#cgi.script_name#?orderby=dateCreated<cfif url.orderby eq "dateCreated">%20desc</cfif>">Joined</A></TH>
  <TH WIDTH="20%">&nbsp;</TH>
  <CFLOOP query="qUsers">
  <TR BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
    <TD>#userID#</TD>
    <TD><A HREF="#cgi.script_name#?pg=addSponsorOfficer&user=#userID#">#lastName#, #firstName#</A></TD>
	<TD>#Name#</TD>
    <TD>#getOfficerKeywords(sponsorID,userID)#</TD>
	<TD>#dateFormat(dateCreated, "m/d/yyyy")#</TD>
	
	<TD><A HREF="#cgi.script_name#?pg=addSponsorOfficer&user=#userID#">edit</A> <A onClick="return confirmDelete();" HREF="#cgi.script_name#?action=deleteOfficer&user=#userID#">delete</A></TD>
  </TR>
  </CFLOOP>
</TABLE>
</CFOUTPUT>
</BODY>
</HTML>
