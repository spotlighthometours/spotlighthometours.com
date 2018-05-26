<?PHP
	set_time_limit(0);
	include('../repository_inc/classes/inc.global.php');
	$tours = new tours();
	// Get all tour with photos that have been finalized
	$toursWithPhotos = $db->run('SELECT t.tourID, count(mediaID) FROM tourprogress t, media m WHERE t.finalized=1 AND t.tourID = m.tourID GROUP BY t.tourID ORDER  by t.tourID');
	//$toursWithPhotos[0]['tourID'] = '39350';
	// Get active photos for tour
	foreach($toursWithPhotos as $row => $column){
		$tourID = $column['tourID'];
		$tourPhotos = $db->run('SELECT mediaID, fileExt FROM media WHERE tourid=\''.$tourID.'\' AND mediaType=\'photo\' ORDER BY createdOn DESC LIMIT 1');
		// Check if the photo returned exist
		$exist = false;
		$tourImage = TOUR_IMAGE_DIRECTORY.$tourID."/photo_sm_".$tourPhotos[0]['mediaID'].".jpg";
		$tourImageF = str_replace("D:\websites\spotlighthometours\public", "F:", $tourImage);
		//print $tourImage.'<br/>';
		//print $tourImageF;
		if(file_exists($tourImage)){
			$exist = true;
		}
		if(file_exists($tourImageF)){
			$exist = true;
		}
		if(!$exist){
			print $tourID.'<br/>';
		}
	}
?>