// JavaScript Document

$(document).ready(
	function() {
		$('select[name="tour_country"]').change(function(){
			var country = $(this).val();
			if($("#tour_city").val()=='Sint Maarten'){
				$("#tour_city").val('');
			}
			if(country=="Canada"){
				$("#state_form_line").show();
				$("#state_form_line .input_title").html('Province');
				$("#tour_zip").val('');
			}else if(country=="Anguilla"){
				$("#state_form_line").hide();
				$("#tour_zip").val('AI-2640');
			}else if(country=="Sint Maarten"){
				$("#state_form_line").hide();
				$("#tour_city").val('Sint Maarten');
				$("#tour_zip").val('46226');
			}else{
				$("#state_form_line").show();
				$("#state_form_line .input_title").html('State');
				$("#tour_zip").val('');
			}
			$.getJSON("states-by-country.php",{country: country}, function(j){
			  var options = '';
			  for (var i = 0; i < j.length; i++) {
				options += '<option value="' + j[i].stateAbbrName + '">' + j[i].stateFullName + '</option>';
			  }
			  $("#tour_state").html(options);
			})
		});
	}
);

/*function GetStates() {
	try {
		var url = "checkout_xml_states.php";
		var params  = "";
		
		var HTTP = false;
		if (window.XMLHttpRequest) {
			HTTP = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			HTTP = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		if(HTTP) {
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					
					var states = HTTP.responseXML.getElementsByTagName("state");
					
					var state_list = Array();
					for(var i = 0; i < states.length; i++) {
						if(states[i].hasChildNodes()) {
							state_list[state_list.length] = states[i].childNodes[0].nodeValue;
						}
					}
					
					$("#tour_state").autocomplete(state_list);
				}
			}
			HTTP.send(params);
		}			
					
	} catch(err) {
		alert("GetStates: " + err);
	}
}*/

function GetAgents() {
	try {
		var url = "checkout_xml_agents.php";
		var params  = "";
		
		var HTTP = false;
		if (window.XMLHttpRequest) {
			HTTP = new XMLHttpRequest();
		} else if (window.ActiveXObject) {
			HTTP = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		if(HTTP) {
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					
					var agents = HTTP.responseXML.getElementsByTagName("agent");
					
					var agent_list = Array();
					for(var i = 0; i < agents.length; i++) {
						if(agents[i].hasChildNodes()) {
							agent_list[agent_list.length] = agents[i].childNodes[0].nodeValue;
						}
					}
					
					$("#tour_coagent").autocomplete(agent_list);
				}
			}
			HTTP.send(params);
		}			
					
	} catch(err) {
		alert("GetAgents: " + err);
	}
}

function CharacterCount(e, total_count) {
	try {
		
		if(document.getElementById(e.id) && document.getElementById('char_count')) {
			var char_count = document.getElementById(e.id).value.length;
			if (char_count <= total_count) {
				document.getElementById('char_count').innerHTML = (total_count - char_count) + ' Characters Left';
			} else {
				document.getElementById(e.id).value = document.getElementById(e.id).value.substring(0, total_count);
			}
		}
	} catch(err) {
		RecordError('CharacterCount: ' + err);
	}
}

function ValidateStep1() {
	try {
		var errors = "";
		if(document.getElementById('tour_title')) {
			if(document.getElementById('tour_title').value.length == 0) {
				errors += '<li>Please enter a tour title.</li>';
			}
		}
		if(document.getElementById('tour_address')) {
			if(document.getElementById('tour_address').value.length == 0) {
				errors += '<li>Please enter an address.</li>';
			}
		}
		if(document.getElementById('tour_state')) {
			if(document.getElementById('tour_state').value.length == 0) {
				errors += '<li>Please enter a state.</li>';
			}
		}
		if(document.getElementById('tour_city')) {
			if(document.getElementById('tour_city').value.length == 0) {
				errors += '<li>Please enter a city.</li>';
			}
		}
		if(document.getElementById('tour_zip')) {
			if(document.getElementById('tour_zip').value.length == 0) {
				errors += '<li>Please enter a zip.</li>';
			}
		}
		if(document.getElementById('tour_beds')) {
			if(document.getElementById('tour_beds').value.length == 0) {
				document.getElementById('tour_beds').value = 0;
			} else if (isNaN(document.getElementById('tour_beds').value)) {
				errors += '<li>Please enter only numbers for the number of beds.</li>';
			}
		}
		if(document.getElementById('tour_baths')) {
			if(document.getElementById('tour_baths').value.length == 0) {
				document.getElementById('tour_baths').value = 0;
			} else if (isNaN(document.getElementById('tour_baths').value)) {
				errors += '<li>Please enter only numbers for the number of baths.</li>';
			}
		}
		if(document.getElementById('tour_sqft')) {
			if (isNaN(document.getElementById('tour_sqft').value)) {
				errors += '<li>Please enter only numbers for the square footage.</li>';
			}
		}
		if(document.getElementById('tour_acres')) {
			if (isNaN(document.getElementById('tour_acres').value)) {
				errors += '<li>Please enter only numbers for the acres.</li>';
			}
		}
		if(document.getElementById('tour_price')) {
			if(document.getElementById('tour_price').value.length == 0) {
				document.getElementById('tour_price').value = 0;
			} else if (isNaN(document.getElementById('tour_price').value)) {
				errors += '<li>Please enter only numbers for the price.</li>';
			}
		}
		
		var multi_id = "mls_frame";
		var multi = document.getElementById(multi_id);
		var inputs = multi.getElementsByTagName("input");
		var selects = multi.getElementsByTagName("select");
		for(var i = 0; i < inputs.length; i++) {
			 if(inputs[i].value.length > 0) {
				if(selects[i].value=="-1"){
					errors += '<li>Please select an MLS provider for ID# '+inputs[i].value+'</li>';
				}
				if(!validate('alphanumeric', inputs[i].value)){
					errors += '<li>The MLS ID# can only contain letters or numbers. Please enter only one MLS ID# per line. Please fix MLS ID#: '+inputs[i].value+'</li>';
				}
			 }
		}
		
		
		if(errors.length > 0) {
			errors = "<ul>" + errors + "</ul>";
			ShowPopUp("Some data is missing ....", errors);
		} else {
			ValidateLocation();	
		}
	} catch(err) {
		alert("ValidateStep1: " + err);
	}	
}

