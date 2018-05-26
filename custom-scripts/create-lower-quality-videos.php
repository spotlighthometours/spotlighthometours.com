<?PHP
	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	ignore_user_abort(true);
	set_time_limit(0);
	// Pull all mp4 videos that were uploaded after September 2012
	$page = $_REQUEST['page'];
	$nextPage = $page+1;
	$numberPerPage = 3;
	$start = $numberPerPage*($page-1);
	$newVideos = $db->run("SELECT mediaID, tourID FROM media WHERE mediaType='video' AND fileExt='mp4' AND createdOn>'".date('2012-10-01 00:00:00') ."' LIMIT ".$start.",".$numberPerPage);
	if(count($newVideos)>0){
		foreach($newVideos as $row => $column){
			$destinationFolder = 'D:/websites/spotlighthometours/public/images/tours/'.$column['tourID'];
			$mediaID = $column['mediaID'];
			$itemInfo = array(
				'fileExt' => 'mp4',
				'mediaType' => 'video',
				'tourID' => $column['tourID']
			);
			if(!file_exists($destinationFolder . '/' . $itemInfo['mediaType'] . '_' . $mediaID . '.' . $itemInfo['fileExt'])){
				$destinationFolder = str_replace('D:/websites/spotlighthometours/public/', 'F:/', $destinationFolder);
			}
			session_write_close();
			exec('"C:/imagemagick/ffmpeg" -i "'.$destinationFolder . '/' . $itemInfo['mediaType'] . '_' . $mediaID . '.' . $itemInfo['fileExt'].'" -y -s 800x450 -crf 25 "'.$destinationFolder . '/' . $itemInfo['mediaType'] . '_' . $mediaID . '_med.' . $itemInfo['fileExt'].'"');
			exec('"C:/imagemagick/ffmpeg" -i "'.$destinationFolder . '/' . $itemInfo['mediaType'] . '_' . $mediaID . '.' . $itemInfo['fileExt'].'" -y -s 480x270 -crf 30 "'.$destinationFolder . '/' . $itemInfo['mediaType'] . '_' . $mediaID . '_low.' . $itemInfo['fileExt'].'"');
			$smilFile = $destinationFolder.'/'.$itemInfo['mediaType'] . '_' . $mediaID.'.smil';
			$smilTxt = '<smil>
		<head>
			<meta base="rtmp://spotlighthometours.com/vod/" />
		</head>
		<body>
			<switch>
				<video src="'.$itemInfo['fileExt'].':spotlight/images/tours/'.$itemInfo['tourID'].'/'.$itemInfo['mediaType'] . '_' . $mediaID . '.' . $itemInfo['fileExt'].'" height="720" system-bitrate="1000000" width="1280" />
				<video src="'.$itemInfo['fileExt'].':spotlight/images/tours/'.$itemInfo['tourID'].'/'.$itemInfo['mediaType'] . '_' . $mediaID . '_med.' . $itemInfo['fileExt'].'" height="450" system-bitrate="800000" width="800" />
				<video src="'.$itemInfo['fileExt'].':spotlight/images/tours/'.$itemInfo['tourID'].'/'.$itemInfo['mediaType'] . '_' . $mediaID . '_low.' . $itemInfo['fileExt'].'" height="270" system-bitrate="300000" width="480" />
			</switch>
		</body>
	</smil>';
			file_put_contents($smilFile, $smilTxt);
			print 'VIDEO CONVERTED FOR TOUR: '.$column['tourID'].'<br/>';
		}
		print '<a href="?page='.$nextPage.'">START THE NEXT 3</a>';
?>
<script>
	window.onload = function(){
		window.location = '?page=<?PHP echo $nextPage; ?>';
	}
</script>
<?PHP
	}
?>