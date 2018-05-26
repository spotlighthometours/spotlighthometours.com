<?php
/**********************************************************************************************
Document: contact-us.php
Creator: Jacob Edmond Kerr
Date: 02-29-12
Purpose: SpotlightHomeTours.com - Contact Us
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	$title = 'Contact Spotlight Home Tours';
	$header = '<link rel="stylesheet" type="text/css" href="../repository_css/splash.css"/>
<link rel="stylesheet" type="text/css" href="../repository_css/contact.css"/>
<script src="../repository_inc/contact.js"></script>
';
	require_once('../repository_inc/template-header.php');
	
//=======================================================================
// Objects
//=======================================================================

	

//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="page-intro">
		<h1><img src="../repository_images/new/contact-us.png" alt="Contact Us" width="237" height="318" align="right" />Contact Us</h1>
		<p>Please feel free to contact us with any questions, comments or inquiries. You may contact us by using the contact information or form below.</p>
		<p><address><strong>Spotlight Home Tours</strong><br/>
		<?PHP echo COMPANY_ADDRESS; ?><br/>
		<?PHP echo COMPANY_CITY; ?>, <?PHP echo COMPANY_STATE; ?> <?PHP echo COMPANY_ZIP; ?></address></p>
		<p><strong>Toll-Free:</strong> <?PHP echo COMPANY_1800; ?><br/>
		<strong>Customer Support:</strong> <?PHP echo COMPANY_SUPPORT_PHONE; ?><br/>
		<strong>Office:</strong> <?PHP echo COMPANY_PHONE; ?>
		</p>
		<div class="clear"></div>
	</div>
	<div class="image-reel-pattern"></div>
	<div class="direction" id="direction">Scroll down to view the contact form</div>
	<div class="page-content">
		<div class="main-column left">
			<div class="details">
			<div class="contact-form">
			<h2 class="strip-top-marg">Send us an email</h2>
			<hr class="strip-top-marg" />
				<div id="contactMsg"></div>
				<form onsubmit="return false;" name="contact">
					<table border="0" cellspacing="0" cellpadding="10">
						<tr>
							<td align="right">Send to</td>
							<td>
								<select name="to">
                                	<option value="support@spotlighthometours.com" <?PHP echo ($_REQUEST['department']=="support"?'selected="selected"':''); ?>>Support</option>
									<option value="info@spotlighthometours.com" <?PHP echo ($_REQUEST['department']=="sales"?'selected="selected"':''); ?>>Information / Sales</option>
									<option value="billing@spotlighthometours.com" <?PHP echo ($_REQUEST['department']=="billing"?'selected="selected"':''); ?>>Billing</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right">Name</td>
							<td><input name="name" type="text" value="" id="name" /><div class="required">required</div></td>
						</tr>
						<tr>
							<td align="right">Phone</td>
							<td><input name="phone" type="text" value="" /></td>
						</tr>
						<tr>
							<td align="right">Email</td>
							<td><input name="email" type="text" value="" />
							<div class="required">required</div></td>
						</tr>
						<tr>
							<td align="right">Message</td>
							<td><textarea name="message" rows="5"></textarea>
							<div class="required">required</div></td>
						</tr>
						<tr>
						<td align="right"> Prove you're<br />
not a robot </td>
							<td align="left">
<?php
          require_once('../repository_inc/recaptchalib.php');
          $publickey = "6Lc2Vc4SAAAAADld4t3q552TAWnGG8sCJ6X5xAQz";
          echo recaptcha_get_html($publickey);
        ?>
<div class="required">required</div></td>
						</tr>
					</table>
					<hr class="strip-top-marg" />
					<div align="right"><input onClick="validateContact('contact')" type="submit" class="submit" value="Send" /></div>
				</form>
				</div>
				<div class="success-message">
					<h2>Email sent!</h2>
					<hr class="strip-top-marg"/>
					<p>Thank you for contacting us. We will be in touch with you shortly. If you need immediate assistance then please feel free to give us a call using the phone number posted above.</p>
					<a class="v2button" href="index.php">Send Another Email</a>
				</div>
			</div>
		</div>
		<div class="side-column right">
<?PHP
	include("../repository_inc/template-side-column.php");
?>
		</div>
		<div class="clear"></div>
	</div>
<?PHP
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>