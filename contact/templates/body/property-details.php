<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite property details control file
*/
include($microsites->getDocumentRoot().'/templates/list/featured-rooms.php');
?>
    <div class="property-details section">
    <h2>Property Details</h2>	
<?PHP include('amenities.php') ?>
<?PHP include('description.php') ?>
	</div>
<?PHP include('map-view.php') ?>