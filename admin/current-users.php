<?php

/* Author: William Merfalen
 * Date: 10-09-2015
 * Purpose: Display active users within the past few minutes
 */

    require(dirname(__FILE__) . '/../repository_inc/classes/inc.global.php');
    $users = new users;
    $users->authenticateAdmin();
	
	$illuminati = new illuminati(0);
	$minutes = 5;
	if( isset($_GET['minutes']) ){
		$minutes = intval($_GET['minutes']);
	}
	$res = $illuminati->activeUsers($minutes)->get();
	function processPage($page){
		if( preg_match("|/tours/tour\.php\?tourid=([0-9]{1,})|",$page,$matches) ){
			return "<span style='color:blue;'>viewing tourID: <b>" . $matches[1] . "</b></span>";
		}
		if( preg_match("|/tours/tour\.php\?&tourid=([0-9]{1,})|",$page,$matches) ){
			return "<span style='color:blue;'>viewing tourID: <b>" . $matches[1] . "</b></span>";
		}
		if( preg_match("|/tours/tour\.php\?mls=([a-zA-Z0-9]+)|",$page,$matches) ){
			return "<span style='color:blue;'>viewing UNBRANDED mls: <b>" . $matches[1] . "</b></span>";
		}
		if( preg_match("|/tours/video\-player\.php|",$page,$matches) ){
			return "<span style='color:blue;'>viewing video player </span>";
		}
		if( preg_match("|/tours/mobile_photo\.php\?w=[0-9]{1,}&src=\.\./images/tours/([0-9]{1,})|",$page,$matches) ){
			return "<span style='color:blue;'>viewing MOBILE tourID: <b>" . $matches[1] . "</b></span>";
		}
		if( preg_match("|^/users/new/|",$page,$matches) ){
			return "<span style='color:grey;'>user cp  --" . $page . "</span>";
		}
		if( preg_match("|^/repository_queries/|",$page,$matches) ){
			return "<span style='color:grey;'>" . $page . "</span>";
		}
		if( preg_match("|/checkout_v2/|",$page) ){
			return "<b style='color:green;'>\$\$\$ checkout \$\$\$</br";
		}
		if( preg_match("|/image_processor/get_photos\.php\?tourid=([0-9]{1,})|",$page,$matches) ){
			return "Downloading photos -- tourID:" . $matches[1];
		}
		return $page;
	}
	function processDate($date){
		//TODO: return friendly date (i.e.: 2 minutes ago)
		return $date;
	}
	function processUserId($userId){
		if( $userId == 0 ){
			return "guest user";
		}
		$users = new users;
		$users->loadUser($userId);
		return "<strong>".$users->firstName . " " . $users->lastName."</strong>";
	}
	
	function processAdmin($adminId){
		global $db;
		if( $adminId == 0 ){
			return "--";
		}
		$res = $db->run("SELECT fullName FROM administrators WHERE administratorID=$adminId");
		return "<strong>".$res[0]['fullName']."</strong>";
	}
?>

<!DOCTYPE html>
<html lang='en' dir='ltr' itemscope itemtype="http://schema.org/QAPage">
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<title>Current Users</title>
	<script src='https://code.jquery.com/jquery-1.11.3.min.js'></script>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/cerulean/bootstrap.min.css'/>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
	<link  href="/repository_inc/cropper/dist/cropper.css" rel="stylesheet">
	<script src="/repository_inc/cropper/dist/cropper.js"></script>
	<script>
			window.setTimeout(function(){
				location.reload();
			},10000);
	</script>
	
<body>
<div class="container">
<?php
	if( empty($res) ){
		echo "<b>No users within the last $minutes minutes</b></body></html>";
		return;
	}
	
	echo "<h1>Users within the last $minutes minutes</h1><br>";
	echo "<table class='table'>
		<thead>
		<tr>
			<th>IP Address</th>
			<th>Page</th>
			<!-- <th>Date</th> -->
			<th>User</th>
			<th>Admin</th>
		</tr>
		</thead>
		<tbody>
	";
	foreach($res as $index => $row){
		echo "<tr>";
		echo "<td>" . $row['remoteIp'] . "</td>";
		echo "<td>" . processPage($row['page']) . "</td>";
		echo "<!-- <td>" . processDate($row['requestDate']) . "</td> -->";
		echo "<td>" . processUserId($row['userId']) . "</td>";
		echo "<td>" . processAdmin($row['adminId']) . "</td>";
		echo "</tr>";
	}
	echo "</tbody></table>";
?>
</div>
</body></html>
