<cfsilent>
	<!--- display all current brokerage plans --->
	<cfquery name="qPlans" datasource="#request.dsn#">
		select count(u.userid) as mycount,b.brokerageName,b.brokerageID,
        case when isnull(m.active) then 0 else m.active end as DIYActive
		from brokerage_express_signup bex
			join brokerages b on bex.brokerage_id = b.brokerageID
			left outer join users u on u.brokerageID = b.brokerageID
            left outer join members m on u.userid = m.userID AND m.typeID = 1
		group by b.brokerageName,b.brokerageID,DIYActive
		order by b.brokerageName asc,DIYActive desc
	</cfquery>
	<!--- get the special brokerage pricing --->
	<cfquery name="brokerpricing" datasource="#request.dsn#">
		select brokerage_id, unitprice
		from pricing_brokers_service
		where service_id = 2
	</cfquery>
	<!--- get the default pricing --->
	<cfquery name="brokerdefaultpricing" datasource="#request.dsn#">
		select default_amount as unitprice
		from services
		where id = 2
	</cfquery>
</cfsilent>
<h3>Current Spotlight Do It Yourself Users by Brokerage</h3>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Brokerage Name</th>
		<th>Users (Out of Total Agents)</th>
		<th>Amount Per Person</th>
		<th>Estimated Monthly Total</th>
		<th>Manage Agent Access</th>
	</tr>
	<cfoutput>
	<cfset strTemp ="">
	<cfset bZebra = 0 />
	<cfloop query="qPlans">
		<cfset bFlag = 0 />
		<cfif qPlans.brokerageName neq strTemp>
			<cfset strTemp = qPlans.brokerageName>
			<cfset bFlag = 1 />
			<cfset bZebra = (bZebra + 1) mod 2>
			<cfset qNot = QueryNew("myCount") />
		</cfif>
		<cfif bFlag eq 1>
			<tr bgcolor="###iif(bZebra mod 2, de("E8EEF7"), de("ffffff"))#">
				<td>#qPlans.brokerageName#</td>
				<td>
					<cfif qPlans.DIYActive eq 1>
						<cfset nCount = qPlans.myCount />
						<cfset nBrokerageID = qPlans.brokerageid />
						<cfquery name="qNot" dbtype="query">
							select myCount
							from qPlans
							where DIYActive = 0
							and brokerageid = #nBrokerageID#
						</cfquery>
						<cfif qNot.RecordCount eq 0>
							<cfset nNot = 0>
						<cfelse>
							<cfset nNot = qNot.myCount />
						</cfif>
					<cfelse>
						<cfset nCount = 0 />
						<cfset nNot = 0 />
					</cfif>
					#nCount# (#Evaluate(nNot + nCount)#)
				</td>
				<td>
					<cfquery name="qTemp" dbtype="query">
						select unitprice
						from brokerpricing
						where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#qPlans.brokerageID#">
					</cfquery>
					<cfif qTemp.RecordCount gt 0>
						<cfset nPrice = qTemp.unitPrice />
					<cfelse>
						<cfset nPrice = brokerdefaultpricing.unitPrice />
					</cfif>
					#DollarFormat(nPrice)#
				</td>
				<td>
					#DollarFormat(nPrice * nCount)#
				</td>
				<td>
					<a href="?pg=editAgents&brokerageID=#qPlans.brokerageID#">Edit Agents</a>
					<cfif qPlans.brokerageID neq 21><a href="index.cfm?action=deletePlan&brokerageID=#qPlans.brokerageID#">Delete Plan</a></cfif>
				</td>
			</tr>
		</cfif>
	</cfloop>
	</cfoutput>
</table>

<div style="margin-top:20px;font-size:10px;">* Because agents from this brokerage are signed up and billed individually, the total amount is the theoretical monthly sum from their credit cards.</div>
