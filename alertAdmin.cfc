<cfcomponent displayname="alertAdmin" output="false">

<cffunction name="init" returntype="alertAdmin">
	<cfreturn this />
</cffunction>

<cffunction name="emailError" hint="expects a cfcatch object from the callee, emails a notification" returntype="void">
	<cfargument name="myCFCatch" type="any" required="true" />
	<cfargument name="subject" type="string" required="false" default="An error has occurred on SpotlightHomeTours.com" />

	<cfset var strOutput = "" />
	<cfsavecontent variable="strOutput">
		<h2>Error Structure</h2>
		<cfdump var="#arguments.myCFCatch#" label="The Error Structure" />

		<h2>Form Scope<h2>
		<cfdump var="#form#" label="The Form Scope" />

		<h2>URL Scope<h2>
		<cfdump var="#url#" label="The URL Scope" />

		<h2>CGI Scope<h2>
		<cfdump var="#cgi#" label="The CGI Scope" />
	</cfsavecontent>

	<cfset strOutput = ReReplace(strOutput,"\s+"," ","ALL") />

	<cfmail to="#request.technical_contact#" subject="#arguments.subject#" from="info@spotlighthometours.com" type="html">
		<html><body>
		<h1>An Error Has Occurred on SpotlightHomeTours.com</h1>
		<cfoutput>#strOutput#</cfoutput>
		</body></html>
	</cfmail>

	<cfreturn />
</cffunction>


</cfcomponent>