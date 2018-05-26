<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite main navigation control file
*/
?>
	<ul>
    	<li><a href="?section=gallery" <?PHP echo ($section=="gallery")?'class="active"':'' ?>>Gallery</a></li>
        <li><a href="?section=videos" <?PHP echo ($section=="videos")?'class="active"':'' ?>>Videos</a></li>
        <li><a href="?section=details" <?PHP echo ($section=="details")?'class="active"':'' ?>>Property Details</a></li>
        <li><a href="?section=local" <?PHP echo ($section=="local")?'class="active"':'' ?>>Local</a></li>
        <li><a href="?section=map" <?PHP echo ($section=="map")?'class="active"':'' ?>>Map</a></li>
    </ul>