// JavaScript Document

var URL = "";
$("#processURL").on("click", function(){
	URL = $(".step-1 #url").val();
	if(isUrlValid(URL)){
		doesURLExists();
	}else{
		$(".step-1 .form-group").addClass("has-danger");
		$(".step-1 .form-group .form-control-feedback").html("Plese enter a valid URL!");
	}
})

$("#url").on('keydown', function (e) {
    if (e.keyCode == 13) {
		$("#processURL").trigger("click");
    	e.preventDefault();
    	return false;
	}
});

function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}

function doesURLExists(){
	ajaxMessage('Checking if URL is valid/exists', 'processing');
	console.log(URL);
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/url-exists.php",
	  dataType: 'json',
	  data: { url: URL }
	}).done(function( response ) {
		if(response.success){
			ajaxMessage('URL exists, we are good to go!', 'processing');
			// Lets open up step 2 with the framed in website
			showStep2();
		}else{
			ajaxMessage('URL does not exist! We can not get a response from this URL.', 'error');
			$(".step-1 .form-group .form-control-feedback").html("The URL you entered is in the right format but does not seem to exists. We are unable to connect! Please double check the spelling to make sure you entered the right URL");
		}
	});
}

function showStep2(){
	$(".step-2 iframe").attr('src', '../../repository_queries/get-website-html.php?url='+encodeURIComponent(URL));
	$(".step-2 iframe").height(800);
	$(".step-3").slideUp('slow');
	ajaxMessage('Please select header and/or header background', 'success');
}

