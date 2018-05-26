var transactionID = -1;
var autoSetItem = false;
function PHP_Logout() {
	try {
		var url = "../repository_inc/user_login_logic.php";
		var params = "logout=true";
		
		if(HTTP) {
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					parent.location = "/users/users.cfm?action=logout";
				}
			}
			HTTP.send(params);
			
		}
	} catch(err) {
		RecordError("PHP_Logout: " + err);
	}
}

// Java version of the PHP isset().  Is a variable defined?
function isset(object) {
	var good = false;
	if (typeof object != 'undefined') {
		good = true;
	}
	return good;
}

function openPopup(url, x, y) {
	try {
		window.open(url,'Preview',"location=0,status=0,scrollbars=0, width=" + x + ",height=" + y);
	} catch(err) {
		RecordError("openPopup: " + err);
	}
}

//Step 1: Ajax query to get states.
function GetStates() {
	try {
		if (HTTP) {
			var dataSource = "checkout_states.php";
			
			HTTP.open("GET", dataSource); 
			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) { 
					$("#state").autocomplete(HTTP.responseText.split(","));
					$("#ccstate").autocomplete(HTTP.responseText.split(","));
					GetAgents();  //Now we can go off and get agents.
				} 
			} 
			HTTP.send(null); 
		}
	} catch(err) {
		RecordError("GetStates: " + err);
	}
}

//Step 2: Ajax query to get agents.
function GetAgents() {
	try {
		if (HTTP) {
			var dataSource = "checkout_agents.php";
			
			HTTP.open("GET", dataSource); 
			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) { 
					$("#coagent").autocomplete(HTTP.responseText.split(","));
					//This would send to another autofill if need be.
				} 
			} 
			HTTP.send(null); 
		}
	} catch(err) {
		RecordError("GetAgents: " + err);
	}
}

//Ask for confirmation on leaving the page.
function RunOnBeforeUnload() {
	if (checkUnload) {
		return "Are you sure that you want to leave?  This tour will be lost. \n If you are looking to change information, please \ncancel and click on the step you would like to change.";
	}
}

//Highlights an option button
function HighlightBtn(itemID) {
	try {
		var item = document.getElementById(itemID + "capl");
		item.className = "btncap btncaplhl";
		item = document.getElementById(itemID + "capr");
		item.className = "btncap btncaprhl";
		item = document.getElementById(itemID + "icon");
		item.className = "btnicon btnbodyhl";
		item = document.getElementById(itemID + "txt");
		item.className = "btntxt btnbodyhl";
	} catch(err) {
		RecordError("HighlightBtn: " + err);
	}
}

//Dehighlights an option button
function DeHighlightBtn(itemID) {
	try {
		var item = document.getElementById(itemID + "capl");
		item.className = "btncap btncapl";
		item = document.getElementById(itemID + "capr");
		item.className = "btncap btncapr";
		item = document.getElementById(itemID + "icon");
		item.className = "btnicon btnbody";
		item = document.getElementById(itemID + "txt");
		item.className = "btntxt btnbody";
	} catch(err) {
		RecordError("DeHighlightBtn: " + err);
	}
}		

//Toggle the visibility of a form item.
//Uses the CSS class 'hidden' and 'visible'.
//Searches the class listings if the item has multiple classes.
function Toggle(itemID) {
	try {
		var item = document.getElementById(itemID);
		var classes = item.className.split(" ");
		var newclasses = '';
		for (var i=0;i < classes.length;i++) {
			if (classes[i] == "visible") {
				classes[i] = "hidden";
			} else if (classes[i] == "hidden") {
				classes[i] = "visible";
			} 
			if (i > 0) {
				newclasses += ' ';
			} 
			newclasses += classes[i];
		}
		item.className = newclasses;
	} catch(err) {
		RecordError("Toggle: " + err);
	}
}

//Shortcut function to toggle the right items to display an additional form.
function ToggleForm(itemID) {
	try {
		Toggle('backdrop'); 
		Toggle('display');
		Toggle(itemID);
	} catch(err) {
		RecordError("ToggleForm: " + err);
	}
}

//Show a step div
function HideStep(stepID) {
	var step = document.getElementById(stepID);
	step.className = 'hidden';
}

//Hide a step div
function ShowStep(stepID) {
	var step = document.getElementById(stepID);
	step.className = 'visible';
}

//Show a step div and hide the other steps.
function ToggleStep(stepID) {
	for (var i=1;i <= 4;i++) {
		HideStep('step' + i);
	}
	ShowStep('step' + stepID);
	UpdateFloatingBar(stepID);
	scrollTop();
}

