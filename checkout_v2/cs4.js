// JavaScript Document

var transactionType = 'cc';

$(document).ready(
	function() {
		try {
			$('.credit_frame .tc-info').hide();
			$('.credit_frame select[name="credit_type"]').change(function(){
				if($(this).val()=="check"){
					if(transactionType=='cc'){
						$('.credit_frame .cc-info').slideToggle('slow', function(){
							$('.credit_frame .tc-info').slideToggle('slow');
						});
						$("#form_save_cc").fadeOut('slow');
						$("#saved-ccs").fadeOut('slow');
						transactionType = 'tc';
					}
				}else{
					if(transactionType=='tc'){
						$('.credit_frame .tc-info').slideToggle('slow', function(){
							$('.credit_frame .cc-info').slideToggle('slow');
						});
						$("#form_save_cc").fadeIn('slow');
						$("#saved-ccs").fadeIn('slow');
						transactionType = 'cc';
					}
				}
			});
            
            $("#checklistCheckbox").bind("click",function(){
                if( $(this).is(":checked") ){
                    $("div.checklist-dropdown-hidden").attr("class","checklist-dropdown").hide().slideDown("slow");
                }else{
                    $("div.checklist-dropdown").slideUp("slow",function(){
                        $(this).attr("class","checklist-dropdown-hidden");
                    });
                }
            });

		} catch(err) {
			window.alert("onload step4: " + err + ' (line: ' + err.line + ')');
		}
	}
);

function PopulateCheckout() {
	try {
		//alert("PopulateCheckout");
		
		var url = "checkout_xml_order_table.php";
		var params  = "city=" + order.city + "&zip=" + order.zip + "&tourtype=" + order.tourtypeid + "&sqft=" + order.sqft + "&price=" + order.price + "&paymentPlanID=" + order.paymentPlanID;
		if(additional_product){
			params  += "&additional_product=true";
		}
		if(diy_order){
			params += "&DIYMembership=true";
		}
		
		if($("#usePaySold").is(':checked')){
			params  += "&usePaySold=1";
		}else{
			params  += "&usePaySold=0";
		}
		
		if(document.getElementById('checkout_coupon')) {
			order.coupon = document.getElementById('checkout_coupon').value;
		}
		params += "&coupon=" + order.coupon;
		showShippingAddress = false;
		
		if(order.prod.length > 0) {
			params += "&products=";
			for(var i = 0; i < order.prod.length; i++) {
				if(order.prod[i].qty > 0) {
					params += order.prod[i].id + "," + order.prod[i].qty + ";";
				}
			}
		}

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
					if(document.getElementById('checkout_table')) {
						document.getElementById('checkout_table').innerHTML = HTTP.responseText;
					}
				}
			}
			HTTP.send(params);
		}		
	} catch(err) {
		window.alert("PopulateCheckout: " + err + ' (line: ' + err.line + ')');
	}	
}


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
		var tandc  = '<div style="height:300px;overflow:auto;padding-right:15px;"><p>The definition of a Spotlight tour will be determined by the specific package ordered. Each package consists of different ';
	    tandc += 'amounts of video, stills, and panoramic photographs. Each customer shall have the option to choose the specific ';
	    tandc += 'rooms/scenes/scenery to be videotaped and or photographed through our online order form. Rooms/scenes/scenery can be ';
	    tandc += 'changed prior to shooting by contacting the videographer and or photographer. Each customer will also have the opportunity ';
	    tandc += 'to leave the room/scene/scenery decisions up to videographers and or photographers discretion by checking the box on the ';
	    tandc += 'online order form or contacting the videographer and/or photographer prior to shooting.</p>';
	    tandc += '<p>Once the tour is processed by Spotlight, it will be posted on www.spotlighthometours.com. The URL to the tour will be sent to ';
	    tandc += 'the email address given to Spotlight Tours by the purchaser. This URL can be freely used to create a link to/from your own ';
	    tandc += 'personal website.</p>';
	    tandc += '<p>Each room/scene/scenery ordered will be made into a video, panoramic, and or still photos tour depending on tour package ';
	    tandc += 'ordered. Spotlight reserves the right to determine which scenes to use that are in the best interest of the agent, homeowner or ';
	    tandc += 'other property owner. Choices not to show a particular room/scene/scenery will be the sole and absolute choice of Spotllight ';
	    tandc += 'and/or its affiliated editing department.</p>';
	    tandc += '<p>Spotlight will post the tour to the website only upon receiving all required information. including, but not limited to; asking ';
	    tandc += 'price, property address, square footage, number of rooms, etc. Spotlight will not be responsible for any delays due to missing ';
	    tandc += 'information. </p>';
	    tandc += '<p>All Spotlight tours will become inactive after the date specified at the time of purchase. The tour may be reactivated at any time ';
	    tandc += 'upon request. Spotlight Tours reserved the right to assess a reactivation fee and/or an annual fee for any tour that is hosted by ';
	    tandc += 'Spotlight Tours beyond the period of one year.</p>';
	    tandc += '<p>All images and media are considered the sole and exclusive property of Spotlight. Spotlight reserves all rights to the images, ';
	    tandc += 'media and content. All images and media shall be made available to the client until the property is sold, the listing expires or ';
	    tandc += 'twelve (12) months, whichever occurs first, unless otherwise agreed upon in writing previously. If the photos are being used for ';
	    tandc += 'nightly rental and/or vacation rental purposes then the client has full licensing of the images for marketing purposes of the';
	    tandc += 'specific property with no end date of use. </p>';
	    tandc += '<p>The customer hereby expressly waives any and all claims, demands, costs, and causes of action against Spotlight, its agents, ';
	    tandc += 'contractors, employees and assigns, for any and all damages, including property and personal injury, for any acts of the ';
	    tandc += 'videographers and/or photographers at the property designated by the agent, homeowner or other property owner.';
	    tandc += 'Payment Terms: Payment in full is expected at the time of order unless otherwise previously arranged. If company billing ';
	    tandc += 'invoice is not paid in full within thirty (30) days following submission of the invoice by Spotlight an interest rate of 2.0% per ';
	    tandc += 'month will be charged on past due balances (24% per annum). The property, including all videotapes, photographs and all other';
	    tandc += 'work completed by Spotlight produced for the customer remains the property of Spotlight Tours.</p>';
	    tandc += '<p>In the event of any action at law or inequity between the customer and Spotlight to enforce any of the provisions and/or rights ';
	    tandc += 'hereunder to recover damages for breach hereof, the unsuccessful party to such litigation covenants and agrees to pay to the ';
	    tandc += 'successful party all costs and expenses, including attorney fees incurred herein by such successful party, and if such successful ';
	    tandc += 'party shall recover judgment in any such action or proceeding such costs and expenses and attorney fees shall be included in ';
	    tandc += 'and as part of such judgment.</p>';
	    tandc += '<p>Any and all changes or modifications of the terms and conditions of this agreement must be in writing and executed by both ';
	    tandc += 'Spotlight and customer. The terms of this Agreement shall be governed by the laws and regulations of the State of Utah. The ';
	    tandc += 'State of Utah shall have exclusive jurisdiction with respect to any and all legal actions that may arise from this Agreement. This ';
	    tandc += 'Agreement shall be considered to be the only agreement between the parties. All negotiations and oral agreements acceptable ';
	    tandc += 'to both parties are included herein. The parties agree that all of the provisions hereof are to be considered as covenants and agreements where used in each ';
	    tandc += 'separate paragraph hereof, and that all of the provisions hereof shall bind and inure to the benefit of the parties hereto, and ';
	    tandc += 'their respective heirs, legal representative, agents, successors and assigns.</p>';
	    tandc += '<p>Spotlight Tours will use reasonable effort to being flexible to film dates with respect to weather. However, it should be ';
	    tandc += 'anticipated that filming may take place regardless of weather or adverse conditions.</p></div>';
		ShowPopUp(title, tandc);
	} catch(err) {
		window.alert("Terms: " + err + ' (line: ' + err.line + ')');
	}	
}

