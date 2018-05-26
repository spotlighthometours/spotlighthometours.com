<?php
/**********************************************************************************************
Document: user_home.php
Creator: Brandon Freeman
Date: 06-30-11
**********************************************************************************************/



//=======================================================================
// Includes
//=======================================================================

	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	require_once ('../repository_inc/write_log.php');
	$security = new security();
	$users = new users($db);
	$mls = new mls();
	$paymentplans = new paymentplans();
	
	//clearCache();
	
	// Authenticate User
	$users->authenticate();
	$users->loadUser();
	
//=======================================================================
// Document
//======================================================================= 
	// SET DEBUG MODE
	$_SESSION['debug'] = false;
	
	$userid = $users->userID;
	
	$paymentplanIDs = $paymentplans->getUserPlans($userid);
	
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	// Set brokerage ID by lookup on users table by userID. Also set DIY user in session
	$query = 'SELECT BrokerageID, mls, mlsProviderID
			FROM `users` WHERE userID='.$userid;
	$stmt = $dbh->prepare($query);
	$stmt->execute();
	$userInfo = $stmt->fetchAll();
	if(isset($userInfo[0]['BrokerageID'])&&!empty($userInfo[0]['BrokerageID'])){
		$brokerid = $userInfo[0]['BrokerageID'];
	}else{
		$brokerid = 0;
	}
	$members = new members(1, 'user', $userid);
	if($members->active()){
		$_SESSION['DIYActive'] = true;
	}else{
		$_SESSION['DIYActive'] = false;
	}
	
	$_SESSION['broker_id'] = $brokerid;
	
	if(isset($_SESSION['broker_id_switch'])&&!empty($_SESSION['broker_id_switch'])){
		$_SESSION['broker_id'] = $_SESSION['broker_id_switch'];
	}

	$tourtypeid = 0;
	$tourid = 0;
	
	// Unset old used credits
	unset($_SESSION['tourTypeCredits']);
	unset($_SESSION['usedCredits']);
	
	// Preliminary check for access rights.
	$access = true;
	if($access) {
//=======================================================================
// CONTENT AREA
//=======================================================================
		// Rand to force fresh download of needed inc files
		$randIt = rand(100, 99999999);
		
		$title = 'Create New Tour';
		$header = '
			<link rel="stylesheet" media="all" href="checkout.css?randIt='.$randIt.'">
			<script src="../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->
			<script src="../repository_inc/jquery-ui-1.10.2.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
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
			<script language = "javascript">
				var sess_id = "'.session_id().'";
				var broker_id = '.$_SESSION['broker_id'].';
				function FocusOnInput() {
					document.getElementById(\'tour_title\').focus();
				};
				window.onload = function() {
				  document.getElementById("tour_title").focus();
				}
			</script>
		';
	    if( isset($_GET['notOrdered']) ){ 
            $header .= "\n<script type='text/javascript' src='checkout_schedulednotordered.js'></script>";
        }
		if(isset($_REQUEST['quickTour'])){
			$header .= '
			<script>
				order.tourtypeid = '.$_REQUEST['quickTour'].';
			';
			if($_REQUEST['quickTour']=="18"){
				$header .= '
					diy_order = true;
				';
			}
			$header .= '
			</script>
			<script type="text/javascript" src="quick_tour.js"></script>
			<style>
				.floating-title{
					top:98px;
					padding-top:10px;
					background-color:white;
				}
			</style>
			';
		}
		
		require_once('../repository_inc/template_header.php');
		echo '<div class="floating-header">';
		include('../repository_inc/top_bar.php');
		echo '</div>';
		echo '<input type="hidden" id="tourtypeid" name="tourtypeid" value="'.$tourtypeid.'" />'."\n";
		echo '<input type="hidden" id="tourid" name="tourid" value="'.$tourid.'" />'."\n";
		echo '				
			<div class="main_frame" >
				<div class="floating-steps">
			';
			echo '
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
							<div class="step step_left" onclick="ChangeStep(1);" >Step 1</div>
							<div class="step step_mid" onclick="ChangeStep(2);" >Step 2</div>
							<div class="step step_mid" >Step 3</div>
							<div class="step step_right step_inactive" >Step 4</div>
						</div>
						<div id="pf_4" class="progress_frame" style="display: none" >
							<div class="step step_left" onclick="ChangeStep(1);" >Step 1</div>
							<div class="step step_mid" onclick="ChangeStep(2);" >Step 2</div>
							<div class="step step_mid" onclick="ChangeStep(3);" >Step 3</div>
							<div class="step step_right" >Step 4</div>
						</div>
			';
		echo '
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
							<div class="input_title" style="font-size:14px; line-height:18px;">Property<br/>Title</div>
							<input id="tour_title" name="tour_title" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" maxlength="49" /><div class="input_desc" style="line-height:18px; margin-left:10px;">&nbsp;(Property Headline)<i class="left" style="font-size:10px;">Example: Fabulous Rambler</i></div>
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Description of tour, or property address. (Appears on tour)</div>
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
						<div class="input_line w_sm" >
							<div class="input_title" >Unit No.</div>
							<input id="tour_unitNumber" name="tour_unitNumber" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Property Unit Number</div>
							</div>
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
							<div class="input_title" >Country</div>
							'.countrySelectMenu("USA", "tour_country", "tour_country", "country", "").'
						</div>
						<div class="required_line w_sm" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" id="state_form_line">
						<div class="input_line w_sm" >
							<div class="input_title" >State</div>
							'.stateSelectMenu(strtoupper($users->state), "tour_state", "tour_state", "stateAbbrName", "Select state...", "USA").'
						</div>
						<div class="required_line w_sm" >
							<span class="required" >required</span>
						</div>	
					</div>
					<div class="form_line" >
						<div class="input_line w_sm" >
							<div class="input_title" >Zip</div>
							<input id="tour_zip" name="tour_zip" maxlength="7" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Zip code...</div>
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
							<div class="input_title" >Acres</div>
							<input id="tour_acres" name="tour_acres" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
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
						<div class="form_direction" >MLS</div>
					</div>
					
					<div id="mls_frame" >
						<div class="form_line left widthAuto" >
							<div class="input_line w_sm" >
								<div class="input_title" >ID#</div>
								<input onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" name="mls[]" value="" />
								<div class="input_info" style="display: none;" >
									<div class="info_text" >One MLS# per line, please ...</div>
								</div>
							</div>
							<div class="required_line w_sm">
								<span class="required" >if you do not have MLS# yet leave blank.</span>
							</div>
						</div>
						<div class="left">&nbsp;&nbsp;&nbsp;&nbsp;</div>
						<div class="form_line left widthAuto" >
							<div class="input_line w_mid" >
								<div class="input_title" >Provider</div>
								'.$mls->providerSelectHTML("mls_provider[]").'
								<div class="input_option" onclick="addMLSInput();" > + Add Another </div>
							</div>
						</div>
						<div class="clear"></div>
					</div>
					<div id="mls_source" style="display: none;" >
						<div>
							<div class="form_line left widthAuto" >
								<div class="input_line w_sm" >
									<div class="input_title" >ID#</div>
									<input onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" name="mls[]" value="" />
									<div class="input_info" style="display: none;" >
										<div class="info_text" >One MLS# per line, please ...</div>
									</div>
								</div>
							</div>
							<div class="left">&nbsp;&nbsp;&nbsp;&nbsp;</div>
							<div class="form_line left widthAuto" >
								<div class="input_line w_mid" >
									<div class="input_title" >Provider</div>
									'.$mls->providerSelectHTML("mls_provider[]").'
									<div class="input_option" onclick="removeMLSInput(this);" > - Remove </div>
								</div>
							</div>
							<div class="clear"></div>
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
		';
			echo '
								<div class="button_new button_blue button_sm close" onclick="ValidateStep2();">
			';
		echo '
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
							Confirm Order';
				$members->userID = $userid;
				$members->membershipID = 10;
				if($members->active()){
					if(strlen($userInfo[0]['mls']) > 0) {
						echo '<font size="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="usePaySold" name="usePaySold" onchange="PopulateCheckout();GetOrderTotal();">Use Pay When Sold Option</input></font>';
					} else {
						// echo '<span>'.$userInfo[0]['mlsProviderID'].'</span>';
						echo '<font size="4">&nbsp;&nbsp;
								<input type="checkbox" disabled="disabled">Use Pay When Sold Option </font>
								<font size="2"><a href="/users/new/my-info.php">(MLS AgentID must be set)</a></input></font>';
					}
				}
				echo '	</div>
					</div>
					<div class="order_frame" >
						<div id="checkout_table" >
						
						</div>
					</div>';
						echo '<div class="payNowDisclaimer" style="display:none;width:350px;height:115px;"><span style="color:red;font-weight:bold;">Notice:</span><br>
							  When using Pay When Sold option, We will not charge your card until confirmation that the listing has sold</div>';

					echo '
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
		if(!$_SESSION['quick_login']){
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
			if(count($paymentplanIDs)>0){
?>
						<div class="form_line" >
							<div class="form_direction" >Payment Plan Options</div>
						</div>
                        <table border="0" cellspacing="0" cellpadding="0" class="list-tbl">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Up front</th>
                                    <th>Months to pay</th>
                                    <th>Fee</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
<?PHP
				foreach($paymentplanIDs as $ppIndex => $paymentPlanID){
					$paymentplans->load($paymentPlanID, false);
?>
                            <tr id="paymentplan_<?PHP echo $paymentPlanID; ?>">
                                <td><?PHP echo $paymentplans->title; ?></td>
                                <td><?PHP if($paymentplans->isUpFrntPercent==1){ echo (floatval($paymentplans->upFront)*100).'%'; }else{ echo '$'.number_format($paymentplans->upFront, 2, '.', ','); } ?></td>
                                <td><?PHP echo $paymentplans->months.' months' ?></td>
                                <td><?PHP if($paymentplans->isIntPercent==1){ echo (floatval($paymentplans->interest)*100).'%'; }else{ echo '$'.number_format($paymentplans->interest, 2, '.', ','); } ?></td>
                                <td class="list-button"><a href="javascript:selectPaymentPlan(<?PHP echo $paymentPlanID; ?>)">Select</a></td>
                            </tr>
<?PHP
				}
?>
							</tbody>
                      	</table>
                        <script>loadListEffects()</script>
<?PHP
			}
			if($count>0){
?>
						<div id="saved-ccs">
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
                        </div>		
<?PHP
			}
		}
		}
		echo '
						<div class="form_line" >
							<div class="form_direction" id="toggleAddUpdate" style="cursor:pointer;">&#9660 Billing Information</div>
						</div>
<span class="paymentAddUpdate">
					<div class="form_line" >
						<div class="input_line w_lg" >
							<div class="input_title" >Name</div>
							<input id="credit_name" name="credit_name" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
							<div class="input_info" style="display: none;" >
								<div class="info_text" >Name as it appears on the card/check.</div>
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
								<option value="discover" selected >Discover</option>
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
						<div id="form_save_cc" style="display:none;"><input id="save" name="save" type="checkbox" value="1" /> Save or Update this card in my account</div>
					</div>
</span>
						<div class="grey-divider"></div>
<p>
<div>
						<input id="accept" name="accept" type="checkbox" value="1" /> Accept the <span class="terms_hl" onclick="Terms();" >Terms and Conditions</span>
						</div>
</p>

					<div class="grey-divider"></div>
                    <p>
                        <div> 
                            <input type="checkbox" id="checklistCheckbox">
                            Send Scheduled Confirmation and Photography Prep Checklist to homeowner
                            <div class="checklist-dropdown-hidden" style="display:none;">
                                <div class="form_line">
                                    <div class="input_line w_sm">
                                        <div class="input_title">Email</div>
                                        <input id="checklistEmail" name="checklistEmail" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
                                        <div class="input_info" style="display: none;">
                                            <div class="info_text">Home owner\'s email address...</div>
                                        </div>  
                                    </div>  
                                </div>  
                            </div>  
                        </div>
                    </p>
					<div class="grey-divider"></div>
					<br/>';

			  echo '<div class="form_line" >
						<div class="form_line" >
							<div class="button_new button_blue button_mid" id="subOrderBtn" onclick="ValidateStep4();">
								<div class="curve curve_left" ></div>
								<span class="button_caption" >Submit Order</span>
								<div class="curve curve_right" ></div>
							</div>
						</div>
					</div>
				</div>	
			</div>
		';
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
