<?php
/**********************************************************************************************
Document: admin_Finalized_Tours.php
Creator: Edward Seniw
Date: 05/24/2013
Purpose: Display a total count for each tourtype in a date range
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
	if (!empty($_REQUEST['startDate'])) {
		$dateSearch = " AND DATEDIFF(t.CreatedOn, '".$_REQUEST['startDate']."') >= 0";
	}
	if (!empty($_REQUEST['endDate'])) {
		$dateSearch .= " AND DATEDIFF(t.CreatedOn, '".$_REQUEST['endDate']."') <= 0";
	}
	if ($dateSearch == "") {
		$dateSearch = " AND DATEDIFF(t.CreatedOn, now()) = 0";
	}
	
	$query = "SELECT p.fullName, tt.tourTypeName, count(t.tourID) as total 
		FROM brokerages b, users u, tours t, tourtypes tt, photographers p
		WHERE p.isAffiliate = 1 AND p.photographerID = b.affiliatePhotographerID 
		AND b.brokerageID = u.brokerageID
		AND t.userID = u.userID AND t.tourTypeID = tt.tourTypeID".$dateSearch.
		" GROUP BY p.fullName, tt.tourTypeName
		ORDER BY p.fullName, tt.tourTypeName";
			
	$tours = $db->run($query);
?>
    <body>
    <form action="admin_reports.php">
    	<input type="hidden" name="section" value="affiliateTotalTours">
    	<div align="center">
	        Start Date<input type="text" name="startDate" id="startDate" value="<?PHP echo $_REQUEST['startDate']; ?>">
    	    End Date<input type="text" name="endDate" id="endDate" value="<?PHP echo $_REQUEST['endDate']; ?>">
            <button type="submit">Submit</button>
        </div>    
        <BR>
		<TABLE style="margin-top: 0px;" WIDTH="100%" BORDER="1" CELLSPACING="2" CELLPADDING="2">
		<TR>
			<TH VALIGN="top">Affiliate Photographer</TH>
			<TH VALIGN="top">Tour Type</TH>
			<TH VALIGN="top">Total Tours</TH>
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
				
				if ($prevName == $row['fullName']) {
					$name = "";
				} else {
					$name = $row['fullName'];
				}
				
				echo '
				<tr class="' . $class . '" >
					<td style="text-align: center; color: blue;">'. $name . '</td>
					<td style="text-align: center; color: blue;">'. $row['tourTypeName'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['total'] . '</td>
				</tr>';
				$prevName = $row['fullName'];
			}
		}
?>
    </table>
    </form>
    </body>
</html>