<?php
/**********************************************************************************************
Document: admin_post_shoot_call_list.php
Creator: Brandon Freeman
Date: 07-19-11
Purpose: Generates a list of tours that were scheduled for the date range.
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();

//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	if (!isset($dbc)) {
		require_once ('../repository_inc/connect.php');
		require_once ('../repository_inc/clean_query.php');
	}
	
//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$debug = true;
	
	// Require Admin Login
	if (!$debug) {
		require_once ('../repository_inc/require_admin.php');
	}
	
	$start = date('Y-m-d', strtotime('-1 day', date("Y-m-d")));
	if(isset($_POST['start'])) {
		$start = $_POST['start'];
	} elseif (isset($_GET['start'])) {
		$start = $_GET['start'];
	}
	
	$end = date('Y-m-d', strtotime('-1 day', date("Y-m-d")));
	if(isset($_POST['end'])) {
		$end = $_POST['end'];
	} elseif (isset($_GET['end'])) {
		$end = $_GET['end'];
	}
	
	$order = 'name';
	if(isset($_POST['order'])) {
		$order = $_POST['order'];
	} elseif (isset($_GET['order'])) {
		$order = $_GET['order'];
	}
	
	echo '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - MLS</title>
        <link type="text/css" href="../repository_css/admin.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="/admin/includes/jquery-ui-1.8.9/css/ui-lightness/jquery-ui-1.8.9.custom.css"  />
		<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-ui-1.8.9.custom.min.js"></script> 
		<script type="text/javascript" >
			$(function() {
				$( "#start" ).datepicker();
				$( "#start" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
				$( "#end" ).datepicker();
				$( "#end" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			});
        </script>
    </head>
    <body>
    	
	';
	
	
	// Create a MySQL PDO
	include ('../repository_inc/data.php');
	$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
	$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$query = 'select b.brokerageName, CONCAT(u.firstName, " ", u.lastName) as name, u.phone, t.address, tp.Scheduledon, tt.tourtypename, p.fullName, t.tourid
		from tours t
		left join tourprogress tp on t.tourid = tp.tourid
		left join users u on t.userID = u.userID
		left join brokerages b on u.brokerageId = b.brokerageID
		left join photographers p on tp.photographer = p.photographerID
		left join tourtypes tt on t.tourtypeid = tt.tourtypeid
		where tp.Scheduledon is not null
		AND tp.Scheduledon BETWEEN date(:start) AND DATE_ADD(:end, INTERVAL 1 DAY) 
		order by ' . $order;
	
	if($stmt = $dbh->prepare($query)) {
		$stmt->bindParam(':start', $start);
		$stmt->bindParam(':end', $end);
		try {
			$stmt->execute();
		} catch (PDOException $e){
			echo $e->getMessage(). '<br />';
		}
		
		$results = $stmt->fetchAll();
		echo '
		<table style="width: 100%;" >
            <tr>
            	<th colspan="8">
                	<FORM ACTION="" METHOD="get">
                        Start: 
                        <INPUT NAME="start" TYPE="text" ID="start" />
                        End: 
                        <INPUT NAME="end" TYPE="text" ID="end" />            
                        <INPUT TYPE="submit" NAME="GO" ID="GO" VALUE="GO" />
                    </FORM>
                </th>
            </tr>
			<tr>
				<th colspan="8" >
					' . $start . ' to ' . $end . ' returned ' . sizeof($results) . ' results.
				</th>
			</tr>
            <tr>
            	<th><a href="admin_post_shoot_call_list.php?start=' . $start . '&end=' . $end . '&order=b.brokerageName" >Brokerage</a></th>
                <th><a href="admin_post_shoot_call_list.php?start=' . $start . '&end=' . $end . '&order=name" >Agent Name</a></th>
                <th><a href="admin_post_shoot_call_list.php?start=' . $start . '&end=' . $end . '&order=phone" >Agent Phone</a></th>
                <th><a href="admin_post_shoot_call_list.php?start=' . $start . '&end=' . $end . '&order=address" >Tour Address</a></th>
                <th><a href="admin_post_shoot_call_list.php?start=' . $start . '&end=' . $end . '&order=Scheduledon" >Scheduled</a></th>
                <th><a href="admin_post_shoot_call_list.php?start=' . $start . '&end=' . $end . '&order=tourtypename" >Tour Type</a></th>
                <th><a href="admin_post_shoot_call_list.php?start=' . $start . '&end=' . $end . '&order=fullName" >Photographer</a></th>
                <th><a href="admin_post_shoot_call_list.php?start=' . $start . '&end=' . $end . '&order=tourid" >Tour ID</a></th>
            </tr>
		';
		$highlight = false;
		foreach ($results as $result) {
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "";
			}
			$highlight = !$highlight;
			echo '
				<tr class="' . $class . '" >
					<td>' . $result['brokerageName'] . '</td>
					<td>' . $result['name'] . '</td>
					<td>' . $result['phone'] . '</td>
					<td>' . $result['address'] . '</td>
					<td>' . $result['Scheduledon'] . '</td>
					<td>' . $result['tourtypename'] . '</td>
					<td>' . $result['fullName'] . '</td>
					<td>' . $result['tourid'] . '</td>
				</tr>
			';
		}
	}
		
?>
        </table>
    </body>
</html>