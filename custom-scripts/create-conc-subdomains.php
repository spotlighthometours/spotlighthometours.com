<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	$conciergeTours = $db->run("SELECT tourID FROM tours WHERE concierge='1'");
	foreach($conciergeTours as $row => $columns){
		$microsites = new microsites($columns['tourID']);	
		$microsites->createSubdomain();
	}
?>