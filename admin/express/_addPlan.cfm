<cfsilent>
	<!--- only get those brokerages not currently assigned to a plan --->
	<cfquery name="qBrokerages" datasource="#request.dsn#">
		select * from (
		select b.brokerageid, b.brokeragename, bex.id
		from brokerages b left outer join brokerage_express_signup bex on bex.brokerage_id = b.brokerageID
		) q
		where q.id is null
		order by q.brokeragename asc
	</cfquery>
	<cfquery name="brokerdefaultpricing" datasource="#request.dsn#">
		select default_amount as unitprice,timespan
		from services
		where id = 2
	</cfquery>
</cfsilent>
<cfoutput>
<form name="AddPlan" action="index.cfm?action=addPlan" method="post">
<table width="500" border="0" cellspacing="2" cellpadding="4">
	<tr><td class="rowHead">Select</td><td class="rowData">
		<select name="brokerageSelect">
			<cfloop query="qBrokerages">
				<option value="#qBrokerages.brokerageid#">#qBrokerages.brokeragename#</option>
			</cfloop>
		</select>
	</td></tr>
	<tr>
		<td class="rowHead">Pricing (leave blank for default)</td>
		<td>
			<input type="text" value="" name="pricing" /> (#DollarFormat(brokerdefaultpricing.unitprice)#/#brokerdefaultpricing.timespan#)
		</td>
	</tr>
	<tr>
		<td class="rowHead">&nbsp;</td>
		<td><input type="submit" value="Save Plan"> *</td>
	</tr>
</table>
</form>
<div style="font-size:10px;">* Adding a new plan will give all brokerage agents access to Spotlight Express</div>
</cfoutput>
