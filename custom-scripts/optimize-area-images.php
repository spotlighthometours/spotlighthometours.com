<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	$simpleimage = new simpleimage();
	$areaImages = $db->run("SELECT image, id FROM areainfo");
	foreach($areaImages as $aiRow => $aiCol){
		$im = imagecreatefromstring($aiCol['image']);
		$simpleimage->image = $im;
		$simpleimage->image_type = IMAGETYPE_JPEG;
		$simpleimage->resizeToWidth(800);
		ob_start();
		imagejpeg($simpleimage->image);
		$i = ob_get_clean();
		$db->update("areainfo",array('image'=>$i),"id='".$aiCol['id']."'");
	}
?>