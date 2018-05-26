<?PHP
	include('../repository_inc/classes/inc.global.php');
	// Pull all teams without an api key
	$teamsNoKey = $db->select('teams', 'api_key is NULL', '', 'username, userid');
	foreach($teamsNoKey as $row => $column){
		// Update teams with API Key
		$update_array = array(
			"api_key"=>rtrim(base64_encode($column['username'].rand(999, 99999)), '=')
		);
		$db->update('teams', $update_array, "username='".$column['username']."' AND userid='".$column['userid']."'");
	}
	
	// Pull all brokerages without an api key
	$brokersNoKey = $db->select('brokerages', 'api_key is NULL', '', 'brokerageID, brokerageName, brokerageDesc');
	foreach($brokersNoKey as $row => $column){
		// Update teams with API Key
		$update_array = array(
			"api_key"=>rtrim(base64_encode($column['brokerageName'].$column['brokerageDesc'].rand(999, 99999)), '=')
		);
		$db->update('brokerages', $update_array, "brokerageID='".$column['brokerageID']."'");
	}
?>