<cfsilent>
	<cfparam name="url.user" default="" />
    <cfparam name="url.pg" default="payinvoice" />
	<cfparam name="errorMsg" default="" />
	<cfparam name="form.amount" default="" />
	<cfparam name="form.invoiceNumber" default="" />
	<cfparam name="form.notes" default="" />
	<cfparam name="form.name" default="" />
	<cfparam name="form.address" default="" />
	<cfparam name="form.city" default="" />
	<cfparam name="form.state" default="" />
	<cfparam name="form.zipCode" default="" />
	<cfparam name="form.shippingaddress" default="" />
	<cfparam name="form.shippingcity" default="" />
	<cfparam name="form.shippingstate" default="" />
	<cfparam name="form.shippingzipCode" default="" />
	<cfparam name="form.cardType" default="" />
	<cfparam name="form.cardNumber" default="" />
	<cfparam name="form.expMonth" default="" />
	<cfparam name="form.expYear" default="" />
	<cfparam name="form.phone1" default="" />
	<cfparam name="form.phone2" default="" />
	<cfparam name="form.phone3" default="" />
	<cfparam name="form.my12sign" default="0" />
	<cfparam name="form.my16sign" default="0" />

    <cfquery name="qCCard" datasource="#request.db.dsn#">
        SELECT crardId, cardNick FROM usercreditcards
        WHERE userid = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.user#">
    </cfquery>

	<cfif form.name eq ""
		AND form.address eq ""
		AND form.city eq ""
		AND form.state eq ""
		AND form.zipCode eq "">

		<!--- attempt to fill in as many details as possible from the agent information --->
        <cfquery name="qGet" datasource="#request.db.dsn#">
			select firstname, lastname, address, city, state, zipcode, phone
			from users
			where userID = <cfqueryparam cfsqltype="cf_sql_varchar" value="#url.user#">
		</cfquery>
        
		<cfset form.name = qGet.firstname & " " & qGet.lastname />
		<cfset form.address = qGet.address />
		<cfset form.city = qGet.city />
		<cfset form.state = qGet.state />
		<cfset form.zipcode = qGet.zipcode />
		<cfif ListLen(qGet.phone,'.') eq 3>
			<cfset form.phone1 = ListGetAt(qGet.phone,1,'.') />
			<cfset form.phone2 = ListGetAt(qGet.phone,2,'.') />
			<cfset form.phone3 = ListGetAt(qGet.phone,3,'.') />
		</cfif>
	</cfif>

	<cfquery name="qStates" datasource="#request.db.dsn#">
		select stateFullName, stateAbbrName from states order by stateFullName
	</cfquery>
</cfsilent>


<!--- <cfelse>
	<template:layout-user title="Purchase Spotlight Mobile Preview Sign">
</cfif> --->

<html>
<head>
<title>Pay Invoice</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<script language = "javascript">

function FillCCInfo(ccID, userID) {
	try {
		
		var HTTP = false;
		if (window.XMLHttpRequest) {
			HTTP = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			HTTP = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		if(HTTP) {
			// Borrowing from the checkout.
			var url = "../../../checkout/checkout_getccdetails.php";
			var params = "cardid=" + ccID + "&userid=" + userID;
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					var xmlDoc = HTTP.responseXML;
					
					document.getElementById('name').value = xmlDoc.documentElement.attributes.getNamedItem("name").nodeValue;
					document.getElementById('address').value = xmlDoc.documentElement.attributes.getNamedItem("address").nodeValue;
					document.getElementById('city').value = xmlDoc.documentElement.attributes.getNamedItem("city").nodeValue;
					
					var states = document.getElementById('state');
					for (var i = 0; i < states.length; i++) {
						if (states[i].value == xmlDoc.documentElement.attributes.getNamedItem("state").nodeValue) {
							states.selectedIndex = i;
						}
					}
					
					document.getElementById('zipCode').value = xmlDoc.documentElement.attributes.getNamedItem("zip").nodeValue;
					
					var cardtypes = document.getElementById('cardType').options;
					for (var i = 0; i < cardtypes.length; i++) {
						if (cardtypes[i].value == xmlDoc.documentElement.attributes.getNamedItem("type").nodeValue) {
							cardtypes.selectedIndex = i;
						}
					}
					
					document.getElementById('cardNumber2').value = xmlDoc.documentElement.attributes.getNamedItem("number").nodeValue;
					
					var months = document.getElementById('month');
					for (var i = 0; i < months.length; i++) {
						if (parseInt(months[i].value) == parseInt(xmlDoc.documentElement.attributes.getNamedItem("month").nodeValue)) {
							months.selectedIndex = i;
						}
					}
					
					var years = document.getElementById('year');
					for (var i = 0; i < years.length; i++) {
						if ((parseInt(years[i].value) - 2000) == parseInt(xmlDoc.documentElement.attributes.getNamedItem("year").nodeValue)) {
							years.selectedIndex = i;
						}
					}
				}
			}
			HTTP.send(params);
		}
	} catch(err) {
		window.alert('FillCCInfo: ' + err);
	}
}

