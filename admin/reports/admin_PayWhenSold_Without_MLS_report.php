<?php
/**********************************************************************************************
Document: admin_product_pricing_reports.php
Creator: Edward Seniw
Date: 01/4/2013
Purpose: Display all pricing adjustements for the brokerages additional products
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
	
	$debug = true;
	
	if (!empty($_REQUEST['orderBy']))
		$orderBy = $_REQUEST['orderBy'];
	else
		$orderBy = 't.createdOn DESC';
	
	$query = "SELECT t.tourID, t.createdOn, DATEDIFF(NOW(), t.createdOn) AS date_difference, 
				o.broker_paySold_total, o.agent_paySold_total, b.brokerageName, u.firstName, u.lastName, u.mls 
			FROM orders o, users u, brokerages b, tours t 
			WHERE t.tourID = o.tourID AND DATEDIFF(NOW(), t.createdOn) > 4 
				AND o.broker_paySold_total + o.agent_paySold_total > 0 AND t.userID = u.userID AND u.brokerageID = b.brokerageID 
				AND t.tourID NOT IN (SELECT tm.tourID FROM tour_to_mls tm WHERE t.tourID = tm.tourID AND tm.mlsID IS NOT NULL) 
			ORDER BY ".$orderBy;
	
	$tours = $db->run($query);
		
?>
    <body>
            
		<TABLE style="margin-top: 0px;" WIDTH="100%" BORDER="1" CELLSPACING="2" CELLPADDING="2">
		<TR>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSoldNoMLS&orderBy=t.tourID">TourID</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSold&orderBy=u.mls">Agent ID</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSold&orderBy=u.lastName, u.firstName">Agent Name</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSoldNoMLS&orderBy=t.brokerageName">Brokerage Name</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSoldNoMLS&orderBy=t.createdOn">Create Date</a></TH>
			<TH VALIGN="top">Days Since Created</TH>
            <TH VALIGN="top">Brokerage Total Due</TH>  
            <TH VALIGN="top">Agent Total Due</TH>      
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
					<td style="text-align: center; color: blue;">'. $row['tourID'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['mls'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['lastName'] . ", ". $row['firstName'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['brokerageName'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['createdOn'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['date_difference'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['broker_paySold_total'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['agent_paySold_total'] . '</td>
				</tr>';
			}
		}
?>
    </table>
    </body>
</html>