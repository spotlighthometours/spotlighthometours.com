<CFSILENT>
	<CFPARAM name="url.action" default="">
	<CFPARAM name="url.pg" default="showExistingPlans">
	<CFPARAM name="strMessage" default="" />


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
	<CFCASE value="editProvider">
		<CFPARAM name="form.name" default="" />
		<CFIF form.name eq "">
			<CFSET url.msg = "The 'Name' is a Required Field. Please try again.">
			<CFSET url.provider = form.mls_service />
			<CFSET url.pg = "editProvider" />
		<CFELSE>
			<!--- if the id is present and "", need to insert, otherwise, update --->
			<CFIF form.id eq "">
				<CFQUERY name="qInsert" datasource="#request.dsn#">
					insert into listhub_providers (id,name,mls_service,description,region)
					values (
						<cfqueryparam cfsqltype="cf_sql_char" value="#createUUID()#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.name)#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.mls_service)#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.description)#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.region)#" />
					)
				</CFQUERY>
			<CFELSE>
				<CFQUERY name="qUpdate" datasource="#request.dsn#">
					update listhub_providers
					set
					name = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.name)#">,
					description = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.description)#" />,
					region = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.region)#" />,
					where id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.id)#" />
				</CFQUERY>
			</CFIF>

			<CFSET url.msg = "Your edits have been sucessfully saved.">
			<CFSET url.pg = "addProvider" />
		</CFIF>
	</CFCASE>
    <CFCASE value="editLonewolfProvider">
		<CFPARAM name="form.name" default="" />
		<CFIF form.name eq "">
			<CFSET url.msg = "The 'Name' is a Required Field. Please try again.">
			<CFSET url.provider = form.mls_service />
			<CFSET url.pg = "editlonewolfProvider" />
		<CFELSE>
			<!--- if the id is present and "", need to insert, otherwise, update --->
			<CFIF form.id eq "">
				<CFQUERY name="qInsert" datasource="#request.dsn#">
					insert into lonewolf_providers (id,name,mls_service,description,region)
					values (
						<cfqueryparam cfsqltype="cf_sql_char" value="#createUUID()#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.name)#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.mls_service)#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.description)#" />,
						<cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.region)#" />
					)
				</CFQUERY>
			<CFELSE>
				<CFQUERY name="qUpdate" datasource="#request.dsn#">
					update lonewolf_providers
					set
					name = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.name)#">,
					description = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.description)#" />,
					region = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.region)#" />
					where id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#trim(form.id)#" />
				</CFQUERY>
			</CFIF>

			<CFSET url.msg = "Your edits have been sucessfully saved.">
			<CFSET url.pg = "addProvider" />
		</CFIF>
	</CFCASE>

	<CFCASE value="addPlan">
		<CFIF form.id eq "">
			<!--- we have a new plan. need to make sure that the keyword/800 numbers are unique --->
			<CFQUERY name="qLookup" datasource="#request.dsn#">
				select id
				from listhub_keywords
				where keyword = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.keyword#" />
				and 800Number = <cfqueryparam cfsqltype="cf_sql_char" value="#form.800Number#" />
			</CFQUERY>

			<CFIF qLookup.RecordCount gt 0>
				<CFSET url.msg = "There is already someone listed for that keyword/800 Number. Please either delete that entry or use different information." />
				<CFSET url.pg = "addPlan" />
			<CFELSE>
				<!--- deal with uploading the image --->
				<CFIF structKeyExists(form, "agentfile") and form.agentfile neq "">
                
                    <CFSET destination = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\previewAgentPhotos\")>
                	<CFIF not directoryExists(destination)>
						<CFDIRECTORY action="create" directory="#destination#" />
					</CFIF>

					<CFFILE action="upload" filefield="agentfile" destination="#destination#" nameConflict="makeUnique" result="upload" />
				</CFIF>

				<!--- insert record into the database --->
				<CFSET myID = createUUID() />
				<!--- cftransaction would be a no brainer here, but not supported for MySQL ISAM tables --->
					<CFQUERY name="qInsert" datasource="#request.dsn#">
						insert into listhub_keywords ( id, keyword,800Number,CreatedOn,firstname,lastname,phone,email,userID_fk,brokerageid_fk,notifycarrier,agentImage,bannerImageID,password,notification)
						values (
							<cfqueryparam cfsqltype="cf_sql_char" value="#myID#" />,
							<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.keyword#" />,
							<cfqueryparam cfsqltype="cf_sql_char" value="#form.800Number#" />,
							<cfqueryparam cfsqltype="cf_sql_date" value="#now()#" />,
							<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.firstname#" />,
							<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.lastname#" />,
							<cfqueryparam cfsqltype="cf_sql_char" value="#form.phone#" />,
							<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.email#" />,
							<cfif structKeyExists(form,"userID") and form.userID neq "" and form.userID neq "-">
								<cfqueryparam cfsqltype="cf_sql_integer" value="#form.userID#" />,
							<cfelse>
								NULL,
							</cfif>
							<cfif structKeyExists(form,"brokerageID") and form.brokerageID neq "" and form.brokerageID neq "-">
								<cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageID#" />,
							<cfelse>
								NULL,
							</cfif>
							<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.carrierselect#" />,
							<cfif structKeyExists(form, "agentfile") and form.agentfile neq "">
								<cfqueryparam cfsqltype="cf_sql_varchar" value="#upload.serverfile#" />,
							<cfelse>
								NULL,
							</cfif>
                            <cfqueryparam cfsqltype="cf_sql_char" value="#form.bannerImageID#" />,
                            <cfif len(form.password) lt 4>
                           <cfqueryparam cfsqltype="cf_sql_varchar" value="#generateFourPass()#" maxlength="4"/>,                            
                            <cfelse>
                            <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.password#" maxlength="4"/>,	                            
                            </cfif>
                            <cfif structKeyExists(form,"notification")>
                            	<cfqueryparam cfsqltype="cf_sql_integer" value="1" />
                            <cfelse>
                          		<cfqueryparam cfsqltype="cf_sql_integer" value="0" />                          
                            </cfif>
                           
						)
					</CFQUERY>

					<!--- need to update the providers multi-table --->
					<CFLOOP index="i" list="#form.providers#">
						<CFQUERY name="qKeywords" datasource="#request.dsn#">
							insert into listhub_keywords_to_providers (id,keyword_fk,provider_fk)
							values (
								<cfqueryparam cfsqltype="cf_sql_char" value="#createUUID()#" />,
								<cfqueryparam cfsqltype="cf_sql_char" value="#myID#" />,
								<cfqueryparam cfsqltype="cf_sql_char" value="#i#" />
							)
						</CFQUERY>
					</CFLOOP>

				<CFSET url.msg = "Your edits have been saved." />
				<CFSET url.pg = "showExistingPlans" />
                
                  <CFIF structKeyExists(form,"emailProvider")>
                        <cfset mailAttributes = {
							server="smtp.gmail.com",
							username="info@spotlighthometours.com",
							password="Spotlight01",
							from="info@spotlighthometours.com",
							to="#form.email#",
							subject="Keyword:#form.keyword# information"
						}
						/>
						<cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
						Congratulations,
                                        Keyword #form.keyword# has been successfully created.
                        Below you can find information to change notificaiton phone,email etc.
                        website:http://www.spotlightpreview.com/admin
                        username:#form.keyword#
                        password:#password#
						</cfmail>
                 </CFIF>       

			</CFIF>
		<CFELSE>
			<!--- do an update of the items --->
				<CFIF structKeyExists(form, "agentfile") and form.agentfile neq "">
				   <CFSET destination = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\previewAgentPhotos\")>
                
					<CFIF not directoryExists(destination)>
						<CFDIRECTORY action="create" directory="#destination#" />
					</CFIF>

					<CFFILE action="upload" filefield="agentfile" destination="#destination#" nameConflict="makeUnique" result="upload" />
				</CFIF>

				<!--- cftransaction would be a no brainer here, but not supported for MySQL ISAM tables --->
					<CFQUERY name="qInsert" datasource="#request.dsn#">
						update listhub_keywords
						set
							keyword = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.keyword#" />,
							800Number = <cfqueryparam cfsqltype="cf_sql_char" value="#form.800Number#" />,
							UpdatedOn = <cfqueryparam cfsqltype="cf_sql_date" value="#now()#" />,
							firstname = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.firstname#" />,
							lastname = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.lastname#" />,
							phone = <cfqueryparam cfsqltype="cf_sql_char" value="#form.phone#" />,
							email = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.email#" />,
                            notifycarrier= <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.CarrierSelect#" />,
							<cfif structKeyExists(form,"brokerageID") and form.brokerageID neq "" and form.brokerageID neq "-">
								brokerageid_fk = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageID#" />,
								userid_fk = NULL,
							<cfelseif structKeyExists(form,"userID") and form.userID neq "" and form.userID neq "-">
								userid_fk = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.userID#" />,
								brokerageid_fk = NULL,
							</cfif>
							<cfif structKeyExists(form, "agentfile") and form.agentfile neq "">
								agentImage = <cfqueryparam cfsqltype="cf_sql_varchar" value="#upload.serverfile#" />,
							</cfif>
							notifycarrier = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.carrierselect#" />,
                            bannerImageID=<cfqueryparam cfsqltype="cf_sql_char" value="#form.bannerImageID#" />,
                             <cfif len(form.password) lt 4>
                            password=<cfqueryparam cfsqltype="cf_sql_varchar" value="#generateFourPass()#" maxlength="4"/>,
                            <cfelse>
                             password=<cfqueryparam cfsqltype="cf_sql_varchar" value="#form.password#" maxlength="4" />	 ,                           
                            </cfif>
                             <cfif structKeyExists(form,"notification")>
                            notification = <cfqueryparam cfsqltype="cf_sql_integer" value="1" />
                            <cfelse>
                             notification = <cfqueryparam cfsqltype="cf_sql_integer" value="0" />                           
                            </cfif>
                            
                           
						where id = <cfqueryparam cfsqltype="cf_sql_char" value="#form.id#" />
					</CFQUERY>

					<!--- delete the previous providers in the multi table for this item --->
					<CFQUERY name="qDelete" datasource="#request.dsn#">
						delete from listhub_keywords_to_providers
						where keyword_fk = <cfqueryparam cfsqltype="cf_sql_char" value="#form.id#" />
					</CFQUERY>

					<!--- need to update the providers multi-table --->
					<CFLOOP index="i" list="#form.providers#">
						<CFQUERY name="qKeywords" datasource="#request.dsn#">
							insert into listhub_keywords_to_providers (id,keyword_fk,provider_fk)
							values (
								<cfqueryparam cfsqltype="cf_sql_char" value="#createUUID()#" />,
								<cfqueryparam cfsqltype="cf_sql_char" value="#form.id#" />,
								<cfqueryparam cfsqltype="cf_sql_char" value="#i#" />
							)
						</CFQUERY>
					</CFLOOP>

				<CFSET url.msg = "Your edits have been saved." />
				<CFSET url.pg = "showExistingPlans" />
                
                

		</CFIF>
		<!--- make sure that the  --->

	</CFCASE>
    <CFCASE value="addLonewolfPlan">
		<CFIF form.id eq "">
			<!--- we have a new plan. need to make sure that the keyword/800 numbers are unique --->
			<CFQUERY name="qLookup" datasource="#request.dsn#">
				select id
				from lonewolf_keywords
				where keyword = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.keyword#" />
				and 800Number = <cfqueryparam cfsqltype="cf_sql_char" value="#form.800Number#" />
			</CFQUERY>
			
            <CFIF structKeyExists(form,"brokerageID") and  form.brokerageID eq "-">
            		<CFSET url.msg = "Brokerage cannot be left blank" />
				<CFSET url.pg = "addLonewolfPlan" />
            <CFELSE>
            
				<CFIF qLookup.RecordCount gt 0>
                    <CFSET url.msg = "There is already someone listed for that keyword/800 Number. Please either delete that entry or use different information." />
                    <CFSET url.pg = "addLonewolfPlan" />
                <CFELSE>
                    <!--- deal with uploading the image --->
                    <CFIF structKeyExists(form, "agentfile") and form.agentfile neq "">
                        <CFSET destination = expandPath("/images/previewAgentPhotos/")>
                        <CFIF not directoryExists(destination)>
                            <CFDIRECTORY action="create" directory="#destination#" />
                        </CFIF>
    
                        <CFFILE action="upload" filefield="agentfile" destination="#destination#" nameConflict="makeUnique" result="upload" />
                    </CFIF>
                    
                     <CFIF structKeyExists(form, "agenticon") and form.agenticon neq "">
                        <CFSET destination2 = expandPath("/images/previewAgentIcon/")>
                        <CFIF not directoryExists(destination2)>
                            <CFDIRECTORY action="create" directory="#destination2#" />
                        </CFIF>
    
                        <CFFILE action="upload" filefield="agenticon" destination="#destination2#" nameConflict="makeUnique" result="upload1" />
                    </CFIF>
    
                    <!--- insert record into the database --->
                    <CFSET myID = createUUID() />
                    <CFSET password="" />
                    <!--- cftransaction would be a no brainer here, but not supported for MySQL ISAM tables --->
                        <CFQUERY name="qInsert" datasource="#request.dsn#">
                            insert into lonewolf_keywords ( id, keyword,800Number,CreatedOn,firstname,lastname,phone,contactPhone,email,userID_fk,brokerageid_fk,notifycarrier,agentImage,bannerImageID,password,notification,exclusive,shortcode,leaddistribution,agenticon)
                            values (
                                <cfqueryparam cfsqltype="cf_sql_char" value="#myID#" />,
                                <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.keyword#" />,
                                <cfqueryparam cfsqltype="cf_sql_char" value="#form.800Number#" />,
                                <cfqueryparam cfsqltype="cf_sql_date" value="#now()#" />,
                                <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.firstname#" />,
                                <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.lastname#" />,
                                <cfqueryparam cfsqltype="cf_sql_char" value="#form.phone#" />,
                                    <cfqueryparam cfsqltype="cf_sql_char" value="#form.contactphone#" />,
                                <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.email#" />,
                                <cfif structKeyExists(form,"userID") and form.userID neq "" and form.userID neq "-">
                                    <cfqueryparam cfsqltype="cf_sql_integer" value="#form.userID#" />,
                                <cfelse>
                                    NULL,
                                </cfif>
                                <cfif structKeyExists(form,"brokerageID") and form.brokerageID neq "" and form.brokerageID neq "-">
                                    <cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageID#" />,
                                <cfelse>
                                    NULL,
                                </cfif>
                                <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.carrierselect#" />,
                                <cfif structKeyExists(form, "agentfile") and form.agentfile neq "">
                                    <cfqueryparam cfsqltype="cf_sql_varchar" value="#upload.serverfile#" />,
                                <cfelse>
                                    NULL,
                                </cfif>
                                <cfqueryparam cfsqltype="cf_sql_char" value="#form.bannerImageID#" />,
                                <cfif len(form.password) lt 4>
                                    <cfset password=generateFourPass() />
                                    <cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4"/>,                            
                                <cfelse>
                                    <cfset password=form.password />
                                    <cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4"/>,	                            
                                </cfif>
                                <cfif structKeyExists(form,"notification")>
                                    <cfqueryparam cfsqltype="cf_sql_integer" value="1" />,
                                <cfelse>
                                    <cfqueryparam cfsqltype="cf_sql_integer" value="0" />  ,                        
                                </cfif>
                                 <cfif structKeyExists(form,"exclusive")>
                                    <cfqueryparam cfsqltype="cf_sql_integer" value="1" />,
                                <cfelse>
                                    <cfqueryparam cfsqltype="cf_sql_integer" value="0" /> ,                         
                                </cfif>
                                <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.shortcode#" maxlength="10"/>	,
                                 <cfif structKeyExists(form,"leaddistribution")>
                                    <cfqueryparam cfsqltype="cf_sql_integer" value="1" />,
                                <cfelse>
                                    <cfqueryparam cfsqltype="cf_sql_integer" value="0" /> ,                         
                                </cfif>
                                <cfif structKeyExists(form, "agenticon") and form.agenticon neq "">
                                    <cfqueryparam cfsqltype="cf_sql_varchar" value="#upload1.serverfile#" />
                                <cfelse>
                                    NULL
                                </cfif>
                                
                               
                            )
                        </CFQUERY>
    
                        
                    <CFSET url.msg = "Your edits have been saved." />
                    <CFSET url.pg = "showExistingLoneWolfPlans" />
                    
                    <CFIF structKeyExists(form,"emailProvider")>
                            <cfset mailAttributes = {
								server="smtp.gmail.com",
								username="info@spotlighthometours.com",
								password="Spotlight01",
								from="info@spotlighthometours.com",
								to="#form.email#",
								subject="Keyword:#form.keyword# information"
							}
							/>
							<cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
							Congratulations,
                                            Keyword '#form.keyword#' has been successfully created.
                            Below you can find information to change notificaiton phone,email etc.
                            website:http://tx2me.com/admin/
                            username:#form.keyword#
                            password:#password#
							</cfmail>
                     </CFIF>       
				</CFIF>
			</CFIF>
		<CFELSE>
			<!--- do an update of the items --->
				<CFIF structKeyExists(form, "agentfile") and form.agentfile neq "">
					    <CFSET destination = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\previewAgentPhotos\")>
					<CFIF not directoryExists(destination)>
						<CFDIRECTORY action="create" directory="#destination#" />
					</CFIF>

					<CFFILE action="upload" filefield="agentfile" destination="#destination#" nameConflict="makeUnique" result="upload" />
				</CFIF>
                
                                    
                     <CFIF structKeyExists(form, "agenticon") and form.agenticon neq "">
                         <CFSET destination2 = GetDirectoryFromPath("D:\websites\spotlightpreview\public\images\previewAgentIcon\")>
                
                        <CFIF not directoryExists(destination2)>
                            <CFDIRECTORY action="create" directory="#destination2#" />
                        </CFIF>
    
                        <CFFILE action="upload" filefield="agenticon" destination="#destination2#" nameConflict="makeUnique" result="upload1" />
                    </CFIF>

            
            <CFIF structKeyExists(form,"brokerageID") and  form.brokerageID eq "-">
                <CFSET url.msg = "Brokerage cannot be left blank" />
            <CFSET url.pg = "showExistingLoneWolfPlans" />
            <CFELSE>
				 <CFSET password="" />
				<!--- cftransaction would be a no brainer here, but not supported for MySQL ISAM tables --->
					<CFQUERY name="qInsert" datasource="#request.dsn#">
						update lonewolf_keywords
						set
							keyword = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.keyword#" />,
							800Number = <cfqueryparam cfsqltype="cf_sql_char" value="#form.800Number#" />,
							UpdatedOn = <cfqueryparam cfsqltype="cf_sql_date" value="#now()#" />,
							firstname = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.firstname#" />,
							lastname = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.lastname#" />,
							phone = <cfqueryparam cfsqltype="cf_sql_char" value="#form.phone#" />,
                            contactphone=	<cfqueryparam cfsqltype="cf_sql_char" value="#form.contactphone#" />,
							email = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.email#" />,
                            notifycarrier= <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.CarrierSelect#" />,
							<cfif structKeyExists(form,"brokerageID") and form.brokerageID neq "" and form.brokerageID neq "-">
								brokerageid_fk = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.brokerageID#" />,
							</cfif>
							<cfif structKeyExists(form,"userID") and form.userID neq "" and form.userID neq "-">
								userid_fk = <cfqueryparam cfsqltype="cf_sql_integer" value="#form.userID#" />,
							<cfelse>
                                    userid_fk =NULL,
							</cfif>
							<cfif structKeyExists(form, "agentfile") and form.agentfile neq "">
								agentImage = <cfqueryparam cfsqltype="cf_sql_varchar" value="#upload.serverfile#" />,
							</cfif>
                            
							<cfif structKeyExists(form, "agenticon") and form.agenticon neq "">
								agenticon = <cfqueryparam cfsqltype="cf_sql_varchar" value="#upload1.serverfile#" />,
							</cfif>
                            
							notifycarrier = <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.carrierselect#" />,
                             bannerImageID=<cfqueryparam cfsqltype="cf_sql_char" value="#form.bannerImageID#" />,
                             <cfif len(form.password) lt 4>
                              <cfset password=generateFourPass() />
                            password=<cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4"/>,
                            <cfelse>
                            	<cfset password=form.password />
                             password=<cfqueryparam cfsqltype="cf_sql_varchar" value="#password#" maxlength="4" />	 ,                           
                            </cfif>
                             <cfif structKeyExists(form,"notification")>
                            notification = <cfqueryparam cfsqltype="cf_sql_integer" value="1" />,
                            <cfelse>
                             notification = <cfqueryparam cfsqltype="cf_sql_integer" value="0" />,                           
                            </cfif>
                            <cfif structKeyExists(form,"exclusive")>
                            exclusive = <cfqueryparam cfsqltype="cf_sql_integer" value="1" />,
                            <cfelse>
                             exclusive = <cfqueryparam cfsqltype="cf_sql_integer" value="0" />,                           
                            </cfif>
                            shortcode= <cfqueryparam cfsqltype="cf_sql_varchar" value="#form.shortcode#" maxlength="10"/>,
							<cfif structKeyExists(form,"leaddistribution")>
                                leaddistribution=<cfqueryparam cfsqltype="cf_sql_integer" value="1" />
                            <cfelse>
                                leaddistribution=<cfqueryparam cfsqltype="cf_sql_integer" value="0" />                         
                            </cfif>

                            
                            
						where id = <cfqueryparam cfsqltype="cf_sql_char" value="#form.id#" />
					</CFQUERY>

					<!--- delete the previous providers in the multi table for this item --->
					<CFQUERY name="qDelete" datasource="#request.dsn#">
						delete from lonewolf_keywords_to_providers
						where keyword_fk = <cfqueryparam cfsqltype="cf_sql_char" value="#form.id#" />
					</CFQUERY>

					<!--- need to update the providers multi-table --->
					

					<CFSET url.msg = "Your edits have been saved." />
                    <CFSET url.pg = "showExistingLoneWolfPlans" />
                    
                    <CFIF structKeyExists(form,"emailProvider")>
                            <cfset mailAttributes = {
								server="smtp.gmail.com",
								username="info@spotlighthometours.com",
								password="Spotlight01",
								from="info@spotlighthometours.com",
								to="#form.email#",
								subject="Keyword:#form.keyword# information"
							}
							/>
							<cfmail port="465" useSSL="true" useTLS="true" attributeCollection="#mailAttributes#">
							Congratulations,
                                            Keyword '#form.keyword#' has been successfully created.
                            Below you can find information to change notificaiton phone,email etc.
                            website:http://tx2me.com/admin/
                            username:#form.keyword#
                            password:#password#
							</cfmail>
                     </CFIF>       
				</CFIF>
		</CFIF>
		<!--- make sure that the  --->

	</CFCASE>
	<CFCASE value="deleteKeyword">
		<!--- first, remove all the provider references associated with this keyword --->
		<CFQUERY name="qDelete" datasource="#request.dsn#">
			delete from listhub_keywords_to_providers
			where keyword_fk = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.keyword#" />
		</CFQUERY>

		<!--- now remove the keyword itself --->
		<CFQUERY name="qDelete" datasource="#request.dsn#">
			delete from listhub_keywords
			where id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.keyword#" />
		</CFQUERY>

		<CFSET url.msg = "The keyword reference has been deleted." />
		<CFSET url.pg = "showExistingPlans" />
	</CFCASE>
    <CFCASE value="deleteLonewolfKeyword">
		<!--- first, remove all the provider references associated with this keyword --->
		<CFQUERY name="qDelete" datasource="#request.dsn#">
			delete from lonewolf_keywords_to_providers
			where keyword_fk = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.keyword#" />
		</CFQUERY>

		<!--- now remove the keyword itself --->
		<CFQUERY name="qDelete" datasource="#request.dsn#">
			delete from lonewolf_keywords
			where id = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.keyword#" />
		</CFQUERY>

		<CFSET url.msg = "The keyword reference has been deleted." />
		<CFSET url.pg = "showExistingLoneWolfPlans" />
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