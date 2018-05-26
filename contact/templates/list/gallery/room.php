<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite photo gallery (room photos)
*/	
	if(!isset($tourID)){
		$tourID = $_REQUEST['tourID'];
	}
	if(!isset($microsites)){
		$microsites = new microsites($tourID);
	}
	if(!isset($room)){
		$room = $_REQUEST['room'];
	}
	$roomPhotos = $microsites->getRoomPhotos($room);
?>
	<div id="gallery">
        <ul class="slideme">
<?PHP
	foreach($roomPhotos as $row => $columns){
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