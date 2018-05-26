// JavaScript Document
var order = {
	membership_id:"",
	coupon:"",
	cc_name:"",
	cc_address:"",
	cc_city:"",
	cc_state:"",
	cc_zip:"",
	cc_type:"",
	cc_number:"",
	cc_month:"",
	cc_year:"",
	total:0
};

function GetCard(cardid) {
	try {
		var url = "checkout_xml_credit_card.php";
		var params  = "cardid=" + cardid;
		
		var HTTP = false;
		if (window.XMLHttpRequest) {
			HTTP = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			HTTP = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		if(HTTP) {
			ShowWait();
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					HideWait();
					var info = HTTP.responseXML.getElementsByTagName("card_info");
					for(var i = 0; i < info.length; i++) {
						if(info[i].hasChildNodes()) {
							for(var j = 0; j < info[i].childNodes.length; j++) {
								if(info[i].childNodes[j].nodeName == "name") {
									if(info[i].childNodes[j].hasChildNodes()) {
										if(document.getElementById('credit_name')) {
											document.getElementById('credit_name').value = info[i].childNodes[j].childNodes[0].nodeValue;
										}
									}
								} else if(info[i].childNodes[j].nodeName == "address") {
									if(info[i].childNodes[j].hasChildNodes()) {
										if(document.getElementById('credit_address')) {
											document.getElementById('credit_address').value = info[i].childNodes[j].childNodes[0].nodeValue;
										}
									}
								} else if(info[i].childNodes[j].nodeName == "city") {
									if(info[i].childNodes[j].hasChildNodes()) {
										if(document.getElementById('credit_city')) {
											document.getElementById('credit_city').value = info[i].childNodes[j].childNodes[0].nodeValue;
										}
									}
								} else if(info[i].childNodes[j].nodeName == "state") {
									if(info[i].childNodes[j].hasChildNodes()) {
										if(document.getElementById('credit_state')) {
											document.getElementById('credit_state').value = info[i].childNodes[j].childNodes[0].nodeValue;
										}
									}
								} else if(info[i].childNodes[j].nodeName == "zip") {
									if(info[i].childNodes[j].hasChildNodes()) {
										if(document.getElementById('credit_zip')) {
											document.getElementById('credit_zip').value = info[i].childNodes[j].childNodes[0].nodeValue;
										}
									}
								} else if(info[i].childNodes[j].nodeName == "type") {
									if(info[i].childNodes[j].hasChildNodes()) {
										if(document.getElementById('credit_type')) {
											var cardtypes = document.getElementById('credit_type').options;
											for (var k = 0; k < cardtypes.length; k++) {
												if (cardtypes[k].value == info[i].childNodes[j].childNodes[0].nodeValue) {
													cardtypes.selectedIndex = k;
												}
											}
										}
									}
								} else if(info[i].childNodes[j].nodeName == "number") {
									if(info[i].childNodes[j].hasChildNodes()) {
										if(document.getElementById('credit_number')) {
											document.getElementById('credit_number').value = info[i].childNodes[j].childNodes[0].nodeValue;
										}
									}
								} else if(info[i].childNodes[j].nodeName == "month") {
									if(info[i].childNodes[j].hasChildNodes()) {
										if(document.getElementById('credit_month')) {
											document.getElementById('credit_month').value = info[i].childNodes[j].childNodes[0].nodeValue;
										}
									}
								} else if(info[i].childNodes[j].nodeName == "year") {
									if(info[i].childNodes[j].hasChildNodes()) {
										if(document.getElementById('credit_year')) {
											document.getElementById('credit_year').value = info[i].childNodes[j].childNodes[0].nodeValue;
										}
									}
								}
								
							}
						}
					}
				}
			}
			HTTP.send(params);
		}		
	} catch(err) {
		window.alert("GetCard: " + err + ' (line: ' + err.line + ')');
	}		
}

