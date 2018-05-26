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
	
	$tourTypes = $tours->getTourTypeList();
	$editorsList = $editors->listAll();
	$removeType = array(
		"35"=>true,
		"34"=>true,
		"33"=>true,
		"32"=>true,
		"30"=>true,
		"18"=>true,
		"17"=>true,
		"1"=>true,
		"27"=>true
	);
	foreach($tourTypes as $row => $column){
		if($removeType[$column['tourTypeID']]){
			unset($tourTypes[$row]);
		}
	}
	
	function averageTime($totalTime, $totalEdited){
		$name = $column['tourTypeName'];
		$times = explode(":", $totalTime);
		$hours = intval($times[0]);
		$minutes = intval($times[1]);
		$seconds = intval($times[2]);
		$secFromHours = 0;
		$secFromMinutes = 0;
		if($hours>0){
			$secFromHours = ($hours*60)*60;
		}
		if($minutes>0){
			$secFromMinutes = $minutes*60;
		}
		$seconds += ($secFromHours+$secFromMinutes);
		$averageSeconds = ceil($seconds/$totalEdited);
		$hours = 0;
		$minutes = 0;
		$seconds = 0;
		if($averageSeconds>=60){
			$minFromSeconds = $averageSeconds/60;
			if(is_float($minFromSeconds)){
				$minFromSeconds = floor($minFromSeconds);
				$remainingSeconds = $averageSeconds - ($minFromSeconds*60);
				$seconds = $remainingSeconds;
			}else{
				$seconds = 0;
			}
			$minutes = $minFromSeconds;
		}else{
			$seconds = $averageSeconds;
		}
		if($minutes>=60){
			$hourFromMinutes = $minutes/60;
			if(is_float($hourFromMinutes)){
				$hourFromMinutes  = floor($hourFromMinutes);
				$remainingMinutes = $minutes - ($hourFromMinutes*60);
				$minutes = $remainingMinutes;
				$hours = $hourFromMinutes;
			}else{
				$mintues = 0;
			}
		}
		return $hours.':'.$minutes.':'.$seconds;
	}
