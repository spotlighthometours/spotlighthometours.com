<?php
/**********************************************************************************************
Document: admin_Brokerage_Agents_report.php
Creator: Edward Seniw
Date: 07/26/2013
Purpose: Display all the agent's name, email, phone for all brokerages
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
	
	$query = "SELECT b.brokerageName, concat(u.firstName, ' ', u.lastName) as name, u.phone, u.email
			FROM brokerages b, users u
			WHERE b.brokerageID = u.brokerageID
			ORDER BY b.brokerageName, name";
			
	$tours = $db->run($query);
?>
    <body>
    <form action="admin_reports.php">
    	<input type="hidden" name="section" value="brokerageAgents">
		<TABLE style="margin-top: 0px;" WIDTH="100%" BORDER="1" CELLSPACING="2" CELLPADDING="2">
		<TR>
			<TH VALIGN="top">Brokerage Name</TH>
			<TH VALIGN="top">Agent Name</TH>
			<TH VALIGN="top">Phone</TH>
			<TH VALIGN="top">EMail</TH>
		</TR>
<?php
		if (!empty($tours)) {
			$prevName = "";
			foreach($tours as $row){
				if ($highlight) {
					$class = "highlight";
				} else {
					$class = "";
				}
				$highlight = !$highlight;
				
				if ($prevName == $row['brokerageName']) {
					$name = "";
				} else {
					$name = $row['brokerageName'];
				}
				
				echo '
				<tr class="' . $class . '" >
					<td style="text-align: center; color: blue;">'. $name . '</td>
					<td style="text-align: center; color: blue;">'. $row['name'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['phone'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['email'] . '</td>
				</tr>';
				$prevName = $row['brokerageName'];
			}
		}
?>
    </table>
    </form>
    </body>
</html>