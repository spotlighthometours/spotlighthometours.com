<?php
/**********************************************************************************************
Document: checkout_coldwell_chooser.php
Creator: Brandon Freeman
Date: 07-26-11
Purpose: Allows what brokerage to order tours under.  Its pricing related.
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
	
	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	
//=======================================================================
// Document
//=======================================================================
	
	$users = new users($db);
	
	// Authenticate User
	$users->authenticate();
	
	//session_start();
	
	$userid = '';
	if(isset($_POST['userid'])) {
		$userid = intval($_POST['userid']);
	} elseif (isset($_GET['userid'])) {
		$userid = intval($_GET['userid']);
	}
	
	if(isset($_POST['switch'])) {
		$switch = intval($_POST['switch']);
	} elseif (isset($_GET['switch'])) {
		$switch = intval($_GET['switch']);
	}
	
	$access = true;
	// Preliminary check for access rights.
	if($access) {
//=======================================================================
// CONTENT AREA
//=======================================================================
		$location = '/checkout_v2/checkout.php?userid=' . $userid;
		if(isset($_REQUEST['session_id'])){
			$location .= '&session_id='.$_REQUEST['session_id'];
		}
		
		if(isset($switch)) {
			$_SESSION['broker_id_switch'] = $switch;
			header('Location: ' . $location);
			ob_flush();		
		}
		
		
		$title = 'Coldwell Chooser';
		$randIt = rand(999,999999);
		$header = '
			<link rel="stylesheet" media="all" href="checkout_coldwell_chooser.css?randIt='.$randIt.'">
			<style>
				.disabled{
					cursor:default !important;
					-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
					filter: alpha(opacity=50);
					-moz-opacity:0.5;
					-khtml-opacity: 0.5;
					opacity: 0.5;
				}
			</style>
		';
	
		require_once('../repository_inc/template_header.php');
		echo '<div id="buttons">';
		if(isset($_REQUEST['session_id'])){
			echo '
				<div class="choice_frame" >
					Please choose the Coldwell Banker photography option you would like to use:<br />
					<div id="btn1" class="choice_button standard left disabled" location="checkout.php?session_id='.$_REQUEST['session_id'].'&userid=' . $userid .'" onclick="alert(\'Before proceeding you must read and agree to the terms and conditions below.\')" >
						<div class="logo" ></div>
						<div class="text" >Non-Previews<br>CB1 & CB2</div>
					</div>
					<div id="btn2" class="choice_button preview right disabled" location="checkout_coldwell_chooser.php?session_id='.$_REQUEST['session_id'].'&userid=' . $userid . '&switch=441" onclick="alert(\'Before proceeding you must read and agree to the terms and conditions below.\')">
						<div class="logo" ></div>
						<div class="text" >Previews<br>CB3</div>
					</div>
				</div>
			';
		}else{
			echo '
				<div class="choice_frame" >
					Please choose the Coldwell Banker photography option you would like to use:<br />
					<div id="btn1" class="choice_button standard left disabled" location="checkout.php?userid=' . $userid .'" onclick="alert(\'Before proceeding you must read and agree to the terms and conditions below.\')" >
						<div class="logo" ></div>
						<div class="text" >Non-Previews<br>CB1 & CB2</div>
					</div>
					<div id="btn2" class="choice_button preview right disabled" location="checkout_coldwell_chooser.php?userid=' . $userid . '&switch=441" onclick="alert(\'Before proceeding you must read and agree to the terms and conditions below.\')" >
						<div class="logo" ></div>
						<div class="text" >Previews<br>CB3</div>
					</div>
				</div>
			';	
		}
		echo '</div>';
?>
<script>
	function showHideButtons(checked){
		if(checked){
			$("#buttons").fadeTo('slow', 1);
			$("#btn1").removeClass('disabled');
			$("#btn1").attr('onclick','').unbind('click');
			$("#btn1").click(function(){
				window.location = $("#btn1").attr('location');
			});
			$("#btn2").removeClass('disabled');
			$("#btn2").attr('onclick','').unbind('click');
			$("#btn2").click(function(){
				window.location = $("#btn2").attr('location');
			});
		}else{
			$("#buttons").fadeTo('slow', .5);
			$("#btn1").addClass('disabled');
			$("#btn1").attr('onclick','').unbind('click');
			$("#btn1").click(function(){
				alert('Before proceeding you must read and agree to the terms and conditions below.');
			});
			$("#btn2").addClass('disabled');
			$("#btn2").attr('onclick','').unbind('click');
			$("#btn2").click(function(){
				alert('Before proceeding you must read and agree to the terms and conditions below.');
			});
		}
	}
</script>
<div class="notice">
	<br/><br/>
	<p align="center"><strong><input type="checkbox" onChange="showHideButtons(this.checked);" /> 
	I agree to the terms and conditions below.</strong></p>
	<h2 align="center" style="margin-bottom:0px;"> <strong>STOP AND READ BEFORE ORDERING PHOTOGRAPHY</strong></h2>
<div align="center">Important!!! Please read this FIRST to avoid any confusion or<u> </u>being overcharged for photography.</div>
<p style="font-weight:bold;" align="center">There are no refunds or no exceptions.</p>
<p><strong>CB1: <br />
</strong>If you intend to market your listing as a CB1, which is a la carte, you are entitled to 15 standard still photos of the property. The company will continue to subsidize $30 of the $50 package with either Obeo or Spotlight Home Tours. The $20 difference of the $50 package is the responsibility of the agent and must be received prior to the photography order being completed. Please submit the new listing form to the office staff with the payment section complete BEFORE you place the photography order. If you do not submit the form and we do not receive your $20 payment for CB1 you will be charged the full $50 amount automatically to your agent bill or credit card number with no refunds and no exceptions. It is the agent’s responsibility to submit the form and payment information to avoid being overcharged.</p>
<p style="font-weight:bold;">We will not refund your account and there will be no exceptions.</p> 
					<p><strong>CB2 AND CB3:  </strong><br />
					If you purposely or “accidently” select the CB2 photography package but intended to actually order the CB1 photography package (or the same for CB2 and CB3) and/or it conflicts with the package you actually selected on the new listing form with payment (CB1 = $20, CB2 = $218 or CB3 = $398) BEFORE you place the photography order with the vendor, the company (CBRB) will charge your office bill or credit card the full retail amount for the photography as it appears on the vendor’s website. Please be very specific on which photography package you intend to order to avoid any problems. It is the agent’s responsibility to submit the form and payment information to avoid being overcharged for payment.</p>
					<p><b>We will not refund your account and there will be no exceptions.</b> <br />
					If you have any questions or need clarification please contact your Manager or OA first to avoid any confusion or misunderstandings. Thank you!</p>
					<p> <strong>PLEASE NOTE: If you choose to order photography from more than the one vendor allotted for your photography package, you will be charged the full retail cost for <u>both</u> vendors and we will place it on your agent bill without notice and further questioning.  We&rsquo;re finding those who are ordering photography from all three vendors which IS NOT how the program is designed but if you choose to please use CAUTION and realize in advance you will be billed for all three because the company is getting billed. Just so you know.  </strong></p>
</div>
<?PHP
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