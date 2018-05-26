// JavaScript Document
var order = {};
var checkoutPrice = 0.00;
var checkoutType = 'month';
var checkoutChoice = 'Monthly Concierge Subscription';
$(document).ready(
	function() {	
		$(".package-price").click(function(){
			$('html, body').animate({
				scrollTop: $("#package-price").offset().top
			}, 500);
			$(".package-price, .packages td").removeClass('selected');
			$(this).addClass('selected');
			$(this).parent().addClass('selected');
			checkoutPrice = $(this).html();
			$("#summary #order-total span").html(checkoutPrice);
			$("#summary #subscription-choice").html($(this).data("choice"));
			checkoutChoice = $(this).data("choice");
			$("#billing").slideUp('slow', function(){
				$("#billing").slideDown('slow');
			});
			$("#summary").slideUp('slow', function(){
				$("#summary").slideDown('slow');
			});
			checkoutType = $(this).data("type");
			checkoutMembershipID = $(this).data("membershipid");
			order.order_type = checkoutType;
			order.membershipid = checkoutMembershipID;
			//checkoutPrice = checkoutPrice.replace(/\D/g,'');
			checkoutPrice = Number(checkoutPrice.replace(/[^0-9\.]+/g,""));
			order.order_total = checkoutPrice;
			console.log(order);
			$("#summary #coup-discount, #summary #coup-total").css("display", "none");
		});
	}
);

function ValidateCC() {
	try {
		var errors = "";
		if(document.getElementById('credit_name')) {
			if (document.getElementById('credit_name').value.length == 0) {
				errors += '<li>Please enter the name on the credit card.</li>';
			} else {
				order.cc_name = document.getElementById('credit_name').value;
			}
		}
		if(document.getElementById('credit_address')) {
			if (document.getElementById('credit_address').value.length == 0) {
				errors += '<li>Please enter the billing address of the credit card.</li>';
			} else {
				order.cc_address = document.getElementById('credit_address').value;
			}
		}
		if(document.getElementById('credit_city')) {
			if (document.getElementById('credit_city').value.length == 0) {
				errors += '<li>Please enter the billing city of the credit card.</li>';
			} else {
				order.cc_city = document.getElementById('credit_city').value;
			}
		}
		if(document.getElementById('credit_state')) {
			if (document.getElementById('credit_state').value == 0) {
				errors += '<li>Please select the billing state of the credit card.</li>';
			} else {
				order.cc_state = document.getElementById('credit_state').value;
			}
		}
		if(document.getElementById('credit_zip')) {
			if (document.getElementById('credit_zip').value.length == 0) {
				errors += '<li>Please enter the billing zip code of the credit card.</li>';
			} else if (isNaN(document.getElementById('credit_zip').value)) {
				errors += '<li>Please enter only numbers for the zip code of the credit card.</li>';
			} else {
				order.cc_zip = document.getElementById('credit_zip').value;
			}
		}
		if(document.getElementById('credit_cvv')) {
			if (document.getElementById('credit_cvv').value.length == 0) {
				errors += '<li>Please enter the CVV number on the back of your card.</li>';
			} else if (isNaN(document.getElementById('credit_cvv').value)) {
				errors += '<li>Please enter only numbers for the CVV number.</li>';
			} else {
				order.cc_cvv = document.getElementById('credit_cvv').value;
			}
		}
		if(document.getElementById('credit_number')) {
			if (document.getElementById('credit_number').value.length == 0) {
				errors += '<li>Please enter the credit card number.</li>';
			} else if (isNaN(document.getElementById('credit_number').value)) {
				errors += '<li>Please enter only numbers for the credit card number.</li>';
			} else {
				order.cc_number = document.getElementById('credit_number').value;
			}
		}
		if(document.getElementById('credit_month')) {
			if (document.getElementById('credit_month').value == 0) {
				errors += '<li>Please select the credit card expiration month.</li>';
			} else {
				order.cc_month = document.getElementById('credit_month').value;
			}
		}
		if(document.getElementById('credit_year')) {
			if (document.getElementById('credit_year').value == 0) {
				errors += '<li>Please select the credit card expiration year.</li>';
			} else {
				order.cc_year = document.getElementById('credit_year').value;
			}
		}
		if(document.getElementById('credit_type')) {
			order.cc_type = document.getElementById('credit_type').options[document.getElementById('credit_type').selectedIndex].value
		}		
		if(errors.length > 0) {
			errors = "<ul>" + errors + "</ul>";
			ShowPopUp("Some data is missing ...", errors);
			return false;
		}else{
			if($("#agree").is(':checked')){
				return true;
			}else{
				ShowPopUp("Terms &amp; Conditions", "Please agree to the terms and conditions to proceed.");
				return false;
			}
		}
	} catch(err) {
		window.alert("ValidateStep4: " + err + ' (line: ' + err.line + ')');
	}
}

