// JavaScript Document

$(document).ready(
	function() {
		GetWaitScreen();
		GetCompleteScreen();
		order.title = "Tour Demo";
		order.price = 0.00;
		order.sqft = 0;
		order.beds = 0;
		order.baths = 0;
		order.address = "Tour Demo";
		order.state = "UT";
		order.city = "Salt Lake City";
		order.zip = "84106";
		ChangeStep(4);
		PopulateCheckout();
		document.getElementById('pf_4').style.display = 'none';
		document.getElementById('form_billing_info').style.display = 'block';
		document.getElementById('pop_up_content').style.display = 'block';
		GetOrderTotal();
	}
);