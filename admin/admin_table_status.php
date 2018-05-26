<?php
/**********************************************************************************************
Document: admin_table_status.php
Creator: Brandon Freeman
Date: 05-19-11
Purpose: Show the table information ... good times.
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
	require_once ('../repository_inc/clean_query.php');
	
//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$debug = false;
	
	// Require Admin Login
	if (!$debug) {
		require_once ('../repository_inc/require_admin.php');
	}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Admin - Table Status</title>
<link type="text/css" href="../repository_css/admin.css" rel="stylesheet" />
</head>
<?php
	//http://firsttube.com/read/sorting-a-multi-dimensional-array-with-php/
	function subval_sort($a,$subkey) {
		foreach($a as $k=>$v) {
			$b[$k] = strtolower($v[$subkey]);
		}
		asort($b);
		foreach($b as $key=>$val) {
			$c[] = $a[$key];
		}
		return $c;
	}
	
	$query =   'SHOW TABLE STATUS';
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
	$dataz = array();
	$count = 0;
	while($result = mysql_fetch_array($r)) {
		$dataz[$count]['Name'] = $result['Name'];
		$dataz[$count]['Rows'] = $result['Rows'];
		$dataz[$count]['Data_length'] = $result['Data_length'];
		$count ++;
	}
	
	$dataz = subval_sort($dataz,'Data_length'); 
	
	echo '
		<table>
			<tr>
				<th>Name</th>
				<th>Rows</th>
				<th>Size</th>
			</tr>
	';
	$highlight = true;
	foreach ($dataz as $data) {
		if ($highlight) {
			$class = "highlight";
		} else {
			$class = "nohighlight";
		}
		$highlight = !$highlight;
		
		echo '
			<tr class="' . $class . '" >
				<td>' . $data['Name'] . '</td>
				<td>' . $data['Rows'] . '</td>
				<td>' . round($data['Data_length']/1024/1024,2) . 'MB</td>
			</tr>
		';
	}
	
	echo '
		</table>
	';
	
?>
<body>
</body>
</html>