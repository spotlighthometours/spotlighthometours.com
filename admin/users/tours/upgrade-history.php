<?php
	require '../../../repository_inc/classes/inc.global.php';
	$tourId = intval($_GET['tourId']);
	$up = new upgrade;
	$up->loadHistory($tourId);
	if( $up->count() == 0 ){
		die;
	}
	function resolveAdmin($id){
		global $db;
		$res = $db->select("administrators","administratorID=$id");
		return $res[0]['fullName'];
	}
	function tourType($id){
		global $up;
		return $up->getTourTypeName($id);
	}
?>
<style>
#upgrade-wrapper table td { 
	border: 1px solid black;
}
#upgrade-wrapper table td {
	background-color: #fff;
}
/*
#upgrade-wrapper tr:nth-child(even) {
	background: #CCC;
}
#upgrade-wrapper tr:nth-child(odd) {
	background: #fff;
}*/
</style>
<!-- upgrade dialog -->
<div id='upgrade-wrapper'>

	<div>
		<b>Upgrade History</b>
	</div>
	<div>
		<table style='border:1px solid black;background-color:green;'>
			<thead>
				<th>Tour ID</th>
				<th>Who</th>
				<th>From</th>
				<th>To</th>
				<th>Date</th>
			</thead>
			<?php
				for($i=0; $i < $up->count();$i++){
					echo "<tr>";
					echo "<td>" . $up[$i]['tourId'] . "</td>";
					echo "<td>" . resolveAdmin($up[$i]['adminId']) . "</td>";
					echo "<td>" . tourType($up[$i]['fromTourType']) . "</td>";
					echo "<td>" . tourType($up[$i]['toTourType']) . "</td>";
					echo "<td>" . $up[$i]['created_at'] . "</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>
</div>