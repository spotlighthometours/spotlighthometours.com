<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	
	$micrositesetting = new micrositesetting();
	$micrositeimage = $micrositesetting->getImages();

	$awss3 = new awss3();
	foreach($micrositeimage as $micrositeBGRow => $micrositeBGData){
		$ext = end((explode(".", $micrositeBGData['path'])));
		$micrositesetting->updateBGPhoto(array("fileExt"=>$ext), $micrositeBGData['id']);
		$fileName = $micrositeBGData['id'].'.'.$ext;
		$awss3->upload("", 'microsite-uploads/'.$fileName, fopen($_SERVER['DOCUMENT_ROOT'].$micrositeBGData['path'], 'rb'));
		if($micrositeBGData['filetype']=="video"){
			$micrositesetting->transcodeVideo($fileName, $micrositeBGData['id']);
		}
	}
?>