var headerParentTag = '';
var headerWrapperExist = false;
var backgroundImageURL = "";
var backgroundSize = "";
var headerHeight = 0;
var headerWrapperTag = "";
$("#webpage").on("load", function(){
	headerParentTag = '';
	headerWrapperExist = false;
	backgroundImageURL = "";
	backgroundSize = "";
	headerWrapperTag = "";
	$(".step-2").slideDown('slow');
	var iframe = $('#webpage').contents();
	iframe.find("*").unbind("click");
	iframe.find("*").on("click", function(e){
		// Let's look for a background image to save for this header...
		var savedHeaderBG = false;
		var imageURLRegex = "^(https?|ftp):\/\/.*(jpeg|png|gif|bmp|jpg)";
		$(e.target).find("div,table,tr,td").each(function () {
			var bgImageCSS = $(this).css('background-image');
			var bgImageURL = bgImageCSS.replace('url(','').replace(')','').replace(/\"/gi, "");
			if(bgImageURL.match(imageURLRegex)){
				if($(this).css('background-size')=="cover"||$(this).css('background-size')=="contain"){
					// Found a background image ask if they want to save this as part of the header
					var saveHeaderBackground = confirm("This element has a background image would you like to save this for the background of the header? If so click OK then select the header and click OK. You will be making 2 selections in this case.");
					if(saveHeaderBackground){
						backgroundImageURL = bgImageCSS;
						backgroundSize = $(this).css('background-size');
						savedHeaderBG = true;
						return false;
					}
				}
			}
		});
		if(savedHeaderBG){
			return false;
		}
		var importHeader = confirm("Are you sure your want to extract the highlighted section/header of this website? Note: it is designed to also select the elements above the highlighted section by default which works well for most if not all cases.");
		if(importHeader){
			iframe.find("*").off("click").off("mouseover").off("mouseout");
			if(backgroundColorSaver=="rgba(0, 255, 0, 0.3)"){
				backgroundColorSaver = " ";
			}
			if($(e.target).css("background-color")=="rgba(0, 255, 0, 0.3)"){
				$(e.target).css("background-color", "");
			}else{
				$(e.target).css("background-color", backgroundColorSaver);
			}
			$(e.target, e.target.outerHTML).css({'cursor' :""});
			if($(e.target).attr("style")==""){
				$(e.target).removeAttr("style");
			}
			if($(e.target.outerHTML).attr("style")==""){
				$(e.target.outerHTML).removeAttr("style");
			}
			if($(e.target).parent().is('body')){
			}else{
				headerWrapperExist = true;
			}
			$(e.target).nextAll().remove();
			if(backgroundImageURL){
				$('#webpage').contents().find('body').css("background-image", backgroundImageURL);
				$('#webpage').contents().find('body').css("background-size", backgroundSize);
			}
			if(headerWrapperExist){
				$(e.target).parent().nextAll().remove();
			}else{
				$(e.target).nextAll().remove();
			}
			headerHeight = 0;
			$('#webpage').height(headerHeight);
			$('#webpage').contents().find("*").each(function (){
				if($(this).height()>headerHeight){
					headerHeight = $(this).height();
				}
			});
			$('#webpage').height(headerHeight);
			$(".step-3").slideDown('slow');
			var increaseHeightInterval = "";
			$("#increaseFrameHeight").mousedown(function(){
				clearInterval(increaseHeightInterval);
				increaseHeightInterval = setInterval(function(){
					$('#webpage').height($('#webpage').height()+1);
					headerHeight = $('#webpage').height();
				},5);
			}).mouseup(function(){
				clearInterval(increaseHeightInterval);
			}).mouseleave(function(){
				clearInterval(increaseHeightInterval);
			});
			var decreaseHeightInterval = "";
			$("#decreaseFrameHeight").mousedown(function(){
				clearInterval(decreaseHeightInterval);
				decreaseHeightInterval = setInterval(function(){
					$('#webpage').height($('#webpage').height()-1);
					headerHeight = $('#webpage').height();
				},1);
			}).mouseup(function(){
				clearInterval(decreaseHeightInterval);
			}).mouseleave(function(){
				clearInterval(decreaseHeightInterval);
			});
			$("#decreaseFrameHeight").mousedown(function(){
				$('#webpage').height($('#webpage').height()-1);
				headerHeight = $('#webpage').height();
			});
			headerParentTag = e.target.outerHTML;
			headerParentTag = $(headerParentTag).first().html(" ")[0].outerHTML;
			if(headerWrapperExist){
				headerWrapperTag = e.target.parentElement.outerHTML;
				headerWrapperTag = $(headerWrapperTag).first().html(" ")[0].outerHTML;
			}
		}
		e.stopPropagation();
		return false;
	});
	iframe.find('*')[0].onclick = null;
	var backgroundColorSaver = "rgba(0, 0, 0, 0)";
	iframe.find("*").mouseover(function(e){
		e.stopPropagation();
		if($(e.target).css("background-color")){
			backgroundColorSaver = $(e.target).css("background-color");	
		}else{
			backgroundColorSaver = "rgba(0, 0, 0, 0)";
		}
		$(e.target).css("background-color", "rgba(0, 255, 0, 0.3)");
		$(e.target).css("cursor", "pointer");
	}).mouseout(function(e){
		e.stopPropagation();
		if(backgroundColorSaver=="rgba(0, 255, 0, 0.3)"){
			backgroundColorSaver = "rgba(0, 0, 0, 0)";
		}
		$(e.target).css("background-color", backgroundColorSaver);
	});
});

var userID = 0;
var userType = 'user';
function saveHeader(){
	ajaxMessage('Saving selected header for this user!', 'processing');
	var header = {
		backgroundImageURL: backgroundImageURL,
		backgroundSize: backgroundSize,
		height: headerHeight,
		URL: URL,
		parentTag: headerParentTag,
		wrapperTag: headerWrapperTag,
		userID: userID,
		userType: userType,
		action: 'save',
		tags: {parentTag: headerParentTag, wrapperTag: headerWrapperTag}
	};
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialcontentheader.php",
	  dataType: 'json',
	  data: header
	}).done(function( response ) {
		ajaxMessage('Selected header saved for this user!', 'success');
		alert("The selected header has been saved for this user! The header ID is: "+response.id);
		window.location = "http://www.spotlighthometours.com/microsites/content.php?contentID=76&userID="+userID;
	});
}

function ajaxMessage(message, type){
	$('#ajaxMessage').find('div').fadeOut('slow', function() {
		$(this).remove();
	 });
	$('#ajaxMessage').prepend('<div class="ajaxMessage '+type+'">'+message+'</div>');
	$('#ajaxMessage .ajaxMessage.'+type).fadeIn('slow');
	if(type=="success"){
		$('#ajaxMessage .ajaxMessage').delay(2000).fadeOut('slow', function() {
			$(this).remove();
		});
	}
}