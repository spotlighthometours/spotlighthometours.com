<?PHP
/**********************************************************************************************
Document: clean-files
Creator: Jacob Edmond Kerr
Date: 04-07-14
Purpose: This script is for helping free up some space on the server by doing things like: deleting images and videos that are not tied to any tours in the DB. Deleting inactive images that were uploaded x years ago.
Notes: 
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Global Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');
	showErrors();
	
//=======================================================================
// Document
//=======================================================================

	$tourImgDir = "../images/tours/";
	$tourImgDirFolder = scandir($tourImgDir);
	echo '<ul>';
	$count = 0;
	foreach($tourImgDirFolder as $index => $folderName){
		if(is_numeric($folderName)){
			$count++;
			echo '<li>'.$folderName;
			$tourImgsDir = "../images/tours/".$folderName;
			$tourImgs = scandir($tourImgsDir);
			echo '<ul>';
			foreach($tourImgs as $index2 => $fileName){
				echo '<li>'.$fileName.'</li>';
			}
			echo '</ul>';
			echo '</li>';
		}
		if($count>2){
			die('</ul>');
		}
	}
	echo '</ul>';
?>