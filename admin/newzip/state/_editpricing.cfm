<CFSILENT>
	<CFPARAM name="url.orderby" default="state asc">
	<CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
		select b.state, b.string ,b.color,b.zip_code_state_id
		from zip_code_state b
		order by #url.orderby#
	</CFQUERY>
	<CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
		select tourTypeID, tourTypeName, unitPrice
		from tourTypes
		order by tourTypeName ASC
	</CFQUERY>
	<CFQUERY name="qBrokerPricing" datasource="#request.db.dsn#">
		select state_ID, tourtype_id, unitprice
		from pricing_states
	</CFQUERY>
</CFSILENT>
<HTML>
<HEAD>
<TITLE>States</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>

<BODY>

<CFOUTPUT>
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

<H3>State Specific Tour Type Pricing</H3>
<TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
	<TR>
  <TH><A HREF="#cgi.script_name#?orderby=brokerageID<cfif url.orderby eq "brokerageID">%20desc</cfif>">StateID</A></TH>
  <TH WIDTH="250"><A HREF="#cgi.script_name#?orderby=brokerageName<cfif url.orderby eq "brokerageName">%20desc</cfif>">State</A></TH>
	<CFLOOP query="qTourTypes">
		<TH>#qTourTypes.tourTypeName#</TH>
	</CFLOOP>
	<TH WIDTH="70">&nbsp;</TH>
	</TR>
  <CFLOOP query="qBrokerages">
	  <TR BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <TD>#zip_code_state_id#</TD>
		 <TD><A HREF="#cgi.script_name#?pg=editState&brokerage=#zip_code_state_id#">#state#</A> -#string#</TD>
		 <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
			<TD>
				<CFQUERY name="qTemp" dbtype="query">
					select unitPrice
					from qBrokerPricing
					where state_ID = #qBrokerages.zip_code_state_id#
					and tourtype_id = #qTourTypes['tourTypeID'][i]#
				</CFQUERY>
				<CFIF qTemp.RecordCount gt 0>
					#dollarFormat(qTemp.unitPrice)#
				<CFELSE>
					-
				</CFIF>
			</TD>
		 </CFLOOP>
		<TD><A HREF="#cgi.script_name#?pg=updatePrices&id=#qBrokerages.zip_code_state_id#">Edit</A> - <A HREF="/admin/newzip/state/county/index.cfm?pg=editpricing&stateid=#qBrokerages.zip_code_state_id#&state=#qBrokerages.state#">County</A></TD>
	  </TR>
  </CFLOOP>
</TABLE>
</CFOUTPUT>
</BODY>
</HTML>
