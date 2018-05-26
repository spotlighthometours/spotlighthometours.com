<?php
/**********************************************************************************************
Document: concierge/index.php
Creator: Jeff Sylvester & Jacob Edmond Kerr
Date: 09-08-16
Purpose: Spotlight's Concierge Email Blast setup page 
**********************************************************************************************/
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	// Get rand number to force fresh download of CSS and JS to avoid cache issues
	$homeRandNum = rand(999999,999999999);
	$title = 'Spotlight | Concierge Checkout';
	$header = '
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.0.min.js"></script>
<link rel="stylesheet" type="text/css" href="tooltipster/css/tooltipster.bundle.min.css"/>
<script src="tooltipster/js/tooltipster.bundle.min.js"></script>
<script src="js/checkout-v3.js"></script>
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/concierge-checkout-v3.css"/>
';
	require_once('../repository_inc/template-header.php');
?>
<!-- Google Code for Concierge Check Out Page Conversion Page -->
<script type="text/javascript">
	/* <![CDATA[ */
	var google_conversion_id = 866348525;
	var google_conversion_language = "en";
	var google_conversion_format = "3";
	var google_conversion_color = "ffffff";
	var google_conversion_label = "GQIVCJ_b1mwQ7duNnQM";
	var google_remarketing_only = false;
	/* ]]> */
</script>
<script>
	fbq('track', 'ViewContent', {
		content_name: "Concierge Checkout v3"
	});
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	</script>

<noscript>
<div style="display:inline;"> <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/866348525/?label=GQIVCJ_b1mwQ7duNnQM&amp;guid=ON&amp;script=0"/> </div>
</noscript>
<?php	
	$conciergeMember = false;
	$userID = $_SESSION['user_id'];
	
	$members = new members(11, 'user', $userID);
	if($members->userActive()){
		$conciergeMember = true;
		//header("location:set-up.php");
	}
	$members->membershipID = 7;
	if($members->userActive()){
		$conciergeMember = true;
		header("location:set-up.php");
	}
	$members->membershipID = 6;
	if($members->userActive()){
		$conciergeMember = true;
		header("location:set-up.php");
	}
	$members->membershipID = 5;
	if($members->userActive()){
		$conciergeMember = true;
		header("location:set-up.php");
	}
	$members->membershipID = 4;
	if($members->userActive()){
		$conciergeMember = true;
		header("location:set-up.php");
	}
	showErrors();
//=======================================================================
// Document
//=======================================================================

$memberships = new memberships();
$packages = new packages();

$memberships->id = 7;
$memberships->getPrice();
$memberships->getYrPrice();
$bronzeMonthlyPrice = $memberships->price;
$bronzeYearlyPrice = $memberships->priceyear;

$memberships->id = 6;
$memberships->getPrice();
$memberships->getYrPrice();
$silverMonthlyPrice = $memberships->price;
$silverYearlyPrice = $memberships->priceyear;

$memberships->id = 5;
$memberships->getPrice();
$memberships->getYrPrice();
$goldMonthlyPrice = $memberships->price;
$goldYearlyPrice = $memberships->priceyear;

$memberships->id = 4;
$memberships->getPrice();
$memberships->getYrPrice();
$platinumMonthlyPrice = $memberships->price;
$platinumYearlyPrice = $memberships->priceyear;

