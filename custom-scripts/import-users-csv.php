<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	$users = new users($db);
	$security = new security();
	
	// Parse CSV File
	$parsecsv = new parseCSV('impower.csv');
	$page = $_REQUEST['page'];
	$perPage = 100;
	$startIndex = $perPage*($page-1);
	$endIndex = $perPage*$page;
	$usersCreated = 0;
	$usersFailed = 0;
	$csvDataArray = $parsecsv->data;
	//$csvData = array_slice($csvDataArray, $startIndex, $endIndex);
	//print_r($csvData);
	$query = "";
	foreach($csvDataArray as $row => $column){
		// Clear user info and to prepare for new user
		unset($users->userInfo);
		unset($brokerageID);
		$wfrmls = new wfrmls();
		$brokerageID = $wfrmls->getBrokerageID(trim($column['brokerageID']), $wfrmls->providerID);
		$userInfo = array(
			'firstName'=>trim($column['firstName']),
            'lastName'=>trim($column['lastName']),
			'email'=>trim($column['email']),
			//'address'=>trim($column['address']),
			//'city'=>trim($column['city']),
			//'state'=>trim($column['state']),
			//'zipCode'=>trim($column['zipCode']),
			'phone'=>trim($column['phone']),
			//'phone2'=>trim($column['phone2']),
            'userType'=>"Agent",
            'BrokerageID'=>$brokerageID,
            'username'=>trim($column['username']),
            'password'=>trim($column['password']),
			'mls_provider'=>5,
			'mls'=>trim($column['mls'])
		);
		//print_r($userInfo);
		//echo "UPDATE users SET brokerageID='".$brokerageID."' WHERE email='".$userInfo['email']."'".'<br/>';
		//$db->run("UPDATE users SET brokerageID='".$brokerageID."' WHERE email='".$userInfo['email']."'");
		//$query .= "UPDATE users SET brokerageID='".$brokerageID."' WHERE email='".$userInfo['username'].";'\n";
		$users->set($userInfo);
		if(!$users->register("noEmails")){
			print $users->errors->getUL();
			// User may already exist if so make sure they are in the right brokerage
			if($users->userExist($users->userInfo['username'])){
				unset($userInfo['password']);
				$updateInfo = $userInfo;
				$users->userInfo['userID'] = $users->getUserByEmail($userInfo['username']);
				$users->save();
				echo "User already exist and was updated to the right brokerage: ".$users->userID."<br/>";
				$usersCreated++;
			}else{
				$usersFailed++;
			}
		}else{
			echo "User created: ".$users->userID."<br/>";
			$usersCreated++;
		}
	}
	/*if($page<40){
		echo '
		<script>
			window.location = "?page='.(intval($page)+1).'";
		</script>
		';
	}*/
	echo "There was a total of ".$usersCreated." users created! <br/>";
	echo "There was a total of ".$usersFailed." users that failed to create! :'( Please review the data above for the failed users...";
?>