?>
<HTML>
	<HEAD>
	<TITLE>Editor Report</TITLE>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
	<LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
	<script src="/javascripts/javascript.js"></script>
	<link type="text/css" href="/admin/includes/jquery-ui-1.8.9/css/ui-lightness/jquery-ui-1.8.9.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-ui-1.8.9.custom.min.js"></script>
	<script type="text/javascript">
	    $(function() {
		    $( "#startdate" ).datepicker();
		    $( "#startdate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#enddate" ).datepicker();
		    $( "#enddate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	    });
	</script>
	</HEAD>
	<BODY>
<FORM ACTION="" METHOD="get">
		Date Range*:<BR>
		<INPUT NAME="startdate" TYPE="text" ID="startdate" VALUE="<?PHP echo $_REQUEST['startdate'] ?>"/>
		<INPUT NAME="enddate" TYPE="text" ID="enddate" VALUE="<?PHP echo $_REQUEST['enddate'] ?>" />
		<INPUT TYPE="submit" NAME="GO" ID="GO" VALUE="GO" />
	</FORM>
<?PHP
	if(!isset($_REQUEST['startdate'])){
?>
<h1>Tours All Time</h1>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td align="center"><h2>Tours</h2></td>
		<td align="center"><h2>Number Edited</h2></td>
		<td align="center"><h2>Total Time</h2></td>
		<td align="center"><h2>Average Time</h2></td>
	</tr>
<?PHP
	foreach($tourTypes as $row => $column){
		$totalTime = $tours->getEditTime($column['tourTypeID']);
		$totalEdited = $tours->countEdited($column['tourTypeID']);
		$averageTime = averageTime($totalTime, $totalEdited);
?>
	<tr>
		<td align="center"><h2><?PHP echo $column['tourTypeName'] ?></h2></td>
		<td align="center" style="font-size:20px;"><?PHP echo $totalEdited ?></td>
		<td align="center" style="font-size:20px;"><?PHP echo $totalTime ?></td>
		<td align="center" style="font-size:20px;"><?PHP echo $averageTime ?></td>
	</tr>
<?PHP
	}
?>
</table>
<?PHP
		die("<h1>PLEASE SELECT A DATE RANGE</h1>");
	}
	$phpdate = strtotime($_REQUEST['startdate']);
	$prettyStartDate = date('n/j/Y', $phpdate);
	$phpdate = strtotime($_REQUEST['enddate']);
	$prettyEndDate = date('n/j/Y', $phpdate);
	$date = $prettyStartDate . " - " . $prettyEndDate;
?>
<h1>Editors: <?PHP echo $date; ?></h1>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
		<tr style="font-weight:bold;">
		<td align="center" valign="top"><h2>Editors</h2></td>
		<?PHP
	foreach($tourTypes as $row => $column){
?>
		<td align="center" valign="top"><h2><?PHP echo $column['tourTypeName'] ?></h2></td>
		<?PHP
	}
?>
		<td align="center" valign="top"><h2>Total</h2></td>
		<td align="center" valign="top"><h2>Total Time</h2></td>
		<td align="center" valign="top"><h2>Average Time</h2></td>
	<tr>
		<?PHP
	foreach($editorsList as $row => $column){
		$editTime = $editors->getEditTime($column['id'], $_REQUEST['startdate'], $_REQUEST['enddate']);
?>
	<tr>
		<td style="font-weight:bold;"><?PHP echo $column['fullName'] ?></td>
		<?PHP
		$total = 0;
		foreach($tourTypes as $trow => $tcolumn){
			$count = $editors->countEdited($column['id'], $_REQUEST['startdate'], $_REQUEST['enddate'], $tcolumn['tourTypeID']);
			$total += $count;
?>
		<td align="center" valign="top" style="font-size:20px !important;"><a href="tours-times-popup.php?startdate=<?PHP echo $_REQUEST['startdate']; ?>&enddate=<?PHP echo $_REQUEST['enddate']; ?>&typeID=<?PHP echo $tcolumn['tourTypeID']; ?>&id=<?PHP echo $column['id']; ?>" target="_blank"><?PHP echo $count; ?></a></td>
		<?PHP
		}
		$editAverageTime = averageTime($editTime, $total);
?>
		<td align="center" valign="top" style="font-size:20px; font-weight:bold;"><?PHP echo $total; ?></td>
		<td align="center" valign="top" style="font-size:20px; font-weight:bold;"><?PHP echo $editTime; ?></td>
		<td align="center" valign="top" style="font-size:20px; font-weight:bold;"><?PHP echo $editAverageTime; ?></td>
	</tr>
		<?PHP
	}
?>
	</table>
<h1>Tours: <?PHP echo $date; ?></h1>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td align="center"><h2>Tours</h2></td>
		<td align="center"><h2>Number Edited</h2></td>
		<td align="center"><h2>Total Time</h2></td>
		<td align="center"><h2>Average Time</h2></td>
	</tr>
<?PHP
	foreach($tourTypes as $row => $column){
		$totalTime = $tours->getEditTimeByDate($_REQUEST['startdate'], $_REQUEST['enddate'], $column['tourTypeID']);
		$totalEdited = $tours->countEditedByDate($_REQUEST['startdate'], $_REQUEST['enddate'], $column['tourTypeID']);
		$averageTime = averageTime($totalTime, $totalEdited);
?>
	<tr>
		<td align="center"><h2><?PHP echo $column['tourTypeName'] ?></h2></td>
		<td align="center" style="font-size:20px;"><?PHP echo $totalEdited ?></td>
		<td align="center" style="font-size:20px;"><?PHP echo $totalTime ?></td>
		<td align="center" style="font-size:20px;"><?PHP echo $averageTime ?></td>
	</tr>
<?PHP
	}
?>
</table>
<h1>Editors Time Not Logged: <?PHP echo $date; ?></h1>
<table border="0" cellspacing="0" cellpadding="10">
	<tr>
		<td><h2>Editor</h2></td>
		<td><h2>Tours</h2></td>
	</tr>
<?PHP
	foreach($editorsList as $row => $column){
		$without = $editors->editedWithoutStartTime($column['id'], $_REQUEST['startdate'], $_REQUEST['enddate']);
		$tourIDs = "";
		$first = true;
		foreach($without as $row2 => $column2){
			if(!$first){
				$tourIDs .= ", ";
			}
			$tourIDs .= $column2['tourID'];
			$first = false;
		}
?>
	<tr>
		<td valign="top"><h2><?PHP echo $column['fullName'] ?></h2></td>
		<td valign="top"><?PHP echo $tourIDs ?></td>
	</tr>
<?PHP
	}
?>
</table>
</body>
</html>