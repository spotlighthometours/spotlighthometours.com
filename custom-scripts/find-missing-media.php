<?PHP
	include('../repository_inc/classes/inc.global.php');
	$errorHandlerCalled = true;
	showErrors();
	set_time_limit(0);
	ini_set('max_execution_time', 0);
	ini_set('memory_limit', '-1');
	$media = new media();
	if(!isset($_REQUEST['start'])){
		$start = 0;
	}else{
		$start = intval($_REQUEST['start']);
	}
	$limit = 100;
	$allMedia = $db->select('media', 'mediaID>0', '', 'mediaID, tourID, fileExt, mediaType', 'LIMIT '.$start.', '.$limit);
	foreach($allMedia as $mrow => $mcolumn){
		if($mcolumn['mediaType']=='photo'){
			if($media->getDrive($mcolumn['tourID'].'/photo_high_'.$mcolumn['mediaID'].'.'.$mcolumn['fileExt'])===false){
				$media->notifications->set(print_r($mcolumn, true)." missing!");
			}
		}else if($mcolumn['mediaType']=='video'){
			if($media->getDrive($mcolumn['tourID'].'/video_'.$mcolumn['mediaID'].'.'.$mcolumn['fileExt'])===false){
				$media->notifications->set(print_r($mcolumn, true)." missing!");
			}
		}
	}
?>
<script>
	window.location = '?start=<?PHP echo $start+$limit; ?>';
</script>