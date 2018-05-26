<CFSILENT>
	<CFPARAM name="url.orderby" default="brokerageName">
	<CFPARAM name="url.countyid" default="">
	<CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
		select b.zip_code_state_id, b.state,b.color,b.county
		from zip_code_state_county b
		where b.zip_code_state_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.countyid#"> 
	</CFQUERY>
	<CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
		select tt.tourTypeID, tt.demotourid, tt.tourTypeName, tt.description, tt.walkthrus, tt.videos, tt.panoramics, tt.photos, tt.unitPrice, pb.unitprice as BrokerPrice,pb.broker_billable,pb.hide
		from tourTypes tt left join 
			(select unitprice, tourtype_id,broker_billable,hide from pricing_counties where state_id = 
			<cfqueryparam cfsqltype="cf_sql_integer" value="#url.stateid#" /> and county_id = 
			<cfqueryparam cfsqltype="cf_sql_integer" value="#url.countyid#" />) pb 
			on tt.tourtypeid = pb.tourtype_id
		order by tt.tourTypeName ASC
	</CFQUERY>
</CFSILENT>
<HTML>
<HEAD>
<TITLE>Brokerages Pricing Update</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>

<BODY>
<CFOUTPUT>
<A HREF="http://www.spotlighthometours.com/admin/newzip/state/index.cfm?pg=editpricing">State</A> - <a href="/admin/newzip/state/county/index.cfm?pg=editpricing&stateid=#url.stateid#&state=#url.state#">County</a> <BR>

<DIV CLASS="msg">#msg#</DIV>
<H3>Update (#qBrokerages.state#) - #qBrokerages.county# Pricing</H3></h3>
 (leave blank to default to global price)
<FORM ACTION="#cgi.script_name#?action=updatePrice" METHOD="post">
	<INPUT TYPE="hidden" NAME="stateid" VALUE="state_#url.stateid#">
    <INPUT TYPE="hidden" NAME="state" VALUE="#url.state#">
    <INPUT TYPE="hidden" NAME="countyid" VALUE="county_#url.countyid#">
<TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
	<TR>
		<TH>Tour Name</TH>
		<TH>State Price</TH>
        <TH WIDTH="50">Make Inactive</TH>
	</TR>
		<CFLOOP query="qTourTypes">
	<TR BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
			<TD>#qTourTypes.tourTypeName#</TD>
			<TD>$<INPUT TYPE="text" VALUE="#qTourTypes.BrokerPrice#" NAME="Tour_#qTourTypes.tourTypeID#"></TD>
            
            <TD><INPUT NAME="hide#qTourTypes.tourTypeID#" TYPE="checkbox" VALUE="1" <cfif qTourTypes.hide eq '1'>CHECKED</cfif>></TD>
	</TR>
		</CFLOOP>
</TABLE>
<INPUT TYPE="submit" VALUE="Update Price"/>
</FORM>
</CFOUTPUT>
</BODY>
</HTML>
