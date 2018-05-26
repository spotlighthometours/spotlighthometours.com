<?php
/**********************************************************************************************
Document: admin_products.php
Creator: Brandon Freeman
Date: 02-24-11
Purpose: Administration page for tours.
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');

// Connect to MySQL
require_once ('../repository_inc/connect.php');

//=======================================================================
// Objects
//=======================================================================

$tourtypes = new tourtypes();

//=======================================================================
// Document
//=======================================================================

$alltourtypes = $tourtypes->listAll();

// Start the session
session_start();

$debug = false;

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
		<title>Spotlight Home Tours Admin - Additional Products</title>
		<link REL="SHORTCUT ICON" HREF="../repository_images/icon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">@import "../repository_css/admin.css";</style>
		<style type="text/css" media="screen">@import "../repository_css/spinner.css";</style>
		<script language = "javascript">
			var XMLHttpRequestObject = false; //Ajax http request object
			
			var image_count = <?php echo sizeof($files) - 2; ?>;
			var first = 1;
			var last = 3;
			
			//Create the http request object
			if (window.XMLHttpRequest) {
				XMLHttpRequestObject = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			// Scroll the image box to the left.
			function ScrollLeft() {
				if (first > 1) {
					last--;
					first--;
					UpdatePosition();
				}
			}
			
			// Scroll the image box to the right.
			function ScrollRight() {
				if (last < image_count) {
					last++;
					first++;
					UpdatePosition();
				}
			}
			
			// Updates what images are currently shown in the box.
			function UpdatePosition() {
				try {
					var picture = false;
					for (var i = 1; i <= image_count; i++) {
						picture = document.getElementById('img' + i);
						if (picture != null) {
							if (i >= first && i <= last) {
								picture.className = "producticon";
							} else {
								picture.className = "producticon hidden";
							}
						}
					}
					var display = document.getElementById('iconpath');
					if (display != null)
						display.value = "";
				} catch(err) {
					window.alert('UpdatePosition: '+err);
				}
			}
			
			// When the user click on the button, we want its url to be put into the box.
			function SelectImage(imageID) {
				try {
					UpdatePosition();
					var picture = document.getElementById('img' + imageID);
					var path = document.getElementById('path' + imageID);
					var display = document.getElementById('iconpath');
					picture.className = "producticonselected";
					display.value = path.value;
				} catch(err) {
					window.alert(err);
				}
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
					window.alert("SwitchClass:"+err);
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
			
			function confirmDelete(itemName, tourTypeID) {
				if (confirm("Delete " + itemName + "? \nAre you sure?")) {
					DeleteTourType(tourTypeID);
				}
			}
			
			function DeleteTourType(productID) {
				try {
					var url = "../repository_queries/admin_products_delproduct.php";
					var params = "productid=" + productID;
					var errorDisplay = document.getElementById('errortext');
					
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
					var url = "admin_products_list_products.php";
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
					window.alert("UpdateList:"+err);
				}
			}
			
			function CreateNew() {
				try {
					var type = document.getElementById('create').value; // Updating or Creating?
					var productid = document.getElementById('productid').value;
					var parent = document.getElementById('parent').value;
					var name = document.getElementById('name').value;
					var videowt = document.getElementById('videowt').value;
					var rsvideos = document.getElementById('rsvideos').value;
					var motionscenes = document.getElementById('motionscenes').value;
					var photos = document.getElementById('photos').value;
                    var hdr_photos = document.getElementById('hdr_photos').value;
					var unitprice = document.getElementById('unitprice').value;
					var taxable = 0;
					if (document.getElementById('is_taxable').checked == true) taxable = 1;
					var isdefault = 0;
					if (document.getElementById('is_default').checked == true) isdefault = 1;
					var monthly = 0;
					if (document.getElementById('is_monthly').checked == true) monthly = 1;
					var oneperorder = 0;
					if (document.getElementById('is_oneperorder').checked == true) oneperorder = 1;
					var visible = 0;
					if (document.getElementById('visible').checked == true) visible = 1;
					var formfile = document.getElementById('formfile').value;
					var formname = document.getElementById('formname').value;
					var demolink = encodeURIComponent(document.getElementById('demolink').value);
					var USBlacklist = 0;
					if (document.getElementById('USBlacklist').checked == true) USBlacklist = 1;
					var tagline = document.getElementById('tagline').value;
					var description = document.getElementById('description').value;
					var iconpath = document.getElementById('iconpath').value;
					var prodToTTID = document.getElementById('prodToTTID').value;
					
					var errorDisplay = document.getElementById('errortext');
					
					var url = "../repository_queries/admin_products_updateproduct.php";
					
					var params  = "type=" + type;
						params += "&productid=" + productid; 
						params += "&parent=" + parent; 
						params += "&name=" + name;
						params += "&videowt=" + videowt;
						params += "&rsvideos=" + rsvideos;
						params += "&motionscenes=" + motionscenes;
						params += "&photos=" + photos;
						params += "&hdr_photos=" + hdr_photos;
						params += "&unitprice=" + unitprice;
						params += "&is_taxable=" + taxable; 
						params += "&is_monthly=" + monthly;
						params += "&is_oneperorder=" + oneperorder;
						params += "&formfile=" + formfile;
						params += "&formname=" + formname;
						params += "&demolink=" + demolink;
						params += "&tagline=" + tagline;
						params += "&description=" + description; 
						params += "&iconpath=" + iconpath;
						params += "&visible=" + visible;
						params += "&is_default=" + isdefault;
						params += "&prodToTTID=" + prodToTTID;
						params += "&USBlacklist=" + encodeURIComponent(USBlacklist);
					
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
					window.alert("CreateNew:"+err);
				}
			}
			
			function Edit(itemID) {
				try {
					WaitOn();
					var dataSource = "../repository_queries/admin_products_getproduct.php?id=" + itemID;
					var info = false;
					var data = false;
					if (XMLHttpRequestObject) {
						// Ajax query to get the tour information.
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
									}else if (document.getElementById(data[0]).type == "select") {
										var options = document.getElementById(data[0]).options;
										for (var j = 0; j < options.length; j++) {
											if (options[j].value == data[1]) {
												document.getElementById(data[0]).selectedIndex = j;
											}
										}
									// The case of the checkbox
									} else {
										document.getElementById(data[0]).value = data[1];
									}
								}
								
								document.getElementById('create').value = "Update";  // Make it say update.
								WaitOff();
								ViewForm();
								document.getElementById("product-tt-pricing").style.display = "block";
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
					document.getElementById('productid').value = "";
					document.getElementById('parent').value = "";
					document.getElementById('name').value = "";
					document.getElementById('parent').value = "";
					document.getElementById('videowt').value = "";
					document.getElementById('rsvideos').value = "";
					document.getElementById('motionscenes').value = "";
					document.getElementById('photos').value = "";
                    document.getElementById('hdr_photos').value = "";
					document.getElementById('unitprice').value = "0";
					document.getElementById('is_taxable').checked = 0;
					document.getElementById('is_monthly').checked = 0;
					document.getElementById('is_oneperorder').checked = 0;
					document.getElementById('is_default').checked = 0;
					document.getElementById('visible').checked = 1;
					document.getElementById('formfile').value = "";
					document.getElementById('formname').value = "";
					document.getElementById('tagline').value = "";
					document.getElementById('demolink').value = "";
					document.getElementById('description').value = "";
					document.getElementById('create').value = "Create";  // Reset the text on the button.
					first = 1;
					last = 3;
					UpdatePosition();
				} catch(err) {
					window.alert(err);
				}
			}
			
			function NewWindow(url, x, y) {
				try {
					window.open(url,'Preview',"location=0,status=0,scrollbars=1, width=" + x + ",height=" + y);
				} catch(err) {
					window.alert(err);
				}
			}
			
			function PreviewTour() {
				try {
					var sTest = document.getElementById('description').value;
					var reNewLines = /[\n\r]/g;
					var description = sTest.replace(reNewLines, "<br />");
					
					var url = "../checkout/checkout_tour_preview.php?";
					url += "price=" + document.getElementById('unitprice').value;
					url += "&name=" + document.getElementById('name').value;
					url += "&icon=" + document.getElementById('iconpath').value;
					url += "&description=" + description;
					url += "&tagline=" + document.getElementById('tagline').value;
					NewWindow(url, 950, 300);
				} catch(err) {
					window.alert(err);
				}
			}
			
			function UpdateOrder() {
				try {
					var errorDisplay = document.getElementById('errortext');
					var url = "../repository_queries/admin_products_updateorder.php";
					var params = "order=";
					var count = parseInt(document.getElementById('count').value);
					var first = true;
					for (var i = 0; i < count; i++) {
						if (first) {
							first = false;
						} else {
							params += ",";
						}
						params += document.getElementById(i + '-id').innerHTML + "=>" + document.getElementById(i + '-order').value;
					}
					
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
									window.alert("Update Successful");
									UpdateList();
								}
							}
						}
						XMLHttpRequestObject.send(params);
					}
				} catch(err) {
					window.alert("UpdateOrder: " + err);
				}
			}
			
			function NumberCheck(index) {
				try {
					if (isNaN(document.getElementById(index + '-order').value)) {
						document.getElementById(index + '-order').value = -1;
					}
				} catch(err) {
					window.alert("UpdateCount: " + err);
				}
			}
			
			function tourTypesWindow(){
				id = document.getElementById('productid').value;
				NewWindow('product_tourtypes.php?id='+id, '800', '400');
			}
			
			function bokeragesWindow(){
				id = document.getElementById('productid').value;
				NewWindow('product_brokerages.php?id='+id, '800', '800');
			}
			
			function affiliatesWindow(){
				id = document.getElementById('productid').value;
				NewWindow('product_affiliates.php?id='+id, '800', '800');
			}
			
			function addPricingWindow(){
				id = document.getElementById('productid').value;
				NewWindow('additional-pricing/?type=product&typeID='+id, '950', '600');
			}
			
			function attMedia(){
				id = document.getElementById('productid').value;
				NewWindow('attach-media/?type=product&typeID='+id, '950', '600');
			}
			
		</script>
		
	</head>
	<body>
		<div id="mainframe" >
			<div id="header" ></div>
			<div id="title" >Additional Products</div>
			
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- LIST TOUR TYPES BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
			<div id="list" class="visible" >
				<div id="listtable" >
					<?php require('admin_products_list_products.php'); ?>
                </div>
			</div>
			
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- NEW/EDIT FORM BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
			<div id="editform" class="hidden" >
				<input id="productid" type="hidden" value="" />
				<div class="formrow" >
					<div class="row r_name" >Product Name</div>
					<div class="row r_content" >
						<input id="name" class="input wide left" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Parent ID</div>
					<div class="row r_content" >
						<input id="parent" class="input sm left" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Walk Thrus</div>
					<div class="row r_content" >
						<input id="videowt" class="input xsm" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Videos</div>
					<div class="row r_content" >
						<input id="rsvideos" class="input xsm" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Motion Scenes</div>
					<div class="row r_content" >
						<input id="motionscenes" class="input xsm" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Photos</div>
					<div class="row r_content" >
						<input id="photos" class="input xsm" type="text" />
					</div>
				</div>
                                <div class="formrow" >
					<div class="row r_name" >HDR Photos</div>
					<div class="row r_content" >
						<input id="hdr_photos" class="input xsm" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Global Price</div>
					<div class="row r_content" >
						<input id="unitprice" class="input sm left" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Sales Tax?</div>
					<div class="row r_content" >
						<input id="is_taxable" class="input left" type="checkbox" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Monthly Charge?</div>
					<div class="row r_content" >
						<input id="is_monthly" class="input left" type="checkbox" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >One Per Order?</div>
					<div class="row r_content" >
						<input id="is_oneperorder" class="input left" type="checkbox" />
					</div>
				</div>
                <div class="formrow" >
					<div class="row r_name" >Globally Visible?</div>
					<div class="row r_content" >
						<input id="visible" class="input left" type="checkbox" />
					</div>
				</div>
                <div class="formrow" >
					<div class="row r_name" >Blacklist Entire US</div>
					<div class="row r_content" >
						<input id="USBlacklist" class="input left" type="checkbox" />
					</div>
				</div>
                <div class="formrow" >
					<div class="row r_name" >Default</div>
					<div class="row r_content" >
						<input id="is_default" class="input left" type="checkbox" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Form File</div>
					<div class="row r_content" >
						<input id="formfile" class="input wide left" style="background-color: #ff9898;" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Form Name</div>
					<div class="row r_content" >
						<input id="formname" class="input wide left" style="background-color: #ff9898;" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Demo Link</div>
					<div class="row r_content" >
						<input id="demolink" class="input wide left" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Tagline</div>
					<div class="row r_content" >
						<input id="tagline" class="input wide left" type="text" />
					</div>
				</div>
				<div class="formrow frtall" >
					<div class="row r_name" >Description</div>
					<div class="row r_content r_tall" >
						<textarea id="description" class="input wide tall left" ></textarea>
					</div>
				</div>
				<div class="formrow fr_product_pic" >
					<div class="row r_name" >Icon</div>
					<div class="row r_content r_producticon" >
						<div class="control" style="height: 43px;" onclick="ScrollLeft();" ></div>
							<?php
								$count = 0;
								foreach($files as $file){
									if (file_exists($dir . '/' . $file) && strpos($file, ".png")) {
										$count++;
										//We can only display 3 at a time.
										//Make the rest hidden.
										if ($count <= 3) {
											$class = "";
										} else {
											$class = "hidden";
										}
										echo '
							<img id="img' . $count . '" class="producticon ' . $class . '" src="' . $dir . '/' . $file . '" onclick="SelectImage(' . Chr(39) . $count . Chr(39) . ')" />
							<input id="path' . $count . '" type="hidden" value="' . $dir . '/' . $file . '" />
										';
									}
								}
							?>

						<div class="control" style="height: 43px;" onclick="ScrollRight();" ></div>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Icon File Name</div>
					<div class="row r_content" >
						<input id="iconpath" class="input wide left" type="text" />
					</div>
				</div>
                <div class="formrow" >
					<div class="row r_name" >Product to Tour Type</div>
					<div class="row r_content" >
						<?PHP
							$options = array(
								"indexes"=>array(
									"tourTypeName",
									"tourTypeID"
								),
								"options"=>$alltourtypes
							);
							echo dbRowsToSelectMenu($options, "", "prodToTTID", "prodToTTID", "Do not give the user a tour type for this product", "data-type='select-one'", "0");
						?>
					</div>
				</div>
                <div style='display:none' id="product-tt-pricing">
                    <div class="formrow" >
                        <div class="row r_name" >Available Tour Types</div>
                        <div class="row r_content" >
                            [ <a href="javascript: tourTypesWindow();">SELECT</a> ]
                        </div>
                    </div>
                    <div class="formrow" >
                        <div class="row r_name" >Available Brokerages</div>
                        <div class="row r_content" >
                            [ <a href="javascript: bokeragesWindow();">SELECT</a> ]
                        </div>
                    </div>
                    <div class="formrow" >
                        <div class="row r_name" >Available Affiliates</div>
                        <div class="row r_content" >
                            [ <a href="javascript: affiliatesWindow();">SELECT</a> ]
                        </div>
                    </div>
                    <div class="formrow" >
                        <div class="row r_name" >Additional Pricing</div>
                        <div class="row r_content" >
                            [ <a href="javascript:addPricingWindow()">SET</a> ]
                        </div>
                    </div>
                    <div class="formrow" >
                        <div class="row r_name" >Attach Media</div>
                        <div class="row r_content" >
                            [ <a href="javascript:attMedia()">Attach</a> ]
                        </div>
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