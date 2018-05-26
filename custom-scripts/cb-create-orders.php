<?PHP
/**********************************************************************************************
Document: cb-create-orders.php
Creator: Jacob Edmond Kerr
Date: 05-09-14
Purpose: Goes through all the tours created by CB Blu and saves orders and order details in the database if the data is not present.
Notes: 
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

	// Global Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');

//=======================================================================
// Document
//=======================================================================

	// Create instance of the emailer class
	$orders = new orders();
	
//=======================================================================
// Document
//=======================================================================

	// Pull all CB Blue tours
	$cbBlueTours = $db->select('tours', "vendor_id>0 AND vendor='4'");
	foreach($cbBlueTours as $row => $column){
		$orderExist = array();
		$orderExist = $db->select('orders', "tourid='".$column['tourID']."'");
		if(count($orderExist)>0){
			$orderID = $orderExist[0]['orderID'];
			$orderExist = true;
		}else{
			$orderExist = false;
		}
		$orderDetailsSaved = false;
		if($orderExist){
			$orderDetailsSaved = array();
			$orderDetailsSaved = $db->select('orderdetails', "orderID='".$orderID."'");
			if(count($orderDetailsSaved)>0){
				$orderDetailsSaved = true;
			}else{
				$orderDetailsSaved = false;
			}
		}
		if(!$orderExist||!$orderDetailsSaved){
			require_once ('../transactional/transactional_pricing.php');
			$tourtypes = array(array('id'=>intval($column['tourTypeID']),'qty'=>1));
			$users = new users();
			$users->userID = $column['userID'];
			$brokerageID = $users->getBrokerID();
			$order = order($tourtypes, array(), $column['city'], $column['zipCode'], $brokerageID, $users->userID, "", 0);
			$orderInformation = array(
				'userID' => $users->userID,
				'tourid' => $column['tourID'],
				'subTotal' => $order['totals']['ub_sub'],
				'salesTax' => $order['totals']['ub_tax'],
				'total' => $order['totals']['ub_total'],
				'broker_total' => $order['totals']['bb_total'],
				'broker_paySold_total' => $order['totals']['bb_paySoldTotal'],
				'agent_paySold_total' => $order['totals']['ub_paySoldTotal'],
				'paid' => 0
			);
			if(!$orderExist){
				$orderID = $orders->saveOrder($orderInformation);
				echo 'Saving order: ';
				print_r($orderInformation);
			}
			if(!$orderDetailsSaved){
				$lineItems = array();
				foreach($order['lines'] as $lineIndex => $lineItem){
					$lineItems[$lineIndex]['type'] = $lineItem['itemType'];
					$lineItems[$lineIndex]['productID'] = $lineItem['itemID'];
					$lineItems[$lineIndex]['quantity' ] = $lineItem['qty'];
					$lineItems[$lineIndex]['unitPrice'] = $lineItem['ub_item'];
					$lineItems[$lineIndex]['broker_price'] = $lineItem['bb_item'];
				}
				$orders->saveOrderDetails($lineItems, $orderID);
				echo 'Saving order details: orderID: '.$orderID;
				print_r($order);
				print_r($lineItems);
			}
		}
	}
?>