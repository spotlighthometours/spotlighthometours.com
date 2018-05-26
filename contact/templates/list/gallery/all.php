<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite photo gallery (all photos)
*/
	require_once($_SERVER['DOCUMENT_ROOT'].'/repository_inc/classes/inc.global.php');
	if(!isset($tourID)){
		$tourID = $_REQUEST['tourID'];
	}
	if(!isset($tours)){
		$tours = new tours();
	}
	$photos = $tours->getPhotos($tourID, 1);
?>
	<div id="gallery">
        <ul class="slideme">
<?PHP
	foreach($photos as $row => $columns){
?>
          <li>
            <h4><?PHP echo $columns['room'] ?></h4>
    		<img border="0" src="<?PHP echo TOUR_IMAGE_DIR_URL.$tourID ?>/photo_960_<?PHP echo $columns['mediaID'] ?>.jpg" alt="<?PHP echo $columns['room'] ?>" width="695" />
          </li>
<?PHP
	}
?>
        </ul>
    </div>