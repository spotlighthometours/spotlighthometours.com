<?php
/**********************************************************************************************
Document: admin_brokerages_types.php
Creator: Brandon Freeman
Date: 05-16-11
Purpose: Lists products or tours in a nice form.
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
	
	if (isset($_POST['type'])) {
		$type = CleanQuery($_POST['type']);
	} elseif (isset($_GET['type'])) {
		$type = CleanQuery($_GET['type']);
	}
	
	if (isset($_POST['name'])) {
		$name = CleanQuery($_POST['name']);
	} elseif (isset($_GET['name'])) {
		$name = CleanQuery($_GET['name']);
	}
	
	
	if (isset($type) && isset($name)) {
		$query = '';
		
		if ($type == "tour") {
			$query = 'SELECT tourTypeID AS id, tourTypeName AS name FROM tourtypes ORDER BY tourTypeName';	
		} elseif ($type == "product") {
			$query = 'SELECT productID AS id, productName AS name FROM products WHERE productName IS NOT NULL ORDER BY productName';	
		} elseif ($type == "concierge") {
			$query = 'SELECT id, name FROM memberships WHERE concierge <> 0 ORDER BY concierge';	
		}
		
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		
		echo '
		<div class="formrow" style="height:145px;">
			<div class="row r_name" >Item</div>
			<div class="row r_content" style="height:145px;">
				<select id="' . $name . '" name="' . $name . '[]" multiple="multiple" size="8">
		';	
		// <select id="' . $name . '" name="' . $name . '" class="input mid" multiple="multiple">
		while($result = mysql_fetch_array($r)) {
			echo '<option value="' . $result['id'] . '" >' . $result['name'] . '</option>	';	
		}
		
		echo '
				</select>
			</div>
		</div>
		';
	}
?>