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
?>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
<LINK HREF="../../repository_css/jquery-ui-1.8.16.custom.css" REL="stylesheet" TYPE="text/css">
<SCRIPT SRC="../../repository_inc/jquery-1.6.2.min.js"></SCRIPT>
<SCRIPT SRC="../../repository_inc/jquery-ui-1.8.16.custom.min.js" TYPE="text/javascript"></SCRIPT>
<SCRIPT SRC="../repository_inc/jquery-ui-timepicker-addon.js" TYPE="text/javascript"></SCRIPT>
<style>
	/* css for timepicker */
	.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }
	.ui-timepicker-div dl { text-align: left; }
	.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
	.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
	.ui-timepicker-div td { font-size: 90%; }
	.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
</style>
<SCRIPT TYPE="text/javascript">
	$('#startDate').datetimepicker({
		dateFormat: 'yy-mm-dd'
	});  						
	$('#endDate').datetimepicker({
		dateFormat: 'yy-mm-dd'
	});  										
</script>
<?PHP
//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$dateSearch = "";	
	if (!empty($_REQUEST['startDate'])) {
		$dateSearch = " AND DATEDIFF(tp.finalizedon, '".$_REQUEST['startDate']."') >= 0";
		$dateEditedSearch = " AND DATEDIFF(tp.EditedOn, '".$_REQUEST['startDate']."') >= 0";
		$dateReEditedSearch = " AND DATEDIFF(tp.ReEditedOn, '".$_REQUEST['startDate']."') >= 0";
		$dateVEditedSearch = " AND DATEDIFF(tp.VideoEditedOn, '".$_REQUEST['startDate']."') >= 0";
		$dateVReEditedSearch = " AND DATEDIFF(tp.VideoReEditedOn, '".$_REQUEST['startDate']."') >= 0";
	}
	if (!empty($_REQUEST['endDate'])) {
		$dateSearch .= " AND DATEDIFF(tp.finalizedon, '".$_REQUEST['endDate']."') <= 0";
		$dateEditedSearch .= " AND DATEDIFF(tp.EditedOn, '".$_REQUEST['endDate']."') <= 0";
		$dateReEditedSearch .= " AND DATEDIFF(tp.ReEditedOn, '".$_REQUEST['endDate']."') <= 0";
		$dateVEditedSearch .= " AND DATEDIFF(tp.VideoEditedOn, '".$_REQUEST['endDate']."') <= 0";
		$dateVReEditedSearch .= " AND DATEDIFF(tp.VideoReEditedOn, '".$_REQUEST['endDate']."') <= 0";
	}
	if ($dateSearch == "") {
		$dateSearch = " AND DATEDIFF(tp.finalizedon, now()) = 0";
		$dateEditedSearch = " AND DATEDIFF(tp.EditedOn, now()) = 0";
		$dateReEditedSearch = " AND DATEDIFF(tp.ReEditedOn, now()) = 0";
		$dateVEditedSearch = " AND DATEDIFF(tp.VideoEditedOn, now()) = 0";
		$dateVReEditedSearch = " AND DATEDIFF(tp.VideoReEditedOn, now()) = 0";
	}
	
	$query = "SELECT tt.tourTypeName AS type, COUNT(t.tourID) as total
			FROM tours t, tourprogress tp, tourtypes tt
			WHERE t.tourID = tp.tourID AND t.tourTypeID = tt.tourTypeID
				AND tp.finalized = 1".$dateSearch.
			" GROUP BY tt.tourTypeName ORDER BY tt.tourTypeName";
			
	$query2 = "SELECT p.productName AS type, COUNT(p.productName) AS total
			FROM tourprogress tp, orders o, orderdetails od, products p
			WHERE tp.tourID = o.tourid AND o.orderID = od.orderID 
			AND od.type = 'product' AND od.productID = p.productID 
			AND ((p.photos + p.hdr_photos > 0
				AND DATEDIFF(tp.ReEditedOn, tp.finalizedOn) > 0".$dateReEditedSearch.")
				OR (p.photos + p.hdr_photos > 0
				AND DATEDIFF(tp.EditedOn, tp.finalizedOn) = 0".$dateEditedSearch.")
				OR  (p.videos > 0
				AND DATEDIFF(tp.VideoReEditedOn, tp.finalizedOn) > 0".$dateVReEditedSearch.")
				OR  (p.videos > 0
				AND DATEDIFF(tp.VideoEditedOn, tp.finalizedOn) = 0".$dateVEditedSearch."))
			GROUP BY p.productName
			ORDER BY p.productName";

	$tours = $db->run($query);
	$products = $db->run($query2);
?>
    <body>
    <form action="admin_reports.php">
    	<input type="hidden" name="section" value="finalizedTours">
    	<div align="center">
	        Start Date<input type="text" name="startDate" id="startDate" value="<?PHP echo $_REQUEST['startDate']; ?>">
    	    End Date<input type="text" name="endDate" id="endDate" value="<?PHP echo $_REQUEST['endDate']; ?>">
            <button type="submit">Submit</button>
        </div>    
        <BR>
		<TABLE style="margin-top: 0px;" WIDTH="100%" BORDER="1" CELLSPACING="2" CELLPADDING="2">
		<TR>
			<TH VALIGN="top">Tour / Product Type</TH>
			<TH VALIGN="top">Total</TH>
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
					<td style="text-align: center; color: blue;">'. $row['type'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['total'] . '</td>
				</tr>';
			}
			if (!empty($products)) {
				if ($highlight) {
					$class = "highlight";
				} else {
					$class = "";
				}
				$highlight = !$highlight;
				
				echo '
				<tr class="' . $class . '" >
					<td style="text-align: center; color: blue;">------------------------------------</td>
					<td style="text-align: center; color: blue;">-----</td>
				</tr>';
			}
		}
		if (!empty($products)) {
			foreach($products as $row){
				if ($highlight) {
					$class = "highlight";
				} else {
					$class = "";
				}
				$highlight = !$highlight;
					
				echo '
				<tr class="' . $class . '" >
					<td style="text-align: center; color: blue;">'. $row['type'] . '</td>
					<td style="text-align: center; color: blue;">'. $row['total'] . '</td>
				</tr>';
			}
		}
?>
    </table>
    </form>
    </body>
</html>