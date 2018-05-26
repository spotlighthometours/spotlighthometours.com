<?php
/**********************************************************************************************
Document: test_order.php
Creator: Brandon Freeman
Date: 03-01-11
Purpose: Test the merchant account.
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();
	
//=======================================================================
// Includes
//=======================================================================

	// Merchant stuff
	include "../repository_inc/lphp.php";

//=======================================================================
// Document
//=======================================================================
	 $mylphp=new lphp;

	$myorder["host"]       = "secure.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "/repository_inc/896176.pem";  # Change this to the name and location of your certificate file 
	$myorder["configfile"] = "896176";   

	$myorder["ordertype"]    = "SALE";
	$myorder["result"]       = "LIVE"; # For a test, set result to GOOD, DECLINE, or DUPLICATE
	$myorder["cardnumber"]   = "4111-1111-1111-1111";
	$myorder["cardexpmonth"] = "01";
	$myorder["cardexpyear"]  = "05";
	$myorder["chargetotal"]  = "9.99";

	$myorder["addrnum"]   = "123";   # Required for AVS. If not provided, transactions will downgrade.
	$myorder["zip"]       = "12345"; # Required for AVS. If not provided, transactions will downgrade.
	$myorder["debugging"] = "true";  # for development only - not intended for production use


  # Send transaction. Use one of two possible methods  #
//	$result = $mylphp->process($myorder);		# use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods
	
	print_r($result);
	echo '<br />';
	
	if ($result["r_approved"] != "APPROVED")	// transaction failed, print the reason
	{	
		print "Status: $result[r_approved]<br />";
		print "Error: $result[r_error]<br />";
	}
	else
	{	// success
		print "Status: $result[r_approved]<br />";
		print "Code: $result[r_code]<br />";
		print "OID: $result[r_ordernum]<br /><br />";
	}

/*
	// Look at returned hash & use the elements you need //
	while (list($key, $value) = each($result))
	{
		echo "$key = $value<br />";

	#if you're in web space, look at response like this:
		 echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "<BR><br />";
	}
*/
?>