<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><cfoutput>#request.admin.name#</cfoutput> :: Administrator</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<style type="text/css">
			<!--
			body {
				background-color: #F5F5F5;
			}
			-->
		</style>
		<link href="includes/admin_styles.css" rel="stylesheet" type="text/css" />
		
		<script language = "javascript">
			var XMLHttpRequestObject = false; // Ajax http request object
			
			// Create the http request object
			if (window.XMLHttpRequest) {
				XMLHttpRequestObject = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			function KeyPressed(e) {
				var key=e.keyCode ||e.which;
				
				if(key==13)
					PHP_Login();	
			}
			
			// Login and create the PHP session then submit the form to create the CF session.
			function PHP_Login() {
				try {
					var url = "../repository_inc/login_logic.php";
					var params = "username=" + document.getElementById('username2').value + "&password=" + document.getElementById('password2').value;
					
					if(XMLHttpRequestObject) {
						XMLHttpRequestObject.open("POST", url, true);
						XMLHttpRequestObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						XMLHttpRequestObject.setRequestHeader("Content-length", params.length);
						XMLHttpRequestObject.setRequestHeader("Connection", "close");

						XMLHttpRequestObject.onreadystatechange = function() { 
							if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
								if (XMLHttpRequestObject.responseText != "-1") {
									document.forms["login"].submit();
								} else {
									document.getElementById('error').innerHTML = "Invalid username and password";
								}
							}
						}
						XMLHttpRequestObject.send(params);
						
					}
				} catch(err) {
					window.alert(err);
				}
			}
			
		</script>
		
	</head>

	<body onLoad="document.login.adminUsername.focus()">
		<div align="center" style="margin-top: 75px;">
			<form id="login" name="login" action="index.cfm" method="post" onkeypress="KeyPressed(event);">
				<table width="400" border="1" cellpadding="15" cellspacing="0" bgcolor="#e6e6e6">
					<tr>
						<td>
							<table width="400" border="0" cellspacing="0" cellpadding="4">
								<tr>
									<td height="50" colspan="2" align="center">
										<strong>Administration Login</strong>
										<div id="error" class="error">
											<cfif isDefined("url.invalid")>Invalid username and password</cfif>
										</div>
									</td>
								</tr>
								<tr>
									<td width="135" align="right">Username</td>
									<td width="257">
										<input name="adminUsername" type="text" id="username2" maxlength="10" onkeypress="KeyPressed(event);"/>
									</td>
								</tr>
								<tr>
									<td height="28" align="right">Password</td>
									<td>
										<input name="password" type="password" id="password2" maxlength="12" onkeypress="KeyPressed(event);"/>
										<input type="button" style="margin-left: 10px;" value="   Login   " onclick="PHP_Login();" />
									</td>
								</tr>
								<tr>
									<td height="28" align="center"></td>
									<td></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>
