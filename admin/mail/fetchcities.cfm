<!---[{"caption":"Manuel Mujica Lainez","value":4},{"caption":"Gustavo Nielsen","value":3},{"caption":"Silvina Ocampo","value":3},{"caption":"Victoria Ocampo", "value":3},{"caption":"Hector German Oesterheld", "value":3},{"caption":"Olga Orozco", "value":3},{"caption":"Juan L. Ortiz", "value":3},{"caption":"Alicia Partnoy", "value":3},{"caption":"Roberto Payro", "value":3},{"caption":"Ricardo Piglia", "value":3},{"caption":"Felipe Pigna", "value":3},{"caption":"Alejandra Pizarnik", "value":3},{"caption":"Antonio Porchia", "value":3},{"caption":"Juan Carlos Portantiero", "value":3},{"caption":"Manuel Puig", "value":3},{"caption":"Andres Rivera", "value":3},{"caption":"Mario Rodriguez Cobos", "value":3},{"caption":"Arturo Andres Roig", "value":3},{"caption":"Ricardo Rojas", "value":3}]--->

<cfparam name="url.tag" default="a">
<!--- Generate a clean feed by suppressing white space and debugging
         information. --->
<cfprocessingdirective suppresswhitespace="yes">
<cfsetting showdebugoutput="no">
<!---<cfcontent type="application/x-javascript">
--->


<cfif find(",",url.tag)>

 <cfset result = ListToArray(url.tag, ",") />
 <cfset fname=trim(result[1]) />
 <cfset lname=trim(result[2]) />
 
	<cfquery name="qCities" datasource="#request.db.dsn#"  >
			select c.city,c.state
            from cities c 
            where city like '#fname#%' and state like '#lname#%'
			order by city asc
            limit 5
		</cfquery>
<cfelse>


<!--- Generate the JSON feed as a JavaScript function. --->


<cfquery name="qCities" datasource="#request.db.dsn#"  >
				select c.city,c.state
            from cities c 
            where city like '#url.tag#%'
			order by city asc
            limit 5
		</cfquery>
</cfif>        
<cfoutput>[</cfoutput>
<cfsavecontent variable="qInsert3">
<cfoutput query="qCities">
{"name":"#qCities.city#,#qCities.state #","brokerage":"","value":"#qCities.city#,#qCities.state#"},
</cfoutput>
</cfsavecontent>
<cfif qCities.RecordCount eq 1 >
	<cfoutput>#trim(qInsert3)#]</cfoutput>
<cfelseif qCities.RecordCount eq 0 >
	<cfoutput>]</cfoutput>
<cfelse>
	<cfoutput>#left(trim(qInsert3), len(trim(qInsert3))-1)#]</cfoutput>		
</cfif>
</cfprocessingdirective>
