<CFSILENT>
	<CFPARAM name="url.action" default="">
	<CFPARAM name="url.pg" default="showExistingBanners">
	<CFPARAM name="strMessage" default="" />


</CFSILENT>
<CFSWITCH expression="#url.action#">
	

	<CFCASE value="addBanner">
		<CFIF form.id eq "">
			<!--- we have a new plan. need to make sure that the keyword/800 numbers are unique --->
			<CFQUERY name="qLookup" datasource="#request.dsn#">
				select id
				from sponsor_banners
				where bannerName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.bannerName#" />
			</CFQUERY>

			<CFIF qLookup.RecordCount gt 0>
				<CFSET url.msg = "Duplicate Banner Name" />
				<CFSET url.pg = "addBanner" />
			<CFELSE>
				<!--- deal with uploading the image --->
				<CFIF structKeyExists(form, "agentfile") and form.agentfile neq "">
                	<CFSET destination = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\bannerImages\")>
					<CFIF not directoryExists(destination)>
						<CFDIRECTORY action="create" directory="#destination#" />
					</CFIF>

					<CFFILE action="upload" filefield="agentfile" destination="#destination#" nameConflict="makeUnique" result="upload" />
				</CFIF>
                
                <CFIF structKeyExists(form, "agentfilesm") and form.agentfilesm neq "">
					<CFSET destination = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\bannerImages\")>
					<CFIF not directoryExists(destination)>
						<CFDIRECTORY action="create" directory="#destination#" />
					</CFIF>

					<CFFILE action="upload" filefield="agentfilesm" destination="#destination#" nameConflict="makeUnique" result="uploadsm" />
				</CFIF>

				<!--- insert record into the database --->
				<CFSET myID = createUUID() />
				<!--- cftransaction would be a no brainer here, but not supported for MySQL ISAM tables --->
					<CFQUERY name="qInsert" datasource="#request.dsn#">
						insert into sponsor_banners ( id, bannerName,bannerImage,bannerImageSm,sponsorID)
						values (
							<cfqueryparam cfsqltype="cf_sql_char" value="#myID#" />,
							<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.bannerName#" />,
							<cfif structKeyExists(form, "agentfile") and form.agentfile neq "">
								<cfqueryparam cfsqltype="cf_sql_varchar" value="#upload.serverfile#" />,
							<cfelse>
								NULL,
							</cfif>
                            <cfif structKeyExists(form, "agentfilesm") and form.agentfilesm neq "">
								<cfqueryparam cfsqltype="cf_sql_varchar" value="#uploadsm.serverfile#" />,
							<cfelse>
								NULL,
							</cfif>
                            <cfqueryparam cfsqltype="cf_sql_char" value="#form.sponsorID#" />                           
						)
					</CFQUERY>

					
					
				<CFSET url.msg = "Your edits have been saved." />
				<CFSET url.pg = "showExistingBanners" />

			</CFIF>
		<CFELSE>
			<!--- do an update of the items --->
				<CFIF structKeyExists(form, "agentfile") and form.agentfile neq "">
					<CFSET destination = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\bannerImages\")>
					<CFIF not directoryExists(destination)>
						<CFDIRECTORY action="create" directory="#destination#" />
					</CFIF>

					<CFFILE action="upload" filefield="agentfile" destination="#destination#" nameConflict="makeUnique" result="upload" />
				</CFIF>
                
                <CFIF structKeyExists(form, "agentfilesm") and form.agentfilesm neq "">
					<CFSET destination = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\bannerImages\")>
					<CFIF not directoryExists(destination)>
						<CFDIRECTORY action="create" directory="#destination#" />
					</CFIF>

					<CFFILE action="upload" filefield="agentfilesm" destination="#destination#" nameConflict="makeUnique" result="uploadsm" />
				</CFIF>

				<!--- cftransaction would be a no brainer here, but not supported for MySQL ISAM tables --->
					<CFQUERY name="qInsert" datasource="#request.dsn#">
						update sponsor_banners
						set
							bannerName = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.bannerName#" />,
					
							<cfif structKeyExists(form, "agentfile") and form.agentfile neq "">
								bannerImage = <cfqueryparam cfsqltype="cf_sql_varchar" value="#upload.serverfile#" />,
							</cfif>
                            
                            <cfif structKeyExists(form, "agentfilesm") and form.agentfilesm neq "">
								bannerImageSm = <cfqueryparam cfsqltype="cf_sql_varchar" value="#uploadsm.serverfile#" />,
							</cfif>
                             sponsorID=<cfqueryparam cfsqltype="cf_sql_char" value="#form.sponsorID#" /> 
						where id = <cfqueryparam cfsqltype="cf_sql_char" value="#form.id#" />
					</CFQUERY>

					<!--- delete the previous providers in the multi table for this item --->
			

				<CFSET url.msg = "Your edits have been saved." />
				<CFSET url.pg = "showExistingBanners" />

		</CFIF>
		<!--- make sure that the  --->

	</CFCASE>
   
	<CFCASE value="deleteBanner">
		<!--- first, remove all the provider references associated with this keyword --->
		<CFQUERY name="qDelete" datasource="#request.dsn#">
			delete from sponsor_banners
			where id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.id#" />
		</CFQUERY>

		

		<CFSET url.msg = "The Banner has deleted." />
		<CFSET url.pg = "showExistingBanners" />
	</CFCASE>
  
</CFSWITCH>

<!--- :: Display the page :: --->
<HTML>
<HEAD>
<TITLE>Keyword Admin</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>
<BODY>
<CFINCLUDE template="_subnav.cfm">
<CFINCLUDE template="_#url.pg#.cfm">
</BODY>
</HTML>