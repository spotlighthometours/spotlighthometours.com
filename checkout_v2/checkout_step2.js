// JavaScript Document

function PopulatePackages() {
	try {
		var url = "checkout_template_list_tour_packages.php";
		var params  = "city=" + order.city + "&zip=" + order.zip;
		
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
					if(document.getElementById('tour_packages')) {
						document.getElementById('tour_packages').innerHTML = HTTP.responseText;
						SetTourPackage();
					}
				}
			}
			HTTP.send(params);
		}		
	} catch(err) {
		window.alert("SelectPackage: " + err + ' (line: ' + err.line + ')');
	}
}

function SelectPackage(e) {
	try {
		var divs = document.getElementById('tour_packages').getElementsByTagName("div");
		if(e.id == ("tt_" + order.tourtypeid)) {
			order.tourtypeid = "";
			e.className = 'button_new button_tour button_sm';
			var caption = e.getElementsByTagName("span");
			for (var j = 0; j < caption.length; j ++) {
				caption[j].innerHTML = "Select";
			}
			e.parentNode.parentNode.setAttribute('style', "");
		} else {
			if($(e).data('required')){
				var requiredFields = $(e).data('required');
				requiredFields = requiredFields.split("::");
				for (key in requiredFields) {
					if(order[requiredFields[key]]>0){
						// proceed required is set
					}else{
						// HALT! Required is not set!
						collectRequiredP(requiredFields[key], e.id);
						return false;
					}
				}
			}
			order.tourtypeid = e.id.substring(e.id.indexOf("_") + 1);
			for (var i = 0; i < divs.length; i ++) {
				if(divs[i].id.indexOf('tt_') > -1) {
					if(divs[i].id == e.id) {
						divs[i].className = 'button_new button_blue button_sm';
						var caption = divs[i].getElementsByTagName("span");
						for (var j = 0; j < caption.length; j ++) {
							caption[j].innerHTML = "Selected";
						}
						divs[i].parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
					} else {
						divs[i].className = 'button_new button_tour button_sm';
						var caption = divs[i].getElementsByTagName("span");
						for (var j = 0; j < caption.length; j ++) {
							caption[j].innerHTML = "Select";
						}
						divs[i].parentNode.parentNode.setAttribute('style', "");
					}
				}
			}
		}
		
		GetOrderTotal();
	} catch(err) {
		window.alert("SelectPackage: " + err + ' (line: ' + err.line + ')');
	}
}

function collectRequiredP(requiredField, selectedID){
	var intro = {sqft: 'The property square footage is required for this tour type. Please enter the property square footage below.', price: 'The property list price is required for this tour type. Please enter the property list price below.'};
	var fieldTitle = {sqft: 'Sq. Ft.', price: 'Price'};
	var infoTxt = {sqft: 'No commas.', price: 'No "$" or ",".'}
	var html = '<p>'+intro[requiredField]+'</p>';
	html+='<div class="form_line">';
	html+='  <div class="input_line w_sm">';
	html+='    <div class="input_title">'+fieldTitle[requiredField]+'</div>';
	html+='    <input id="required_'+requiredField+'" name="required_'+requiredField+'" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">';
	html+='    <div class="input_info" style="display: none;">';
	html+='      <div class="info_text">'+infoTxt[requiredField]+'</div>';
	html+='    </div>';
	html+='  </div>';
	html+='	 <div class="required_line w_sm">';
	html+='     <span class="required">required</span>';
	html+='	 </div>';
	html+='</div>';	
	html+='<div class="grey-divider" style="margin-bottom:10px;"></div>';
	html+='<br/>';
	html+='<table cellpadding="5">';
	html+='  <tr>';
	html+='    <td><div class="button_new button_blue button_mid" onclick="saveRequiredAndSelectP(\''+requiredField+'\', \''+selectedID+'\')">';
	html+='        <div class="curve curve_left" ></div>';
	html+='        <span class="button_caption" >Save &amp; Select</span>';
	html+='        <div class="curve curve_right" ></div>';
	html+='      </div></td>';
	html+='    <td><div class="button_new button_dgrey button_mid" onclick="HidePopUp()">';
	html+='        <div class="curve curve_left" ></div>';
	html+='        <span class="button_caption" >Cancel</span>';
	html+='        <div class="curve curve_right"></div>';
	html+='      </div></td>';
	html+='  </tr>';
	html+='</table>';
	ShowPopUp('Property '+fieldTitle[requiredField]+' Required!',html);
}

