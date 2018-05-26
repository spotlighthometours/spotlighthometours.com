// JavaScript Document

var productIndex;
var realtorDotComID = 30;
var realtorDotComMemberID = 71;
var virtualStagingID = 63;
var micrositeID = 137;
var propertyUrlID = 55;
var propertyUrl = false;
var Obj;

function PopulateProducts() {
	try {
		
		var tourtypeid = document.getElementById('tourtypeid').value;
		var url = "checkout_template_list_additional_products.php";
		var params  = "city=" + order.city + "&zip=" + order.zip + "&tourtype=";
		if(tourtypeid >0){
			params  += tourtypeid;
		}else{
			params  += order.tourtypeid;
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
					if(document.getElementById('additional_products')) {
						document.getElementById('additional_products').innerHTML = HTTP.responseText;
						SetProducts();
						if(typeof jump_to=="undefined"){
							
						}else{
							$('html,body').animate({scrollTop: $("#"+jump_to).offset().top-150},'fast');
						}
					}
				}
			}
			HTTP.send(params);
		}		
	} catch(err) {
		window.alert("PopulateProducts: " + err + ' (line: ' + err.line + ')');
	}
}

function eraseMicrositeSession(){
    $.ajax({
        url: '/repository_queries/user_checkdomain.php',
        data:{
            erase: 'microsite'
        }
    }).done(function(msg){
    });
}

