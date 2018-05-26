<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	$media = new media();
	$duplicateSortOrderTours = $db->run('SELECT media.tourID, COUNT(media.tourID) as dupCount FROM  `media`, tours WHERE sortOrder=1 AND tours.tourID = media.tourID AND media.mediaType="photo" AND tours.concierge=1 GROUP BY media.tourID');
	foreach($duplicateSortOrderTours as $row => $columns){
		if(intval($columns['dupCount'])>1){
			echo $columns['tourID'].'<br/>';
			$media->updateSortOrder($columns['tourID']);
		}
	}
?>