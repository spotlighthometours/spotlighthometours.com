<cfsilent>
<!--- <cfsavecontent variable="saveScopes"> --->
	<cfset xmlSaveItems = XmlParse(getHTTPRequestData().content) />

	<!--- Iterate over the xml and save to the database --->
	<cfloop index="i" from="1" to="#ArrayLen(xmlSaveItems.transaction.xmlNodes)#">
		<cfif Trim(xmlSaveItems.transaction.xmlNodes[i].xmlName) eq 'img'>
			<cfset nodeTemp = xmlSaveItems.transaction.xmlNodes[i] />
			<cfquery name="qUpdate" datasource="#request.db.dsn#">
				update media
				set show_on_tab = <cfqueryparam cfsqltype="cf_sql_integer" value="#nodeTemp.xmlAttributes.onstage#" />,
				room = <cfqueryparam cfsqltype="cf_sql_varchar" value="#nodeTemp.xmlAttributes.room#" maxlength="50" />,
				description = <cfqueryparam cfsqltype="cf_sql_varchar" value="#nodeTemp.xmlAttributes.description#" maxlength="65000" />,
				<cfif Trim(nodeTemp.xmlAttributes.tourIcon) eq	"">
					tourIcon = <cfqueryparam cfsqltype="cf_sql_integer" value="0" />,
				<cfelse>
					tourIcon = <cfqueryparam cfsqltype="cf_sql_integer" value="#nodeTemp.xmlAttributes.tourIcon#" />,
				</cfif>
				sortOrder = <cfqueryparam cfsqltype="cf_sql_integer" value="#nodeTemp.xmlAttributes.position#" />,
				modifiedOn = <cfqueryparam cfsqltype="cf_sql_timestamp" value="#now()#" />
				where mediaID = <cfqueryparam cfsqltype="cf_sql_integer" value="#nodeTemp.xmlAttributes.id#" />
			</cfquery>
		</cfif>
	</cfloop>
<!--- </cfsavecontent> --->
</cfsilent>
