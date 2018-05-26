<?PHP
	include('../repository_inc/classes/inc.global.php');
	if(!isset($_REQUEST['start'])){
		$start = 0;
	}else{
		$start = intval($_REQUEST['start']);
	}
	$limit = 100;
	$members = new members();
	$fullMemberList = $db->select('members', '', '', '*', 'LIMIT '.$start.', '.$limit);
	foreach($fullMemberList as $row => $columns){
		$members->userType = $columns['userType'];
		$members->userID = $columns['userID'];
		$members->membershipID = $columns['typeID'];
		$members->create();
		if($columns['active']=="1"){
			$members->activate();
		}
	}
if(count($fullMemberList)>0){
?>
<a href="?start=<?PHP echo $start+$limit; ?>">Next 100</a>
<script>
	window.location = '?start=<?PHP echo $start+$limit; ?>';
</script>
<?PHP
	}
?>