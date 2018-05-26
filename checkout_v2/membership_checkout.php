<?php 

//=======================================================================
// Includes
//=======================================================================
	
	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	require_once('../repository_inc/classes/class.memberships.php');
	require_once ('../repository_inc/write_log.php');
	$security = new security();
	$users = new users($db);

	//clearCache();
	
	// Authenticate User
	$users->authenticate();

	// SET DEBUG MODE
	$_SESSION['debug'] = false;

	$userid = $users->userID;

	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	

	//CANT USE RIGHT NOW

	// Set brokerage ID by lookup on users table by userID. 
	$query = 'SELECT BrokerageID FROM `users` WHERE userID='.$userid;
	$stmt = $dbh->prepare($query);
	$stmt->execute();
	$userInfo = $stmt->fetch();

	if(isset($userInfo['BrokerageID'])&&!empty($userInfo['BrokerageID'])){
		$brokerid = $userInfo['BrokerageID'];
	}else{
		$brokerid = 0;
	}


	$_SESSION['broker_id'] = $brokerid;
	
	if(isset($_SESSION['broker_id_switch'])&&!empty($_SESSION['broker_id_switch'])){
		$_SESSION['broker_id'] = $_SESSION['broker_id_switch'];
		unset($_SESSION['broker_id_switch']);
	}	
	

	// Unset old used credits
	unset( $_SESSION['tourTypeCredits'] );
	unset($_SESSION['usedCredits']);
	$access = true;
	// Preliminary check for access rights.
	if($access) {	
?>
<?php
//=======================================================================
// CONTENT AREA
//=======================================================================
		// Rand to force fresh download of needed inc files
		$randIt = rand(100, 99999999);
		  
		$title = 'membership';
		$header = '
			<link rel="stylesheet" media="all" href="checkout.css?randIt='.$randIt.'">
			<link rel="stylesheet" media="all" href="membership_checkout.css?randIt='.$randIt.'">
			<script src="../repository_inc/jquery-1.5.min.js" type="text/javascript"></script> <!--- jQuery --->
			<style type="text/css" media="screen">@import "../repository_css/jquery.autocomplete.css";</style> <!--- For Autocomplete --->
			<script type="text/javascript" src="../repository_inc/jquery.bgiframe.min.js"></script> <!--- For Autocomplete --->
			<script type="text/javascript" src="../repository_inc/jquery.dimensions.js"></script> <!--- For Autocomplete --->
			<script type="text/javascript" src="../repository_inc/jquery.autocomplete.js"></script> <!--- For Autocomplete --->
			<script type="text/javascript" src="../repository_inc/template.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="membership_checkout.js?randIt='.$randIt.'"></script>
		';

?>
<?php



			$membership = new memberships();

			$membership->loadMembership($_GET['id']);

            $price = $membership->getMembershipAdjustedPrice($_GET['id'], $userid);
            
		//}

			$header .= '
                <script>
                    window.onload = function(){
                    	GetWaitScreen();
                    	GetCompleteScreen();
                        order.address = "Tour Demo";
                        order.state = "UT";
                        order.city = "Salt Lake City";
                        order.zip = "84106";
                        order.price = "'.$price.'";
                        order.total = "'.$price.'";
                        order.f_mb_total = "'.$price.'";
                        order.f_ub_total = "'.$price.'";
                        order.membershiptype = "'.$membership->name .'";
						order.membership
                    }
                </script>

                <style>
                    .floating-title{
                        top:98px;
                        padding-top:10px;
                        background-color:white;
                    }
                </style>';
		
		require_once('../repository_inc/template_header.php');
?>
		<div class="floating-header">
		<?php include('../repository_inc/top_bar.php'); ?>
		</div>

			<div class="main_frame" >
				<div class="floating-steps">

				</div>

			    <div id="step_4" class="step_frame" style="display: block" >
					<div class="floating-title">
						<div class="title" >
							Confirm Order
						</div>
					</div>
					<div class="order_frame" >
						<div id="checkout_table" >
							<div class="pr_frame" >
                                <table class="pricing" cellpadding="0" cellspacing="0" border="0" nowrap>
                                    <tr>
                                        <th rowspan="2" >Items</th>
                                        <th rowspan="2" >Qty</th>
                                        <th rowspan="2" >Coupon</th>
                                        <th rowspan="2" >Std. Price</th>
                                        <th rowspan="2" >Adj. Price</th>
                                        <th colspan="2" class="h_monthly">Monthly</th>
                                    </tr>
                                    <tr>
                                        <th class="h_monthly" >Unit</th>
                                        <th class="h_monthly" >Total</th>
                                    </tr>
                                    <tr>
                                        <td><?php echo  $membership->name; ?></td>
                                        <td>1</td>
                                        <td class="membership_coupon"></td>
                                        <td class="dol" >$ <?php echo $price; ?></td>
                                        <td class="dol" >$ <?php echo $price; ?></td>
                                        <td class="dol l_monthly" ><?php echo $price; ?></td>
                                        <td class="dol l_monthly" ><?php echo $price; ?></td>
                                   </tr>
                                     <tr>
                                        <td colspan="4" rowspan="4" class="blank" ></td>
                                        <th >Tax</th>
                                        <td colspan="2" class="dol l_monthly" >$0.00</td>
                                     </tr>
                                    <tr>
                                        <th >Subtotal</th>
                                        <td colspan="2" class="dol l_monthly" id="subtotal">$ <?php echo $price; ?></td>
                                    </tr>
                                    <tr>
                                        <th >Total</th>
                                        <td colspan="2" class="dol l_monthly l_monthly_tot" id="total" >$ <?php echo $price; ?></td>
                                    </tr>
                                </table>
                                <input id="order_total" type="hidden"  />
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
							<div class="button_new button_mgrey button_sm close " id="coupon" onclick="Coupon();">
								<div class="curve curve_left" ></div>
								<span class="button_caption apply-code" >Apply Code</span><?php //call apply-coupon click with jquery in socialhub_checkout.js; ?>
								<div class="curve curve_right" ></div>
							</div>
						</div>
					</div>
					</div>
					
					<div class="clear"></div>
					<div class="credit_frame">
					<div id="form_billing_info" style="display:block;">
		<?php
		if(!$_SESSION['quick_login'])
		{
			$query = 'SELECT crardId AS id, cardNumber AS last FROM usercreditcards where userid = :userid';
			if($stmt = $dbh->prepare($query)) 
			{
				$stmt->bindParam(':userid', $userid);
				try {
					$stmt->execute();
				} catch (PDOException $e){
					WriteLog("checkout", $e->getMessage());
				}
				// Count rows if not greater then 0 then print nothing for saved CC.
				$count = $stmt->rowCount();
				if($count>0)
				{
?>
					<div class="form_line" >
						<div class="form_direction" >Saved Credit Cards</div>
					</div>
					<div id="saved_cards" >
<?php
						while($result = $stmt->fetch()) :
							$cardNumber = $security->decrypt($result['last']);
?>

								<div class="form_line" >
									<div class="input_line w_mid" >
										XXXX-XXXX-XXXX-<?php echo substr($cardNumber, -4, 4); ?>
										<div class="button_new button_mgrey button_sm close" onclick="GetCard('<?php echo $result['id']; ?>');">
											<div class="curve curve_left" ></div>
											<span class="button_caption" >Select</span>
											<div class="curve curve_right" ></div>
										</div>
									</div>
								</div>
<?php
						endwhile;
?>
					</div>		
<?PHP
				}
			}
		}
?>
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
							</select>
						</div>
					</div>
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
					<p>
					<div class="form_line" >
						<div id="form_save_cc" style="display:block;"><input id="save" name="save" type="checkbox" value="1" /> Save or Update this card in my account <br /></div>
						<div class="grey-divider"></div>
						<input id="accept" name="accept" type="checkbox" value="1" /> Accept the <span class="terms_hl" onclick="Terms();" >Terms and Conditions</span>
					</div>
					</p>
					<br/>
					<div class="form_line" >
						<div class="form_line" >
							<div class="button_new button_blue button_mid" id="subOrderBtn" onclick="ValidateMembership($_GET['id']);">
								<div class="curve curve_left" ></div>
								<span class="button_caption" >Submit Order</span>
								<div class="curve curve_right" ></div>
							</div>
						</div>
					</div>
				</div>	
			</div>
<?php
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
	include('../repository_inc/html/modal.html');
		
		
?>