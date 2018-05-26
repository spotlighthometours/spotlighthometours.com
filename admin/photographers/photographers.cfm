<cfparam name="url.action" default="">
<cfparam name="msg" default="">
<cfparam name="url.pg" default="listphotographers">
<cfswitch expression="#url.action#">
	<cfcase value="insertphotographer">
    	<cfquery name="qEmail" datasource="#request.db.dsn#">
			SELECT photographerID, fullName FROM photographers WHERE email = '#trim(form.email)#'
        </cfquery>
        <CFIF qEmail.RecordCount eq 0>
			<cfscript>
                phone    = form.phone_1 & "." & form.phone_2 & "." & form.phone_3;
            </cfscript>
            <cfquery datasource="#request.db.dsn#" result="stResult">
                insert into photographers 
                (fullName, email,address, city, state, zipCode, phone,phonecarrier,dateCreated, dateModified,active,isAffiliate,password,peMerToken) 
                values ('#trim(form.fullName)#','#trim(form.email)#',
                <cfqueryparam value="#trim(form.address)#" cfsqltype="cf_sql_varchar" maxlength="200">,
                            <cfqueryparam value="#trim(form.city)#" cfsqltype="cf_sql_varchar" maxlength="50">,
                            <cfqueryparam value="#trim(form.state)#" cfsqltype="cf_sql_varchar" maxlength="2">,
                            <cfqueryparam value="#trim(form.zipCode)#" cfsqltype="cf_sql_varchar" maxlength="10">,
                            <cfqueryparam value="#trim(phone)#" cfsqltype="cf_sql_varchar" maxlength="20">,
                            <cfqueryparam value="#phonecarrier#" cfsqltype="cf_sql_varchar" maxlength="20">,
                            #now()#,
                            #now()#,
                            1,
                            <cfif isDefined('form.isAffiliate')>1<cfelse>0</cfif>,
                            <cfqueryparam value="#password#" cfsqltype="cf_sql_varchar" maxlength="20">,
							<cfqueryparam value="#trim(form.peMerToken)#" cfsqltype="cf_sql_varchar" maxlength="100">
                )
            </cfquery>
            <cfif isDefined('form.isAffiliate')>
	            <cfquery name="qEmail" datasource="#request.db.dsn#">
					INSERT INTO brokerages (brokerageName, affiliatePhotographerID) 
                    	VALUES ('Affiliate-#trim(form.fullName)#', #stResult["GENERATEDKEY"]#)
        		</cfquery>
            </cfif>    
            <cfset msg = "Photographer has been added successfully.">
        <CFELSE>
            <cfset msg = "Photographer EMail is not unique. (#qEmail.photographerID#, #qEmail.fullName# is already using that EMail address)">
        </CFIF>
	</cfcase>
	<cfcase value="updatephotographer">
     	<cfquery name="qEmail" datasource="#request.db.dsn#">
			SELECT photographerID, fullName FROM photographers WHERE email = '#trim(form.email)#'
            	AND photographerID <> #form.photographerID#
        </cfquery>
        <CFIF qEmail.RecordCount eq 0>
			<cfscript>
                phone    = form.phone_1 & "." & form.phone_2 & "." & form.phone_3;
            </cfscript>
            <cfquery datasource="#request.db.dsn#">
                update photographers set
                    fullname = '#trim(form.fullName)#',
                    email = '#trim(form.email)#',
                    address = <cfqueryparam value="#form.address#" cfsqltype="cf_sql_varchar" maxlength="200">,
                    city = <cfqueryparam value="#form.city#" cfsqltype="cf_sql_varchar" maxlength="50">,
                    state = <cfqueryparam value="#form.state#" cfsqltype="cf_sql_varchar" maxlength="2">,
                    zipCode = <cfqueryparam value="#form.zipCode#" cfsqltype="cf_sql_varchar" maxlength="10">,
                    phone = <cfqueryparam value="#phone#" cfsqltype="cf_sql_varchar" maxlength="20">,
                    phonecarrier = <cfqueryparam value="#trim(phonecarrier)#" cfsqltype="cf_sql_varchar" maxlength="20">,
                    dateModified = #now()#,
                    active = <cfif isDefined('form.active')>1<cfelse>0</cfif>,
                    isAffiliate = <cfif isDefined('form.isAffiliate')>1<cfelse>0</cfif>,
                    password = <cfqueryparam value="#password#" cfsqltype="cf_sql_varchar" maxlength="20">,
					peMerToken = <cfqueryparam value="#trim(form.peMerToken)#" cfsqltype="cf_sql_varchar" maxlength="100">
                where photographerID = #form.photographerID#
            </cfquery>
            
            <cfif isDefined('form.isAffiliate')>
            	<cfquery name="qBrokerageCheck" datasource="#request.db.dsn#">
					SELECT brokerageID FROM brokerages WHERE brokerageName = 'Affiliate-#trim(form.fullName)#'
                </cfquery>
                <CFIF qBrokerageCheck.RecordCount EQ 0>
		            <cfquery name="qBrokerage" datasource="#request.db.dsn#">
						INSERT INTO brokerages (brokerageName, affiliatePhotographerID) 
        	            	VALUES ('Affiliate-#trim(form.fullName)#', #form.photographerID#)
        			</cfquery>
                </CFIF>
            </cfif>    
            
            <cfset msg = "Photographer has been updated successfully.">
        <CFELSE>
            <cfset msg = "Photographer EMail is not unique. (#qEmail.photographerID#, #qEmail.fullName# is already using that EMail address)">
        </CFIF>
	</cfcase>
	<cfcase value="deletephotographer">
		<cfquery datasource="#request.db.dsn#">
			delete from photographers where photographerID = #url.rep#
		</cfquery>
		<cfset msg = "Photographer has been removed successfully.">
	</cfcase>
</cfswitch>
<cfinclude template="_#url.pg#.cfm">