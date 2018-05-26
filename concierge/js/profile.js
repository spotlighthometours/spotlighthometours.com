// JavaScript Document
var goToNextStepCheck = false;
function validateUserInfo(goToNextStep){
	goToNextStepCheck = goToNextStep;
	formName = 'userInfoForm';
	
	required = new Array(
		'firstName',
		'lastName',
		'BrokerageID',
		'password',
		'email',
		'address',
		'city',
		'state',
		'zipCode',
		'phone',
		'phonecarrier',
		'mls[]',
		'mls_provider[]'
	);
	
	required_type = new Array(
		'empty',
		'empty',
		'select',
		'password',
		'empty',
		'empty',
		'empty',
		'select',
		'zip',
		'phone',
		'select',
		'empty',
		'select'
	);
	
	inValidOutput = new Array(
		'Please enter your first name (Letters only).',
		'Please enter you last name (Letters only).',
		'Please select a brokerage. Select None/Other at the bottom of the list if none.',
		'Please enter a valid password. Letters and numbers only.',
		'Please enter a valid email address.',
		'Please enter your mailing address.',
		'Please enter your city.',
		'Please select a state.',
		'Please enter a valid zip code. Example: 84106 or 84106-2389',
		'Please enter a valid phone number. A 10 digit phone number is required. Example (801)501-6500.',
		'Please select your phone provider.',
		'Please enter your MLS agent ID#',
		'Please select an MLS provider'
	);
	
	numberOfRequired = required.length;
	
	for(i=0; i<numberOfRequired; i++){
		if(required_type[i]=="select"){
			inputValue = $("select[name='"+required[i]+"']").val();
			if(inputValue<0){
				inputValue = 0;
			}
			type = 'empty';
		}else{
			inputValue = $("input[name='"+required[i]+"']").val();
			console.log(inputValue);
			type = required_type[i];
		}
		var checkIt = true;
		if(required_type[i]=="password"){
			if(inputValue){
				checkIt = true;
			}else{
				checkIt = false;
			}
		}
		if(!validate(type, inputValue)&&checkIt){
			outputAlert('userInfoMsg', inValidOutput[i]);
			$('html,body').animate({scrollTop: $("#userInfoMsg").offset().top-50},'fast');
			if(required_type[i]=="password"){
				$("#password").val('');
				$("#password2").val('');	
			}
			document.forms[formName][required[i]].focus();
			exit;
		}
		if(required_type[i]=="password"){
			if($("#password").val()!==$("#password2").val()){
				outputAlert('userInfoMsg', 'The password in field 1 does not match the password in field 2. Please re-enter your new password.');
				$('html,body').animate({scrollTop: $("#userInfoMsg").offset().top-50},'fast');
				$("#password").val('');
				$("#password2").val('');
				document.forms[formName][required[i]].focus();
				exit;
			}
		}
	}
	
	// Validation Passed Update User Information
	$('#userInfoMsg').html('');
	 updateUserInfo();
}

function updateUserInfo(){
	var params = '';
	var first = true;
	$(':input', '#userInfoForm').each(function() {
    	if(!first){
			params += "&";
		}
		params += this.name+"="+encodeURIComponent(this.value);
		first = false;
    });
	ajaxMessage('Saving...', 'processing');
	var url = '../../repository_queries/user_member_updateinfo.php';
	ajaxQuery(url, params, 'userInfoSaved');
}

function userInfoSaved(){
	ajaxMessage('Membership Details Saved!', 'success');
	outputAlert('userInfoMsg', 'Membership Details Saved!');
	if(goToNextStepCheck){
		window.location='tour-window.php';
	}else{
		window.location='set-up.php';
	}
}

function addMLSInput(){
	$('#mls_frame').append($('#mls_source').html());
}

function removeMLSInput(Obj){
	$(Obj).parent().parent().remove();
}