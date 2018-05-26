<CFSILENT>
	<CFPARAM name="url.action" default="">
	<CFPARAM name="url.pg" default="showSponsors">
	<CFPARAM name="strMessage" default="" />
    <CFPARAM name="msg" default="" />


</CFSILENT>
<CFFUNCTION name="generateFourPass" access="public" hint="get id" returntype="any">
       <CFSET NumbOfChars=4>
		<CFSET NewPass = "">
            <CFLOOP INDEX="RandAlhpaNumericPass"
            FROM="1" TO="#NumbOfChars#">
                <CFSET NewPass =NewPass&Mid('abcdefghijklmnopqrstuvwxyz123456789',RandRange('1','35'),'1')>
            </CFLOOP>
    <CFRETURN NewPass>
</CFFUNCTION>

<CFSWITCH expression="#url.action#">
	<CFCASE value="insertSponsor">
		<!--- check to see if the username already exists --->
	

		<CFIF len(trim(form.sponsorName)) eq 0>
			<CFSET msg = "Please make sure the mandatory fields are filled." />
		<CFELSE>
			<CFIF isDefined('form.mainSponsorId')>
            	<CFSET sponsorID=form.mainSponsorId />
            <CFELSE>
            	<CFSET sponsorID=0 />
            </CFIF>
			
			<CFSCRIPT>
				cphone    = form.cphone_1 & "." & form.cphone_2 & "." & form.cphone_3;
				nphone   = form.nphone_1 & "." & form.nphone_2 & "." & form.nphone_3;
				
			</CFSCRIPT>
            <CFSET myID = createUUID() />
			<CFQUERY datasource="#request.db.dsn#">
				insert into sponsorSub (id,
                                      mainSponsorId,
                                      Name,
                                      Address,
                                      City,
                                      State,
                                      zip,
                                      contactPhone,
                                      contactEmail,
                                      notifyPhone,
                                      notifyPhoneCarrier,
                                      notifyEmail,
                                      
                                      username,
                                      password,
                                       notifyEmailValid,
                                      dateCreated,
                                      active
                                    )
				values (
                		'#myID#',	
                		<cfqueryparam value="#trim(sponsorID)#" cfsqltype="cf_sql_int" maxlength="20">,
						<cfqueryparam value="#trim(form.sponsorName)#" cfsqltype="cf_sql_varchar" maxlength="128">,
						<cfqueryparam value="#trim(form.Address)#" cfsqltype="cf_sql_text" >,
						<cfqueryparam value="#trim(form.City)#" cfsqltype="cf_sql_varchar" maxlength="50">,
						<cfqueryparam value="#trim(form.State)#" cfsqltype="cf_sql_varchar" maxlength="2">,
						<cfqueryparam value="#trim(form.zip)#" cfsqltype="cf_sql_varchar" maxlength="15">,
						<cfqueryparam value="#trim(cphone)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(form.contactEmail)#" cfsqltype="cf_sql_varchar" maxlength="128">,
						<cfqueryparam value="#trim(nphone)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(form.notifyPhoneCarrier)#" cfsqltype="cf_sql_varchar" maxlength="50">,
						<cfqueryparam value="#trim(form.notifyEmail)#" cfsqltype="cf_sql_varchar" maxlength="50">,
                        <cfqueryparam value="#trim(form.username)#" cfsqltype="cf_sql_varchar" maxlength="20">,
                        <cfif len(form.password) lt 4>
                            	<cfset password=generateFourPass() />
                            	<cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4"/>,                            
                            <cfelse>
                            	<cfset password=form.password />
                            	<cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4"/>,	                            
                            </cfif>
						0,
						#now()#,
						<cfif isDefined('form.active')>1<cfelse>0</cfif>)
			</CFQUERY>
           
            <CFLOOP index="i" list="#form.keywords#">
                <CFQUERY name="qKeywords" datasource="#request.dsn#">
                    Update lonewolf_keywords
                    set sponsorID='#myID#'
                    WHERE id='#i#'
                    
                </CFQUERY>
	        </CFLOOP>
            
            
            <CFIF isDefined('form.sendEmail')>
             <CFIF len(trim(form.contactEmail)) gt 0>
             <CFMAIL to="#form.contactEmail#"
        from="Mobile Wolf Home Preview<info@spotlighthometours.com>"
        subject="Mobile Wolf Home Preview Sponsor Account Information" type="text">
             Congratulations!  You have been set up as a "Top Level Sponsor"  through Mobile Wolf Home Preview, the best mobile real estate search technology available today.  You can edit your account setting by going to: www.mobilewolfhp.com/sponsor

