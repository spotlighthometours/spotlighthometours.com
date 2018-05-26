<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite map view control file
*/
?>
    <div class="map-view section">
      <h2>Map View</h2>
      <iframe width="695" height="392" frameborder="0" style="border:0" src="<?PHP echo $microsites->getMapURL() ?>"></iframe>
      <br/>
      <br/>
      <br/>
    </div>