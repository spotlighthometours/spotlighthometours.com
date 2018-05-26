<?php
/**********************************************************************************************
Document: checkout_subform_virtual_staging_styles.php
Creator: Brandon Freeman
Date: 03-07-11
Purpose: Subform for virtual staging.
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
	
	if (isset($_POST['index'])) {
		$index = $_POST['index'];
	} elseif (isset($_GET['index'])) {
		$index = $_GET['index'];
	}
	
	if (isset($_POST['room'])) {
		$room = $_POST['room'];
	} elseif (isset($_GET['room'])) {
		$room = $_GET['room'];
	}
	
	$query = "SELECT DISTINCT style_name FROM vs_designsets WHERE room_name = '" . $room . "'";
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error());
	$count = 0;
	while($result = mysql_fetch_array($r)){
		echo '
										<div id="' . $index . '-step2-' . $count . '" class="form_select_line form_select_line_deselected" onclick="SelectStyle(' . $index . ', ' . Chr(39) . '' . $index . '-step2-' . $count . Chr(39) . ');" >' . $result['style_name'] . '</div>
		';
		$count++;
	}
	
	echo '
										<input id="' . $index . '-styles" type="hidden" value="' . $count . '" />
	';
	
?>