<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	neverDieAllowAbort();
	$finalizedTours = $db->run("SELECT tourID FROM tourprogress WHERE finalizedon > '2017-07-10' AND finalizedon < '2017-07-12'");
	$awss3 = new awss3();		
	$regex = '/.*\.zip/i';
	foreach($finalizedTours as $row => $tourID){
		$awss3->deleteMatchingObjects("", "tours/".$tourID['tourID']."/", $regex);
		echo "Deleted zips for tourID: ".$tourID['tourID']."\n";
	}
?>