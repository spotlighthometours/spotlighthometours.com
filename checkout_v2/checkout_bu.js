// JavaScript Document
var order = {
	title:"",
	address:"",
	hide_address:"0",
	city:"",
	state:"",
	zip:"",
	beds:"",
	hide_beds:"0",
	baths:"",
	hide_baths:"0",
	sqft:"",
	hide_sqft:"0",
	price:"",
	hide_price:"0",
	mls:Array(),
	desc:"",
	add:"",
	coagent:"",
	tourtypeid:"",
	prod:Array(),
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

var wait = "";
var complete = "";

var checkUnload = true;

window.onload = function initialLoad(){  
    try {
		GetStates();
		GetAgents();
		GetWaitScreen();
		GetCompleteScreen();
	} catch(err) {
		window.alert("onload: " + err + ' (line: ' + err.line + ')');
	}
}

window.onbeforeunload = function() {
    if (checkUnload) {
		return "Are you sure that you want to leave?  This tour will be lost. \n If you are looking to change information, please \ncancel and click on the step you would like to change.";
	}
}

function openPopup(url, x, y) {
	try {
		window.open(url,'Preview',"location=0,status=0,scrollbars=0, width=" + x + ",height=" + y);
	} catch(err) {
		RecordError("openPopup: " + err);
	}
}

function GetWaitScreen() {
	try {
		var url = "checkout_wait.php";
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
					
					wait = HTTP.responseText;
					
				}
			}
			HTTP.send(params);
		}			
					
	} catch(err) {
		alert("GetWaitScreen: " + err);
	}
}

function GetCompleteScreen() {
	try {
		var url = "checkout_complete.php";
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
					
					complete = HTTP.responseText;
					
				}
			}
			HTTP.send(params);
		}			
					
	} catch(err) {
		alert("GetCompleteScreen: " + err);
	}
}


function ChangeStep(step) {
	try {
		var pricing = document.getElementById('total_frame');
		//Todos
		if (step == 1) {
			pricing.style.display = "none";
		} else if(step == 2) {
			PopulatePackages();
			pricing.style.display = "block";
		} else if(step == 3) {
			pricing.style.display = "block";
			PopulateProducts();
		} else if(step == 4) {
			pricing.style.display = "none";
			PopulateCheckout();
		}
		
		var steps = Array();
		if(document.getElementById('step_1')) {
			steps[1] = document.getElementById('step_1');
		}
		if(document.getElementById('step_2')) {
			steps[2] = document.getElementById('step_2');
		}
		if(document.getElementById('step_3')) {
			steps[3] = document.getElementById('step_3');
		}
		if(document.getElementById('step_4')) {
			steps[4] = document.getElementById('step_4');
		}
		
		var progress = Array();
		if(document.getElementById('pf_1')) {
			progress[1] = document.getElementById('pf_1');
		}
		if(document.getElementById('pf_2')) {
			progress[2] = document.getElementById('pf_2');
		}
		if(document.getElementById('pf_3')) {
			progress[3] = document.getElementById('pf_3');
		}
		if(document.getElementById('pf_4')) {
			progress[4] = document.getElementById('pf_4');
		}
		
		for(var i = 1; i < steps.length; i++) {
			steps[i].style.display = "none";
		}
		for(var i = 1; i < progress.length; i++) {
			progress[i].style.display = "none";
		}
		
		steps[step].style.display = "block";
		progress[step].style.display = "block";
		
		
		
	} catch(err) {
		alert("ChangeStep: " + err);
	}		
}

