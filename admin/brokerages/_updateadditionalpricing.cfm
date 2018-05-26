<CFSILENT>
	<CFPARAM name="url.orderby" default="brokerageName">
	<CFPARAM name="url.id" default="">
	<CFQUERY name="qBrokerages" datasource="#request.db.dsn#">
		select b.brokerageID, b.brokerageName
		from brokerages b
		where b.brokerageID = <cfqueryparam cfsqltype="cf_sql_integer" value="#url.id#"> 
	</CFQUERY>
	<CFQUERY name="qTourTypes" datasource="#request.db.dsn#">
		select tt.productID, tt.productName, tt.unitPrice, pb.unitprice as BrokerPrice,pb.broker_billable,pb.hide
		from products tt left join 
			(select unitprice, product_id,broker_billable,hide from pricing_brokers where brokerage_id = 
			<cfqueryparam cfsqltype="cf_sql_integer" value="#url.id#" />) pb 
			on tt.productID = pb.product_id
        where tt.tourTypeID='0'    
		order by tt.productName ASC
	</CFQUERY>
</CFSILENT>
<HTML>
<HEAD>
<TITLE>Brokerages Pricing Update</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>

<BODY>
<CFOUTPUT>
<DIV CLASS="msg">#msg#</DIV>
<H3>Update #qBrokerages.brokerageName# Pricing</H3></h3>
 (leave blank to default to global price)
<FORM ACTION="#cgi.script_name#?action=updatePrice" METHOD="post">
	<INPUT TYPE="hidden" NAME="brokerage" VALUE="broker_#url.id#">
<TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
	<TR>
		<TH>Tour Name</TH>
		<TH>Brokerage Price</TH>
        <TH WIDTH="50">Brokerage Billable</TH>
        <TH WIDTH="50">Hide</TH>
	</TR>
		<CFLOOP query="qTourTypes">
	<TR BGCOLOR="###iif(currentRow mod 2, de("E8EEF7"), de("ffffff"))#">
			<TD>#qTourTypes.productName#</TD>
			<TD>$<INPUT TYPE="text" VALUE="#qTourTypes.BrokerPrice#" NAME="Tour_#qTourTypes.productID#"></TD>
            <TD><INPUT NAME="billable#qTourTypes.productID#" TYPE="checkbox" VALUE="1" <cfif qTourTypes.broker_billable eq '1'>CHECKED</cfif>></TD>
            <TD><INPUT NAME="hide#qTourTypes.productID#" TYPE="checkbox" VALUE="1" <cfif qTourTypes.hide eq '1'>CHECKED</cfif>></TD>
	</TR>
		</CFLOOP>
</TABLE>
<INPUT TYPE="submit" VALUE="Update Price"/>
</FORM>
</CFOUTPUT>
</BODY>
</HTML>