function erasePropertyUrlSession(){
    $.ajax({
        url: '/repository_queries/user_checkdomain.php',
        data:{
            erase: 'propertyUrl'
        }
    }).done(function(msg){
    });
}
function dumpSession(){
	return;
}
function SelectProduct(e,skip) {
	try {
		var ap_id = parseInt(e.id.substring(e.id.indexOf("_") + 1));
		Obj = e;
		var found = false;
		for(var i = 0; i < order.prod.length; i++) {
			if(parseInt(order.prod[i].id) == ap_id) {
				found = true;
				console.log('called');
				if(order.prod[i].qty >= 1) {
					order.prod[i].qty = 0;
					e.className = e.className.replace('button_blue', 'button_tour');
					var caption = e.getElementsByTagName("span");
					for (var j = 0; j < caption.length; j ++) {
						caption[j].innerHTML = "Select";
					}
					e.parentNode.parentNode.setAttribute('style', "");
                    if( ap_id == micrositeID ){
                        eraseMicrositeSession();
                    }
                    if( ap_id == propertyUrlID){
                        erasePropertyUrlSession();
                    }
				} else {
					if($(e).data('required')){
						var requiredFields = $(e).data('required');
						requiredFields = requiredFields.split("::");
						for (key in requiredFields) {
							if(order[requiredFields[key]]>0){
								// proceed required is set
							}else{
								// HALT! Required is not set!
								collectRequired(requiredFields[key], e.id);
								return false;
							}
						}
					}
					if(ap_id==realtorDotComID){
						try {
							var url = "realtor_member_popup.php";
							var params  = '';
							responseTitle = 'Are you a showcase member?';
										
							ajaxQuery(url, params, 'ShowResponse');
										
						} catch(err) {
							alert("SelectProduct Realtor.com: " + err);
						}
					}else if(ap_id==virtualStagingID){
						try {
							var url = "virtual_staging_popup.php";
							var params  = '';
							responseTitle = 'Virtual Staging Enabled!';
							productIndex = i;
							Obj = e;
										
							ajaxQuery(url, params, 'ShowResponse');
							
							order.prod[i].qty = 1;
							e.className = e.className.replace('button_tour', 'button_blue');
							var caption = e.getElementsByTagName("span");
							for (var j = 0; j < caption.length; j ++) {
								caption[j].innerHTML = "Selected";
							}
							e.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
										
						} catch(err) {
							alert("SelectProduct Virtual Staging: " + err);
						}
					}else if(ap_id==micrositeID && skip != 'microsite' ){
						try {
							var url = "domain_selection_popup.php";
							var params  = '';
							responseTitle = 'Pick a Domain';
                            propertyUrl = false;
							ajaxQuery(url, params, 'ShowResponse');
						} catch(err) {
							alert("SelectProduct Microsite: " + err);
						}
					}else if(ap_id==propertyUrlID && skip != 'propertyUrl' ){
						try {
							var url = "domain_selection_popup-new.php";
							var params  = 'type=propertyUrl';
							responseTitle = 'Pick a Domain';
                            propertyUrl = true;
							ajaxQuery(url, params, 'ShowResponse');
						} catch(err) {
							alert("SelectProduct Microsite: " + err);
						}
					}else{
						var addMedia = $(Obj).parent().parent().data('addmedia');
						if(addMedia){
							var price = $(Obj).parent().parent().find(".tagline_frame").html();
							price = price.replace(/[A-Za-z$-]/g, "");
							getAddMediaPopUp(ap_id, false, price);
						}else{
							order.prod[i].qty = 1;
							e.className = e.className.replace('button_tour', 'button_blue');
							var caption = e.getElementsByTagName("span");
							for (var j = 0; j < caption.length; j ++) {
								caption[j].innerHTML = "Selected";
							}
							e.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
						}
					}
				}
			}
		}
		if(!found) {
			if(ap_id==realtorDotComID){
					try {
						var url = "realtor_member_popup.php";
						var params  = '';
						responseTitle = 'Are you a showcase member?';			
						ajaxQuery(url, params, 'ShowResponse');			
					} catch(err) {
						alert("SelectProduct Realtor.com: " + err);
					}
			}else if(ap_id==virtualStagingID){
					try {
						order.prod[order.prod.length] = {
							id:ap_id,
							qty:1
						}
						var url = "virtual_staging_popup.php";
						var params  = '';
						responseTitle = 'Virtual Staging Enabled!';
						productIndex = i;
						Obj = e;
									
						ajaxQuery(url, params, 'ShowResponse');
						
						e.className = e.className.replace('button_tour', 'button_blue');
						var caption = e.getElementsByTagName("span");
						for (var j = 0; j < caption.length; j ++) {
							caption[j].innerHTML = "Selected";
						}
						e.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
									
					} catch(err) {
						alert("SelectProduct Virtual Staging: " + err);
					}
			}else if(ap_id==micrositeID && skip != 'microsite'){
						try {
							var url = "domain_selection_popup.php";
							var params  = '';
							responseTitle = 'Pick a Domain';
                            propertyUrl = false;
							ajaxQuery(url, params, 'ShowResponse');
						} catch(err) {
							alert("SelectProduct Microsite: " + err);
						}
			}else if(ap_id==propertyUrlID && skip != 'propertyUrl'){
						try {
							var url = "domain_selection_popup-new.php";
							var params  = 'type=propertyUrl';
                            propertyUrl = true;
							responseTitle = 'Pick a Domain';
							ajaxQuery(url, params, 'ShowResponse');
						} catch(err) {
							alert("SelectProduct Microsite: " + err);
						}
			}else{
				var addMedia = $(Obj).parent().parent().data('addmedia');
				if(addMedia){
					var price = $(Obj).parent().parent().find(".tagline_frame").html();
					price = price.replace(/[A-Za-z$-]/g, "");
					getAddMediaPopUp(ap_id, false, price);
				}else{
					if($(e).data('required')){
						var requiredFields = $(e).data('required');
						requiredFields = requiredFields.split("::");
						for (key in requiredFields) {
							if(order[requiredFields[key]]>0){
								// proceed required is set
							}else{
								// HALT! Required is not set!
								collectRequired(requiredFields[key], e.id);
								return false;
							}
						}
					}
					order.prod[order.prod.length] = {
						id:ap_id,
						qty:1
					}
					e.className = e.className.replace('button_tour', 'button_blue');
					var caption = e.getElementsByTagName("span");
					for (var j = 0; j < caption.length; j ++) {
						caption[j].innerHTML = "Selected";
					}
					e.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
				}
			}
		}
		GetOrderTotal();
	} catch(err) {
		window.alert("SelectProduct: " + err + ' (line: ' + err.line + ')');
	}
}

