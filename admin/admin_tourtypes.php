<?php
/**********************************************************************************************
Document: admin_tourtypes.php
Creator: Brandon Freeman
Date: 02-10-11
Purpose: Administration page for tours.
**********************************************************************************************/

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
showErrors();

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


$dir = '../repository_thumbs/tour_icons';
$files = scandir($dir);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Spotlight Home Tours Admin - Tour Types</title>
		<link REL="SHORTCUT ICON" HREF="../repository_images/icon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">@import "../repository_css/admin.css";</style>
		<style type="text/css" media="screen">@import "../repository_css/spinner.css";</style>
		<script language = "javascript">
			window.onload = function(){
<?PHP
	if($_REQUEST['edit']){
?>
				EditTourType('<?PHP echo $_REQUEST['tourTypeID']?>');			
<?PHP
	}
?>				
			}
			var XMLHttpRequestObject = false; //Ajax http request object
			
			var image_count = <?php echo sizeof($files) - 2; ?>;
			var first = 1;
			var last = 3;
			
			var errorDisplay = document.getElementById('errortext');
			
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
								picture.className = "touricon";
							} else {
								picture.className = "touricon hidden";
							}
						}
					}
					var display = document.getElementById('iconpath');
					if (display != null)
						display.value = "";
				} catch(err) {
					window.alert("UpdatePosition:"+err);
				}
			}
			
			// When the user click on the button, we want its url to be put into the box.
			function SelectImage(imageID) {
				try {
					UpdatePosition();
					var picture = document.getElementById('img' + imageID);
					var path = document.getElementById('path' + imageID);
					var display = document.getElementById('iconpath');
					picture.className = "touriconselected";
					display.value = path.value;
				} catch(err) {
					window.alert("SelectImage:"+err);
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
				if (confirm("Delete " + itemName + "?! \nAre you sure?")) {
					DeleteTourType(tourTypeID);
				}
			}
			
			function DeleteTourType(tourTypeID) {
				try {
					var list = document.getElementById('listtable');
					var url = "../repository_queries/admin_tourtypes_deltourtype.php";
					var params = "tourtypeid=" + tourTypeID;
					
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
					window.alert("DeleteTourType:"+err);
				}
			}
			
			function UpdateList() {
				try {
					var list = document.getElementById('listtable');
					var url = "admin_tourtypes_list_tourtypes.php";
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
			
			function CreateNew() {  // ALso updates.
				try {
					var type = document.getElementById('create').value; // Updating or Creating?
					var tourtypeid = document.getElementById('tourtypeid').value;
					var name = document.getElementById('name').value;
					var category = document.getElementById('category').options[document.getElementById('category').selectedIndex].value;
					var videowt = document.getElementById('videowt').value;
					var rsvideos = document.getElementById('rsvideos').value;
					var motionscenes = document.getElementById('motionscenes').value;
					var photos = document.getElementById('photos').value;
                    var hdr_photos = document.getElementById('hdr_photos').value;
					var unitprice = document.getElementById('unitprice').value;
					var tagline = document.getElementById('tagline').value;
					var description = document.getElementById('description').value;
					var demolink = document.getElementById('demolink').value;
					//alert(demolink);
					//demolink = escape(demolink);
					//alert(demolink);
					var USBlacklist = 0;
					if (document.getElementById('USBlacklist').checked == true) USBlacklist = 1;
					var persistence = 0;
					if (document.getElementById('is_persistent').checked == true) persistence = 1;
					var isdefault = 0;
					if (document.getElementById('is_default').checked == true) isdefault = 1;
					var preview = 0;
					if (document.getElementById('is_preview').checked == true) preview = 1;
					var monthly = 0;
					if (document.getElementById('is_monthly').checked == true) monthly = 1;
					var hidden = 0;
					if (document.getElementById('is_hidden').checked == true) hidden = 1;
					var iconpath = document.getElementById('iconpath').value;
					
					var errorDisplay = document.getElementById('errortext');
					
					var url = "../repository_queries/admin_tourtypes_updatetourtype.php";
					
					var params = "type=" + encodeURIComponent(type) + "&tourtypeid=" + tourtypeid + "&name=" + encodeURIComponent(name) + "&category=" + encodeURIComponent(category) + "&videowt=" + encodeURIComponent(videowt) + "&rsvideos=" + encodeURIComponent(rsvideos);
						params += "&motionscenes=" + encodeURIComponent(motionscenes) + "&photos=" + encodeURIComponent(photos) + "&hdr_photos=" + encodeURIComponent(hdr_photos) + "&unitprice=" + encodeURIComponent(unitprice) + "&tagline=" + encodeURIComponent(tagline);
						params += "&description=" + encodeURIComponent(description) + "&demolink=" + encodeURIComponent(demolink) + "&is_preview=" + encodeURIComponent(preview) + "&is_monthly=" + encodeURIComponent(monthly) + "&is_persistent=" + encodeURIComponent(persistence);
						params += "&USBlacklist=" + encodeURIComponent(USBlacklist) + "&is_default=" + encodeURIComponent(isdefault) + "&is_hidden=" + encodeURIComponent(hidden) + "&iconpath=" + encodeURIComponent(iconpath);
					
					
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
									//UpdateProductList();
									Reset();
									UpdateList();
								}
							}
						}
						XMLHttpRequestObject.send(params);
						
					}
					
				} catch(err) {
					window.alert("CreateNew:"+err);
				}
			}
			
						
			function EditTourType(itemID) {
				try {
					WaitOn();
					var dataSource = "../repository_queries/admin_tourtypes_ gettourtype.php?id=" + itemID;
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
									// The clase of the checkbox
									} else if (document.getElementById(data[0]).type == "checkbox") {
										document.getElementById(data[0]).checked = parseInt(data[1]);
									// The case of everything else.
									} else {
										document.getElementById(data[0]).value = data[1];
									}
								}
								
								// We have to fake an image select.
								for (var k = 1; k <= image_count; k++) {
									if (document.getElementById("path" + k) != null && document.getElementById("iconpath") != null) {
										if (document.getElementById("path" + k).value == document.getElementById("iconpath").value) {
											// Keep scrolling right till we get to our image.
											while (!(first <= k && last >= k)) { //While not between the displayed values
												first++;
												last++;
											}
											UpdatePosition();
											SelectImage(k);
										}
									}
								}
								WaitOff();
								//SelectProducts();
								document.getElementById('create').value = "Update";  // Make it say update.
								document.getElementById('prod-tt-pricing').style.display = "block";
								ViewForm();
							} 
						} 
						XMLHttpRequestObject.send(null);
					}
				} catch(err) {
					window.alert("EditTourType:"+err);
				}
			}
			
			function selectAllOptions(obj) {
	
			}
			
			function Cancel() {
				UpdateList();  // Return to list view.
				Reset();	 // Reset the form.
			}
			
			function Reset() {
				try {
					document.getElementById('tourtypeid').value = "";
					document.getElementById('name').value = "";
					document.getElementById('category').selectedIndex = 0;
					document.getElementById('videowt').value = "";
					document.getElementById('rsvideos').value = "";
					document.getElementById('motionscenes').value = "";
					document.getElementById('photos').value = "";
                    document.getElementById('hdr_photos').value = "";
					document.getElementById('unitprice').value = "";
					document.getElementById('tagline').value = "";
					document.getElementById('description').value = "";
					document.getElementById('demolink').value = "";
					document.getElementById('create').value = "Create";  // Reset the text on the button.
					document.getElementById('is_persistent').checked = 0;
					document.getElementById('is_default').checked = 0;
					document.getElementById('is_preview').checked = 0;
					document.getElementById('is_monthly').checked = 0;
					document.getElementById('is_hidden').checked = 0;
					first = 1;
					last = 3;
					UpdatePosition();
				} catch(err) {
					window.alert("Reset:"+err);
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
					window.alert("PreviewTour:"+err);
				}
			}
			
			function UpdateOrder() {
				try {
					var errorDisplay = document.getElementById('errortext');
					var url = "../repository_queries/admin_tourtypes_updateorder.php";
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
			
			function productsWindow(){
				id = document.getElementById('tourtypeid').value;
				NewWindow('tourtype_products.php?id='+id, '800', '400');
			}
			
			function brokeragesWindow(){
				id = document.getElementById('tourtypeid').value;
				NewWindow('tourtype_brokerages.php?id='+id, '800', '400');
			}
			
			function affiliatesWindow(){
				id = document.getElementById('tourtypeid').value;
				NewWindow('tourtype_affiliates.php?id='+id, '800', '400');
			}
			
			function addPricingWindow(){
				id = document.getElementById('tourtypeid').value;
				NewWindow('additional-pricing/?type=tour&typeID='+id, '950', '600');
			}		
		</script>
		
	</head>
	<body>
		<div id="mainframe" >
			<div id="header" ></div>
			<div id="title" >Tour Types</div>
<!--------------------------------------------------------------------------------------------------------------------------------
----- LIST TOUR TYPES BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
			<div id="list" class="visible" >
				<div id="listtable" >
					<?php require('admin_tourtypes_list_tourtypes.php'); ?>
				</div>
			</div>
			
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- NEW/EDIT FORM BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
			<div id="editform" class="hidden" >
				<input id="tourtypeid" type="hidden" value="" />
				<div class="formrow" >
					<div class="row r_name" >Tour Type Name</div>
					<div class="row r_content" >
						<input id="name" class="input wide" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Tour Category</div>
					<div class="row r_content" >
						<select id="category" class="input mid" >
							<?php
								// List the Tour Categories in some option tags for the select.
								$query = "SELECT category_name FROM tour_category ORDER BY category_name";
								$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
								while($result = mysql_fetch_array($r)){
									echo '
							<option value="' . $result['category_name'] . '">' . $result['category_name'] . '</option>
									';
								}
							?>
						</select>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Motion Walk Thrus</div>
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
						<input id="unitprice" class="input sm" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Tagline</div>
					<div class="row r_content" >
						<input id="tagline" class="input wide" type="text" />
					</div>
				</div>
				<div class="formrow frtall" >
					<div class="row r_name" >Description</div>
					<div class="row r_content r_tall" >
						<textarea id="description" class="input wide tall" ></textarea>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Demo Link</div>
					<div class="row r_content" >
						<input id="demolink" class="input wide" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Blacklist Entire US</div>
					<div class="row r_content" >
						<input id="USBlacklist" class="input" type="checkbox" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Persistence</div>
					<div class="row r_content" >
						<input id="is_persistent" class="input" type="checkbox" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Preview Only</div>
					<div class="row r_content" >
						<input id="is_preview" class="input" type="checkbox" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Monthly Charge</div>
					<div class="row r_content" >
						<input id="is_monthly" class="input" type="checkbox" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Hidden</div>
					<div class="row r_content" >
						<input id="is_hidden" class="input" type="checkbox" />
					</div>
				</div>
                <div class="formrow" >
					<div class="row r_name" >Default</div>
					<div class="row r_content" >
						<input id="is_default" class="input" type="checkbox" />
					</div>
				</div>
                <div id="prod-tt-pricing" style="display:none;">
                    <div class="formrow" >
                        <div class="row r_name" >Products</div>
                        <div class="row r_content" >
                            <!--<select id="products" class="input wide tall" size="5" multiple="multiple">
                                <?php
                                    /*// List the products for the select.
                                    $query = "SELECT productID, productName FROM products WHERE productName IS NOT NULL AND parentProduct IS NULL ORDER BY productName";
                                    $r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
                                    while($result = mysql_fetch_array($r)){
                                        echo '
                                <option value="' . $result['productID'] . '">' . $result['productName'] . '</option>
                                        ';
                                    }*/
                                ?>
                            </select>-->
                            [ <a href="javascript:productsWindow()">Select</a> ]
                        </div>
                    </div>
                    <div class="formrow" >
                        <div class="row r_name" >Brokerages</div>
                        <div class="row r_content" >
                            [ <a href="javascript:brokeragesWindow()">Select</a> ]
                        </div>
                    </div>
                    <div class="formrow" >
                        <div class="row r_name" >Affiliates</div>
                        <div class="row r_content" >
                            [ <a href="javascript:affiliatesWindow()">Select</a> ]
                        </div>
                    </div>
                    <div class="formrow" >
                        <div class="row r_name" >Additional Pricing</div>
                        <div class="row r_content" >
                            [ <a href="javascript:addPricingWindow()">SET</a> ]
                        </div>
                    </div>
                </div>
				<div class="formrow r_touricon" >
					<div class="row r_name" >Icon</div>
					<div class="row r_content r_touricon" >
						<div class="control" onclick="ScrollLeft();" ></div>
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
							<img id="img' . $count . '" class="touricon ' . $class . '" src="' . $dir . '/' . $file . '" onclick="SelectImage(' . Chr(39) . $count . Chr(39) . ')" />
							<input id="path' . $count . '" type="hidden" value="' . $dir . '/' . $file . '" />
										';
									}
								}
							?>

						<div class="control" onclick="ScrollRight();" ></div>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Icon File Name</div>
					<div class="row r_content" >
						<input id="iconpath" class="input wide" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" ></div>
					<div class="row r_content" >
						<input class="" type="button" value="Preview" onclick="PreviewTour();" />
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