<cfparam name="url.action" default="">
<cfparam name="url.pg" default="listCodes">
<cfparam name="msg" default="">
<cfparam name="errorMsg" default="">

<cfswitch expression="#url.action#">
	<cfcase value="insertCode">
		<cfif find('%',form.value)>
			<cfset form.value = reReplace(form.value, "[^0-9.]", "", "all") / 100>
		</cfif>

        <cfquery datasource="#request.db.dsn#" name="test">
			insert into promoCodes (codestr,value,expdate,limits,tourTypeID)
			values (<cfqueryparam value="#form.codestr#" cfsqltype="cf_sql_varchar" maxlength="7">,
					<cfqueryparam value="#reReplace(form.value, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">,
                    #createODBCDate(form.expdate)#
                    ,<cfif form.limit eq "">'0'<cfelse>'#form.limit#'</cfif>,
                    <cfif form.tourTypeID eq 0>'0'<cfelse>'#form.tourTypeID#'</cfif>)
		</cfquery>
		<cfset msg = "The code was successfully added.">
	</cfcase>
	
	<cfcase value="updateCode">
		<cfif find('%',form.value)>
			<cfset form.value = reReplace(form.value, "[^0-9.]", "", "all") / 100>
		</cfif>
		<cfquery datasource="#request.db.dsn#">
			update promoCodes
			set value = <cfqueryparam value="#reReplace(form.value, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">,
				 expDate = #createODBCDate(form.expdate)#
                 <cfif len(form.limit) gt 0>,limits='#form.limit#'</cfif>
                 <cfif len(form.tourTypeID) gt 0>,tourTypeID='#form.tourTypeID#'</cfif>
			where codestr = '#form.codestr#'
		</cfquery>
		<cfset msg = "The code was successfully updated.">
	</cfcase>
	
	<cfcase value="deleteCode">
		<cfquery datasource="#request.db.dsn#">
			delete from promoCodes where codeStr = '#url.code#'
		</cfquery>
		<cfset msg = "The code has been deleted.">
	</cfcase>
</cfswitch>
<cfinclude template="_#url.pg#.cfm">