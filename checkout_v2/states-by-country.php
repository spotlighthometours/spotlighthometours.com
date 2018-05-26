<?PHP
	require_once('../repository_inc/classes/inc.global.php');
	print json_encode(listStates($_REQUEST['country']));
?>