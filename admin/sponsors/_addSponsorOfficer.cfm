<CFSILENT>
<CFSET editMode = iif(isDefined("url.user"), true, false)>
<CFIF editMode>
	<CFQUERY name="qUsers" datasource="#request.db.dsn#">
		select * from sponsor_officers where userID = #url.user#
	</CFQUERY>
	<CFSET aPhone = listToArray(qUsers.phone, ".")>
	<CFSET aPhone2 = listToArray(qUsers.phone2, ".")>
	<CFSET aFax = listToArray(qUsers.fax, ".")>
<CFELSE>
	<CFSET qUsers = QueryNew("phonecarrier") />
</CFIF>
<CFQUERY name="qStates" datasource="#request.db.dsn#">
	select stateFullName, stateAbbrName from states order by stateFullName
</CFQUERY>
<CFQUERY name="qSponsors" datasource="#request.db.dsn#">
	select * from sponsorsub order by Name
</CFQUERY>
<CFQUERY name="qKeywords" datasource="#request.db.dsn#">
	select 		k.id,
    			k.keyword,
            	k.officerID,
                k.sponsorid,
                k.officerid,
                (select name from sponsorsub where id=k.sponsorid limit 1) as sponsorName,
                (select concat(firstname,' ',lastname) from sponsor_officers where userID=k.officerID limit 1) as officerName
   	from 		lonewolf_keywords k
    order by 	Keyword
</CFQUERY>


</CFSILENT>
<HTML>
<HEAD>
<TITLE>Users</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>