function DumpOrder() {
	try {
		var dump = "";
		
		dump += "title: " + order.title + "\n";
		dump += "address: " + order.address + "\n";
		dump += "hide_address: " + order.hide_address + "\n";
		dump += "city: " + order.city + "\n";
		dump += "state: " + order.state + "\n";
		dump += "zip: " + order.zip + "\n";
		dump += "beds: " + order.beds + "\n";
		dump += "hide_beds: " + order.hide_beds + "\n";
		dump += "baths: " + order.baths + "\n";
		dump += "hide_baths: " + order.hide_baths + "\n";
		dump += "sqft: " + order.sqft + "\n";
		dump += "hide_sqft: " + order.hide_sqft + "\n";
		dump += "price: " + order.price + "\n";
		dump += "hide_price: " + order.hide_price + "\n";
		
		for(var i = 0; i < order.mls.length; i++) {
			dump += "mls: " + order.mls[i] + "\n";
		}
		dump += "desc: " + order.desc + "\n";
		dump += "add: " + order.add + "\n";
		dump += "coagent: " + order.coagent + "\n";
		dump += "tourtypeid: " + order.tourtypeid + "\n";
		
		for(var i = 0; i < order.prod.length; i++) {
			dump += "product " + order.prod[i].id + ": " + order.prod[i].qty + "\n";
		}
		
		alert(dump);
	} catch(err) {
		alert("DumpOrder: " + err);
	}
}

function ShowPopUp(title, content) {
	try {
		var backdrop = false;
		if(document.getElementById("backdrop")) {
			backdrop = document.getElementById("backdrop");
		}
		
		var frame = false;
		if(document.getElementById("pop_up_frame")) {
			frame = document.getElementById("pop_up_frame");
		}
		
		var f_title = false;
		if(document.getElementById("pop_up_title")) {
			f_title = document.getElementById("pop_up_title");
		}
		
		var f_content = false;
		if(document.getElementById("pop_up_content")) {
			f_content = document.getElementById("pop_up_content");
		}
		
		backdrop.style.display = "block";
		f_title.innerHTML = title;
		f_content.innerHTML = content;
		frame.style.display = "block";
		
		if(document.getElementById("pop_up_window")) {
			var win = document.getElementById("pop_up_window");
			win.style.marginTop = (-1 * parseInt(win.clientHeight/2)) + 'px';
		}
	} catch(err) {
		alert("ShowPopUp: " + err);
	}
}

function HidePopUp() {
	try {
		var backdrop = false;
		if(document.getElementById("backdrop")) {
			backdrop = document.getElementById("backdrop");
		}
		
		var frame = false;
		if(document.getElementById("pop_up_frame")) {
			frame = document.getElementById("pop_up_frame");
		}
		
		backdrop.style.display = "none";
		frame.style.display = "none";
	} catch(err) {
		alert("HidePopUp: " + err);
	}
}

function ShowWait() {
	try {
		if(document.getElementById("pop_up_title_frame")) {
			document.getElementById("pop_up_title_frame").style.display = "none";
		}
		ShowPopUp("", wait);
	} catch(err) {
		alert("ShowWait: " + err);
	}
}

function HideWait() {
	try {
		if(document.getElementById("pop_up_title_frame")) {
			document.getElementById("pop_up_title_frame").style.display = "block";
		}
		HidePopUp();
	} catch(err) {
		alert("HideWait: " + err);
	}
		
}

function GetOrderTotal() {
	try {
		var url = "checkout_xml_order_total.php";
		var params  = "city=" + order.city + "&zip=" + order.zip + "&tourtype=" + order.tourtypeid;
		
		if(document.getElementById('checkout_coupon')) {
			order.coupon = document.getElementById('checkout_coupon').value;
		}
		params += "&coupon=" + order.coupon;
		
		if($("#usePaySold").is(':checked')){
			params  += "&usePaySold=1";
		}else{
			params  += "&usePaySold=0";
		}
				
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
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && HTTP.status == 200) {
					var total = HTTP.responseXML.getElementsByTagName("total");
					if(total.length > 0) {
						
						for(var i = 0; i < total.length; i++) {
							if(total[i].hasChildNodes()) {
								order.total = parseFloat(total[i].childNodes[0].nodeValue).toFixed(2);
								if(document.getElementById('order_total')) {
									document.getElementById('order_total').innerHTML = '$' + parseFloat(total[i].childNodes[0].nodeValue).toFixed(2);
								}
							}
						}
					}
				}
			}
			HTTP.send(params);
		}		
	} catch(err) {
		window.alert("GetOrderTotal: " + err + ' (line: ' + err.line + ')');
	}
}