function submitOrder(){
	if(!ValidateCC()){
		return false;
	}
	var params = '';
	var first = true;
	$(':input').each(function() {
		if(!first){
			params += "&";
		}
		if($(this).attr('type')=='checkbox'){
			if($(this).is(':checked')){
				params += this.name+"=1";
			}else{
				params += this.name+"=0";
			}
		}else{
			params += this.name+"="+encodeURIComponent(this.value);
		}
		first = false;
	});
	for (x in order){
			params += '&'+x+'='+order[x];
	}
	var url = '../../repository_queries/process_concierge_order.php';
	GetLoadingScreen('Processing Transaction');
	ajaxQuery(url, params, 'transactionResult');
}

function transactionResult(){
	HidePopUp();
	var errors = responseXML.getElementsByTagName("error");			
	var error = "";
	for(var i = 0; i < errors.length; i++) {
		if(errors[i].hasChildNodes()) {
			error += "<li>"+errors[i].childNodes[0].nodeValue+"</li>";
		}
	}
	if (error.length > 0) {
		error = "<ul>" + error + "</ul>";
		ShowPopUp("There was an error with your order.", error);
	} else {
		// Get order info from return XML
		var orderids = responseXML.getElementsByTagName("orderid");
		var orderid = "";
		for(var i = 0; i < orderids.length; i++) {
			if(orderids[i].hasChildNodes()) {
				orderid += orderids[i].childNodes[0].nodeValue;
			}
		}
		OrderSuccess(orderid);
	}
}

function OrderSuccess(orderid){
	try {
		if(document.getElementById("pop_up_title_frame")) {
			document.getElementById("pop_up_title_frame").style.display = "none";
		}
		var complete = '<div class="complete_frame" >';
		complete += '<div class="complete_title" >';
		complete += '<div class="check" ></div>'+checkoutChoice+' ordered Successfully!';
		complete += '</div>';
		complete += '<div class="complete_text" >Here is your order information:</div>';
		complete += '<div class="order_info" >';
		complete += '<div class="order_frame left_frame" >';
		complete += 'Order #:';
		complete += '</div>';
		complete += '<div id="complete_info" class="order_frame right_frame" >';
		complete += orderid;
		complete += '</div>';
		complete += '</div>';
		complete += '<div class="complete_text" >';
		complete += 'Thank you for choosing Spotlight Home Tours!';
		complete += '</div>';
		complete += '<div class="contact_info" >';
		complete += '<span class="bold" >Please contact us with any questions</span><br />';
		complete += 'support@spotlighthometours.com<br />';
		complete += '801.466.4074<br />';
		complete += '888.838.8810';
		complete += '</div>';
		complete += '<div class="button_new button_blue button_mid" onclick="window.location = \'profile.php\'">';
		complete += '<div class="curve curve_left" ></div>';
		complete += '<span class="button_caption" >Continue</span>';
		complete += '<div class="curve curve_right" ></div>';
		complete += '</div>';
		complete += '</div>';
		ShowPopUp("", complete);
	} catch(err) {
		window.alert("OrderSuccess: " + err + ' (line: ' + err.line + ')');
	}		
}