Your user name is:#form.username#
Your password is:#password#
			  </CFMAIL>
             </CFIF>
            
            </CFIF>

            
			<CFSET msg = "The user was successfully added.">
		</CFIF>
	</CFCASE>
	<CFCASE value="updateSponsor">
		<CFSCRIPT>
			cphone    = form.cphone_1 & "." & form.cphone_2 & "." & form.cphone_3;
			nphone   = form.nphone_1 & "." & form.nphone_2 & "." & form.nphone_3;
		</CFSCRIPT>

		<CFIF isDefined('form.mainSponsorId')>
            	<CFSET sponsorID=form.mainSponsorId />
            <CFELSE>
            	<CFSET sponsorID=0 />
            </CFIF>
		<CFQUERY datasource="#request.db.dsn#">
			update sponsorSub set
				mainSponsorId = <cfqueryparam value="#trim(sponsorID)#" cfsqltype="cf_sql_int" maxlength="20">,
				Name = <cfqueryparam value="#trim(form.sponsorName)#" cfsqltype="cf_sql_varchar" maxlength="128">,
				Address = <cfqueryparam value="#trim(form.Address)#" cfsqltype="cf_sql_text" >,
                City = <cfqueryparam value="#trim(form.City)#" cfsqltype="cf_sql_varchar" maxlength="50">,
				State = <cfqueryparam value="#trim(form.State)#" cfsqltype="cf_sql_varchar" maxlength="2">,
				zip = <cfqueryparam value="#trim(form.zip)#" cfsqltype="cf_sql_varchar" maxlength="15">,
				contactPhone = <cfqueryparam value="#trim(cphone)#" cfsqltype="cf_sql_varchar" maxlength="20">,
                contactEmail = <cfqueryparam value="#trim(form.contactEmail)#" cfsqltype="cf_sql_varchar" maxlength="128">,
				notifyPhone = <cfqueryparam value="#trim(nphone)#" cfsqltype="cf_sql_varchar" maxlength="20">,
				notifyPhoneCarrier =<cfqueryparam value="#trim(form.notifyPhoneCarrier)#" cfsqltype="cf_sql_varchar" maxlength="50">,
				notifyEmail = <cfqueryparam value="#trim(form.notifyEmail)#" cfsqltype="cf_sql_varchar" maxlength="128">,
				active = <cfif isDefined('form.active')>1<cfelse>0</cfif>,
                username = <cfqueryparam value="#trim(form.username)#" cfsqltype="cf_sql_varchar" maxlength="20">,
				<cfif len(form.password) lt 4>
					<cfset password=generateFourPass() />
                    password=<cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4"/>,
                <cfelse>
					<cfset password=form.password />
                    password=<cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4" />	 ,                           
                </cfif>

				dateModified = #now()#
			where id = '#form.sponsorid#'
		</CFQUERY>
        <CFQUERY name="qKeywordsUpdate" datasource="#request.dsn#">
            Update lonewolf_keywords
            set sponsorID=''
            WHERE sponsorID='#form.sponsorid#'
            
        </CFQUERY>
        <CFLOOP index="i" list="#form.keywords#">
            <CFQUERY name="qKeywords" datasource="#request.dsn#">
                Update lonewolf_keywords
                set sponsorID='#form.sponsorid#'
                WHERE id='#i#'
                
            </CFQUERY>
        </CFLOOP>
            
              <CFIF isDefined('form.sendEmail')>
             <CFIF len(trim(form.contactEmail)) gt 0>
             <CFMAIL to="#form.contactEmail#"
        from="Mobile Wolf Home Preview<info@spotlighthometours.com>"
        subject="Mobile Wolf Home Preview Sponsor Account Information" type="text">
             Congratulations!  You have been set up as a "Top Level Sponsor"  through Mobile Wolf Home Preview, the best mobile real estate search technology available today.  You can edit your account setting by going to: www.mobilewolfhp.com/sponsor

