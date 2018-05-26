<?PHP
	include('../repository_inc/classes/inc.global.php');
	
	$users = new users($db);
	$security = new security();
	
	// Parse CSV File
	$parsecsv = new parseCSV('coldwell-users.csv');
	
	$usersCreated = 0;
	$usersFailed = 0;
	foreach($parsecsv->data as $row => $column){
		// Clear user info and to prepare for new user
		unset($users->userInfo);
		$brokerageID = trim($column['BrokerageID']);
		$name = explode(" ", $column['Name']);
		$userInfo = array(
			'firstName'=>trim($name[0]),
            'lastName'=>trim($name[1]),
			'email'=>trim($column['email']),
			'address'=>trim($column['address']),
			'city'=>trim($column['city']),
			'state'=>trim($column['state']),
			'zipCode'=>trim($column['zipCode']),
			'uri'=>trim($column['uri']),
			'phone'=>trim($column['phone']),
			'phone2'=>trim($column['phone2']),
            'userType'=>"Agent",
            'BrokerageID'=>$brokerageID,
            'username'=>trim($column['username']),
            'password'=>$security->generatePassword()
		);
		//print_r($userInfo);
		$users->set($userInfo);

		//if(!$users->register("noEmails")){
			//print $users->errors->getUL();
			// User may already exist if so make sure they are in the right brokerage
			if($users->userExist($userInfo['email'])){
				unset($userInfo['password']);
				$db->update('users', $userInfo, 'username=\''.$userInfo['email']."'");
				echo "User already exist and was updated to the right brokerage: ".$users->userID."<br/>";
				$usersCreated++;
			}else{
				$usersFailed++;
			}
		//}else{
			//echo "User created: ".$users->userID."<br/>";
			//$usersCreated++;
		//}
	}
	echo "There was a total of ".$usersCreated." users created! <br/>";
	echo "There was a total of ".$usersFailed." users that failed to create! :'( Please review the data above for the failed users...";
?>