function addPrice(addPrice){
	if(addPrice=="1"){
		order.prod[productIndex].qty = 1;
		Obj.className = Obj.className.replace('button_tour', 'button_blue');
		var caption = Obj.getElementsByTagName("span");
		for (var j = 0; j < caption.length; j ++) {
			caption[j].innerHTML = "Selected";
		}
		Obj.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
		HidePopUp();
	}else{
		order.prod[productIndex].qty = 0;
		Obj.className = Obj.className.replace('button_blue', 'button_tour');
		var caption = Obj.getElementsByTagName("span");
		for (var j = 0; j < caption.length; j ++) {
			caption[j].innerHTML = "Select";
		}
		Obj.parentNode.parentNode.setAttribute('style', "");
		HidePopUp();
	}
	GetOrderTotal();
}

function addRealtorDotCom(isMember){
	var e = document.getElementById('aps_'+realtorDotComID);
	if(isMember){
		id = realtorDotComMemberID;
	}else{
		id = realtorDotComID;
	}

	// Remove all realtor products before adding again.
	for(var i = 0; i < order.prod.length; i++) {
		if(parseInt(order.prod[i].id) == realtorDotComMemberID || parseInt(order.prod[i].id) == realtorDotComID) {
			order.prod.splice(i,1);
		}
	}
	
	// Set realtor product
	order.prod[order.prod.length] = {
		id:id,
		qty:1
	}
	e.className = e.className.replace('button_tour', 'button_blue');
	var caption = e.getElementsByTagName("span");
	for (var j = 0; j < caption.length; j ++) {
		caption[j].innerHTML = "Selected";
	}
	e.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
	HidePopUp();
	GetOrderTotal();
	console.log(order);
}

function IncrementProduct(e) {
	try {
		var ap_id = parseInt(e.id.substring(e.id.indexOf("_") + 1));
		var found = false;
		var addMedia = $("#ap_"+ap_id).data('addmedia');
		if(addMedia){
			var price = $("#ap_"+ap_id).find(".tagline_frame").html();
			price = price.replace(/[A-Za-z$-]/g, "");
			getAddMediaPopUp(ap_id, true, price);
		}else{
			for(var i = 0; i < order.prod.length; i++) {
				if(parseInt(order.prod[i].id) == ap_id) {
					found = true;
					order.prod[i].qty += 1;
					var caption = e.parentNode.getElementsByTagName("span");
					for (var j = 0; j < caption.length; j ++) {
						caption[j].innerHTML = order.prod[i].qty;
					}
				}
			}
			if(!found) {
				order.prod[order.prod.length] = {
					id:ap_id,
					qty:1
				}
				e.className = e.className.replace('button_tour', 'button_blue');
				var caption = e.parentNode.getElementsByTagName("span");
				for (var j = 0; j < caption.length; j ++) {
					caption[j].innerHTML = 1;
				}
				e.parentNode.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
			}
			GetOrderTotal();
		}
	} catch(err) {
		window.alert("IncrementProduct: " + err + ' (line: ' + err.line + ')');
	}
}

function DecrementProduct(e) {
	try {
		var ap_id = parseInt(e.id.substring(e.id.indexOf("_") + 1));
		var addMedia = $("#ap_"+ap_id).data('addmedia');
		if(addMedia){
			var price = $("#ap_"+ap_id).find(".tagline_frame").html();
			price = price.replace(/[A-Za-z$-]/g, "");
			getAddMediaPopUp(ap_id, true, price);
		}else{
			for(var i = 0; i < order.prod.length; i++) {
				if(parseInt(order.prod[i].id) == ap_id) {
					order.prod[i].qty -= 1;
					if(order.prod[i].qty <= 0) {
						order.prod[i].qty = 0;
						e.parentNode.parentNode.parentNode.setAttribute('style', "");
					}
					var caption = e.parentNode.getElementsByTagName("span");
					for (var j = 0; j < caption.length; j ++) {
						caption[j].innerHTML = order.prod[i].qty;
					}
				}
			}
			GetOrderTotal();
		}
	} catch(err) {
		window.alert("DecrementProduct: " + err + ' (line: ' + err.line + ')');
	}
}