<BODY>
<CFOUTPUT>
<FORM ACTION="#cgi.sript_name#?action=<cfif editMode>updateUser<cfelse>insertUser</cfif>" METHOD="post"  enctype="multipart/form-data">
    <TABLE WIDTH="500" BORDER="0" CELLSPACING="2" CELLPADDING="4">
      <TR>
        <TD CLASS="rowHead">First Name</TD>
        <TD CLASS="rowData"><INPUT NAME="firstName" TYPE="text" SIZE="20" MAXLENGTH="15"<cfif editMode> value="#qUsers.firstName#"</cfif>></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Last Name</TD>
        <TD CLASS="rowData"><INPUT NAME="lastName" TYPE="text" SIZE="28" MAXLENGTH="24"<cfif editMode> value="#qUsers.lastName#"</cfif>></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">User Type</TD>
        <TD CLASS="rowData"> <SELECT NAME="userType">
            <OPTION<cfif editMode and qUsers.userType eq "Loan Officer"> selected</cfif>>Loan Officer</OPTION>
            <OPTION<cfif editMode and qUsers.userType eq "Officer"> selected</cfif>>Officer</OPTION>
          
          </SELECT> </TD>
      </TR>
      
      <TR>
        <TD CLASS="rowHead">Sponsor</TD>
        <TD CLASS="rowData"> <SELECT NAME="sponsorID">
            <CFIF not editMode>
              <OPTION VALUE="">Select One...</OPTION>
            </CFIF>
            <CFLOOP query="qSponsors">
              <OPTION VALUE="#qSponsors.id#"<cfif editMode and qSponsors.id eq qUsers.SponsorID> selected</cfif>>#qSponsors.Name#</OPTION>
            </CFLOOP>
          
          </SELECT> </TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Keyword</TD>
        <TD CLASS="rowData"><SELECT NAME="keywordid" multiple="true">
          <CFLOOP query="qKeywords">
          <OPTION VALUE="#qKeywords.id#" <cfif editMode >#IIF(ListFindNoCase(url.user,qKeywords.officerID),DE('selected="true"'),DE(''))#</cfif>>#qKeywords.Keyword# (#qKeywords.sponsorName# - #qKeywords.officerName#)</OPTION>
          </CFLOOP>
        </SELECT></TD>
      </TR>
     
      <TR>
        <TD CLASS="rowHead">Username</TD>
        <TD CLASS="rowData"><INPUT NAME="username" TYPE="text" SIZE="24" MAXLENGTH="48"<cfif editMode> value="#qUsers.username#"</cfif>></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Password</TD>
        <TD CLASS="rowData"><INPUT NAME="password" TYPE="text" SIZE="24" MAXLENGTH="20"<cfif editMode> value="#qUsers.password#"</cfif>></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Email Address</TD>
        <TD CLASS="rowData"><INPUT NAME="email" TYPE="text" SIZE="36" MAXLENGTH="255"<cfif editMode> value="#qUsers.email#"</cfif>></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Address</TD>
        <TD CLASS="rowData"><INPUT NAME="address" TYPE="text" SIZE="36" MAXLENGTH="200"<cfif editMode> value="#qUsers.address#"</cfif>></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">City</TD>
        <TD CLASS="rowData"><INPUT NAME="city" TYPE="text" SIZE="24" MAXLENGTH="50"<cfif editMode> value="#qUsers.city#"</cfif>></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">State</TD>
        <TD CLASS="rowData"> <SELECT NAME="state">
            <CFIF not editMode>
              <OPTION VALUE="">Select One...</OPTION>
            </CFIF>
            <CFLOOP query="qStates">
              <OPTION VALUE="#stateAbbrName#"<cfif editMode and stateAbbrName eq qUsers.state> selected</cfif>>#stateFullName#</OPTION>
            </CFLOOP>
          </SELECT> </TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Zip Code</TD>
        <TD CLASS="rowData"><INPUT NAME="zipCode" TYPE="text" SIZE="12" MAXLENGTH="10"<cfif editMode> value="#qUsers.zipCode#"</cfif>></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Cell Phone</TD>
        <TD CLASS="rowData"><INPUT TYPE="text" NAME="phone_1"<cfif editMode and arrayLen(aPhone) eq 3> value="#aPhone[1]#"</cfif> MAXLENGTH="3" SIZE="3" />
          &nbsp; <INPUT TYPE="text" NAME="phone_2"<cfif editMode and arrayLen(aPhone) eq 3> value="#aPhone[2]#" maxlength="3"</cfif> SIZE="3" />
          &nbsp; <INPUT TYPE="text" NAME="phone_3"<cfif editMode and arrayLen(aPhone) eq 3> value="#aPhone[3]#" maxlength="4"</cfif> SIZE="4" /></TD>
      </TR>
		<TR>
			<TD CLASS="rowHead">Cell Carrier</TD>
			<TD CLASS="rowData">
				<SELECT NAME="phonecarrier">
					<OPTION VALUE="" >-</OPTION>
					<OPTION VALUE="ATTUS" <cfif qUsers.phonecarrier eq "ATTUS">selected</cfif>>AT&T</OPTION>
                    <OPTION VALUE="CINGULARUS" <cfif qUsers.phonecarrier eq "CINGULARUS">selected</cfif>>Cingular</OPTION>
                    <OPTION VALUE="NEXTELUS" <cfif qUsers.phonecarrier eq "NEXTELUS">selected</cfif>>Nextel</OPTION>
					<OPTION VALUE="SPRINTUS" <cfif qUsers.phonecarrier eq "SPRINTUS">selected</cfif>>Sprint</OPTION>
					<OPTION VALUE="TMOBILEUS" <cfif qUsers.phonecarrier eq "TMOBILEUS">selected</cfif>>T-Mobile</OPTION>
					<OPTION VALUE="VERIZONUS" <cfif qUsers.phonecarrier eq "VERIZONUS">selected</cfif>>Verizon</OPTION>
					<OPTION VALUE="other">Other</OPTION>
				</SELECT>
			</TD>
		</TR>
      <TR>
        <TD CLASS="rowHead">Alternate Phone</TD>
        <TD CLASS="rowData"><INPUT TYPE="text"<cfif editMode and arrayLen(aPhone2) eq 3> value="#aPhone2[1]#"</cfif> NAME="phone2_1" MAXLENGTH="3" SIZE="3" />
          &nbsp; <INPUT TYPE="text" NAME="phone2_2"<cfif editMode and arrayLen(aPhone2) eq 3> value="#aPhone2[2]#"</cfif> MAXLENGTH="3" SIZE="3" />
          &nbsp; <INPUT TYPE="text" NAME="phone2_3"<cfif editMode and arrayLen(aPhone2) eq 3> value="#aPhone2[3]#"</cfif> MAXLENGTH="4" SIZE="4" /></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Fax</TD>
        <TD CLASS="rowData"><INPUT TYPE="text" NAME="fax_1"<cfif editMode and arrayLen(aFax) eq 3> value="#aFax[1]#"</cfif> MAXLENGTH="3" SIZE="3" />
          &nbsp; <INPUT TYPE="text" NAME="fax_2"<cfif editMode and arrayLen(aFax) eq 3> value="#aFax[2]#"</cfif> MAXLENGTH="3" SIZE="3" />
          &nbsp; <INPUT TYPE="text" NAME="fax_3"<cfif editMode and arrayLen(aFax) eq 3> value="#aFax[3]#"</cfif> MAXLENGTH="4" SIZE="4" /></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">URI</TD>
        <TD CLASS="rowData"><INPUT NAME="uri" TYPE="text" SIZE="36"<cfif editMode> value="#qUsers.uri#"</cfif>></TD>
      </TR>
       <TR>
        <TD VALIGN="top" CLASS="rowHead">Custom Message</TD>
        <TD CLASS="rowData"><TEXTAREA NAME="customMessage" COLS="36" ROWS="5" "><CFIF editMode>#qUsers.customMessage#</CFIF></TEXTAREA></TD>
      </TR>
       <TR>
        <TD CLASS="rowHead">image</TD>
        <TD CLASS="rowData">	<CFIF editMode><CFIF qusers.image neq "">
				<DIV>Current Image:</DIV>
				<DIV><IMG SRC="http://www.spotlightpreview.com/images/officerImages/#qUsers.image#" /></DIV>
				<DIV>&nbsp;</DIV>
			</CFIF>
            </CFIF>
			<DIV><INPUT TYPE="file" VALUE="" NAME="agentimage" /> (250px X 75px)</DIV>	</TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Send Email</TD>
        <TD CLASS="rowData"><INPUT NAME="sendEmail" TYPE="checkbox" ID="sendEmail" VALUE="1">
		</TD>
      </TR>
      
      <CFIF editMode>
        <TR>
          <TD CLASS="rowHead">Created</TD>
          <TD CLASS="rowData">#dateFormat(qUsers.dateCreated, "mm/dd/yyyy")# #timeFormat(qUsers.dateCreated,
            "hh:mm tt")#</TD>
        </TR>
        <TR>
          <TD CLASS="rowHead">Last
            Modified</TD>
          <TD CLASS="rowData">#dateFormat(qUsers.dateModified, "mm/dd/yyyy")# #timeFormat(qUsers.dateModified, "hh:mm tt")#</TD>
        </TR>
      </CFIF>
      <TR>
        <TD CLASS="rowHead"><CFIF editMode>
            <INPUT TYPE="hidden" NAME="userID" VALUE="#qUsers.userID#">
          </CFIF></TD>
        <TD CLASS="rowData"><INPUT TYPE="submit" VALUE="<cfif EditMode>Update<cfelse>Add</cfif> User"></TD>
      </TR>
    </TABLE>
</FORM>
</CFOUTPUT>
</BODY>
</HTML>
