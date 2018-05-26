// JavaScript Document

var numberOfMemberships = 0;
var totalMonth = 0.00;
var memberships = Array();
var membershipIDs = Array();
var membershipPrices = {};
var membershipNames = {};
$('.memberships input[type="checkbox"]').on("change", function(){
	var membershipID = $(this).parent().find('select').val();
	var checkedItemPrice = parseFloat($(this).parent().parent().find("input[type='number']").val());
	var checkedItemName = $(this).parent().find('select option:selected').text();
	if($(this).is(":checked")){
		if(membershipIDs.indexOf(membershipID)>-1){
			alert("You have already selected this membership!");
			$(this).prop('checked', false);
			return false;
		}
		numberOfMemberships++;
		// Add to membershipIDs array
		membershipIDs.push(membershipID);
		// Add to membershipPrices Object
		membershipPrices[membershipID] = checkedItemPrice;
		// Add to membershipNames Object
		membershipNames[membershipID] = checkedItemName;
	}else{
		numberOfMemberships--;
		// Remove from membershipIDs array
		var membershipIDIndex = membershipIDs.indexOf(membershipID);
		if(membershipIDIndex>-1){
			membershipIDs.splice(membershipIDIndex, 1);
		}
		// Remove from membershipPrices Object
		delete membershipPrices[membershipID];
		// Remove from membershipNames Object
		delete membershipNames[membershipID];
	}
	totalMonth = 0.00;
	$.each(membershipIDs, function(miIndx, membershipID) {
		totalMonth += membershipPrices[membershipID];
	});
	$(".totals .numb-memb").html(numberOfMemberships);
	$(".totals .monthly").html("$"+totalMonth.toFixed(2)+"mo");
});

$('.memberships input[type="number"]').on("blur", function(){
	var membCheckBox = $(this).parent().parent().parent().find('input[type="checkbox"]');
	if($(membCheckBox).is(":checked")){
		$(membCheckBox).prop("checked", false).trigger('change').prop("checked", true).trigger('change');
	}
});

function ajaxMessage(message, type){
	$('#ajaxMessage').find('div').fadeOut('slow', function() {
		$(this).remove();
	 });
	$('#ajaxMessage').prepend('<div class="ajaxMessage '+type+'">'+message+'</div>');
	$('#ajaxMessage .ajaxMessage.'+type).fadeIn('slow');
	if(type=="success"){
		$('#ajaxMessage .ajaxMessage').delay(2000).fadeOut('slow', function() {
			$(this).remove();
		});
	}
}

var userID = 0;
function createUser(){
	ajaxMessage('Loading/Creating User...', 'processing');
	var email = $("#email").val();
	var fullName = $("#fullname").val();
	var name = fullName.split(" ");
	var firstName = name[0];
	var lastName = name[1];
	var address = $("#address").val();
	var city = $("#city").val();
	var state = $("#state").val();
	var zipCode = $("#zip").val();
	var mls = $("#mlsid").val();
	var mlsProvider = 0;
	var otherBrokerage = $("#brokerage").val();
	var BrokerageID = 0;
	var phone = $("#phone").val();
	var otherMLSProvider = $("#mlsprovider").val();
	var userType = 'Agent';
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/users-create.php",
	  dataType: 'json',
	  data: { userType:userType, email: email, firstName: firstName, lastName: lastName, address: address, city: city, state: state, zipCode: zipCode, mls: mls, mls_provider: mlsProvider, otherBrokerage: otherBrokerage, BrokerageID: BrokerageID, phone: phone, otherMLSProvider: otherMLSProvider }
	}).done(function(response){
		console.log(response);
		if(response.success){
			userID = response.userID;
			ajaxMessage('User Created/Loaded, Processing Payment and Creating Memberships!', 'processing');
			chargeUserAndCreateMemberships();
		}else{
			ajaxMessage('Could not create/load user!', 'error');
		}
	});
}

