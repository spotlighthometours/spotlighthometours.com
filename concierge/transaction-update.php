<?php
/**********************************************************************************************
Document: concierge/transaction-update.php
Creator: Son Duong
Date: 11-28-2017
Purpose: update comtomeer profile if credit card exprice
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
		<link href="https://fonts.googleapis.com/css?family=Nunito:300" rel="stylesheet" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">';
	require_once('../repository_inc/template-header.php');
	showErrors();
?>

<div class="concierge-set-up" style="background-color:#FFF">
  <!-- Popup Order Submission -->
  <div class="order-info">

    <div class="form_line">
	
      <div class="form_direction fullWidth" style="font-size:20px;height:40px; margin-top:-28px; font-weight:bold">Update Profile</div>
    </div>
    <div id="order-msg"></div>
    <div class="left" style="padding-left:40px">
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
    <div class="right" style="padding-right:40px">
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
          <input onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" name="credit_cvv" id="credit_cvv" maxlength="3" value="">
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
    <div style="padding-left:40px">
      <input id="agree" name="agree" type="checkbox" value="1">
      Accept the <span class="terms_hl" onclick="Terms();">Terms and Conditions</span> 
	</div>
    <p></p>
	
    <div class="grey-divider"></div>
    <p></p>
    <table cellpadding="5" align="right" style="padding-right:40px">
      <tbody>
        <tr>
          <td></td>
          <td><div class="button_new button_blue button_mid" id="subOrderBtn" onclick="updateProfile();">
              <div class="curve curve_left"></div>
              <span class="button_caption">Update Your Profile</span>
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
</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>
</body>