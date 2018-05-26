<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite featured rooms control file
*/

	$featuredRooms = $microsites->getFeaturedRooms();
?>
    <div class="featured-rooms section">
      <h2>Featured Rooms</h2>
<?PHP
	foreach($featuredRooms as $row => $columns){
		$iconMediaID = $microsites->getRoomIcon($columns['room']);
?>
      <div class="room" style="margin-left:0px; <?PHP echo ($columns===end($featuredRooms))?'margin-right:0px;':''; ?>" onclick="showFeaturedGallery('<?PHP echo $columns['room'] ?>')"> <img src="http://www.spotlighthometours.com/images/tours/<?PHP echo $tourID ?>/photo_400_<?PHP echo $iconMediaID ?>.jpg" width="193" />
        <h3><?PHP echo ucwords($columns['room']) ?></h3>
        <div class="view-gallery">view gallery</div>
      </div>
<?PHP
	}
?>
      <div class="clear"></div>
      <!-- FEATURED ROOM GALLERIES -->
	  <div class="featured-room-gals">
<?PHP
	foreach($featuredRooms as $row => $columns){
		$roomPhotos = $microsites->getRoomPhotos($columns['room']);
?>
		<ul id="<?PHP echo strtolower(str_replace(" ", "", $columns['room'])); ?>">
<?PHP
		foreach($roomPhotos as $rrow => $rcolumns){
?>
			<li>
      			<h4><?PHP echo ucfirst($rcolumns['room']) ?></h4>
      			<img border="0" src="http://www.spotlighthometours.com/images/tours/<?PHP echo $rcolumns['tourID'] ?>/photo_960_<?PHP echo $rcolumns['mediaID'] ?>.jpg" alt="<?PHP ucfirst($rcolumns['room']) ?>" width="695" />
            </li>
<?PHP
		}
?>
        </ul>
<?PHP
	}
?>	  
      </div>
    </div>