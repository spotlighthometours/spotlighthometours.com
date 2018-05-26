<?php
/**********************************************************************************************
Document: admin_transactions.php
Creator: Brandon Freeman
Date: 03-17-11
Purpose: Lists credit card transactions.
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
	require_once ('../repository_inc/connect.php');

//=======================================================================
// Document
//=======================================================================
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<title>Spotlight Home Tours - Admin - Transactions</title>
		<link REL="SHORTCUT ICON" HREF="../repository_images/icon.ico">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">@import "../repository_css/admin.css";</style>
		<link type="text/css" href="includes/jquery-ui-1.8.9/css/ui-lightness/jquery-ui-1.8.9.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="includes/jquery-ui-1.8.9/js/jquery-1.4.4.min.js"></script>
		<script type="text/javascript" src="includes/jquery-ui-1.8.9/js/jquery-ui-1.8.9.custom.min.js"></script> 
		<script type="text/javascript">
			$(function() {
				$( "#date" ).datepicker();
				$( "#date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			});
		</script>
	</head>
	<body>
<?php
	echo '
		<table>
			<tr>
				<td colspan="10" >
					<form action="' .  $_SERVER['PHP_SELF'] . '" method="post">
						Date: <input type="text" id="date" name="date" value="' . $_POST['date'] . '" />
						<input type="submit" id="submit" name="submit" value="submit" />
	';
	if (isset($_POST['date'])) {
		echo 'Results for : <b>' . date("l F j, Y", strtotime( $_POST['date'] )) . '</b>';
	}
	echo '
					</form>
				</td>
			</tr>
			<tr>
				<th>Order ID</th>
				<th>Name</th>
				<th>Tour ID</th>
				<th>Tour Title</th>
				<th>Add. Prod?</th>
				<th>Merch. Order #</th>
				<th>Order Total</th>
				<th>Date</th>
				<th>Last 4</th>
				<th>Card Type</th>
			</tr>
	';

if (isset($_POST['date'])) {
	$query = '
		SELECT
		u.firstName, u.LastName,
		t.tourID, t.title, t.createdOn as tCreatedOn,
		o.orderID, o.total, o.createdOn as oCreatedOn,
		mt.orderId, mt.transactionDate, mt.cardLastFour,
		ucc.cardType
		FROM (((orders o
		LEFT JOIN merchant_transactions mt ON o.transactionId = mt.transactionId)
		LEFT JOIN usercreditcards ucc ON SUBSTRING(ucc.cardNumber, -4) = mt.cardLastFour AND ucc.userid = mt.userID)
		LEFT JOIN tours t ON o.tourID = t.tourID)
		LEFT JOIN users u ON o.userID = u.userID
		WHERE o.transactionId > -1 
		AND o.createdOn BETWEEN "' . $_POST['date'] . '  00:00:00" AND "' . $_POST['date'] . ' 23:59:59"  
		ORDER BY mt.transactionDate DESC
	';

	$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
	$highlight = true;
	while($result = mysql_fetch_array($r)){
		
		if ($highlight) {
			$class = "highlight";
		} else {
			$class = "nohighlight";
		}
		$highlight = !$highlight;
		
		echo '
			<tr class="' . $class . '" >
				<td>' . $result['orderID'] . '</td>
				<td>' . $result['firstName'] . ' ' . $result['lastName'] . '</td>
				<td>' . $result['tourID'] . '</td>
				<td>' . $result['title'] . '</td>
				<td>';
	
		if ($result['oCreatedOn'] != $result['tCreatedOn']) {
			echo 'YES';
		} else {
			echo 'NO';
		}
			
		echo '</td>
				<td>' . preg_replace("/^(.{1})(.{8})(.{4})(.{4})(.{4})/", "$1-$2-$3-$4-$5-", $result['orderId']) . '</td>
				<td>$' . number_format($result['total'], 2, '.', '') . '</td>
				<td>' . date("F j, Y g:ia", $result['transactionDate']) . '</td>
				<td>' . $result['cardLastFour'] . '</td>
				<td>' . $result['cardType'] . '</td>
			</tr>
		';
	}
	echo '
		</table>
	';
}
?>
	</body>
</html>