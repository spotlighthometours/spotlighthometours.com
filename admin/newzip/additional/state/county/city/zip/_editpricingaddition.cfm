<CFSILENT>
	<CFPARAM name="url.orderby" default="brokerageName">
	<CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
		select b.brokerageID, b.brokerageName ,b.brokerageDesc
		from brokerages b
		order by #url.orderby#
	</CFQUERY>
	<CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
		select productID, productName, unitPrice
		from products
        where tourtypeid='0'
        
		order by productName ASC
	</CFQUERY>
	<CFQUERY name="qBrokerPricing" datasource="#request.db.dsn#">
		select brokerage_ID, product_id, unitprice
		from pricing_Brokers_additional
	</CFQUERY>
</CFSILENT>
<HTML>
<HEAD>
<TITLE>Brokerages</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>

<BODY>
<CFOUTPUT>
<DIV CLASS="msg">#msg#</DIV>
<H3>Default Additional Product Pricing</H3>
<TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
	<TR>
		<CFLOOP query="qTourTypes">
			<TH>#qTourTypes.productName#</TH>
		</CFLOOP>
	</TR>
	<TR>
		<CFLOOP query="qTourTypes">
			<TD>#dollarFormat(qTourTypes.unitPrice)#</TD>
		</CFLOOP>
	</TR>
</TABLE>

<H3>Broker Specific Additional Product Pricing</H3>
<TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
	<TR>
  <TH><A HREF="#cgi.script_name#?orderby=brokerageID<cfif url.orderby eq "brokerageID">%20desc</cfif>">StateID</A></TH>
  <TH WIDTH="250"><A HREF="#cgi.script_name#?orderby=brokerageName<cfif url.orderby eq "brokerageName">%20desc</cfif>">Name</A></TH>
	<CFLOOP query="qTourTypes">
		<TH>#qTourTypes.productName#</TH>
	</CFLOOP>
	<TH>&nbsp;</TH>
	</TR>
  <CFLOOP query="qBrokerages">
	  <TR BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
		 <TD>#brokerageID#</TD>
		 <TD><A HREF="#cgi.script_name#?pg=editState&state=#brokerageID#">#brokerageName#</A> -#brokerageDesc#</TD>
		 <CFLOOP index="i" from="1" to="#qTourTypes.RecordCount#">
			<TD>
				<CFQUERY name="qTemp" dbtype="query">
					select unitPrice
					from qBrokerPricing
					where brokerage_ID = #qBrokerages.brokerageID#
					and product_id = #qTourTypes['productid'][i]#
				</CFQUERY>
				<CFIF qTemp.RecordCount gt 0>
					#dollarFormat(qTemp.unitPrice)#
				<CFELSE>
					-
				</CFIF>
			</TD>
		 </CFLOOP>
		<TD><A HREF="#cgi.script_name#?pg=editadditionalpricing&id=#qBrokerages.brokerageID#">Edit</A></TD>
	  </TR>
  </CFLOOP>
</TABLE>
</CFOUTPUT>
</BODY>
</HTML>
