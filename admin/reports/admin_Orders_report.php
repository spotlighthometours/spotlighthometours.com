<?php
/**********************************************************************************************
Document: admin_Orders_report.php
Creator: Edward Seniw
Date: 05/24/2013
Purpose: Display a list of orders for a date range
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();

//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$dateSearch = "";	
	if (!empty($_REQUEST['startDate'])) 
		$dateSearch = " AND DATEDIFF(od.addDate, '".$_REQUEST['startDate']."') >= 0";
	if (!empty($_REQUEST['endDate'])) 
		$dateSearch .= " AND DATEDIFF(od.addDate, '".$_REQUEST['endDate']."') <= 0";
	if ($dateSearch == "")
		$dateSearch = " AND DATEDIFF(od.addDate, now()) = 0";
		
	$query = "SELECT *, CASE WHEN od.type = 'tour' THEN tt.tourTypeName ELSE p.productName END AS orderTypeName
		FROM users u, orderdetails od
		LEFT JOIN products p ON od.type = 'product' AND od.productID = p.productID
		LEFT JOIN tourtypes tt ON od.type = 'tour' AND od.productID = tt.tourTypeID, 
		orders o
		LEFT JOIN usercreditcards uc ON o.crardId = uc.crardId
		WHERE o.orderID = od.orderID ".$dateSearch.
		" AND o.userID = u.userID
		ORDER BY o.tourID, od.addDate";
	//echo($query);
	$tours = $db->run($query);
		
?>
    <body>
    <form action="admin_reports.php">
    	<input type="hidden" name="section" value="orders">
    	<div align="center">
	        Start Date<input type="text" name="startDate" id="startDate" value="<?PHP echo $_REQUEST['startDate']; ?>">
    	    End Date<input type="text" name="endDate" id="endDate" value="<?PHP echo $_REQUEST['endDate']; ?>">
            <button type="submit">Submit</button>
        </div>    
        <BR>
		<TABLE style="margin-top: 0px;" WIDTH="100%" BORDER="1" CELLSPACING="2" CELLPADDING="2">
		<TR>
			<TH VALIGN="top">TourID</TH>
			<TH VALIGN="top">Agent</TH>
			<TH VALIGN="top">Tour/Product</TH>
			<TH VALIGN="top">Order Date</TH>
			<TH VALIGN="top">Total</TH>
			<TH VALIGN="top">Broker Total</TH>
			<TH VALIGN="top">Agent Total</TH>
			<TH VALIGN="top">Coupon</TH>
			<TH VALIGN="top">Coupon Amount</TH>
			<TH VALIGN="top">CC Name</TH>
			<TH VALIGN="top">CC Address</TH>
		</TR>
<?php
		if (!empty($tours)) {
			foreach($tours as $row){
				if ($highlight) {
					$class = "highlight";
				} else {
					$class = "";
				}
				$highlight = !$highlight;
					
				echo '
				<tr class="' . $class . '" >
					<td style="text-align: center; color: blue;">'. $row['tourid'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['firstName'] . " ". $row['lastName'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['orderTypeName'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['addDate'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['unitPrice'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['broker_price'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['total'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['coupon'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['coupon_total'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['cardName'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['cardAddress'] . '</td>
				</tr>';
			}
		}
?>
    </table>
    </form>
    </body>
</html>