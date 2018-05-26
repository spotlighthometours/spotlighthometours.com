<cfset editMode = iif(isDefined("url.product"), true, false)>
<cfif editMode>
	<cfquery name="qProducts" datasource="#request.db.dsn#">
		select * from products where productID = #url.product#
	</cfquery>
</cfif>
<html>
<head>
<title>Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<cfoutput>
<form action="#cgi.sript_name#?action=<cfif editMode>updateProduct<cfelse>insertProduct</cfif>" method="post">
    <table width="500" border="0" cellspacing="2" cellpadding="4">
      <tr> 
        <td class="rowHead">Product Name</td>
        <td class="rowData"><input name="productName" type="text" size="32" maxlength="50"<cfif editMode> value="#qProducts.productName#"</cfif>></td>
      </tr>
		<tr> 
        <td class="rowHead">Unit Price</td>
        <td class="rowData"><input name="unitPrice" type="text" size="32" maxlength="50"<cfif editMode> value="#dollarFormat(qProducts.unitPrice)#"</cfif>></td>
      </tr>
		<tr> 
        <td class="rowHead">No Multiple Quantities</td>
        <td class="rowData"><input name="onePerOrder" type="checkbox"<cfif editMode and qProducts.onePerOrder> checked</cfif>></td>
      </tr>
		<tr> 
        <td class="rowHead">Charge Sales Tax</td>
        <td class="rowData"><input name="chargeSalesTax" type="checkbox"<cfif editMode and qProducts.chargeSalesTax> checked</cfif>></td>
      </tr>
		<tr> 
        <td class="rowHead">Description</td>
        <td class="rowData">
		  <textarea name="description" style="width: 300px; height:100px;"><cfif editMode>#qProducts.description#</cfif></textarea>
		  </td>
      </tr>
      <tr> 
        <td class="rowHead"><cfif editMode>
            <input type="hidden" name="productID" value="#qProducts.productID#">
          </cfif></td>
        <td class="rowData"><input type="submit" value="<cfif EditMode>Update<cfelse>Add</cfif> Product"></td>
      </tr>
    </table>
</form>
</cfoutput>
</body>
</html>