function saveRequiredAndSelectP(requiredField, selectedID){
	var enteredVal = $("input[name='required_"+requiredField+"']").val();
	if(isNaN(enteredVal)||enteredVal.length==0){
		alert("Please enter a valid value for the required field. Numbers only!");
	}else{
		$("input[name='tour_"+requiredField+"']").val($("input[name='required_"+requiredField+"']").val());
		order[requiredField] = enteredVal;
		console.log(selectedID);
		HidePopUp();
		SelectPackage(document.getElementById(selectedID));		
	}
}

function UpgradePackage(standard_id, upgrade_id, upgrade_doc) {
	try {
		if(order.tourtypeid == standard_id || upgrade_doc.length == 0) {
			if(document.getElementById("tt_" + 	standard_id)) {
				SelectPackage(document.getElementById("tt_" + standard_id));
			}
		} else {
			var url = "upgrade_doc/" + upgrade_doc;
			var params  = "";
			
			var selection  = '<div class="button_new button_blue button_mid" onclick="SelectUpgrade(' + upgrade_id + ');">';
				selection += '	<div class="curve curve_left" ></div>';
				selection += '	<span class="button_caption" >Continue</span>';
				selection += '	<div class="curve curve_right" ></div>';
				selection += '</div>';
				selection += '<div class="button_new button_blue button_mid" onclick="SelectUpgrade(' + standard_id + ');">';
				selection += '	<div class="curve curve_left" ></div>';
				selection += '	<span class="button_caption" >Continue</span>';
				selection += '	<div class="curve curve_right" ></div>';
				selection += '</div>';
			var selection  = '<div class="upgrade_frame" >';
				selection += '<div class="yes" onclick="SelectUpgrade(' + upgrade_id + ');" ></div>';
				selection += '<div class="no" onclick="SelectUpgrade(' + standard_id + ');" >No thanks, select my original option.</div>';
				selection += '</div>';
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
						ShowPopUp("Upgrade for additional features!", HTTP.responseText + selection);
					}
				}
				HTTP.send(params);
			}
		}
	} catch(err) {
		window.alert("UpgradePackage: " + err + ' (line: ' + err.line + ')');
	}
}

function SelectUpgrade(package_id) {
	try {
		if(document.getElementById("tt_" + 	package_id)) {
			if(package_id != order.tourtypeid) {
				SelectPackage(document.getElementById("tt_" + 	package_id));
			}
		}
		//order.tourtypeid = parseInt(package_id);
		//SetTourPackage();
		HidePopUp();
	} catch(err) {
		window.alert("SelectUpgrade: " + err + ' (line: ' + err.line + ')');
	}	
}

function SetTourPackage() {
	try {
		if(order.tourtypeid != "") {
			if(document.getElementById("tt_" + 	order.tourtypeid)) {
				var package = document.getElementById("tt_" + 	order.tourtypeid);
				package.className = 'button_new button_blue button_sm';
				var caption = package.getElementsByTagName("span");
				for (var j = 0; j < caption.length; j ++) {
					caption[j].innerHTML = "Selected";
				}
				package.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
			} else {
				order.tourtypeid = "";	
			}
			GetOrderTotal();
		}
	} catch(err) {
		window.alert("SetTourPackage: " + err + ' (line: ' + err.line + ')');
	}		
}

function ValidateStep2(nxtStep) {
	try {
		if (typeof nxtStep == "undefined") {
    		nxtStep = 3;
  		}
		var errors = "";
		if (order.tourtypeid == "") {
			errors += '<li>Please select a tour package.</li>';
		}
		if(errors.length > 0) {
			errors = "<ul>" + errors + "</ul>";
			ShowPopUp("Some data is missing ....", errors);
		} else {
			ChangeStep(nxtStep);
		}
	} catch(err) {
		window.alert("ValidateStep2: " + err + ' (line: ' + err.line + ')');
	}
}