function Terms() {
	try {
		var title = "Terms & Conditions";
		var tandc  = '<div style="height:300px;overflow:auto;padding-right:15px;"><p>The definition of a Spotlight tour will be determined by the specific package ordered. Each package consists of different ';
	    tandc += 'amounts of video, stills, and panoramic photographs. Each customer shall have the option to choose the specific ';
	    tandc += 'rooms/scenes/scenery to be videotaped and or photographed through our online order form. Rooms/scenes/scenery can be ';
	    tandc += 'changed prior to shooting by contacting the videographer and or photographer. Each customer will also have the opportunity ';
	    tandc += 'to leave the room/scene/scenery decisions up to videographers and or photographers discretion by checking the box on the ';
	    tandc += 'online order form or contacting the videographer and/or photographer prior to shooting.</p>';
	    tandc += '<p>Once the tour is processed by Spotlight, it will be posted on www.spotlighthometours.com. The URL to the tour will be sent to ';
	    tandc += 'the email address given to Spotlight Tours by the purchaser. This URL can be freely used to create a link to/from your own ';
	    tandc += 'personal website.</p>';
	    tandc += '<p>Each room/scene/scenery ordered will be made into a video, panoramic, and or still photos tour depending on tour package ';
	    tandc += 'ordered. Spotlight reserves the right to determine which scenes to use that are in the best interest of the agent, homeowner or ';
	    tandc += 'other property owner. Choices not to show a particular room/scene/scenery will be the sole and absolute choice of Spotllight ';
	    tandc += 'and/or its affiliated editing department.</p>';
	    tandc += '<p>Spotlight will post the tour to the website only upon receiving all required information. including, but not limited to; asking ';
	    tandc += 'price, property address, square footage, number of rooms, etc. Spotlight will not be responsible for any delays due to missing ';
	    tandc += 'information. </p>';
	    tandc += '<p>All Spotlight tours will become inactive after the date specified at the time of purchase. The tour may be reactivated at any time ';
	    tandc += 'upon request. Spotlight Tours reserved the right to assess a reactivation fee and/or an annual fee for any tour that is hosted by ';
	    tandc += 'Spotlight Tours beyond the period of one year.</p>';
	    tandc += '<p>All images and media are considered the sole and exclusive property of Spotlight. Spotlight reserves all rights to the images, ';
	    tandc += 'media and content. All images and media shall be made available to the client until the property is sold, the listing expires or ';
	    tandc += 'twelve (12) months, whichever occurs first, unless otherwise agreed upon in writing previously. If the photos are being used for ';
	    tandc += 'nightly rental and/or vacation rental purposes then the client has full licensing of the images for marketing purposes of the';
	    tandc += 'specific property with no end date of use. </p>';
	    tandc += '<p>The customer hereby expressly waives any and all claims, demands, costs, and causes of action against Spotlight, its agents, ';
	    tandc += 'contractors, employees and assigns, for any and all damages, including property and personal injury, for any acts of the ';
	    tandc += 'videographers and/or photographers at the property designated by the agent, homeowner or other property owner.';
	    tandc += 'Payment Terms: Payment in full is expected at the time of order unless otherwise previously arranged. If company billing ';
	    tandc += 'invoice is not paid in full within thirty (30) days following submission of the invoice by Spotlight an interest rate of 2.0% per ';
	    tandc += 'month will be charged on past due balances (24% per annum). The property, including all videotapes, photographs and all other';
	    tandc += 'work completed by Spotlight produced for the customer remains the property of Spotlight Tours.</p>';
	    tandc += '<p>In the event of any action at law or inequity between the customer and Spotlight to enforce any of the provisions and/or rights ';
	    tandc += 'hereunder to recover damages for breach hereof, the unsuccessful party to such litigation covenants and agrees to pay to the ';
	    tandc += 'successful party all costs and expenses, including attorney fees incurred herein by such successful party, and if such successful ';
	    tandc += 'party shall recover judgment in any such action or proceeding such costs and expenses and attorney fees shall be included in ';
	    tandc += 'and as part of such judgment.</p>';
	    tandc += '<p>Any and all changes or modifications of the terms and conditions of this agreement must be in writing and executed by both ';
	    tandc += 'Spotlight and customer. The terms of this Agreement shall be governed by the laws and regulations of the State of Utah. The ';
	    tandc += 'State of Utah shall have exclusive jurisdiction with respect to any and all legal actions that may arise from this Agreement. This ';
	    tandc += 'Agreement shall be considered to be the only agreement between the parties. All negotiations and oral agreements acceptable ';
	    tandc += 'to both parties are included herein. The parties agree that all of the provisions hereof are to be considered as covenants and agreements where used in each ';
	    tandc += 'separate paragraph hereof, and that all of the provisions hereof shall bind and inure to the benefit of the parties hereto, and ';
	    tandc += 'their respective heirs, legal representative, agents, successors and assigns.</p>';
	    tandc += '<p>Spotlight Tours will use reasonable effort to being flexible to film dates with respect to weather. However, it should be ';
	    tandc += 'anticipated that filming may take place regardless of weather or adverse conditions.</p></div>';
		ShowPopUp(title, tandc);
	} catch(err) {
		window.alert("Terms: " + err + ' (line: ' + err.line + ')');
	}	
}