function Terms() {
	try {
		var title = "Terms & Conditions";
		var tandc  = '<div class="terms" >';
			tandc += "The definition of a Spotlight Tour will be determined by the specific tour package ordered. Each package consists of different amounts of video, stills, and panoramic photographs. Each customer shall have the option to choose the specific rooms/scenes to be videotaped and or photographed through our online order form. Rooms/scenes can be changed prior to shooting by contacting the videographer and or photographer. Each customer will also have the opportunity to leave the room/scene decisions up to videographers and or photographers discretion by checking the box on the online order form or contacting the videographer and/or photographer prior to shooting.<br />";
			tandc += "Once the tour is processed by Spotlight Tours, it will be posted on www.spotlighthometours.com. The URL to the tour will be sent to the email address given to Spotlight Tours by the purchaser. This URL can be freely used to create a link to/from your own personal website.<br />";
			tandc += "Each room/scene ordered will be made into a video, panoramic, and or still photos tour depending on tour package ordered. Spotlight Tours reserves the right to determine which scenes to use that are in the best interest of the agent, homeowner or other property owner. Choices not to show a particular room/scene due to poor lighting, weather, unclean rooms/scenes, etc. will be left up to the discretion of Spotlight Tours editing department.<br />";	 
			tandc += "Spotlight Tours will post the tour ordered to Spotlighthometours website only upon receiving all required information. including, but not limited to; asking price, property address, square footage, number of rooms, etc. Spotlight Tours will not be responsible for any delays due to missing information. <br />";
			tandc += "All Spotlight tours will become inactive after the date specified at the time of purchase. The tour may be reactivated at any time upon request.  Spotlight Tours reserved the right to assess a reactivation fee and/or an annual fee for any tour that is hosted by Spotlight Tours beyond the period of one year.<br />";
			tandc += "All images and media are considered the sole and exclusive property of Spotlight Tours. Spotlight Tours reserves all rights to the images, media and content. All images and media shall be made available to the client until the property is sold, the listing expires or twelve (12) months, whichever occurs first, unless otherwise agreed upon in writing previously.<br />";
			tandc += "The customer hereby expressly waives any and all claims, demands, costs, and causes of action against Spotlight Tours, its agents, contractors, employees and assigns, for any and all damages, including property and personal injury, for any acts of the videographers and/or photographers at the property designated by the agent, homeowner or other property owner.<br />";
			tandc += "Payment Terms: Payment in full is expected at the time of order unless otherwise previously arranged.  If company billing invoice is not paid in full within thirty (30) days following submission of the invoice by Spotlight Tours an interest rate of 2.0% per month will be charged on past due balances (24% per annum).  The property, including all videotapes, photographs and all other work completed by Spotlight Tours produced for the customer remains the property of Spotlight Tours.<br />";
			tandc += "In the event of any action at law or inequity between the customer and Spotlight Tours to enforce any of the provisions and/or rights hereunder to recover damages for breach hereof, the unsuccessful party to such litigation covenants and agrees to pay to the successful party all costs and expenses, including attorney fees incurred herein by such successful party, and if such successful party shall recover judgment in any such action or proceeding such costs and expenses and attorney fees shall be included in and as part of such judgment.<br />";
			tandc += "Any and all changes or modifications of the terms and conditions of this agreement must be in writing and executed by both Spotlight Tours and customer.<br />";
			tandc += "The terms of this Agreement shall be governed by the laws and regulations of the State of Utah. The State of Utah shall have exclusive jurisdiction with respect to any and all legal actions that may arise from this Agreement.<br />";
			tandc += "This Agreement shall be considered to be the only agreement between the parties. All negotiations and oral agreements acceptable to both parties are included herein.<br />";
			tandc += "The parties agree that all of the provisions hereof are to be considered as covenants and agreements where used in each separate paragraph hereof, and that all of the provisions hereof shall bind and inure to the benefit of the parties hereto, and their respective heirs, legal representative, agents, successors and assigns.<br />";	
			tandc += "</div>";
		ShowPopUp(title, tandc);
	} catch(err) {
		window.alert("Terms: " + err + ' (line: ' + err.line + ')');
	}	
}

