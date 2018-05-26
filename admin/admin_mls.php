<?php
/**********************************************************************************************
Document: admin_mls.php
Creator: Brandon Freeman
Date: 06-23-11
Purpose: Checksheet for new MLS and R.C information.
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

	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	
	// Create instances of needed objects
	$mls = new mls();

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
	
	if(isset($_POST['submit'])) {
		$entered = intval($_POST['entered']);
		$visible = 1;
		if(intval($_POST['complete'])) {
			$visible = 0;
		}
		
		$query = '
			UPDATE tour_mls_log SET 
			visible = "' . $visible . '",
			registered = "' . intval($_POST['registered']) . '",
			modified = now() 
			WHERE tourid = "' . CleanQuery($_POST['tourid']) . '" 
			AND mls = "' . CleanQuery($_POST['mls_val']) . '" 
			AND visible = 1
			LIMIT 1
		';
		mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query: " . $query);
		
		if($mls->providerChanged(CleanQuery($_POST['tourid']), CleanQuery($_POST['mls_val']), intval($_POST['mls_provider']))){
			$insertdata = array(
				"tourid" => CleanQuery($_POST['tourid']),
				"mls" => CleanQuery($_POST['mls_val']),
				"mls_provider" => intval($_POST['mls_provider']),
				"action" => "provider", 
				"entered" => $db->now()
			);
			$mls->logMLSActivity($insertdata);
			$mls->updateTourID(CleanQuery($_POST['tourid']), CleanQuery($_POST['mls_val']), CleanQuery($_POST['mls_val']), intval($_POST['mls_provider']));
		}
		
		$query = '
			UPDATE tourprogress SET 
			mls = 1, 
			mlson = now(), 
			realtorcom = "' . intval($_POST['rdc']) . '", 
			realtorcomon = now()
			WHERE tourid = "' . CleanQuery($_POST['tourid']) . '"
			LIMIT 1
		';
		mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query: " . $query);
		
		header('Location: ' . basename($_SERVER['PHP_SELF']));
		ob_flush();
		
	}
	
	if(isset($_POST['attempt'])) {
		$query = 'INSERT INTO tour_mls_log (tourid, mls, action, entered) VALUES ("' . CleanQuery($_POST['tourid']) . '", "' . CleanQuery($_POST['mls_val']) . '", "attempt", now())';
		mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query: " . $query);
		$attempt = $_POST['tourid'] . " @ " . date("F j, Y, g:i a");
	}
	
	if(isset($_REQUEST['complete_all_mls'])){ // THIS IS BACKWARDS!!!!! It completes all REALTOR.COMs
		$query = "SELECT DISTINCT(tourid) FROM tour_mls_log WHERE visible='1' AND action = 'add'";
		$mlsR = mysql_query($query) or die("Query failed with error: " . mysql_error());
		while($row = mysql_fetch_assoc($mlsR)){
			$query = "SELECT orderid FROM orders WHERE tourid = '".$row['tourid']."'";
			$ordersR = mysql_query($query) or die("Query failed with error: " . mysql_error());
			while($row2 = mysql_fetch_assoc($ordersR)){
				$query = "SELECT productID FROM orderdetails WHERE orderID = '".$row2['orderid']."' AND type='product' AND (productID='30' OR productID='71')";
				$isRealtor = mysql_query($query) or die("Query failed with error: " . mysql_error());
				if(!mysql_num_rows($isRealtor)>0){
					$query = "UPDATE tour_mls_log SET visible='0' WHERE tourid='".$row['tourid']."'";
					mysql_query($query) or die("Query failed with error: " . mysql_error());
				}
			}
		}
	}
	
	$query = '
		SELECT DISTINCT tml.*, tm.mlsProvider , t.address, t.userId, tp.mls AS tp_mls, tp.realtorcom AS tp_rdc
		FROM tours t, tour_mls_log tml,  tourprogress tp, tour_to_mls tm,orders o, orderdetails od 
		WHERE tml.tourid = t.tourId AND tml.tourid = tp.tourid
		AND tml.tourid = tm.tourID AND tml.mls = tm.mlsID
		AND tml.visible = 1 AND tml.action = "add"
		AND tm.mlsID IS NOT NULL
		AND tm.tourID = o.tourID AND o.orderID = od.orderID AND (productID=30 OR productID=71)
		ORDER BY t.tourid
	';
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error());
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - MLS</title>
        <link type="text/css" href="../repository_css/admin.css" rel="stylesheet" />
        <style>	
			#attempt {
				padding-left: 6px;
				padding-right: 6px;
				font-size: 14px;
				font-weight: bold;
				font-family: Arial, Helvetica, sans-serif;
				color: white;
				line-height: 30px;
				border: 6px solid #d6e8c9;
				background-color: #6da941; 
			}
		</style>
		<script type="text/javascript" >
			function openPopup(url, x, y) {
				try {
					window.open(url,'Preview',"location=0,status=0,scrollbars=0, width=" + x + ",height=" + y);
				} catch(err) {
					alert("openPopup: " + err);
				}
			}
        </script>
    </head>
    <body>
    	<!--<div style="width: 800px; margin:auto;"><form action="?complete_all_mls=true" method="post"><input type="submit" name="Complete all non Realtor.com" value="Complete all non Realtor.com" /></form></div>-->
		<table style="width: 900px;" >
<?php
	if(isset($attempt)) {
		echo '
			<tr>
				<td id="attempt" colspan="11" >RDC Attempt has been added to the log for: ' . $attempt . '</td>
			</tr>
		';
	}

?>
            <tr>
            	<th>Tour ID</th>
                <th>TS</th>
                <th>Hist.</th>
                <th>Address</th>
                <th>MLS #</th>
                <th>MLS Provider</th>
                <th>Attempted</th>
                <th>Realtor.com</th>
                <th>Complete</th>
                <th>Update</th>
                <th>Attempt</th>
            </tr>
<?php
	$highlight = false;
	while ($result = mysql_fetch_array($r)) {
		$query = 'SELECT * FROM tour_mls_log WHERE tourid='.$result['tourid'].
																						' ORDER BY entered DESC LIMIT 1';
		$history = mysql_query($query) or die("Query failed with error: " . mysql_error());
		$history = mysql_fetch_array($history);
		
		if ($highlight) {
			$class = "highlight";
		} else {
			$class = "";
		}
		$highlight = !$highlight;
			//http://spotlighthometours.com/admin/users/users.cfm?pg=toursheet&tour=28256&user=7706
		echo '
			<tr class="' . $class . '" >
				<form id="form" action="' . basename($_SERVER['PHP_SELF']) . '" method="post">
					<input name="tourid" type="hidden" value="' . $result['tourid'] . '" />
					<input name="mls_val" type="hidden" value="' . $result['mls'] . '" />
					<td style="text-align: center; cursor: pointer; color: blue;" onclick="openPopup(' . chr(39) . '../tours/route.php?tourid=' . $result['tourid'] . chr(39) .', 980, 730)" >' . $result['tourid'] . '</td>
					<td style="text-align: center;" ><a href="users/users.cfm?pg=toursheet&tour=' . $result['tourid'] . '&user=' . $result['userId'] . '" target="_blank" >TS</a></td>
					<td style="text-align: center; cursor: pointer; color: blue;" onclick="openPopup(' . chr(39) . 'admin_mls_hist.php?tourid=' . $result['tourid'] . chr(39) .', 800, 500)" >Hist.</td>
					<td>' . $result['address'] . '</td>
					<td>' . $result['mls'] . '</td>
					<td style="text-align: center;" >' . $mls->providerSelectHTML("mls_provider", $result['mlsProvider']) . '</td>
					<td style="text-align: center;" >
						<input name="registered" type="checkbox" value="1" ';
				
		if (intval($result['registered']) == 1) {
			echo 'checked';
		}
		
		echo ' />
					</td>
					<td style="text-align: center;" >
						'.substr($history['entered'],0,10).'<BR>
						<input name="rdc" type="checkbox" value="1" ';
				
		if (intval($result['tp_rdc']) == 1) {
			echo 'checked';
		}
		
		echo ' />
					</td>
					<td style="text-align: center;" >
						<input name="complete" type="checkbox" value="1" />
					</td>
					<td style="text-align: center;" >
						<input name="submit" type="submit" value="update" />
					</td>
				</form>
				<td style="text-align: center;" >
					<form id="form" action="' . basename($_SERVER['PHP_SELF']) . '" method="post">
						<input name="tourid" type="hidden" value="' . $result['tourid'] . '" />
						<input name="mls_val" type="hidden" value="' . $result['mls'] . '" />
						<input name="attempt" type="submit" value="attempt" />
					</form>
				</td>
			</tr>
		
		';
	}
?>
        </table>
    </body>
</html>