Your user name is:#form.username#
Your password is:#password#
			  </CFMAIL>
             </CFIF>
            
            </CFIF>
   
            
            
		<CFSET msg = "The sponsor was successfully updated.">
	</CFCASE>
    <CFCASE value="insertUser">
		<!--- check to see if the username already exists --->
		<CFQUERY name="qUserCheck" datasource="#request.dsn#">
			select userid from sponsor_officers
			where username = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.username)#" />
		</CFQUERY>

		<CFIF qUserCheck.RecordCount gt 0>
			<CFSET msg = "Username is already in use. Please use something else." />
		<CFELSE>
        <CFSET hasImage = false />
			<CFIF structKeyExists(form, "agentimage") and form.agentimage neq "">
            <CFSET hasImage = true />
				
                <CFSET destination = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\officerImages\")>
                <CFIF not directoryExists(destination)>
                    <CFDIRECTORY action="create" directory="#destination#" />
                </CFIF>

           	 <CFFILE action="upload" filefield="agentimage" destination="#destination#" nameConflict="makeUnique" result="upload" />
             
             <CFSET myImage=ImageNew("#destination#\#upload.serverFile#") />
		<CFSET ImageResize(myImage,"150","","highestPerformance") />
		
		<CFIMAGE source="#myImage#" action="write" destination="#destination#\#upload.serverfile#" overwrite="yes" />
      
            </CFIF>

			<CFSCRIPT>
				phone    = form.phone_1 & "." & form.phone_2 & "." & form.phone_3;
				phone2   = form.phone2_1 & "." & form.phone2_2 & "." & form.phone2_3;
				fax      = form.fax_1 & "." & form.fax_2 & "." & form.fax_3;
			</CFSCRIPT>
			<CFQUERY datasource="#request.db.dsn#" RESULT="insertID">
				insert into sponsor_officers (firstName, lastName, userType,  SponsorID,username, password, address, city, state, zipCode, phone, phonecarrier,phone2, fax, email, uri,dateCreated, dateModified,customMessage,image)
				values (<cfqueryparam value="#trim(form.firstName)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(form.lastName)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(form.userType)#" cfsqltype="cf_sql_varchar" maxlength="15">,						
						<cfqueryparam value="#trim(form.sponsorID)#" cfsqltype="cf_sql_char" maxlength="35">,
                        <cfqueryparam value="#trim(form.username)#" cfsqltype="cf_sql_varchar" maxlength="48">,
						<cfif len(form.password) lt 4>
							<cfset password=generateFourPass() />
                           <cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4"/>,
                        <cfelse>
                            <cfset password=form.password />
                            <cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4" />	 ,                           
                        </cfif>
						<cfqueryparam value="#trim(form.address)#" cfsqltype="cf_sql_varchar" maxlength="200">,
						<cfqueryparam value="#trim(form.city)#" cfsqltype="cf_sql_varchar" maxlength="50">,
						<cfqueryparam value="#trim(form.state)#" cfsqltype="cf_sql_varchar" maxlength="2">,
						<cfqueryparam value="#trim(form.zipCode)#" cfsqltype="cf_sql_varchar" maxlength="10">,
						<cfqueryparam value="#trim(phone)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#phonecarrier#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(phone2)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(fax)#" cfsqltype="cf_sql_varchar" maxlength="20">,
						<cfqueryparam value="#trim(form.email)#" cfsqltype="cf_sql_varchar" maxlength="255">,
						<cfqueryparam value="#replace(form.uri, "http://", "")#" cfsqltype="cf_sql_varchar" maxlength="255">,
						#now()#,
						#now()#,
                        <cfqueryparam value="#trim(form.customMessage)#" cfsqltype="cf_sql_text" maxlength="255">,
                         <CFIF hasImage eq true >
                         <cfqueryparam cfsqltype="cf_sql_varchar" value="#upload.serverfile#" />
                         <cfelse>
                         ''
                         </CFIF>
                        )
			</CFQUERY>
            
            
              
        <CFLOOP index="i" list="#form.keywordid#">
            <CFQUERY name="qKeywords" datasource="#request.db.dsn#">
                Update lonewolf_keywords
                set sponsorID='#form.sponsorid#',
                	officerID='#insertID.GENERATED_KEY#'
                WHERE id='#i#'
                
            </CFQUERY>
        </CFLOOP>
            
            
                <CFQUERY datasource="#request.db.dsn#" name="sponsor">
        	Select Name from sponsorSub WHERE id='#form.sponsorID#' limit 1
        </CFQUERY>
        
             <CFIF isDefined('form.sendEmail')>
             <CFIF len(trim(form.email)) gt 0>
             <CFMAIL to="#form.email#"
        from="Mobile Wolf Home Preview<info@spotlighthometours.com>"
        subject="Mobile Wolf Home Preview Sponsor Account Information" type="text">
             Congratulations! You have been selected by #sponsor.Name# as a marketing partner through Mobile Wolf Home Preview.  To upload you picture and edit your account go to: www.mobilewolfhp.com/LO

