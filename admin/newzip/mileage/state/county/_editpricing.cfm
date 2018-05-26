<CFSILENT>
	<CFPARAM name="url.orderby" default="county asc">
	<CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
		select b.state, b.county ,b.color,b.zip_code_state_id
		from zip_code_state_county b
        where state='#url.state#'
		order by #url.orderby#
	</CFQUERY>
	<CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
		select productID as tourTypeID, productName as tourTypeName, unitPrice from mileage where tourTypeID = 0
	</CFQUERY>
	<CFQUERY name="qBrokerPricing" datasource="#request.db.dsn#">
		select state_ID, tourtype_id,county_id, unitprice
		from pricing_counties_mileage
        where state_id='#url.stateid#'
	</CFQUERY>
</CFSILENT>
<HTML>
<HEAD>
<TITLE>County</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>

<BODY>
<CFOUTPUT>
<A HREF="http://www.spotlighthometours.com/admin/newzip/mileage/state/index.cfm?pg=editpricing">State</A> - County <BR>
<DIV CLASS="msg">#msg#</DIV>
<H3>Default Tour Type Pricing</H3>
<TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
	<TR>
		<CFLOOP query="qTourTypes">
			<TH>#qTourTypes.tourTypeName#</TH>
		</CFLOOP>
	</TR>
	<TR>
		<CFLOOP query="qTourTypes">
			<TD>#dollarFormat(qTourTypes.unitPrice)#</TD>
		</CFLOOP>
	</TR>
</TABLE>

<H3>County Specific Tour Type Pricing</H3>
<TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
	<TR>
  <TH><A HREF="#cgi.script_name#?orderby=brokerageID<cfif url.orderby eq "brokerageID">%20desc</cfif>">CountyID</A></TH>
  <TH WIDTH="250"><A HREF="#cgi.script_name#?orderby=brokerageName<cfif url.orderby eq "brokerageName">%20desc</cfif>">County</A></TH>
	<CFLOOP query="qTourTypes">
		<TH>#qTourTypes.tourTypeName#</TH>
	</CFLOOP>
	<TH WIDTH="70">&nbsp;</TH>
	</TR>
  <CFLOOP query="qBrokerages">
	  <TR BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <TD>#zip_code_state_id#</TD>
		 <TD>#state# -<A HREF="#cgi.script_name#?pg=editState&brokerage=#zip_code_state_id#">#county#</A></TD>
		 <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
			<TD>
				<CFQUERY name="qTemp" dbtype="query">
					select unitPrice
					from qBrokerPricing
					where county_ID = #qBrokerages.zip_code_state_id#
					and tourtype_id = #qTourTypes['tourTypeID'][i]#
				</CFQUERY>
				<CFIF qTemp.RecordCount gt 0>
					#dollarFormat(qTemp.unitPrice)#
				<CFELSE>
					-
				</CFIF>
			</TD>
		 </CFLOOP>
		<TD><A HREF="#cgi.script_name#?pg=updatePrices&countyid=#qBrokerages.zip_code_state_id#&stateid=#url.stateid#&state=#url.state#">Edit</A> - <A HREF="/admin/newzip/mileage/state/county/city/index.cfm?pg=editpricing&stateid=#url.stateid#&countyid=#qBrokerages.zip_code_state_id#&county=#qBrokerages.county#&state=#url.state#&">city</A></TD>
	  </TR>
  </CFLOOP>
</TABLE>
</CFOUTPUT>
</BODY>
</HTML>
