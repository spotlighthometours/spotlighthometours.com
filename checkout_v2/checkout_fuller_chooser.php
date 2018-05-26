<?php
/**********************************************************************************************
Document: checkout_fuller_chooser.php
Creator: Jacob Edmond Kerr
Date: 01-31-13
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
	
	$userid = '';
	if(isset($_POST['userid'])) {
		$userid = intval($_POST['userid']);
	} elseif (isset($_GET['userid'])) {
		$userid = intval($_GET['userid']);
	}
	
	if(isset($_POST['switch'])) {
		$switch = $_POST['switch'];
	} elseif (isset($_GET['switch'])) {
		$switch = $_GET['switch'];
	}
	
	$access = true;
	// Preliminary check for access rights. (this is not really used it is depreciated $users->authenticate(); takes care of this now)
	if($access) {
//=======================================================================
// CONTENT AREA
//=======================================================================
		$location = '/checkout_v2/checkout.php?userid=' . $userid;
		
		if(isset($switch)) {
			$_SESSION['broker_id_switch'] = $switch;
			header('Location: ' . $location);
			ob_flush();		
		}else{
			unset($_SESSION['broker_id_switch']);
		}
		
		
		$title = "Sotheby Photography Packages";
		$randIt = rand(999,999999);
		$header = '
			<link rel="stylesheet" media="all" href="checkout_fuller_chooser.css?randIt='.$randIt.'">
			<script src="../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
			<script src="../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
			<script>
				$(document).ready(
					function() {
						$(".option-btn:not(.selected)").mouseover(function(){
							$(this).stop().animate({ backgroundColor: "#0087cc", borderColor: "#22597c" }, "slow");
						});
						$(".option-btn:not(.selected)").mouseleave(function(){
							$(this).stop().animate({ backgroundColor: "#62c0fb", borderColor: "#62c0fb" }, "slow");
						});
						$(".option-btn").click(function(){
							if($("#agree").is(":checked")){
								window.location = $(this).attr("location");
								if($(this).hasClass("selected")){
									$(this).removeClass("selected");
									$(this).stop().animate({ backgroundColor: "#62c0fb", borderColor: "#62c0fb" }, "slow");
								}else{
									$(".option-btn").removeClass("selected");
									$(this).addClass("selected");
									$(this).stop().animate({ backgroundColor: "#0087cc"}, "slow");
								}
							}else{
								alert("You must agree to the terms and conditions by checking the checkbox above before you can make a selection.");
							}
						});
					}
				);
			</script>
		';
	
		require_once('../repository_inc/template_header.php');
?>
<div class="title">Sotheby’s Photography Packages</div>
<div class="agree">
	<input name="agree" type="checkbox" id="agree" value="1" /> <strong>I agree to the terms and conditions below and confirm I have a signed, valid FSIR listing agreement.</strong>
    <p>Note: Be sure and select the correct price level for this listing. The package level is determined by the price of the home
In the event the wrong level is selected you maybe charged back in your agent account.</p>
</div>
<h2>Please select the price range this listing will be listed at. </h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="option-tbl">
  <tr>
    <td><table border="0" cellspacing="0" cellpadding="15">
      <tr>
        <td><div class="option-btn" location="checkout_fuller_chooser.php?userid=<?PHP echo $userid; ?>&switch=513">Select</div></td>
        <td class="option-txt">Lot or Land </td>
      </tr>
      <tr>
        <td><div class="option-btn" location="checkout_fuller_chooser.php?userid=<?PHP echo $userid; ?>&switch=511">Select</div></td>
        <td class="option-txt">$0 - $250,000</td>
      </tr>
      <tr>
        <td><div class="option-btn" location="checkout_fuller_chooser.php?userid=<?PHP echo $userid; ?>&switch=514">Select</div></td>
        <td class="option-txt">$251,000 - $750,000</td>
      </tr>
    </table></td>
    <td><table border="0" cellspacing="0" cellpadding="15">
      <tr>
        <td><div class="option-btn" location="checkout_fuller_chooser.php?userid=<?PHP echo $userid; ?>&switch=515">Select</div></td>
        <td class="option-txt">$750,001 - $1,500,000</td>
      </tr>
      <tr>
        <td><div class="option-btn" location="checkout_fuller_chooser.php?userid=<?PHP echo $userid; ?>&switch=516">Select</div></td>
        <td class="option-txt">$1,500,000 - $3,000,000</td>
      </tr>
      <tr>
        <td><div class="option-btn" location="checkout_fuller_chooser.php?userid=<?PHP echo $userid; ?>&switch=517">Select</div></td>
        <td class="option-txt">$3,000,000 or Above</td>
      </tr>
    </table></td>
  </tr>
</table>
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



	if(intval($userid) == 1633)
	{
		$switch = '516';
			$_SESSION['broker_id_switch'] = $switch;
		header('Location: https://www.spotlighthometours.com/checkout_v2/checkout.php?userid=1633');
			ob_flush();		
	}



?>