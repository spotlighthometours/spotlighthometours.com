<CFSILENT>
	<CFPARAM name="url.orderby" default="brokerageName">
	<CFPARAM name="url.id" default="">
	<CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
		select b.zipid, b.state_prefix,b.color,b.county,b.zip_code,b.city
		from zip_code b
		where b.zipid = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.zipid#"> 
	</CFQUERY>
	<CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
		select tt.productID as tourTypeID, tt.productName as tourTypeName, tt.unitPrice, pb.unitprice as BrokerPrice,pb.broker_billable,pb.hide
		from products tt left join 
			(select unitprice, tourtype_id,broker_billable,hide from pricing_zips_additional where state_id = 
			<cfqueryparam cfsqltype="cf_sql_integer" value="#url.stateid#" /> and county_id = 
			<cfqueryparam cfsqltype="cf_sql_integer" value="#url.countyid#" /> and city_id =
            <cfqueryparam cfsqltype="cf_sql_integer" value="#url.cityid#" /> and zip_id=
            <cfqueryparam cfsqltype="cf_sql_integer" value="#url.zipid#" />) pb 
			on tt.productID = pb.tourtype_id
        where tt.tourTypeID = 0    
		order by tourTypeName ASC
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
<A HREF="http://www.spotlighthometours.com/admin/newzip/additional/state/index.cfm?pg=editpricing">State</A> - <A HREF="http://www.spotlighthometours.com/admin/newzip/additional/state/county/index.cfm?pg=editpricing&stateid=#url.stateid#&state=#url.state#">County</A> - <A HREF="/admin/newzip/additional/state/county/city/index.cfm?pg=editpricing&stateid=#url.stateid#&cityid=#url.cityid#&county=#url.county#&state=#url.state#&city=#url.city#&countyid=#url.countyid#&">City</A> - <A HREF="/admin/newzip/additional/state/county/city/zip/index.cfm?pg=editpricing&stateid=#url.stateid#&cityid=#url.cityid#&county=#url.county#&state=#url.state#&city=#url.city#&countyid=#url.countyid#&">Zip</A> 

<DIV CLASS="msg">#msg#</DIV>
<H3>Update #qBrokerages.state_prefix#(#qBrokerages.county# #qBrokerages.city# #qBrokerages.zip_code#) Pricing</H3></h3>
 (leave blank to default to global price)
<FORM ACTION="#cgi.script_name#?action=updatePrice" METHOD="post">
	<INPUT TYPE="hidden" NAME="stateid" VALUE="state_#url.stateid#">
    <INPUT TYPE="hidden" NAME="state" VALUE="#url.state#">
    <INPUT TYPE="hidden" NAME="countyid" VALUE="county_#url.countyid#">
    <INPUT TYPE="hidden" NAME="county" VALUE="#url.county#">
    <INPUT TYPE="hidden" NAME="cityid" VALUE="city_#url.cityid#">
    <INPUT TYPE="hidden" NAME="city" VALUE="#url.city#">
    <INPUT TYPE="hidden" NAME="zipid" VALUE="zip_#url.zipid#">
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
