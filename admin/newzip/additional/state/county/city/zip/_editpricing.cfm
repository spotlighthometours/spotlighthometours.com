<CFSILENT>
	<CFPARAM name="url.orderby" default="city">
	<CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
		select b.state_prefix as state, b.county ,b.city,b.color,b.zip_code,b.zipid as zip_code_state_id
		from zip_code b
        where state_prefix='#url.state#' and county='#url.county#' and city='#url.city#'
		order by #url.orderby# asc
	</CFQUERY>
	<CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
		select productID as tourTypeID, productName as tourTypeName, unitPrice from products where tourTypeID = 0
	</CFQUERY>
	<CFQUERY name="qBrokerPricing" datasource="#request.db.dsn#">
		select state_ID, tourtype_id, unitprice,county_id,city_id,zip_id
		from pricing_zips_additional
        where state_id='#url.stateid#' and county_id='#url.countyid#' and city_id='#url.cityid#'
	</CFQUERY>
</CFSILENT>
<HTML>
<HEAD>
<TITLE>City</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>

<BODY>
<CFOUTPUT>
<A HREF="http://www.spotlighthometours.com/admin/newzip/additional/state/index.cfm?pg=editpricing">State</A> - <A HREF="http://www.spotlighthometours.com/admin/newzip/additional/state/county/index.cfm?pg=editpricing&stateid=#url.stateid#&state=#url.state#">County</A> - <A HREF="/admin/newzip/additional/state/county/city/index.cfm?pg=editpricing&stateid=#url.stateid#&cityid=#url.cityid#&county=#url.county#&state=#url.state#&city=#url.city#&countyid=#url.countyid#&">City</A> - Zip 
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

<H3>Zip Specific Tour Type Pricing</H3>
<TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
	<TR>
  <TH><A HREF="#cgi.script_name#?orderby=brokerageID<cfif url.orderby eq "brokerageID">%20desc</cfif>">ZipID</A></TH>
  <TH WIDTH="250"><A HREF="#cgi.script_name#?orderby=brokerageName<cfif url.orderby eq "brokerageName">%20desc</cfif>">Zip</A></TH>
	<CFLOOP query="qTourTypes">
		<TH>#qTourTypes.tourTypeName#</TH>
	</CFLOOP>
	<TH WIDTH="70">&nbsp;</TH>
	</TR>
  <CFLOOP query="qBrokerages">
	  <TR BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <TD>#zip_code_state_id#</TD>
		<TD>#state# -#county# -#city# - <A HREF="#cgi.script_name#?pg=editState&brokerage=#zip_code_state_id#">#zip_code#</A></TD>
		 <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
			<TD>
				<CFQUERY name="qTemp" dbtype="query">
					select unitPrice
					from qBrokerPricing
					where zip_ID = #qBrokerages.zip_code_state_id#
					and tourtype_id = #qTourTypes['tourTypeID'][i]#
				</CFQUERY>
				<CFIF qTemp.RecordCount gt 0>
					#dollarFormat(qTemp.unitPrice)#
				<CFELSE>
					-
				</CFIF>
			</TD>
		 </CFLOOP>
		<TD><A HREF="#cgi.script_name#?pg=updatePrices&zipid=#qBrokerages.zip_code_state_id#&stateid=#url.stateid#&cityid=#url.cityid#&county=#url.county#&state=#url.state#&city=#url.city#&countyid=#url.countyid#&">Edit</A></TD>
	  </TR>
  </CFLOOP>
</TABLE>
</CFOUTPUT>
</BODY>
</HTML>
