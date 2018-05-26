<?php
/**********************************************************************************************
Document: admin_mls_providers.php
Creator: Jacob Edmond Kerr
Date: 09-13-11
Purpose: Administration page for MLS providers.
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

ini_set ('display_errors', 1);
error_reporting (E_ALL & ~E_NOTICE);
ob_start();

//=======================================================================
// Includes
//=======================================================================

// Application Configuration
require_once ('../repository_inc/classes/inc.global.php');

//=======================================================================
// Object Instances
//=======================================================================

// MLS Object
$mls = new mls();

//=======================================================================
// Document
//=======================================================================

// Start the session
session_start();

$debug = true;

// Require Admin Login
if (!$debug) {
	require_once ('../repository_inc/require_admin.php');
}


$dir = '../repository_thumbs/product_icons';
$files = scandir($dir);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Spotlight Home Tours Admin - MLS Providers</title>
		<link REL="SHORTCUT ICON" HREF="../repository_images/icon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">@import "../repository_css/admin.css";</style>
		<style type="text/css" media="screen">@import "../repository_css/spinner.css";</style>
		<script language = "javascript">
			
			var XMLHttpRequestObject = false; //Ajax http request object
			
			//Create the http request object
			if (window.XMLHttpRequest) {
				XMLHttpRequestObject = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			function SwitchClass(itemID, searchFor, replaceWith) {
				try {
					var item = document.getElementById(itemID);
					var classes = item.className.split(" ");
					var newclasses = '';
					for (var i=0;i < classes.length;i++) {
						if (classes[i] == searchFor) {
							classes[i] = replaceWith;
						} 
						if (i > 0) {
							newclasses += ' ';
						} 
						newclasses += classes[i];
					}
					item.className = newclasses;
				} catch(err) {
					window.alert(err);
				}
			}
			
			function ToggleOn(itemID) {
				SwitchClass(itemID, "hidden", "visible");
			}
			
			function ToggleOff(itemID) {
				SwitchClass(itemID, "visible", "hidden");
			}
			
			function WaitOn() {
				ToggleOn('backdrop');
				ToggleOn('display');
				ToggleOn('wait');
			}
			
			function WaitOff() {
				ToggleOff('wait');
				ToggleOff('display');
				ToggleOff('backdrop');
			}
			
			function FormOn(itemID) {
				ToggleOn('backdrop');
				ToggleOn('display');
				ToggleOn(itemID);
			}
			
			function FormOff(itemID) {
				ToggleOff(itemID);
				ToggleOff('display');
				ToggleOff('backdrop');
			}
			
			function ViewList() {
				ToggleOn('list');
				ToggleOff('editform');
			}
			
			function ViewForm() {
				ToggleOn('editform');
				ToggleOff('list');
			}
			
			function confirmDelete(itemName, id) {
				if (confirm("Delete " + itemName + "?! \nAre you sure?")) {
					DeleteMLSProvider(id);
				}
			}
			
			function DeleteMLSProvider(id) {
				try {
					var url = "../repository_queries/admin_mls_delprovider.php";
					var params = "id=" + id;
					
					if(XMLHttpRequestObject) {
						WaitOn();
						XMLHttpRequestObject.open("POST", url, true);
						XMLHttpRequestObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						XMLHttpRequestObject.setRequestHeader("Content-length", params.length);
						XMLHttpRequestObject.setRequestHeader("Connection", "close");

						XMLHttpRequestObject.onreadystatechange = function() { 
							if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
								WaitOff();
								if (XMLHttpRequestObject.responseText != "1") {
									errorDisplay.innerHTML = XMLHttpRequestObject.responseText;
									FormOn('formError');  // Display an error message.
								} else {
									UpdateList();
								}
							}
						}
						XMLHttpRequestObject.send(params);
						
					}
				} catch(err) {
					window.alert(err);
				}
			}
			
			function UpdateList() {
				try {
					var list = document.getElementById('listtable');
					var url = "admin_mls_list_providers.php";
					var params = "includes=true";
					if(XMLHttpRequestObject) {
						WaitOn();
						XMLHttpRequestObject.open("POST", url, true);
						XMLHttpRequestObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						XMLHttpRequestObject.setRequestHeader("Content-length", params.length);
						XMLHttpRequestObject.setRequestHeader("Connection", "close");

						XMLHttpRequestObject.onreadystatechange = function() { 
							list.innerHTML = XMLHttpRequestObject.responseText;
							ViewList();
							WaitOff();
						}
						XMLHttpRequestObject.send(params);
					}
				} catch(err) {
					window.alert(err);
				}
			}
			
			function CreateNew() {
				try {
					var type = document.getElementById('create').value; // Updating or Creating?
					var id = document.getElementById('id').value;
					var name = document.getElementById('name').value;
					var stateID = document.getElementById('state').value;
					var website = document.getElementById('website').value;
					var concierge = document.getElementById('concierge');
					if (concierge.checked){
						concierge = 1;
					}else{
						concierge = 0;
					}
					
					var errorDisplay = document.getElementById('errortext');
					
					var url = "../repository_queries/admin_mls_updateprovider.php";
					
					var params  = "type=" + type;
						params += "&id=" + id; 
						params += "&name=" + name; 
						params += "&stateID=" + stateID;
						params += "&website=" + website;
						params += "&concierge=" + concierge;
					
					if(XMLHttpRequestObject) {
						WaitOn();
						XMLHttpRequestObject.open("POST", url, true);
						XMLHttpRequestObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						XMLHttpRequestObject.setRequestHeader("Content-length", params.length);
						XMLHttpRequestObject.setRequestHeader("Connection", "close");

						XMLHttpRequestObject.onreadystatechange = function() { 
							if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
								WaitOff();
								if (XMLHttpRequestObject.responseText != "1") {
									errorDisplay.innerHTML = XMLHttpRequestObject.responseText;
									FormOn('formError');  // Display an error message.
								} else {
									UpdateList();
									Reset();
								}
							}
						}
						XMLHttpRequestObject.send(params);
						
					}
					
				} catch(err) {
					window.alert(err);
				}
			}
			
			function Edit(itemID) {
				try {
					WaitOn();
					var dataSource = "../repository_queries/admin_mls_getprovider.php?id=" + itemID;
					var info = false;
					var data = false;
					if (XMLHttpRequestObject) {
						// Ajax query to get the mls provider information.
						XMLHttpRequestObject.open("GET", dataSource, true); 
						XMLHttpRequestObject.onreadystatechange = function() { 
							if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) { 
								// The response.
								info = XMLHttpRequestObject.responseText.split("~"); //Tilde delimited?  You betcha!		
								for (var i = 0; i < info.length; i++) {
									data = info[i].split('=>'); // Uses => to separate form id name and the data.
									
									// The case of a select box
									if (document.getElementById(data[0]).type == "select-one") {
										var options = document.getElementById(data[0]).options;
										for (var j = 0; j < options.length; j++) {
											if (options[j].value == data[1]) {
												document.getElementById(data[0]).selectedIndex = j;
											}
										}
									// The case of the checkbox
									} else if (document.getElementById(data[0]).type == "checkbox") {
										document.getElementById(data[0]).checked = parseInt(data[1]);
									// The case of everything else.
									} else {
										document.getElementById(data[0]).value = data[1];
									}
								}
								
								document.getElementById('create').value = "Update";  // Make it say update.
								WaitOff();
								ViewForm();
							} 
						} 
						XMLHttpRequestObject.send(null);
					}
				} catch(err) {
					window.alert(err);
				}
			}
			
			function Cancel() {
				UpdateList();  // Return to list view.
				Reset();	 // Reset the form.
			}
			
			function Reset() {
				try {
					document.getElementById('name').value = "";
					document.getElementById('state').value = "";
					document.getElementById('website').value = "";
					document.getElementById('create').value = "Create";  // Reset the text on the button.
					first = 1;
					last = 3;
				} catch(err) {
					window.alert(err);
				}
			}
			
			function NewWindow(url, x, y) {
				try {
					window.open(url,'Preview',"location=0,status=0,scrollbars=0, width=" + x + ",height=" + y);
				} catch(err) {
					window.alert(err);
				}
			}
			
			function NumberCheck(index) {
				try {
					if (isNaN(document.getElementById(index + '-order').value)) {
						document.getElementById(index + '-order').value = -1;
					}
				} catch(err) {
					window.alert("NumberCheck: " + err);
				}
			}
			
		</script>
		
	</head>
	<body>
		<div id="mainframe" >
			<div id="header" ></div>
			<div id="title" >MLS Providers</div>
			
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- LIST TOUR TYPES BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
			<div id="list" class="visible" >
				<div id="listtable" >
					<?php require('admin_mls_list_providers.php'); ?>
                </div>
			</div>
			
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- NEW/EDIT FORM BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
			<div id="editform" class="hidden" >
				<input id="id" type="hidden" value="" />
				<div class="formrow" >
					<div class="row r_name" >Name</div>
					<div class="row r_content" >
						<input id="name" class="input wide left" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >State</div>
					<div class="row r_content" >
						<?PHP echo stateSelectMenu(); ?>
					</div>
				</div>
                <div class="formrow" >
					<div class="row r_name" >Website</div>
					<div class="row r_content" >
						<input id="website" class="input wide left" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Concierge Enabled</div>
					<div class="row r_content" >
						<input type="checkbox" id="concierge" name="concierge" value="1">
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" ></div>
					<div class="row r_content" >
						<input id="create" class="" type="button" value="Create" onclick="CreateNew();" />
						<input class="" type="button" value="Cancel" onclick="Cancel();" />
						<div></div>
					</div>
				</div>
			</div>
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- ADDITIONAL FORM BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
			<!--- Area for additional forms that appear with the faded backdrop. --->
			<div id="backdrop" class="hidden" ></div>
			<div id="display" class="hidden" >
				
				<div id="formError" class="additionalform hidden">
					<div class="alert" ></div>
					<div id="errortext" class="big_n_white" > </div>
					<div class="big_n_white big_n_white_border" onclick="FormOff('formError');" >Close</div>
				</div>
				
				<div id="wait" class="additionalform hidden">
					<div class="big_n_white" >One moment please ...</div>
					<div id="spinner"></div>
				</div>
			
			</div>			
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- END BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
		</div>
	</body>
</html>