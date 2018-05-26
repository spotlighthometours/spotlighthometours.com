<?php
/**********************************************************************************************
Document: memberships.php
Creator: Jacob Edmond Kerr
Date: 05-02-12
Purpose: Checkout for Spotlight Monthly Memberships 
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================
	
	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	
//=======================================================================
// Objects
//=======================================================================

	// Create instances of needed objects
	$users = new users($db);
	$memberships = new memberships();
	$security = new security();
	$billing = new billing();
	
//=======================================================================
// CONTENT AREA
//=======================================================================
	
	// Authenticate user
	$users->authenticate();
	
	// Load membership
	if(!empty($_REQUEST['id'])){
		$memberships->loadMembership($_REQUEST['id']);
	}else{
		die("<h1>Membership ID is required!</h1>");
	}
	
	// Rand to force fresh download of needed inc files
	$randIt = rand(100, 99999999);
		
	// Header
	$title = 'Spotlight Memberships Checkout';
	$header = '
			<link rel="stylesheet" media="all" href="checkout.css?randIt='.$randIt.'">
			<link rel="stylesheet" media="all" href="membership.css?randIt='.$randIt.'">
			<script src="../repository_inc/jquery-1.5.min.js" type="text/javascript"></script> <!--- jQuery --->
			<script type="text/javascript" src="../repository_inc/template.js?randIt='.$randIt.'"></script>
			<script type="text/javascript" src="membership.js?randIt='.$randIt.'"></script>
			<script>
				order.total = "'.$memberships->price.'";
			</script>
	';
	require_once('../repository_inc/template_header.php');
?>

<div class="floating-header">
	<?PHP
	include('../repository_inc/top_bar.php');
?>
</div>
<div class="main_frame" >
<div class="floating-title">
	<div class="title" >Spotlight Membership Checkout</div>
</div>
<div class="order_frame" >
	<div id="checkout_table" > </div>
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
	<div id="form_billing_info">
<?PHP
		$userCC = $billing->getUserCC($users->userID);
		// Count rows if not greater then 0 then print nothing for saved CC.
		$count = count($userCC);
		if($count>0){
?>
		<div class="form_line" >
			<div class="form_direction" >Saved Credit Cards</div>
		</div>
		<div id="saved_cards" >
<?PHP
			foreach($userCC as $row => $result) {
				$cardNumber = $security->decrypt($result['cardNumber']);
				echo '
							<div class="form_line" >
								<div class="input_line w_mid" >
									XXXX-XXXX-XXXX-' . substr($cardNumber, -4, 4) . '
									<div class="button_new button_mgrey button_sm close" onclick="GetCard(' . $result['crardId'] . ');">
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
			<div class="required_line w_lg" > <span class="required" >required</span> </div>
		</div>
		<div class="form_line" >
			<div class="input_line w_lg" >
				<div class="input_title" >Address</div>
				<input id="credit_address" name="credit_address" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
				<div class="input_info" style="display: none;" >
					<div class="info_text" >Do not include state or zip code.</div>
				</div>
			</div>
			<div class="required_line w_lg" > <span class="required" >required</span> </div>
		</div>
		<div class="form_line" >
			<div class="input_line w_mid" >
				<div class="input_title" >City</div>
				<input id="credit_city" name="credit_city" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
				<div class="input_info" style="display: none;" >
					<div class="info_text" >Billing city.</div>
				</div>
			</div>
			<div class="required_line w_mid" > <span class="required" >required</span> </div>
		</div>
		<div class="form_line" >
			<div class="input_line w_sm" >
				<div class="input_title" >State</div>
				<input id="credit_state" name="credit_state" maxlength="2" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
				<div class="input_info" style="display: none;" >
					<div class="info_text" >Two letters, please ...</div>
				</div>
			</div>
			<div class="required_line w_sm" > <span class="required" >required</span> </div>
		</div>
		<div class="form_line" >
			<div class="input_line w_sm" >
				<div class="input_title" >Zip</div>
				<input id="credit_zip" name="credit_zip" maxlength="5" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
				<div class="input_info" style="display: none;" >
					<div class="info_text" >Five digits, please ...</div>
				</div>
			</div>
			<div class="required_line w_sm" > <span class="required" >required</span> </div>
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
			<div class="required_line w_mid" > <span class="required" >required</span> </div>
		</div>
		<div class="form_line" >
			<div class="input_line w_sm" >
				<div class="input_title" >Month</div>
				<input id="credit_month" name="credit_month" maxlength="2" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
				<div class="input_info" style="display: none;" >
					<div class="info_text" >Expiration Month</div>
				</div>
			</div>
			<div class="required_line w_sm" > <span class="required" >required</span> </div>
		</div>
		<div class="form_line" >
			<div class="input_line w_sm" >
				<div class="input_title" >Year</div>
				<input id="credit_year" name="credit_year" maxlength="4" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" />
				<div class="input_info" style="display: none;" >
					<div class="info_text" >Expiration Year</div>
				</div>
			</div>
			<div class="required_line w_sm" > <span class="required" >required</span> </div>
		</div>
	</div>
	<p>
	<div class="form_line" >
		<div id="form_save_cc">
			<input id="save" name="save" type="checkbox" value="1" />
			Save or Update this card in my account <br />
		</div>
		<div class="grey-divider"></div>
		<input id="accept" name="accept" type="checkbox" value="1" />
		Accept the <span class="terms_hl" onclick="Terms();" >Terms and Conditions</span> </div>
	</p>
	<br/>
	<div class="form_line" >
		<div class="form_line" >
			<div class="button_new button_blue button_mid" onclick="ValidateStep4();">
				<div class="curve curve_left" ></div>
				<span class="button_caption" >Submit Order</span>
				<div class="curve curve_right" ></div>
			</div>
		</div>
	</div>
</div>
<?PHP
	include('../repository_inc/html/modal.html');
	require_once('../repository_inc/template_footer.php');
?>