function ValidateLocation() {
	try {
		var state = false;
		var city = false;
		var zip = false;
		if(document.getElementById("tour_state")) {
			state = document.getElementById("tour_state").value;
		}
		if(document.getElementById("tour_city")) {
			city = document.getElementById("tour_city").value;
		}
		if(document.getElementById("tour_zip")) {
			zip = document.getElementById("tour_zip").value;
		}
		
		if(state && city && zip) {
			var url = "checkout_xml_location_validation.php";
			var params  = "state=" + state;
				params += "&city=" + city;
				params += "&zip=" + zip;
			
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
						var valid = HTTP.responseXML.getElementsByTagName("valid");
						if(valid.length == 0) {
							var locations = HTTP.responseXML.getElementsByTagName("location");
							var info = "<div style='height:450px; overflow:auto;'>Are one of these correct? Please select one.<br />";
							for(var i = 0; i < locations.length; i++) {
								var state = false;
								var city = false;
								var zip = false;
								if(locations[i].hasChildNodes()) {
									for(var j = 0; j < locations[i].childNodes.length; j++) {
										//alert(locations[i].childNodes[j].nodeName);
										if(locations[i].childNodes[j].hasChildNodes()) {
											if(locations[i].childNodes[j].nodeName == "state") {
												state = locations[i].childNodes[j].childNodes[0].nodeValue;
											}
											
											if(locations[i].childNodes[j].nodeName == "city") {
												city = locations[i].childNodes[j].childNodes[0].nodeValue;
											}
											
											if(locations[i].childNodes[j].nodeName == "zip") {
												zip = locations[i].childNodes[j].childNodes[0].nodeValue;
											}
											
										}
									}
								}
								info += '<div class="selection_line" onclick="SelectLocation(this)" ><span class="city" >' + city + '</span>, <span class="state" >' + state + '</span> <span class="zip" >' + zip + '</span></div>';
							}
							info += '</div>';
							ShowPopUp("The location could not be found.", info);
						} else {
							ValidateCoAgent();
						}
					}
				}
				HTTP.send(params);
			}			
		}
	} catch(err) {
		alert("ValidateLocation: " + err);
	}
}

function SelectLocation(e) {
	try {
		var state = false;
		var city = false;
		var zip = false;
		var children = e.childNodes;
		for(var i = 0; i < children.length; i++) {
			if(children[i].className == "state") {
				state = children[i].innerHTML;
			}
			if(children[i].className == "city") {
				city = children[i].innerHTML;
			}
			if(children[i].className == "zip") {
				zip = children[i].innerHTML;
			}
		}
		
		if(state) {
			if(document.getElementById('tour_state')) {
				document.getElementById('tour_state').value = state;
			}
		}
		if(city) {
			if(document.getElementById('tour_city')) {
				document.getElementById('tour_city').value = city;
			}
		}
		if(zip) {
			if(document.getElementById('tour_zip')) {
				document.getElementById('tour_zip').value = zip;
			}
		}
		
		HidePopUp();
		ValidateStep1();
	} catch(err) {
		alert("SelectLocation: " + err);
	}	
}

