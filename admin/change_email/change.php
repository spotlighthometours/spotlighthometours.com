<?php
/**********************************************************************************************
Document: change.php
Creator: Brandon Freeman
Date: 02-05-11
Purpose: Update emails from a csv file.  
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

ini_set ("display_errors", 1);
error_reporting (E_ALL & ~E_NOTICE);
ob_start();

//=======================================================================
// Includes
//=======================================================================

// Connect to MySQL
require_once "inc/connect.php";

//=======================================================================
// Document
//=======================================================================

// This script is probably going to take a bit ... better turn off execution time.
if( !ini_get('safe_mode') ){ 
	set_time_limit(0); 
}

$file_name = 'emails.csv';
$email_suffix = "pureutah.com";
$count = 1;
/*$query = 'SELECT firstName, lastName FROM users WHERE userType="Agent"';
$r = mysql_query($query) or die('Query failed with error: ' . mysql_error() . '<br />');
$first = true;
while($result = mysql_fetch_array($r)){
	if (!$first) {
		echo ',';
	} else {
		$first = !$first;
	}
	echo $result["firstName"] . ' ' . $result["lastName"];
}*/

$file_array = file($file_name);
echo '<table border=2>
		<tr>
			<th></th>
			<th>fname</th>
			<th>lname</th>
			<th>new email</th>
			<th>db_username</th>
			<th>db_email</th>
			<td></td>
		</tr>';
		
foreach ($file_array as $line)
{
	$data = explode(",", $line, 2);
	$name = explode(" ", $data[0], 2);
	$email = explode("@", $data[1], 2);
	
	$query = 'SELECT firstName, lastName, username, email FROM users WHERE username = "' . $email[0] . '@' . $email_suffix . '" OR email = "' . $email[0] . '@' . $email_suffix . '" LIMIT 1';
	$r = mysql_query($query) or die('Query failed with error: ' . mysql_error() . '<br />');
	while($result = mysql_fetch_array($r)){
		echo "<tr>";
		echo "<td>" . $count . "</td>";
		echo "<td>" . $name[0] . "</td>";
		echo "<td>" . $name[1] . "</td>";
		echo "<td>" . $data[1] . "</td>";
		//echo "<td>" . $result["firstName"] . "</td>";
		//echo "<td>" . $result["lastName"] . "</td>";
		echo "<td>" . $result["username"] . "</td>";
		echo "<td>" . $result["email"] . "</td>";
		echo "<td>";
		
		//$query = 'UPDATE users SET username = "' . $data[1] . '", email = "' . $data[1] . '" WHERE username = "' . $email[0] . '@' . $email_suffix . '" OR email = "' . $email[0] . '@' . $email_suffix . '"';
		$query = 'UPDATE users SET username = "' . trim($data[1]) . '", email = "' . trim($data[1]) . '" WHERE username = "' . $email[0] . '@' . $email_suffix . '" OR email = "' . $email[0] . '@' . $email_suffix . '"';
		$ret = mysql_query($query) or die('Query failed with error: ' . mysql_error());

		echo $ret . "</td>";
		echo '</tr>';
		$count++;
	}
}
echo '</tr></table>';
?>