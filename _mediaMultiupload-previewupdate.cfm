<cfsilent>
<!--- this is in a top level domain because it has to be in the same directory as the dccom folder, used by upload in /express directory --->
<!--- this file serves as a buffer between the multifile uploaders and the normal image processing --->
<!--- needs to take the information provided and reformat as if it was the normal form post --->

<cfparam name="url.tourID" default="" />
<cfparam name="url.mediaType" default = 'photo' />
<cfparam name="msg" default="" />
<!--- <cfset mediaPath = cffile.serverDirectory /> --->
<cfset mediaPath = ExpandPath("\images\tours\") & url.tourID & "\" />
<cfset location = ExpandPath("\") />
<cfset mat =  location & "\mat.jpg" />
<cfset url.mediaType = 'photo' />

<cfloop index="i" from="1" to="#Form.FileCount#">
	<!--- Get source file and save it to disk. --->
	<cffile action="UPLOAD" filefield="Thumbnail1_#i#"
		destination="#mediaPath#"
		nameconflict="overwrite">

	<!--- todo: this is a copy/paste of code from users.cfm action="uploadMedia". need to combine --->
	<cflock name="insertMedia" type="exclusive" timeout="5">
		<cfquery datasource="#request.db.dsn#">
			insert into media (tourID, mediaType, fileExt, room, description, createdOn, modifiedOn)
			values (
				<cfqueryparam cfsqltype="cf_sql_integer" value="#url.tourID#" />,
				<cfqueryparam cfsqltype="cf_sql_varchar" value='#url.mediaType#'/>,
				<cfqueryparam cfsqltype="cf_sql_varchar" value='#cffile.serverFileExt#'/>,
				<cfqueryparam cfsqltype="cf_sql_varchar" value=''/>,
				<cfqueryparam cfsqltype="cf_sql_varchar" value=''/>, #now()#, #now()#)
		</cfquery>
		<cfquery name="qMedia" datasource="#request.db.dsn#">
			select max(mediaID) as maxMediaID from media
		</cfquery>
	</cflock>

	<cfset strIDTemp = qMedia.maxMediaID />


	<cfset fileName="#cffile.serverFile#">

	<cffile action="copy" source="#mediaPath#\#cffile.serverFile#" destination="#mediaPath#\#url.mediaType#_high_#strIDTemp#.#cffile.serverFileExt#" />
	<cfimage action="info" source="#mediaPath#\#cffile.serverFile#" structname="img" />

	<cfif img.height gt img.width>
		<!--- save off the orignal file for use later --->
		<cfimage source="#mediaPath#\#cffile.serverFile#" name="topImage">

		<cfset newX = (img.height + 2) * 1.5 />

		<cfimage source="#mat#" name="myImage">

		<!--- Turn on antialiasing to improve image quality. --->
		<cfset ImageSetAntialiasing(myImage,"on")>

		<!--- compute the borders  --->
		<cfset myX = (newX - img.width) / 2 />
		<cfset myY = 1 />

		<!--- Overlay the top image on the background image. --->
		<cfset ImagePaste(myImage,topImage,myX, myY) />

		<!--- now need to crop any extra y excess at the bottom --->
		<cfset ImageCrop(myImage,0,0,newX,img.height + 2) />

		<!--- write out the image. --->
		<cfimage action="write" destination="#mediaPath#\#cffile.serverFile#" source="#myImage#" overwrite = "true" />
		<cfimage action="info" source="#mediaPath#\#cffile.serverFile#" structname="img" />
	</cfif>



		<!--- 214x113 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"215","143","highestPerformance") />
		<cfset ImageCrop(myImage,1,15,214,128) />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#url.mediaType#_sm_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<!--- 400x300 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"400","300","highestPerformance") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#url.mediaType#_400_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<!--- 640x480 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"640","480","highestQuality") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#url.mediaType#_640_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<!--- 960x640 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"960","640","highestPerformance") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#url.mediaType#_960_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<!--- 600x400 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"600","400","highestQuality") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#url.mediaType#_600_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<cfset ext = cffile.serverFileExt />

		<!--- need to create the required sizes --->
		<!--- 120x80 --->
		<!---
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"120","80","highestPerformance") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#url.mediaType#_th_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />
		 --->
		<!--- using the uploader's rendered thumbnail instead of cutting it up manually --->

		<!--- Get first thumbnail (the single thumbnail in this code sample) and save it to disk. --->
		<cffile action="UPLOAD" filefield="Thumbnail2_#i#"
			destination="#mediaPath#"
			nameconflict="MakeUnique">

		<!--- Rename thumbnail file so that it has .jpg extension --->
		<cffile action="rename"
			source="#mediaPath#/#serverFile#"
			destination="#mediaPath#/#url.mediaType#_th_#strIDTemp#.jpg">

		<cfset msg = "Your image was successfully uploaded and saved." />
</cfloop>
</cfsilent><cfoutput>#msg#</cfoutput>