function ValidateCoAgent() {
	try {
		if(document.getElementById('tour_coagent')) {
			if(document.getElementById('tour_coagent').value.length > 0) {
				var url = "checkout_xml_agent_id.php";
				var params  = "name=" + document.getElementById('tour_coagent').value;
				
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
							var coagent_id = false;
							var id = HTTP.responseXML.getElementsByTagName("id");
							if(id.length > 0) {
								
								for(var i = 0; i < id.length; i++) {
									if(id[i].hasChildNodes()) {
										coagent_id = id[i].childNodes[0].nodeValue;
									}
								}
								
								if(coagent_id.length > 0) {
									if(document.getElementById('coagent_id')) {
										document.getElementById('coagent_id').value = coagent_id;
									}
								}
								
								PackageStep1();
								
							} else {
								ShowPopUp("Unknown Co-Listing Agent.", "The co-listing agent could not be found in the system. Please be sure that you are using the name in which they registered with Spotlight Home Tours.");
							}
						}
					}
					HTTP.send(params);
				}
			} else {
				PackageStep1();
			}
		}
	} catch(err) {
		alert("ValidateCoAgent: " + err);
	}		
}

function PackageStep1() {
	try {
		var errors = "";
		if(document.getElementById('tour_title')) {
			order.title = document.getElementById('tour_title').value;
		}
		if(document.getElementById('tour_address')) {
			order.address = document.getElementById('tour_address').value;
		}
		if(document.getElementById('hide_address')) {
			if(document.getElementById('hide_address').checked == true) {
				order.hide_address = document.getElementById('hide_address').value;
			}
		}
		if(document.getElementById('tour_unitNumber')) {
			order.unitNumber = document.getElementById('tour_unitNumber').value;
		}
		if(document.getElementById('tour_state')) {
			order.state = document.getElementById('tour_state').value;
		}
		if(document.getElementById('tour_city')) {
			order.city = document.getElementById('tour_city').value;
		}
		if(document.getElementById('tour_zip')) {
			order.zip = document.getElementById('tour_zip').value;
		}
		if(document.getElementById('tour_beds')) {
			order.beds = document.getElementById('tour_beds').value;
		}
		if(document.getElementById('hide_beds')) {
			if(document.getElementById('hide_beds').checked == true) {
				order.hide_beds = document.getElementById('hide_beds').value;
			}
		}
		if(document.getElementById('tour_baths')) {
			order.baths = document.getElementById('tour_baths').value;
		}
		if(document.getElementById('hide_baths')) {
			if(document.getElementById('hide_baths').checked == true) {
				order.hide_baths = document.getElementById('hide_baths').value;
			}
		}
		if(document.getElementById('tour_sqft')) {
			order.sqft = document.getElementById('tour_sqft').value;
		}
		if(document.getElementById('tour_acres')) {
			order.acres = document.getElementById('tour_acres').value;
		}
		if(document.getElementById('hide_sqft')) {
			if(document.getElementById('hide_sqft').checked == true) {
				order.hide_sqft = document.getElementById('hide_sqft').value;
			}
		}
		if(document.getElementById('tour_price')) {
			order.price = document.getElementById('tour_price').value;
		}
		if(document.getElementById('hide_price')) {
			if(document.getElementById('hide_price').checked == true) {
				order.hide_price = document.getElementById('hide_price').value;
			}
		}
		if(document.getElementById('tour_descrip')) {
			order.desc = document.getElementById('tour_descrip').value;
		}
		if(document.getElementById('tour_add')) {
			order.add = document.getElementById('tour_add').value;
		}
		if(document.getElementById('coagent_id')) {
			order.coagent = document.getElementById('coagent_id').value;
		}
		
		var multi_id = "mls_frame";
		var multi = document.getElementById(multi_id);
		var inputs = multi.getElementsByTagName("input");
		var selects = multi.getElementsByTagName("select");
		order.mls = Array();
		order.mls_provider = Array();
		for(var i = 0; i < inputs.length; i++) {
			 if(inputs[i].value.length > 0) {
			 	order.mls[order.mls.length] = inputs[i].value;
				order.mls_provider[order.mls.length] = selects[i].value;
			 }
		}
		
		function cleanArray(actual){
		  var newArray = new Array();
		  for(var i = 0; i<actual.length; i++){
			  if (actual[i]){
				newArray.push(actual[i]);
			}
		  }
		  return newArray;
		}
		
		order.mls_provider = cleanArray(order.mls_provider);
		
		if(order.mls.length == 0) {
			ShowPopUp('No MLS Numbers', '<p>You have not entered any MLS numbers. For this reason, this tour will not link to the MLS.  Please Note: MLS numbers can be added later by editing the tour details.</p><ul><li><a onClick="ChangeStep(2);" style="cursor:pointer">OK, Thank You.</a></li><li><a onClick="HidePopUp(); setFocus(\'mls_number\');" style="cursor:pointer">I Want To Enter My MLS Numbers.</a></li></ul>');
		}else{
			ChangeStep(2);
		}
		
	} catch(err) {
		alert("PackageStep1: " + err);
	}	
}

function addMLSInput(){
	$('#mls_frame').append($('#mls_source').html());
}

function removeMLSInput(Obj){
	$(Obj).parent().parent().parent().remove();
}
