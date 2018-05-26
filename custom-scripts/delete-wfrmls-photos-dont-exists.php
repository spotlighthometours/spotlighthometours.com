<?PHP
/**********************************************************************************************
Document: delete-wfrmls-photos-dont-exists.php
Creator: Jacob Edmond Kerr
Date: 07-06-15
Purpose: This script deletes all the photos imported from WFRMLS that has failed and do not exist on any drive
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Global Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');
	showErrors();
	
	$media = new media();
	$mls = new mls();
	
	if(!isset($_REQUEST['start'])){
		$start = 0;
	}else{
		$start = intval($_REQUEST['start']);
	}
	$limit = 100;
	$allMedia = $db->select('media_to_mls', 'mlsProvider=5', '', 'mediaID', 'LIMIT '.$start.', '.$limit);
	
//=======================================================================
// Document
//=======================================================================
	
	foreach($allMedia as $mrow => $mcolumn){
			$mediaID = $mcolumn['mediaID'];
			// Delete the media from the DB as well
			$db->delete('media', "mediaID='".$mediaID."'");
			// Delete slides from slideshows if exist
			$slideShowExsists = $db->select('photo_tour_images', 'mediaID="'.$mediaID.'"');
			if(count($slideShowExsists)>0){
				$db->delete('photo_tours', 'photoTourID="'.$slideShowExsists[0]['photoTourID'].'"');
			}
			$db->delete('photo_tour_images', 'mediaID="'.$mediaID.'"');
			// Delete from the media_to_mls table if exist
			$db->delete('media_to_mls', 'mediaID="'.$mediaID.'"');
	}
?>
<?PHP
	if(count($allMedia)>0){
?>
<script>
	window.location = '?start=<?PHP echo $start+$limit; ?>';
</script>
<?PHP
	}
?>