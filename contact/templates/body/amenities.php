<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite amenities control file
*/
	$amenities = new amenities($tourID);
	$propertyAmenities = $amenities->get();
	$doubleColumns = false;
	if(count($propertyAmenities)>4){
		$doubleColumns = true;
		$propertyAmenitiesCount = count($propertyAmenities);
		$propertyAmenitiesColumn2 = array_slice($propertyAmenities, $propertyAmenitiesCount/2, $propertyAmenitiesCount);
		$propertyAmenities = array_slice($propertyAmenities, 0, $propertyAmenitiesCount/2);
	}
?>	
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="top"><table border="0" cellspacing="0" cellpadding="0" class="details">
<?PHP
	foreach($propertyAmenities as $row => $columns){
?>
              <tr>
                <td valign="top"><?PHP echo ucwords($columns['name']) ?>:</td>
                <td valign="top"><span><?PHP echo $columns['value'] ?></span></td>
              </tr>
<?PHP
	}
?>
            </table></td>
<?PHP
	if($doubleColumns){
?>
          <td valign="top"><table border="0" cellspacing="0" cellpadding="0" class="details" style="margin-left:5px;">
<?PHP
		foreach($propertyAmenitiesColumn2 as $row => $columns){
?>
              <tr>
                <td valign="top"><?PHP echo ucwords($columns['name']) ?>:</td>
                <td valign="top"><span><?PHP echo $columns['value'] ?></span></td>
              </tr>
<?PHP
		}
?>
            </table></td>
<?PHP
	}
?>
        </tr>
      </table>