<cffunction name="pfuAddVariable" output="Yes">
<cfargument name="var">
<cfargument name="val">
<cfif REQUEST.pfuDisplaymode EQ "script">
	<cfoutput>jsProFlashUpload1.addVariable ( "#JSStringFormat( ARGUMENTS.var )#", "#JSStringFormat( ARGUMENTS.val )#");</cfoutput>
<cfelse>
	<cfparam name="ATTRIBUTES.flashvars" default="">
	<cfif Len( ATTRIBUTES.flashvars ) IS "">
		<cfset ATTRIBUTES.flashvars = ARGUMENTS.var & "=" & ARGUMENTS.val>
	<cfelse>
		<cfset ATTRIBUTES.flashvars = ListAppend( ATTRIBUTES.flashvars, ARGUMENTS.var & "=" & ARGUMENTS.val, "&" )>
	</cfif>
</cfif>
</cffunction>
<cfset REQUEST.pfuAddVariable = pfuAddVariable>
