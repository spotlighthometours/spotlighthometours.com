<?php
/**********************************************************************************************
Document: admin_virtualstaging.php
Creator: Brandon Freeman
Date: 02-15-11
Purpose: Administration page for tours.
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

// Connect to MySQL
require_once ('../repository_inc/connect.php');

//=======================================================================
// Document
//=======================================================================

// Start the session
session_start();

$debug = false;

// Require Admin Login
if (!$debug) {
	require_once ('../repository_inc/require_admin.php');
}

$dir = '../repository_thumbs/virtual_staging';
$files = scandir($dir);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Spotlight Home Tours Admin - Virtual Staging</title>
		<link REL="SHORTCUT ICON" HREF="../repository_images/icon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">@import "../repository_css/admin.css";</style>
		<style type="text/css" media="screen">@import "../repository_css/spinner.css";</style>
		<script language = "javascript">
			var HTTP = false; //Ajax http request object
			
			//Create the http request object
			if (window.XMLHttpRequest) {
				HTTP = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				HTTP = new ActiveXObject("Microsoft.XMLHTTP");
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
			
			
			function UpdateRooms() {
				try {
					WaitOn();
					var url = "../repository_queries/admin_virtualstaging_getrooms.php";
					var params = "";
					var rooms = false;
					var newHTML = "";
					if(HTTP) {
						WaitOn();
						HTTP.open("POST", url, true);
						HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						HTTP.setRequestHeader("Content-length", params.length);
						HTTP.setRequestHeader("Connection", "close");

						HTTP.onreadystatechange = function() { 
							if (HTTP.readyState == 4 && HTTP.status == 200) {
								rooms = HTTP.responseText.split(",");
								for (var i = 0; i < rooms.length; i++){
									newHTML += '<option value="' + rooms[i] + '" >' + rooms[i] + '</option>';
								}
								document.getElementById('rooms').innerHTML = newHTML;
								document.getElementById('rooms').selectedIndex = -1;
								WaitOff();
							}
						}
						HTTP.send(params);
					}
				} catch(err) {
					window.alert("UpdateRooms: " + err);
				}
			}
			
			function AddRoom() {
				try {
					if (document.getElementById('newroom').value.length > 0) {
						WaitOn();
						var url = "../repository_queries/admin_virtualstaging_addroom.php";
						var params = "room=" + document.getElementById('newroom').value;
						if(HTTP) {
							HTTP.open("POST", url, true);
							HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							HTTP.setRequestHeader("Content-length", params.length);
							HTTP.setRequestHeader("Connection", "close");

							HTTP.onreadystatechange = function() { 
								if (HTTP.readyState == 4 && HTTP.status == 200) {
									WaitOff();
									if (HTTP.responseText == "1") {
										document.getElementById('newroom').value = "";
										UpdateRooms();
									} else {
										window.alert('AddRoom Failed: ' + HTTP.responseText);
									}
								}
							}
							HTTP.send(params);
						}
					}
				} catch(err) {
					window.alert("AddRoom: " + err);
				}
			}
			
			function DeleteRoom() {
				try {
					if (document.getElementById('rooms').selectedIndex != -1) {
						if (confirm("Delete " + document.getElementById('rooms').options[document.getElementById('rooms').selectedIndex].value + "?! \nAre you sure?")) {
							WaitOn();
							var url = "../repository_queries/admin_virtualstaging_delroom.php";
							var params = "room=" + document.getElementById('rooms').options[document.getElementById('rooms').selectedIndex].value;
							if(HTTP) {
								HTTP.open("POST", url, true);
								HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								HTTP.setRequestHeader("Content-length", params.length);
								HTTP.setRequestHeader("Connection", "close");

								HTTP.onreadystatechange = function() { 
									if (HTTP.readyState == 4 && HTTP.status == 200) {
										WaitOff();
										if (HTTP.responseText == "1") {
											UpdateRooms();
										} else {
											window.alert('DelRoom Failed: ' + HTTP.responseText);
										}
									}
								}
								HTTP.send(params);
							}
						}
					}
				} catch(err) {
					window.alert("DeleteRoom: " + err);
				}
			}
			
			function UpdateStyles() {
				try {
					WaitOn();
					var url = "../repository_queries/admin_virtualstaging_getstyles.php";
					var params = "";
					var styles = false;
					var newHTML = "";
					if(HTTP) {
						HTTP.open("POST", url, true);
						HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						HTTP.setRequestHeader("Content-length", params.length);
						HTTP.setRequestHeader("Connection", "close");

						HTTP.onreadystatechange = function() { 
							if (HTTP.readyState == 4 && HTTP.status == 200) {
								styles = HTTP.responseText.split(",");
								for (var i = 0; i < styles.length; i++){
									newHTML += '<option value="' + styles[i] + '" >' + styles[i] + '</option>';
								}
								document.getElementById('styles').innerHTML = newHTML;
								document.getElementById('styles').selectedIndex = -1;
								WaitOff();
							}
						}
						HTTP.send(params);
					}
				} catch(err) {
					window.alert("UpdateStyles: " + err);
				}
			}
			
			function AddStyle() {
				try {
					if (document.getElementById('newstyle').value.length > 0) {
						WaitOn();
						var url = "../repository_queries/admin_virtualstaging_addstyle.php";
						var params = "style=" + document.getElementById('newstyle').value;
						if(HTTP) {
							HTTP.open("POST", url, true);
							HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							HTTP.setRequestHeader("Content-length", params.length);
							HTTP.setRequestHeader("Connection", "close");

							HTTP.onreadystatechange = function() { 
								if (HTTP.readyState == 4 && HTTP.status == 200) {
									WaitOff();
									if (HTTP.responseText == "1") {
										document.getElementById('newstyle').value = "";
										UpdateStyles();
									} else {
										window.alert('AddStyle Failed: ' + HTTP.responseText);
									}
								}
							}
							HTTP.send(params);
						}
					}
				} catch(err) {
					window.alert("AddStyle: " + err);
				}
			}
			
			function DeleteStyle() {
				try {
					if (document.getElementById('styles').selectedIndex != -1) {
						if (confirm("Delete " + document.getElementById('styles').options[document.getElementById('styles').selectedIndex].value + "?! \nAre you sure?")) {
							WaitOn();
							var url = "../repository_queries/admin_virtualstaging_delstyle.php";
							var params = "style=" + document.getElementById('styles').options[document.getElementById('styles').selectedIndex].value;
							if(HTTP) {
								HTTP.open("POST", url, true);
								HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								HTTP.setRequestHeader("Content-length", params.length);
								HTTP.setRequestHeader("Connection", "close");

								HTTP.onreadystatechange = function() { 
									if (HTTP.readyState == 4 && HTTP.status == 200) {
										WaitOff();
										if (HTTP.responseText == "1") {
											UpdateStyles();
										} else {
											window.alert('DelStyle Failed: ' + HTTP.responseText);
										}
									}
								}
								HTTP.send(params);
							}
						}
					}
				} catch(err) {
					window.alert("DeleteStyle: " + err);
				}
			}
			
			function PreviewPhoto() {
				try {
					if (document.getElementById('bankset').selectedIndex != -1) {
						var file = document.getElementById('bankset').options[document.getElementById('bankset').selectedIndex].value;
						document.getElementById('bankphoto').src = file;
					}
				} catch(err) {
					window.alert("PreviewPhoto: " + err);
				}
			}
			
			function UpdateSets() {
				try {
					WaitOn();
					var url = "../repository_queries/admin_virtualstaging_getsetxml.php";
					var params = "";
					var xmlObj = false;
					var newHTML = "";
					if(HTTP) {
						HTTP.open("POST", url, true);
						HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						HTTP.setRequestHeader("Content-length", params.length);
						HTTP.setRequestHeader("Connection", "close");

						HTTP.onreadystatechange = function() { 
							if (HTTP.readyState == 4 && HTTP.status == 200) {
								xmlDoc = HTTP.responseXML.getElementsByTagName("set");
								for (var i = 0; i < xmlDoc.length; i++) {
									var room = xmlDoc[i].attributes.getNamedItem("room").value;
									var style = xmlDoc[i].attributes.getNamedItem("style").value;
									var file = xmlDoc[i].attributes.getNamedItem("file").value;
									var tempfile = file.substring(file.lastIndexOf("/") + 1)
									newHTML += '<option value="' + room + ',' + style + ',' + file + '" >' + room + ' > ' + style + ' > ' + tempfile + '</option>';
									//if (xmlDoc[i].hasChildNodes()) {
									//	alert(xmlDoc[i].firstChild.nodeValue);
									//}
								}
								document.getElementById('sets').innerHTML = newHTML;
								document.getElementById('sets').selectedIndex = -1;
								WaitOff();
							}
						}
						HTTP.send(params);
					}
				} catch(err) {
					window.alert("UpdateSets: " + err);
				}
			}
			
			function AddSet() {
				try {
					if (document.getElementById('rooms').selectedIndex > -1 && document.getElementById('styles').selectedIndex  > -1 && document.getElementById('bankset').selectedIndex > -1 ) {
						var roomName = document.getElementById('rooms').options[document.getElementById('rooms').selectedIndex].value;
						var styleName = document.getElementById('styles').options[document.getElementById('styles').selectedIndex].value;
						var setImage = document.getElementById('bankset').options[document.getElementById('bankset').selectedIndex].value;
						var description = document.getElementById('description').value;
					
						var url = "../repository_queries/admin_virtualstaging_addset.php";
						var params  = "name=" + styleName;
							params += "&img=" + setImage;
							params += "&room=" + roomName;
							params += "&description=" + description;
							
						if(HTTP) {
							WaitOn();
							HTTP.open("POST", url, true);
							HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							HTTP.setRequestHeader("Content-length", params.length);
							HTTP.setRequestHeader("Connection", "close");

							HTTP.onreadystatechange = function() { 
								if (HTTP.readyState == 4 && HTTP.status == 200) {
									if (HTTP.responseText == "1") {
										UpdateSets();
									} else {
										window.alert(HTTP.responseText);
									}
									WaitOff();
								}
							}
							HTTP.send(params);
						}
					} else {
						var error = "";
						if (document.getElementById('rooms').selectedIndex == -1) {
							error += "Please select a room\n";
						}
						if (document.getElementById('styles').selectedIndex == -1) {
							error += "Please select a style\n";
						}
						if (document.getElementById('bankset').selectedIndex == -1) {
							error += "Please select a photo\n";
						}
						window.alert(error);
					}
				} catch(err) {
					window.alert("AddSet: " + err);
				}
			}
			
			function DelSet() {
				try {
					if (document.getElementById('sets').selectedIndex > -1 ) {
						var data = document.getElementById('sets').options[document.getElementById('sets').selectedIndex].value.split(',');
						var roomName = data[0];
						var styleName = data[1];
						var setImage = data[2];
						
						var url = "../repository_queries/admin_virtualstaging_delset.php";
						var params  = "name=" + styleName;
							params += "&img=" + setImage;
							params += "&room=" + roomName;
						
						if(HTTP) {
							WaitOn();
							HTTP.open("POST", url, true);
							HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							HTTP.setRequestHeader("Content-length", params.length);
							HTTP.setRequestHeader("Connection", "close");

							HTTP.onreadystatechange = function() { 
								if (HTTP.readyState == 4 && HTTP.status == 200) {
									if (HTTP.responseText == "1") {
										UpdateSets();
									} else {
										window.alert(HTTP.responseText);
									}
									WaitOff();
								}
							}
							HTTP.send(params);
						}
					} else {
						window.alert("Please select a set to delete.");
					}
				} catch(err) {
					window.alert("DelSet: " + err);
				}
			}
			
			function EditSet() {
				try {
					var info = document.getElementById('sets').options[document.getElementById('sets').selectedIndex].value.split(',');
					var room = info[0];
					var style = info[1];
					var image = info[2];
					
					var url = "../repository_queries/admin_virtualstaging_getsetxml.php";
					var params  = "name=" + style;
						params += "&img=" + image;
						params += "&room=" + room;
						
					if(HTTP) {
						WaitOn();
						HTTP.open("POST", url, true);
						HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						HTTP.setRequestHeader("Content-length", params.length);
						HTTP.setRequestHeader("Connection", "close");

						HTTP.onreadystatechange = function() { 
							if (HTTP.readyState == 4 && HTTP.status == 200) {
								xmlDoc = HTTP.responseXML.getElementsByTagName("set");
								for (var i = 0; i < xmlDoc.length; i++) {
									if (xmlDoc[i].hasChildNodes()) {
										document.getElementById('description').value = xmlDoc[i].firstChild.nodeValue;
									} else {
										document.getElementById('description').value = '';
									}
								}
								// Set the selected index of rooms.
								for (var i = 0; i < document.getElementById('rooms').options.length; i++) {
									if (document.getElementById('rooms').options[i].value == room) {
										document.getElementById('rooms').selectedIndex = i;
									}
								}
								// Set the selected index of styles.
								for (var i = 0; i < document.getElementById('styles').options.length; i++) {
									if (document.getElementById('styles').options[i].value == style) {
										document.getElementById('styles').selectedIndex = i;
									}
								}
								// Set the selected index of the photo.
								for (var i = 0; i < document.getElementById('bankset').options.length; i++) {
									if (document.getElementById('bankset').options[i].value == image) {
										document.getElementById('bankset').selectedIndex = i;
									}
								}
								PreviewPhoto();
								WaitOff();
							}
						}
						HTTP.send(params);
					}
				} catch(err) {
					window.alert("EditSet: " + err);
				}
			}
			
			function UpdateSetDesc() {
				try {
					if (document.getElementById('sets').selectedIndex > -1) {
						var info = document.getElementById('sets').options[document.getElementById('sets').selectedIndex].value.split(',');
						var room = info[0];
						var style = info[1];
						var image = info[2];
						
						var url = "../repository_queries/admin_virtualstaging_updatesetdesc.php";
						var params  = "name=" + style;
							params += "&img=" + image;
							params += "&room=" + room;
							params += "&description=" + document.getElementById('description').value;
							
						if(HTTP) {
							WaitOn();
							HTTP.open("POST", url, true);
							HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							HTTP.setRequestHeader("Content-length", params.length);
							HTTP.setRequestHeader("Connection", "close");

							HTTP.onreadystatechange = function() { 
								if (HTTP.readyState == 4 && HTTP.status == 200) {
									if (HTTP.responseText == "1") {
										window.alert("Update Successful");
									} else {
										window.alert(HTTP.responseText);
									}
									WaitOff();
								}
							}
							HTTP.send(params);
						}
					} else {
						window.alert("Please select a set to update.");
					}
				} catch(err) {
					window.alert("UpdateSetDesc: " + err);
				}
			}
			
		</script>
		
	</head>
	<body onmousemove="MouseMove(event);" onmouseup="MouseUp(event);" >
		<div id="mainframe" >
			<div id="header" ></div>
			<div id="title" >Virtual Staging</div>
			
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- LIST ROOMS AND DESIGN STYLES TYPES BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
			<div id="list" class="visible" >
				<div class="formrow frtall" >
					<div class="row r_name" >Rooms</div>
					<div class="row r_content r_tall" >
						<select multiple="yes" id="rooms" class="input mid tall left" >
							<?php
								// List the Tour Categories in some option tags for the select.
								$query = "SELECT room_name FROM vs_rooms ORDER BY room_name";
								$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
								while($result = mysql_fetch_array($r)){
									echo '
							<option value="' . $result['room_name'] . '">' . $result['room_name'] . '</option>
									';
								}
							?>
						</select>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						<div class="button_txt left noselect" onclick="DeleteRoom();" >Delete</div>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						<input id="newroom" class="input mid left" type="text" />
						<div class="button_txt left noselect" onclick="AddRoom();" >Add</div>
					</div>
				</div>
				<div class="formrow frtall" >
					<div class="row r_name" >Design Styles</div>
					<div class="row r_content r_tall" >
						<select multiple="yes" id="styles" class="input mid tall left" >
							<?php
								// List the Tour Categories in some option tags for the select.
								$query = "SELECT style_name FROM vs_designstyles ORDER BY style_name";
								$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
								while($result = mysql_fetch_array($r)){
									echo '
							<option value="' . $result['style_name'] . '">' . $result['style_name'] . '</option>
									';
								}
							?>
						</select>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						<div class="button_txt left noselect" onclick="DeleteStyle();" >Delete</div>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						<input id="newstyle" class="input mid left" type="text" />
						<div class="button_txt left noselect" onclick="AddStyle();" >Add</div>
					</div>
				</div>
				<div class="formrow frtall" >
					<div class="row r_name" >Photo Bank</div>
					<div class="row r_content r_tall" >
						<select multiple="yes" id="bankset" class="input wide tall left" onchange="PreviewPhoto();" >
							<?php
									$count = 0;
									foreach($files as $file){
										if (file_exists($dir . '/' . $file) && (strpos($file, ".png") || strpos($file, ".jpg"))) {
											echo '
							<option value="' . $dir . '/' . $file . '">' . $file . '</option>
											';
											$count++;
										}
									}
							?>
						</select>
					</div>
				</div>
				<div class="formrow fr_vs_pic" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content r_vs_pic" >
						<img id="bankphoto" class="set_photo noselect left" src="../repository_thumbs/virtual_staging_unknown.png"  />
					</div>
				</div>
				<div class="formrow frtall" >
					<div class="row r_name" >Description</div>
					<div class="row r_content r_tall" >
						<textarea id="description" class="input wide tall" ></textarea>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						<div class="button_txt left noselect" onclick="AddSet();" >Add</div>
					</div>
				</div>
				<div class="formrow frtall" >
					<div class="row r_name" >Design Sets</div>
					<div class="row r_content r_tall" >
						<select multiple="yes" id="sets" class="input wide tall left" onchange="EditSet();" >
							<?php
								// List the Tour Categories in some option tags for the select.
								$query = "SELECT * FROM vs_designsets ORDER BY room_name, style_name, set_image";
								$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
								while($result = mysql_fetch_array($r)){
									$tempfile = substr($result['set_image'], (strrpos($result['set_image'], "/", -1 * sizeof($result['set_image'])) + 1 ));
									echo '
							<option value="' . $result['room_name'] . ',' . $result['style_name'] . ',' . $result['set_image'] .  '">' . $result['room_name'] . ' > ' . $result['style_name'] . ' > ' . $tempfile . '</option>
									';
								}
							?>
						</select>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						<div class="button_txt left noselect" onclick="UpdateSetDesc();" >Update Description</div>
						<div class="button_txt left noselect" onclick="DelSet();" >Delete</div>
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