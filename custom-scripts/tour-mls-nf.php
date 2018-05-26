<?PHP
	include('../repository_inc/classes/inc.global.php');
	if(!isset($_REQUEST['start'])){
		$start = 0;
	}else{
		$start = intval($_REQUEST['start']);
	}
	$limit = 1000;
	$tours = $db->select('tours', '', '', 'tourID, mls', 'LIMIT '.$start.', '.$limit);
	foreach($tours as $row => $info){
		$ids = explode(',', $info['mls']);
		foreach($ids as $index => $id){
			if(!empty($id)){
				$columns = array(
					"tourID"=>$info['tourID'],
					"mlsID"=>$id,
					"mlsProvider"=>0
				);
				echo '<p>';
				print_r($columns);
				echo '</p>';
				$db->insert('tour_to_mls', $columns);
			}
		}
	}
	if(count($tours)>0){
?>
<a href="?start=<?PHP echo $start+$limit; ?>">Next 1,000</a>
<script>
	window.location = '?start=<?PHP echo $start+$limit; ?>';
</script>
<?PHP
	}
?>