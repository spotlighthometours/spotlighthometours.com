<?php
require_once('../repository_inc/classes/inc.global.php');
$brokerages = new brokerages();
$brokerageList = $brokerages->listAll();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" media="all" href="../repository_css/tblSorterSkin/style.css">
<script type="text/javascript" src="../repository_inc/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../repository_inc/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../repository_inc/jquery.tablesorter.pager.js"></script>
<script>
	$(document).ready(function() 
    { 
        $("<?PHP for($i=0;$i<count($brokerageList);$i++){echo'#myTable'.$i.', ';}?>").tablesorter(); 
    } 
); 
</script>
</head>
<body>
<?PHP
	$count = 0;
	foreach($brokerageList as $row => $brokerage){
		$count++;
		$userTourTotals = $db->run("SELECT u.userID, u.firstName, u.lastName, u.brokerageID, (SELECT COUNT(tourID) FROM tours WHERE userID=u.userID) as tourCount FROM users u WHERE brokerageID='".$brokerage['brokerageID']."' AND (SELECT COUNT(tourID) FROM tours WHERE userID=u.userID)>0");
		echo '<h2>'.$brokerage['brokerageName'].' - '.$brokerage['brokerageDesc'].'</h2>';
		echo'<table id="myTable'.$count.'" class="tablesorter"> 
				<thead> 
				<tr> 
					<th>Last Name</th> 
					<th>First Name</th> 
					<th>Tour Count</th> 
				</tr> 
				</thead> 
				<tbody> ';
		foreach($userTourTotals as $row => $userInfo){
			echo '
			<tr> 
				<td>'.$userInfo['lastName'].'</td> 
				<td>'.$userInfo['firstName'].'</td> 
				<td>'.$userInfo['tourCount'].'</td> 
			</tr>';
		}
		echo '
			</tbody> 
		</table>
		';
	}
?>
</body>
</html>
