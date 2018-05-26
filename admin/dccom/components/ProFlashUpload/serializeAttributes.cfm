<cfparam name="ATTRIBUTES.uid">
<cfparam name="ATTRIBUTES.method"><!--- save/load --->
<cfparam name="ATTRIBUTES.scope" default="session"><!--- SESSION/APPLICATION --->

<!--- Create a new SESSION scope structure to access the variables --->
<cfset collectionName = ATTRIBUTES.scope & ".">
<cfset collectionName = collectionName & "ProFlashUpload">
<cfset collectionName = collectionName & ".v" & ATTRIBUTES.uid>

<cfswitch expression="#UCASE(ATTRIBUTES.method)#">

	<!--- START: SAVE ALL ATTRIBUTE DATA FOR RE-LOAD IN IFRAME PAGES --->
	<cfcase value="SAVE">
	
		<!--- If storing at application level, cycle through all old files looking for memory objects that have expired --->
		<cfif ATTRIBUTES.scope EQ "APPLICATION" AND StructKeyExists(APPLICATION,CALLER.ATTRIBUTES.component)>
		
			<cfset CALLER.ATTRIBUTES.ts = now()>
			<CFSET theTimeout = CreateTimespan(0,2,0,0)>
			
			<!--- Remove any older session from application memory --->
			<cfset fullMemList = Evaluate(ATTRIBUTES.scope&"."&CALLER.ATTRIBUTES.component)>
			<CFLOOP collection="#fullMemList#" item="savedRef">
			
				<cfif StructKeyExists( fullMemList[savedRef][1], "ts" )>
					<CFSCRIPT>
					onlineSince = fullMemList[savedRef][1].ts;
					if(DateCompare(onlineSince+theTimeout, Now()) IS NOT 1) {
						//delete this old session
						StructDelete(fullMemList, savedRef);
					}
					</CFSCRIPT>
				<cfelse>
					<cfset StructDelete(fullMemList, savedRef)>
				</cfif>
			
			</CFLOOP>

		</cfif>
	
		<cfset evalString = "#collectionName# = arrayNew(1)">
		<cfscript>Evaluate(evalString);</cfscript>
		
		<!--- Append the current attributes to the array --->
		
		<cfset evalString = "arrayAppend(#collectionName#,caller.attributes)">
		<cfscript>Evaluate(evalString);</cfscript>
		
		
		<!--- Resave of assocAttribs --->
		<cfif isdefined("CALLER.assocAttribs")>
			<cfset evalString = "arrayAppend(#collectionName#,CALLER.assocAttribs)">
		<cfelse>
			<!--- Append all the sub tag attribute information to the array --->
			<cfparam name="CALLER.thisTag.assocAttribs" default="#ArrayNew(1)#">
			<cfset evalString = "arrayAppend(#collectionName#,caller.thisTag.assocAttribs)">
		</cfif>
		
		<cfscript>Evaluate(evalString);</cfscript>
		
		<cfset CALLER.collectionName = collectionName>
		
	</cfcase>
	<!--- END: SAVE ALL ATTRIBUTE DATA --->

	<cfcase value="LOAD">
	
		
	<!--- START: LOAD ALL ATTRIBUTE DATA FOR RE-LOAD IN IFRAME PAGES --->
		<!--- Check that the session hasn't time out --->
		<cfif NOT isdefined("#collectionName#")>
			<cfthrow message="Component Session Timeout" detail="The current proFlashUpload session has timed out. Please reload the interface and try again. Reference '#collectionName#' not found.">
		</cfif>
	
		<!--- Restore the variables --->
		<CFSET CALLER.ATTRIBUTES = evaluate("#collectionName#[1]")>
		<CFSET CALLER.ASSOCATTRIBS = evaluate("#collectionName#[2]")>
		
		<!--- If storing at APPLICATION LEVEL, update the timestamp because we are still using it! --->
		<cfif ATTRIBUTES.scope EQ "APPLICATION">
			<cfset CALLER.ATTRIBUTES.ts = now()>
		</cfif>
		
	<!--- END: LOAD ALL ATTRIBUTE DATA --->
	</cfcase>
	
</cfswitch>
<!--- 
<cfdump label="#ATTRIBUTES.scope#.#CALLER.ATTRIBUTES.component#" var="#Evaluate( ATTRIBUTES.scope & "." & CALLER.ATTRIBUTES.component )#">
 --->