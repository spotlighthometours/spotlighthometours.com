<?php
	require "../repository_inc/classes/inc.global.php";
	if( isset($_GET['tourId']) && strlen($_GET['tourId']) ){
		$videoRegen = new videoregenerator($_GET['tourId'],"DESC");
	}else{
		$videoRegen = new videoregenerator;
		$videoRegen->loadQueue(null,"DESC");
	}
// id | tourId | mediaId | status | updatedOn | createdOn | 360 | 480 | 720
	if( isset($_GET['ajax']) ){
		switch($_POST['mode']){
			case 'regen':
				$ret = $videoRegen->getMissingVideoCommands($_POST['tourId']);
				die(json_encode($ret));
			break;
			case 'delete':
				$videoRegen->remove($_POST['id']);
				die(json_encode(array('status'=>'ok')));
			break;
			case 'confirm':
				$ret = $videoRegen->regenerate($_POST['tourId']);
				die(json_encode(array('status'=>'ok')));
			case 'read':
				foreach($videoRegen as $index => $row){
					displayRow($row);
				}
				die;
			break;
			default:
				die;
			break;
		}
	}

	
	
	function displayRow($row){
		echo "<tr id='tr_" . $row['id'] . "'><td>";
		echo "<a href='javascript:void(0);' onClick='deleteId(" . $row['id'] . ")'>X</a>";
		echo "</td>";
		echo "<td>" . $row['tourId'] . "</td>";
		echo "<td>" . $row['mediaId'] . "</td>";
		echo "<td>";
		switch($row['status']){
			case 0:
				echo "waiting";
				break;
			case 1: 
				echo "processing";
				break;
			case 2:
				echo "finished";
				break;
		}
		echo "</td>";
		echo "<td>" . ($row['res360'] == 0 ? "missing" : "") . "</td>";
		echo "<td>" . ($row['res480'] == 0 ? "missing" : "") . "</td>";
		echo "<td>" . ($row['res720'] == 0 ? "missing" : "") . "</td>";
		echo "<td>" . $row['updatedOn'] . "</td>";
		echo "<td>" . $row['createdOn'] . "</td>";
		echo "</tr>";	
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Video Regenerator</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script>
		$(document).ready(function(){
			
		});
		function regenRequest(tourId){
			$.ajax({
				url: '/admin/video-regen.php?ajax=1',
				data:{
					"tourId": tourId,
					"mode": "regen"
				},
				type: "POST"
			}).done(function(msg){
				a = $.parseJSON(msg);
				if( a.length ){
					html = "<h1>TourID: " + tourId + "</h1><br>Missing videos: ";
					for(i=0;i < a.length; i++){
						html += a[i].resolution;
						if( a.length != i + 1 ){
							html += ", ";
						}
					}
					html += "<br><input type='button' value='Yes, regenerate these' onClick='confirmRequest(" + tourId + ")'>";
					$("#regenResults").html(html);
					$("#regenResults").fadeIn();
				}
			});
		}
		function deleteId(id){
			if( confirm("Do you really want to delete this?") ){
				$.ajax({
					url: "/admin/video-regen.php?ajax=1",
					data: {
						"id": id,
						"mode": "delete"
					},
					type: "POST"
				}).done(function(msg){
					$("#tr_" + id).fadeOut();
				});
			}else{
				return;
			}
		}
		function confirmRequest(tourId){
			$.ajax({
				url: '/admin/video-regen.php?ajax=1',
				data:{
					"tourId": tourId,
					"mode": "confirm"
				},
				type: "POST"
			}).done(function(msg){
				//alert("Submitted to be regenrated");
				$("#regenResults").fadeOut();
				refreshList();
			});
		}
		function refreshList(){
			$.ajax({
				url: '/admin/video-regen.php?ajax=1',
				data:{
					"mode": "read"
				},
				type: "POST"
			}).done(function(msg){
				$("#queueBody").fadeOut("slow",function(){
					$(this).html(msg);
					$(this).fadeIn();
				});
			});
		}
		
	</script>
	<style>
	* { 
		font-family: 'Open Sans', sans-serif;
	}
	#queue td{ 
		border: 1px solid black;
		padding: 5px;
	}
	#regenResults {
		border: 1px solid black;
		border-radius: 15px;
		width: 250px;
		padding: 20px;
		display: none;
	}
	#regenResults input {
		padding: 20px;
		font-size: 20px;
	}
	table tr:nth-child(even) {
		background-color: #eee;
	}
	</style>
<body>
	<form method="GET">
		Show TourID: <input type='text' id='tourId' name='tourId'>
		<input type='submit' value='Search'> | <input type='submit' value='ShowAll' onClick='$("#tourId").val("");'><br>
		Regenerate TourID: <input type='text' id='regenTourId' name='regenTourId'> <input type='button' value='Regenerate' onClick='regenRequest($("#regenTourId").val())'>
		<br>
		<div id='regenResults'></div>
	</form>
	<?php
		if( count($videoRegen->count()) ){
			echo "<table id='queue'>";
			echo " <tr id='queueHeader'>";
			echo "	<th>Delete</th>";
			echo "	<th>TourID</th>";
			echo "	<th>MediaID</th>";
			echo "	<th>status</th>";
			echo "	<th>360</th>";
			echo "	<th>480</th>";
			echo "	<th>720</th>";
			echo "  <th>updated on</th>";
			echo "  <th>created on</th>";
			echo "</tr><tbody id='queueBody'>";
			foreach($videoRegen as $key => $row){
				displayRow($row);
			}
			echo "</tbody></table>";
		}

		if(isset($videoRegen->debug))
		{
			echo  '<span class="debug">Result: ';
			print_r($videoRegen->debug);
			echo  '</span>';
		}
	?>
<!-- 		<div>Working Please Stand By</div>
		<div><?php //print_r($videoRegen); ?></div>
 --></body></html>