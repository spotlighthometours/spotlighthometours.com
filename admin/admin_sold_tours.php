<?php
/**********************************************************************************************
Document: admin_sold_tours.php
Creator: Edward Seniw
Date: 09/21/2012
Purpose: Display all tours that have been sold (according to mls feeds)
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
	if (!empty($_REQUEST['status'])) 
		$status = $_REQUEST['status'];
	else
		$status = "'Not Collected'";
	
	$query = "SELECT t.userID, t.tourID, u.firstName, u.lastName, mp.name AS mlsProviderName, u.mls AS agentID, 
					concat(u.phone,', ', u.phone2) as agentPhone, b.brokerageName, 
					CONCAT(b.brokerageContactPhone, ', ',b.brokerageNotifyPhone, ', ',b.brokerageSchedulePhone) as brokeragePhone, 
					st.mlsID,
					t.address, t.unitNumber, t.city, t.state, t.zipCode, 
					st.soldPrice, st.soldDate, t.createdOn as listedDate,
					CASE WHEN ISNULL(m.membershipType) THEN 'None' ELSE m.membershipType END AS membershipType,
					st.dispositionStatus, o.broker_paySold_total, o.agent_paySold_total, o.crardId
				FROM soldtours st
				JOIN tours t 
				JOIN users u
				JOIN brokerages b
				LEFT JOIN mls_providers mp ON st.mlsProviderID = mp.id
				LEFT JOIN memberships m ON st.conciergeLevel = m.id
				LEFT JOIN orders o ON st.tourID = o.tourID AND o.agent_paySold_total > 0
				WHERE st.tourID = t.tourID 
					AND t.userID = u.userID
					AND u.brokerageID = b.brokerageID
					AND dispositionStatus IN (".$status.")
				ORDER BY soldDate";
	
	$soldTours = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query: " . $query);
		
?>
<script src="../../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->


<script language = "javascript">

	function VerifyDispositionChange(tourID) {
		
		if (document.getElementById(tourID.toString()+"Disposition") != typeof(undefined)) {
			newStatus = document.getElementById(tourID.toString()+"Disposition");
			newStatus = newStatus.value;
		} 
		else
			newStatus = "Not Collected";
				
		if (confirm("Change Tour Number " + tourID.toString() + " status to " + newStatus + "? \nAre you sure?")) {
			ChangeDisposition(tourID, newStatus);
		}
	}
	
	function ChangeDisposition(tourid, newstatus) {
		$.post(
			"../../repository_queries/soldtours_update_disposition_status.php",
			{ tourID : tourid, newStatus : newstatus}, 
			function(response){
				// use console.log for debugging
				console.log(response);
			}
		);
	}
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - sold Tours</title>
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
			table {
				width:95% !important;
			}
			table TH {
				background-color: #C3D9FF; 
			}
			
		</style>
    </head>
    <body>
    	<H1 align="center">Sold Tours</H1>
        
		<TABLE style="margin-top: 0px;" WIDTH="100%" BORDER="1" CELLSPACING="2" CELLPADDING="2">
		<TR>
			<TH HEIGHT="65" VALIGN="top">TourID</TH>
			<TH HEIGHT="65" VALIGN="top">Agent</TH>
			<TH HEIGHT="65" VALIGN="top">MLS Provider</TH>
			<TH HEIGHT="65" VALIGN="top">AgentID</TH>
            <TH HEIGHT="65" VALIGN="top" width="80px">Agent Phone</TH>
            <TH HEIGHT="65" VALIGN="top">Brokerage</TH>
            <TH HEIGHT="65" VALIGN="top" width="80px">Brokerage Phone</TH>
            <TH HEIGHT="65" VALIGN="top">MLS ID</TH>
			<TH HEIGHT="65" VALIGN="top">Address</TH>
			<TH HEIGHT="65" VALIGN="top">City</TH>
            <TH HEIGHT="65" VALIGN="top">State</TH>
            <TH HEIGHT="65" VALIGN="top">Zip Code</TH>
            <TH HEIGHT="65" VALIGN="top">Sold Price</TH>
			<TH HEIGHT="65" VALIGN="top" width="54px">Sold Date</TH>
			<TH HEIGHT="65" VALIGN="top" width="54px">Listed Date</TH>
			<TH HEIGHT="65" VALIGN="top">Concierge Level</TH>
			<TH HEIGHT="65" VALIGN="top">Brokerage Billing Amount</TH>
			<TH HEIGHT="65" VALIGN="top">Agent Billing Amount</TH>
			<TH HEIGHT="65" VALIGN="top" width="100px">Disposition Status<br />
            	<a href="admin_sold_tours.php?status='Not Collected','Withdrawn','Collected'">Show All</a><br />
            	<a href="admin_sold_tours.php?status='Collected'">Show Collected</a><br />
            	<a href="admin_sold_tours.php?status='Not Collected'">Show Not Collected</a>
            </TH>	        
		</TR>
<?php
		while($row = mysql_fetch_assoc($soldTours)){
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "";
			}
			$highlight = !$highlight;
			$address = $row['address'];
			if (strlen($row['unitNumber']))
				$address += " Unit#:".$row['unitNumber'];
				
			echo '
			<tr class="' . $class . '" >
				<td style="text-align: center; cursor: pointer; color: blue;">'. $row['tourID'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['firstName'] . " " . $row['lastName'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['mlsProviderName'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['agentID'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['agentPhone'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['brokerageName'] . '</td>
				<td style="text-align: center; color: blue;">'. str_replace(", ,", ", ", $row['brokeragePhone']) . '</td>
				<td style="text-align: center; color: blue;">'. $row['mlsID'] . '</td>
				<td style="text-align: center; color: blue;">'. $address . '</td>
				<td style="text-align: center; color: blue;">'. $row['city'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['state'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['zipCode'] . '</td>
				<td style="text-align: center; color: blue;">'. '$' . number_format($row['soldPrice'], 0) . '</td>
				<td style="text-align: center; color: blue;">'. $row['soldDate'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['listedDate'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['membershipType'] . '</td>
				<td style="text-align: center; color: blue;">'. '$' . number_format($row['broker_paySold_total'], 0) . '</td>
				<td style="text-align: center; color: blue;">'. '$' . number_format($row['agent_paySold_total'], 0) . '</td>
				<td style="text-align: center;">
					<select style="position:relative;" name="'. $row['tourID'] . 'Disposition" id="'. 
							$row['tourID'] . 'Disposition" onChange="VerifyDispositionChange('. $row['tourID'] . ')">';
?>
						<option value="Not Collected" <?php if ($row['dispositionStatus']=="Not Collected") echo "selected"; ?>>Not Collected</option>
						<option value="Collected" <?php if ($row['dispositionStatus']=="Collected") echo "selected"; ?>>Collected</option>
						<option value="Withdrawn" <?php if ($row['dispositionStatus']=="Withdrawn") echo "selected"; ?>>Withdrawn</option>
                	</select>
                    <BR />
                    <a href="../admin/admin_invoice.php?id=<?php echo($row['userID']."&tourid_0=".$row['tourID']."&amount=".$row['agent_paySold_total']."&notes=Tour was sold and this is the outstanding Agent Amount.&invoicenum=".$row['tourID']."_PaySold&card=".$row['crardId']);?>">Bill Agent</a>
                </td>
			</tr>
<?php
		}
?>
    </table>
    </body>
</html>