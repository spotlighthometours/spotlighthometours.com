<cfparam name="Form.BROKERAGES" default="">
<cfparam name="Form.MAILDEMO" default="">
<cfparam name="Form.sendtoall" default="">
<cfparam name="Form.subject" default="">
<cfparam name="Form.htmlbody" default="">
<cfparam name="Form.unsubscribe" default="0">
<cfset unsubscribe = 0>
<cfif FORM.unsubscribe eq '1'>
<cfset unsubscribe = 1>
</cfif>

<cfif len(Form.htmlbody) gt 0>
	<cfset brokeragesLen = ListLen(Form.BROKERAGES) />
    <cfset usersLen = ListLen(Form.MAILDEMO) />
    
    <cfoutput>
		<cfif brokeragesLen gt 0 >
            <h3>Brokerages</h3>
            <cfset sent=0 />
            <cfset unsent=0 />
            <cfloop list="#Form.BROKERAGES#" index="i">
            	<cfquery datasource="#request.db.dsn#" name="qBrokerage">
                	select brokerageName from brokerages where brokerageid='#i#' and emailUnsuscribe='#unsubscribe#'
                </cfquery>
                <h4>#qBrokerage.brokerageName#</h4>
                <cfquery datasource="#request.db.dsn#" name="qBrokerageAgents">
                	select username from users where brokerageid='#i#' and emailUnsuscribe='#unsubscribe#'
                </cfquery>
                <cfloop query="qBrokerageAgents">
                	-#qBrokerageAgents.username#
                    <cfif REFindNocase("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.(([a-z]{2,3})|(aero|coop|info|museum|name))$", qBrokerageAgents.username)> 
					<cfmail 
						server="smtp.cfdynamics.com"
						to="#qBrokerageAgents.username#"
						from="Spotlight Home Tours<notifications@spotlighthometours.com>"
						subject="#form.subject#"
						type="html"
					>
					#form.htmlbody#
					</cfmail>
                    <cfset sent=sent+1 />
                     <b><font color="##00CC00">Email Sent</font></b>
        			<cfelse>
                    <b><font color="##FF0000">Invalid Email</font></b>
                    <cfset unsent=unsent+1 />
                    </cfif>
                    <br />
                 </cfloop>
                 <b><font color="##00CC00">Email Sent:#sent# </font></b>-<b><font color="##FF0000">Invalid Email:#unsent#</font></b>
            </cfloop>
        </cfif>
        
        
        <cfif usersLen gt 0 >
            <h3>Users</h3> 
            <cfset sent=0 />
            <cfset unsent=0 />
            <cfloop list="#Form.MAILDEMO#" index="u">
            	
                <cfquery datasource="#request.db.dsn#" name="qUser">
                	select username from users where userid='#u#' and emailUnsuscribe='#unsubscribe#' limit 1
                </cfquery>
                <cfloop query="qUser">
                	-#qUser.username#
                    <cfif REFindNocase("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.(([a-z]{2,3})|(aero|coop|info|museum|name))$", qUser.username)>
					<cfmail 
						server="smtp.cfdynamics.com"
						to="#qUser.username#"
						from="Spotlight Home Tours<notifications@spotlighthometours.com>"
						subject="#form.subject#"
						type="html"
					>
					#form.htmlbody#
					</cfmail>
                    <cfset sent=sent+1 />
                     <b><font color="##00CC00">Email Sent</font></b>
        			<cfelse>
                    <b><font color="##FF0000">Invalid Email</font></b>
                    <cfset unsent=unsent+1 />
                    </cfif>
                    <br />
                 </cfloop>
                 <b><font color="##00CC00">Email Sent:#sent# </font></b>-<b><font color="##FF0000">Invalid Email:#unsent#</font></b>
            </cfloop>
        </cfif>
        
        <cfif FORM.sendtoall eq '1'>
        <h3>Send to all</h3> 
            <cfquery  datasource="#request.db.dsn#" name="qUsers">
                SELECT username FROM users
                 WHERE length(username) > 5 and emailUnsuscribe='#unsubscribe#'
            </cfquery>
            <cfset sent=0 />
            <cfset unsent=0 />
            <cfloop query="qUsers">
                
                <cfif REFindNocase("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.(([a-z]{2,3})|(aero|coop|info|museum|name))$", qUsers.username)>
				<cfmail 
					server="smtp.cfdynamics.com"
					to="#qUser.username#"
					from="Spotlight Home Tours<notifications@spotlighthometours.com>"
					subject="#form.subject#"
					type="html"
				>
				#form.htmlbody#
				</cfmail>
                <cfset sent=sent+1 />
                
                <cfelse>
                
                <cfset unsent=unsent+1 />
                </cfif>
                
             </cfloop>
             <b><font color="##00CC00">Email Sent:#sent# </font></b>- <b><font color="##FF0000">Invalid Email:#unsent#</font></b>
        </cfif>
    </cfoutput>
    
    <br />Mail Successfully sent. <a href="/admin/mail/">Send another email</a>
<cfelse>
	<br />Mail Body was empty.<a href="/admin/mail/">Send another email</a>
</cfif>    