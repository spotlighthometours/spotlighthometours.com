<?php
/**************************************************************************************************************
Document: functions.php
Creator: Brandon Freeman
Date: 06-01-07
Purpose: Public functions for all.
***************************************************************************************************************/

// Connects to the database.
function dbconnect() {
	include ('data.php');
	if ($dbc = @mysql_connect ($server, $username, $password)) {
		if (!@mysql_select_db ($database)) {
			die ('<p>Could not select the database because: <b>' . mysql_error() . '</b></p>');
		}
			
	} else {
		die ('<p>Could not connect to MySQL because: <b>' . mysql_error() . '</b></p>');
	}
}

//Start a session.
function start_session() {
	session_start();
}

//Stop a session.
function stop_session() {
	unset ($_SESSION);
	session_destroy();
}

// Ticker function to count visits to each document.
function clicker() {
	dbconnect();
	
	$query = 'SELECT * FROM stats WHERE document="' . $_SERVER['PHP_SELF'] . '" LIMIT 1';
	$table = mysql_fetch_array (mysql_query ($query));
	
	if (empty ($table)) {
		$query = 'INSERT INTO stats (document, last_open) VALUES ("' . $_SERVER['PHP_SELF']. '", "' . strtotime("now") . '")';
		if (!@mysql_query ($query)) {
			print '<p>Could not create because: <b>' . mysql_error() . '</b></p><p>The query being run was: <b>' . $query . '</b></p>';
		}
	}
	
	$query = 'UPDATE stats SET count=' . $table['count'] . '+1, last_open=' . strtotime("now") . ', reset_count=' . $table['reset_count'] . '+1 WHERE document="' . $_SERVER['PHP_SELF'] . '"';
	if (!@mysql_query ($query)) {
		print '<p>Could not update because: <b>' . mysql_error() . '</b></p><p>The query being run was: <b>' . $query . '</b></p>';
	}
	
	mysql_close();
}


// Removes an item from an array.
function array_remval($i,&$arr){
  $arr=array_merge(array_slice($arr, 0,$i), array_slice($arr, $i+1));
}

function arrayIndexOf($needle, $haystack) {                // conversion of JavaScripts most awesome
	for ($i=0;$i<count($haystack);$i++) {         // indexOf function.  Searches an array for
		if ($haystack[$i] == $needle) {       // a value and returns the index of the *first*
			return $i;                    // occurance
		}
	}
	return null;
}

//require access level
function access($i) {
	if ($_SESSION['access'] < $i) {
		go('login.php?f=denied');
	}
}

//redirect browser
function go($i) {
      ob_start();
      header('Location: ' . $i);
      ob_flush();
}

//Formats data sizes from bytes.
function format_size($size, $round = 0) {
    //Size must be bytes!
    $sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    for ($i=0; $size > 1024 && isset($sizes[$i+1]); $i++) $size /= 1024;
    return round($size,$round).$sizes[$i];
}

//Cleans tags ,slashes, and other bad things out of an input.
function clean_input($input) {
    $input = @strip_tags($input);
    $input = @stripslashes($input);
    return $input;
}

?>