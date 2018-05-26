<cfsilent>
	<cfparam name="url.action" default="">
	<cfparam name="url.pg" default="mailsender">
	<cfparam name="msg" default="">
	<cfparam name="errorMsg" default="">
	<cfparam name="jpegquality" default="70">

	<cfparam name="form.BROKERAGES" default="">
    <cfparam name="form.MAILDEMO" default="">
    <cfparam name="form.HTMLBODY" default="">
	
    
	<cfswitch expression="#url.action#">
		<!---=================================================================================================--->
		<cfcase value="sendmail">
			
            
            <cfif 1 eq 1>
            	<cfset url.pg = "mailsent" />
            <cfelse>
            	<cfset url.pg = "mailerror" />    
            </cfif>
		</cfcase>
		
	</cfswitch>
</cfsilent>

	<cfif url.pg eq "mailsent">
        <cfinclude template="_mailsent.cfm"> 
     <cfelseif url.pg eq "mailerror">
        <cfinclude template="_mailerror.cfm">        
    <cfelse>
        <cfinclude template="_#url.pg#.cfm">
    </cfif>
