<?php
/**********************************************************************************************
Document: checkout_merchant.php
Creator: Brandon Freeman
Date: 03-02-11
Purpose: Processes the transaction with the merchant account.
Notes: CC information is passed from the calling document.
**********************************************************************************************/

	// Include appplication's global configuration
	require_once($_SERVER["DOCUMENT_ROOT"].'/repository_inc/classes/inc.global.php');
	
	
	function SingleTransaction($transInfo) {
		$transactions = new transactions();
		$output = $transactions->runSingleCC($transInfo);
		$output['merchError'] = $output['ErrorMessage'];
		return $output;
	}
	
	function RecurringTransaction($transInfo) {
		$transactions = new transactions();
		$output = $transactions->runMonthlyTransaction($transInfo);
		$output['merchError'] = $output['ErrorMessage'];
		return $output;
	}

?>