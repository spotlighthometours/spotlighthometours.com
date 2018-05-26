<?PHP
/**********************************************************************************************
Document: delete-files-from-318-on-s3
Creator: Jacob Edmond Kerr
Date: 06-12-14
Purpose: This script checks if media from the DB exists on S3 and if so deletes it off 318
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Global Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');
	showErrors();
	
	$media = new media();
	$tourphotos = new tourphotos();
	$tourvideos = new tourvideos();
	$awss3 = new awss3();
	
	if(!isset($_REQUEST['start'])){
		$start = 0;
	}else{
		$start = intval($_REQUEST['start']);
	}
	$limit = 100;
	$allMedia = $db->select('media', 'mediaID>0', '', 'mediaID, tourID, fileExt, mediaType', 'ORDER BY mediaID DESC LIMIT '.$start.', '.$limit);
	
//=======================================================================
// Document
//=======================================================================

	// Pull all media from the media table let's run 100 at a time!
	function doesFileExistOnS3($file, $tourID){
		global $awss3;
		if($awss3->doesObjectExist('tours/'.$tourID.'/'.$file)){
			return true;
		}else{
			return false;
		}
	}
	
	function deleteFile($file, $tourID){
		global $media;
		$theFile = $tourID.'/'.$file;
		$drive = $media->getDrive($theFile, false);
		switch($drive){
			case'd':
				$filePath = TOUR_IMAGE_DIRECTORY.$theFile;
			break;
			case'f':
				$filePath = TOUR_IMAGE_DIRECTORY_F.$theFile;
			break;
			case'g':
				$filePath = TOUR_IMAGE_DIRECTORY_G.$theFile;
			break;
		}
		if(isset($filePath)){
			$media->notifications->set($filePath.' DELETED!');
			echo $filePath.' DELETED!!<br/>';
			unlink($filePath);
		}
	}
	
	foreach($allMedia as $mrow => $mcolumn){
		switch($mcolumn['mediaType']){
			case'photo':
				$sizes = $tourphotos->sizeAbrev;
				$sizes[] = 'high';
				foreach($sizes as $index => $size){
					$fileName = 'photo_'.$size.'_'.$mcolumn['mediaID'].'.'.$mcolumn['fileExt'];
					if(doesFileExistOnS3($fileName, $mcolumn['tourID'])){
						deleteFile($fileName, $mcolumn['tourID']);
					}
				}
			break;
			case'video':
				$videoFoundOnS3 = false;
				$sizes = $tourvideos->sizes;
				$sizes = array_merge($sizes, $tourvideos->oldSizes);
				$smilFile = 'video_'.$mcolumn['mediaID'].'.smil';
				foreach($sizes as $index => $size){
					$fileName = 'video_'.$mcolumn['mediaID'].'_'.$size.'.'.$mcolumn['fileExt'];
					if(doesFileExistOnS3($fileName, $mcolumn['tourID'])){
						$videoFoundOnS3 = true;
						deleteFile($fileName, $mcolumn['tourID']);
					}
				}
				if($videoFoundOnS3){
					deleteFile($smilFile, $mcolumn['tourID']);
				}
			break;
			case'walkthru':
				$fileName = 'walkthru_'.$mcolumn['mediaID'].'.flv';
				if(doesFileExistOnS3($fileName, $mcolumn['tourID'])){
					deleteFile($fileName, $mcolumn['tourID']);
				}
			break;
		}
	}
?>
<script>
	window.location = '?start=<?PHP echo $start+$limit; ?>';
</script>