function applyCode(){
	var code = $("#checkout_coupon").val();
	var price = order.order_total;
	var params = 'orderPrice='+price+'&code='+code;
	order.coupon = code;
	var url = '../../repository_queries/get_order_discount.php';
	GetLoadingScreen('Applying Dicount Code');
	ajaxQuery(url, params, 'showDiscount');
}

function showDiscount(){
	HidePopUp();
	var total = responseXML.getElementsByTagName("total");
	total = total[0].textContent;
	var discount = responseXML.getElementsByTagName("discount");
	discount = discount[0].textContent;
	if(parseInt(discount)>0){
		$("#summary #coup-discount span").html("$"+discount);
		$("#summary #coup-total span").html("$"+total);
		$("#summary #coup-discount, #summary #coup-total").slideDown('slow');
	}else{
		$("#summary #coup-discount, #summary #coup-total").css("display", "none");
	}
}

function choseAgentBroker(){
	var selectQuote = ' \
<div class="quote-form login-panel" id="agentBroker"> \
	<img src="images/Agent_1.png" alt="Agent Quote"  onmouseover="" style="cursor: pointer;" onclick="getAgentQuoteForm()";>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\
	<img src="images/Brokerage_1.png" alt="Mountain View"  onmouseover="" style="cursor: pointer;" onclick="getBrokerQuoteForm()";> \
<div class="clear"></div> \
</div>';
	ShowPopUp('Concierge for', selectQuote);
}

