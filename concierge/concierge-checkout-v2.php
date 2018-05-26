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
<script src="js/checkout-v2.js"></script>
<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="css/concierge-checkout-v2.css"/>
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
		content_name: "Concierge Checkout v2"
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
    <a class="watch-video-button" onclick="showVideo('https://www.spotlighthometours.com/tours/video-player.php?type=video&id=2577968&autoPlay=true');_gaq.push(['_trackEvent','watch a video','clicked']);fbq('trackCustom', 'Watch Video Clicked V2');">Watch a Video</a>
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
        <img src="images/bronze.png" alt="Bronze" />
        <a class="package-price" id="package-price" data-choice="Bronze Concierge Monthly Subscription" data-type="month" data-membershipid="7" >$<?PHP echo $bronzeMonthlyPrice ?> / Monthly</a>
      <a class="package-price" data-choice="Bronze Concierge Yearly Subscription" data-type="year" data-membershipid="7" >$<?PHP echo $bronzeYearlyPrice ?> / Yearly</a></td>
      <td valign="top" align="center"><img src="images/silver.png" alt="Silver" />
        <a class="package-price" data-choice="Silver Concierge Monthly Subscription" data-type="month" data-membershipid="6" >$<?PHP echo $silverMonthlyPrice ?> / Monthly</a>
      <a class="package-price" data-choice="Silver Concierge Yearly Subscription" data-type="year" data-membershipid="6">$<?PHP echo $silverYearlyPrice ?> / Yearly</a></td>
      <td valign="top" align="center"><!-- <a href="http://www.spotlighthometours.com/microsites/agent.php" class="demo-realtor-website" target="_new"></a> -->
        
        <div class="realtor-website-button2"  onclick="agentWebsite('http://www.spotlighthometours.com/microsites/agent.php')">Demo</div>
        <img src="images/gold-v3.png" alt="Gold" />
        <a class="package-price" data-choice="Gold Concierge Monthly Subscription" data-type="month" data-membershipid="5">$<?PHP echo $goldMonthlyPrice ?> / Monthly</a>
      <a class="package-price" data-choice="Gold Concierge Yearly Subscription" data-type="year" data-membershipid="5">$<?PHP echo $goldYearlyPrice ?> / Yearly</a></td>
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
  <div id="concierge-line2"><img src="images/concierge-line.png" alt="line" /></div>
  <div id="billing" style="margin-left:-15px;">
    <div id="billing-info">Billing Information:</div>
    <form>
      <div id="card-type">
        <label for="credit_type">card type:</label>
        <select id="credit_type" name="credit_type">
          <option value="visa" selected="">Visa</option>
          <option value="mastercard">Master Card</option>
          <option value="americanexpress">American Express</option>
        </select>
      </div>
      <div  id="card-number">
        <label for="credit_number">card number:</label>
        <input type="" name="credit_number" id="credit_number" />
      </div>
      <div  id="name-on-card">
        <label for="credit_name">name on card:</label>
        <input type="text" name="credit_name" id="credit_name" />
      </div>
      <div id="experation-month">
        <label for="credit_month">experation date:</label>
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
      <div  id="cvv">
        <label for="credit_cvv">cvv:</label>
        <input type="text" maxlength="4" name="credit_cvv" id="credit_cvv"/>
      </div>
      <div  id="address">
        <label for="credit_address">address:</label>
        <input type="text" name="credit_address" id="credit_address" />
      </div>
      <div  id="city">
        <label for="credit_city">city:</label>
        <input type="text" name="credit_city" id="credit_city" />
      </div>
      <div id="state">
        <label for="credit_state">state:</label>
        <select name="credit_state" id="credit_state">
          <option value="0" selected="selected" >State</option>
          <option value="AL" >Alabama</option>
          <option value="AK" >Alaska</option>
          <option value="AB" >Alberta</option>
          <option value="AI" >Anguilla</option>
          <option value="AZ" >Arizona</option>
          <option value="AR" >Arkansas</option>
          <option value="BC" >British Columbia</option>
          <option value="CA" >California</option>
          <option value="CO" >Colorado</option>
          <option value="CT" >Connecticut</option>
          <option value="DE" >Delaware</option>
          <option value="FL" >Florida</option>
          <option value="GA" >Georgia</option>
          <option value="HI" >Hawaii</option>
          <option value="ID" >Idaho</option>
          <option value="IL" >Illinois</option>
          <option value="IN" >Indiana</option>
          <option value="IA" >Iowa</option>
          <option value="KS" >Kansas</option>
          <option value="KY" >Kentucky</option>
          <option value="LA" >Louisiana</option>
          <option value="ME" >Maine</option>
          <option value="MB" >Manitoba</option>
          <option value="MD" >Maryland</option>
          <option value="MA" >Massachusetts</option>
          <option value="MI" >Michigan</option>
          <option value="MN" >Minnesota</option>
          <option value="MS" >Mississippi</option>
          <option value="MO" >Missouri</option>
          <option value="MT" >Montana</option>
          <option value="NE" >Nebraska</option>
          <option value="NV" >Nevada</option>
          <option value="NB" >New Brunswick</option>
          <option value="NH" >New Hampshire</option>
          <option value="NJ" >New Jersey</option>
          <option value="NM" >New Mexico</option>
          <option value="NY" >New York</option>
          <option value="NL" >Newfoundland and Labrador</option>
          <option value="NC" >North Carolina</option>
          <option value="ND" >North Dakota</option>
          <option value="NT" >Northwest Territories</option>
          <option value="NS" >Nova Scotia</option>
          <option value="NU" >Nunavut</option>
          <option value="OH" >Ohio</option>
          <option value="OK" >Oklahoma</option>
          <option value="ON" >Ontario</option>
          <option value="OR" >Oregon</option>
          <option value="PA" >Pennsylvania</option>
          <option value="PE" >Prince Edward Island</option>
          <option value="QC" >Quebec</option>
          <option value="RI" >Rhode Island</option>
          <option value="SK" >Saskatchewan</option>
          <option value="SM" >Sint Maarten</option>
          <option value="SC" >South Carolina</option>
          <option value="SD" >South Dakota</option>
          <option value="TN" >Tennessee</option>
          <option value="TX" >Texas</option>
          <option value="UT" >Utah</option>
          <option value="VT" >Vermont</option>
          <option value="VA" >Virginia</option>
          <option value="WA" >Washington</option>
          <option value="DC" >Washington D.C.</option>
          <option value="WV" >West Virginia</option>
          <option value="WI" >Wisconsin</option>
          <option value="WY" >Wyoming</option>
          <option value="YT" >Yukon</option>
        </select>
      </div>
      <div  id="zip">
        <label for="credit_zip">zip:</label>
        <input type="text" name="credit_zip" id="credit_zip" />
      </div>
    </form>
  </div>
  <div id="summary">
    <div id="concierge-line3"><img src="images/concierge-line4.png" alt="line" /></div>
    <div id="order-summary">Order Summary:</div>
    <div id="subscription-choice">12 month Concierge subscription</div>
    <div id="concierge-line5"><img width="200px" src="images/concierge-line.png" alt="line" /></div>
    <div id="order-total">Order total: <span></span></div>
    <div id="coup-discount">Discount: <span>$24.99</span></div>
    <div id="coup-total">Total: <span>$25.00</span></div>
    <div id="order-coupon">
      <label>coupon:</label>
      <input id="checkout_coupon" name="checkout_coupon">
      <div class="button_new button_dgrey button_med" onclick="applyCode()">
        <div class="curve curve_left"></div>
        <span class="button_caption">Apply Code</span>
        <div class="curve curve_right"></div>
      </div>
    </div>
    <div id="terms-of-service">Accept our <a href="javascript:Terms();">terms and conditions</a>
      <input type="checkbox" name="agree" id="agree" value="agree">
    </div>
    <div id="place-order"><a href="javascript:submitOrder()"><img src="images/place-order-button.png" alt="place-order" width="120px"/></a></div>
  </div>
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