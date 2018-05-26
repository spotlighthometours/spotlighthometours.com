<?php 
    session_start();
    if( isset($_REQUEST['logout'])){
        session_destroy();
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Spotlight Home Tours :: Administrator</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<style type="text/css">
			<!--
			body {
				background-color: #F5F5F5;
			}
			-->
		</style>
		<link href="includes/admin_styles.css" rel="stylesheet" type="text/css" />
		<script src="../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->
		<script language = "javascript">
			<?php
			     if( isset($_REQUEST['logout']) ):
			?>
			     $.ajax({
			         url: "login.cfm?logout=1",
			         type: "POST"
			     }).done(function(m){
				 });
			<?php
			     endif;
			?>
			function KeyPressed(e) {
				var key=e.keyCode ||e.which;
				
				if(key==13)
					PHP_Login();	
			}
			
			// Login and create the PHP session then submit the form to create the CF session.
			function PHP_Login() {
			    $.ajax({
			        url: "index.cfm",
			        type: "POST",
			        data: {
			            adminUsername: $("#username2").val(),
			            password: $("#password2").val()
			        },
			        async: false
			    }).done(function(msg){
			        loginLogic(msg);
			    });
			}

			function loginLogic(msg){

					$.ajax({
						url: "../repository_inc/login_logic.php",
						type: "POST",
						data: {
							username: $("#username2").val(),
							password: $("#password2").val()
						},
						async: false
					}).done(function(a){
    					if (a != "-1") {
    						document.location.href="/admin/";
    					} else {
    						$('#error').html("Invalid username and password");
    					}
					});
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
											<?php
											     if( isset($_GET['invalid']) ){
                                                    echo "Invalid username and password";
                                                 }
											?>
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
