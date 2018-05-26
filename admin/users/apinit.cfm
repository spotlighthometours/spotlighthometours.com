<cfsilent>
	<cfif StructKeyExists(session,"reorder") AND StructKeyExists(session.reorder,"tourid")>
		<cfset url.tour = session.reorder.tourid />
	<cfelse>
		<cfparam name="url.tour" default="" />
	</cfif>
	<cfparam name="url.lRoomOrder"
		default="Exterior,
				Great Room,
				Kitchen,
				Dining Room,
				Family Room,
				Master Bedroom,
				Master Suite,
				Master Bath,
				Office,
				Bedroom,
				Bedroom One,
				Bedroom Two,
				Bedroom Three,
				Bedroom Four,
				Bedroom Five,
				Laundry Room" />
	<!--- mimic the beginnings of proc_media --->
	<cfquery name="qMedia" datasource="#request.db.dsn#">
		select mediaid, show_on_tab, room, description, tourIcon, sortOrder
		from media
		where tourID = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.tour#" />
		and mediatype = 'photo'
		order by mediaType desc, sortOrder, createdOn desc
	</cfquery>

	<!--- need to detects whether these are previously ordered. if not, order by room --->
	<cfset nTemp = ListLen(url.lRoomOrder) + 1 />
	<cfset aRoomOrder = ArrayNew(1) />
	<cfif qMedia.RecordCount gt 0>
		<cfset ArrayResize(aRoomOrder,qMedia.RecordCount) />
		<cfset ArraySet(aRoomOrder,1,qMedia.RecordCount,nTemp) />
	</cfif>
	<cfloop index="i" from="1" to="#ListLen(url.lRoomOrder)#">
		<cfloop index="j" from="1" to="#qMedia.RecordCount#">
			<cfif qMedia['Room'][j] eq Trim(ListGetAt(url.lRoomOrder,i))>
				<cfset aRoomOrder[j] = i />
			</cfif>
		</cfloop>
	</cfloop>
	<cfset QueryAddColumn(qMedia,"roomOrder",aRoomOrder) />

	<!--- reorder the query --->
	<cfquery name="qMedia2" dbtype="query">
		select * from qMedia
		order by show_on_tab DESC, sortOrder, roomOrder
	</cfquery>

	<!--- create the xml to be read by the swf organizer --->
	<cfxml variable="xmlImages">
<transaction><cfoutput>
	<cfloop query="qMedia2">
	<img url="http://spotlighthometours.com/images/tours/#url.tour#/photo_th_#qMedia2.mediaid#.jpg"
		largeImageURL="http://spotlighthometours.com/images/tours/#url.tour#/photo_640_#qMedia2.mediaid#.jpg"
		mediumImageURL="http://spotlighthometours.com/images/tours/#url.tour#/photo_400_#qMedia2.mediaid#.jpg"
		id="#qMedia2.mediaID#"
		position="#qMedia2.CurrentRow#"
		onstage="#qMedia2.show_on_tab#"
		description="#XMLFormat(qMedia2.description)#"
		room="#XMLFormat(qMedia2.room)#"
		tourIcon="#XMLFormat(qMedia2.tourIcon)#" />
	</cfloop>
</cfoutput></transaction>
	</cfxml>
<!--- <cfdump var="#qMedia2#" />
<cfdump var="#xmlImages#" /> --->
</cfsilent><cfcontent type="text/xml"><cfoutput>#xmlImages#</cfoutput>