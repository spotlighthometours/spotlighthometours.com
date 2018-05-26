<?php
/**********************************************************************************************
Document: admin_invoice.php
Creator: Brandon Freeman
Date: 05-31-11
Purpose: Lists brokerages.
**********************************************************************************************/

// Force Secure
/*if($_SERVER["HTTPS"] !== "on") {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);	
}*/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	/*ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();*/

//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
	require_once ('../checkout/checkout_merchant.php');
	require_once ('admin_invoice_receipt_email.php');		
	require_once ('../repository_inc/classes/class.security.php');
	$security = new security();
	
//=======================================================================
// Document
//=======================================================================
	// Start the session
	session_start();
	
	$debug = false;
	
	// Require Admin Login
	if (!$debug) {
		require_once ('../repository_inc/require_admin.php');
	}
	
	if (isset($_POST['id'])) {
		$id = CleanQuery($_POST['id']);
	} elseif (isset($_GET['id'])) {
		$id = CleanQuery($_GET['id']);
	}
	
	$transInfo = array();
		// TourIDs
	$tourid = array();
	$index = 0;
	$count = 0;
	if (isset($_POST['tourid_0'])) {
		while (isset($_POST['tourid_' . $index])) {
			if (intval($_POST['tourid_' . $index]) > 0 ) {
				$tourid[$count] = intval($_POST['tourid_' . $index]);
				$count++;
			}
			$index++;
		}
	}
	else {
		while (isset($_GET['tourid_' . $index])) {
			if (intval($_GET['tourid_' . $index]) > 0 ) {
				$tourid[$count] = intval($_GET['tourid_' . $index]);
				$count++;
			}
			$index++;
		}
	}

	//userId
	if (isset($_POST['id'])) {
		$transInfo['userId'] = CleanQuery($_POST['id']);
	} elseif (isset($_GET['id'])) {
		$transInfo['userId'] = CleanQuery($_GET['id']);
	}
	
	//orderTotal
	if (isset($_POST['amount'])) {
		$transInfo['orderTotal'] = CleanQuery($_POST['amount']);
	} elseif (isset($_GET['amount'])) {
		$transInfo['orderTotal'] = CleanQuery($_GET['amount']);
	}
	//invoiceNum
	if (isset($_POST['invoicenum'])) {
		$transInfo['invoiceNum'] = CleanQuery($_POST['invoicenum']);
	} elseif (isset($_GET['invoicenum'])) {
		$transInfo['invoiceNum'] = CleanQuery($_GET['invoicenum']);
	}
	//notes
	if (isset($_POST['notes'])) {
		$transInfo['notes'] = CleanQuery($_POST['notes']);
	} elseif (isset($_GET['notes'])) {
		$transInfo['notes'] = CleanQuery($_GET['notes']);
	}
	
	if (isset($_POST['submit'])) {
		$type = "";
		$g2g = true;
		$reason = '';
		$approved = false;
		$transID = '';
		
		if (isset($_POST['card'])) {
			if ($_POST['card'] == "MANUAL") {
				$type = 'manual';
				//nameOnCard
				if (isset($_POST['cardname'])) {
					$transInfo['nameOnCard'] = CleanQuery($_POST['cardname']);
				}
				//cardType
				if (isset($_POST['cardtype'])) {
					$transInfo['cardType'] = CleanQuery($_POST['cardtype']);
				}
				//cardNumber
				if (isset($_POST['cardnumber'])) {
					$transInfo['cardNumber'] = CleanQuery($_POST['cardnumber']);
				}
				//cardMonth
				if (isset($_POST['cardmonth'])) {
					$transInfo['cardMonth'] = CleanQuery($_POST['cardmonth']);
				}
				//cardYear
				if (isset($_POST['cardyear'])) {
					$transInfo['cardYear'] = CleanQuery($_POST['cardyear']);
				}
				//cardAddress
				if (isset($_POST['cardaddress'])) {
					$transInfo['cardAddress'] = CleanQuery($_POST['cardaddress']);
				}
				//cardCity
				if (isset($_POST['cardcity'])) {
					$transInfo['cardCity'] = CleanQuery($_POST['cardcity']);
				}
				//cardState
				if (isset($_POST['cardstate'])) {
					$transInfo['cardState'] = CleanQuery($_POST['cardstate']);
				}
				//cardZip
				if (isset($_POST['cardzip'])) {
					$transInfo['cardZip'] = CleanQuery($_POST['cardzip']);
				}
				
			} else {
				$type = 'saved';
				
				$query = '
					SELECT *
					FROM usercreditcards 
					WHERE crardId = "' . CleanQuery($_POST['card']) .  '" 
					LIMIT 1
				';	
				$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
				if (mysql_num_rows($r)) {
					$result = mysql_fetch_array($r);
					//nameOnCard
					$transInfo['nameOnCard'] = $result['cardName'];
					//cardType
					$transInfo['cardType'] = $result['cardType'];
					//cardNumber
					$transInfo['cardNumber'] = $security->decrypt($result['cardNumber']);
					//cardMonth
					$transInfo['cardMonth'] = $result['cardMonth'];
					//cardYear
					$transInfo['cardYear'] = $result['cardYear'];
					//cardAddress
					$transInfo['cardAddress'] = $result['cardAddress'];
					//cardCity
					$transInfo['cardCity'] = $result['cardCity'];
					//cardState
					$transInfo['cardState'] = $result['cardState'];
					//cardZip
					$transInfo['cardZip'] = $result['cardZip'];
				}
			}
			
			
			// Verify that we are actually g2g.
			
			//userId
			if (intval($transInfo['userId']) <= 0) {
				$g2g = false;
				$reason .= 'User ID was not supplied.<br />';
			}
			
			//invoiceNum
			if (!strlen($transInfo['invoiceNum'])) {
				$g2g = false;
				$reason .= 'Invoice number was not supplied.<br />';
			}
			
			//orderTotal
			if (floatval($transInfo['orderTotal']) > 0) {
				// Make sure the total is in the right decimal format.
				if ($transInfo['orderTotal'] > 0) {
					$transInfo['orderTotal'] = number_format($transInfo['orderTotal'], 2, '.', '');
				}
			} else {
				$g2g = false;
				$reason .= 'Amount was not supplied.<br />';
			}
			
			//nameOnCard
			if (!strlen($transInfo['nameOnCard'])) {
				$g2g = false;
				$reason .= 'Card name was not supplied.<br />';
			}
			
			//cardNumber
			if (!strlen($transInfo['cardNumber'])) {
				$g2g = false;
				$reason .= 'Card number was not supplied.<br />';
			}
			
			//cardMonth
			if (intval($transInfo['cardMonth']) >= 1 && intval($transInfo['cardMonth']) <= 12 ) {
				if (strlen($transInfo['cardMonth']) == 1) {
					$transInfo['cardMonth'] = '0' . $transInfo['cardMonth'];
				}
			} else {
				$g2g = false;
				$reason .= 'Card month was not supplied.<br />';	
			}
			
			//cardYear
			if (intval($transInfo['cardYear'])) {
				// Make sure the year is a two digit representation.
				if (strlen($transInfo['cardYear']) > 2) {
					$transInfo['cardYear'] = substr($transInfo['cardYear'], -2);
				}
			} else {
				$g2g = false;
				$reason .= 'Card year was not supplied.<br />';
			}
			
			//cardAddress
			if (!strlen($transInfo['cardAddress'])) {
				$g2g = false;
				$reason .= 'Card address was not supplied.<br />';
			}
			
			//cardCity
			if (!strlen($transInfo['cardCity'])) {
				$g2g = false;
				$reason .= 'Card city was not supplied.<br />';
			}
			
			//cardState
			if (!strlen($transInfo['cardState'])) {
				$g2g = false;
				$reason .= 'Card state was not supplied.<br />';
			}
			
			//cardZip
			if (!strlen($transInfo['cardZip'])) {
				$g2g = false;
				$reason .= 'Card zip was not supplied.<br />';
			}
			
			// Get this show on the road.
			if ($g2g) {
				$result = SingleTransaction($transInfo);
				
				//$result['transResult'] = 'approved';
				
				if(isset($result['success'])) {
					if($result['success']) {
						$approved = true;
						$reason .=  'Transaction Result: <span style="color: green;">Transaction Approved!</span><br />';
						if(isset($result['transId'])) {
							$transID = CleanQuery($result['transId']);
						}
					} else {
						$reason .=  'Transaction Result: ' . $result['ErrorMessage'] . '<br />';
					}
				}
				
				if(isset($result['sqlError'])) {
					$reason .=  'SQL Error: ' . $result['sqlError'] . '<br />';
				}
				
				if(isset($result['merchError'])) {
					$reason .=  'Merchant Error: ' . $result['merchError'] . '<br />';
				}
			}
			
			if ($approved) {
				$invoiceID = date("YmdHisu");
				$query = '
					INSERT INTO invoices (invoiceID, number, amount, notes, createdOn, userID_fk, completed, ordernumber)
					VALUES
					("' . $invoiceID . '", "' . $transInfo['invoiceNum'] . '", "' . $transInfo['orderTotal'] . '", "' . $transInfo['notes'] . '", NOW(), "' . $transInfo['userId'] . '", 1, "' . $transID . '")
				';	
				if (mysql_query($query)) {
					if (sizeof($tourid) > 0) {
						$query = '
							INSERT INTO invoices_tour_reference (invoiceID, tourID)
							VALUES
						';
						
						$first = true;
						foreach($tourid as $tid) {
							if($first) {
								$first = !$first;	
							} else {
								$query .= ',';
							}
							$query .= '("' . $invoiceID . '","' . $tid . '")';
						}
						if (!mysql_query($query)) {
							$reason .=  'Query Failed: ' . mysql_error() . '<br />Query Run: ' . $query . '<br />';
						}
					}
					
					// Send the email to the user.
					$query = 'SELECT email FROM users WHERE userID = "' . $id . '" LIMIT 1';
					$r = @mysql_query($query);
					if (mysql_num_rows($r)) {
						$result = mysql_fetch_array($r);
						if (strlen($result['email']) > 0 ) {
							EmailInvoiceReceipt($invoiceID, $result['email']);
						}
					}
					
					// Confirm and Clean
					$reason .=  'Database Result: <span style="color: green;">SUCCESSFUL!</span><br />';
					$reason .=  'Receipt: <a href="/admin/admin_invoice_receipt.php?id=' . $invoiceID . '" >Click Here</a>';
					$transInfo = null;
					$tourid = null;
					
				} else {
					$reason .=  'Query Failed: ' . mysql_error() . '<br />Query Run: ' . $query . '<br />';
				}	
			}
		}
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - Invoice</title>
        <link type="text/css" href="../repository_css/admin.css" rel="stylesheet" />
        <script type="text/javascript" src="admin_invoice.js"></script>
        <script type="text/javascript" >
        	var index = <?php echo sizeof($tourid); ?>;
        </script>
    </head>
    <body>

<?php
	
	if (strlen($reason)) {
		echo '
			<style>
				#danger_box {
					position: relative;
					margin-left: auto;
					margin-right: auto;
					background-color: silver; 
					color: red; 
					width: 650px;
					margin-top: 20px;
					margin-bottom: 20px;
					padding: 10px;
				}
			</style>
			<div id="danger_box" >
			' . $reason . '
			</div>  
		';
		if (strpos($transInfo['invoiceNum'], "_PaySold") > 0)
			echo '<div align="center"><a href="admin_sold_tours.php">Return to Sold Tours</a></div>';
	}
	
	echo '
		<form action="' . basename($_SERVER['PHP_SELF']) . '" method="post">
			<input name="id" type="hidden" value="' . $id . '" />
			<div class="formrow" >
				<div class="row r_name" >Invoice #</div>
				<div class="row r_content" >
					<input name="invoicenum" class="input mid exp" type="text" value="' . $transInfo['invoiceNum'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Amount</div>
				<div class="row r_content" >
					<input name="amount" class="input mid exp" type="text" value="' . $transInfo['orderTotal'] . '" />
				</div>
			</div>
			<div class="formrow frtall" >
				<div class="row r_name" >Notes</div>
				<div class="row r_content r_tall" >
					<textarea name="notes" class="input wide tall left" >' . $transInfo['notes'] . '</textarea>
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Tour IDs</div>
				<div class="row r_content" >
					<input type="button" name="addtourid" value="Add ID" onclick="AddTourID();" />
				</div>
			</div>
			<div id="tourid_box" >
	';
	
	$index = 0;
	while (isset($tourid[$index])) {
		echo '
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						<input id="tourid_' . $index . '" name="tourid_' . $index . '" class="input mid exp" type="text" value="' . $tourid[$index] . '" />
					</div>
				</div>
		';
		$index++;
	}
	
	echo '
			</div>
	';
	
	if ($id) {
		$query = '
			SELECT 
			CONCAT(firstName, " " ,lastName) AS name 
			FROM users 
			WHERE userID = "' . $id .  '" 
			LIMIT 1
		';
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		$result = mysql_fetch_array($r);
		echo '
			<div class="formrow" >
				<div class="row r_name" >Username</div>
				<div class="row r_content" >
					' . $result['name'] . '
				</div>
			</div>
		';
		
		if (isset($_GET['card']) && $_GET['card'] > 0)
			$where = "crardId = ".$_GET['card'];
		else
			$where = "userid = " . $id;
		$query = '
			SELECT *
			FROM usercreditcards 
			WHERE '.$where.' 
			LIMIT 1
		';	
		//echo $query;
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		$default = false;
		while($result = mysql_fetch_array($r)) {
			echo '
				<div class="formrow" >
					<div class="row r_name" >
						Select <input type="radio" name="card" value="' . $result['crardId'] . '" ';	
			if ($result['cardDefault'] || isset($_GET['card'])) {
				echo 'checked';
				$default = true;
			}
			echo ' > 
					</div>
					<!--<div class="row r_content" >
						' . $result['cardNick'] . '
					</div>-->
				<!--</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>-->
					<div class="row r_content" >
						XXXX-XXXX-XXXX-' . substr($security->decrypt($result['cardNumber']), -4) . ' (exp: ' . $result['cardMonth'] . '/' . $result['cardYear'] . ')
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						' . $result['cardName'] . '
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						' . $result['cardAddress'] . ' ' . $result['cardCity'] . ', ' . $result['cardState'] . ' ' . $result['cardZip'] . '
					</div>
				</div>
			';
			
		}
		
		echo '
				<div class="formrow" >
					<div class="row r_name" >
						Select <input type="radio" name="card" value="MANUAL" ';
		if(!$default) {
			echo 'checked';	
		}
		
		echo '> 
					</div>
					<div class="row r_content" >
						Enter a card manually.
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Name on Card</div>
					<div class="row r_content" >
						<input name="cardname" class="input mid exp" type="text" value="' . $transInfo['nameOnCard'] . '" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Type</div>
					<div class="row r_content" >
						<select id="cardtype" name="cardtype">
							<option value="visa" selected="">Visa</option>
							<option value="mastercard">Master Card</option>
							<option value="americanexpress">American Express</option>
						</select>
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Card Number</div>
					<div class="row r_content" >
						<input name="cardnumber" class="input mid exp" maxlength="16" type="text" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Exp Month</div>
					<div class="row r_content" >
						<input name="cardmonth" class="input xsm exp" maxlength="2" type="text" value="' . $transInfo['cardMonth'] . '" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Exp Year</div>
					<div class="row r_content" >
						<input name="cardyear" class="input xsm exp" maxlength="4" type="text" value="' . $transInfo['cardYear'] . '" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Address</div>
					<div class="row r_content" >
						<input name="cardaddress" class="input mid exp" type="text" value="' . $transInfo['cardAddress'] . '" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >City</div>
					<div class="row r_content" >
						<input name="cardcity" class="input sm exp" type="text" value="' . $transInfo['cardCity'] . '" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >State</div>
					<div class="row r_content" >
						<input name="cardstate" class="input xsm exp" maxlength="2" type="text" value="' . $transInfo['cardState'] . '" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name" >Zip</div>
					<div class="row r_content" >
						<input name="cardzip" class="input xsm exp" maxlength="5" type="text" value="' . $transInfo['cardZip'] . '" />
					</div>
				</div>
				<div class="formrow" >
					<div class="row r_name invisible" ></div>
					<div class="row r_content" >
						<input type="submit" name="submit" value="submit" />
					</div>
				</div>
			</form>
		';
	}
?>
    </body>
</html>