<cfsilent>
	<cfset xmlDeleteItems = XmlParse(getHTTPRequestData().content) />
	<cfloop index="i" from="1" to="#ArrayLen(xmlDeleteItems.transaction.xmlNodes)#">
		<cfif Trim(xmlDeleteItems.transaction.xmlNodes[i].xmlName) eq 'img'>
			<cfquery name="qDelete" datasource="#request.db.dsn#">
			delete from media
			where mediaID = <cfqueryparam cfsqltype="cf_sql_integer" value="#xmlDeleteItems.transaction.xmlNodes[i].xmlAttributes.id#">
			</cfquery>
            <cfquery name="ptDelete" datasource="#request.db.dsn#">
			delete from photo_tour_images
			where mediaID = <cfqueryparam cfsqltype="cf_sql_integer" value="#xmlDeleteItems.transaction.xmlNodes[i].xmlAttributes.id#">
			</cfquery>
		</cfif>
	</cfloop>
</cfsilent>