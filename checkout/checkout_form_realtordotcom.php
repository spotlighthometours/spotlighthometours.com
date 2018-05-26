			<!--- Realtor.com Form --->
			<?php
				// Destroy the session variable for realtor.com.
				unset($_SESSION['realtordotcom']);
				
				// Get the product id for realtor.com.
				$query = 'SELECT productID FROM products WHERE productName = "Tour added to Realtor.com" LIMIT 1';
				$rdc = mysql_query($query) or die("Query failed with error:<br />" . mysql_error() . "<br />Query being run:<br />" . $query);
				$result_rdc = mysql_fetch_array($rdc);
			?>
			<style type="text/css">
				.rdcbutton {
					margin-top: 30px;
					width: 117px;
					height: 54px;
					background-repeat: no-repeat;
					cursor: pointer;
					
				}
				.rdcyes {
					float: left;
					margin-left: 175px;
					background-image: url("../repository_images/yes.png");
				}
				.rdcno {
					float: right;
					margin-right: 175px;
					background-image: url("../repository_images/no.png");
				}
				.rdctitle {
					width: 100%;
					margin-top: 50px;
					text-align: center;
					font-size: 30px;
				}
				.rdctext {
					float: left;
					margin-left: 110px;
					width: 400px; 
					color: #666666;
					font-size: 18px;
					margin-top: 30px;
				}
				.rdcremove {
					cursor: pointer;
					text-decoration: underline;
				}
			</style>
			<script language="javascript"> 
			<!-- 
				var showcase_member = false;
				var rdc_id = <?php echo $result_rdc['productID']; ?>;
				
				function rdctoggle(value) { 
					try {
						
						if (value == 1) {
							showcase_member = true;
						} else {
							showcase_member = false;
						}
						
						// Create HTTP Request Object
						var rdcHTTP = false; 
						if (window.XMLHttpRequest) {
							rdcHTTP = new XMLHttpRequest();
						} else if (window.ActiveXObject) {
							rdcHTTP = new ActiveXObject("Microsoft.XMLHTTP");
						}
						
						// Set the destination and parameters
						var url = "checkout_query_realtordotcom.php";
						var params = "member=" + value;
						
						// Send request to change realtor.com session variable.
						if(rdcHTTP) {
							rdcHTTP.open("POST", url, true);
							rdcHTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							rdcHTTP.setRequestHeader("Content-length", params.length);
							rdcHTTP.setRequestHeader("Connection", "close");
				
							rdcHTTP.onreadystatechange = function() { 
								if (rdcHTTP.readyState == 4 && rdcHTTP.status == 200) {
									if (rdcHTTP.responseText.length > 0) {
										window.alert(rdcHTTP.responseText);
									} else {
										AddRdc();
										FormOff('realtordotcom');
									}
									
								}
							}
							rdcHTTP.send(params);
							
						}
					} catch(err) {
						window.alert("rdctoggle: " + err);
					}
				}
				
				function AddRdc() {
					try {
						var qty = 1;
						var price = 0;
						if (!showcase_member) {
							price = parseFloat(document.getElementById(rdc_id + '-productprice').value);
						}
						order_additional_co[rdc_id] = new Array(rdc_id, qty, price);
						UpdateTotalOrder();
					} catch(err) {
						window.alert("AddRdc: " + err);
					}
				}
				
				function DelRdc() {
					
					try {
						order_additional_co[rdc_id] = undefined;
						UpdateTotalOrder();
						FormOff('realtordotcom');
					} catch(err) {
						window.alert("DelRdc: " + err);
					}
				}
			// --> 
			</script>
			<div id="realtordotcom" class="description_main hidden">
				<div class="description_cap" >
					<img class="description_corner left" src="../repository_images/checkout/form-tl.png" />
					<div class="description_cap_spacer description_cap_spacer_top" /></div>
					<img class="description_corner right" src="../repository_images/checkout/form-tr.png" />
				</div>
				<div id="description_main" class="description_frame" >
					<div class="button right" onclick="FormOff('realtordotcom');" >
						<div class="buttoncap graybuttonleft" ></div>
						<div class="buttontext gray" >Close</div>
						<div class="buttoncap graybuttonright" ></div>
					</div>
					<div class="rdctitle" >Are you a showcase member?</div>
					<div id="rdc-yes" class="rdcbutton rdcyes" onclick="rdctoggle(1);" ></div>
					<div id="rdc-no" class="rdcbutton rdcno" onclick="rdctoggle(0);" ></div>
					<div class="rdctext" >
						*Showcase members are current subscribers of Realtor.com's Showcase Listing Enhancements
						<br />
						<br />
						$25 will be charged to non-Showcase members. Member price will be reflected at checkout.
						<br />
						<br />
						If you would like to remove Realtor.com from your order, click <span class="rdcremove" onclick="DelRdc();" >here</span>.
					</div>
				</div>
				<div class="description_cap" >
					<img class="description_corner left" src="../repository_images/checkout/form-bl.png" />
					<div class="description_cap_spacer description_cap_spacer_bottom" ></div>
					<img class="description_corner right" src="../repository_images/checkout/form-br.png" />
				</div>
			</div>
			<!--- END Realtor.com Form --->