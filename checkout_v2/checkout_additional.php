<?php
/**********************************************************************************************
Document: checkout_additonal.php
Creator: Brandon Freeman Modified by Jacob Edmond Kerr
Date: 06-30-11 | Mod Date: 9-22-2011
Purpose: The additional products page
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
	
	require_once ('../teamtools/user_ownership.php');
	require_once ('../repository_inc/write_log.php');
	require_once ('../repository_inc/classes/class.security.php');
	$security = new security();
	
//=======================================================================
// Document
//=======================================================================
	
	session_start();
	
	$userid = '';
	if(isset($_POST['userid'])) {
		$userid = intval($_POST['userid']);
	} elseif (isset($_GET['userid'])) {
		$userid = intval($_GET['userid']);
	}
	
	if(!isset($_SESSION['user_id'])){
		$_SESSION['user_id'] = $userid;
	}
		
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// Set brokerage ID by lookup on users table by userID
	$query = 'SELECT BrokerageID FROM `users` WHERE userID='.$userid;
	$stmt = $dbh->prepare($query);
	$stmt->execute();
	$brokerid = $stmt->fetchAll();
	$brokerid = $brokerid[0]['BrokerageID'];
	$_SESSION['broker_id'] = $brokerid;
	
	// If tourid has been passed to this page lookup and set tour information (this would be used on pages like additional products.
	if(isset($_GET['tourid'])&&!empty($_GET['tourid'])){
		$tourid = $_GET['tourid'];
		$query = 'SELECT tourTypeID, city, zipCode, sqFootage FROM `tours` WHERE tourID='.$tourid;
		$stmt = $dbh->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		$tourtypeid = $result[0]['tourTypeID'];
		$tourcity = $result[0]['city'];
		$tourzip = $result[0]['zipCode'];
		$toursqft = $result[0]['sqFootage'];
	}else{
		$tourid = 0;
		$tourtypeid = 0;
	}
	
	if(isset($_SESSION['broker_id_switch'])&&!empty($_SESSION['broker_id_switch'])){
		$_SESSION['broker_id'] = $_SESSION['broker_id_switch'];
	}
	
	// Preliminary check for access rights.
	if(AccessByUser($userid)) {
//=======================================================================
// CONTENT AREA
//=======================================================================
		
		// Rand to force fresh download of needed inc files
		$randIt = rand(100, 99999999);
		
		$title = 'Additional Products';
		$header = '';
		if(!empty($_GET['jump_to'])){
			$header .= '
			<script language="javascript">
				var jump_to = "'.$_GET['jump_to'].'";
			</script>
			';
		}
		$header .= '
			<link rel="stylesheet" media="all" href="checkout.css?randIt='.$randIt.'">
			<script src="../repository_inc/jquery-1.5.min.js" type="text/javascript"></script> <!--- jQuery --->
			<style type="text/css" media="screen">@import "../repository_css/jquery.autocomplete.css";</style> <!--- For Autocomplete --->
			<script type="text/javascript" src="../repository_inc/jquery.bgiframe.min.js"></script> <!--- For Autocomplete --->
			<script type="text/javascript" src="../repository_inc/jquery.dimensions.js"></script> <!--- For Autocomplete --->
			<script type="text/javascript" src="../repository_inc/jquery.autocomplete.js"></script> <!--- For Autocomplete --->
			<script type="text/javascript" src="../repository_inc/template.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="checkout.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="checkout_step1.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="checkout_step2.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="checkout_step3.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="checkout_step4.js?randIt='.$randIt.'"></script>
			<script>
				var sess_id = "'.session_id().'";
				additional_product = true;
				order.city = "'.$tourcity.'";
				order.zip = "'.$tourzip.'";
			</script>
			<script>
				additional_product = true;
				order.city = "'.$tourcity.'";
				order.zip = "'.$tourzip.'";
				order.sqft = "'.$toursqft.'";
			</script>
		';
		
		if(isset($_GET['popup'])&&$_GET['popup']){
			$header .= '	
				<style>
					#header{
						display:none;
					}
					.floating-steps{
						top:-50px;
						padding-top:50px;
						background-color:white;
					}
					.floating-title{
						top:62px;
					}
					.main_frame{
						padding-top:150px;
					}
					.floating-steps #total_frame{
						top:134px;
					}
				</style>
			';
		}
		
		require_once('../repository_inc/template_header.php');

		if( isset($_GET['select']) && isset($_GET['proceed']) && preg_match("|aps_([0-9]{1,})|",$_GET['select'],$matches)){
			$sel = "aps_" . $matches[1];
?>
<script>
$(document).ready(function(){
	var a = window.setInterval(function(){
		if(document.getElementById('<?php echo $sel;?>')){
			SelectProduct(document.getElementById('<?php echo $sel;?>'));
			window.clearTimeout(a);
			ChangeStep(4);
		}
	},500);
});
</script>
<?php
		}

		echo '<div class="floating-header">';
		include('../repository_inc/top_bar.php');
		echo '</div>'."\n";
		echo '<input type="hidden" id="tourtypeid" name="tourtypeid" value="'.$tourtypeid.'" />'."\n";
		echo '<input type="hidden" id="tourid" name="tourid" value="'.$tourid.'" />'."\n";
		echo '				
			<div class="main_frame" >
				<div class="floating-steps">
					<div id="pf_1" class="progress_frame" style="display: block" >
						<div class="step step_left" >Step 1</div>
						<div class="step step_mid step_inactive" >Step 2</div>
						<div class="step step_mid step_inactive" >Step 3</div>
						<div class="step step_right step_inactive" >Step 4</div>
					</div>
					<div id="pf_2" class="progress_frame" style="display: none" >
						<div class="step step_left" onclick="ChangeStep(1);" >Step 1</div>
						<div class="step step_mid" >Step 2</div>
						<div class="step step_mid step_inactive" >Step 3</div>
						<div class="step step_right step_inactive" >Step 4</div>
					</div>
					<div id="pf_3" class="progress_frame" style="display: none" >
						<div class="step step_left" >Step 1</div>
						<div class="step step_right step_inactive" >Step 2</div>
					</div>
					<div id="pf_4" class="progress_frame" style="display: none" >
						<div class="step step_left" onclick="ChangeStep(3);" >Step 1</div>
						<div class="step step_right" >Step 2</div>
					</div>
					
					<div id="total_frame" style="display: none;">
						Your Total: <span id="order_total" >$0.00</span>
					</div>
				</div>
				
				<div id="step_1" class="step_frame" style="display: block;">
					<div class="floating-title">
						<div class="title" >
							Property Information
						</div>
					</div>
					<div class="form_line" >
						<div class="form_direction" >Address Information</div>
					</div>
					<div class="form_line">
						<div class="input_line w_lg">
							<div class="input_title">Title</div>
							<input id="tour_title" name="tour_title" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" /><div class="input_desc">&nbsp;(Property Headline)</div>
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Descriptive of tour, or property address. (Appears on tour)</div>
							</div>
						</div>
						<div class="required_line w_lg" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_lg" >
							<div class="input_title" >Address</div>
							<input id="tour_address" name="tour_address" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_option" >
								do not display<input id="hide_address" name="hide_address" type="checkbox" value="1" /> 
							</div>
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Do not include state or zip code.</div>
							</div>
						</div>
						<div class="required_line w_lg" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_mid" >
							<div class="input_title" >City</div>
							<input id="tour_city" name="tour_city" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >The city of the tour.</div>
							</div>
						</div>
						<div class="required_line w_mid" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >State</div>
							<input id="tour_state" name="tour_state" maxlength="2" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Two letters, please ...</div>
							</div>
						</div>
						<div class="required_line w_sm" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >Zip</div>
							<input id="tour_zip" name="tour_zip" maxlength="5" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Five digits, please ...</div>
							</div>
						</div>
						<div class="required_line w_sm" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >Beds</div>
							<input id="tour_beds" name="tour_beds" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_option" >
								do not display<input id="hide_beds" name="hide_beds" type="checkbox" value="1" /> 
							</div>
							<div class="input_info" style="display: none;" >
								<div class="info_text" >No commas.</div>
							</div>
						</div>
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >Baths</div>
							<input id="tour_baths" name="tour_baths" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_option" >
								do not display<input id="hide_baths" name="hide_baths" type="checkbox" value="1" /> 
							</div>
							<div class="input_info" style="display: none;" >
								<div class="info_text" >No commas.</div>
							</div>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >Sq. Ft.</div>
							<input id="tour_sqft" name="tour_sqft" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_option" >
								do not display<input id="hide_sqft" name="hide_sqft" type="checkbox" value="1" /> 
							</div>
							<div class="input_info" style="display: none;" >
								<div class="info_text" >No commas.</div>
							</div>
						</div>
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >Price</div>
							<input id="tour_price" name="tour_price" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_option" >
								do not display<input id="hide_price" name="hide_price" type="checkbox" value="1" /> 
							</div>
							<div class="input_info" style="display: none;" >
								<div class="info_text" >No "$" or ",".</div>
							</div>
						</div>
					</div>
					
					<div class="form_line" >
						<div class="form_direction" >MLS Numbers</div>
					</div>
					
					<div id="mls_frame" >
						<div class="form_line" >
							<div class="input_line w_mid" >
								<div class="input_title" >MLS</div>
								<input id="mls_number" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
								<div class="input_option" onclick="AddInput();" >
									+ Add Another
								</div>
								<div class="input_info" style="display: none;" >
									<div class="info_text" >One MLS# per line, please ...</div>
								</div>
							</div>
							<div class="required_line w_mid" >
							<span class="required" >if you do not have your MLS# at this time, please leave blank.</span>
						</div>	
						</div>
					</div>
					<div id="mls_source" style="display: none;" >
						<div class="form_line" >
							<div class="input_line w_mid" >
								<div class="input_title" >MLS</div>
								<input onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
								<div class="input_option" onclick="DelInput(this);" >
									- Remove
								</div>
								<div class="input_info" style="display: none;" >
									<div class="info_text" >One MLS# per line, please ...</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form_line" >
						<div class="form_direction" >Description</div>
					</div>
					<div class="form_line text_field" >
						<div class="input_line w_lg" >
							<div class="input_title" ></div>
							<textarea id="tour_descrip" name="tour_descrip" onkeydown="CharacterCount(this, 2000);" onkeyup="CharacterCount(this, 2000);" /></textarea>
						</div>
						<div class="required_line w_lg" >
							<span id="char_count" class="required" >2000 Characters Left</span>
						</div>
					</div>
					
					<div class="form_line" >
						<div class="form_direction" >Additional Instructions, Preferred Photographer, Etc.</div>
					</div>
					<div class="form_line text_field" >
						<div class="input_line w_lg" >
							<div class="input_title" ></div>
							<textarea id="tour_add" name="tour_add" /></textarea>
						</div>
					</div>
					<div class="form_line" >
						<div class="form_direction" >Co-Listing Agent</div>
					</div>
					<div class="form_line" >
						<div class="input_line w_mid" >
							<div class="input_title" ></div>
							<input id="tour_coagent" name="tour_coagent" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
						</div>
						<div class="required_line w_mid" >
							<span class="required" >Leave blank unless applicable.</span>
						</div>	
					</div>
					<input id="coagent_id" type="hidden" value="" />
					<div class="form_line" >
						<div class="button_new button_blue button_mid" onclick="ValidateStep1();">
							<div class="curve curve_left" ></div>
							<span class="button_caption" >Continue</span>
							<div class="curve curve_right" ></div>
						</div>
					</div>
				</div>
				
				<div id="step_2" class="step_frame" style="display: none" >
					<div class="floating-title">
						<div class="title" >
							Tour Packages
							<div class="button_new button_blue button_sm close" onclick="ValidateStep2();">
								<div class="curve curve_left" ></div>
								<span class="button_caption" >Continue</span>
								<div class="curve curve_right" ></div>
							</div>
						</div>
					</div>
					<div id="tour_packages" >
						
					</div>
				</div>
				
				<div id="step_3" class="step_frame" style="display: none" >
					<div class="floating-title">
						<div class="title" >
							Additional Products
							<div class="button_new button_blue button_sm close" onclick="ChangeStep(4);">
								<div class="curve curve_left" ></div>
								<span class="button_caption" >Continue</span>
								<div class="curve curve_right" ></div>
							</div>
						</div>
					</div>
					<div id="additional_products" >	
						
					</div>
				</div>
				
				<div id="step_4" class="step_frame" style="display: none" >
					<div class="floating-title">
						<div class="title" >
							Confirm Order
						</div>
					</div>
					<div class="order_frame" >
						<div id="checkout_table" >
						
						</div>
					</div>
					<div class="coupon_frame" >
						<div class="code_box" >
							<div class="form_line" >
								<div class="input_line w_sm" >
									<div class="input_title" >Coupon</div>
									<input id="checkout_coupon" name="checkout_coupon" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
								</div>
							</div>
							<div class="button_new button_mgrey button_sm close" onclick="PopulateCheckout();GetOrderTotal();">
								<div class="curve curve_left" ></div>
								<span class="button_caption" >Apply Code</span>
								<div class="curve curve_right" ></div>
							</div>
						</div>	
					</div>
					<div class="clear"></div>
					<div class="credit_frame">
					<div id="form_billing_info" style="display:none;">
		';

		$query = 'SELECT crardId AS id, cardNumber AS last FROM usercreditcards where userid = :userid';
		if($stmt = $dbh->prepare($query)) {
			$stmt->bindParam(':userid', $userid);
			try {
				$stmt->execute();
			} catch (PDOException $e){
				WriteLog("checkout", $e->getMessage());
			}
			// Count rows if not greater then 0 then print nothing for saved CC.
			$count = $stmt->rowCount();
			if($count>0){
?>
						<div class="form_line" >
							<div class="form_direction" >Saved Credit Cards</div>
						</div>
						<div id="saved_cards" >
<?PHP
				while($result = $stmt->fetch()) {
					$cardNumber = $security->decrypt($result['last']);
					echo '
							<div class="form_line" >
								<div class="input_line w_mid" >
									XXXX-XXXX-XXXX-' . substr($cardNumber, -4, 4) . '
									<div class="button_new button_mgrey button_sm close" onclick="GetCard(' . $result['id'] . ');">
										<div class="curve curve_left" ></div>
										<span class="button_caption" >Select</span>
										<div class="curve curve_right" ></div>
									</div>
								</div>
							</div>
					';
				}
?>
						</div>		
<?PHP
			}
		}	
		echo '
						<div class="form_line" >
							<div class="form_direction" >Billing Information</div>
						</div>		
						<div class="form_line" >
						<div class="input_line w_lg" >
							<div class="input_title" >Name</div>
							<input id="credit_name" name="credit_name" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Name as it appears on the card.</div>
							</div>
						</div>
						<div class="required_line w_lg" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_lg" >
							<div class="input_title" >Address</div>
							<input id="credit_address" name="credit_address" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Do not include state or zip code.</div>
							</div>
						</div>
						<div class="required_line w_lg" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_mid" >
							<div class="input_title" >City</div>
							<input id="credit_city" name="credit_city" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Billing city.</div>
							</div>
						</div>
						<div class="required_line w_mid" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >State</div>
							<input id="credit_state" name="credit_state" maxlength="2" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Two letters, please ...</div>
							</div>
						</div>
						<div class="required_line w_sm" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >Zip</div>
							<input id="credit_zip" name="credit_zip" maxlength="5" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Five digits, please ...</div>
							</div>
						</div>
						<div class="required_line w_sm" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_mid" >
							<div class="input_title" >Type</div>
							<select id="credit_type" name="credit_type" >
								<option value="visa" selected >Visa</option>
								<option value="mastercard" >Master Card</option>
								<option value="americanexpress" >American Express</option>
								<option value="check" >Check</option>
							</select>
						</div>
					</div>
					<div class="cc-info">
					<div class="form_line" >
						<div class="input_line w_mid" >
							<div class="input_title" >Number</div>
							<input id="credit_number" name="credit_number" maxlength="16" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Do not include spaces or dashes.</div>
							</div>
						</div>
						<div class="required_line w_mid" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >Month</div>
							<input id="credit_month" name="credit_month" maxlength="2" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Expiration Month</div>
							</div>
						</div>
						<div class="required_line w_sm" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >Year</div>
							<input id="credit_year" name="credit_year" maxlength="4" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Expiration Year</div>
							</div>
						</div>
						<div class="required_line w_sm" >
							<span class="required" >required</span>
						</div>	
					</div>
					</div>
					<div class="tc-info">
						<div class="form_line" >
							<div class="input_line w_mid" >
								<div class="input_title" >Routing #</div>
								<input id="routing_number" name="routing_number" maxlength="9" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
								<div class="input_info" style="display: none;" >
									<div class="info_text" >Numbers only please.</div>
								</div>
							</div>
							<div class="required_line w_mid" >
								<span class="required" >required</span>
							</div>	
						</div>
						<div class="form_line" >
							<div class="input_line w_mid" >
								<div class="input_title" >Account #</div>
								<input id="account_number" name="account_number" maxlength="17" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
								<div class="input_info" style="display: none;" >
									<div class="info_text" >Numbers only please.</div>
								</div>
							</div>
							<div class="required_line w_mid" >
								<span class="required" >required</span>
							</div>	
						</div>
						<div class="form_line" >
							<div class="input_line w_sm" >
								<div class="input_title" >Check #</div>
								<input id="check_number" name="check_number" maxlength="6" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
								<div class="input_info" style="display: none;" >
									<div class="info_text" >Numbers only.</div>
								</div>
							</div>
							<div class="required_line w_sm" >
								<span class="required" >required</span>
							</div>	
						</div>
						<div class="form_line" >
							<div class="input_line w_mid" >
								<div class="input_title" >Acct Type</div>
								<select name="account_type" id="account_type">
                                    <option value="pc">Personal checking</option>
                                    <option value="ps">Personal savings</option>
                                    <option value="bc">Business checking</option>
                                    <option value="bs">Business savings</option>
                                </select>
							</div>
							<div class="required_line w_mid" >
								<span class="required" >required</span>
							</div>
					   </div>
					   <div class="form_line" >
							<div class="input_line w_mid" >
								<div class="input_title" >DL #</div>
								<input id="dl_number" name="dl_number" maxlength="35" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
								<div class="input_info" style="display: none;" >
									<div class="info_text" >Numbers only please.</div>
								</div>
							</div>
							<div class="required_line w_mid" >
								<span class="required" >required</span>
							</div>	
					   </div>
					   <div class="form_line" >
							<div class="input_line w_sm" >
								<div class="input_title" >DL State</div>
									<select name="dl_state" id="dl_state">
                                       <option value=""> ... </option>
                                       <option value="AK"> AK </option>
                                       <option value="AL"> AL </option>
                                       <option value="AR"> AR </option>
                                       <option value="AZ"> AZ </option>
                                       <option value="CA"> CA </option>
                                       <option value="CO"> CO </option>
                                       <option value="CT"> CT </option>
                                       <option value="DC"> DC </option>
                                       <option value="DE"> DE </option>
                                       <option value="FL"> FL </option>
                                       <option value="GA"> GA </option>
                                       <option value="HI"> HI </option>
                                       <option value="IA"> IA </option>
                                       <option value="ID"> ID </option>
                                       <option value="IL"> IL </option>
                                       <option value="IN"> IN </option>
                                       <option value="KS"> KS </option>
                                       <option value="KY"> KY </option>
                                       <option value="LA"> LA </option>
                                       <option value="MA"> MA </option>
                                       <option value="MD"> MD </option>
                                       <option value="ME"> ME </option>
                                       <option value="MI"> MI </option>
                                       <option value="MN"> MN </option>
                                       <option value="MO"> MO </option>
                                       <option value="MS"> MS </option>
                                       <option value="MT"> MT </option>
                                       <option value="NC"> NC </option>
                                       <option value="ND"> ND </option>
                                       <option value="NE"> NE </option>
                                       <option value="NH"> NH </option>
                                       <option value="NJ"> NJ </option>
                                       <option value="NM"> NM </option>
                                       <option value="NV"> NV </option>
                                       <option value="NY"> NY </option>
                                       <option value="OH"> OH </option>
                                       <option value="OK"> OK </option>
                                       <option value="OR"> OR </option>
                                       <option value="PA"> PA </option>
                                       <option value="RI"> RI </option>
                                       <option value="SC"> SC </option>
                                       <option value="SD"> SD </option>
                                       <option value="TN"> TN </option>
                                       <option value="TX"> TX </option>
                                       <option value="UT"> UT </option>
                                       <option value="VA"> VA </option>
                                       <option value="VT"> VT </option>
                                       <option value="WA"> WA </option>
                                       <option value="WI"> WI </option>
                                       <option value="WV"> WV </option>
                                       <option value="WY"> WY </option>
                                	</select>
							</div>
							<div class="required_line w_sm" >
								<span class="required" >required</span>
							</div>
					   </div>
					   <p style="background:url(\'../repository_images/checkout/check.gif\');width:351px;height:190px;display:block;"></p>
					</div>
					</div>
					<p>
					<div class="form_line" >
						<div id="form_save_cc" style="display:none;"><input id="save" name="save" type="checkbox" value="1" /> Save or Update this card in my account <br /></div>
						<div class="grey-divider"></div>
						<input id="accept" name="accept" type="checkbox" value="1" /> Accept the <span class="terms_hl" onclick="Terms();" >Terms and Conditions</span>
					</div>
					</p>
					<div class="grey-divider"></div>
					<br/>';

			  echo '<div class="form_line" >
						<div class="form_line" >
							<div class="button_new button_blue button_mid" onclick="ValidateStep4();">
								<div class="curve curve_left" ></div>
								<span class="button_caption" >Submit Order</span>
								<div class="curve curve_right" ></div>
							</div>
						</div>
					</div>
				</div>	
			</div>
		';
		include('../repository_inc/html/modal.html');
//=======================================================================
// END CONTENT AREA
//=======================================================================
	} else {
		require_once('../repository_inc/template_header.php');
		
		echo '
			<div class="title" >
				You do not have rights to this users tours.
			</div>
		';
	}
	
	require_once('../repository_inc/template_footer.php');

?>