function scrollTop() {
	window.document.body.scrollTop = 0;
	window.document.documentElement.scrollTop = 0;
	window.scrollTo(0, 0);
}

function SwitchClass(itemID, searchFor, replaceWith) {
	try {
		var item = document.getElementById(itemID);
		var classes = item.className.split(" ");
		var newclasses = '';
		for (var i=0;i < classes.length;i++) {
			if (classes[i] == searchFor) {
				classes[i] = replaceWith;
			} 
			if (i > 0) {
				newclasses += ' ';
			} 
			newclasses += classes[i];
		}
		item.className = newclasses;
	} catch(err) {
		RecordError("SwitchClass: " + err);
	}
}

function ToggleOn(itemID) {
	try {
		SwitchClass(itemID, "hidden", "visible");
	} catch(err) {
		RecordError("ToggleOn: " + err);
	}
}

function ToggleOff(itemID) {
	try {
		SwitchClass(itemID, "visible", "hidden");
	} catch(err) {
		RecordError("ToggleOff: " + err);
	}
}

function WaitOn() {
	try {
		ToggleOn('backdrop');
		ToggleOn('display');
		ToggleOn('wait');
	} catch(err) {
		RecordError("WaitOn: " + err);
	}
}

function WaitOff() {
	try {
		ToggleOff('wait');
		ToggleOff('display');
		ToggleOff('backdrop');
	} catch(err) {
		RecordError("WaitOff: " + err);
	}
}

function FormOn(itemID) {
	try {
		ToggleOn('backdrop');
		ToggleOn('display');
		ToggleOn(itemID);
	} catch(err) {
		RecordError("FormOn: " + err);
	}
}

function FormOff(itemID) {
	try {
		ToggleOff(itemID);
		ToggleOff('display');
		ToggleOff('backdrop');
	} catch(err) {
		RecordError("FormOff: " + err);
	}
}

function SelectTour(itemID) {
	try {
		var item = document.getElementById(itemID);
		var tourids = document.getElementById('tourlist').value.split(",");
		var deselected = false;
		var selected = false;
		var classes = false;
		var newclasses = '';
		// Loop through all the buttons and deselect them all.
		for (var i=0;i < tourids.length;i++) {
			
			// Set the tour frame highlight back to standard.
			SwitchClass('tour' + tourids[i], "tourhl", "tourstd");
			
			// Get rid of the remove button.
			SwitchClass('remove' + tourids[i], "visible", "hidden");
			
			deselected = document.getElementById('btnTour' + tourids[i] + 'Cart');
			selected = document.getElementById('btnSelect' + tourids[i]);
			
			// Set the non-selected button to visible.
			classes = deselected.className.split(" ");
			for (var j=0;j < classes.length;j++) {
				if (classes[j] == "hidden") {
					classes[j] = "visible";
				} 
				if (j > 0) {
					newclasses += ' ';
				} 
				newclasses += classes[j];
			}
			deselected.className = newclasses;
			
			// Set the selected button to hidden.
			newclasses = '';
			classes = selected.className.split(" ");
			for (var k=0;k < classes.length;k++) {
				if (classes[k] == "visible") {
					classes[k] = "hidden";
				} 
				if (k > 0) {
					newclasses += ' ';
				} 
				newclasses += classes[k];
			}
			selected.className = newclasses;
		}
		
		// Selected the selected tour
		Toggle('btnTour' + itemID + 'Cart');
		Toggle('btnSelect' + itemID);
		
		// Set the order tour id to the selected tour
		order_tourid = itemID;
		order_tourprice = parseFloat(document.getElementById('tour' + itemID + 'price').value);
		
		// Get that selected border around the tour.
		SwitchClass('tour' + itemID, "tourstd", "tourhl");
		
		// Show the remove button.
		SwitchClass('remove' + itemID, "hidden", "visible");
		
		// Update the cart price
		UpdateTotalOrder();
	} catch(err) {
		RecordError("SelectTour: " + err);
	}
}

function DeSelectTour(itemID) {
	try {
		Toggle('btnTour' + itemID + 'Cart');
		Toggle('btnSelect' + itemID);
							
		// Set the tour frame highlight back to standard.
		SwitchClass('tour' + itemID, "tourhl", "tourstd");
		
		// Get rid of the remove button.
		SwitchClass('remove' + itemID, "visible", "hidden");
		
		// "Blank out" the selected tour id.
		order_tourid = -1;
		order_tourprice = 0;
		// Update the cart price
		UpdateTotalOrder();
	} catch(err) {
		RecordError("DeSelectTour: " + err);
	}
}

