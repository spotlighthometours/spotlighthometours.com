<?php
/**********************************************************************************************
Document: user_ownership.php
Creator: Brandon Freeman
Date: 06-23-11
Purpose: Function to determin if a team owns a user, thus giving rights to its stuff.
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================
	
	if (!isset($dbc)) {
		require_once ('../repository_inc/connect.php');
		require_once ('../repository_inc/clean_query.php');
	}

//=======================================================================
// Document
//=======================================================================

function TeamOwnsUser($teamid, $userid) {
	
	session_start();
	
	$ownership = false;

	$query = '
		SELECT u.userID, u.firstName, u.lastName
		FROM (teams_to_brokerages ttb
		LEFT JOIN brokerages b ON ttb.brokerage_id = brokerageID)
		RIGHT JOIN users u ON ttb.brokerage_id = u.brokerageID
		WHERE ttb.team_id = ' . $teamid . '
		AND u.userID = ' . $userid . '
		LIMIT 1
	';
	$r = @mysql_query($query);
	if(mysql_num_rows($r)) {
		$ownership = true;
		$result = mysql_fetch_array($r);
		$_SESSION['first_name'] = $result['firstName'];
		$_SESSION['last_name'] = $result['lastName'];
	}

	return $ownership;
}

function AccessByUser($userid) {
	session_start();
	$access = false;
	if(intval($userid) > 0) {
		// Create a MySQL PDO
		include ('../repository_inc/data.php');
		$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
		$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$query = 'SELECT userID, CONCAT(firstName, " ", lastName) AS name FROM users WHERE userID = :userid LIMIT 1';
		if($stmt = $dbh->prepare($query)) {
			$stmt->bindParam(':userid', $userid);
			try {
				$stmt->execute();
			} catch (PDOException $e){
				echo $e->getMessage(). '<br />';
			}
			$result = $stmt->fetch();
			if (intval($result['userID']) > 0) {
			
				if(isset($_SESSION['admin_id'])){
					$access = true;
				} elseif (isset($_SESSION['team_user_id'])){
					if(TeamOwnsUser($_SESSION['team_user_id'], $result['userID'])) {
						$access = true;
					}
				} elseif (isset($_SESSION['user_id'])) {
					if ($_SESSION['user_id'] == $result['userID']) {
						$access = true;
					}
				}
			} 
		}
	}
	return $access;
}

function AccessByTour($tourid) {
		
}

?>