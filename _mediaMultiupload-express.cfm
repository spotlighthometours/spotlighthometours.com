<cfsilent>
<!--- this is in a top level domain because it has to be in the same directory as the dccom folder, used by upload in /express directory --->
<!--- this file serves as a buffer between the multifile uploaders and the normal image processing --->
<!--- needs to take the information provided and reformat as if it was the normal form post --->

<cfparam name="msg" default="" />
<!--- <cfset mediaPath = cffile.serverDirectory /> --->
<cfset mediaPath = GetDirectoryFromPath(ExpandPath("\express\submissions\")) & session.user.id />
<cfset location = ExpandPath("\") />
<cfset mat =  location & "\mat.jpg" />
<cfset form.mediaType = 'photo' />

<cfloop index="i" from="1" to="#Form.FileCount#">
	<cfset strIDTemp = createUUID() />

	<!--- Get source file and save it to disk. --->
	<cffile action="UPLOAD" filefield="Thumbnail1_#i#"
		destination="#mediaPath#"
		nameconflict="overwrite">

	<cfset fileName="#cffile.serverFile#">


	<cffile action="copy" source="#mediaPath#\#cffile.serverFile#" destination="#mediaPath#\#form.mediaType#_high_#strIDTemp#.#cffile.serverFileExt#" />
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
<!---
	<cfif img.width lt 600>
		<cffile action="delete" file="#mediaPath#/#cffile.serverFile#">
		<cfset msg = "The image was too small. Please upload an image of at least 800 pixels wide by 600 pixels tall.">
	<cfelse>
 --->
	 	<!--- add the image to the session scope --->
		<cfif NOT StructKeyExists(session,"express")>
			<cfset session.express = StructNew() />
		</cfif>

		<cfif NOT StructKeyExists(session.express,"images")>
			<cfset session.express.images = ArrayNew(1) />
		</cfif>

		<cfset Details = StructNew() />
		<cfset Details.filePath = "#mediaPath#\#cffile.serverFile#" />
		<cfset Details.displayPath = "/express/submissions/#session.user.id#/#cffile.serverFile#" />
		<cfset Details.tempID = strIDTemp />
		<cfset Details.Description = "" />
		<cfset Details.height = img.height />
		<cfset Details.width = img.width />
		<cfset Details.Room = "" />
		<cfset Details.onTab = 1 />
		<cfset Details.Order = ArrayLen(session.express.images) />
		<cfset Details.id = ArrayLen(session.express.images) />

		<cfset ArrayAppend(session.express.images,Details) />

		<!--- 214x113 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"215","143","highestPerformance") />
		<cfset ImageCrop(myImage,1,15,214,128) />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_sm_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<!--- 400x300 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"400","300","highestPerformance") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_400_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<!--- 640x480 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"640","480","highestQuality") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_640_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<!--- 960x640 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"960","640","highestPerformance") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_960_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<!--- 600x400 --->
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"600","400","highestQuality") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_600_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />

		<cfset ext = cffile.serverFileExt />

		<!--- need to create the required sizes --->
		<!--- 120x80 --->
		<!---
		<cfset myImage=ImageNew("#mediaPath#\#cffile.serverFile#") />
		<cfset ImageResize(myImage,"120","80","highestPerformance") />
		<cfimage source="#myImage#" action="write" destination="#mediaPath#\#form.mediaType#_th_#strIDTemp#.#cffile.serverFileExt#" overwrite="yes" />
		 --->
		<!--- using the uploader's rendered thumbnail instead of cutting it up manually --->

		<!--- Get first thumbnail (the single thumbnail in this code sample) and save it to disk. --->
		<cffile action="UPLOAD" filefield="Thumbnail2_#i#"
			destination="#mediaPath#"
			nameconflict="MakeUnique">

		<!--- Rename thumbnail file so that it has .jpg extension --->
		<cffile action="rename"
			source="#mediaPath#/#serverFile#"
			destination="#mediaPath#/#form.mediaType#_th_#strIDTemp#.jpg">

		<cfset msg = "Your image was successfully uploaded and saved." />
<!--- 	</cfif> --->
</cfloop>
</cfsilent><cfoutput>#msg#</cfoutput>