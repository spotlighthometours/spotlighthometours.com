<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite videos control file
*/
	// Get branded or non branded tour videos
	if($branded){
		// Show branded tour videos
		$tourVideos = $microsites->tours->getFilteredVideos($tourID);
	}else{
		// Show non branded tour videos
		$tourVideos = $microsites->tours->getFilteredVideos($tourID, false);
	}
	// Pull tour slideshows
	$tourSlideShows = $slideshows->getSlideShows($tourID, true);
	foreach($tourVideos as $row => $columns){
?>
    <div class="section">
    	<h2><?PHP echo ucfirst($columns['room']) ?></h2>
        <iframe width="799" height="451" src="<?PHP echo $microsites->getVideoPlayerURL("video", $columns['mediaID']) ?>" frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true" allowfullscreen="true" scrolling="no"></iframe>
    </div>
<?PHP
	}
	foreach($tourSlideShows as $row => $columns){
?>
    <div class="section">
    	<h2><?PHP echo ucfirst($columns['name']) ?></h2>
        <iframe width="799" height="451" src="<?PHP echo $microsites->getVideoPlayerURL("slideshow", $columns['photoTourID']) ?>" frameborder="0" mozallowfullscreen="true" webkitallowfullscreen="true" allowfullscreen="true"  scrolling="no"></iframe>
    </div>
<?PHP
	}
?>