<?PHP
/**********************************************************************************************
Document: delete-wfrmls-photos.php
Creator: Jacob Edmond Kerr
Date: 07-02-15
Purpose: This script deletes all the photos imported from WFRMLS (This is to fix a big boo boo I Jacob had made!)
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Global Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');
	showErrors();
	neverDie();
	$awss3 = new awss3();
	$awss3->trace = true;
	
	if(!isset($_REQUEST['start'])){
		$start = 0;
	}else{
		$start = intval($_REQUEST['start']);
	}
	$limit = 100;
	$allMedia = $db->select('tour_to_mls, tours', 'tour_to_mls.mlsProvider=5 AND tours.tourID = tour_to_mls.tourID AND tours.concierge=1', '', 'tour_to_mls.tourID', 'ORDER BY tour_to_mls.id ASC LIMIT '.$start.', '.$limit);
	
//=======================================================================
// Document
//=======================================================================
	
	foreach($allMedia as $mrow => $mcolumn){
		$awss3->notifications->trace("Calling method to delete tour images for tourID: ".$mcolumn['tourID']);
		$awss3->deleteFolder($mcolumn['tourID']);
	}
	
?>
<script>
	window.location = '?start=<?PHP echo $start+$limit; ?>';
</script>