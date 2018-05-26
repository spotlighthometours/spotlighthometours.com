<?php
	if(!isset($dbc)) {
		require_once ('../repository_inc/connect.php');
		require_once ('../repository_inc/clean_query.php');
	}
	require_once ('../repository_inc/emailer_inline.php');
	
	if(isset($_GET['id']) && isset($_GET['address'])) {
		EmailInvoiceReceipt($_GET['id'], $_GET['address']);
	}
	
	function EmailInvoiceReceipt($id, $address) {
		try {
			$log = '';
			$body = '';
			
			$query = '
				SELECT 
				i.invoiceID, i.number, i.amount, i.notes, i.createdOn, i.userID_fk, i.ordernumber, u.BrokerageID,
				CONCAT(u.firstName, " ", u.lastName) AS name
				FROM invoices i
				LEFT JOIN users u ON u.userID = i.userID_fk 
				WHERE invoiceID = "' . CleanQuery($id) .  '"
				LIMIT 1
			';
			
			$r = @mysql_query($query);
			
			if (mysql_num_rows($r)) {
				$invoice = mysql_fetch_array($r);
				$affiliateID = mysql_query("SELECT affiliatePhotographerID FROM brokerages WHERE brokerageID='".$invoice['BrokerageID']."'");
				$affiliateID = mysql_result($affiliateID, 0, 'affiliatePhotographerID');
				if($affiliateID>0){
					$affiliateEmail = mysql_query("SELECT email FROM photographers WHERE photographerID='".$affiliateID."'");
					$affiliateEmail = mysql_result($affiliateEmail, 0, 'email');
				}
				$body .= '
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
					$body .=  '
					<tr>
						<td class="line_title" >Tour:</td>
						<td>' . $result['address'] . ' (' . $result['tourID'] . ')</td>
					</tr>
					';
				}
				
				$body .= '
					
					<tr>
						<td class="line_title" >Notes:</td>
						<td class="line_title" >' . $invoice['notes'] . '</td>
					</tr>
					
				</table>
				';
				
				SendMail('Spotlight Invoice Receipt', $body, $address, 'info@spotlighthometours.com');
				
				// We need to stop sending emails to Sheri.
				SendMail('Spotlight Invoice Receipt', $body, 'billing@spotlighthometours.com', 'info@spotlighthometours.com');
				
				if(($affiliateID>0)&&(!empty($affiliateEmail))){
					SendMail('Spotlight Invoice Receipt', $body, $affiliateEmail);
				}
				
			} else {
				$log .= "Transaction could not be found.\n";	
			}
		} catch (Exception $e) {
			$log .= date("YmdHis") . " - ERROR: " . $e->getMessage() . "\n";
		}
	}
?>