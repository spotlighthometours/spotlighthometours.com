<?PHP
	// Global Application Configuration
	//require_once ('../repository_inc/classes/inc.global.php');
	//showErrors();
	//$errorHandlerCalled = true;
	//set_time_limit(0);
	echo 'called';
	
	// Pull all affiliates from the spotlight DB
	//$affiliates = $db->select('photographers', 'isAffiliate="1"');
	
	
	
	# your php extension
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	$phpbb_root_path = $_SERVER['DOCUMENT_ROOT'].'/affiliate-forum/';
	
	/* includes all the libraries etc. required */
	require($phpbb_root_path ."common.php");
	$user->session_begin();
	$auth->acl($user->data);
	
	/* the file with the actual goodies */
	require($phpbb_root_path ."includes/functions_user.php");
	
	// Import the affiliates that do not already exist to the forum
	$affiliates = array(
		"fullName" => "Jacob Kerr",
		"password" => "spotlight",
		"email" => "jacob@spotlighthometours.com"
	);
	foreach($affiliates as $arow => $acolumns){
		/* All the user data (I think you can set other database fields aswell, these seem to be required )*/
		$user_row = array(
			'username' => trim(preg_replace('/[^a-zA-Z\s]/', '', $acolumns['fullName'])),
			'user_password' => md5($acolumns['password']), 'user_email' => $acolumns['email'],
			'group_id' => 2,
			'user_timezone' => '1.00',
			'user_dst' => 0,
			'user_lang' => 'en',
			'user_type' => '0',
			'user_actkey' => '',
			'user_dateformat' => 'd M Y H:i',
			'user_style' => '',
			'user_regdate' => time(),
		);
		/* Now Register user */
		//print_r($user_row);
		$phpbb_user_id = user_add($user_row);
		echo $phpbb_user_id.'</br>';
	}
?>