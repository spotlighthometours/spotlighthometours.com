<cfset editMode = iif(isDefined("url.code"), true, false)>
<cfif editMode>
	<cfquery name="qPromoCodes" datasource="#request.db.dsn#">
		select * from promoCodes where codeStr = '#url.code#'
	</cfquery>
	<cfset codeStr = qPromoCodes.codeStr>
<cfelse>
	<cfset alphaList = 'a,b,c,d,e,f,g,h,i,j,1,2,3,4,5,6,7,8,9,0'>
	<cfset a = listToArray(alphaList)>
	<cfset i = arrayLen(a)>
	<cfset codeStr = a[randRange(1,i)]&a[randRange(1,i)]&a[randRange(1,i)]&a[randRange(1,i)]&a[randRange(1,i)]&a[randRange(1,i)]&a[randRange(1,i)]>
</cfif>

<cfquery name="qTourTypes" datasource="#request.db.dsn#">
	select tourTypeID, tourTypeName, unitPrice, hidden,tourCategory from tourTypes order by unitPrice desc
</cfquery>


<html>
<head>
<title>Promo Codes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<cfoutput>
<form action="#cgi.sript_name#?action=<cfif editMode>updateCode<cfelse>insertCode</cfif>" method="post">
    <table width="500" border="0" cellspacing="2" cellpadding="4">
      <tr> 
        <td class="rowHead">Code</td>
        <td class="rowData"><input name="codeStr" type="text" size="10" maxlength="7" value="#uCase(codeStr)#"<cfif editMode> disabled</cfif>></td>
      </tr>
		<tr> 
        <td class="rowHead">Value</td>
        <td class="rowData">
		  <cfif editMode and qPromoCodes.value lte 1>
		  	<cfset value = numberFormat(qPromoCodes.value * 100, '999') & '%'>
		  <cfelseif editMode>
		  	<cfset value = dollarFormat(qPromoCodes.value)>
		  </cfif>
		  
		  <input name="value" type="text" size="10" maxlength="20"<cfif editMode> value="#value#"</cfif>></td>
      </tr>
		<tr> 
        <td class="rowHead">Expiration Date</td>
        <td class="rowData"><input name="expDate" type="text" size="14" maxlength="10"<cfif editMode> value="#qPromoCodes.expDate#"</cfif>></td>
      </tr>
      </tr>
		<tr> 
        <td class="rowHead">Limit</td>
        <td class="rowData"><input name="limit" type="text" size="14" maxlength="3"<cfif editMode> value="#qPromoCodes.limits#"</cfif>></td>
      </tr>
      </tr>
		<tr> 
        <td class="rowHead">Tour Type</td>
        <td class="rowData">   
      <select name="tourTypeID" id="tourTypeID">
          	<option value="0">------None------</option>
            <cfloop query="qTourTypes">
            <option  <cfif editMode> <cfif qTourTypes.tourTypeID eq qPromoCodes.tourTypeID>selected="selected" selected="true"</cfif> </cfif> value="#qTourTypes.tourTypeID#">#qTourTypes.tourTypeName#</option>
        	</cfloop>	
          </select></td>
      </tr>
      <tr> 
        <td class="rowHead"><cfif editMode><input type="hidden" name="codeStr" value="#codeStr#"></cfif></td>
        <td class="rowData"><input type="submit" value="<cfif EditMode>Update<cfelse>Add</cfif> Code"></td>
      </tr>
    </table>
</form>
</cfoutput>
</body>
</html>