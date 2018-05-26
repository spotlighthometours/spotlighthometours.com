<cfsilent>
<cfquery name="qAgentName" datasource="#request.db.dsn#">
	select firstname, lastname, email
	from users
	where userid = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.userid#">
</cfquery>
</cfsilent>
<cfset mailAttributes = {
	server="smtp.gmail.com",
	username="info@spotlighthometours.com",
	password="Spotlight01",
	from="info@spotlighthometours.com",
	to="billing@spotlighthometours.com",
	subject="Invoice Order Confirmation"
}
/>
<cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
  <style type="text/css">
td, th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

##shoppingCart { width: 500px; }
##shoppingCart th {
	background: ##989898;
	color: ##ffffff;
}
##shoppingCart td {
	background: ##f5f5f5;
	padding: 4px;
}

</style>
  <table width="500">
    <tr> 
      <td colspan="2">This is an automatically generated email. An invoice has been payed by #form.name# for agent #form.userid# (#qAgentName.firstname# #qAgentName.lastname#).<br />
		Phone: #session.user.billing.phone#<br />
		Email: #qAgentName.email#<br/><br />
		</td>
    </tr>
    <tr> 
      <td><strong>Invoice Number: </strong></td>
		<td>#form.invoicenumber#</td>
    </tr>
    <tr> 
      <td><strong>Amount Paid: </strong></td>
		<td>#form.amount#</td>
    </tr>
    <tr> 
      <td><strong>Notes: </strong></td>
		<td>#form.notes#</td>
    </tr>
  </table>
</cfmail>