<cfsilent>
	<!--- only get those brokerages not currently assigned to a plan --->
	<cfquery name="qBrokerages" datasource="#request.dsn#">
		select * from (
		select b.brokerageid, b.brokeragename, mb.mobile_account_type_id
		from brokerages b left outer join mobile_brokerage_signup mb on mb.brokerage_id = b.brokerageID
		) q
		where q.mobile_account_type_id is null 
		order by q.brokeragename asc
	</cfquery>
	<cfquery name="qPlans" datasource="#request.dsn#">
		select id, name, comment
		from mobile_account_types
	</cfquery>
	<cfquery name="brokerdefaultpricing" datasource="#request.dsn#">
		select default_amount as unitprice,timespan
		from services
		where id = 1
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
		<td class="rowHead">Plan Type</td>
		<td>
			<select name="planSelect">
				<cfloop query="qPlans">
					<option value="#qPlans.id#">#qPlans.name#</option>
				</cfloop>
			</select>
			<ul>
			<cfloop query="qPlans">
				<li><strong>#qPlans.name#</strong> - #qPlans.comment#</li>
			</cfloop>
			</ul>
		</td>
	</tr>
	<tr>
		<td class="rowHead">Pricing (leave blank for default)</td>
		<td>
			<input type="text" value="" name="pricing" /> (#DollarFormat(brokerdefaultpricing.unitprice)#/#brokerdefaultpricing.timespan#)
		</td>
	</tr>
	<tr>
		<td class="rowHead">&nbsp;</td>
		<td><input type="submit" value="Save Plan"></td>
	</tr>
</table>
</form>
</cfoutput>