function ValidateStep4() {
	//alert('ValidateStep4()');
	try {
		var errors = "";
		if (order.total > 0 || $("#usePaySold").is(':checked')) {
			if(document.getElementById('credit_name')) {
				if (document.getElementById('credit_name').value.length == 0) {
					errors += '<li>Please enter the name on the credit card/check.</li>';
				} else {
					order.cc_name = document.getElementById('credit_name').value;
				}
			}
			if(document.getElementById('credit_address')) {
				if (document.getElementById('credit_address').value.length == 0) {
					errors += '<li>Please enter the billing address of the credit card/check.</li>';
				} else {
					order.cc_address = document.getElementById('credit_address').value;
				}
			}
			if(document.getElementById('credit_city')) {
				if (document.getElementById('credit_city').value.length == 0) {
					errors += '<li>Please enter the billing city of the credit card/check.</li>';
				} else {
					order.cc_city = document.getElementById('credit_city').value;
				}
			}
			if(document.getElementById('credit_state')) {
				if (document.getElementById('credit_state').value.length == 0) {
					errors += '<li>Please enter the billing state of the credit card/check.</li>';
				} else {
					order.cc_state = document.getElementById('credit_state').value;
				}
			}
			if(document.getElementById('credit_zip')) {
				if (document.getElementById('credit_zip').value.length == 0) {
					errors += '<li>Please enter the billing zip code of the credit card/check.</li>';
				} else if (isNaN(document.getElementById('credit_zip').value)) {
					errors += '<li>Please enter only numbers for the zip code of the credit card/check.</li>';
				} else {
					order.cc_zip = document.getElementById('credit_zip').value;
				}
			}
			if(transactionType=='cc'){
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
			}else{
				if(document.getElementById('routing_number')) {
					if (document.getElementById('routing_number').value.length == 0) {
						errors += '<li>Please enter the routing number.</li>';
					} else if (isNaN(document.getElementById('routing_number').value)) {
						errors += '<li>Please enter only numbers for the routing number.</li>';
					} else {
						order.tc_rnumber = document.getElementById('routing_number').value;
					}
				}
				if(document.getElementById('account_number')) {
					if (document.getElementById('account_number').value.length == 0) {
						errors += '<li>Please enter the account number.</li>';
					} else if (isNaN(document.getElementById('account_number').value)) {
						errors += '<li>Please enter only numbers for the account number.</li>';
					} else {
						order.tc_anumber = document.getElementById('account_number').value;
					}
				}
				if(document.getElementById('check_number')) {
					if (document.getElementById('check_number').value.length == 0) {
						errors += '<li>Please enter the check number.</li>';
					} else if (isNaN(document.getElementById('check_number').value)) {
						errors += '<li>Please enter only numbers for the check number.</li>';
					} else {
						order.tc_cnumber = document.getElementById('check_number').value;
					}
				}
				order.tc_atype = document.getElementById('account_type').options[document.getElementById('account_type').selectedIndex].value;
				if(document.getElementById('dl_number')) {
					if (document.getElementById('dl_number').value.length == 0) {
						errors += '<li>Please enter your drivers license number.</li>';
					} else if (isNaN(document.getElementById('dl_number').value)) {
						errors += '<li>Please enter only numbers for your drivers license number.</li>';
					} else {
						order.tc_dlnumber = document.getElementById('dl_number').value;
					}
				}
				if(document.getElementById('dl_state')) {
					if (document.getElementById('dl_state').options[document.getElementById('dl_state').selectedIndex].value == "") {
						errors += '<li>Please select your drivers license state.</li>';
					} else {
						order.tc_dlstate = document.getElementById('dl_state').options[document.getElementById('dl_state').selectedIndex].value;
					}
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
		
		if($("#usePaySold").is(':checked')) {
			if(!($("#save").is(':checked'))) {
				errors += '<li>Please check "Save or Update this card in my account". (Neccessary for Pay When Sold).</li>';
			}
		}
				
        if($("#checklistCheckbox").is(":checked")){
            if( $("#checklistEmail").val().length == 0 ){
                errors += "<li>Please enter a valid email address</li>";
            }else{
                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                if( !re.test($("#checklistEmail").val()) ){
                    errors += "<li>Please enter a valid email address</li>";
                }
            }
        }

//		isShipping = false;
//		for(var i = 0; i < order.prod.length; i++) {
//			if(order.prod[i].id == 56 || order.prod[i].id == 34)
//				isShipping = true;
//		}
//		if (isShipping === true) {
//			if(document.getElementById('shippingAddress')) {
//				if (document.getElementById('shippingAddress').value.length == 0) {
//					errors += '<li>Please enter the Shipping Address.</li>';
//				} else {
//					order.shipAddress = document.getElementById('shippingAddress').value;
//				}
//			}
//			if(document.getElementById('shippingCity')) {
//				if (document.getElementById('shippingCity').value.length == 0) {
//					errors += '<li>Please enter the Shipping City.</li>';
//				} else {
//					order.shipCity = document.getElementById('shippingCity').value;
//				}
//			}
//			if(document.getElementById('shippingState')) {
//				if (document.getElementById('shippingState').value.length == 0) {
//					errors += '<li>Please enter the Shipping State.</li>';
//				} else {
//					order.shipState = document.getElementById('shippingState').value;
//				}
//			}
//			if(document.getElementById('shippingZip')) {
//				if (document.getElementById('shippingZip').value.length == 0) {
//					errors += '<li>Please enter the Shipping Zipcode.</li>';
//				} else if (isNaN(document.getElementById('shippingZip').value)) {
//					errors += '<li>Please enter a number for the Shipping Zipcode.</li>';
//				} else {
//					order.shipZip = document.getElementById('shippingZip').value;
//				}
//			}
//		}
		
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

function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++){
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam){
            return sParameterName[1];
        }
    }
}

function SubmitOrder(){
	try {
		var tourid = document.getElementById('tourid').value;
		var url = "checkout_xml_submit_order.php";
		
		var params = "";
		for (name in order){
			if(name != 'prod' && name != 'total')
				params += "&" + name + "=" + order[name];
		}
		if(tourid>0){
			params  += "&tourid="+tourid;
		}
		
		if($("#save").is(':checked')){
			params  += "&save_cc=1";
		}else{
			params  += "&save_cc=0";
		}
		
		if($("#usePaySold").is(':checked')){
			params  += "&usePaySold=1";
		}else{
			params  += "&usePaySold=0";
		}

        if( $("#checklistCheckbox").is(":checked") && $("#checklistEmail").val().length > 0){
            params += "&checklistEmail=" + $("#checklistEmail").val();
        }
		
		// Add add prod to the params
		if(order.prod.length > 0) {
			params += "&products=";
			for(var j = 0; j < order.prod.length; j++) {
				if(order.prod[j].qty > 0) {
					params += order.prod[j].id + "," + order.prod[j].qty + ";";
				}
			}
			for(var k = 0; k < order.prod.length; k++) {
				if(order.prod[k].qty > 0) {
					if(order.addMedia[order.prod[k].id]){
						for(var l = 0; l < order.addMedia[order.prod[k].id].length; l++) {
							params += "&prodmediaatts["+order.prod[k].id+"][]="+order.addMedia[order.prod[k].id][l];
						}
					}
				}
			}
		}
		
		if(diy_order){
			params += "&DIYMembership=true";
		}
		
		params += "&session_id="+sess_id;
		
        notOrdered = GetURLParameter("notOrdered");
        if(  typeof notOrdered != 'undefined' && notOrdered.length ){
            params += "&notOrdered=" + notOrdered;
        }
        n = GetURLParameter("n");
        if( typeof n != 'undefined' && n.length ){
            params += "&n=1";
        }
		console.log(params);
		//exit();

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
			$("#subOrderBtn").attr("onclick", "");
			$("#subOrderBtn").unbind('click');
			$("#subOrderBtn").click(function(){alert('Order processing... Please wait!')});
			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					HideWait();
					//alert(HTTP.response);
					var errors = HTTP.responseXML.getElementsByTagName("error");
					
					var error = "";
					for(var i = 0; i < errors.length; i++) {
						if(errors[i].hasChildNodes()) {
							error += errors[i].childNodes[0].nodeValue;
						}
					}
					if (error.length > 0) {
						error = "<ul>" + error + "</ul>";
						$("#subOrderBtn").attr("onclick", "");
						$("#subOrderBtn").unbind('click');
						$("#subOrderBtn").click(function(){ValidateStep4()});
						ShowPopUp("There was an error with your order.", error);
					} else {
						$("#subOrderBtn").attr("onclick", "");
						$("#subOrderBtn").unbind('click');
						$("#subOrderBtn").click(function(){alert('Order is complete. You can not process the same order twice.')});
						// Get order info from return XML
						var orderids = HTTP.responseXML.getElementsByTagName("orderid");
						var orderid = "";
						for(var i = 0; i < orderids.length; i++) {
							if(orderids[i].hasChildNodes()) {
								orderid += orderids[i].childNodes[0].nodeValue;
							}
						}
						var tourids = HTTP.responseXML.getElementsByTagName("tourid");
						var tourid = "";
						for(var i = 0; i < tourids.length; i++) {
							if(tourids[i].hasChildNodes()) {
								tourid += tourids[i].childNodes[0].nodeValue;
							}
						}
						//alert("order ID: "+orderid+". Tour ID: "+tourid);
						OrderSuccess(orderid, tourid);
					}
				}
			}
			HTTP.send(params);
		}		
	} catch(err) {
		window.alert("SubmitOrder: " + err + ' (line: ' + err.line + ')');
	}	
}