function processOrder(){
	// First run validation on the form. I had to make a global variable for this script to see if validation passed...
	$("#membershipsForm").vindicate("validate");
	if(validationPassed){
		// Form passed validaion now let's make sure they selected a membership...
		console.log("validated!");
		if(membershipIDs.length>0){
			// Membership selected and form valid we may proceed...
		}else{
			// Membership not selected let them know and scroll to the membership selection portion of page...
			ajaxMessage('Please select a membership!', 'error');
			$("html, body").animate({ scrollTop: $("#choosePlan").offset().top }, "slow");
			return false;	
		}
	}else{
		// Form did not validate let them know and scroll to the top of form...
		console.log("not validated!");
		ajaxMessage('Please fill out all the required fields!', 'error');
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	}
	// Everything appears to be filled out ok, let's confirm we have what we want selected...
	var confirmOrderText = 'Are you sure you want to charge and sign this user up for the following: '+"\n";
	var firstMembConfirm = true;
	$.each(membershipIDs, function(miIndx, membershipID) {
		if(!firstMembConfirm){
			confirmOrderText += ', '+"\n";
		}
		confirmOrderText += 'Membership Name: '+membershipNames[membershipID]+' Price: $'+membershipPrices[membershipID];
		firstMembConfirm = false;
	});
	confirmOrderText += "\nTotal: $"+totalMonth+"mo";
	var confirmOrder = confirm(confirmOrderText);
	if(!confirmOrder){
		console.log('Did not confirm!');
		return false;
	}
	console.log('Confirmed order!');
	// We are good to go let's start with creating or getting the user...
	createUser();
}

function chargeUserAndCreateMemberships(){
	var membFrequency = 30;
	var membTotal = totalMonth.toFixed(2);
	var cardNumber = $("#cardnumber").val();
	var cardYear = $("#cardyear").val();
	var cardMonth = $("#cardmonth").val();
	var fullName = $("#fullname").val();
	var address = $("#address").val();
	var city = $("#city").val();
	var state = $("#state").val();
	var zip = $("#zip").val();
	var email = $("#email").val();
	var phone = $("#phone").val();
	var brokerage = $("#brokerage").val();
	var memberships = Array();
	$.each(membershipIDs, function(miIndx, membershipID) {
		memberships.push({id: membershipID, price: membershipPrices[membershipID], userType: 'user', userID: userID});
	});
	var memberFormData = { address: address, city: city, state: state, zip: zip, userID: userID, membFrequency: membFrequency, membTotal: membTotal, cardNumber: cardNumber, cardYear: cardYear, cardMonth: cardMonth, fullName: fullName, email:email, phone: phone, brokerage: brokerage, memberships: memberships };
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/membertransactions-process.php",
	  dataType: 'json',
	  data: memberFormData
	}).done(function( response ) {
		if(response.success){
			ajaxMessage('Success! User charged and memberships created!', 'processing');
			var confirmOrderText = 'The user has been created and charged for the following memberships: '+"\n";
			var firstMembConfirm = true;
			$.each(membershipIDs, function(miIndx, membershipID) {
				if(!firstMembConfirm){
					confirmOrderText += ', '+"\n";
				}
				confirmOrderText += 'Membership Name: '+membershipNames[membershipID]+' Price: $'+membershipPrices[membershipID];
				firstMembConfirm = false;
			});
			confirmOrderText += "\n Total Charged: $"+membTotal+"mo";
			confirmOrderText += "\n User ID: "+userID;
			confirmOrderText += "\n Member Transaction ID: "+response.memberTransID+" (this ID can be used to lookup all details for this order)";
			confirmOrderText += "\n Infusionsoft Contact ID: "+response.isContactID;
			confirmOrderText += "\n Press OK to refresh this page and start a new order. Press cancel to keep this page with current information (If you run this order again it will charge the user twice. You can start fresh by simply refreshing this page though.)";
			var confirmOrder = confirm(confirmOrderText);
			if(confirmOrder){
				location.reload();
			}else{
				// Do nothing. Stay on page and do not start fresh.
			}
		}else{
			ajaxMessage('Transactions failed! Here is the error: '+response.error, 'error');
		}
	});
}

$("#membershipsForm").vindicate("init");