function SetProductButtons() {
	try {
		if (document.getElementById('additionalproducts').innerHTML.length > 0) { // Only do this if the products are loaded.  Otherwise, we are gunna get a null.
			for ( var i = 0; i < order_additional_co.length; i++) {
				if (document.getElementById(i + '-additionalproduct') != undefined) { // Check to see if the item actually exists
					if (isset(order_additional_co[i])) {
						if (document.getElementById(i + '-productincrement').value == "single") {
							ToggleOn('btnform' + i + 'hl');
							ToggleOff('btnform' + i);
						}
						SwitchClass(i + '-additionalproduct', "optionstd", "optionhl");
					} else {
						if (document.getElementById(i + '-productincrement').value == "single") {
							ToggleOff('btnform' + i + 'hl');
							ToggleOn('btnform' + i);
						}
						SwitchClass(i + '-additionalproduct', "optionhl", "optionstd");
					}
				}
			}
		}
	} catch(err) {
		RecordError("SetProductButtons: " + err);
	}
}

function UpdateTotalOrder() {
	try {
		SetProductButtons();
		var total = 0;
		if (order_tourid != -1) total += order_tourprice;  // Add in the tour price.
		for (var i=0 ; i < order_additional_co.length; i++) { // Add in all the one per order items.
			if (isset(order_additional_co[i])) {
				total += (order_additional_co[i][1] * order_additional_co[i][2]);
			}
		}
		
		var formatPrice = "$" + parseFloat(total).toFixed(2);
		document.getElementById('step2total').innerHTML = formatPrice;
		document.getElementById('step3total').innerHTML = formatPrice;
		document.getElementById('step4total').innerHTML = formatPrice;
		document.getElementById('floatingtotal2').innerHTML = formatPrice;
		document.getElementById('floatingtotal3').innerHTML = formatPrice;
		document.getElementById('floatingtotal4').innerHTML = formatPrice;
		document.getElementById("virtualstagingtotal").innerHTML = formatPrice;
	} catch(err) {
		RecordError("UpdateTotalOrder: " + err);
	}
}

function AddOne(productId) {
	try {
		document.getElementById(productId + '-productcounter').value++;
		UpdateCount(productId);
	} catch(err) {
		RecordError("AddOne: " + err);
	}
}

function SubOne(productId) {
	try {
		document.getElementById(productId + '-productcounter').value--;
		UpdateCount(productId);
	} catch(err) {
		RecordError("SubOne: " + err);
	}
}

function UpdateCount(productId) {
	try {
		var product = document.getElementById(productId + '-productcounter');
		if (isNaN(product.value)) {
			product.value = 0;
		}
		if (product.value < 0) {
			product.value = 0;
		}
		SetItem(productId, parseInt(product.value));
		UpdateTotalOrder();
	} catch(err) {
		RecordError("UpdateCount: " + err);
	}
}

function SetItem(productID, quantity) {
	try {
		if (quantity) {	
			var price = parseFloat(document.getElementById(productID + '-productprice').value);
			order_additional_co[productID] = new Array(productID, parseInt(quantity), price);
		} else {
			order_additional_co[productID] = undefined;
		}
		UpdateTotalOrder();
	} catch(err) {
		RecordError("SetItem: " + err);
	}
}

function UpdateFloatingBar(step) {
	try {
		for (var i = 1; i <=4; i++) {
			ToggleOff('floatstep' + i);
		}
		switch(step) {
			case 1:
				ToggleOff('floatingbar');
				break;
			case 2:
				ToggleOn('floatingbar');
				ToggleOn('floatstep' + step);
				break;
			case 3:
				ToggleOn('floatingbar');
				ToggleOn('floatstep' + step);
				break;
			case 4:
				ToggleOff('floatingbar');
				break;
			default:
				break;
		}
	} catch(err) {
		RecordError("UpdateFloatingBar: " + err);
	}
}

function DispProductDescription(productName) {
	try {
		if (HTTP) {
			WaitOn();
			var url = "checkout_getproductdesc.php";
			var params = "name=" + productName;
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					WaitOff();
					// Make sure the close button is visible.
					SwitchClass("formdescriptionclose", "hidden", "visible");
					DispDescription(productName, HTTP.responseText);
				}
			}
			HTTP.send(params);
		}
	} catch(err) {
		RecordError('DispProductDescription: ' + err);
	}
}

function DispDescription(title, description) {
	try {
		document.getElementById('description_title').innerHTML = title;
		document.getElementById('description_text').innerHTML = description;
		
		FormOn('formDescription');
	} catch(err) {
		RecordError("DispDescription: " + err);
	}
}

