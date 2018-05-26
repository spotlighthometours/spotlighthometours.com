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
		$orderBy = 'b.brokerageID, pr.productName';
	$query = "SELECT b.brokerageID, b.brokerageName, pr.productName, pp.price, p.price as AdjustedPrice
			FROM products pr
			JOIN nf_pricing pp ON pr.productID = pp.itemID AND pp.category = 'standard' AND pp.itemType = 'product'
			JOIN nf_pricing p ON pr.productID = p.itemID AND p.category = 'broker' AND p.itemType = 'product'
			JOIN brokerages b ON p.categoryID = b.brokerageID
			ORDER BY ".$orderBy;
	
	$products = $db->run($query);
		
?>
    <body>
            
		<TABLE style="margin-top: 0px;" WIDTH="100%" BORDER="1" CELLSPACING="2" CELLPADDING="2">
		<TR>
			<TH VALIGN="top"><a href="admin_reports.php?section=productPricing&orderBy=b.brokerageName, pr.productName">Brokerage Name</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=productPricing&orderBy=b.brokerageID, pr.productName">Brokerage ID</a></TH>
			<TH VALIGN="top"><a href="admin_reports.php?section=productPricing&orderBy=pr.productName, b.brokerageName">Product</a></TH>
			<TH VALIGN="top">Base Price</TH>
            <TH VALIGN="top">Adjusted Price</TH>      
		</TR>
<?php
		foreach($products as $row){
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
				<td style="text-align: center; color: blue;">'. $row['productName'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['price'] . '</td>
				<td style="text-align: center; color: blue;">'. $row['AdjustedPrice'] . '</td>
			</tr>';
		}
?>
    </table>
    </body>
</html>