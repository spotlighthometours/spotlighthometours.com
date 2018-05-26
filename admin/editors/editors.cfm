<cfparam name="url.action" default="">
<cfparam name="msg" default="">
<cfparam name="url.pg" default="listeditors">

<cfswitch expression="#url.action#">
	<cfcase value="inserteditor">
    
    <cfscript>
				phone    = form.phone_1 & "." & form.phone_2 & "." & form.phone_3;
				
			</cfscript>
		<cfquery datasource="#request.db.dsn#">
			insert into editors (fullName, email,address, city, state, zipCode, phone,phonecarrier,dateCreated, dateModified,active) values ('#trim(form.fullName)#','#trim(form.email)#',
            <cfqueryparam value="#trim(form.address)#" cfsqltype="cf_sql_varchar" maxlength="200">,
						<cfqueryparam value="#trim(form.city)#" cfsqltype="cf_sql_varchar" maxlength="50">,
						<cfqueryparam value="#trim(form.state)#" cfsqltype="cf_sql_varchar" maxlength="2">,
						<cfqueryparam value="#trim(form.zipCode)#" cfsqltype="cf_sql_varchar" maxlength="10">,
						<cfqueryparam value="#trim(phone)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#phonecarrier#" cfsqltype="cf_sql_varchar" maxlength="20">,
                        #now()#,
						#now()#,
                        1
            )
		</cfquery>
		<cfset msg = "Photographer has been added successfully.">
	</cfcase>
	<cfcase value="updateeditor">
     
    <cfscript>
				phone    = form.phone_1 & "." & form.phone_2 & "." & form.phone_3;
				
			</cfscript>
		<cfquery datasource="#request.db.dsn#">
			update editors set
				fullname = '#trim(form.fullName)#',
				email = '#trim(form.email)#',
                address = <cfqueryparam value="#form.address#" cfsqltype="cf_sql_varchar" maxlength="200">,
				city = <cfqueryparam value="#form.city#" cfsqltype="cf_sql_varchar" maxlength="50">,
				state = <cfqueryparam value="#form.state#" cfsqltype="cf_sql_varchar" maxlength="2">,
				zipCode = <cfqueryparam value="#form.zipCode#" cfsqltype="cf_sql_varchar" maxlength="10">,
				phone = <cfqueryparam value="#phone#" cfsqltype="cf_sql_varchar" maxlength="20">,
				phonecarrier = <cfqueryparam value="#trim(phonecarrier)#" cfsqltype="cf_sql_varchar" maxlength="20">,
                dateModified = #now()#,
                active = <cfif isDefined('form.active')>1<cfelse>0</cfif> 
                
			where id = #form.id#
		</cfquery>
		<cfset msg = "Editor has been updated successfully.">
	</cfcase>
	<cfcase value="deleteeditor">
		<cfquery datasource="#request.db.dsn#">
			delete from editors where id = #url.rep#
		</cfquery>
		<cfset msg = "Editor has been removed successfully.">
	</cfcase>
</cfswitch>
<cfinclude template="_#url.pg#.cfm">