function FillCCInfo(ccID) {
	try {
		if(HTTP) {
			WaitOn();
			var url = "checkout_getccdetails.php";
			var params = "cardid=" + ccID;
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					var xmlDoc = HTTP.responseXML;
					document.getElementById('ccname').value = xmlDoc.documentElement.attributes.getNamedItem("name").nodeValue;
					document.getElementById('ccaddress').value = xmlDoc.documentElement.attributes.getNamedItem("address").nodeValue;
					document.getElementById('cccity').value = xmlDoc.documentElement.attributes.getNamedItem("city").nodeValue;
					document.getElementById('ccstate').value = xmlDoc.documentElement.attributes.getNamedItem("state").nodeValue;
					document.getElementById('cczip').value = xmlDoc.documentElement.attributes.getNamedItem("zip").nodeValue;
					var cardtypes = document.getElementById('cctype').options;
					for (var i = 0; i < cardtypes.length; i++) {
						if (cardtypes[i].value == xmlDoc.documentElement.attributes.getNamedItem("type").nodeValue) {
							cardtypes.selectedIndex = i;
						}
					}
					document.getElementById('ccnum').value = xmlDoc.documentElement.attributes.getNamedItem("number").nodeValue;
					document.getElementById('ccmonth').value = xmlDoc.documentElement.attributes.getNamedItem("month").nodeValue;
					document.getElementById('ccyear').value = xmlDoc.documentElement.attributes.getNamedItem("year").nodeValue;
					WaitOff();
				}
			}
			HTTP.send(params);
		}
	} catch(err) {
		RecordError('FillCCInfo: ' + err);
	}
}

function TaC() {
	try {
		if (HTTP) {
			WaitOn();
			var url = "checkout_tac.php";
			var params = "";
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					WaitOff();
					DispDescription("Terms & Conditions", HTTP.responseText);
				}
			}
			HTTP.send(params);
		}
	} catch(err) {
		RecordError('TaC: ' + err);
	}
}

function ValidateTransInfo() {
	try {
		var badChars = new Array("$","&","|","?","'",String.fromCharCode(34));
		
		var errorText = "";
		var errorDisplay = document.getElementById('errortext');
		var name = document.getElementById('ccname').value;
		var address = document.getElementById('ccaddress').value;
		var city = document.getElementById('cccity').value;
		var state = document.getElementById('ccstate').value;
		var zip	= document.getElementById('cczip').value;
		var number = document.getElementById('ccnum').value;
		var month = document.getElementById('ccmonth').value;
		var year = document.getElementById('ccyear').value;
		
		for (var i = 0; i < badChars.length; i++) {
			if (name.indexOf(badChars[i]) > -1) {
				errorText += "Billing name has a(n) '" + badChars[i] + "' in it.  Please remove it.<br />";
			}
			if (address.indexOf(badChars[i]) > -1) {
				errorText += "Billing address has a(n) '" + badChars[i] + "' in it.  Please remove it.<br />";
			}
			if (city.indexOf(badChars[i]) > -1) {
				errorText += "Billing city has a(n) '" + badChars[i] + "' in it.  Please remove it.<br />";
			}
			if (state.indexOf(badChars[i]) > -1) {
				errorText += "Billing state has a(n) '" + badChars[i] + "' in it.  Please remove it.<br />";
			}
		}
		
		if (name == "Name" || name.length == 0) {
			errorText += "Please verify the billing name.<br />";
		}
		if (address == "Billing Address" || address.length == 0) {
			errorText += "Please verify the billing address.<br />";
		}
		if (city == "City" || city.length == 0) {
			errorText += "Please verify the billing city.<br />";
		}
		if (state == "State" || state.length == 0) {
			errorText += "Please verify the billing state.<br />";
		}
		if (isNaN(zip)) {
			errorText += "Please verify the billing zip code.<br />";
		}
		if (isNaN(number)) {
			errorText += "Please verify your card number.<br />";
		}
		if (isNaN(month)) {
			errorText += "Please verify your card expiration month.<br />";
		}
		if (isNaN(year)) {
			errorText += "Please verify your card expiration year.<br />";
		}
		
		if (errorText.length > 0) {
			errorDisplay.innerHTML = errorText;
			FormOn('formError');  // Display an error message.
			return false;
		} else {
			return true;
		}
	} catch(err) {
		RecordError('ValidateTransInfo: ' + err);
	}
}

