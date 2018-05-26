<?php
	require_once ('data.php');
	if ($dbc = @mysql_connect ($server, $username, $password)) {
		if (!@mysql_select_db ($database)) {
			die ('<p>Could not select the database because: <b>' . mysql_error() . '</b></p>');
		}
	} else {
		die ('<p>Could not connect to MySQL because: <b>' . mysql_error() . '</b></p>');
	}
?>