<?php
/**********************************************************************************************
Document: checkout_subform_virtual_staging_slider.php
Creator: Brandon Freeman
Date: 02-18-11
Purpose: Slider subform for virtual staging.
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

	if (isset($_POST['style'])) {
		$style = CleanQuery($_POST['style']);
	} elseif (isset($_GET['style'])) {
		$style = CleanQuery($_GET['style']);
	}
	
	if (isset($_POST['room'])) {
		$room = CleanQuery($_POST['room']);
	} elseif (isset($_GET['room'])) {
		$room = CleanQuery($_GET['room']);
	}
	
	if (isset($_POST['index'])) {
		$index = $_POST['index'];
	} elseif (isset($_GET['index'])) {
		$index = $_GET['index'];
	}
	
	if ( isset($style) && isset($index) ) {
		$query = "SELECT set_image, set_description FROM vs_designsets WHERE room_name = '" . $room . "' AND style_name = '" . $style . "'";
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error());
		
		$count = 0;
		while($result = mysql_fetch_array($r)){
			$slides .= '
				<img id="' . $index . '-sl_img-' . $count . '" class="form_slider_photo photo_deselected" src="' . $result['set_image'] . '" onclick="SelectPhoto(' . $index . ', ' . Chr(39) . $index . '-sl_img-' . $count . Chr(39) . ');" />
				<input id="' . $index . '-sl_img-' . $count . '-desc" type="hidden" value="' . $result['set_description'] . '" />
			';
			$count++;
		}
		
		echo '<input id="' . $index . '-room" type="hidden" value="' . $room . '" />';
		echo '<input id="' . $index . '-style" type="hidden" value="' . $style . '" />';
		echo '<div id="' . $index . '-photoslider" class="form_slider_photo_frame" style="width: ' . ($count * 276) . 'px;" >';
		echo $slides;
		echo '</div>';
		echo '<input id="' . $index . '-imagecount" type="hidden" value="' . $count . '" />';
		echo '<input id="' . $index . '-sliderwidth" type="hidden" value="' . ($count * 276) . '" />';
		
	} else {
		echo 'Not Enough Information';
	}
	
?>