// The pricing requires certain parameters to be passed to it.
// This gets used in a few places, so it seemed like a good idea to build a function to put them together.
function BuildPricingParams() {
	try {
		var city = document.getElementById('city').value;
		var zip	= document.getElementById('zip').value;
	
		var coupon = document.getElementById('couponcode').value;
		// If the coupon code has the default text, set it to nothing.
		if (coupon == "Coupon Code") {
			coupon = "";
		}
		
		var params = "city=" + city + "&zip=" + zip + "&brokerid=" + brokerid + "&coupon=" + coupon;
		if (!additionalOnly) {
			params += "&tourtypeid=" + order_tourid;
		}
		var count = 0;
		for (var i = 0; i < order_additional_co.length ; i++) {
			if (isset(order_additional_co[i])) {
				if (order_additional_co[i].length > 0) {
					params += "&itemid" + count + "=" + order_additional_co[i][0] + "&itemqty" + count + "=" + order_additional_co[i][1];
					count++;
				}
			}
		}
		return params;
	} catch(err) {
		RecordError('BuildPricingParams: ' + err);
	}	
}

function SubmitOrder() {
	try {
		var errorText = "";
		var errorDisplay = document.getElementById('errortext');
		
		var transactions = false;
		var mysqlerrors = false;
		
		var type = false;
		var result = false;
		var error = false;
		var id = false;
		
		var count = 0;
		if (HTTP) {
			if (document.getElementById('ccagree').checked == true) {
				var grandTotal = parseFloat(document.getElementById('grandtotal').value);
				if (grandTotal > 0) {  // If we have money to charge, submit the order.
					if (ValidateTransInfo()) {
						WaitOn();
						
						var url = "checkout_submitorder.php";
						
						var params = BuildPricingParams(); 
						
						// Credit card information
						params += "&ccname=" + document.getElementById('ccname').value;
						params += "&ccaddress=" + document.getElementById('ccaddress').value;
						params += "&cccity=" + document.getElementById('cccity').value;
						params += "&ccstate=" + document.getElementById('ccstate').value;
						params += "&cczip=" + document.getElementById('cczip').value;
						var cardtypes = document.getElementById('cctype').options;
						params += "&cctype=" + cardtypes[cardtypes.selectedIndex].value;
						params += "&ccnumber=" + document.getElementById('ccnum').value;
						params += "&ccmonth=" + document.getElementById('ccmonth').value;
						params += "&ccyear=" + document.getElementById('ccyear').value;
							
						if (document.getElementById('ccsave').checked == true) {
							params += "&ccsave=1";
						}
							
						for (var i = 0; i < order_additional_co.length ; i++) {
							if (isset(order_additional_co[i])) {
								if (order_additional_co[i].length > 0) {
									params += "&itemid" + count + "=" + order_additional_co[i][0] + "&itemqty" + count + "=" + order_additional_co[i][1];
									count++;
								}
							}
						}
										
						HTTP.open("POST", url, true);
						HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						HTTP.setRequestHeader("Content-length", params.length);
						HTTP.setRequestHeader("Connection", "close");

						HTTP.onreadystatechange = function() { 
							if (HTTP.readyState == 4 && HTTP.status == 200) {
								WaitOff();
								
								transactions = HTTP.responseXML.getElementsByTagName("transaction");
								mysqlerrors = HTTP.responseXML.getElementsByTagName("mysqlerror");
								
								// Run through and record the sql errors.
								
								for (var i = 0; i < mysqlerrors.length; i++) {
									errorText += mysqlerrors[i].childNodes[0].nodeValue + '<br />';
								}
								
								if (errorText.length > 0) {
									RecordError(errorText);
									errorText = "";
								}
								
								// Run through the returned transactions
								for (var i = 0; i < transactions.length; i++) {
									
									type = "";
									result = "";
									error = "";
									id = "";
									
									if (isset(transactions[i].attributes.getNamedItem("type").value)) {
										type = transactions[i].attributes.getNamedItem("type").value;
									}
									
									if (transactions[i].getElementsByTagName("result").length > 0) {
										if (transactions[i].getElementsByTagName("result")[0].childNodes.length > 0) {
											result = transactions[i].getElementsByTagName("result")[0].childNodes[0].nodeValue;
										}
									}
									if (transactions[i].getElementsByTagName("error").length > 0) {
										if (transactions[i].getElementsByTagName("error")[0].childNodes.length > 0) {
											error = transactions[i].getElementsByTagName("error")[0].childNodes[0].nodeValue;
										}
									}
									if (transactions[i].getElementsByTagName("id").length > 0) {
										if (transactions[i].getElementsByTagName("id")[0].childNodes.length > 0) {
											id = transactions[i].getElementsByTagName("id")[0].childNodes[0].nodeValue;
										}
									}
									
									if (type.toLowerCase() == "single" && result.toLowerCase() == "approved") {
										if (id.length > 0) {
											transactionID = parseInt(id);
										}
									} else if (type.toLowerCase() == "single" && (result.toLowerCase() == "failed" || result.toLowerCase() == "declined")) {
										// Record the error for later display
										if (error.length > 0) {
											if (error.indexOf('SGS') > -1) {
												errorText += 'Single Payment: ' + RefactorCCError(error) + '<br />';
											} else {
												errorText += 'Single Payment: ' + error + '<br />';
											}
										} else {
											errorText += 'Single Payment: Your credit card has been declined for unknown reasons.<br />';
										}
									} else if (type.toLowerCase() == "recurring" && result.toLowerCase() == "approved") {
										if (transactionID == -1) {
											transactionID = parseInt(id);
										}
									} else if (type.toLowerCase() == "recurring" && (result.toLowerCase() == "failed" || result.toLowerCase() == "declined")) {
										// Record the error for later display
										if (error.length > 0) {
											if (error.indexOf('SGS') > -1) {
												errorText += 'Recurring Payment: ' + RefactorCCError(error) + '<br />';
											} else {
												errorText += 'Recurring Payment: ' + error + '<br />';
											}
										} else {
											errorText += 'Recurring Payment: Your credit card has been declined for unknown reasons.<br />';
										}
									}
									
								}
								
								// Display the error if there is one.
								if (errorText.length > 0) {
									errorText += "<br/> Please verify your card information or call 801-466-4074 for assistance.";
									errorDisplay.innerHTML = errorText;
									FormOn('formError');  // Display an error message.
								}
								
								//alert(transactionID);
								if (transactionID > -1) {
									InsertOrder();
								}
								
							}
						}
						HTTP.send(params);
					}
				} else { // If there was no money to charge, move directly on to inserting the order.
					InsertOrder();
				}
			} else {
				window.alert("Please read and accept the Terms and Conditions.");
			}
		}  
	} catch(err) {
		RecordError("SubmitOrder: " + err);
	}
}