function SetProducts() {
	try {
		if(order.prod.length > 0) {
			for(var i = 0; i < order.prod.length; i++) {
				if(order.prod[i].qty > 0) {
					id = order.prod[i].id;
					if(id==realtorDotComMemberID){
						id = realtorDotComID;
					}
					if(document.getElementById('app_' + id)) {
						var plus = document.getElementById('app_' + id);
						var caption = plus.parentNode.getElementsByTagName("span");
						for (var j = 0; j < caption.length; j ++) {
							caption[j].innerHTML = order.prod[i].qty;
						}
						plus.parentNode.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
					} else if (document.getElementById('aps_' + id)) {
						var single = document.getElementById('aps_' + id);
						single.className = single.className.replace('button_tour', 'button_blue');
						var caption = single.getElementsByTagName("span");
						for (var j = 0; j < caption.length; j ++) {
							caption[j].innerHTML = "Selected";
						}
						single.parentNode.parentNode.setAttribute('style', "border: 2px solid #0087CC;");
					}
				}
			}
		}
	} catch(err) {
		window.alert("SetProducts: " + err + ' (line: ' + err.line + ')');
	}		
}

function LearnMore(e, title) {
	try {
		var ap_id = parseInt(e.id.substring(e.id.indexOf("_") + 1));
		
		var url = "checkout_xml_product_description.php";
		var params  = "id=" + ap_id;
		
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
					ShowPopUp(title, HTTP.responseText);
				}
			}
			HTTP.send(params);
		}		
	} catch(err) {
		window.alert("GetOrderTotal: " + err + ' (line: ' + err.line + ')');
	}
}

/* DOMAIN NAME SELECTION */

function validateDomain(){
	var domain = $('#domainName').val();
	if(domain==''){
		outputError('domainMsg', "Please enter a domain name!");
		return false;
	}
	if(!validate('domain', domain)){
		outputError('domainMsg', "Invalid domain name! The domain name can not contain any special characters.");
		return false;
	}
	return true;
}

function validateSpecificDomain(domain){
	if(domain.length == 0){
		outputError('domainMsg', "Please enter a domain name!");
		return false;
	}
	if(!validate('domain', domain)){
		outputError('domainMsg', "Invalid domain name! The domain name can not contain any special characters.");
		return false;
	}
	return true;
}

function removeUrl(index){
	$("#domainUrl_" + index).remove();
}

var urlCounter = 1;
function addDomainUrl(){
	$("#additionalUrls").append(
		'<div id="domainUrl_' + urlCounter + '" >' +
			'<div class="clear"></div>' + 
			'<div style="position:relative;left:-54px;top:30px;">' + 
				'<a href="javascript:void(0);" onClick="removeUrl(' + urlCounter + ');">Remove</a>'+
			'</div>' +
			'<div class="form_line widthAuto left">' + 
				'<div class="input_line w_md">'+
					'<div class="input_title widthAuto">www.</div>'+
					'<input id="domainName_' + urlCounter + '" name="domainName" />'+
				'</div>'+
			'</div>' +
			'<div class="left">&nbsp;</div>'+
			'<div class="form_line widthAuto left" >'+
				'<div class="input_line widthAuto" >'+
					'<select name="domainExt" id="domainExt_' + urlCounter + '">'+
						'<option value=".com">.com</option>' +
						'<option value=".net">.net</option>' +
						'<option value=".org">.org</option>' +
						'<option value=".biz">.biz</option>' +
						'<option value=".name">.name</option>' +
						'<option value=".info">.info</option>' +
					'</select>' +
				'</div>' +
			'</div>' + 
			"<div class='left' style='width:80px;'>" +
				'<select id="domainBranding_' + urlCounter + '">' + 
					'<option value="branded">Branded</option>' + 
					'<option value="nonbranded">Non-Branded</option>' + 
				'</select>' + 
			'</div>' +
		'</div>'
	);
	urlCounter++;
}