Your user name is:#form.username#
Your password is:#password#

			  </CFMAIL>
             </CFIF>
            
            </CFIF>
   
            
            
            
            <CFSET url.pg ="showSponsorOfficers" />
			<CFSET msg = "The Officer was successfully added.">
		</CFIF>
	</CFCASE>
	<CFCASE value="updateUser">
		<CFSCRIPT>
			phone    = form.phone_1 & "." & form.phone_2 & "." & form.phone_3;
			phone2   = form.phone2_1 & "." & form.phone2_2 & "." & form.phone2_3;
			fax      = form.fax_1 & "." & form.fax_2 & "." & form.fax_3;
		</CFSCRIPT>
		<CFSET hasImage = false />
        <CFIF structKeyExists(form, "agentimage") and form.agentimage neq "">
				
                <CFSET destination = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\officerImages\")>
                <CFIF not directoryExists(destination)>
                    <CFDIRECTORY action="create" directory="#destination#" />
                </CFIF>

         <CFFILE action="upload" filefield="agentimage" destination="#destination#" nameConflict="makeUnique" result="upload" />
         
                
             <!--- 214x113 --->
		<CFSET myImage=ImageNew("#destination#\#upload.serverFile#") />
		<CFSET ImageResize(myImage,"150","","highestPerformance") />
		
		<CFIMAGE source="#myImage#" action="write" destination="#destination#\#upload.serverfile#" overwrite="yes" />
             
         <CFSET hasImage = true />
        </CFIF>
	

		<CFQUERY datasource="#request.db.dsn#">
			update sponsor_officers set
				firstName = <cfqueryparam value="#form.firstName#" cfsqltype="cf_sql_varchar" maxlength="20">,
				lastName = <cfqueryparam value="#form.lastName#" cfsqltype="cf_sql_varchar" maxlength="20">,
				userType = <cfqueryparam value="#trim(form.userType)#" cfsqltype="cf_sql_varchar" maxlength="15">,
               
				
				sponsorID = <cfqueryparam value="#trim(form.sponsorID)#" cfsqltype="cf_sql_char" maxlength="35">,		
				username = <cfqueryparam value="#form.username#" cfsqltype="cf_sql_varchar" maxlength="48">,
				<cfif len(form.password) lt 4>
							<cfset password=generateFourPass() />
                         password=  <cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4"/>,
                        <cfelse>
                            <cfset password=form.password />
                          password=   <cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4" />	 ,                           
                        </cfif>
				address = <cfqueryparam value="#form.address#" cfsqltype="cf_sql_varchar" maxlength="200">,
				city = <cfqueryparam value="#form.city#" cfsqltype="cf_sql_varchar" maxlength="50">,
				state = <cfqueryparam value="#form.state#" cfsqltype="cf_sql_varchar" maxlength="2">,
				zipCode = <cfqueryparam value="#form.zipCode#" cfsqltype="cf_sql_varchar" maxlength="10">,
				phone = <cfqueryparam value="#phone#" cfsqltype="cf_sql_varchar" maxlength="20">,
				phonecarrier = <cfqueryparam value="#trim(phonecarrier)#" cfsqltype="cf_sql_varchar" maxlength="20">,
				phone2 = <cfqueryparam value="#phone2#" cfsqltype="cf_sql_varchar" maxlength="20">,
				fax = <cfqueryparam value="#fax#" cfsqltype="cf_sql_varchar" maxlength="20">,
				email = <cfqueryparam value="#form.email#" cfsqltype="cf_sql_varchar" maxlength="255">,
				uri = <cfqueryparam value="#replace(form.uri, "http://", "")#" cfsqltype="cf_sql_varchar" maxlength="255">,
				dateModified = #now()#,
                customMessage=<cfqueryparam value="#trim(form.customMessage)#" cfsqltype="cf_sql_text" maxlength="255">
                <cfif hasImage eq true>,
                image=<cfqueryparam cfsqltype="cf_sql_varchar" value="#upload.serverfile#" />
				</cfif>	
			where userID = #form.userID#
		</CFQUERY>
        
               
        <CFLOOP index="i" list="#form.keywordid#">
            <CFQUERY name="qKeywords" datasource="#request.db.dsn#">
                Update lonewolf_keywords
                set sponsorID='#form.sponsorid#',
                	officerID='#form.userID#'
                WHERE id='#i#'
                
            </CFQUERY>
        </CFLOOP>
        
        <CFQUERY datasource="#request.db.dsn#" name="sponsor">
        	Select Name from sponsorSub WHERE id='#form.sponsorID#' limit 1
        </CFQUERY>
        
             <CFIF isDefined('form.sendEmail')>
             <CFIF len(trim(form.email)) gt 0>
             <CFMAIL to="#form.email#"
        from="Mobile Wolf Home Preview<info@spotlighthometours.com>"
        subject="Mobile Wolf Home Preview Sponsor Account Information" type="text">
             Congratulations! You have been selected by #sponsor.Name# as a marketing partner through Mobile Wolf Home Preview.  To upload you picture and edit your account go to: www.mobilewolfhp.com/LO

