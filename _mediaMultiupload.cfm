<cfsilent>
<!--- this is in a top level domain because it has to be in the same directory as the dccom folder, used by upload in the /user directory --->
<!--- this file serves as a buffer between the multifile uploaders and the normal image processing --->
<!--- needs to take the information provided and reformat as if it was the normal form post --->
<!--- <cfsavecontent variable="blah"><cfdump var="#getPageContext().getBuiltInScopes()#" /></cfsavecontent>
<cffile action="write" file="#expandPath('/users')#/blah.html" output="#blah#">
 --->
<cfparam name="msg" default="" />
<cfset mediaPath = cffile.serverDirectory />
<cfimage action="info" source="#mediaPath#\#cffile.serverFile#" structname="img" />

<cfif img.height lt 600 or img.width lt 800>
	<cffile action="delete" file="#mediaPath#\#cffile.serverFile#">
	<cfset msg = "The image was too small. Please upload an image of at least 800 pixels wide by 600 pixels tall.">
<cfelse>

<cfset form.tourID = attributes.tourID />
<cfset form.mediaType = 'photo' />

<!--- todo: this is a copy/paste of code from users.cfm action="uploadMedia". need to combine --->
	<cflock name="insertMedia" type="exclusive" timeout="5">
		<cfquery datasource="#request.db.dsn#">
			insert into media (tourID, mediaType, fileExt, room, description, createdOn, modifiedOn)
			values (
				<cfqueryparam cfsqltype="cf_sql_integer" value="#form.tourID#" />,
				<cfqueryparam cfsqltype="cf_sql_varchar" value='#form.mediaType#'/>,
				<cfqueryparam cfsqltype="cf_sql_varchar" value='#cffile.serverFileExt#'/>,
				<cfqueryparam cfsqltype="cf_sql_varchar" value=''/>,
				<cfqueryparam cfsqltype="cf_sql_varchar" value=''/>, #now()#, #now()#)
		</cfquery>
		<cfquery name="qMedia" datasource="#request.db.dsn#">
			select max(mediaID) as maxMediaID from media
		</cfquery>
	</cflock>

	<!--- need to create the required sizes --->
	<!--- 120x80 --->
	<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
	<cfset ImageResize(myImage,"120","80","highestQuality") />
	<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_th_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes" />

	<!--- 214x113 --->
	<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
	<cfset ImageResize(myImage,"215","143","highestPerformance") />
	<cfset ImageCrop(myImage,1,15,214,128) />
	<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_sm_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes" />

	<!--- 400x300 --->
	<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
	<cfset ImageResize(myImage,"400","300","highestQuality") />
	<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_400_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes" />

	<!--- 640x480 --->
	<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
	<cfset ImageResize(myImage,"640","480","highestQuality") />
	<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_640_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes" />

	<!--- 960x640 --->
	<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
	<cfset ImageResize(myImage,"960","640","highestPerformance") />
	<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_960_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes" />

	<!--- 600x400 --->
	<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
	<cfset ImageResize(myImage,"600","400","highestQuality") />
	<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_600_#qMedia.maxMediaID#.#cffile.serverFileExt#" overwrite="yes" />

	<cfset ext = cffile.serverFileExt />
	<cflog text="#mediaPath#\#form.mediaType#_high_#qMedia.maxMediaID#.#ext#">
	<cffile action="copy" source="#mediaPath#\#cffile.serverFile#"
			destination="#mediaPath#\#form.mediaType#_high_#qMedia.maxMediaID#.#ext#" />
	<cfset msg = "Your image was successfully uploaded and saved." />
</cfif>

</cfsilent><cfoutput>#msg#</cfoutput>