var allDomainsAvailable = false;
var domainChecklist = [];
function checkDomainList(doneCallback){
	domainChecklist = [];
	spinnerAlert('domainMsg', 'Checking domain availability...');
	$("div[id^='domainUrl_']").each(function(index,element){
		id = $(this).attr('id').split('_')[1];
		console.log(id);
		domain = $("#domainName_" + id).val();
		$(this).css("border","");
		if(validateSpecificDomain(domain)){
			domainChecklist.push(domain + $("#domainExt_" + id).val() + ":" + id + ":" + $("#domainBranding_" + id).val());
		}else{
			$("#domainName_" + id).css("border","1px solid red");
			$("#domainSaveButton").fadeOut();
		}
	});
	
	$.ajax({
		url: '/repository_queries/user_checkdomain.php',
		type: 'POST',
		data: {
			domainList: JSON.stringify(domainChecklist),
		   'check': 1
		}
	}).done(function(msg){
		json = $.parseJSON(msg);
		console.log(json);
		if( json.status == 'okay' ){
			for(i in json.available){
				$("#domainName_" + json.available[i].id).css("border","1px solid #BEBEBE");
				outputAlert('domainMsg',json.available[i].domain + " is available");
			}

			for(i in json.unavailable){
				$("#domainName_" + json.unavailable[i].id).css("border","1px solid red");
				outputError('domainMsg',json.unavailable[i].domain + " isn't available");
				$("#domainSaveButton").fadeOut();
				allDomainsAvailable = false;
			}
			
			if( json.allAvailable ){
				allDomainsAvailable = true;
				$("#domainSaveButton").fadeIn();
			}else{
				$("#domainSaveButton").fadeOut();
			}
			if( doneCallback ){
				doneCallback();
			}
		}
	});
}

function checkDomain(){
	if(validateDomain()){
		var url = "../repository_queries/domain-name-availability.php";
		var domainName = $('#domainName').val();
		var domainExtension = $('#domainExt').val();
		var params = "domainName="+domainName+"&domainExt="+domainExtension;	
		spinnerAlert('domainMsg', 'Checking domain availability...')
        $.ajax({
            url: '/repository_queries/user_checkdomain.php',
            type: 'POST',
            data: {
                domain: domainName + domainExtension
            }
        }).done(function(msg){
            json = $.parseJSON(msg);
            console.log(json);
            if( json.status == 'okay' ){
                if( json.exists == 0 ){
                    response = 1;
                }else{
                    response = 0;
                }
            }
            domainResponse();
        });
	}
}

function domainSave(){
	checkDomainList(function(){
		if( allDomainsAvailable ){
			$("#domainSaveButton").fadeOut();
			spinnerAlert('domainMsg', 'Saving...');

			$.ajax({
				url: '/repository_queries/user_checkdomain.php',
				type: 'POST',
				data:{
					'domainList': JSON.stringify(domainChecklist),
					'save': 1
				}
			}).done(function(msg){
				outputAlert('domainMsg','Saved');
				SelectProduct(document.getElementById("aps_55"),'propertyUrl');
				ap_id = 55;
				found = false;
				for(var i = 0; i < order.prod.length; i++) {
					if(parseInt(order.prod[i].id) == ap_id) {
						found = true;
						order.prod[i].qty = domainChecklist.length;
					}
				}
				if(!found){
					order.prod.push({
						id: ap_id,
						qty: domainChecklist.length
					});
				}
				GetOrderTotal();
				window.setTimeout(function(){
					HidePopUp();
				},3000);
			});
		}else{
			outputError('domainMsg',"One or more domains aren't available");
		}
	});
}