function getBrokerQuoteForm(){
	var quoteForm = ' \
	<div class="quote-form login-panel"><p>Simply fill out the brief form below and one of Spotlight\'s team members will contact you right away with a free quote.</p><br/> \
	<div id="brokerageSignupMsg"></div> \
	<div class="form_line"> \
	  <div class="input_line w_lg"> \
		<div class="input_title">Brokerage</div> \
		<input id="brokerage" name="brokerage" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
		<div class="input_info" style="display: none;" > \
		  <div class="info_text" >Brokerage name and office</div> \
		</div> \
	  </div> \
	</div> \
	<div class="form_line"> \
	  <div class="input_line w_lg"> \
		<div class="input_title">Contact name</div> \
		<input id="contactname" name="contactname" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
		<div class="input_info" style="display: none;" > \
		  <div class="info_text" >A contact name for your brokerage</div> \
		</div> \
	  </div> \
	</div> \
	<div class="form_line"> \
	  <div class="input_line w_lg"> \
		<div class="input_title">Phone</div> \
		<input id="brkphone" name="brkphone" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
		<div class="input_info" style="display: none;" > \
		  <div class="info_text">10 digit # required.</div> \
		</div> \
	  </div> \
	</div> \
	<div class="form_line"> \
	  <div class="input_line w_lg"> \
		<div class="input_title">Email</div> \
		<input id="brkemail" name="brkemail" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
		<div class="input_info" style="display: none;" > \
		  <div class="info_text" >Your email address</div> \
		</div> \
	  </div> \
	</div> \
	<div class="form_line"> \
	  <div class="input_line w_lg"> \
		<div class="input_title">Number agents</div> \
		<input id="num_agents" name="num_agents" onfocus="ToggleInputInfo(this, 1);" type="number" /> \
	  </div> \
	</div> \
	<div class="loginButtons" style="width:550px;"> \
	<div align="left"><div class="button_new button_blue button_mid" onClick="requestQuote()"><div class="curve curve_left"></div><span class="button_caption">Request Quote</span><div class="curve curve_right"></div></div> \
	</div> \
	<div class="clear"></div> \
	</div>';
		ShowPopUp('Concierge for Brokerages', quoteForm);
}
function getAgentQuoteForm(){
	var quoteForm = ' \
	<div class="quote-form login-panel"><p>Simply fill out the brief form below and one of Spotlight\'s team members will contact you right away with a free quote.</p><br/> \
	<div id="brokerageSignupMsg"></div> \
	<div class="form_line"> \
	  <div class="input_line w_lg"> \
		<div class="input_title">Brokerage</div> \
		<input id="brokerage" name="brokerage" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
		<div class="input_info" style="display: none;" > \
		  <div class="info_text" >Brokerage name and office</div> \
		</div> \
	  </div> \
	</div> \
	<div class="form_line"> \
	  <div class="input_line w_lg"> \
		<div class="input_title">Contact name</div> \
		<input id="contactname" name="contactname" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
		<div class="input_info" style="display: none;" > \
		  <div class="info_text" >A contact name for your brokerage</div> \
		</div> \
	  </div> \
	</div> \
	<div class="form_line"> \
	  <div class="input_line w_lg"> \
		<div class="input_title">Phone</div> \
		<input id="brkphone" name="brkphone" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
		<div class="input_info" style="display: none;" > \
		  <div class="info_text">10 digit # required.</div> \
		</div> \
	  </div> \
	</div> \
	<div class="form_line"> \
	  <div class="input_line w_lg"> \
		<div class="input_title">Email</div> \
		<input id="brkemail" name="brkemail" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
		<div class="input_info" style="display: none;" > \
		  <div class="info_text" >Your email address</div> \
		</div> \
	  </div> \
	</div> \
	<div class="loginButtons" style="width:550px;"> \
	<div align="left"><div class="button_new button_blue button_mid" onClick="requestQuote()"><div class="curve curve_left"></div><span class="button_caption">Request Quote</span><div class="curve curve_right"></div></div> \
	</div> \
	<div class="clear"></div> \
	</div>';
		ShowPopUp('Concierge for Agent', quoteForm);
}


