<cfsetting requesttimeout="5000000">

<cfparam name="url.initiate" default="false">

<cfparam name="proc_errors.aFiles" default="#arrayNew(1)#">
<cfparam name="proc_errors.aMsg" default="#arrayNew(1)#">

<cfparam name="tours_dir" default="#expandPath("../../images/tours")#">
<cfparam name="media_dir" default="#expandPath("../../images/tours/dropbox")#">


<cfif url.initiate>
	<cfset counter = 0>
	<cfdirectory action="list" directory="#media_dir#" name="qDirectory">
	<!--- <cfdump var="#qDirectory#"> --->
	
	<cfloop query="qDirectory">
	
		<cftry>
		
			<cfset fileext = lCase(right(name, 3))>
			<cfset filename = left(name, len(name)-4)>
			<cfset RE = '(photo|walkthru|video)-([0-9]+)-([_a-zA-Z0-9 ]+)'>
		
			<cfset aFileInfo = listToArray(filename, '-')>
			<cfset aFileInfo[3] = reReplace(aFileInfo[3], '[0-9]*','','all')>
			
			<cfset mediatype = trim(aFileInfo[1])>
			<cfset tourid = trim(aFileInfo[2])>
			<cfset roomname = trim(aFileInfo[3])>
			
			<cfif not listContainsNoCase('photos,walkthru,video', mediatype)>
				<cfthrow message="Bad media type.">
			</cfif>
			
			<cfif not directoryExists("#tours_dir#/#tourid#/")>
				<cfdirectory action="create" directory="#tours_dir#/#tourid#">
			</cfif>
			
			
			<!---
			<cfoutput>
			#tourid#, #roomname#<br>
			</cfoutput>
			--->
			
			
			<cflock name="insertMedia" type="exclusive" timeout="5">
				<cfquery datasource="#request.dsn#">
					INSERT INTO media (tourID, mediaType, fileExt, room, tourIcon, createdOn, modifiedOn)
					VALUES (#tourid#, '#mediaType#', '#fileext#', '#roomname#', 0, now(), now())
			</cfquery>
				<cfquery name="qMedia" datasource="#request.dsn#">
					select max(mediaID) as maxMediaID from media
				</cfquery>
			</cflock>
			
			
			<cfif fileext eq 'jpg'>
				
			<CFX_DynamicImage
							NAME="IMAGE"
							ACTION ="SCALE"
							QUALITY="60"
							SRC = "#media_dir#/#qDirectory.name#"
							DST = "#tours_dir#/#tourid#/photo_th_#qMedia.maxMediaID#.jpg"
							PARAMETERS = "120,80,0,0">
			
			<CFX_DynamicImage
							NAME="IMAGE"
							ACTION="SCALE"
							QUALITY="80"
							SRC = "#media_dir#/#qDirectory.name#"
							DST = "#tours_dir#/#tourid#/photo_sm_#qMedia.maxMediaID#.jpg"
							PARAMETERS = "215,143,0,0">
							
				<CFX_DynamicImage
							NAME="IMAGE"
							ACTION = "SCALE"
							QUALITY="80"
							SRC = "#media_dir#/#qDirectory.name#"
							DST = "#tours_dir#/#tourid#/photo_640_#qMedia.maxMediaID#.jpg"
							PARAMETERS = "640,480,0,0">

				<CFX_DynamicImage
							NAME="IMAGE"
							ACTION = "SCALE"
							QUALITY="90"
							SRC = "#media_dir#/#qDirectory.name#"
							DST = "#tours_dir#/#tourid#/photo_800_#qMedia.maxMediaID#.jpg"
							PARAMETERS = "800,600,0,0">
				
				<CFX_DynamicImage
							NAME="IMAGE"
							ACTION = "SCALE"
							QUALITY="90"
							SRC = "#media_dir#/#qDirectory.name#"
							DST = "#tours_dir#/#tourid#/photo_400_#qMedia.maxMediaID#.jpg"
							PARAMETERS = "400,300,0,0">
							
				<CFX_DynamicImage
							NAME="IMAGE"
							ACTION = "Crop"
							SRC = "#tours_dir#/#tourid#/photo_sm_#qMedia.maxMediaID#.jpg"
							DST = "#tours_dir#/#tourid#/photo_sm_#qMedia.maxMediaID#.jpg"
							PARAMETERS = "1,15,215,128">	
			
					
			
			<cffile action="move" source="#media_dir#/#qDirectory.name#" destination="#tours_dir#/#tourid#/photo_high_#qMedia.maxMediaID#.jpg" nameconflict="overwrite">
			
			<cfelse>
				<cffile action="move" source="#media_dir#/#qDirectory.name#" destination="#tours_dir#/#tourid#/#mediatype#_#qMedia.maxMediaID#.#fileext#" nameconflict="overwrite">
			
			</cfif>
			
			<cfset counter = counter + 1>
		<cfcatch type="any">
			<cfset arrayAppend(proc_errors.aFiles, "#name# | #cfcatch.message#")>
			<!---<cfset arrayAppend(proc_errors.aMsg, cfcatch.message)>--->
		</cfcatch>
		</cftry>
	</cfloop>
	
	<cfif arrayLen(proc_errors.aFiles)>
		<p><cfoutput>#counter#</cfoutput> files were successfully imported. However, there were some errors.</p>
		<strong>These files could not be added. Please check the filenames and try again.</strong>
		<cfdump var="#proc_errors.aFiles#">
	<cfelse>
		<div style="color:#060;font-weight:bold;">Batch media file import successful! <cfoutput>#counter#</cfoutput> files were imported.</div>
	</cfif>
	
	
	
	
</cfif>
