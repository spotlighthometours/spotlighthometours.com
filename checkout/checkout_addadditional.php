<?php
/**********************************************************************************************
Document: checkout_additional.php
Creator: Brandon Freeman
Date: 02-01-11
Purpose: Brand new check-out system for 2011.  Now with 50% more awesome!
**********************************************************************************************/

//=======================================================================
// Header stuff for clearing cache - Good for AJAX and IE
//=======================================================================

header("Expires: Sun, 19 Nov 1978 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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
	require_once ('../repository_inc/clean_query.php');

//=======================================================================
// Document
//=======================================================================

	// Start the session
	session_start();

	$debug = false;

	// Require User Login
	if (!$debug) {
		if (!isset($_SESSION['user_id'])) {
			header('Location: /login/');
			ob_flush();
		} 
	}

	//Set some debug values
	if ($debug) {
		$_SESSION['user_id'] = 2203;
		$_SESSION['broker_id'] = 68;
		$_SESSION['express_user'] = false;
		$_SESSION['state'] = 'UT';
		$_SESSION['first_name'] = "Bret";
		$_SESSION['last_name'] = "Peterson";
	}

	// Get the tour id from either post or get
	if (isset($_POST['tourid'])) {
		$tourid = CleanQuery($_POST['tourid']);
	} elseif (isset($_GET['tourid'])) {
		$tourid = CleanQuery($_GET['tourid']);
	}
	// Pull the tour information from the database.
	if (isset($tourid)) {
		$query = "SELECT * FROM tours WHERE tourID = '" . $tourid . "' AND userID = '" . $_SESSION['user_id'] . "' LIMIT 1";
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error());
		$result = mysql_fetch_array($r);
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Spotlight Home Tours - Add Additional Products</title>
		<link REL="SHORTCUT ICON" HREF="../repository_images/icon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<script type="text/javascript" src="../repository_inc/error_recorder.js"></script> <!--- Error Recording --->
		
		<style type="text/css" media="screen">@import "../repository_css/template.css";</style>
		<style type="text/css" media="screen">@import "checkout.css";</style>
		<style type="text/css" media="screen">@import "../repository_css/spinner.css";</style>
		<style type="text/css" media="screen">@import "../repository_css/forms.css";</style>
				
		<script src="../repository_inc/jquery-1.5.min.js" type="text/javascript"></script> <!--- jQuery --->
		
		<style type="text/css" media="screen">@import "../repository_css/jquery.autocomplete.css";</style> <!--- For Autocomplete --->
		<script type="text/javascript" src="../repository_inc/jquery.bgiframe.min.js"></script> <!--- For Autocomplete --->
		<script type="text/javascript" src="../repository_inc/jquery.dimensions.js"></script> <!--- For Autocomplete --->
		<script type="text/javascript" src="../repository_inc/jquery.autocomplete.js"></script> <!--- For Autocomplete --->
		<?PHP
			if(!empty($_GET['jump_to'])){
		?>
        <script language="javascript">
			var jump_to = '<?php echo $_GET['jump_to']; ?>';
		</script>
		<?PHP	
			}
		?>
		<script language="javascript" src="checkout.js"></script>
		<script language="javascript" src="checkout_jquery.js"></script>
		
		<style type="text/css" media="screen">@import "checkout_virtual_staging.css";</style> <!--- Virtual Staging --->
		<script language="javascript" src="checkout_virtual_staging.js"></script><!--- Virtual Staging --->
		
		<!--- Order Variables --->
		<script language = "javascript">
			try {
				var additionalOnly = true;
				<?php
				if ($debug) {
					echo 'var debug = true;' . Chr(10);
				} else {
					echo 'var debug = false;' . Chr(10);
				}
				if (isset($result['tourTypeID'])) {
					echo 'var order_tourid = ' . $result['tourTypeID'] . ';' . Chr(10);
					echo 'var order_tourprice = 0;' . Chr(10);
				} else {
					echo 'var order_tourid = -1;' . Chr(10);
					echo 'var order_tourprice = 0;' . Chr(10);
				}
				
				echo 'var order_additional = new Array();' . Chr(10);
				echo 'var order_additional_co = new Array();' . Chr(10);
				echo 'var order_vs = new Array();' . Chr(10);
				
				//Dynamically get the broker ID.
				if (isset($_SESSION['broker_id'])) {
					echo "var brokerid = " . $_SESSION['broker_id'];
				} else {
					echo "var brokerid = 0";
				}
				
				if(!$debug) {
					echo '
					$(document).ready(function() {
						PrepStep3();
					});
					' . Chr(10);
				}
				?>
				
				var checkUnload = <?php if($debug){echo "false";} else {echo "true";} ?>; //Initialize to check for unload
				var HTTP = false; //Ajax http request object
				//Create the http request object
				if (window.XMLHttpRequest) {
					HTTP = new XMLHttpRequest();
				} else if (window.ActiveXObject) {
					HTTP = new ActiveXObject("Microsoft.XMLHTTP");
				}
				
				//GetStates();  //This begins a chain of autofills.
			
			} catch(err) {
				window.alert("OnLoad: " + err);
			}
			
			<?PHP
				if(isset($_GET['singleProduct'])&&isset($_GET['singleProduct'])){
					echo 'var autoSetItem = true;'."\n";
					echo 'var autoSetItemID = '. $_GET['productID'].';'."\n";
					echo 'var autoSetItemQ = '. $_GET['quantity'].';'."\n";
				}
			?>
			
		</script>
		
	</head>
	<body onbeforeunload="return RunOnBeforeUnload();" onmousemove="MouseMove(event);" onmouseup="MouseUp(event);">
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- START THE DOC BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
		
		<div id="mainframe" >
			
			<!--- Main Area --->
			<div id="mainslice" >
				
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- MAGIC FLOATING TITLE BAR
----- ------------------------------------------------------------------------------------------------------------------------- --->
				<div id="floatingbar" class="floatingbar hidden" >
					<!--- Header Bar --->
					<?php ($_GET['popup'])?'':include('../repository_inc/top_bar.php'); ?>
					
					<div id="floatstep1" class="hidden" ></div>
					
					<div id="floatstep2" class="hidden" >
						<div id="floatingtotal2" class="checkoutamt" >$0.00</div>
					</div>
					
					<div id="floatstep3" class="hidden" >
						<!--- Progress Bar --->
                        <div class="progress" >
							<div class="progressleft" >Step 1...</div>
							<div class="progressright progressfaded" >Step 2...</div>
						</div>

						<!--- Title Bar --->
						<div class="title floatingtitle" >
							<div class="titletext" >Additional Products</div>
								
							<div class="button right buttontop" onclick="PrepStep4();" >
								<div class="buttoncap greenbuttonleft" ></div>
								<div class="buttontext green buttonco" >Checkout</div>
								<div class="buttoncap greenbuttonright" ></div>
							</div>
							
							<div id="floatingtotal3" class="checkoutamt" >$0.00</div>
							<div class="checkouttext" >Your Total: </div>
						</div>
					</div>
					
					<div id="floatstep4" class="hidden" >
						<div id="floatingtotal4" class="checkoutamt" >$0.00</div>
					</div>
					
				</div>

<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- DOCUMENT
----- ------------------------------------------------------------------------------------------------------------------------- --->
				
				<!--- Shadows --->
				<div id="leftshadow" ></div>
				<div id="rightshadow" ></div>
				
				<!--- Header Bar --->
				<?php ($_GET['popup'])?'':include('../repository_inc/top_bar.php'); ?>
				
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- STEP 1 BLOCK - TOUR INFORMATION
----- ------------------------------------------------------------------------------------------------------------------------- --->				
				<div id="step1" class="visible" >
					
					<!--- Title Bar --->
					<div class="title" >
						<div class="titletext" >Tour Information</div>
						<?php
						// Only show the continue button if there is a valid tour to modify.
						if (isset($result['tourID'])) {
							echo '
						<div class="button right buttontop" onclick="PrepStep3();" >
							<div class="buttoncap greenbuttonleft" ></div>
							<div class="buttontext green" >Continue</div>
							<div class="buttoncap greenbuttonright" ></div>
						</div>
							';
						}
						?>
					</div>
					<div>
						<?php
						if (isset($_SESSION['user_id'])) {
							echo 'User ID: ' . $_SESSION['user_id'] . '<br />';
						} else {
							echo 'The specified tour doesnt belong to you.<br />';
						}
						if (isset($result['tourID'])) {
							//tourID, userID, tourTypeID, title, address, city, state, zipCode, 
							//listPrice, sqFootage, bedrooms, bathrooms, mls, description, additionalInstructions, 
							//createdOn, modifiedOn, couserID
							echo 'Tour ID: ' . $result['tourID'] . '<br />';
							echo '<input id="prevtourid" type="hidden" value="' . $result['tourID'] . '" />';
							//echo 'User ID: ' . $result['userID'] . '<br />';
							echo 'Tour Type ID: ' . $result['tourTypeID'] . '<br />';
							echo 'Title: ' . $result['title'] . '<br />';
							echo 'Address: ' . $result['address'] . ' ' . $result['city'] . ' ' . $result['state'] . ' ' . $result['zipCode'] . '<br />';
							echo '<input id="city" type="hidden" value="' . $result['city'] . '" />';
							echo '<input id="zip" type="hidden" value="' . $result['zipCode'] . '" />';
							echo 'List Price: ' . $result['listPrice'] . '<br />';
							echo 'Square Footage: ' . $result['sqFootage'] . '<br />';
							echo 'Bedrooms: ' . $result['bedrooms'] . '<br />';
							echo 'Bathrooms: ' . $result['bathrooms'] . '<br />';
							echo 'MLS #: ' . $result['mls'] . '<br />';
							echo 'Description: ' . $result['description'] . '<br />';
							echo 'Additional Instructions: ' . $result['additionalInstructions'] . '<br />';
							echo 'Created On: ' . $result['createdOn'] . '<br />';
							echo 'Modified On: ' . $result['modifiedOn'] . '<br />';
							echo 'Co-Listing Broker: ' . $result['couserID'] . '<br />';
						} else {
							echo 'The specified tour doesnt belong to you.<br />';
						}
						//echo 'Query: ' . $query . '<br />';
						
						?>
					</div>
				</div>
				
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- STEP 2 BLOCK - SELECT TOUR TYPE
----- ------------------------------------------------------------------------------------------------------------------------- --->				

				<!--- SKIPPED! --->
				<div id="step2" class="hidden" >
						<div id="step2total" class="checkoutamt hidden" >$0.00</div>
						<div class="checkouttext hidden" >Your Total: </div>	
				</div>
				
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- STEP 3 BLOCK - ADDITIONAL PRODUCTS
----- ------------------------------------------------------------------------------------------------------------------------- --->	
				<div id="step3" class="hidden" >
					<?PHP if(!$_GET['popup']){ ?>
                    <!--- Progress Bar --->
                        <div class="progress" >
							<div class="progressleft" >Step 1...</div>
							<div class="progressright progressfaded" >Step 2...</div>
						</div>
				
					<!--- Title Bar --->
					<div class="title" >
						<div id="title3" class="titletext" >Additional Products</div>
							
						<div class="button right buttontop" onclick="PrepStep4();" >
							<div class="buttoncap greenbuttonleft" ></div>
							<div class="buttontext green buttonco" >Check Out</div>
							<div class="buttoncap greenbuttonright" ></div>
						</div>
						
						<div id="step3total" class="checkoutamt" >$0.00</div>
						<div class="checkouttext" >Your Total: </div>
					</div>	
					<?PHP }else{ ?>
                    	<div id="step3total" style="display:none;"></div>
                    	<p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                    <?PHP } ?>
					<!--- Additional Products --->
					<div id="additionalproducts"></div>
					
				</div>
				
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- STEP 4 BLOCK - Confirm Your Order
----- ------------------------------------------------------------------------------------------------------------------------- --->	
				<div id="step4" class="hidden" >
					<!--- Progress Bar --->
					<?PHP
						if(!isset($_GET['singleProduct'])){
					?>
                    <div id="progress4" class="progress" >
						<div class="progressleft" onclick="ToggleStep(3);" >Step 1...</div>
						<div class="progressright" >Step 2...</div>
					</div>
					<?PHP
						}
					?>
					<!--- Title Bar --->
					<div class="title" >
						<div id="title4" class="titletext" >Confirm Your Order</div>
						<div class="checkout hidden" >
							<div id="step4total" class="checkoutamt" >$0.00</div>
							<div class="checkouttext" >Your Total: </div>
						</div>
					</div>
					
					<!--- Checkout Table --->
					<div id="checkoutarea" ></div>	
					
					<!--- Coupon Code Box --->
					<div class="ccframe" style="margin-top: 5px;" >
							<input id="couponcode" class="defaultText input mid ccspacer" style="margin-top: 1px; margin-left: 420px;" title="Coupon Code" type="text" />
							<div class="button left" onclick="PrepStep4();" >
								<div class="buttoncap bluebuttonleft" ></div>
								<div class="buttontext blue buttonco" >Apply</div>
								<div class="buttoncap bluebuttonright" ></div>
							</div>
					</div><br />
					
					<!--- saved cards block --->
					<div id="savedcards" class="visible" >
					
					<?php
					$query = 'SELECT * FROM usercreditcards WHERE userid = "' . $_SESSION['user_id'] . '"';
					$r = mysql_query($query) or die("Query failed with error:<br />" . mysql_error() . "<br />Query being run:<br />" . $query);
					while($result = mysql_fetch_array($r)){
						echo '
							<div class="ccframe" >
								<div class="cclisting" style="width: 400px;" >
									<div class="ccleftgray left" ></div>
									<div class="cctext left" >XXXX-XXXX-XXXX-' . substr($result['cardNumber'], -4) . '</div>
									<div class="button left" onclick="FillCCInfo(' . $result['crardId'] . ');" >
										<div class="buttoncap bluebuttonleft ccgraybg" ></div>
										<div class="buttontext blue ccbuttontext" onclick="" >Use this card</div>
										<!---<div class="buttontext blue ccbuttontext" onclick="Toggle(' . Chr(39) . 'showbilling' . Chr(39) . '); Toggle(' . Chr(39) . 'billingdetails' . Chr(39) . '); Toggle(' . Chr(39) . 'terms' . Chr(39) . ');" >Use this card</div>--->
										<div class="buttoncap bluebuttonright" ></div>
									</div>
								</div>
							</div><br />
						';
					}
					?>
							
						
						<!---<div class="ccframe" >
							<div class="cceditbox">Edit my cards on file</div>
						</div><br />--->
					</div>
					
					<!--- show billing toggle switch --->
					<div id="showbilling" class="hidden" >
						<div class="ccframe ccbillingtitle ccbillingspacer cctermshighlight" onclick="Toggle('showbilling'); Toggle('billingdetails'); Toggle('terms');" >Show Billing Details</div><br />
					</div>
					
					<!--- billing details block --->
					<div id="billingdetails" class="visible" >
						<div class="ccframe ccbillingtitle ccbillingspacer" >Billing Details</div><br />
						<div class="ccframe" >
							<input id="ccname" class="defaultText input wide ccspacer" title="Name" type="text" />
						</div><br />
						<div class="ccframe" >
							<input id="ccaddress" class="defaultText input wide ccspacer" title="Billing Address" type="text" />
						</div><br />
						<div class="ccframe" >
							<input id="cccity" class="defaultText input mid ccspacer" title="City" type="text" />
							<input id="ccstate" maxlength="2" class="defaultText input small ccspacer" title="State" type="text" />
							<input id="cczip" class="defaultText input mid ccspacer" title="Zip Code" type="text" />
						</div><br />
						<div class="ccframe ccbillingspacer" >
							<select id="cctype" class="defaultText input mid ccspacer" title="Card Type" >
								<option value="visa" >Visa</option>
								<option value="mastercard" >Master Card</option>
								<option value="americanexpress" >American Express</option>
							</select>
						</div><br />
						<div class="ccframe" >
							<input id="ccnum" class="defaultText input mid ccspacer" title="Card Number" type="text" />
							<input id="ccmonth" maxlength="2" class="defaultText input small ccspacer" title="MM" type="text" />
							<input id="ccyear" maxlength="4" class="defaultText input small ccspacer" title="YYYY" type="text" />
						</div><br />
						<div class="ccframe" >
							<input id="ccsave" class="cctermstext left" type="checkbox" title="HidePrice" id="HidePrice" />
							<div class="cctermstext left" >Save this card in my account</span></div>
						</div><br />
						<div class="ccframe" >
							<input id="ccagree" class="cctermstext left" type="checkbox" title="HidePrice" id="HidePrice" />
							<div class="cctermstext left" >I agree to the <span class="cctermshighlight" onclick="TaC();" >terms and conditions</span></div>
						</div><br />
						<div class="ccframe" >
							<div class="button left buttontop" onclick="SubmitOrder();" >
								<div class="buttoncap greenbuttonleft" ></div>
								<div class="buttontext green buttonco" >Submit</div>
								<div class="buttoncap greenbuttonright" ></div>
							</div>
						</div><br />
					</div>
				</div>

<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- ADDITIONAL FORM BLOCK
----- ------------------------------------------------------------------------------------------------------------------------- --->
				
				<!--- Area for additional forms that appear with the faded backdrop. --->
				<div id="backdrop" class="hidden" ></div>
				<div id="display" class="hidden" >
					
					<!--- Dynamic Forms From DB --->
					<?php 
					$query = 'SELECT checkoutFormFile FROM products WHERE checkoutFormFile IS NOT NULL';
					$r = mysql_query($query) or die("Query failed with error:<br />" . mysql_error() . "<br />Query being run:<br />" . $query);
					while($result = mysql_fetch_array($r)){
						if(file_exists($result['checkoutFormFile'])) {
							require_once($result['checkoutFormFile']);
						}
					}
					
					?>
					
					<div id="information" class="additionalform hidden">
						<div class="big_n_white" >Information?</div>
						<div class="big_n_white big_n_white_border" onclick="FormOff('information');" >Close</div>
					</div>
					
					<div id="formDescription" class="description_main hidden">
						<div class="description_cap" >
							<img class="description_corner left" src="../repository_images/checkout/form-tl.png" />
							<div class="description_cap_spacer description_cap_spacer_top" /></div>
							<img class="description_corner right" src="../repository_images/checkout/form-tr.png" />
						</div>
						<div id="description_main" class="description_frame" >
							<<div id="formdescriptionclose" class="button right visible" onclick="FormOff('formDescription');" >
								<div class="buttoncap graybuttonleft" ></div>
								<div class="buttontext gray" >Close</div>
								<div class="buttoncap graybuttonright" ></div>
							</div>
							<div id="description_title" class="description_title" >Default Description</div>
							<div id="description_text" class="description_text" >
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. <br />
								Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. <br />
								Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br />
								Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. <br />
							</div>
						</div>
						<div class="description_cap" >
							<img class="description_corner left" src="../repository_images/checkout/form-bl.png" />
							<div class="description_cap_spacer description_cap_spacer_bottom" ></div>
							<img class="description_corner right" src="../repository_images/checkout/form-br.png" />
						</div>
					</div>
					
					<div id="formError" class="description_main hidden">
						<div class="description_cap" >
							<img class="description_corner left" src="../repository_images/checkout/form-tl.png" />
							<div class="description_cap_spacer description_cap_spacer_top" /></div>
							<img class="description_corner right" src="../repository_images/checkout/form-tr.png" />
						</div>
						<div id="description_main" class="description_frame" >
							<div class="button right" onclick="FormOff('formError');" >
								<div class="buttoncap graybuttonleft" ></div>
								<div class="buttontext gray" >Close</div>
								<div class="buttoncap graybuttonright" ></div>
							</div>
							<div class="description_title" >
								<img src="../repository_images/alert_blt.png" />
								A Problem ...
							</div>
							<div id="errortext" class="description_text" >
								Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. <br />
								Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. <br />
								Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. <br />
								Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. <br />
							</div>
						</div>
						<div class="description_cap" >
							<img class="description_corner left" src="../repository_images/checkout/form-bl.png" />
							<div class="description_cap_spacer description_cap_spacer_bottom" ></div>
							<img class="description_corner right" src="../repository_images/checkout/form-br.png" />
						</div>
					</div>
					
					<div id="wait" class="description_main hidden">
						<div class="description_cap" >
							<img class="description_corner left" src="../repository_images/checkout/form-tl.png" />
							<div class="description_cap_spacer description_cap_spacer_top" /></div>
							<img class="description_corner right" src="../repository_images/checkout/form-tr.png" />
						</div>
						<div id="description_main" class="description_frame" >
							<div class="description_title" >One moment please ...</div>
							<div id="spinner_nowebkit"></div>
						</div>
						<div class="description_cap" >
							<img class="description_corner left" src="../repository_images/checkout/form-bl.png" />
							<div class="description_cap_spacer description_cap_spacer_bottom" ></div>
							<img class="description_corner right" src="../repository_images/checkout/form-br.png" />
						</div>
					</div>
					
				</div>
				
<!--- ------------------------------------------------------------------------------------------------------------------------- ----
----- END THE DOC BLOCK :P
----- ------------------------------------------------------------------------------------------------------------------------- --->
				<div id="footer" ></div>
			</div>
		</div>
	</body>
</html>
