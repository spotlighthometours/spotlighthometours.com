<?PHP
/**********************************************************************************************
Document: checkout_cities.php
Creator: Brandon Freeman
Date: 02-14-11  (Happy Valentines Day!)
Purpose: Returns cities by zip. (for Ajax request)  
Notes: Accepts post or get for 'zip'.
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

	// Connect to MySQL
	require_once ('../repository_inc/connect.php');
	// Connect to MySQL
	require_once ('../repository_inc/clean_query.php');
	
//=======================================================================
// Document
//=======================================================================
		
	// Clean the input for zip.
	// SQL injection BAD!
	$zip = "";
	if (isset($_POST['zip'])) {
		$zip = CleanQuery($_POST['zip']);
	} elseif (isset($_GET['zip'])) {
		$zip = CleanQuery($_GET['zip']);
	}
	
	// Ask for the admin id associated with the info.
	$query = "SELECT city FROM zip_code WHERE zip_code = '" . $zip . "'";
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
	$first = true;
	while($result = mysql_fetch_array($r)){
		if (!$first) {
			echo ",";
		} else {
			$first = !$first;
		}
		echo $result['city'];
	}
?>