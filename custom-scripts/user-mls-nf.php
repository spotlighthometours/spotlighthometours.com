<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
	if(!isset($_REQUEST['start'])){
		$start = 0;
	}else{
		$start = intval($_REQUEST['start']);
	}
	$limit = 100;
	$users = $db->select('users', 'mls IS NOT NULL AND mls<>\'\'', '', 'userID, mls, mlsProviderID', 'LIMIT '.$start.', '.$limit);
	foreach($users as $row => $info){
		$ids = explode(',', $info['mls']);
		foreach($ids as $index => $id){
			if(!empty($id)){
				$columns = array(
					"userID"=>$info['userID'],
					"mlsID"=>$id
				);
				if(ctype_digit($info['mlsProviderID'])&&intval($info['mlsProviderID'])>0){
					$columns["mlsProvider"] = $info['mlsProviderID'];
				}else{
					$columns["mlsProvider"] = 0;
				}
				echo '<p>';
				print_r($columns);
				echo '</p>';
				$db->insert('user_to_mls', $columns);
			}
		}
	}
	if(count($users)>0){
?>
<a href="?start=<?PHP echo $start+$limit; ?>">Next 100</a>
<script>
	window.location = '?start=<?PHP echo $start+$limit; ?>';
</script>
<?PHP
	}
?>