function ValidateStep4() {
	try {
		var errors = "";
		if (order.total > 0) {
			if(document.getElementById('credit_name')) {
				if (document.getElementById('credit_name').value.length == 0) {
					errors += '<li>Please enter the name on the credit card.</li>';
				} else {
					order.cc_name = document.getElementById('credit_name').value;
				}
			}
			if(document.getElementById('credit_address')) {
				if (document.getElementById('credit_address').value.length == 0) {
					errors += '<li>Please enter the billing address of the credit card.</li>';
				} else {
					order.cc_address = document.getElementById('credit_address').value;
				}
			}
			if(document.getElementById('credit_city')) {
				if (document.getElementById('credit_city').value.length == 0) {
					errors += '<li>Please enter the billing city of the credit card.</li>';
				} else {
					order.cc_city = document.getElementById('credit_city').value;
				}
			}
			if(document.getElementById('credit_state')) {
				if (document.getElementById('credit_state').value.length == 0) {
					errors += '<li>Please enter the billing state of the credit card.</li>';
				} else {
					order.cc_state = document.getElementById('credit_state').value;
				}
			}
			if(document.getElementById('credit_zip')) {
				if (document.getElementById('credit_zip').value.length == 0) {
					errors += '<li>Please enter the billing zip code of the credit card.</li>';
				} else if (isNaN(document.getElementById('credit_zip').value)) {
					errors += '<li>Please enter only numbers for the zip code of the credit card.</li>';
				} else {
					order.cc_zip = document.getElementById('credit_zip').value;
				}
			}
			if(document.getElementById('credit_number')) {
				if (document.getElementById('credit_number').value.length == 0) {
					errors += '<li>Please enter the credit card number.</li>';
				} else if (isNaN(document.getElementById('credit_number').value)) {
					errors += '<li>Please enter only numbers for the credit card number.</li>';
				} else {
					order.cc_number = document.getElementById('credit_number').value;
				}
			}
			if(document.getElementById('credit_month')) {
				if (document.getElementById('credit_month').value.length == 0) {
					errors += '<li>Please enter the credit card expiration month.</li>';
				} else if (isNaN(document.getElementById('credit_month').value)) {
					errors += '<li>Please enter a number for the credit card expiration month.</li>';
				} else {
					order.cc_month = document.getElementById('credit_month').value;
				}
			}
			if(document.getElementById('credit_year')) {
				if (document.getElementById('credit_year').value.length == 0) {
					errors += '<li>Please enter the credit card expiration year.</li>';
				} else if (isNaN(document.getElementById('credit_year').value)) {
					errors += '<li>Please enter a number for the credit card expiration year.</li>';
				} else {
					order.cc_year = document.getElementById('credit_year').value;
				}
			}
			if(document.getElementById('credit_type')) {
				order.cc_type = document.getElementById('credit_type').options[document.getElementById('credit_type').selectedIndex].value
			}
			 
		}
		
		if(document.getElementById('accept')) {
			if (document.getElementById('accept').checked == false) {
				errors += '<li>Please accept the Terms and Conditions.</li>';
			}
		}
		
		
		if(errors.length > 0) {
			errors = "<ul>" + errors + "</ul>";
			ShowPopUp("Some data is missing ...", errors);
		} else {
			SubmitOrder();
		}
	} catch(err) {
		window.alert("ValidateStep4: " + err + ' (line: ' + err.line + ')');
	}
}

function SubmitOrder() {
	try {
				
	} catch(err) {
		window.alert("SubmitOrder: " + err + ' (line: ' + err.line + ')');
	}	
}

function OrderSuccess(orderid, tourid) {
	try {
		if(document.getElementById("pop_up_title_frame")) {
			document.getElementById("pop_up_title_frame").style.display = "none";
		}

		ShowPopUp("", complete);
		
	} catch(err) {
		window.alert("OrderSuccess: " + err + ' (line: ' + err.line + ')');
	}		
}