function RefactorCCError(sgsError) {
	try {
		// Check for SGS errors that were passed from the transaction.
		// ': SGS002000 DDeclinedYYYX'
		// ': SGS002304 Credit card is expired.'
		// ': SGS002303 Invalid credit card number.'
		// ': SGS002300 Invalid credit card type.'
		var response = "";
		
		if (sgsError.indexOf('SGS002000') > -1) {
			response = "Your credit card has been declined.";
		} else if (sgsError.indexOf('SGS002304') > -1) {
			response = "Your credit card has expired.";
		} else if (sgsError.indexOf('SGS000001') > -1) {
			response = "Your credit card has been declined.";
		} else if (sgsError.indexOf('SGS002303') > -1) {
			response = "Your credit card number is invalid.";
		} else if (sgsError.indexOf('SGS002300') > -1) {
			response = "Your credit card type is invalid.";
		} else {
			response = "Your credit card has produced an error that is unhandled at this time. <br /> This error will be recorded.";
			RecordError(sgsError);
		}
		
		return response;
		
	} catch(err) {
		RecordError("RefactorCCError: " + err);
	}
}

function InsertOrder() {
	try {
		if(HTTP) {
			WaitOn();
			var errorDisplay = document.getElementById('errortext');
			var errorText = "";
			
			var url = "checkout_insertorder.php";
			
			var params = BuildPricingParams(); 
			
			if (!additionalOnly) {
				params += "&transactionid=" + transactionID;
				params += "&title=" + document.getElementById('tourtitle').value;
				params += "&address=" + document.getElementById('propertyaddress').value;
				params += "&state=" + document.getElementById('state').value;
				params += "&price=" + document.getElementById('price').value; 
				params += "&sqfoot=" + document.getElementById('sqfoot').value; 
				params += "&bedrooms=" + document.getElementById('bedrooms').value;
				params += "&bathrooms=" + document.getElementById('bathrooms').value;
				params += "&mls=" + document.getElementById('mls').value;
				params += "&description=" + document.getElementById('description').value;
				params += "&additional=" + document.getElementById('additionalinstructions').value;
				params += "&coagent=" + document.getElementById('coagent').value;
				if (document.getElementById('hideprice').checked == true) {
					params += "&hideprice=1";
				}
				if (document.getElementById('hidesqfoot').checked == true) {
					params += "&hidesqfoot=1";
				}
				if (document.getElementById('hidebedrooms').checked == true) {
					params += "&hidebedrooms=1";
				}
				if (document.getElementById('hidebathrooms').checked == true) {
					params += "&hidebathrooms=1";
				}
				if (document.getElementById('hideaddress').checked == true) {
					params += "&hideaddress=1";
				}
			} else {
				params += "&prevtourid=" + document.getElementById('prevtourid').value;
			}
			
			// Build virtual staging items.
			var count = 0;
			for (var i = 0; i < order_vs.length ; i++) {
				params += "&vs" + count + "=" + order_vs[i][1] + " > " + order_vs[i][2] + " > " + order_vs[i][4] + " > " + order_vs[i][3] ;
				count++;
			}
			
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					WaitOff();
					var tourid = -1;
					var orderid = -1;
					
					var mysqlerrors = HTTP.responseXML.getElementsByTagName("mysqlerror");
					var tourids = HTTP.responseXML.getElementsByTagName("tourid");
					var orderids = HTTP.responseXML.getElementsByTagName("orderid");
								
					// Run through and record the sql errors.
					for (var i = 0; i < mysqlerrors.length; i++) {
						errorText += mysqlerrors[i].childNodes[0].nodeValue + '<br />';
					}
					
					// Get the tourid.
					for (var i = 0; i < tourids.length; i++) {
						if (tourids[i].childNodes.length > 0) {
							tourid = parseInt(tourids[i].childNodes[0].nodeValue);
						}
					}
					
					// Get the orderid.
					for (var i = 0; i < orderids.length; i++) {
						if (orderids[i].childNodes.length > 0) {
							orderid = parseInt(orderids[i].childNodes[0].nodeValue);
						}
					}
					
					if (errorText.length > 0) {
						RecordError(errorText);
						errorText = "";
					} 
					if (orderid > 0) {
						SendEmails(orderid);
					}
					
				}
			}
			HTTP.send(params);
		}
	} catch(err) {
		RecordError("InsertOrder: " + err);
	}
}

