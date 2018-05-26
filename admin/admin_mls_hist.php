<?php
/**********************************************************************************************
Document: admin_mls_hist.php
Creator: Brandon Freeman
Date: 06-23-11
Purpose: Show the mls history for a particular tour id.
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

	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	
	// Create instances of needed objects
	$mls = new mls();

	// Connect to MySQL
	if (!isset($dbc)) {
		require_once ('../repository_inc/connect.php');
		require_once ('../repository_inc/clean_query.php');
	}
	
//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$debug = true;
	
	// Require Admin Login
	if (!$debug) {
		require_once ('../repository_inc/require_admin.php');
	}
	
	$tourid = "";
	if (isset($_POST['tourid'])) {
		$tourid = CleanQuery($_POST['tourid']);
	} elseif (isset($_GET['tourid'])) {
		$tourid = CleanQuery($_GET['tourid']);
	}
	
	$query = '
		SELECT tml.*, t.address FROM tour_mls_log tml
		LEFT JOIN tours t ON tml.tourid = t.tourId
		WHERE tml.tourid = "' . $tourid . '"
		ORDER BY tml.tourid, tml.mls, tml.entered DESC
	';
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query: " . $query);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - MLS Log</title>
        <link type="text/css" href="../repository_css/admin.css" rel="stylesheet" />
    </head>
    <body>
   		<table style="width: 800px;" >
            <tr>
            	<th>Tour ID</th>
                <th>Address</th>
                <th>MLS</th>
				<th>Provider</th>
                <th>Action</th>
                <th>Entered</th>
            </tr>
<?php
	$highlight = false;
	while ($result = mysql_fetch_array($r)) {
		if ($highlight) {
			$class = "highlight";
		} else {
			$class = "";
		}
		$highlight = !$highlight;
		if($mls->loadProvider($result['mls_provider'])){
			$provider = $mls->provider['name'];
		}else{
			$provider = "Other";
		}
		echo '
			<tr class="' . $class . '" >
				<td>' . $result['tourid'] . '</td>
				<td>' . $result['address'] . '</td>
				<td>' . $result['mls'] . '</td>
				<td>' . $provider . '</td>
				<td>' . $result['action'] . '</td>
				<td>' . date("F j, Y, g:i a", strtotime($result['entered'])) . '</td>
			</tr>
		
		';
	}
?>
        </table>
    </body>
</html>