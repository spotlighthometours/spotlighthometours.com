<?php
// MailChimp API
require_once 'inc/MCAPI.class.php';
require_once 'inc/key.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Spotlight Home Tours - MailChimp Interface</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">@import "css/forms.css";</style>
		<script language = "javascript">
			var XMLHttpRequestObject = false; 

			if (window.XMLHttpRequest) {
				XMLHttpRequestObject = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			function RunMC(outputDivID) {
				if (XMLHttpRequestObject) {
					var started = false;
					
					var output = document.getElementById(outputDivID);
					Clear(outputDivID);
					
					var dataSource = "mc_operations.php";
					
					var sbListName = document.getElementById("listname");
					var cbSyncList = document.getElementById("synclist");
					var cbSyncData = document.getElementById("syncdata");
					var txtBatchSize = document.getElementById("batchsize");
					var cbPartial = document.getElementById("partial");
					
					if (sbListName.value.length > 0) {
						if (started == true) {
							dataSource += "&list=" + sbListName.value;
						} else {
							dataSource += "?list=" + sbListName.value;
							started = true;
						}
					}
					
					if (cbSyncList.checked == true) {
						if (started == true) {
							dataSource += "&synclist=t"
						} else {
							dataSource += "?synclist=t"
							started = true;
						}
					}
					
					if (cbSyncData.checked == true) {
						if (started == true) {
							dataSource += "&syncdata=t"
						} else {
							dataSource += "?syncdata=t"
							started = true;
						}
					}
					
					if (cbPartial.checked == true) {
						if (started == true) {
							dataSource += "&partial=t"
						} else {
							dataSource += "?partial=t"
							started = true;
						}
					}
					
					if (txtBatchSize.value.length > 0) {
						if (started == true) {
							dataSource += "&batchsize=" + txtBatchSize.value;
						} else {
							dataSource += "?batchsize=" + txtBatchSize.value;
							started = true;
						}
					}
					
					Toggle("wait");
					
					XMLHttpRequestObject.open("GET", dataSource); 
					XMLHttpRequestObject.onreadystatechange = function() { 
						if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) { 
							output.innerHTML = XMLHttpRequestObject.responseText;
							Toggle("wait");
							Toggle("outputDiv");
						} 
					} 
					XMLHttpRequestObject.send(null); 
				}
			}
			
			function Clear(outputDivID) {
				var output = document.getElementById(outputDivID);
				output.innerHTML = "";
				output.className = "hidden";
			}
			
			function Toggle(itemID) {
			
				var item = document.getElementById(itemID);
				if (item.className == "visible") {
					item.className = "hidden";
				} else {
					item.className = "visible";
				}
			}

		</script>
	</head>
	<body>
		<div class="form_frame">
			<form>
				<div class="title" >MailChimp List Interface</div>
				<label for="listname">List Name:</label>	
				<select name="listname" id="listname">
					<?php
						//Load the available lists.
						$api = new MCAPI($apikey);
						$return = $api->lists();
						if ($api->errorCode){
							echo "<option>NONE - Error?</option>";
						} else {
							foreach ($return['data'] as $list){
								echo "<option value='" . $list['name'] . "'>" . $list['name'] . "</option>";
							}
						}
					?>
				</select>
				<br />
				<label for="synclist">Sync List:</label>
				<input class="checkbox" type="checkbox" name="synclist" id="synclist" />
				<br />
				<label for="syncdata">Sync Data:</label>
				<input class="checkbox" type="checkbox" name="syncdata" id="syncdata" />
				<br />
				<label for="batchsize">Batch Size:</label>
				<input type="text" name="batchsize" id="batchsize" />
				<br />
				<label for="partial">Partial Sync:</label>
				<input class="checkbox" type="checkbox" name="partial" id="partial" />
				<br />
				<input class="button" type="button" value="Run" onclick="RunMC('outputDiv')">
				<input class="button" type="button" value="Clear" onclick="Clear('outputDiv')"> 
				<input class="button" type="button" value="?" onclick="Toggle('instructions')"> 
			</form>
		</div>
		<div id="instructions" class="hidden">
			Instructions:<br />
			List Name - The name of the list you are working on. <br />
			Sync List - Checking this will sync the fields of the list. <br />
			Sync Data - Checking this will sync the data of the list. <br />
			Batch Size - The increment size that we send data to MailChimp (default 1000). <br />
			Partial Sync - This options will only sync a single batch for testing. <br />
		</div>
		<div id="wait" class="hidden">
			<img src='images/ajax-loader.gif' />
		</div>
		<div id="outputDiv" class="hidden"></div>
	</body>
</html>