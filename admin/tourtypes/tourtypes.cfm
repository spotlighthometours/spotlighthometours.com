<cfparam name="url.action" default="">
<cfparam name="url.pg" default="listTourTypes">
<cfparam name="msg" default="">

<cfswitch expression="#url.action#">
	<cfcase value="insertTourType">
		<cflock name="insertTourType" type="exclusive" timeout="5">
        <cfset monthly = 0 />
		<cfif structKeyExists(form,'monthly') and form.monthly eq "on">
        	<cfset monthly = 1 />
        </cfif>
			<cfif StructKeyExists(form,"mobileOnly") AND form.mobileOnly eq "on">
				<cfquery name="qTourTypes" datasource="#request.db.dsn#">
					insert into tourTypes (tourTypeName, unitPrice, description, walkthrus, videos, panoramics, photos, mobileOnly,hidden,tourCategory,monthly)
					values (<cfqueryparam value="#form.tourTypeName#" cfsqltype="cf_sql_varchar" maxlength="50">,
							<cfqueryparam value="#reReplace(form.unitPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">,
							<cfqueryparam value="#form.description#" cfsqltype="cf_sql_longvarchar">,
							<cfqueryparam value="#form.walkthrus#" cfsqltype="cf_sql_varchar" maxlength="10">,
							<cfqueryparam value="#form.videos#" cfsqltype="cf_sql_varchar" maxlength="10">,
							<cfqueryparam value="#form.panoramics#" cfsqltype="cf_sql_varchar" maxlength="10">,
							<cfqueryparam value="#form.photos#" cfsqltype="cf_sql_varchar" maxlength="10">,
							<cfqueryparam value="1" cfsqltype="cf_sql_integer">,
							<cfqueryparam value="#form.hidden#" cfsqltype="cf_sql_integer" maxlength="1">,
                            <cfqueryparam value="#form.tourCategory#" cfsqltype="cf_sql_enum" >,
                            <cfqueryparam value="#monthly#" cfsqltype="cf_sql_integer" maxlength="1">
					)
				</cfquery>
			<cfelse>
				<cfquery name="qTourTypes" datasource="#request.db.dsn#">
					insert into tourTypes (tourTypeName, unitPrice, description, walkthrus, videos, panoramics, photos,hidden,tourCategory,monthly)
					values (<cfqueryparam value="#form.tourTypeName#" cfsqltype="cf_sql_varchar" maxlength="50">,
							<cfqueryparam value="#reReplace(form.unitPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">,
							<cfqueryparam value="#form.description#" cfsqltype="cf_sql_longvarchar">,
							<cfqueryparam value="#form.walkthrus#" cfsqltype="cf_sql_varchar" maxlength="10">,
							<cfqueryparam value="#form.videos#" cfsqltype="cf_sql_varchar" maxlength="10">,
							<cfqueryparam value="#form.panoramics#" cfsqltype="cf_sql_varchar" maxlength="10">,
							<cfqueryparam value="#form.photos#" cfsqltype="cf_sql_varchar" maxlength="10">,
							<cfqueryparam value="#form.hidden#" cfsqltype="cf_sql_varchar" maxlength="1">,
                            <cfqueryparam value="#form.tourCategory#" cfsqltype="cf_sql_enum" >,
                            <cfqueryparam value="#monthly#" cfsqltype="cf_sql_integer" maxlength="1">
					)
				</cfquery>
			</cfif>
			<cfquery name="qTourTypes" datasource="#request.db.dsn#">
				select max(tourTypeID) as maxTourTypeID from tourTypes
			</cfquery>
		</cflock>
		<cfquery datasource="#request.db.dsn#">
			insert into products (tourTypeID, unitPrice)
			values (#qTourTypes.maxTourTypeID#,
					  <cfqueryparam value="#reReplace(form.unitPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">)
		</cfquery>
		<cfset msg = "The tour type was successfully added.">
	</cfcase>
	<cfcase value="updateTourType">
    	<cfset monthly = 0 />
		<cfif structKeyExists(form,'monthly') and form.monthly eq "on">
        	<cfset monthly = 1 />
        </cfif>
		<cfif structKeyExists(form,'mobileOnly') and form.mobileOnly eq "on">
			<cfquery datasource="#request.db.dsn#">
				update tourTypes set
					tourTypeName = <cfqueryparam value="#form.tourTypeName#" cfsqltype="cf_sql_varchar" maxlength="50">,
					unitPrice = 	<cfqueryparam value="#reReplace(form.unitPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">,
					description = 	<cfqueryparam value="#form.description#" cfsqltype="cf_sql_longvarchar">,
					walkthrus = 	<cfqueryparam value="#form.walkthrus#" cfsqltype="cf_sql_varchar" maxlength="10">,
					videos = 	<cfqueryparam value="#form.videos#" cfsqltype="cf_sql_varchar" maxlength="10">,
					panoramics = 	<cfqueryparam value="#form.panoramics#" cfsqltype="cf_sql_varchar" maxlength="10">,
					photos = 	<cfqueryparam value="#form.photos#" cfsqltype="cf_sql_varchar" maxlength="10">,
					mobileOnly = <cfqueryparam value="1" cfsqltype="cf_sql_integer" />,
					hidden = <cfqueryparam value="#form.hidden#" cfsqltype="cf_sql_integer" />,
                    tourCategory =   <cfqueryparam value="#form.tourCategory#" cfsqltype="cf_sql_enum" >,
                    monthly = <cfqueryparam value="#monthly#" cfsqltype="cf_sql_integer" maxlength="1">
				where tourTypeID = #form.tourTypeID#
			</cfquery>
		<cfelse>
			<cfquery datasource="#request.db.dsn#">
				update tourTypes set
					tourTypeName = <cfqueryparam value="#form.tourTypeName#" cfsqltype="cf_sql_varchar" maxlength="50">,
					unitPrice = 	<cfqueryparam value="#reReplace(form.unitPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">,
					description = 	<cfqueryparam value="#form.description#" cfsqltype="cf_sql_longvarchar">,
					walkthrus = 	<cfqueryparam value="#form.walkthrus#" cfsqltype="cf_sql_varchar" maxlength="10">,
					videos = 	<cfqueryparam value="#form.videos#" cfsqltype="cf_sql_varchar" maxlength="10">,
					panoramics = 	<cfqueryparam value="#form.panoramics#" cfsqltype="cf_sql_varchar" maxlength="10">,
					photos = 	<cfqueryparam value="#form.photos#" cfsqltype="cf_sql_varchar" maxlength="10">,
					mobileOnly = <cfqueryparam value="0" cfsqltype="cf_sql_integer" />,
					hidden = <cfqueryparam value="#form.hidden#" cfsqltype="cf_sql_integer" />,
                    tourCategory=  <cfqueryparam value="#form.tourCategory#" cfsqltype="cf_sql_enum" >,
                    monthly = <cfqueryparam value="#monthly#" cfsqltype="cf_sql_integer" maxlength="1">
				where tourTypeID = #form.tourTypeID#
			</cfquery>
		</cfif>
		<cfquery datasource="#request.db.dsn#">
			update products set
			unitPrice = <cfqueryparam value="#reReplace(form.unitPrice, "[^0-9.]", "", "all")#" cfsqltype="cf_sql_money">
			where tourTypeID = #form.tourTypeID#
		</cfquery>
		<cfset msg = "The tour type was successfully updated.">
	</cfcase>
	<cfcase value="deleteTourType">
		<cfquery datasource="#request.db.dsn#">
			delete from tourTypes where tourTypeID = #url.tourType#
		</cfquery>
		<cfset msg = "The tour type was successfully deleted.">
	</cfcase>
</cfswitch>

<cfif url.pg eq "editTourType">
	<cfinclude template="_edittourtype.cfm">
<cfelse>
	<cfinclude template="_listtourtypes.cfm">
</cfif>