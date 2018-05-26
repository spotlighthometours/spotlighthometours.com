<?PHP
/**********************************************************************************************
Document: insert-locations.php
Creator: Jacob Edmond Kerr
Date: 06-02-14
Purpose: Insert locations in nf_locations table
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Global Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');
	showErrors();
	
//=======================================================================
// Document
//=======================================================================

	$locations = array();
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'YES',
		'z_type'=>'STANDARD',
		'city'=>'Bethel',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.10'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Betty Hill',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.00'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Blowing Point',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.18',
		  'lon'=>'-63.10'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Blowing Point Village',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.17',
		  'lon'=>'-63.08'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Bungalows',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.18',
		  'lon'=>'-63.10'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Cannifist',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.02'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Cauls Bottom',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Chalvilles',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.00'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Crocus Hill',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.07'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Deep Waters',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.02'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'East End',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.00'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'East End Village',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.00'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Ebenezer',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.07'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'George Hill',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.07'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Island Harbour',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.25',
		  'lon'=>'-63.00'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Junks Hole',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.25',
		  'lon'=>'-62.98'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Little Dicks',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Little Dix',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Long Bay',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.18',
		  'lon'=>'-63.12'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Long Bay Village',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.18',
		  'lon'=>'-63.12'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Long Ground',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.05'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Long Path',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.02'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Lower South Hill',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.18',
		  'lon'=>'-63.10'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Mahogany Tree',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Mount Fortune',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-62.98'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'North Hill Village',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.07'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'North Side',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.05'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Rey Hill',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Saint Mary\'s',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.07'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Saint Mary\'s',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.07'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Sandy Ground Village',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.08'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Shoal Bay Village',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.25',
		  'lon'=>'-63.02'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Shoal Village',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.25',
		  'lon'=>'-63.02'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'South Hill Village',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.18',
		  'lon'=>'-63.08'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Stoney Ground',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'The Copse',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-62.98'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'The Farrington',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'The Forest',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'The Fountain',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'The Quarter',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.20',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'The Valley',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.05'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Valley',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.05'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Wattices',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.22',
		  'lon'=>'-63.03'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Welches',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.02'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'Welches Hill',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.23',
		  'lon'=>'-63.02'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'West End Village',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.17',
		  'lon'=>'-63.15'
	);
	$locations[] = array(
		'zip_code'=>'AI-2640',
		'z_primary'=>'NO',
		'z_type'=>'STANDARD',
		'city'=>'White Hill',
		'state_prefix'=>'BWI',
		'county'=>'CARIBBEAN',
		'country'=>'AI',
		 'lat'=>'18.25',
		  'lon'=>'-63.00'
	);
	$count = 0;
	foreach($locations as $index => $location){
		$db->insert('nf_locations', $location);
		$count++;
	}
	echo $count.' locations inserted!';
?>