function domainResponse(){
	if(response==1){
		outputAlert('domainMsg', 'This domain is availble!');
		$('#domainButton .button_caption').html('Select');
		$('#domainName').change(function() {
			$('#domainButton .button_caption').html('Check Availability');
			$('#domainButton').removeAttr("onclick");
			$('#domainButton').unbind('click');
			$('#domainButton').click(function() {
			  checkDomain();
			});
			$('#domainName').unbind('change');
			$('#domainMsg').hide();
		});
		$('#domainExt').change(function() {
			$('#domainButton').removeAttr("onclick");
			$('#domainButton').unbind('click');
			$('#domainButton').click(function() {
			  checkDomain();
			});
			$('#domainButton .button_caption').html('Check Availability');
			$('#domainExt').unbind('change');
			$('#domainMsg').hide();
		});
		$('#domainButton').removeAttr("onclick");
		$('#domainButton').unbind('click');
		$('#domainButton').click(function() {
			// SAVE DOMAIN NAME AND EXT, SELECT PRODUCT AND CLOSE POPUP
            domainName = $('#domainName').val(); 
            $.ajax({
                url: '/repository_queries/user_checkdomain.php',
                type: 'POST',
                data: {
                    saveSession: domainName + $("#domainExt").val(),
                    "propertyUrl": (propertyUrl ? "true" : "false" )
                }
            }).done(function(msg){
                if( propertyUrl ){
                    SelectProduct(document.getElementById("aps_55"),'propertyUrl');
                }else{
                    SelectProduct(document.getElementById("aps_137"),'microsite');
                }
				dumpSession();
                HidePopUp();
            });
        });
	}else{
		outputError('domainMsg', "I'm sorry this domain is not available!");
	}
}

function getDemo(URL){
	previewWindow = window.open(URL,'','fullscreen=yes, scrollbars=yes');
	previewWindow.moveTo((screen.width/2)-500,0);
	previewWindow.resizeTo(1000,800);
}

/* ADD MEDIA */
var selectedMedia = new Array();
var mediaShowingID = 0;
var mediaPrice = 0;
var total = 0;
var addMediaProdID = 0;
var addMediaMultiSelect = true;

