<?php
/**********************************************************************************************
Document: admin_PayWhenSold_report.php
Creator: Edward Seniw
Date: 01/4/2013
Purpose: Display all pay when sold tours
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
		$orderBy = 't.createdOn';
	$query = "SELECT b.BrokerageID, b.brokerageName, b.brokerageDesc, COUNT(u.userID) AS totalAgents
        FROM brokerages b
        JOIN users u ON u.BrokerageID = b.brokerageID
        GROUP BY b.BrokerageID";
	
	$tours = $db->run($query);
		
?>
    <body>
            
		<TABLE style="margin-top: 0px;" WIDTH="100%" BORDER="1" CELLSPACING="2" CELLPADDING="2">
		<TR>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSold&orderBy=t.tourID">TourID</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSold&orderBy=u.mls">Agent ID</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSold&orderBy=u.lastName, u.firstName">Agent Name</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSold&orderBy=b.brokerageName">Brokerage Name</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=payWhenSold&orderBy=t.createdOn">Create Date</a></TH>
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