function SendEmails(orderId) {
	try {
		if (HTTP) {
			WaitOn();
			var url = "checkout_emails.php";
			
			var params = "orderid=" + orderId;
							
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					WaitOff();
					if (HTTP.responseText.indexOf('SUCCESS') > -1 ) {
						DisplayCompleted();
					} else {
						RecordError('SendEmails:' + HTTP.responseText);
						DisplayCompleted();
					}
				}
			}
			HTTP.send(params);
		}
		
	} catch(err) {
		RecordError("SendEmails: " + err);
	}
}

function DisplayCompleted() {
	try {
		if (HTTP) {
			WaitOn();
			var url = "checkout_complete.php";
			var params = "";
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					WaitOff();
					checkUnload = false;
					SwitchClass("formdescriptionclose", "visible", "hidden");
					DispDescription("Thanks for your order!", HTTP.responseText);
				}
			}
			HTTP.send(params);
		}
	} catch(err) {
		RecordError('DisplayCompleted: ' + err);
	}
}

function CharacterCount(itemId, totalCount, counterId) {
	try {
		var itemCount = document.getElementById(itemId).value.length;
		if (itemCount <= totalCount) {
			document.getElementById(counterId).innerHTML = (totalCount - itemCount) + ' characters left';
		} else {
			document.getElementById(itemId).value = document.getElementById(itemId).value.substring(0, totalCount);
		}
	} catch(err) {
		RecordError('CharacterCount: ' + err);
	}
}

function step1Validate() {
	try {
		var error = false;
		var errorText = '<div class="left-justify">';
		var errorDisplay = document.getElementById('errortext');
		var tourTitle = document.getElementById('tourtitle');
		var propertyAddress	= document.getElementById('propertyaddress');
		var city = document.getElementById('city');
		var state = document.getElementById('state');
		var zip	= document.getElementById('zip');
		
		WaitOn();
		
		// Check if tour title has a value.
		if (tourTitle.value.length == 0 || tourTitle.value == "Tour Title") {
			error = true;
			errorText += "You haven't entered a tour title.<br />";
		}
		
		// Check if property address has a value.
		if (propertyAddress.value.length == 0 || propertyAddress.value == "Property Address") {
			error = true;
			errorText += "You haven't entered a property address.<br />";
		}
		
		// Check if city has a value.
		if (city.value.length == 0 || city.value == "City") {
			error = true;
			errorText += "You haven't entered a city.<br />";
		}
		
		// Check if state has a value.
		if (state.value.length == 0 || state.value == "State") {
			error = true;
			errorText += "You haven't entered a state.<br />";
		}
		
		// Check if zip has a value.
		if (zip.value.length == 0 || zip.value == "Zip Code") {
			error = true;
			errorText += "You haven't entered a zip code.<br />";
		}
		
		// Validate state and city from the DB
		if (HTTP) {
		
			var dataSource = "checkout_address_info.php?zip=" + zip.value + "&city=" + city.value;
			HTTP.open("GET", dataSource); 
			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) { 
				
					
				
					if (HTTP.responseText.length < 2  && zip.value.length >= 5) {
						error = true;
						errorText += "The zip/city combination entered is not valid. <br />";
						
						var url = "checkout_cities.php";
						var params = "zip=" + zip.value;
						
						HTTP.open("POST", url, true);
						HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						HTTP.setRequestHeader("Content-length", params.length);
						HTTP.setRequestHeader("Connection", "close");

						HTTP.onreadystatechange = function() { 
							if (HTTP.readyState == 4 && HTTP.status == 200) {
								var cities = HTTP.responseText.split(",");
								if (cities.length > 0 && cities[0].length > 0) {
									errorText += 'Please select one of the following cities: <br />';
									for (var i=0;i < cities.length;i++) {
										errorText += '<span onclick="UpdateCity(' + String.fromCharCode(39) + cities[i] + String.fromCharCode(39) +');" class="selectable" >' + cities[i] + '</span><br />';
									}
								} else {
									errorText += "We were unable to find cities that matched your zip-code.<br />";
								}
								errorText += '</div>';
								errorDisplay.innerHTML = errorText;
								WaitOff();
								ToggleForm('formError');
							}
						}
						HTTP.send(params);
						
					} else {
					
						// Either report an error or move on to the next step.
						WaitOff();
						if (!error) {
							PrepStep2();
						} else {
							errorText += '</div>';
							errorDisplay.innerHTML = errorText;
							ToggleForm('formError');
						}
						
					}
				} 
			} 
			HTTP.send(null); 
		}
	} catch(err) {
		RecordError("step1Validate: " + err);
	}
}

