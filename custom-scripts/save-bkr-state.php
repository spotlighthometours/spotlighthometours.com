<?PHP
/**********************************************************************************************
Document: save-bkr-state.php
Creator: Jacob Edmond Kerr
Date: 03-25-14
Purpose: So I guess there are a bunch of brokerage that do not have it's state selected and saved in the DB. The solution to that problem is pulling a list that do not have the state saved then pulling a list of tours for that brokerage and finding out what state most of the tours are in then saving that state as the state for the brokerage :)
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

	$bkrNoState = $db->run('SELECT brokerageID FROM brokerages WHERE state="--" OR state IS NULL');
	foreach($bkrNoState as $row => $columns){
		$state = $db->run('SELECT t.state, COUNT(t.state) AS magnitude FROM tours t, users u WHERE t.userID = u.userID AND u.brokerageID = "'.$columns['brokerageID'].'" GROUP BY t.state ORDER BY magnitude DESC LIMIT 1');
		if(count($state)>0){
			$state = $state[0]['state'];
		}else{
			$state = $db->run('SELECT state, COUNT(state) AS magnitude FROM users WHERE brokerageID = "'.$columns['brokerageID'].'" GROUP BY state ORDER BY magnitude DESC LIMIT 1');
			if(count($state)>0){
				$state = $state[0]['state'];
			}
		}
		if(!is_array($state)&&!empty($state)){
			$db->run('UPDATE brokerages SET state="'.strtoupper($state).'" WHERE brokerageID="'.$columns['brokerageID'].'"');
		}
	}
	
?>