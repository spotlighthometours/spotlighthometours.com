<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	$users = new users($db);
	$security = new security();
	
	// Parse CSV File
	$parsecsv = new parseCSV('lvagents.csv');
	$page = $_REQUEST['page'];
	$perPage = 100;
	$startIndex = $perPage*($page-1);
	$endIndex = $perPage*$page;
	$usersUpdated = 0;
	$usersFailed = 0;
	$csvDataArray = $parsecsv->data;
	//$csvData = array_slice($csvDataArray, $startIndex, $endIndex);
	//print_r($csvData);
	foreach($csvDataArray as $row => $column){
		// Clear user info and to prepare for new user
		unset($users->userInfo);
		$agentName = $column['Agent'];
		$agentName = explode(" ", $agentName);
		$firstName = $agentName[0];
		array_shift($agentName);
		$lastName = implode(" ", $agentName);
		$userInfo = array(
			//'firstName'=>trim($column['firstName']),
            //'lastName'=>trim($column['lastName']),
			'email'=>trim($column['Email']),
			//'address'=>trim($column['address']),
			//'city'=>trim($column['city']),
			//'state'=>trim($column['state']),
			//'zipCode'=>trim($column['zipCode']),
			'phone'=>trim($column['Phone']),
			//'phone2'=>trim($column['phone2']),
            //'userType'=>"Agent",
            //'BrokerageID'=>$brokerageID,
            //'username'=>trim($column['username']),
			//'mls_provider'=>5,
			//'mls'=>trim($column['mls'])
		);
		if(!is_null($users->getUserByEmail($userInfo['email']))){
			$userInfo['userID'] = $users->userID;
			$users->set($userInfo);
			$users->save();
			echo 'User updated! Email: '.$userInfo['email'].'<br/>';
			$usersUpdated++;
			//print $users->errors->getUL();
			//print_r($userInfo);
			//echo $users->userID;
		}else{
			$usersFailed++;
			echo 'User not found! Email: '.$userInfo['email'].'<br/>';
			/*$emailFirst = explode('@',$userInfo['email']);
			$emailFirst = $emailFirst[0];
			$potentialUser = $db->run("SELECT * FROM users WHERE firstName LIKE '%".$firstName."%' AND lastName LIKE '%".$lastName."%' LIMIT 1");*/
		}
	}
	echo "There was a total of ".$usersUpdated." users updated! <br/>";
	echo "There was a total of ".$usersFailed." users that failed to update!";
	/*if($page<40){
		echo '
		<script>
			window.location = "?page='.(intval($page)+1).'";
		</script>
		';
	}*/
?>