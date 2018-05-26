<?php
/**********************************************************************************************
Document: new-broker.php
Creator: Jacob Edmond Kerr
Date: 03-05-12
Purpose: SpotlightHomeTours.com - New Broker
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	$title = 'Spotlight | New Brokerage';
	$header = '<link rel="stylesheet" type="text/css" href="repository_css/splash.css"/>
<link rel="stylesheet" type="text/css" href="repository_css/contact.css"/>
<script src="repository_inc/new-broker.js"></script>
';
	require_once('repository_inc/template-header.php');
	
//=======================================================================
// Objects
//=======================================================================

	

//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="page-intro">
		<h1>New Brokerages</h1>
		<p>Brokerages may be eligible for exclusive products and pricing. If you fill out the questionnaire below you will be contacted by one of our representatives and presented with special offers designed to fit your brokerage's needs. If you need to talk to someone right away then please feel free to give us at call at: <?PHP echo COMPANY_1800; ?></p>
		<div class="clear"></div>
	</div>
	<div class="image-reel-pattern"></div>
	<div class="direction" id="direction">Scroll down to view the contact form</div>
	<div class="page-content">
		<div class="main-column left">
			<div class="details">
			<div class="questionnaire-form">
			<h2 class="strip-top-marg">Brokerage Questionnaire</h2>
			<hr class="strip-top-marg" />
				<div id="questionnaireMsg"></div>
				<form onsubmit="return false;" name="questionnaire">
					<table border="0" cellspacing="0" cellpadding="10">
						<tr>
							<td align="right">Your name</td>
							<td><input name="users_name" type="text" value="" /><div class="required">required</div></td>
						</tr>
						<tr>
							<td align="right">Brokerage name</td>
							<td><input name="brokerage" type="text" value="" /><div class="required">required</div></td>
						</tr>
						<tr>
							<td align="right">Phone</td>
							<td><input name="phone" type="text" value="" /><div class="required">required</div></td>
						</tr>
						<tr>
							<td align="right">Email</td>
							<td><input name="email" type="text" value="" /><div class="required">required</div></td>
						</tr>
						<tr>
							<td align="right">How many agents are in your brokerage?</td>
							<td><input name="number_of_agents" type="text" value="" /></td>
						</tr>
						<tr>
							<td align="right">What is the average number of listings produced per month?</td>
							<td><input name="listings_per_month" type="text" value="" id="name" /></td>
						</tr>
						<tr>
							<td align="right">On the average, how many listings does your brokerage sell per month?</td>
							<td><input name="sales_per_month" type="text" value="" /></td>
						</tr>
						<tr>
							<td align="right">Do you currently offer marketing solutions for your agents?</td>
							<td>
								<select name="offers_marketing">
									<option value="no">No</option>
									<option value="yes">Yes</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right">Questions / Comments</td>
							<td><textarea name="message" rows="5"></textarea></td>
						</tr>
						<tr>
							<td align="right">Please select some of the products you may be interested in</td>
							<td>
								<div class="left" style="width:50%"><input class="checkbox" type="checkbox" name="product[]" value="Photo Tours" /> Photo Tours<br/><input type="checkbox" name="product[]" value="Cinematic Video Tours" class="checkbox" /> Cinematic Video Tours<br/><input type="checkbox" name="product[]" value="3D Motion Tours" class="checkbox" /> 3D Motion Tours<br/><input type="checkbox" name="product[]" value="Mobile Preview" class="checkbox" /> Mobile Preview<br/><input type="checkbox" name="product[]" value="Agent Sites" class="checkbox" /> Agent Sites</div>
								<div class="left" style="width:50%"><input class="checkbox" type="checkbox" name="product[]" value="Virtual Staging" /> Virtual Staging<br/><input type="checkbox" name="product[]" value="Do-it-Yourself" class="checkbox" /> Do-it-Yourself<br/><input type="checkbox" name="product[]" value="Micro Sites" class="checkbox" /> Micro Sites<br/><input type="checkbox" name="product[]" value="Concierge" class="checkbox" /> Concierge</div>
							</td>
						</tr>
						<tr>
						<td align="right"> Prove you're<br />
not a robot </td>
							<td align="left">
<?php
          require_once('repository_inc/recaptchalib.php');
          $publickey = "6Lc2Vc4SAAAAADld4t3q552TAWnGG8sCJ6X5xAQz";
          echo recaptcha_get_html($publickey);
        ?>
<div class="required">required</div></td>
						</tr>
					</table>
					<hr class="strip-top-marg" />
					<div align="right"><input onClick="validateQuestionnaire('questionnaire')" type="submit" class="submit" value="Send" /></div>
				</form>
				</div>
				<div class="success-message">
					<h2>Questionnaire sent!</h2>
					<hr class="strip-top-marg"/>
					<p>One of our representatives will be looking over the information you have submitted and contacting you soon. With the information you have submitted our representative will be able to generate exclusive products and pricing for your brokerage. If you need to speak to a representative now then please feel free to give us a call at: <strong><?PHP echo COMPANY_PHONE; ?></strong></p>
				</div>
			</div>
		</div>
		<div class="side-column right">
<?PHP
	include("repository_inc/template-side-column.php");
?>
		</div>
		<div class="clear"></div>
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('repository_inc/template-footer.php');
?>