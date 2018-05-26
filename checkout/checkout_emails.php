<?php
/**********************************************************************************************
Document: checkout_emails.php
Creator: Brandon Freeman
Date: 03-03-11
Purpose: Sends emails about the order. (for Ajax request)  
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();
	
	// Start the session
	session_start();
//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
	require_once ('../repository_inc/phpgmailer/class.phpgmailer.php');
	require_once ('checkout_orderpricing.php');
	
//=======================================================================
// Document
//=======================================================================
	
	// Set orderid.
	if (isset($_POST['orderid'])) {
		$orderId = cleanQuery($_POST['orderid']);
	} elseif (isset($_GET['orderid'])) {
		$orderId = cleanQuery($_GET['orderid']);
	}else {
		$orderId = -1;
	}
	
	$query = '
	SELECT 
    tourID, orderID, subTotal, salesTax, total, broker_total, coupon, coupon_total
    FROM orders
    WHERE orderID = ' . $orderId . '
	';
	
	$o = mysql_query($query) or die("Query failed with error:<br />" . mysql_error() . "<br />Query being run:<br />" . $query);
	$order = mysql_fetch_array($o);
	
	if ($order) {
	
		$query = '
		SELECT 
		od.type, od.productID, od.quantity, od.unitPrice,
		tt.tourTypeName,
		p.productName
		FROM orderdetails od 
		LEFT JOIN products p ON od.productID = p.productID AND od.type = "product"
		LEFT JOIN tourTypes tt ON od.productID = tt.tourTypeID AND od.type = "tour"
		WHERE od.orderID = ' . $orderId . '
		';
		
		$od = mysql_query($query) or die("Query failed with error:<br />" . mysql_error() . "<br />Query being run:<br />" . $query);
		
		$body = '
			<table>
				<tr>
					<th>Item</th>
					<th>Qty</th>
					<th>Unit Price</th>
					<th>Total Price</th>
				</tr>
		';
		
		while ($orderDetails = mysql_fetch_array($od)) {
			$name = "";
			if (strlen($orderDetails['tourTypeName']) > 0) {
				$name = $orderDetails['tourTypeName'];
			} elseif (strlen($orderDetails['productName']) > 0) {
				$name = $orderDetails['productName'];
			}
			
			$body .= '
				<tr>
					<td>' . $name . '</td>
					<td>' . $orderDetails['quantity'] . '</td>
					<td>$' . number_format($orderDetails['unitPrice'], 2, '.', '') . '</td>
					<td>' . number_format(($orderDetails['unitPrice'] * $orderDetails['quantity']), 2, '.', '') . '</td>
				</tr>
			';
		}
		
		if (strlen($order['coupon']) > 0 && $order['coupon'] != -1) {
			$body .= '
			<tr>
				<td colspan="2" ></td>
				<td>Coupon:</td>
				<td>' . $order['coupon'] . '</td>
			</tr>
			<tr>
				<td colspan="2" ></td>
				<td>Coupon Value:</td>
				<td>$' . number_format($order['coupon_total'], 2, '.', '') . '</td>
			</tr>
		';
		}
		
		$body .= '
			<tr>
				<td colspan="2" ></td>
				<td>Subtotal:</td>
				<td>$' . number_format($order['subTotal'], 2, '.', '') . '</td>
			</tr>
			<tr>
				<td colspan="2" ></td>
				<td>Sales Tax:</td>
				<td>$' . number_format($order['salesTax'], 2, '.', '') . '</td>
			</tr>
		';
		
		if (floatval($order['broker_total']) > 0) {
			$body .= '
			<tr>
				<td colspan="2" ></td>
				<td>Broker Total:</td>
				<td>$' . number_format($order['broker_total'], 2, '.', '') . '</td>
			</tr>
		';
		}
		
		$body .= '
			<tr>
				<td colspan="2" ></td>
				<td>Order Total:</td>
				<td>$' . number_format($order['total'], 2, '.', '') . '</td>
			</tr>
		</table>
		';
		
		$query = '
		SELECT
		b.brokerageName,
		u.firstName,
		u.lastName,
		u.phone,
		u.email,
		t.createdOn,
		t.tourID,
		t.title,
		tt.tourTypeName,
		t.address,
		t.city,
		t.state,
		t.zipCode,
		t.listPrice,
		t.sqFootage,
		t.bedrooms, 
		t.bathrooms, 
		t.mls, 
		t.description, 
		t.additionalInstructions
		from ((tours t
		LEFT JOIN users u ON t.userID = u.userID)
		LEFT JOIN brokerages b ON u.brokerageID = b.brokerageID)
		LEFT JOIN tourtypes tt ON t.tourTypeID = tt.tourTypeID
		WHERE tourID = ' . $order['tourID'] . ' LIMIT 1
		';
		
		$r = mysql_query($query) or die("Query failed with error:<br />" . mysql_error() . "<br />Query being run:<br />" . $query);
		$result = mysql_fetch_array($r);
				
		$user_msg = '
			Dear ' . $result['firstName'] . ',<br />
			<br />
			Thank you for ordering the best home tour available today through Spotlight<br />
			Home Tours. Please anticipate a call from our tour coordinator within 24<br />
			hours to arrange access to your listing. If you have any questions or have<br />
			not heard from our representative within 24 hours, please contact us<br />
			immediately at customerservice@spotlighthometours.com so we can make prompt<br />
			arrangements for your tour to be completed.<br />
	
			If you have any questions about our packages please feel free to contact us<br />
			at 801-466-4074 or visit us at www.spotlighthometours.com and view our demos<br />
			and addons pages.<br />
			<br />
			Thanks again,<br />
			<br />
			Spotlight Home Tours<br />
			<br />
			<b>Tour ID:</b> ' . $result['tourID'] . '<br />
			<b>Address:</b> ' . $result['address'] . '<br />
			' . $body . '<br />
			';
				
		$admin_msg = '
		A new tour order has been recieved from the user ' . $result['firstName'] . ' ' . $result['lastName'] . '.<br />
		<b>Agent Brokerage:</b> ' . $result['brokerageName'] . '<br />
		<b>Phone:</b> ' . $result['phone'] . '<br />
		<b>Email:</b> ' . $result['email'] . '<br />
		<br />
		<b>Ordered on:</b> ' . $result['createdOn'] . '<br />
		<b>Tour ID:</b> ' . $result['tourID'] . '<br />
		<b>Tour Title:</b> ' . $result['title'] . '<br />
		<b>Tour Type:</b> ' . $result['tourTypeName'] . '<br />
		<b>Address:</b> ' . $result['address'] . '<br />
		<b>City:</b> ' . $result['city'] . '<br />
		<b>State:</b> ' . $result['state'] . '<br />
		<b>Zip Code:</b> ' .$result['zipCode']  . '<br />
		<b>List Price:</b> ' . $result['listPrice'] . '<br />
		<b>Total Square Feet:</b> ' . $result['sqFootage'] . '<br />
		<b>Bedrooms:</b> ' . $result['bedrooms'] . '<br />
		<b>Bathrooms:</b> ' . $result['bathrooms'] . '<br />
		<b>MLS:</b> ' . $result['mls'] . '<br />
		<br />
		<b>Description:</b> ' . $result['description'] . '<br />
		<br />
		<b>Instructions:</b> ' . $result['additionalInstructions'] . '<br />
		<br />
		' . $body . '<br />
		';
		
		$mail = new PHPGMailer();
		$mail->Username = 'info@spotlighthometours.com'; 
		$mail->Password = 'bailey22';
		$mail->From = 'info@spotlighthometours.com'; 
		$mail->FromName = 'Spotlight';
		$mail->Subject = 'Your Spotlight Order Confirmation';
		if ($_SESSION['debug'] == true) {
			$mail->AddAddress('brandon@spotlighthometours.com');
		} else {
			$mail->AddAddress('neworders@spotlighthometours.com');
		}
		$mail->Body = $admin_msg;
		$mail->IsHTML(true); // send as HTML
		$mail->Send();
	
		$mail = new PHPGMailer();
		$mail->Username = 'info@spotlighthometours.com'; 
		$mail->Password = 'bailey22';
		$mail->From = 'info@spotlighthometours.com'; 
		$mail->FromName = 'Spotlight';
		$mail->Subject = 'Your Spotlight Order Confirmation';
		$mail->AddAddress($result['email']);
		$mail->Body = $user_msg;
		$mail->IsHTML(true); // send as HTML
		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "SUCCESS";
		}
	
	} else {
		echo 'no order found: ' . $query;	
	}
	
?>