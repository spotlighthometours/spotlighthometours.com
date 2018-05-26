<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Receipt</title>
    </head>
    <body>
<?php
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
	
	if (isset($_POST['id'])) {
		$id = CleanQuery($_POST['id']);
	} elseif (isset($_GET['id'])) {
		$id = CleanQuery($_GET['id']);
	}
	
	$query = '
		SELECT 
		i.invoiceID, i.number, i.amount, i.notes, i.createdOn, i.userID_fk, i.ordernumber,
		CONCAT(u.firstName, " ", u.lastName) AS name
		FROM invoices i
		LEFT JOIN users u ON u.userID = i.userID_fk 
		WHERE invoiceID = "' . $id .  '"
		LIMIT 1
	';	
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
	if (mysql_num_rows($r)) {
		$invoice = mysql_fetch_array($r);
		echo '
		<style>
			.line_title {
				width: 155px;
			}
		</style>
		<table>
			<tr>
				<th colspan="2" >Spotlight Tours - Receipt</th>
			</tr>
			<tr>
				<td class="line_title" >Invoice ID:</td>
				<td>' . $invoice['invoiceID'] . '</td>
			</tr>
			<tr>
				<td class="line_title" >Invoice Number:</td>
				<td>' . $invoice['number'] . '</td>
			</tr>
			<tr>
				<td class="line_title" >Merch. Order Number:</td>
				<td>' . $invoice['ordernumber'] . '</td>
			</tr>
			<tr>
				<td class="line_title" >Date:</td>
				<td>' . $invoice['createdOn'] . '</td>
			</tr>
			<tr>
				<td class="line_title" >Name:</td>
				<td>' . $invoice['name'] . ' (' . $invoice['userID_fk'] . ')</td>
			</tr>
			<tr>
				<td class="line_title" >Amount:</td>
				<td>$' . number_format($invoice['amount'], 2, '.', '') . '</td>
			</tr>
		';
		
		$query = '
			SELECT i.tourID, t.address
			FROM invoices_tour_reference i
			LEFT JOIN tours t ON i.tourID = t.tourID 
			WHERE invoiceID = "' . $invoice['invoiceID'] . '"';
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		while ($result = mysql_fetch_array($r)) {
			echo '
			<tr>
				<td class="line_title" >Tour:</td>
				<td>' . $result['address'] . ' (' . $result['tourID'] . ')</td>
			</tr>
			';
		}
		
		echo '
			
			<tr>
				<td class="line_title" >Notes:</td>
				<td class="line_title" >' . $invoice['notes'] . '</td>
			</tr>
			
		</table>
		';
	} else {
		echo 'Transaction could not be found. <br />';	
	}
?>
    </body>
</html>