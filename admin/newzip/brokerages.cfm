<CFPARAM name="url.action" default="">
<CFPARAM name="url.pg" default="listBrokerages">
<CFPARAM name="msg" default="">

<CFSWITCH expression="#url.action#">
	<CFCASE value="insertBrokerage">
		
		<CFLOCK type="exclusive" name="brokerages" timeout="30">
			<CFQUERY datasource="#request.db.dsn#">
				insert into brokerages (brokerageName, salesRepID, theme_id, logo,api_key,brokerageClientId,brokerageContactPhone,brokerageContactEmail,brokerageNotifyPhone,brokerageNotifyEmail,brokerageCountry,brokerageDesc,emailUnsuscribe)
				values (<cfqueryparam value="#form.brokerageName#" cfsqltype="cf_sql_varchar" maxlength="50">,
						<cfqueryparam value="#form.salesRepID#" cfsqltype="cf_sql_integer" maxlength="10">,
						<cfqueryparam value="#form.theme_id#" cfsqltype="cf_sql_integer" maxlength="10">,
						'',
						'#UCase(left(toBase64(encrypt('#brokerageName##randRange(1,9999)#', 'spht1987')),16))#',
                        <cfqueryparam value="#form.brokerageClientId#" cfsqltype="cf_sql_char" maxlength="15">,
                        <cfqueryparam value="#form.brokerageContactPhone#" cfsqltype="cf_sql_char" maxlength="10">,
                        <cfqueryparam value="#form.brokerageContactEmail#" cfsqltype="cf_sql_text">,
                        <cfqueryparam value="#form.brokerageNotifyPhone#" cfsqltype="cf_sql_char" maxlength="10">,
                        <cfqueryparam value="#form.brokerageNotifyEmail#" cfsqltype="cf_sql_text" >,
                        <cfqueryparam value="#form.brokerageCountry#" cfsqltype="cf_sql_integer" maxlength="2">,
                        <cfqueryparam value="#form.brokerageNotifyEmail#" cfsqltype="cf_sql_text" >,
                        <cfif isDefined('form.emailUnsuscribe')>1<cfelse>0</cfif>   
				)
			</CFQUERY>
			<CFQUERY name="qBrokerage" datasource="#request.db.dsn#">
				SELECT max(brokerageID) as newBrokerID FROM brokerages
			</CFQUERY>
		</CFLOCK>
		
		<CFIF len(form.logo_file)>
			<CFSET filepath = expandPath("../../images/logos")>
			<CFFILE action="upload" filefield="logo_file" destination="#filepath#/" nameconflict="overwrite">
			<CFSET new_filename = "brokerage_#qBrokerage.newBrokerID#.#cffile.serverFileExt#">
			<CFFILE action="rename" source="#filepath#/#cffile.serverFile#" destination="#filepath#/#new_filename#" nameconflict="overwrite">
		
			<CFQUERY datasource="#request.dsn#">
				UPDATE brokerages SET logo = '#new_filename#' WHERE brokerageID = '#qBrokerage.newBrokerID#'
			</CFQUERY>
		</CFIF>
		
		<CFSET msg = "The brokerage was successfully added.">
	</CFCASE>
	<CFCASE value="updateBrokerage">
		<CFQUERY datasource="#request.db.dsn#">
			update brokerages set
				brokerageName = <cfqueryparam value="#form.brokerageName#" cfsqltype="cf_sql_varchar" maxlength="50">,
				salesRepID = <cfqueryparam value="#form.salesRepID#" cfsqltype="cf_sql_integer" maxlength="10">,
				theme_id = <cfqueryparam value="#form.theme_id#" cfsqltype="cf_sql_integer" maxlength="10">,
                brokerageClientId=<cfqueryparam value="#trim(form.brokerageClientId)#" cfsqltype="cf_sql_char" maxlength="15">,
                brokerageContactPhone=<cfqueryparam value="#trim(form.brokerageContactPhone)#" cfsqltype="cf_sql_char" maxlength="10">,
                brokerageContactEmail=<cfqueryparam value="#trim(form.brokerageContactEmail)#" cfsqltype="cf_sql_text">,
                brokerageNotifyPhone=<cfqueryparam value="#trim(form.brokerageNotifyPhone)#" cfsqltype="cf_sql_char" maxlength="10">,
                brokerageNotifyEmail=<cfqueryparam value="#trim(form.brokerageNotifyEmail)#" cfsqltype="cf_sql_text" >,
                brokerageCountry=<cfqueryparam value="#trim(form.brokerageCountry)#" cfsqltype="cf_sql_integer" maxlength="2">,
                brokerageDesc=<cfqueryparam value="#trim(form.brokerageDesc)#" cfsqltype="cf_sql_text" >,
                emailUnsuscribe= <cfif isDefined('form.emailUnsuscribe')>1<cfelse>0</cfif>   

			where brokerageID = #form.brokerageID#
		</CFQUERY>
		
		<CFIF len(form.logo_file)>
			<CFSET filepath = expandPath("../../images/logos")>
			<CFFILE action="upload" filefield="logo_file" destination="#filepath#/" nameconflict="overwrite">
			<CFSET new_filename = "brokerage_#form.brokerageID#.#cffile.serverFileExt#">
			<CFFILE action="rename" source="#filepath#/#cffile.serverFile#" destination="#filepath#/#new_filename#" nameconflict="overwrite">
		
			<CFQUERY datasource="#request.dsn#">
				UPDATE brokerages SET logo = '#new_filename#' WHERE brokerageID = '#form.brokerageID#'
			</CFQUERY>
		</CFIF>
		
		<CFSET msg = "The brokerage was successfully updated.">
	</CFCASE>
	<CFCASE value="deleteBrokerage">
		<CFQUERY datasource="#request.db.dsn#">
			delete from brokerages where brokerageID = #url.brokerage#
		</CFQUERY>
		<CFSET msg = "The brokerage was successfully deleted.">
	</CFCASE>
	<CFCASE value="updatePrice">
		<CFSET lFields = form.fieldnames />
		<CFLOOP list="#lFields#" index="i">
			<CFIF FindNoCase('Tour_',i) neq 0>

				<!--- need to see if that reference already existed. if so update, otherwise, insert --->
				<CFQUERY name="qPrevious" datasource="#request.dsn#">
					select pb_id
					from pricing_brokers
					where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(form.brokerage,2,'_')#" />
					and tourtype_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(i,2,'_')#" />
				</CFQUERY>

				<!--- check to see if there was something in the form field. if so, update/insert --->
				<CFIF Trim(Evaluate('form.' & i)) neq "">
					<CFIF qPrevious.RecordCount gt 0>
						<CFQUERY name="qUpdate" datasource="#request.dsn#">
							update pricing_brokers
							set unitprice = <cfqueryparam cfsqltype="cf_sql_decimal" value="#Evaluate('form.' & i)#">,
                            	hide=<cfif isdefined('form.hide'&ListGetAt(i,2,'_'))>'1'<cfelse>'0'</cfif>,
                                broker_billable=<cfif isdefined('form.billable'&ListGetAt(i,2,'_'))>'1'<cfelse>'0'</cfif>
							where pb_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#qPrevious.pb_id#" />
						</CFQUERY>
					<CFELSE>
						<CFQUERY name="qCount" datasource="#request.dsn#">
							select max(pb_id) as mynum from pricing_brokers 
						</CFQUERY>
						<CFQUERY name="qInsert" datasource="#request.dsn#">
							insert into pricing_brokers (pb_id, brokerage_id, tourtype_id, unitprice,hide,broker_billable)
							values (
								<cfqueryparam cfsqltype="cf_sql_integer" value="#Evaluate(qCount.mynum + 1)#" />,
								<cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(form.brokerage,2,'_')#" />,
								<cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(i,2,'_')#" />,
								<cfqueryparam cfsqltype="cf_sql_decimal" value="#Evaluate('form.' & i)#" />,
                                <cfif isdefined('form.hide'&ListGetAt(i,2,'_'))>'1'<cfelse>'0'</cfif>,
                                <cfif isdefined('form.billable'&ListGetAt(i,2,'_'))>'1'<cfelse>'0'</cfif>
                                
							)
						</CFQUERY>					
					</CFIF>
				<CFELSE>
					<!--- check to see if there was a previous reference. if so, delete it --->
					<CFIF qPrevious.RecordCount gt 0>
						<CFQUERY name="qDelete" datasource="#request.dsn#">
							delete from pricing_brokers
							where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(form.brokerage,2,'_')#" />
							and tourtype_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(i,2,'_')#" />
						</CFQUERY>
					</CFIF>
				</CFIF>
			</CFIF>
		</CFLOOP>
		<CFSET msg = "Pricing successfully updated.">
		<CFSET url.pg = "editpricing" />
	</CFCASE>
    <CFCASE value="updateadditionalPrice">
		<CFSET lFields = form.fieldnames />
		<CFLOOP list="#lFields#" index="i">
			<CFIF FindNoCase('Tour_',i) neq 0>

				<!--- need to see if that reference already existed. if so update, otherwise, insert --->
				<CFQUERY name="qPrevious" datasource="#request.dsn#">
					select pb_id
					from pricing_brokers_additional
					where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(form.brokerage,2,'_')#" />
					and product_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(i,2,'_')#" />
				</CFQUERY>

				<!--- check to see if there was something in the form field. if so, update/insert --->
				<CFIF Trim(Evaluate('form.' & i)) neq "">
					<CFIF qPrevious.RecordCount gt 0>
						<CFQUERY name="qUpdate" datasource="#request.dsn#">
							update pricing_brokers_additional
							set unitprice = <cfqueryparam cfsqltype="cf_sql_decimal" value="#Evaluate('form.' & i)#">,
                            	hide=<cfif isdefined('form.hide'&ListGetAt(i,2,'_'))>'1'<cfelse>'0'</cfif>,
                                broker_billable=<cfif isdefined('form.billable'&ListGetAt(i,2,'_'))>'1'<cfelse>'0'</cfif>
							where pb_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#qPrevious.pb_id#" />
						</CFQUERY>
					<CFELSE>
						<CFQUERY name="qCount" datasource="#request.dsn#">
							select max(pb_id) as mynum from pricing_brokers_additional 
						</CFQUERY>
						<CFQUERY name="qInsert" datasource="#request.dsn#">
							insert into pricing_brokers_additional (pb_id, brokerage_id, product_id, unitprice,hide,broker_billable)
							values (
								<cfqueryparam cfsqltype="cf_sql_integer" value="#Evaluate(qCount.mynum + 1)#" />,
								<cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(form.brokerage,2,'_')#" />,
								<cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(i,2,'_')#" />,
								<cfqueryparam cfsqltype="cf_sql_decimal" value="#Evaluate('form.' & i)#" />,
                                <cfif isdefined('form.hide'&ListGetAt(i,2,'_'))>'1'<cfelse>'0'</cfif>,
                                <cfif isdefined('form.billable'&ListGetAt(i,2,'_'))>'1'<cfelse>'0'</cfif>
                                
							)
						</CFQUERY>					
					</CFIF>
				<CFELSE>
					<!--- check to see if there was a previous reference. if so, delete it --->
					<CFIF qPrevious.RecordCount gt 0>
						<CFQUERY name="qDelete" datasource="#request.dsn#">
							delete from pricing_brokers_additional
							where brokerage_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(form.brokerage,2,'_')#" />
							and product_id = <cfqueryparam cfsqltype="cf_sql_integer" value="#ListGetAt(i,2,'_')#" />
						</CFQUERY>
					</CFIF>
				</CFIF>
			</CFIF>
		</CFLOOP>
		<CFSET msg = "Pricing successfully updated.">
		<CFSET url.pg = "editpricingaddition" />
	</CFCASE>
</CFSWITCH>

<CFIF url.pg eq "editbrokerage">
	<CFINCLUDE template="_editbrokerage.cfm">
<CFELSEIF url.pg eq "editpricing">
	<CFINCLUDE template="_editpricing.cfm">
<CFELSEIF url.pg eq "editadditionalpricing">
	<CFINCLUDE template="_editadditionalpricing.cfm">

<CFELSEIF url.pg eq "editpricingaddition">
	<CFINCLUDE template="_editpricingaddition.cfm">

<CFELSEIF url.pg eq "editadditionalpricing">
	<CFINCLUDE template="_editadditionalpricing.cfm">
<CFELSEIF url.pg eq "updatePrices">
	<CFINCLUDE template="_updatepricing.cfm">
<CFELSE>
	<CFINCLUDE template="_listbrokerages.cfm">
</CFIF>