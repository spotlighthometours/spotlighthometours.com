<?php
	// Radomize the page the user will get. This is for multi variant testing
	$mvPages = array(
		'concierge-checkout-v2.php',
		'concierge-checkout-v3.php'
	);
	$randomPage = $mvPages[array_rand($mvPages,1)];
	header("Location: https://www.spotlighthometours.com/concierge/".$randomPage);	 
?>