Your user name is:#form.username#
Your password is:#password#

			  </CFMAIL>
             </CFIF>
            
            </CFIF>
   
            
        
         <CFSET url.pg ="showSponsorOfficers" />
		<CFSET msg = "The Officer was successfully updated.">
	</CFCASE>
	<CFCASE value="deleteUser">
		<CFQUERY datasource="#request.db.dsn#">
			delete from sponsor_officers where userID = #url.user#
		</CFQUERY>
        <CFSET url.pg ="showSponsorOfficers" />
		<CFSET msg = "The officer was successfully deleted.">
	</CFCASE>

	<CFCASE value="updateBanner">
        <CFQUERY name="qBannerChange" datasource="#request.dsn#">
            select
        k.id
		from lonewolf_keywords k
       where k.SponsorID = '#form.sponsorID#'
        </CFQUERY>
       
    
        <CFLOOP  query="qBannerChange">
        	<CFSET keywordBanner = form["#qBannerChange.id#"] />
            <CFQUERY datasource="#request.db.dsn#">
                Update lonewolf_keywords set bannerID='#keywordBanner#'
                 
                 where SponsorID = '#form.sponsorID#' and id= '#qBannerChange.id#' limit 1
            </CFQUERY>
           
         </CFLOOP>  
		<CFSET msg = "The Banner was successfully Saved.">
	</CFCASE>

	
</CFSWITCH>


<!--- :: Display the page :: --->
<HTML>
<HEAD>
<TITLE>Sponsor Admin</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
</HEAD>
<BODY>
<CFINCLUDE template="_subnav.cfm">
<CFOUTPUT><DIV CLASS="msg">#msg#</DIV></CFOUTPUT>
<CFINCLUDE template="_#url.pg#.cfm">
</BODY>
</HTML>