$packages->loadPackage(85);
$conciergePackagePrice = $packages->price;
?>
<div class="concierge-set-up">
  <div id="setup">Choose your Concierge package:</div>
  <div id="concierge-line"><img src="images/concierge-line.png" alt="line" /></div>
  <div class="concierge-logo"><img src="images/concierge-logo.png" alt="logo" /></div>
  <br />
  <br />
  <center class="platinum-package">
    <img src="images/platinum-v2.png" alt="Concierge for Brokerages" />
    <a class="watch-video-button" onclick="showVideo('https://www.spotlighthometours.com/tours/video-player.php?type=video&id=2577968&autoPlay=true');_gaq.push(['_trackEvent','watch a video','clicked']);fbq('trackCustom', 'Watch Video Clicked V3');">Watch a Video</a>
    <a class="brokerage-quote-button" onclick="choseAgentBroker()">Request a Demo</a>
    <a class="video-bio-button"  onclick="showVideo('https://www.spotlighthometours.com/tours/video-player.php?type=video&id=1133991&autoPlay=true');_gaq.push(['_trackEvent','bio video demo','clicked']);">Demo</a>
    <a class="logo-amination-button"  onclick="showVideo('https://www.spotlighthometours.com/tours/video-player.php?type=video&id= 2574577&autoPlay=true');_gaq.push(['_trackEvent','logo animation demo video','clicked']);" >Demo</a>
    <a class="book-end-button"  onclick="showVideo('https://www.spotlighthometours.com/tours/video-player.php?type=video&id=2573572&autoPlay=true');_gaq.push(['_trackEvent','bookend demo video','clicked']);">Demo</a>
    <a class="realtor-website-button"  onclick="agentWebsite('https://www.spotlighthometours.com/microsites/agent.php?userID=15');_gaq.push(['_trackEvent','agent website demo','clicked']);">Demo</a>
    <div class="tooltip info" title="Did you know 92% of homebuyers start their search online? Catch leads right when they are looking for a new home by advertising on Google."></div>
    <div class="tooltip info advertising-info" title="Say goodbye to aimlessly mailing flyers and hoping for a lead. With Facebook, you can reach your ideal audience and generate higher quality leads by targeting based on age, location, household income, interests and more."></div>
    <div class="tooltip info drip-info" title="You know what they say, “the fortune is in the follow up!” Use an email drip campaign to stay in contact with your client list - without the time commitment. Simply send us your contact list, and we will take it from there."></div>
    <div class="tooltip info marketing-info" title="Reach people where they spend their time - on their phones! With mobile app marketing, you can show ads to people while they are playing games, using apps, or surfing the internet on their mobile devices."></div>
  </center>
  
  <!-- Popup Videos -->
  <div class="popup-video">
    <iframe src="" width="900" height="507" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
  </div>
  <div class="modal-bg" onClick="hideVideo()"></div>
  <!-- End Popup Videos -->
  
  <table cellpadding="17" cellspacing="0" class="packages" id="packages" style="margin-left:10px;">
    <tr>
      <td valign="top" align="center"><input id="userid" name="userid" type="hidden" value="<?php echo ($userID > 0 ? $userID: 0); ?>" />
        <img src="images/bronze-v3.png" alt="Bronze" /> <a class="package-price" id="package-price" data-choice="Bronze Concierge Monthly Subscription" data-type="month" data-membershipid="7" data-price="<?PHP echo $bronzeMonthlyPrice ?>">BUY NOW</a></td>
      <td valign="top" align="center"><img src="images/silver-v3.png" alt="Silver" /> <a class="package-price" data-choice="Silver Concierge Monthly Subscription" data-type="month" data-membershipid="6" data-price="<?PHP echo $silverMonthlyPrice ?>">BUY NOW</a></td>
      <td valign="top" align="center"><!-- <a href="http://www.spotlighthometours.com/microsites/agent.php" class="demo-realtor-website" target="_new"></a> -->
        
        <div class="realtor-website-button2"  onclick="agentWebsite('http://www.spotlighthometours.com/microsites/agent.php')">Demo</div>
        <img src="images/gold-v3.png" alt="Gold" /> <a class="package-price" data-choice="Gold Concierge Monthly Subscription" data-type="month" data-membershipid="5" data-price="<?PHP echo $goldMonthlyPrice ?>">BUY NOW</a></td>
    </tr>
  </table>
  <center>
    <br/>
    <img src="images/concierge-for-brokerages.png" alt="Concierge for Brokerages" width="277" height="40" border="0" /><br/>
    <a class="brokerage-quote" onclick="getQuoteForm()">Request a Demo</a>
    <div id="inquiry">For more information email: <a id="email-link" href="mailto:concierge@spotlighthometours.com">concierge@spotlighthometours.com</a> <br>
      or <br>
      call us at <strong>(801) 466.4074</strong></div>
  </center>
  <!-- Popup Order Submission -->
  <div class="order-info">
    <h2>Confirm Order </h2>
    <table border="0" cellpadding="5" cellspacing="2">
      <tbody>
        <tr>
          <td bgcolor="#a9a9a9"><strong>Items</strong></td>
          <td bgcolor="#a9a9a9">&nbsp;</td>
          <td bgcolor="#83ccc8"><strong>Monthly</strong></td>
        </tr>
        <tr>
          <td bgcolor="#dedede" class="membership">Gold Concierge Membership</td>
          <td bgcolor="#dedede">&nbsp;</td>
          <td bgcolor="#b4d4d6" class="price">$99.99</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="center" bgcolor="#a9a9a9"><strong>Tax</strong></td>
          <td bgcolor="#b4d4d6">$0.00</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="center" bgcolor="#a9a9a9"><strong>Subtotal</strong></td>
          <td bgcolor="#b4d4d6" class="price">$99.99</td>
        </tr>
        <tr class="coupon-discount" style="display:none;">
          <td>&nbsp;</td>
          <td align="center" bgcolor="#a9a9a9"><strong>Coupon</strong></td>
          <td bgcolor="#83ccc8" class="discount-total">-$25.00</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td align="center" bgcolor="#a9a9a9"><strong>Total</strong></td>
          <td bgcolor="#83ccc8" class="price total"><strong>$99.99</strong></td>
        </tr>
      </tbody>
    </table>
    <p></p>
    <table border="0" cellpadding="0">
      <tbody>
        <tr>
          <td valign="top"><div class="form_line">
              <div class="input_line w_sm">
                <div class="input_title">Coupon</div>
                <input id="checkout_coupon" name="checkout_coupon" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
              </div>
            </div></td>
          <td valign="top" style="padding-left:5px;padding-top:2px;"><div class="button_new button_mgrey button_sm close" onclick="applyCode()">
              <div class="curve curve_left"></div>
              <span class="button_caption">Apply Code</span>
              <div class="curve curve_right"></div>
            </div></td>
        </tr>
      </tbody>
    </table>
    <div class="form_line">
      <div class="form_direction fullWidth">Billing Information</div>
    </div>
    <div id="order-msg"></div>
    <div class="left">
      <div class="form_line">
        <div class="input_line w_lg">
          <div class="input_title">Name</div>
          <input id="credit_name" name="credit_name" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_info" style="display: none;">
            <div class="info_text">Name as it appears on the card/check.</div>
          </div>
        </div>
        <div class="required_line w_lg"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_lg">
          <div class="input_title">Address</div>
          <input id="credit_address" name="credit_address" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_info" style="display: none;">
            <div class="info_text">Do not include state or zip code.</div>
          </div>
        </div>
        <div class="required_line w_lg"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_mid">
          <div class="input_title">City</div>
          <input id="credit_city" name="credit_city" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_info" style="display: none;">
            <div class="info_text">Billing city.</div>
          </div>
        </div>
        <div class="required_line w_mid"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">State</div>
          <input id="credit_state" name="credit_state" maxlength="2" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_info" style="display: none;">
            <div class="info_text">Two letters, please ...</div>
          </div>
        </div>
        <div class="required_line w_sm"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_sm">
          <div class="input_title">Zip</div>
          <input id="credit_zip" name="credit_zip" maxlength="5" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_info" style="display: none;">
            <div class="info_text">Five digits, please ...</div>
          </div>
        </div>
        <div class="required_line w_sm"> <span class="required">required</span> </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="right">
      <div class="form_line">
        <div class="input_line w_mid">
          <div class="input_title">Type</div>
          <select id="credit_type" name="credit_type">
            <option value="visa" selected="">Visa</option>
            <option value="mastercard">Master Card</option>
            <option value="americanexpress">American Express</option>
          </select>
        </div>
        <div class="required_line w_mid"><span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_lg">
          <div class="input_title">Number</div>
          <input id="credit_number" name="credit_number" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);">
          <div class="input_info" style="display: none;">
            <div class="info_text">Number on the front of your card.</div>
          </div>
        </div>
        <div class="required_line w_lg"> <span class="required">required</span> </div>
      </div>
      <div class="form_line">
        <div class="input_line w_mid">
          <div class="input_title">Month</div>
          <select name="credit_month" id="credit_month">
            <option value="0" selected="selected">Month</option>
            <option value="01" >01</option>
            <option value="02">02</option>
            <option value="03" >03</option>
            <option value="04" >04</option>
            <option value="05" >05</option>
            <option value="06" >06</option>
            <option value="07" >07</option>
            <option value="08" >08</option>
            <option value="09" >09</option>
            <option value="10" >10</option>
            <option value="11" >11</option>
            <option value="12" >12</option>
          </select>
        </div>
      </div>
      <div class="form_line">
        <div class="input_line w_mid">
          <div class="input_title">Year</div>
          <select name="credit_year" id="credit_year">
            <option value="0" selected="selected">Year</option>
            <option value="2016" >2016</option>
            <option value="2017">2017</option>
            <option value="2018" >2018</option>
            <option value="2019" >2019</option>
            <option value="2020" >2020</option>
            <option value="2021" >2021</option>
            <option value="2022" >2022</option>
            <option value="2023" >2023</option>
            <option value="2024" >2024</option>
            <option value="2025" >2025</option>
            <option value="2026" >2026</option>
            <option value="2027" >2027</option>
            <option value="2028" >2028</option>
            <option value="2029" >2029</option>
            <option value="2030" >2030</option>
          </select>
        </div>
        <div class="required_line w_mid"><span class="required">required</span> </div>
      </div>
      <div class="form_line left widthAuto">
        <div class="input_line w_sm">
          <div class="input_title">CVV</div>
          <input onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" name="credit_cvv" id="credit_cvv" maxlength="4" value="">
          <div class="input_info" style="display: none;">
            <div class="info_text">3 digit security code on the back on the card</div>
          </div>
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <div class="grey-divider"></div>
    <p></p>
    <div>
      <input id="agree" name="agree" type="checkbox" value="1">
      Accept the <span class="terms_hl" onclick="Terms();">Terms and Conditions</span> </div>
    <p></p>
    <div class="grey-divider"></div>
    <p></p>
    <table cellpadding="5" align="right">
      <tbody>
        <tr>
          <td><div class="button_new button_dgrey button_mid" onclick="hideOrderInfo();">
              <div class="curve curve_left"></div>
              <span class="button_caption">Cancel</span>
              <div class="curve curve_right"></div>
            </div></td>
          <td><div class="button_new button_blue button_mid" id="subOrderBtn" onclick="submitOrder();">
              <div class="curve curve_left"></div>
              <span class="button_caption">Submit Order</span>
              <div class="curve curve_right"></div>
            </div></td>
        </tr>
      </tbody>
    </table>
    <div class="clear"></div>
  </div>
  <div class="order-info-bg" onClick="hideOrderInfo()"></div>
  <!-- END Popup Order Submission -->
  <div id="concierge-line2"><img src="images/concierge-line.png" alt="line" /></div>
  <br />
  <br />
  <br />
  <br />
  <br />
  <br />
</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>
</body>