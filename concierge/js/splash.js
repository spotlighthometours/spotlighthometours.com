// JavaScript Document
var userID = 0;
$('.tooltip').tooltipster({
	maxWidth: 200
});
function signupPopup(){	
	var signupForm = '<div class="signup-form login-panel"><p><i><strong>Already have a spotlight account? <a href="javascript:loginSignup()" style="color:#4378ff;text-decoration:none;">sign in</a></strong></i></p>If you are not already a Spotlight member then all we need is the brief information below and you\'ll be customizing your Concierge Membership in no time!<br/><br/><br/>';
    signupForm += '<div id="signupMsg"></div>';
    signupForm += '<div class="form_line">';
    signupForm += '  <div class="input_line w_lg">';
    signupForm += '    <div class="input_title">First name</div>';
    signupForm += '    <input id="firstName" name="firstName" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" onkeyup="setFocusOnEnter(event, \'lastName\')" />';
    signupForm += '    <div class="input_info" style="display: none;" >';
    signupForm += '      <div class="info_text" >Your First Name</div>';
    signupForm += '    </div>';
    signupForm += '  </div>';
    signupForm += '</div>';
    signupForm += '<div class="form_line">';
    signupForm += '  <div class="input_line w_lg">';
    signupForm += '    <div class="input_title">Last name</div>';
    signupForm += '    <input id="lastName" name="lastName" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" onkeyup="setFocusOnEnter(event, \'phone\')" />';
    signupForm += '    <div class="input_info" style="display: none;" >';
    signupForm += '      <div class="info_text" >Your Last Name</div>';
    signupForm += '    </div>';
    signupForm += '  </div>';
    signupForm += '</div>';
    signupForm += '<div class="form_line">';
    signupForm += '  <div class="input_line w_lg">';
    signupForm += '    <div class="input_title">Cell Phone</div>';
    signupForm += '    <input id="phone" name="phone" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" onkeyup="setFocusOnEnter(event, \'username\')" />';
    signupForm += '    <div class="input_info" style="display: none;" >';
    signupForm += '      <div class="info_text">10 digit # required.</div>';
    signupForm += '    </div>';
    signupForm += '  </div>';
    signupForm += '</div>';
    signupForm += '<div class="form_line">';
    signupForm += '  <div class="input_line w_lg">';
    signupForm += '    <div class="input_title">Username email</div>';
    signupForm += '    <input id="username" name="username" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" onkeyup="setFocusOnEnter(event, \'password\')" />';
    signupForm += '    <div class="input_info" style="display: none;" >';
    signupForm += '      <div class="info_text" >Your email address</div>';
    signupForm += '    </div>';
    signupForm += '  </div>';
    signupForm += '</div>';
    signupForm += '<div class="form_line">';
    signupForm += '  <div class="input_line w_lg">';
    signupForm += '    <div class="input_title">Password</div>';
    signupForm += '    <input id="password" name="password" onfocus="ToggleInputInfo(this, 1);" type="password" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" onkeyup="sumbitLoginFormEnter(event)"/>';
    signupForm += '    <div class="input_info" style="display: none;" >';
    signupForm += '      <div class="info_text" >Your password</div>';
    signupForm += '    </div>';
    signupForm += '  </div>';
    signupForm += '</div>';
  	signupForm += '<div class="loginButtons" style="width:550px;">';
	signupForm += '<div align="left"><div class="button_new button_blue button_mid" onClick="signUp()"><div class="curve curve_left"></div><span class="button_caption">Signup</span><div class="curve curve_right"></div></div>';
  	signupForm += '</div>';
  	signupForm += '<div class="clear"></div>';
	signupForm += '</div>';
	ShowPopUp('Concierge Sign Up!', signupForm);
}
function agentWebsite(url){
	window.open(url);
}
function showVideo(src){
	$(".popup-video").html('<iframe src="'+src+'" width="900" height="506" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
	$(".popup-video").fadeIn('slow');
	$(".modal-bg").fadeTo('slow', .7);
}
function hideVideo(){
	$(".popup-video").fadeOut('slow');
	$(".modal-bg").fadeOut('slow');
	$(".popup-video iframe").remove();
}
function loginSignup(){	
	var signupForm = '<div class="signup-form login-panel">Please login below and you\'ll be customizing your Concierge Membership in no time!<br/><br/><br/>';
    signupForm += '<div id="signupMsg"></div>';
    signupForm += '<div class="form_line">';
    signupForm += '  <div class="input_line w_lg">';
    signupForm += '    <div class="input_title">Username email</div>';
    signupForm += '    <input id="username" name="username" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" onkeyup="setFocusOnEnter(event, \'password\')" />';
    signupForm += '    <div class="input_info" style="display: none;" >';
    signupForm += '      <div class="info_text" >Your email address</div>';
    signupForm += '    </div>';
    signupForm += '  </div>';
    signupForm += '</div>';
    signupForm += '<div class="form_line">';
    signupForm += '  <div class="input_line w_lg">';
    signupForm += '    <div class="input_title">Password</div>';
    signupForm += '    <input id="password" name="password" onfocus="ToggleInputInfo(this, 1);" type="password" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" onkeyup="sumbitLoginFormEnter(event)"/>';
    signupForm += '    <div class="input_info" style="display: none;" >';
    signupForm += '      <div class="info_text" >Your password</div>';
    signupForm += '    </div>';
    signupForm += '  </div>';
    signupForm += '</div>';
  	signupForm += '<div class="loginButtons" style="width:550px;">';
	signupForm += '<div align="left"><div class="button_new button_blue button_mid" onClick="processLoginSignup()"><div class="curve curve_left"></div><span class="button_caption">Login and Signup</span><div class="curve curve_right"></div></div>';
  	signupForm += '</div>';
  	signupForm += '<div class="clear"></div>';
	signupForm += '</div>';
	ShowPopUp('Login &amp; Concierge Sign Up!', signupForm);
}

function processLoginSignup(){
	if(validateLogin()){
		logSignInSignupIn();
	}
}

function validateLogin(){
	required = new Array(
		'username',
		'password'
	);
	
	required_type = new Array(
		'email',
		'password'
	);
	
	inValidOutput = new Array(
		'Please enter a valid email address.',
		'Please enter a valid password. Letters and numbers only.'
	);
	
	numberOfRequired = required.length;
	
	for(i=0; i<numberOfRequired; i++){
		inputValue = document.getElementById(required[i]).value;
		type = required_type[i];
		if(!validate(type, inputValue)){
			outputAlert('signupMsg', inValidOutput[i]);
			document.getElementById(required[i]).focus();
			return false;
		}
	}
	
	return true;
}

function validateSignup(){
	required = new Array(
		'firstName',
		'lastName',
		'phone',
		'username',
		'password'
	);
	
	required_type = new Array(
		'empty',
		'empty',
		'phone',
		'email',
		'password'
	);
	
	inValidOutput = new Array(
		'Please enter your first name.',
		'Please enter you last name.',
		'Please enter a valid phone number. A 10 digit phone number is required. Example 801-501-6500.',
		'Please enter a valid email address.',
		'Please enter a valid password. Letters and numbers only.'
	);
	
	numberOfRequired = required.length;
	
	for(i=0; i<numberOfRequired; i++){
		inputValue = document.getElementById(required[i]).value;
		type = required_type[i];
		if(type=="phone"){
			if(validate('empty', inputValue)){
				if(!validate(type, inputValue)){
					outputAlert('signupMsg', inValidOutput[i]);
					document.getElementById(required[i]).focus();
					return false;
				}
			}
		}else{
			if(!validate(type, inputValue)){
				outputAlert('signupMsg', inValidOutput[i]);
				document.getElementById(required[i]).focus();
				return false;
			}
		}
	}
	
	return true;
}

function signUp(){
	if(validateSignup()){
		outputAlert('signupMsg', '<img src="../repository_images/spinner-alert.gif" alt="test" width="16" height="16" align="absmiddle" /> Creating Concierge Membership, please wait...');
		var firstName = document.getElementById('firstName').value;
		var lastName = document.getElementById('lastName').value;
		var phone = document.getElementById('phone').value;
		var username = document.getElementById('username').value;
		var password = document.getElementById('password').value;
		var url = "../repository_queries/concierge-signup.php";
		var params = "firstName="+firstName+"&lastName="+lastName+"&phone="+phone+"&username="+username+"&password="+password;
		ajaxQuery(url, params, 'processSignUp');
	}
}

function signupPopupUser(userID){
	var signupForm = '<div id="signupMsg"></div>';
	signupForm += '<div align="left"><div class="button_new button_blue button_mid" onClick="creatMembership()"><div class="curve curve_left"></div><span class="button_caption">Signup</span><div class="curve curve_right"></div></div>';
	ShowPopUp('Concierge Sign Up!', signupForm);	
}

function creatMembership(){
	var url = "../repository_queries/concierge-signup.php";
	var params = "";
	ajaxQuery(url, params, 'gotoSetup');
}

function processSignUp(){
	var errors = responseXML.getElementsByTagName("error");
	var userIDs = responseXML.getElementsByTagName("userID");
	if(userIDs.length>0){
		userID = userIDs[0].childNodes[0].nodeValue;
	}
	var error = "";
	for(var i = 0; i < errors.length; i++) {
		if(errors[i].hasChildNodes()) {
			error += "<li>"+errors[i].childNodes[0].nodeValue+"</li>";
		}
	}
	if (error.length > 0) {
		error = "<ul>" + error + "</ul>";
		outputError('signupMsg', error);
	} else {
		logThemIn();
	}
}

function logThemIn(){
	username = document.getElementById('username').value;
	password = document.getElementById('password').value;
	var params = "username=" + username + "&password=" + password;
	var url = "../repository_inc/user_login_logic.php";
	ajaxQuery(url, params, 'gotoSetup');
}

function logSignInSignupIn(){
	username = document.getElementById('username').value;
	password = document.getElementById('password').value;
	var params = "username=" + username + "&password=" + password;
	var url = "../repository_inc/user_login_logic.php";
	ajaxQuery(url, params, 'processLogin');
}

function processLogin(){
	if (response == "-1") {
		outputError('signupMsg', 'Invalid username and password');
	} else if (response == "invalid") {
		outputError('signupMsg', 'Account not validated. Please check email.');
	} else {
		creatMembership();
	}
}

function gotoSetup(){
	window.location = "concierge-checkout.php";
}

$(window).scroll(function() {
	if(isScrolledIntoView(".auto-tour .step")){
		showTourCreationSteps();
	}
	if(isScrolledIntoView(".social-hub h3")){
		showSocialHub();
	}
	if(isScrolledIntoView(".brochure-creator h3")){
		showBrochure();
	}
	if(isScrolledIntoView(".agent-sites .screen")){
		showAgentSite();
	}
	if(isScrolledIntoView(".concierge-splash .featured .row1")){
		showFeaturesRow1();
	}
	if(isScrolledIntoView(".concierge-splash .featured .row2")){
		showFeaturesRow2();
	}
	if(isScrolledIntoView(".concierge-splash .brokerages .brokerage-row1")){
		showBrokerageRow1();
	}
	if(isScrolledIntoView(".concierge-splash .brokerages .brokerage-row2")){
		showBrokerageRow2();
	}
	if(isScrolledIntoView(".concierge-splash .brokerages .cta")){
		showBrokerageCTA();
	}
});

function isScrolledIntoView(elem){
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();
    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();
    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

function showTourCreationSteps(){
	$(".auto-tour h2, .auto-tour h3").css("visibility", "visible");
	$(".auto-tour h2, .auto-tour h3").addClass("fadeInRight"); 
	$(".auto-tour h2, .auto-tour h3").addClass("animated");
	$(".auto-tour .steps .step.mls").animate({opacity: 1},400, function(){
		$(".auto-tour .steps .step.tour").animate({opacity: 1, left: "254px"}, 400, function(){
			$(".auto-tour .steps .step.update").animate({opacity: 1, left: "508px"}, 400, function(){
				$(".auto-tour .steps .step.hourly").animate({opacity: 1, left: "762px"}, 400, function(){
					
				});
			});
		});
	});
}

function showSocialHub(){
	$(".social-hub .icons").css("visibility", "visible");
	$(".social-hub .icons").addClass("rollIn"); 
	$(".social-hub .icons").addClass("animated");
	$(".social-hub .icons").css("visibility", "visible");
	$(".social-hub .desc").css("visibility", "visible").addClass("fadeInRight").addClass("animated");
}

function showBrochure(){
	$(".brochure-creator .feature").css("visibility", "visible");
	$(".brochure-creator .feature").addClass("fadeInUp"); 
	$(".brochure-creator .feature").addClass("animated");
	$(".brochure-creator h2, .brochure-creator h3").css("visibility", "visible");
	$(".brochure-creator h2, .brochure-creator h3").addClass("fadeIn"); 
	$(".brochure-creator h2, .brochure-creator h3").addClass("animated");
}

function showAgentSite(){
	$(".agent-sites .screen").css("visibility", "visible");
	$(".agent-sites .screen").addClass("fadeInLeft"); 
	$(".agent-sites .screen").addClass("animated");
	$(".agent-sites .desc").css("visibility", "visible");
	$(".agent-sites .desc").addClass("fadeInRight"); 
	$(".agent-sites .desc").addClass("animated");
}

function showFeaturesRow1(){
	$(".concierge-splash .featured .row1").css("visibility", "visible").addClass("zoomInLeft").addClass("animated");
}

function showFeaturesRow2(){
	$(".concierge-splash .featured .row2").css("visibility", "visible").addClass("zoomInRight").addClass("animated");
}

function showBrokerageRow1(){
	$(".concierge-splash .brokerages .brokerage-row1").css("visibility", "visible").addClass("fadeInUp").addClass("animated");
}

function showBrokerageRow2(){
	$(".concierge-splash .brokerages .brokerage-row2").css("visibility", "visible").addClass("fadeInUp").addClass("animated");
}

function showBrokerageCTA(){
	$(".concierge-splash .brokerages .cta").css("visibility", "visible").addClass("bounceIn").addClass("wow").addClass("animated");
}

$(window).bind('resize', function() {   
	setResourceByWindowSize();
});
 
function setResourceByWindowSize(){
	var bannerHeight = 97;
	var videoHeight = $(".concierge-splash video").height();
	var introHeight = (videoHeight-bannerHeight);
	var introContentTop = (introHeight/2) - ($('.intro').height()/2);
	var bannerHeight = (introHeight-introContentTop);
	if((bannerHeight+introContentTop)>videoHeight){
		bannerHeight = videoHeight-introContentTop;
	}
	$('.banner').css('height', (bannerHeight)+'px');
	$('.banner').css('padding-top', introContentTop+'px');
	$('.intro').css('top', introContentTop+'px');
	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
		},
		any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};
	if (isMobile.any()) {
		isMobile = true;
	}else{
		isMobile = false;
	}
	if(isMobile){
		$("video").hide();
	}else{
		$("video").show();
	}
	if($('.intro').height()>videoHeight){
		$('.intro').css("zoom", "70%");
		$(".intro").css("top", "67px");
	}else{
		$('.intro').css("zoom", "100%");
		$('.intro').css('top', introContentTop+'px');
	}
}
setTimeout(function(){setResourceByWindowSize();}, 600);
setResourceByWindowSize();