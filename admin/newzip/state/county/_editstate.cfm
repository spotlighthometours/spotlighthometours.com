<CFSET editMode = iif(isDefined("url.brokerage"), true, false)>
<CFIF editMode>
	<CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
		select * from zip_code_state where zip_code_state_id = #url.brokerage#
	</CFQUERY>
</CFIF>


<HTML>
<HEAD>
<TITLE>Users</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>

<BODY>
<CFOUTPUT>
<FORM ENCTYPE="multipart/form-data" ACTION="#cgi.sript_name#?action=<cfif editMode>updateState<cfelse>insertState</cfif>" METHOD="post">
    <TABLE WIDTH="500" BORDER="0" CELLSPACING="2" CELLPADDING="4">
      <TR> 
        <TD CLASS="rowHead">State Abbribation</TD>
        <TD CLASS="rowData"><INPUT NAME="state" TYPE="text" SIZE="32" MAXLENGTH="50"<cfif editMode> readonly="" value="#qBrokerages.state#"</cfif>></TD>
      </TR>
       <TR> 
        <TD CLASS="rowHead">State Name</TD>
        <TD CLASS="rowData"><INPUT NAME="string" TYPE="text" SIZE="32" MAXLENGTH="50"<cfif editMode> value="#qBrokerages.string#"</cfif>></TD>
      </TR>
      <TR>
        <TD CLASS="rowHead">Color</TD>
        <TD CLASS="rowData"><INPUT NAME="color" TYPE="text" ID="brokerageClientId" SIZE="32" MAXLENGTH="50"<cfif editMode> value="#qBrokerages.color#"</cfif>></TD>
      </TR>
      <TR> 
        <TD CLASS="rowHead"><CFIF editMode>
            <INPUT TYPE="hidden" NAME="stateid" VALUE="#qBrokerages.zip_code_state_id#">
          </CFIF></TD>
        <TD CLASS="rowData"><INPUT TYPE="submit" VALUE="<cfif EditMode>Update<cfelse>Add</cfif> State"></TD>
      </TR>
      

    </TABLE>
</FORM>
</CFOUTPUT>
</BODY>
</HTML>
