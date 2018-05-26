<?php
/**********************************************************************************************
Document: index.php
Creator: Brandon Freeman
Date: 04-13-11
Purpose: Tour windows designed for the iPad ... useful for other platforms too.
**********************************************************************************************/

//=======================================================================
// Header stuff for clearing cache - Good for AJAX and IE
//=======================================================================

	header("Expires: Sun, 19 Nov 1978 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();

	if( !ini_get('safe_mode') ){ 
		set_time_limit(0); 
	}
//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	require_once ('repository_inc/connect.php');
	//require_once ('../repository_inc/clean_query.php');

//=======================================================================
// Document
//=======================================================================

	// Start the session
	session_start();
	
	function typetabs($num) {
		for ($i = 0; $i < $num; $i++) {
			echo '----';
		}
	}
	
	function parsedir($dir) {
		if (is_dir($dir)) {
			$tally = 0;
			$files = scandir($dir);
			$images = array();
			echo '[' . $dir . ']<br />';
			
			if (is_numeric(basename($dir))) {
				$query = '
					SELECT DISTINCT mediaID
					FROM media
					WHERE mediaType = "photo"
					AND tourID = ' . basename($dir) . '
				';
				
				//echo $query . "<br />";
				$p = mysql_query($query) or die ("Query failed with error: " . mysql_error() . Chr(10) . "Query being run: " . $query . Chr(10));
				while ($photo = mysql_fetch_array($p)) {
					$images[intval($photo['mediaID'])] = true;
					//echo $photo['mediaID'] . '<br />';
				}
				
			} 
			
			foreach ($files as $file) {
					
				if (is_file($dir . '/' . $file) && strpos($file, '.jpg')) {
					$mediaid = intval(basename(substr($file, strripos($file, '_') + 1), ".jpg"));
					if (!isset($images[$mediaid])) {
						
						echo $dir . '/' .$file . ' (' . round(filesize($dir . '/' . $file)/1024/1024, 2) . 'MB) - ' . $mediaid . '<br />';
						$tally += filesize($dir . '/' . $file);
					}
				}
				
				if (is_dir($dir . '/' . $file) && strstr($file, ".") == false ) {
					$tally += parsedir($dir . '/' . $file);
				}
			}
		}
		return $tally;	
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
	<?php
		$dir = 'images/tours';
		echo 'LISTING TREE: ' . $dir . '<br />';
		echo round(parsedir($dir, 0)/1024/1024,2) . 'MB<br />';
	
	?>


</body>
</html>