<?php
/**********************************************************************************************
Document: admin_pricingbyuser.php
Creator: Brandon Freeman
Date: 04-21-11
Purpose: Lists tour and product pricing for a given userid.
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	/*ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();*/

//=======================================================================
// Includes
//=======================================================================

	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
	require_once ('../transactional/transactional_pricing.php');

//=======================================================================
// Document
//=======================================================================
	
	if (isset($_POST['userid'])) {
		$userid = CleanQuery($_POST['userid']);
	} elseif (isset($_GET['userid'])) {
		$userid = CleanQuery($_GET['userid']);
	}
	
	if (isset($_POST['city'])) {
		$city = CleanQuery($_POST['city']);
	} elseif (isset($_GET['city'])) {
		$city = CleanQuery($_GET['city']);
	}
	
	if (isset($_POST['zip'])) {
		$zip = CleanQuery($_POST['zip']);
	} elseif (isset($_GET['zip'])) {
		$zip = CleanQuery($_GET['zip']);
	}
	
	if (isset($userid)) {
		$query = 'SELECT firstName, lastName, city, zipCode, BrokerageID FROM users WHERE userID = ' . $userid . ' LIMIT 1';
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		$result = mysql_fetch_array($r);
		$name = $result['firstName'] . ' ' . $result['lastName'];
		$brokerage = $result['BrokerageID'];
		if (!isset($city)) $city = $result['city'];
		if (!isset($zip)) $zip = $result['zipCode'];
		
		$query = 'SELECT tourTypeID FROM tourtypes';
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		$tours = array();
		$counter = 0;
		while($result = mysql_fetch_array($r)) {
			$tours[$counter]['id'] = $result['tourTypeID'];
			$tours[$counter]['qty'] = 1;
			$counter ++;
		}
		
		$query = 'SELECT productID FROM products WHERE productName IS NOT NULL';
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		$items = array();
		$counter = 0;
		while($result = mysql_fetch_array($r)) {
			$items[$counter]['id'] = $result['productID'];
			$items[$counter]['qty'] = 1;
			$counter ++;
		}
		
		$tourpricing = pricing( $tours, $items, $city, $zip, $brokerage, $userid, '');
		//$tourpricing = ApplyMileage($tourpricing, $city, $zip);
		//print_r($tourpricing);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - Pricing By User</title>
        <style type="text/css" media="screen">@import "../repository_css/admin.css";</style>
    </head>

    <body>
    <?php
		echo '
		<table>
			<tr>
				<th colspan="5" >
					<form action="' .  basename($_SERVER['PHP_SELF']) . '" method="post">
						City: <input type="text" id="city" name="city" value="' . $city . '" /><br />
						Zip: <input type="text" id="zip" name="zip" value="' . $zip . '" /><br />
						<input type="hidden" id="userid" name="userid" value="' . $userid . '" />
						<input type="submit" id="submit" name="submit" value="submit" />
					</form>
				</th>
			</tr>
			<tr>
				<th colspan="5" >Results: ' . $name . ', ' . $city . ', ' . $zip . '</th>
			</tr>
        	<tr>
				<th>Item Type</th>
				<th>Item Name</th>
				<th>Price</th>
				<th>Disc. Price</th>
                <th>Broker Billable</th>
			</tr>
		';
	
		$highlight = true;
		foreach ($tourpricing as $item) {
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "nohighlight";
			}
			$highlight = !$highlight;
			
			$price = $item['ub_item'];
			$discount = false;
			
			// Only set if package pricing exist
			if(isset($item['ub_item_retail'])){
				$price = ($item['ub_item_retail']);
				$discount = true;
			}
			
			echo '
			<tr class="' . $class . '" >
				<td>' . $item['itemType'] . '</td>
				<td>' . $item['name'] . '</td>
				<td>$' . number_format($price, 2, '.', '') . '</td>
			';
			if($discount){
				echo'
					<td>$' . number_format($item['ub_item'], 2, '.', '') . '</td>
				';
			}else{
				echo'
					<td></td>
				';
			}
			
			if ($item['brokerbillable'] == 0 || $item['brokerbillable'] == "") {
				echo '
				<td>No</td>
				';
			} else {
				echo '
				<td>Yes</td>
				';
			}
			echo '
			</tr>
			';
		}
	
	?>
    	</table>
    </body>
</html>