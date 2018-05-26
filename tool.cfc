<cfcomponent displayname="tool" output="false">

<cffunction name="init" returntype="tool">
	<cfreturn this />
</cffunction>

<!--- ======================================================================================== --->
<cffunction name="isDirection" output="false" returntype="boolean" hint="when given a string, returns true if its directional, false if it is not">
	<cfargument name="streetName" type="string" required="true" />

	<cfswitch expression="#arguments.streetName#">
		<cfcase value="N,S,E,W,North,South,East,West,Northeast,Northwest,Southeast,Southwest">
			<cfreturn true />
		</cfcase>
		<cfdefaultcase>
			<cfreturn false />
		</cfdefaultcase>
	</cfswitch>
</cffunction>

<!--- ======================================================================================== --->
<cffunction name="genStreetCode" output="false" returntype="string" hint="when given space delimited address, attempts to generate a T9 keyboard numeric code for the first 3 street letters">
	<cfargument name="strAddy" type="string" required="true" />

	<cfset var streetCode = "" />
	<cfset var strStreet = "" />

	<cfif ListLen(strAddy, " ") gt 1>
		<cfset strStreet = ListGetAt(strAddy,2," ") />
		<cfif isDirection(strStreet) AND
			ListLen(strAddy, " ") gt 2>
			<cfset strStreet = ListGetAt(strAddy,3," ") />
		</cfif>

		<cfif Len(strStreet) gte 3>
			<!--- making sure we remove any non alphanum chars from listing --->
			<cfset strStreet = Left(ReReplace(strStreet,"[^[:alpha:]]","","ALL"),3) />
			<cfif len(strStreet) eq 3>
				<!--- split out first 3 characters --->
				<cfset streetCode = application.stcT9Lookup[Left(strStreet,1)]
					& application.stcT9Lookup[mid(strStreet,2,1)]
					& application.stcT9Lookup[Right(strStreet,1)] />
			</cfif>
		</cfif>
	</cfif>

	<cfreturn streetCode />
</cffunction>


<!--- ======================================================================================== --->
<cfscript>
	function ISODateToTS(str) {
		var date = createDateTime(100,1,1,0,0,0);
		try {
			date = ParseDateTime(ReplaceNoCase(left(str,16),"T"," ","ALL"));
		}
		catch(Any e) {
			/* required to be here, but just letting the default value fall through */
		}
		return date;
	}
</cfscript>

</cfcomponent>