function OrderSuccess(orderid, tourid) {
	try {
		
		if(document.getElementById("pop_up_title_frame")) {
			document.getElementById("pop_up_title_frame").style.display = "none";
		}
		
		checkUnload = false;
		ShowPopUp("", complete);
		
		if(document.getElementById("complete_info")) {
			document.getElementById("complete_info").innerHTML = orderid + "<br />" + tourid;
		}
		$.ajax({
            url : '/repository_queries/user_checkdomain.php',
            data: {
                'tourId': tourid
            }
        }).done(function(msg){
            $.ajax({
                url: '/repository_queries/user_checkdomain.php',
                data:{
                    'orderCompleted': 1
                }
            }).done(function(msg){});
        });
	} catch(err) {
		window.alert("OrderSuccess: " + err + ' (line: ' + err.line + ')');
	}		
}

function selectPaymentPlan(paymentPlanID){
	var paymentPlanRow = $("#paymentplan_"+paymentPlanID);
	if($(paymentPlanRow).find("a").hasClass('selected')){
		$(paymentPlanRow).find("a").removeClass('selected');
		$(paymentPlanRow).find("a").html('select');
		order.paymentPlanID = 0;
	}else{
		$(paymentPlanRow).find("a").addClass('selected');
		$(paymentPlanRow).find("a").html('selected');
		order.paymentPlanID = paymentPlanID;
	}
	PopulateCheckout();
}