function UpdateCity(cityName) {
	try {
		document.getElementById('city').value = cityName;
		ToggleForm('formError');
		step1Validate();
	} catch(err) {
		RecordError("UpdateCity: " + err);
	}
}

function PrepStep2() {
	try {
		var tours = document.getElementById('tours');
		var city = document.getElementById('city');
		var zip	= document.getElementById('zip');
		
		if (HTTP) {
			WaitOn();
			tours.innerHTML = "";
			ToggleStep(2);
			var dataSource = "checkout_list_tours.php?city=" + city.value + "&zip=" + zip.value + "&brokerid=" + brokerid;
			HTTP.open("GET", dataSource); 
			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) { 
					tours.innerHTML = HTTP.responseText;
					WaitOff();
					
					// If this is the second time around, they may have selected a tour already.
					// Make sure this gets highlighted.
					if (order_tourid != -1) {
						SelectTour(order_tourid);
					}
				}
			} 
			HTTP.send(null); 
		}
	} catch(err) {
		RecordError("PrepStep2: " + err);
	}
}

function step2Validate() {
	try {
		var error = false;
		var errorText = "";
		var errorDisplay = document.getElementById('errortext');
		
		WaitOn();  // Let the user know we are validating their data.
		
		if (order_tourid == -1) {
			error = true;
			errorText += "Please select a tour.";
		}
		
		// Either report an error or move on to the next step.
		WaitOff();
		if (!error) {
			PrepStep3();
		} else {
			errorDisplay.innerHTML = errorText;
			FormOn('formError');  // Display an error message.
		}
	} catch(err) {
		RecordError("step2Validate: " + err);
	}
}

function PrepStep3() {
	try {
		var city = document.getElementById('city').value;
		var zip	= document.getElementById('zip').value;
		if (HTTP) {
			WaitOn();
			document.getElementById("additionalproducts").innerHTML = "";
			ToggleStep(3);
			var url = "checkout_list_additionalproducts.php";
			var params = "city=" + city + "&zip=" + zip + "&brokerid=" + brokerid + "&id=" + order_tourid;
			
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					document.getElementById("additionalproducts").innerHTML = HTTP.responseText;
					if(typeof jump_to=="undefined"){
						
					}else{
						$('html,body').animate({scrollTop: $("#"+jump_to).offset().top-120},'fast');
					}
					SetProductButtons();
					GetAnother(); //Add the first room the vs.
					WaitOff();
					if(autoSetItem){
						SetItem(autoSetItemID, autoSetItemQ);
						PrepStep4();
					}
				}
			}
			HTTP.send(params);
		}
	} catch(err) {
		RecordError("PrepStep3: " + err);
	}
}

function PrepStep4() {
	try {
		if (HTTP) {
			WaitOn();
			ToggleStep(4);

			var url = "checkout_buildorder.php";
			
			var params = BuildPricingParams(); 
			
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					document.getElementById("checkoutarea").innerHTML = HTTP.responseText;
					WaitOff();
				}
			}
			HTTP.send(params);
		}
		
	} catch(err) {
		RecordError("PrepStep4: " + err);
	}
}

