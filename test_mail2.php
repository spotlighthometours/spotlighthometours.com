<?php
	require 'repository_inc/classes/inc.global.php';
	
		//callURL(
		$url = "http://spotlighthometours.com/test_mail.php?email=" 
			. urlencode($_GET['email']) 
			. "&subject=" . urlencode("Tour updated") 
			. "&body=" . urlencode("The tour " . $_GET['tourId'] 
			. " has been converted to the new tour window") 
		;
		//);
var_dump($url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,TRUE);
		curl_exec($ch);
		curl_close($ch);


//mail($_GET['email'],$_GET['subject'],$_GET['body']);

?>