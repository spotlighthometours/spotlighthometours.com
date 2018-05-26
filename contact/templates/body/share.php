<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite social media share control file
*/
?>
<h2 style="white-space: nowrap;">Share this property!</h2>
<div class="share-icons"> <a href="https://twitter.com/home?status=Check%20out%20the%20<?PHP echo urlencode($microsites->tours->title) ?>%20website%20<?PHP echo $_SERVER['HTTP_HOST'] ?>" target="_blank" class="twitter">Share on Twitter</a> <a href="https://www.facebook.com/sharer/sharer.php?u=<?PHP echo $_SERVER['HTTP_HOST'] ?>" target="_blank" class="facebook">Share on Facebook</a> <a href="https://pinterest.com/pin/create/button/?url=&amp;media=<?PHP echo $metaData['icon'] ?>&amp;description=Check%20out%20the%20<?PHP echo urlencode($microsites->tours->title) ?>%20website%20<?PHP echo $_SERVER['HTTP_HOST'] ?>" target="_blank" class="pinterest">Share on Pinterest</a> <a href="https://plus.google.com/share?url=<?PHP echo $_SERVER['HTTP_HOST'] ?>" target="_blank" class="googleplus">Share on Google Plus</a><div class="clear"></div></div>