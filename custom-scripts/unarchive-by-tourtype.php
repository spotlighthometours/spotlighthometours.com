<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	$tourIDs = $db->run("SELECT tourID FROM tours WHERE tourTypeID='118' OR tourTypeID='72' OR tourTypeID='36'");
	$tourarchive = new tourarchive();
	foreach($tourIDs as $tourIDrow => $tourIDCols){
		//$tourarchive->setArchive($tourIDCols['tourID']);
		if($tourarchive->isArchived($tourIDCols['tourID'])){
			echo 'Tour is archived: '.$tourIDCols['tourID']."<br/>";
			//$tourarchive->unArchiveTour($tourIDCols['tourID']);
			//echo "Unarchived tourID: ".$tourIDCols['tourID']."<br/>";
		}else{
			echo 'Tour unarchived: '.$tourIDCols['tourID']."<br/>";
		}
	}
?>