</script>

</head>

<body>

<cfif url.pg eq "purchaseSign">
	<script type="text/javascript" >
		function fillInInfo() {
			/* check if form box is checked, if so, pull billing info and insert it into proper slots */
			if(document.getElementById('sameasbilling').checked == true) {
				document.getElementById('shippingaddress').value = document.getElementById('address').value;
				document.getElementById('shippingcity').value = document.getElementById('city').value;
				/* need to loop through the state items and find the selected value */
				document.getElementById('shippingstate').selectedIndex = document.getElementById('state').selectedIndex;
				document.getElementById('shippingzipCode').value = document.getElementById('zipCode').value;
			}
		}
	</script>
</cfif>

<cfoutput>

	<cfif len(errorMsg)>
        <div class="error">
        	There was an error. Please try again.<br />
            Error Detail: #errorMsg#
        </div>
    </cfif>
    <form name="invoiceForm" action="users.cfm?action=payInvoice" method="post" id="invoiceForm">
        <input type="hidden" name="referencepage" value="#url.pg#" />
        <input type="hidden" name="userid" value="#url.user#" />
		<cfif url.pg neq "purchaseSign">
            <h3>Invoice Details</h3>
            <div>
                <strong>Invoice Number</strong>
            </div>
            <div class="invoiceFormEntry">
                <input type="text" name="invoicenumber" maxlength="24" value="#form.invoicenumber#"/>
            </div>
            <div class="invoiceFormLabel">
                <strong>Amount</strong> (for example if the amount is $20.00, enter "20")
            </div>
            <div class="invoiceFormEntry">
                <input type="text" name="amount" maxlength="8" value="#form.amount#"/>
            </div>
            <div class="invoiceFormLabel">
                <strong>Notes (Optional)</strong>
            </div>
            <div class="invoiceFormEntry">
                <textarea name="notes" >#form.notes#</textarea>
            </div>
        <cfelse>
            <h3>Sign Options</h3>
            <input type="hidden" name="invoicenumber" maxlength="24" value="Preview Sign Purchase"/>
            <div class="invoiceFormLabel">
            	<strong>Size (shipping included in price):</strong>
            </div>
            <div class="invoiceFormEntry">
                <table>
                	<tr>
                    	<th>Type</th>
                        <th>Number</th>
                    </tr>
                	<tr>
                    	<td style="padding-right:10px;">6" Rider ($12)</td>
                        <td>
                        	<input type="input" maxlength="2" size="2" name="my12sign" value="#form.my12sign#">
                        </td>
                    </tr>
                	<input type="hidden" maxlength="2" size="2" name="my16sign" value="0">
                </table>
            </div>
            <input type="hidden" name="notes" value="Mobile Preview Sign Purchase" />
    	</cfif>
		<h3 style="padding-top: 25px;">Billing Details</h3>
    	<table width="500">
      		<tr>
        		<td height="26" colspan="3">
                	<strong>Saved Cards</strong><br>
                    <cfloop query="qCCard">
                    	<p><span style="background-color: ##999; cursor: pointer; " onclick="FillCCInfo('#crardId#', '#url.user#');" >#cardNick#</span></p>
                    </cfloop>
                </td>
      		</tr>
            <tr>
        		<td height="26" colspan="3">
                	<strong>Name on Card</strong><br>
                    <input id="name" name="name" type="text" size="36" value="#form.Name#" maxlength="100" />
                </td>
      		</tr>
      		<tr>
        		<td colspan="3">
                	<strong>Address</strong><br>
                    <input name="address" type="text" id="address" value="#form.address#" size="42" maxlength="100" />
                </td>
      		</tr>
      		<tr>
        		<td>
                	<strong>City</strong><br>
                    <input name="city" value="#form.city#" type="text" id="city" />
                </td>
        		<td>
                	<strong>State</strong><br>
                    <select name="state" id="state">
            			<option value="">Select One...</option>
            			<cfloop query="qStates">
              				<option value="#stateAbbrName#" <cfif form.state eq stateAbbrName> selected="true"</cfif>>#stateFullName#</option>
            			</cfloop>
          			</select>
                </td>
        		<td>
                	<strong>Zip Code</strong><br>
                    <input name="zipCode" type="text" id="zipCode" value="#form.zipCode#" size="10" maxlength="10" />
                </td>
      		</tr>
      		<tr>
        		<td colspan="3">
                	<strong>Phone</strong><br>
                    <input name="phone1" value="#form.phone1#" type="text" id="phone1" size="3" maxlength="3" />
          			<input name="phone2" type="text" id="phone2" value="#form.phone2#" size="3" maxlength="3" />
          			<input name="phone3" type="text" id="phone3" value="#form.phone3#" size="4" maxlength="4" />
                </td>
      		</tr>
      		<tr>
            	<td>
                	<strong>Card Type</strong><br>
                	<select name="cardType" id="cardType">
                    	<option>Select One...</option>
                    	<option value="visa">Visa</option>
                    	<option value="mastercard">Mastercard</option>
                    	<option value="discover">Discover</option>
                    	<option value="americanexpress">American Express</option>
                	</select>
            	</td>
            	<td>
                	<strong>Card Number</strong><br>
                    <input name="cardNumber" type="text" id="cardNumber2" size="20" maxlength="20" />
              	</td>
           	 	<td>
                	<strong>Expiration Date</strong><br>
                    <select id="month" name="expMonth">
                        <cfloop from="1" to="12" index="i">
                            <cfset display = numberFormat(i,"09")>
                            <option value="#i#">#display#</option>
                        </cfloop>
                    </select>
                    <select id="year" name="expYear">
                        <cfloop from="0" to="6" index="i">
                            <cfset year = dateFormat(dateAdd("yyyy", i, now()), "yyyy")>
                            <option value="#year#">#year#</option>
                        </cfloop>
                    </select>
                </td>
      		</tr>
		</table>
		<cfif url.pg eq "purchaseSign">
			<h3 style="padding-top: 25px;">Shipping Details</h3>
            <table width="500">
                <tr>
                    <td colspan="3">
                        <strong>Same as Billing?</strong><br>
                        <input name="sameasbilling" type="checkbox" id="sameasbilling" onClick="fillInInfo();" />
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Address</strong><br>
                        <input name="shippingaddress" type="text" id="shippingaddress" value="#form.shippingaddress#" size="42" maxlength="100" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>City</strong><br>
                        <input name="shippingcity" value="#form.shippingcity#" type="text" id="shippingcity" />
                    </td>
                    <td>
                        <strong>State</strong><br> 
                        <select name="shippingstate" id="shippingstate">
                            <option value="">Select One...</option>
                            <cfloop query="qStates">
                                <option value="#stateAbbrName#" <cfif form.shippingstate eq stateAbbrName> selected="true"</cfif>>#stateFullName#</option>
                            </cfloop>
                        </select>
                    </td>
                    <td>
                        <strong>Zip Code</strong><br>
                        <input name="shippingzipCode" type="text" id="shippingzipCode" value="#form.shippingzipCode#" size="10" maxlength="10" />
                    </td>
                </tr>
            </table>
		</cfif>
		<div class="invoiceFormLabel"><input type="submit" value="Submit" /></div>
	</form>
</cfoutput>
</body>
</html>

