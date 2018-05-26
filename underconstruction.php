<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />	
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="http://www.creativecrunk.com/html/BehindMe/css/style.css">
	<link href="http://www.creativecrunk.com/html/BehindMe/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<script src="http://www.creativecrunk.com/html/BehindMe/js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="http://www.creativecrunk.com/html/BehindMe/js/jquery.shuffleLetters.js"></script>
	<script language="Javascript" type="text/javascript" src="http://www.creativecrunk.com/html/BehindMe/js/jquery.lwtCountdown-1.0.js"></script>
	<script language="Javascript" type="text/javascript" src="http://www.creativecrunk.com/html/BehindMe/js/misc.js"></script>
	<link rel="Stylesheet" type="text/css" href="http://www.creativecrunk.com/html/BehindMe/css/main.css"></link>
	<script>
	/*timer setting*/
		var end_day=1;	/* Day of end date.*/
		var end_month=8;	/*Month of end date .*/
		var end_year=2017; /* Year of end date.*/
		var end_hour=21; /* Hour of end date.*/
		var end_min=0; /* Min of end date.*/
		var end_sec=0; /* Second of end date.*/
		/*Social setting*/
		var facebook="http://www.facebook.com";  /* Replace with you Facebook page url*/
		var twitter="http://www.twitter.com";  /* Replace with you Twitter page url*/
		var google="http://www.google.com";   /* Replace with you Google page url*/
	</script>
	<style>
		body, html{
			background: url(spotlight-water-mrk.gif) no-repeat fixed center center #0b0b0b;
		}
	</style>
<head>
<body>
	<div id="front">
				<div class="wrap">
					<div id="logo" style="margin-top:50px;">
						
					</div><!--logo-->
					<div id="intro">
						<h1><span class="shuffle">WILL BE BACK </span><span style="color: yellow;">SOON!</span></h1>
						<p style="font-size:20px;margin-left:50px;margin-right:50px">In order to improve our website performance and operation we are moving it to a more superior network. Unfortunately this move requires some down time. The expected downtime is from 7:00pm - 9:00pm MST. <strong>We will be back up soon:</strong></p>
					</div><!--intro-->
						<!-- Countdown dashboard start -->
						<div id="countdown_dashboard">
						<!--<div class="dash weeks_dash">
								<span class="dash_title">weeks</span>
								<div class="digit">0</div>
								<div class="digit">0</div>
							</div>-->

							<div class="dash days_dash">
								<span class="dash_title">days</span>
								<div class="digit">0</div>
								<div class="digit">0</div>
							</div>

							<div class="dash hours_dash">
								<span class="dash_title">hours</span>
								<div class="digit">0</div>
								<div class="digit">0</div>
							</div>

							<div class="dash minutes_dash">
								<span class="dash_title">minutes</span>
								<div class="digit">0</div>
								<div class="digit">0</div>
							</div>

							<div class="dash seconds_dash">
								<span class="dash_title">seconds</span>
								<div class="digit">0</div>
								<div class="digit">0</div>
							</div>

						</div>
						<!-- Countdown dashboard end -->
				</div><!--wrap-->
	</div><!--front-->
		<script src="http://www.creativecrunk.com/html/BehindMe/js/main.js"></script>	
		<script type="text/javascript" src="http://www.creativecrunk.com/html/BehindMe/js/form-validate/jquery.validate.min.js"></script>
		<script type="text/javascript" src="http://www.creativecrunk.com/html/BehindMe/js/form-validate/additional-methods.min.js"></script>	
			
		<script language="javascript" type="text/javascript">

		function resizer(){
			var width=$(window).width();
				var height=$(window).height();
				if(height < width){
					$('body').css('font-size','1vh');
					//alert('height');
				}
				else{
				$('body').css('font-size','1vw');
				//alert('width');
				}
		}// resizer
		jQuery(window).resize(function() {
		
		resizer();
		});
			jQuery(document).ready(function() {
			
			/*countdown timer*/
				$('#countdown_dashboard').countDown({
					targetDate: {
						'day': 	end_day,
						'month': end_month,
						'year': end_year,
						'hour': end_hour,
						'min': end_min,
						'sec': end_sec
					},
					omitWeeks: true
				});
				
				$('#email_field').focus(email_focus).blur(email_blur);
				$('#subscribe_form').bind('submit', function() { return false; });
				resizer();
				/*suffle text*/
				$(".shuffle").shuffleLetters();
				/*-------------------Socila ----------------*/
				$('.twitter').click(function(){
					window.open(twitter, '_blank');
					event.preventDefault();
				});
				$('.facebook').click(function(){
					window.open(facebook, '_blank');
					event.preventDefault();
				});
				$('.google').click(function(){
					window.open(google, '_blank');
					event.preventDefault();
				});
				
			}); /*ends doc ready*/

		</script>		
		
</body>
</html>