function getAddMediaPopUp(productID, multiselect, price){
	addMediaProdID = productID;
	addMediaMultiSelect = multiselect;
	mediaPrice = price;
	total = 0;
	mediaShowingID = 0;
	var url = "add-media-popup.php";
	var params  = "type=product&typeID="+productID;
	ajaxQuery(url, params, 'showAddMediaPopUp');	
}
function showAddMediaPopUp(){
	selectedMedia = new Array();
	ShowPopUp("", response);
	$(".add-media .media-details .price span").html(mediaPrice);
	loadAddMedia(addMediaProdID);
	$(".thmb-list .thmb-add, .thmb-list .thmb-remove").die("click");
	$(".thmb-list .check").die("click");
	$(".media-desc .check").die("click");
	$(".media-desc .add-btn, .media-desc .minus-btn").die("click");
	$('.add-media .thmb-list .thmb').click(function(){
		var mediaID = $(this).attr('id').replace(/\D/g,'');
		showMediaDetails(mediaID);
	});
	$(".thmb-list .thmb-add, .thmb-list .thmb-remove").live("click", function(){
		var mediaID = $(this).parent().attr('id').replace(/\D/g,'');
		if($(this).attr("class")=="thmb-add"){
			addMedia(mediaID);
		}else{
			removeMedia(mediaID);
		}
	});
	$(".thmb-list .check").live("click", function(){
		var mediaID = $(this).parent().attr('id').replace(/\D/g,'');
		removeMedia(mediaID);
	});
	$(".media-desc .check").live("click", function(){
		var mediaID = $(this).parent().parent().data('mediaid');
		removeMedia(mediaID);
	});
	$(".media-desc .add-btn, .media-desc .minus-btn").live("click", function(){
		var mediaID = $(this).parent().parent().data('mediaid');
		if($(this).attr("class")=="add-btn"){
			addMedia(mediaID);
		}else{
			removeMedia(mediaID);
		}
	});
}
function loadAddMedia(productID){
	if(order.addMedia[productID]){
		$.each(order.addMedia[productID], function(index,mediaID){
			addMedia(mediaID);
		});
	}
}
function addMedia(mediaID){
	if(jQuery.inArray(parseInt(mediaID), selectedMedia)<0){
		if(!addMediaMultiSelect){
			$.each(selectedMedia, function(index,mediaID){
				removeMedia(mediaID);
			});
		}
		selectedMedia.push(parseInt(mediaID));
		total = parseInt(selectedMedia.length)*mediaPrice;
		var mediaDetailsDiv = $('.add-media .media-desc');
		mediaDetailsDiv.find('.media-details .total span').html(total.toFixed(2));
		showMediaDetails(mediaID);
		selectMedia(mediaID);
	}
}
function removeMedia(mediaID){
	var mediaIDPos = jQuery.inArray(parseInt(mediaID), selectedMedia);
	if(mediaIDPos>=0){
		selectedMedia.splice(mediaIDPos, 1);
		total = parseInt(selectedMedia.length)*mediaPrice;
		var mediaDetailsDiv = $('.add-media .media-desc');
		mediaDetailsDiv.find('.media-details .total span').html(total.toFixed(2));
		deselectMedia(mediaID);
	}
}
function selectMedia(mediaID){
	$(".thmb-list").find('#thmb_'+mediaID).prepend('<div class="check"></div>');
	$(".thmb-list").find('#thmb_'+mediaID+ ' .thmb-add').removeClass('thmb-add').addClass('thmb-remove').html('Remove');
}
function deselectMedia(mediaID){
	$(".thmb-list").find('#thmb_'+mediaID).find('.check').remove();
	$(".thmb-list").find('#thmb_'+mediaID+ ' .thmb-remove').removeClass('thmb-remove').addClass('thmb-add').html('Add');
	if(parseInt(mediaID)==parseInt(mediaShowingID)){
		var mediaDetailsDiv = $('.add-media .media-desc');
		mediaDetailsDiv.find('.media-preview .check').remove();
		mediaDetailsDiv.find('.media-details .minus-btn').removeClass('minus-btn').addClass('add-btn');
	}
}
function showMediaDetails(mediaID){
	if(parseInt(mediaID)!==parseInt(mediaShowingID)){
		mediaShowingID = mediaID;
		var thmbDiv = $('#thmb_'+mediaID);
		var mediaName = thmbDiv.find('.thmb-lbl').html();
		var mediaDesc = thmbDiv.find('.thmb-desc').html();
		var mediaType = thmbDiv.data('mediatype');
		var tourID = thmbDiv.data('tourid');
		var mediaDetailsDiv = $('.add-media .media-desc');
		mediaDetailsDiv.data('mediaid',mediaID);
		mediaDetailsDiv.find('.media-details h2').html(mediaName);
		mediaDetailsDiv.find('.media-details p').html(mediaDesc);
		if(mediaType=="photo"){
			mediaDetailsDiv.find('.media-preview').html('<img src="../../images/tours/'+tourID+'/photo_400_'+mediaID+'.jpg" width="413">');
		}else{
			mediaDetailsDiv.find('.media-preview').html('<iframe src="https://www.spotlighthometours.com/tours/video-player-new.php?type=video&id='+mediaID+'" width="413" height="275" frameborder="0"></iframe>');
		}
		if(jQuery.inArray(parseInt(mediaID), selectedMedia)>=0){
			mediaDetailsDiv.find('.media-preview').prepend('<div class="check"></div>');
			mediaDetailsDiv.find('.media-details .add-btn').removeClass('add-btn').addClass('minus-btn');
		}else{
			mediaDetailsDiv.find('.media-details .minus-btn').removeClass('minus-btn').addClass('add-btn');
		}
	}else{
		var mediaDetailsDiv = $('.add-media .media-desc');
		if(jQuery.inArray(parseInt(mediaID), selectedMedia)>=0){
			mediaDetailsDiv.find('.media-preview').prepend('<div class="check"></div>');
			mediaDetailsDiv.find('.media-details .add-btn').removeClass('add-btn').addClass('minus-btn');
		}else{
			mediaDetailsDiv.find('.media-preview .check').remove();
			mediaDetailsDiv.find('.media-details .minus-btn').removeClass('minus-btn').addClass('add-btn');
		}
	}
}
function addToOrder(){
	order.addMedia[addMediaProdID] = selectedMedia;
	var numberSelected = selectedMedia.length;
	if(addMediaMultiSelect){
		var found = false;
		for(var i = 0; i < order.prod.length; i++) {
			if(parseInt(order.prod[i].id) == addMediaProdID) {
				found = true;
				order.prod[i].qty = numberSelected;
				var caption = $("#ap_"+addMediaProdID).find('.button_frame .col_right .button_caption');
				for (var j = 0; j < caption.length; j ++) {
					$("#aps_"+addMediaProdID).removeClass('button_tour').addClass('button_blue');
				}
				$('#ap_'+addMediaProdID).attr("style", "border: 2px solid #0087CC");
			}
		}
		if(!found) {
			order.prod[order.prod.length] = {
				id:addMediaProdID,
				qty:numberSelected
			}
			var caption = $("#ap_"+addMediaProdID).find('.button_frame .col_right .button_caption');
			for (var j = 0; j < caption.length; j ++) {
				$("#aps_"+addMediaProdID).removeClass('button_tour').addClass('button_blue');
			}
			$('#ap_'+addMediaProdID).attr("style", "border: 2px solid #0087CC");
		}
		if(numberSelected<1){
			$('#ap_'+addMediaProdID).attr("style", "");
			$("#aps_"+addMediaProdID).removeClass('button_blue').addClass('button_tour');
		}
		GetOrderTotal();
	}else{
		if(numberSelected>0){
			var found = false;
			for(var i = 0; i < order.prod.length; i++) {
				if(parseInt(order.prod[i].id) == addMediaProdID) {
					found = true;
					order.prod[i].qty = 1;
					$("#aps_"+addMediaProdID).removeClass('button_tour').addClass('button_blue');
				}
			}
			if(!found){
				order.prod[order.prod.length] = {
					id:addMediaProdID,
					qty:1
				}
				$("#aps_"+addMediaProdID).removeClass('button_tour').addClass('button_blue');
			}
			$('#ap_'+addMediaProdID).attr("style", "border: 2px solid #0087CC");
			GetOrderTotal();
		}else{
			$('#ap_'+addMediaProdID).attr("style", "");
		}
	}
	HidePopUp();
}
function cancelOrder(){
	selectedMedia = new Array();
	HidePopUp();
}
/* END ADD MEDIA */
function collectRequired(requiredField, selectedID){
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
	html+='    <td><div class="button_new button_blue button_mid" onclick="saveRequiredAndSelect(\''+requiredField+'\', \''+selectedID+'\')">';
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

function saveRequiredAndSelect(requiredField, selectedID){
	var enteredVal = $("input[name='required_"+requiredField+"']").val();
	if(isNaN(enteredVal)||enteredVal.length==0){
		alert("Please enter a valid value for the required field. Numbers only!");
	}else{
		$("input[name='tour_"+requiredField+"']").val($("input[name='required_"+requiredField+"']").val());
		order[requiredField] = enteredVal;
		console.log(selectedID);
		HidePopUp();
		SelectProduct(document.getElementById(selectedID));		
	}
}
