<?php
/**********************************************************************************************
Document: admin_tourtype_pricing_reports.php
Creator: Edward Seniw
Date: 01/4/2013
Purpose: Display all pricing adjustements for the brokerages tourtypes
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
		$orderBy = "b.brokerageID, t.tourTypeName";
	$query = "SELECT b.brokerageID, b.brokerageName, t.tourTypeName, pp.price, p.price as AdjustedPrice
			FROM tourtypes t
			JOIN nf_pricing pp ON t.tourTypeID = pp.itemID AND pp.category = 'standard' AND pp.itemType = 'tour'
			JOIN  nf_pricing p ON t.tourTypeID = p.itemID AND p.category = 'broker' AND p.itemType = 'tour'
			JOIN brokerages b ON p.categoryID = b.brokerageID
			ORDER BY ".$orderBy;
	
	$tourTypes = $db->run($query);
?>
    <body>
            
		<TABLE style="margin-top: 0px;" WIDTH="100%" BORDER="1" CELLSPACING="2" CELLPADDING="2">
		<TR>
			<TH VALIGN="top"><a href="admin_reports.php?section=tourTypePricing&orderBy=b.brokerageName, t.tourTypeName">Brokerage Name</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=tourTypePricing&orderBy=b.brokerageID, t.tourTypeName">Brokerage ID</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=tourTypePricing&orderBy=t.tourTypeName, b.brokerageName">Tour Type</a></TH>
			<TH VALIGN="top">Base Price</TH>
            <TH VALIGN="top">Adjusted Price</TH>      
		</TR>
<?php
		foreach($tourTypes as $row){
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "";
			}
			$highlight = !$highlight;
				
			echo '
			<tr class="' . $class . '" >
				<td style="text-align: center; color: blue;">'. $row['brokerageName'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['brokerageID'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['tourTypeName'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['price'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['AdjustedPrice'] . '</td>
			</tr>';
		}
?>
    </table>
    </body>
</html>