<cfsilent>
	<!--- display all current brokerage plans --->
	<cfquery name="qPlans" datasource="#request.dsn#">
		select count(u.userid) as mycount,b.brokerageName,b.brokerageID,mat.name,
        case when isnull(m.active) then 0 else m.active end as PreviewActive
		from mobile_brokerage_signup mb join mobile_account_types mat on mb.mobile_account_type_id = mat.id
			join brokerages b on mb.brokerage_id = b.brokerageID
			left outer join users u on u.brokerageID = b.brokerageID
			left outer join members m on u.userid = m.userID AND m.typeID = 2
        group by b.brokerageName,b.brokerageID,mat.name,PreviewActive
		order by b.brokerageName asc, PreviewActive desc
	</cfquery>
	<!--- get the special brokerage pricing --->
	<cfquery name="brokerpricing" datasource="#request.dsn#">
		select brokerage_id, unitprice
		from pricing_brokers_service
		where service_id = 1
	</cfquery>
	<!--- get the default pricing --->
	<cfquery name="brokerdefaultpricing" datasource="#request.dsn#">
		select default_amount as unitprice
		from services
		where id = 1
	</cfquery>
</cfsilent>
<h3>Current Spotlight Preview Users by Brokerage</h3>
<table width="90%" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<th>Brokerage Name</th>
		<th>Users (Out of Total Agents)</th>
		<th>Plan Type</th>
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
					<cfif qPlans.PreviewActive eq 1>
						<cfset nCount = qPlans.myCount />
						<cfset nBrokerageID = qPlans.brokerageid />
						<cfquery name="qNot" dbtype="query">
							select myCount
							from qPlans
							where PreviewActive = 0
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
				<td>#qPlans.name#</td>
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
					<cfif qPlans.name eq 'standalone'>
						<em>#DollarFormat(nPrice)#</em>
					<cfelse>
						#DollarFormat(nPrice)#
					</cfif>
				</td>
				<td>
					<cfif qPlans.name eq 'standalone'>
						<em>#DollarFormat(nPrice * nCount)#*</em>
					<cfelse>
						#DollarFormat(nPrice * nCount)#
					</cfif>
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
