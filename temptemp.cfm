<CFPARAM NAME="url.county" DEFAULT="" />
<CFPARAM NAME="url.zip" DEFAULT="" />
<cfif len(url.county) >
<CFQUERY name="qZip" datasource="#request.dsn#">
select zip_code from zip_code where state_prefix='CO' and county='#url.county#' group by zip_code
</CFQUERY>
<CFLOOP QUERY="qZip">
<cftry> 

 <CFINVOKE component="com.pricebyzip" method="mainproducts"  zip="#zip_code#" returnvariable="tourtype" />
 
 <cfcatch type = "Database"> 
        <CFOUTPUT>'#zip_code#',</CFOUTPUT>
        
    </cfcatch> 
</cftry>
</CFLOOP>

<cfelse>
<cftry> 

 <CFINVOKE component="com.pricebyzip" method="mainproducts"  zip="#url.zip#" returnvariable="tourtype" />
 <CFDUMP VAR="#tourtype#" />
 <cfcatch type = "Database"> 
        <!--- The message to display. ---> 
        <h3>You've Thrown a Database <b>Error</b> for <CFOUTPUT>#url.zip#</CFOUTPUT></h3> 
        <cfoutput> 
            <!--- The diagnostic message from ColdFusion. ---> 
            <p>#cfcatch.message#</p> 
            <p>Caught an exception, type = #CFCATCH.TYPE#</p> 
            <p>The contents of the tag stack are:</p> 
            <cfdump var="#cfcatch.tagcontext#"> 
        </cfoutput> 
    </cfcatch> 
</cftry>

</cfif>

