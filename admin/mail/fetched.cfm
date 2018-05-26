<!---[{"caption":"Manuel Mujica Lainez","value":4},{"caption":"Gustavo Nielsen","value":3},{"caption":"Silvina Ocampo","value":3},{"caption":"Victoria Ocampo", "value":3},{"caption":"Hector German Oesterheld", "value":3},{"caption":"Olga Orozco", "value":3},{"caption":"Juan L. Ortiz", "value":3},{"caption":"Alicia Partnoy", "value":3},{"caption":"Roberto Payro", "value":3},{"caption":"Ricardo Piglia", "value":3},{"caption":"Felipe Pigna", "value":3},{"caption":"Alejandra Pizarnik", "value":3},{"caption":"Antonio Porchia", "value":3},{"caption":"Juan Carlos Portantiero", "value":3},{"caption":"Manuel Puig", "value":3},{"caption":"Andres Rivera", "value":3},{"caption":"Mario Rodriguez Cobos", "value":3},{"caption":"Arturo Andres Roig", "value":3},{"caption":"Ricardo Rojas", "value":3}]--->

<cfparam name="url.tag" default="a">
<!--- Generate a clean feed by suppressing white space and debugging
         information. --->
<cfprocessingdirective suppresswhitespace="yes">
<cfsetting showdebugoutput="no">
<cfcontent type="application/x-javascript">



<cfif find(" ",url.tag)>

 <cfset result = ListToArray(url.tag, " ") />
 <cfset fname=trim(result[1]) />
 <cfset lname=trim(result[2]) />
 
	<cfquery name="qBrokerages" datasource="#request.db.dsn#"  >
			select u.userid,u.firstname,u.lastname,(select brokerageName from brokerages where brokerageID=u.brokerageid limit 1) as brokerageName
            from users u 
            where firstname like '#fname#%' and lastname like '#lname#%'
			order by firstname asc
            limit 10
		</cfquery>
<cfelse>


<!--- Generate the JSON feed as a JavaScript function. --->


<cfquery name="qBrokerages" datasource="#request.db.dsn#"  >
			select u.userid,u.firstname,u.lastname,(select brokerageName from brokerages where brokerageID=u.brokerageid limit 1) as brokerageName
            from users u 
            where firstname like '#url.tag#%'
			order by firstname asc
            limit 10
		</cfquery>
</cfif>        
<cfoutput>[</cfoutput>
<cfsavecontent variable="qInsert3">
<cfoutput query="qBrokerages">
{"name":"#qBrokerages.firstname# #qBrokerages.lastname #","brokerage":"#qBrokerages.brokerageName#","value":#qBrokerages.userid#},
</cfoutput>
</cfsavecontent>
<cfif len(trim(qInsert3)) eq 1 >
	<cfoutput>#trim(qInsert3)#]</cfoutput>
<cfelse>
	<cfoutput>#left(trim(qInsert3), len(trim(qInsert3))-1)#]</cfoutput>		
</cfif>
</cfprocessingdirective>
