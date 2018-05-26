<?PHP
	// Include appplication's global configuration
	require_once('../../repository_inc/classes/inc.global.php');
	
	clearCache();
	
	// Create instances of needed objects
	$editors = new editors();
	$tours = new tours($db);
	$users = new users($db);

	// Require admin
	$users->authenticateAdmin();
	
	if(!isset($_REQUEST['id'])||!isset($_REQUEST['startdate'])||!isset($_REQUEST['enddate'])||!isset($_REQUEST['typeID'])){
		die("<H1>REQUIRED INFORMATION WAS NOT PASSED TO THIS PAGE!!!</H1>");
	}
	
	$phpdate = strtotime($_REQUEST['startdate']);
	$prettyStartDate = date('n/j/Y', $phpdate);
	$phpdate = strtotime($_REQUEST['enddate']);
	$prettyEndDate = date('n/j/Y', $phpdate);
	$date = $prettyStartDate . " - " . $prettyEndDate;
	
	$editorName = $editors->get('fullName', $_REQUEST['id']);
	$editorName = $editorName['fullName'];
	$toursTimes = $editors->getEditedToursWithTimes($_REQUEST['id'], $_REQUEST['startdate'], $_REQUEST['enddate'], $_REQUEST['typeID']);
	$tourTypeName = $tours->getTourTypeName($_REQUEST['typeID']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?PHP echo $editorName ?> <?PHP echo $tourTypeName ?> Tours: <?PHP echo $date; ?></title>
</head>
<body>
<h1><?PHP echo $editorName ?> <?PHP echo $tourTypeName ?> Tours: <?PHP echo $date; ?></h1>
<table border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td><h2>Tour</h2></td>
		<td><h2>Time</h2></td>
	</tr>
<?PHP
	foreach($toursTimes as $row => $column){
		$tours->tourID = $column['tourID'];
		$userID = $tours->get('userID');
?>
	<tr>
		<td><a href="../users/users.cfm?pg=toursheet&tour=<?PHP echo $tours->tourID; ?>&user=<?PHP echo $userID; ?>" target="_blank"><?PHP echo $column['tourID'] ?></a></td>
		<td><?PHP echo $column['time'] ?></td>
	</tr>
<?PHP
	}
?>
</table>
</body>
</html>