function getQuoteForm(){
	
	var quoteForm = ' \
<div class="quote-form login-panel"><p>Simply fill out the brief form below and one of Spotlight\'s team members will contact you right away with a free quote.</p><br/> \
<div id="brokerageSignupMsg"></div> \
<div class="form_line"> \
  <div class="input_line w_lg"> \
    <div class="input_title">Brokerage</div> \
    <input id="brokerage" name="brokerage" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
    <div class="input_info" style="display: none;" > \
      <div class="info_text" >Brokerage name and office</div> \
    </div> \
  </div> \
</div> \
<div class="form_line"> \
  <div class="input_line w_lg"> \
    <div class="input_title">Contact name</div> \
    <input id="contactname" name="contactname" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
    <div class="input_info" style="display: none;" > \
      <div class="info_text" >A contact name for your brokerage</div> \
    </div> \
  </div> \
</div> \
<div class="form_line"> \
  <div class="input_line w_lg"> \
    <div class="input_title">Phone</div> \
    <input id="brkphone" name="brkphone" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
    <div class="input_info" style="display: none;" > \
      <div class="info_text">10 digit # required.</div> \
    </div> \
  </div> \
</div> \
<div class="form_line"> \
  <div class="input_line w_lg"> \
    <div class="input_title">Email</div> \
    <input id="brkemail" name="brkemail" onfocus="ToggleInputInfo(this, 1);" onblur="ToggleInputInfo(this, 0);" onkeydown="ToggleInputInfo(this, 0);" /> \
    <div class="input_info" style="display: none;" > \
      <div class="info_text" >Your email address</div> \
    </div> \
  </div> \
</div> \
<div class="form_line"> \
  <div class="input_line w_lg"> \
    <div class="input_title">Number agents</div> \
    <input id="num_agents" name="num_agents" onfocus="ToggleInputInfo(this, 1);" type="number" /> \
  </div> \
</div> \
<div class="loginButtons" style="width:550px;"> \
<div align="left"><div class="button_new button_blue button_mid" onClick="requestQuote()"><div class="curve curve_left"></div><span class="button_caption">Request Quote</span><div class="curve curve_right"></div></div> \
</div> \
<div class="clear"></div> \
</div>';
	ShowPopUp('Concierge for Brokerages', quoteForm);
}

function validateQuoteRequest(){
	required = new Array(
		'brokerage',
		'contactname',
		'brkphone',
		'brkemail',
		'num_agents'
	);
	
	required_type = new Array(
		'empty',
		'empty',
		'phone',
		'email',
		'number'
	);
	
	inValidOutput = new Array(
		'Please enter the brokerage name and office.',
		'Please enter a contact name for the brokerage.',
		'Please enter a valid phone number. A 10 digit phone number is required. Example 801-501-6500.',
		'Please enter a valid email address.',
		'Please enter the number of agents in the brokerage.'
	);
	
	numberOfRequired = required.length;
	
	for(i=0; i<numberOfRequired; i++){
		inputValue = document.getElementById(required[i]).value;
		type = required_type[i];
		if(type=="phone"){
			if(validate('empty', inputValue)){
				if(!validate(type, inputValue)){
					outputAlert('brokerageSignupMsg', inValidOutput[i]);
					document.getElementById(required[i]).focus();
					return false;
				}
			}
		}else{
			if(!validate(type, inputValue)){
				outputAlert('brokerageSignupMsg', inValidOutput[i]);
				document.getElementById(required[i]).focus();
				return false;
			}
		}
	}
	
	return true;
}

function requestQuote(){
	if(validateQuoteRequest()){
		outputAlert('brokerageSignupMsg', '<img src="../repository_images/spinner-alert.gif" alt="test" width="16" height="16" align="absmiddle" /> Sending quote request, please wait...');
		var brokerage = document.getElementById('brokerage').value;
		var contactname = document.getElementById('contactname').value;
		var phone = document.getElementById('brkphone').value;
		var email = document.getElementById('brkemail').value;
		var num_agents = document.getElementById('num_agents').value;
		var url = "../repository_queries/concierge-request-quote.php";
		var params = "brokerage="+brokerage+"&contactname="+contactname+"&phone="+phone+"&email="+email+"&num_agents="+num_agents;
		ajaxQuery(url, params, 'quoteRequestSent');
	}
}

function quoteRequestSent(){
	HidePopUp();
}

function showVideo(src){
	$(".popup-video").html('<iframe src="'+src+'" width="900" height="506" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>');
	$(".popup-video").fadeIn('slow');
	$(".modal-bg").fadeTo('slow', .7);
}
function hideVideo(){
	$(".popup-video").fadeOut('slow');
	$(".modal-bg").fadeOut('slow');
	$(".popup-video iframe").remove();
}

function agentWebsite(url){
	window.open(url);
}

