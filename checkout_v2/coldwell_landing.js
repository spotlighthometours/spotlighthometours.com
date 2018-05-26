function GetColdwellLoginScreen(){
    
    try {
    	if(window.location.port){
			var url = window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/repository_inc/ajax/cw-login.php";
		}else{
			var url = window.location.protocol+"//"+window.location.hostname+"/repository_inc/ajax/cw-login.php";
		}
        var params  = '';
        responseTitle = 'Login';
		    
        ajaxQuery(url, params, 'ShowResponse');
            
    } catch(err) {
        alert("GetLoginScreen: " + err);
    }
}

function CBLogin(){
		try {
			
			// Validate Form
			usernameObj = document.getElementById('username');
			
			if(!validate('email', usernameObj.value)){
				outputAlert('loginMsg', 'Please enter a valid email address for your Username email.');
				usernameObj.value="";
				usernameObj.focus();
			}else{
				if(window.location.port){
					var url = window.location.protocol+"//"+window.location.hostname+":"+window.location.port+"/repository_queries/user_email_login.php";
				}else{
					var url = window.location.protocol+"//"+window.location.hostname+"/repository_queries/user_email_login.php";
				}
				var params = "username=" + usernameObj.value;
				
				ajaxQuery(url, params, 'processCBLogin');
			}

		} catch(err) {

			window.alert("userLogin: "+err);

		}
}

function processCBLogin(){
	if (response == "-1") {
		outputError('loginMsg', 'Username email not found please try another email address or sign up now.');
	} else {
		window.location = "checkout_router.php?userid="+response;
	}
}