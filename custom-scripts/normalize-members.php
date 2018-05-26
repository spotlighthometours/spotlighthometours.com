<?PHP
	require_once('../repository_inc/classes/inc.global.php');
	$errorHandlerCalled = true;
	showErrors();
	$members = $db->select('members');
	foreach($members as $row => $columns){
		if(intval($columns['brokerageID'])>0){
			$db->update('members', array('userType'=>'broker', 'userID'=>$columns['brokerageID']), "id='".$columns['id']."'");
		}
	}
?>