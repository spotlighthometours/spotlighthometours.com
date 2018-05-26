<?php
/**********************************************************************************************
Document: checkout_merchant.php
Creator: Brandon Freeman
Date: 03-02-11
Purpose: Processes the transaction with the merchant account.
Notes: CC information is passed from the calling document.
**********************************************************************************************/
	
	function SingleTransaction($transInfo) {
		
		// Begin the conditioning of the transaction info.
		
		//userId
		if (!isset($transInfo['userId'])) {
			$transInfo['userId'] = '';
		}
		
		//nameOnCard
		if (!isset($transInfo['nameOnCard'])) {
			$transInfo['nameOnCard'] = '';
		}
		
		//cardNumber
		if (!isset($transInfo['cardNumber'])) {
			$transInfo['cardNumber'] = '';
		}
		
		//cardMonth
		if (!isset($transInfo['cardMonth'])) {
			$transInfo['cardMonth'] = '';
		}
		
		//cardYear
		if (!isset($transInfo['cardYear'])) {
			$transInfo['cardYear'] = '';
		}
		// Make sure the year is a two digit representation.
		if (strlen($transInfo['cardYear']) > 2) {
			$transInfo['cardYear'] = substr($transInfo['cardYear'], -2);
		}
		
		//cardAddress
		if (!isset($transInfo['cardAddress'])) {
			$transInfo['cardAddress'] = '';
		}
		
		//cardCity
		if (!isset($transInfo['cardCity'])) {
			$transInfo['cardCity'] = '';
		}
		
		//cardState
		if (!isset($transInfo['cardState'])) {
			$transInfo['cardState'] = '';
		}
		
		//cardZip
		if (!isset($transInfo['cardZip'])) {
			$transInfo['cardZip'] = '';
		}
		
		//orderTotal
		if (!isset($transInfo['orderTotal'])) {
			$transInfo['orderTotal'] = 0.01;
		}
		// Make sure the total is in the right decimal format.
		if ($transInfo['orderTotal'] > 0) {
			$transInfo['orderTotal'] = number_format($transInfo['orderTotal'], 2, '.', '');
		}
		
		// If we aren't connected to MySQL ...
		if(!isset($dbc)) {
			// Connect to MySQL
			require_once ('../repository_inc/connect.php');
		}
		
		// Set up the credentials that we need to create our SOAP request.
		$un_pw = "WS896176._.1:7QHdXrpZ";
		$ssl_pw = "ckp_1298998267";
		
		// This will switch the locations of the cert files, depending on if its on a dev server or prod server.
		if (file_exists("C:\certs\WS896176._.1.pem")) {
			$ssl_cert = "C:\certs\WS896176._.1.pem";
			$ssl_key = "C:\certs\WS896176._.1.key";
		} elseif (file_exists("D:\certs\WS896176._.1.pem")) {
			$ssl_cert = "D:\certs\WS896176._.1.pem";
			$ssl_key = "D:\certs\WS896176._.1.key";
		} elseif (file_exists("/Volumes/Macintosh HD/certs/WS896176._.1.pem")) {	
			$ssl_cert = "/Volumes/Macintosh HD/certs/WS896176._.1.pem";
			$ssl_key = "/Volumes/Macintosh HD/certs/WS896176._.1.key";
		}
		
		// Create the soap request.
		// There are PHP functions that can create this, but this works all the same IMO.
		$body = '
			<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
				<SOAP-ENV:Header />
				<SOAP-ENV:Body>
					<fdggwsapi:FDGGWSApiOrderRequest xmlns:v1="http://secure.linkpt.net/fdggwsapi/schemas_us/v1" xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi">
						<v1:Transaction>
							<v1:CreditCardTxType>
								<v1:Type>sale</v1:Type>
							</v1:CreditCardTxType>
							<v1:CreditCardData>
								<v1:CardNumber>' . $transInfo['cardNumber'] . '</v1:CardNumber>
								<v1:ExpMonth>' . $transInfo['cardMonth'] . '</v1:ExpMonth>
								<v1:ExpYear>' . $transInfo['cardYear'] . '</v1:ExpYear>
							</v1:CreditCardData>
							<v1:Payment>
								<v1:ChargeTotal>' . $transInfo['orderTotal'] . '</v1:ChargeTotal>
							</v1:Payment>
		';
		
		if(isset($transInfo['invoiceNum'])) {
			$body .= '
							<v1:TransactionDetails>
								<v1:UserID>' . $transInfo['invoiceNum'] . '</v1:UserID>
								<v1:InvoiceNumber>' . $transInfo['invoiceNum'] . '</v1:InvoiceNumber>
								<v1:OrderId>' . $transInfo['invoiceNum'] . '</v1:OrderId>
							</v1:TransactionDetails>
			';
		}
		
		$body .= '			
							<v1:Billing>
								<v1:CustomerID>' . $transInfo['userId'] . '</v1:CustomerID>
								<v1:Name>' . $transInfo['nameOnCard'] . '</v1:Name>
								<v1:Address1>' . $transInfo['cardAddress'] . '</v1:Address1>
								<v1:City>' . $transInfo['cardCity'] . '</v1:City>
								<v1:State>' . $transInfo['cardState'] . '</v1:State>
								<v1:Zip>' . $transInfo['cardZip'] . '</v1:Zip>
							</v1:Billing>
						</v1:Transaction>
					</fdggwsapi:FDGGWSApiOrderRequest>
				</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>
		';
		
		// The stuff for sending the transaction XML SOAP request.
		$ch = curl_init('https://ws.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $un_pw);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSLCERT, $ssl_cert);
		curl_setopt($ch, CURLOPT_SSLKEY, $ssl_key);
		curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $ssl_pw);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,2);
		
		// Debugging
		if ($_SESSION['debug']) {
			
			//success
			$response = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Header/><SOAP-ENV:Body><fdggwsapi:FDGGWSApiOrderResponse xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi"><fdggwsapi:CommercialServiceProvider>CSI</fdggwsapi:CommercialServiceProvider><fdggwsapi:TransactionTime>Wed Apr 06 14:59:24 2011</fdggwsapi:TransactionTime><fdggwsapi:TransactionID>820490438</fdggwsapi:TransactionID><fdggwsapi:ProcessorReferenceNumber>045914</fdggwsapi:ProcessorReferenceNumber><fdggwsapi:ProcessorResponseMessage>APPROVED</fdggwsapi:ProcessorResponseMessage><fdggwsapi:ErrorMessage/><fdggwsapi:OrderId>A-ca109717-bdcd-4e13-ac62-335764afefea</fdggwsapi:OrderId><fdggwsapi:ApprovalCode>0459140820490438:YYYX:</fdggwsapi:ApprovalCode><fdggwsapi:AVSResponse>YYYX</fdggwsapi:AVSResponse><fdggwsapi:TDate>1302116364</fdggwsapi:TDate><fdggwsapi:TransactionResult>APPROVED</fdggwsapi:TransactionResult><fdggwsapi:ProcessorResponseCode>A</fdggwsapi:ProcessorResponseCode><fdggwsapi:ProcessorApprovalCode/><fdggwsapi:CalculatedTax/><fdggwsapi:CalculatedShipping/><fdggwsapi:TransactionScore/><fdggwsapi:FraudAction/><fdggwsapi:AuthenticationResponseCode>XXX</fdggwsapi:AuthenticationResponseCode></fdggwsapi:FDGGWSApiOrderResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
		
			// Failure
			//$response = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Header/><SOAP-ENV:Body><fdggwsapi:FDGGWSApiOrderResponse xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi"><fdggwsapi:CommercialServiceProvider/><fdggwsapi:TransactionTime>Wed Apr 06 15:07:36 2011</fdggwsapi:TransactionTime><fdggwsapi:TransactionID/><fdggwsapi:ProcessorReferenceNumber/><fdggwsapi:ProcessorResponseMessage/><fdggwsapi:ErrorMessage>SGS-002300: Invalid credit card type.</fdggwsapi:ErrorMessage><fdggwsapi:OrderId>A-b6efab75-a944-4d2e-af07-d0229fb69d77</fdggwsapi:OrderId><fdggwsapi:ApprovalCode/><fdggwsapi:AVSResponse/><fdggwsapi:TDate/><fdggwsapi:TransactionResult>DECLINED</fdggwsapi:TransactionResult><fdggwsapi:ProcessorResponseCode/><fdggwsapi:ProcessorApprovalCode/><fdggwsapi:CalculatedTax/><fdggwsapi:CalculatedShipping/><fdggwsapi:TransactionScore/><fdggwsapi:FraudAction/><fdggwsapi:AuthenticationResponseCode/></fdggwsapi:FDGGWSApiOrderResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
		
		} else {
			$response = curl_exec($ch);
		}
		
		curl_close($ch);
		
		$output = array();
		
		if (empty($response)) {
			
			$output['merchError'] = 'FAILED: No Response. ' . Chr(10) . 'SENT: ' . $body;
			
		} else {
			$output['response'] = $response;
			
			// Colons and dashes aren't enjoyed by the SimpleXML reader.
			// BE RID OF THEM, I SAY!
			$response = str_replace(":", "", $response);
			$response = str_replace("-", "", $response);
		
			$xml = simplexml_load_string($response);
			$result = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiOrderResponse[0]->fdggwsapiTransactionResult[0];
			
			$transactionTime = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiOrderResponse[0]->fdggwsapiTransactionTime[0];
			$transactionId = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiOrderResponse[0]->fdggwsapiTransactionID[0];
			$orderId = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiOrderResponse[0]->fdggwsapiOrderId[0];
			$referenceNumber = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiOrderResponse[0]->fdggwsapiProcessorReferenceNumber[0];
			$approvalCode = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiOrderResponse[0]->fdggwsapiApprovalCode[0];
			$transactionDate = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiOrderResponse[0]->fdggwsapiTDate[0];
			$errorMessage = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiOrderResponse[0]->fdggwsapiErrorMessage[0];
			
			
			if(strlen($result) > 0) {
				$output['transResult'] = (string) $result;
			}
			
			// Record the transactionId, if there is one
			if(strlen($transactionId) > 0) {
				$output['transId'] = (string) $transactionId;
			}
			
			// Record the merchant error, if there is one.
			if(strlen($errorMessage) > 0) {
				$output['merchError'] = (string) $errorMessage;
			}
			
			$query =
			"INSERT INTO merchant_transactions 
			SET
			userID = '" . $transInfo['userId'] . "',
			transactionId = '" . $transactionId . "',
			transactionTime = '" . $transactionTime . "',
			orderId = '" . $orderId . "',
			referenceNumber = '" . $referenceNumber . "',
			approvalCode = '" . $approvalCode . "',
			transactionDate = '" . $transactionDate . "',
			transactionResult = '" . $result . "',
			cardLastFour = '" . substr($transInfo['cardNumber'], -4) . "',
			cardMonth = '" . $transInfo['cardMonth'] . "',
			cardYear = '" . $transInfo['cardYear'] . "',
			amount = '" . $transInfo['orderTotal'] . "',
			type = 'SINGLE',
			errorMessage = '" . $errorMessage . "'";
			mysql_query($query) or $output['sqlError'] = "Query failed with error: " . mysql_error() . Chr(10) . 'Query Run: ' . $query;
			
		}
		
		return $output;
	}
	
	function RecurringTransaction($transInfo) {
		
		// Begin the conditioning of the transaction info.
		
		//userId
		if (!isset($transInfo['userId'])) {
			$transInfo['userId'] = '';
		}
		
		//nameOnCard
		if (!isset($transInfo['nameOnCard'])) {
			$transInfo['nameOnCard'] = '';
		}
		
		//cardNumber
		if (!isset($transInfo['cardNumber'])) {
			$transInfo['cardNumber'] = '';
		}
		
		//cardMonth
		if (!isset($transInfo['cardMonth'])) {
			$transInfo['cardMonth'] = '';
		}
		
		//cardYear
		if (!isset($transInfo['cardYear'])) {
			$transInfo['cardYear'] = '';
		}
		// Make sure the year is a two digit representation.
		if (strlen($transInfo['cardYear']) > 2) {
			$transInfo['cardYear'] = substr($transInfo['cardYear'], -2);
		}
		
		//cardAddress
		if (!isset($transInfo['cardAddress'])) {
			$transInfo['cardAddress'] = '';
		}
		
		//cardCity
		if (!isset($transInfo['cardCity'])) {
			$transInfo['cardCity'] = '';
		}
		
		//cardState
		if (!isset($transInfo['cardState'])) {
			$transInfo['cardState'] = '';
		}
		
		//cardZip
		if (!isset($transInfo['cardZip'])) {
			$transInfo['cardZip'] = '';
		}
		
		//orderTotal
		if (!isset($transInfo['orderTotal'])) {
			$transInfo['orderTotal'] = 0.01;
		}
		// Make sure the total is in the right decimal format.
		if ($transInfo['orderTotal'] > 0) {
			$transInfo['orderTotal'] = number_format($transInfo['orderTotal'], 2, '.', '');
		}
		
		// If we aren't connected to MySQL ...
		if(!isset($dbc)) {
			// Connect to MySQL
			require_once ('../repository_inc/connect.php');
		}
		
		//startDate
		if (!isset($transInfo['startDate'])) {
			$transInfo['startDate'] = date('Ymd');
		}
		
		// Set up the credentials that we need to create our SOAP request.
		$un_pw = "WS896176._.1:7QHdXrpZ";
		$ssl_pw = "ckp_1298998267";
		
		// This will switch the locations of the cert files, depending on if its on a dev server or prod server.
		if (file_exists("C:\certs\WS896176._.1.pem")) {
			$ssl_cert = "C:\certs\WS896176._.1.pem";
			$ssl_key = "C:\certs\WS896176._.1.key";
		} elseif (file_exists("D:\certs\WS896176._.1.pem")) {
			$ssl_cert = "D:\certs\WS896176._.1.pem";
			$ssl_key = "D:\certs\WS896176._.1.key";
		} elseif (file_exists("/Volumes/Macintosh HD/certs/WS896176._.1.pem")) {	
			$ssl_cert = "/Volumes/Macintosh HD/certs/WS896176._.1.pem";
			$ssl_key = "/Volumes/Macintosh HD/certs/WS896176._.1.key";
		}
		
		// Create the soap request.
		// There are PHP functions that can create this, but this works all the same IMO.
		$body = '
			<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
				<SOAP-ENV:Header/>
				<SOAP-ENV:Body>
					<fdggwsapi:FDGGWSApiActionRequest xmlns:v1="http://secure.linkpt.net/fdggwsapi/schemas_us/v1" xmlns:a1="http://secure.linkpt.net/fdggwsapi/schemas_us/a1" xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi">
						<a1:Action>					
							<a1:RecurringPayment>
							
								<a1:RecurringPaymentInformation>
									<a1:RecurringStartDate>' . $transInfo['startDate'] . '</a1:RecurringStartDate>
									<a1:InstallmentFrequency>1</a1:InstallmentFrequency>
									<a1:InstallmentPeriod>month</a1:InstallmentPeriod>
									<a1:MaximumFailures>5</a1:MaximumFailures>
								</a1:RecurringPaymentInformation>
								
								<a1:TransactionDataType>
									<a1:CreditCardData>
										<v1:CardNumber>' . $transInfo['cardNumber'] . '</v1:CardNumber>
										<v1:ExpMonth>' . $transInfo['cardMonth'] . '</v1:ExpMonth>
										<v1:ExpYear>' . $transInfo['cardYear'] . '</v1:ExpYear>
									</a1:CreditCardData>
								</a1:TransactionDataType>
								
								<v1:Payment>
									<v1:ChargeTotal>' . $transInfo['orderTotal'] . '</v1:ChargeTotal>
								</v1:Payment>
								
								<v1:Billing>
									<v1:CustomerID>' . $transInfo['userId'] . '</v1:CustomerID>
									<v1:Name>' . $transInfo['nameOnCard'] . '</v1:Name>
									<v1:Address1>' . $transInfo['cardAddress'] . '</v1:Address1>
									<v1:City>' . $transInfo['cardCity'] . '</v1:City>
									<v1:State>' . $transInfo['cardState'] . '</v1:State>
									<v1:Zip>' . $transInfo['cardZip'] . '</v1:Zip>
								</v1:Billing>
								
								<a1:Function>install</a1:Function>
							
							</a1:RecurringPayment>
						</a1:Action>
					</fdggwsapi:FDGGWSApiActionRequest>
				</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>
		';
		
		// The stuff for sending the transaction XML SOAP request.
		$ch = curl_init('https://ws.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $un_pw);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSLCERT, $ssl_cert);
		curl_setopt($ch, CURLOPT_SSLKEY, $ssl_key);
		curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $ssl_pw);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,2);
		
		// Debugging
		if ($_SESSION['debug']) {
			
			// Success
			$response = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Header/><SOAP-ENV:Body><fdggwsapi:FDGGWSApiActionResponse xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi"><fdggwsapi:Success>true</fdggwsapi:Success><fdggwsapi:CommercialServiceProvider/><fdggwsapi:TransactionTime>Wed Apr 06 14:59:25 2011</fdggwsapi:TransactionTime><fdggwsapi:TransactionID/><fdggwsapi:ProcessorReferenceNumber/><fdggwsapi:ProcessorResponseMessage/><fdggwsapi:ErrorMessage/><fdggwsapi:OrderId>A-98c30095-7296-49ad-a215-af11429c2e6c</fdggwsapi:OrderId><fdggwsapi:TransactionResult>APPROVED</fdggwsapi:TransactionResult><fdggwsapi:ApprovalCode/><fdggwsapi:AVSResponse/><fdggwsapi:TDate/><fdggwsapi:ProcessorResponseCode/><fdggwsapi:ProcessorApprovalCode/><fdggwsapi:TransactionScore/><fdggwsapi:FraudAction/></fdggwsapi:FDGGWSApiActionResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
			
			// Failure
			//$response = '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Header/><SOAP-ENV:Body><fdggwsapi:FDGGWSApiActionResponse xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi"><fdggwsapi:Success>false</fdggwsapi:Success><fdggwsapi:CommercialServiceProvider/><fdggwsapi:TransactionTime>Wed Apr 06 14:44:28 2011</fdggwsapi:TransactionTime><fdggwsapi:TransactionID/><fdggwsapi:ProcessorReferenceNumber/><fdggwsapi:ProcessorResponseMessage/><fdggwsapi:ErrorMessage>SGS-001003: Invalid credit card number.</fdggwsapi:ErrorMessage><fdggwsapi:OrderId/><fdggwsapi:TransactionResult>FAILED</fdggwsapi:TransactionResult><fdggwsapi:ApprovalCode/><fdggwsapi:AVSResponse/><fdggwsapi:TDate/><fdggwsapi:ProcessorResponseCode/><fdggwsapi:ProcessorApprovalCode/><fdggwsapi:TransactionScore/><fdggwsapi:FraudAction/></fdggwsapi:FDGGWSApiActionResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>';
		
		} else {
			$response = curl_exec($ch);
		}
		
		curl_close($ch);
		
		$output = array();
		
		if (empty($response)) {
			
			$output['merchError'] = 'FAILED: No Response. ' . Chr(10) . 'SENT: ' . $body;
			
		} else {
			$output['response'] = $response;
			
			// Colons and dashes aren't enjoyed by the SimpleXML reader.
			// BE RID OF THEM, I SAY!
			$response = str_replace(":", "", $response);
			$response = str_replace("-", "", $response);
		
			$xml = simplexml_load_string($response);
			$result = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiTransactionResult[0];
			
			
			$transactionTime = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiTransactionTime[0];
			$transactionId = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiTransactionID[0];
			$orderId = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiOrderId[0];
			$referenceNumber = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiProcessorReferenceNumber[0];
			$approvalCode = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiApprovalCode[0];
			$transactionDate = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiTDate[0];
			$errorMessage = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiErrorMessage[0];
			
			
			if(strlen($result) > 0) {
				$output['transResult'] = (string) $result;
			}
			
			// Record the transactionId, if there is one
			if(strlen($transactionId) > 0) {
				$output['transId'] = (string) $transactionId;
			}
			
			// Record the merchant error, if there is one.
			if(strlen($errorMessage) > 0) {
				$output['merchError'] = (string) $errorMessage;
			}
			
			$query =
			"INSERT INTO merchant_transactions 
			SET
			userID = '" . $transInfo['userId'] . "',
			transactionId = '" . $transactionId . "',
			transactionTime = '" . $transactionTime . "',
			orderId = '" . $orderId . "',
			referenceNumber = '" . $referenceNumber . "',
			approvalCode = '" . $approvalCode . "',
			transactionDate = '" . $transactionDate . "',
			transactionResult = '" . $result . "',
			cardLastFour = '" . substr($transInfo['cardNumber'], -4) . "',
			cardMonth = '" . $transInfo['cardMonth'] . "',
			cardYear = '" . $transInfo['cardYear'] . "',
			amount = '" . $transInfo['orderTotal'] . "',
			type = 'RECURRING',
			active = '1',
			errorMessage = '" . $errorMessage . "'";
			mysql_query($query) or $output['sqlError'] = "Query failed with error: " . mysql_error() . Chr(10) . 'Query Run: ' . $query;
			
		}
		
		return $output;
	}
	
	function CancelRecurring($orderId) {
		
		// If we aren't connected to MySQL ...
		if(!isset($dbc)) {
			// Connect to MySQL
			require_once ('../repository_inc/connect.php');
		}
		
		// Set up the credentials that we need to create our SOAP request.
		$un_pw = "WS896176._.1:7QHdXrpZ";
		$ssl_pw = "ckp_1298998267";
		
		// This will switch the locations of the cert files, depending on if its on a dev server or prod server.
		if (file_exists("C:\certs\WS896176._.1.pem")) {
			$ssl_cert = "C:\certs\WS896176._.1.pem";
			$ssl_key = "C:\certs\WS896176._.1.key";
		} elseif (file_exists("D:\certs\WS896176._.1.pem")) {
			$ssl_cert = "D:\certs\WS896176._.1.pem";
			$ssl_key = "D:\certs\WS896176._.1.key";
		} elseif (file_exists("/Volumes/Macintosh HD/certs/WS896176._.1.pem")) {	
			$ssl_cert = "/Volumes/Macintosh HD/certs/WS896176._.1.pem";
			$ssl_key = "/Volumes/Macintosh HD/certs/WS896176._.1.key";
		}
		
		// Create the soap request.
		// There are PHP functions that can create this, but this works all the same IMO.
		$body = '
			<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
				<SOAP-ENV:Header/>
				<SOAP-ENV:Body>
					<fdggwsapi:FDGGWSApiActionRequest xmlns:v1="http://secure.linkpt.net/fdggwsapi/schemas_us/v1" xmlns:a1="http://secure.linkpt.net/fdggwsapi/schemas_us/a1" xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi">
						<a1:Action>					
							<a1:RecurringPayment>
								
								<a1:OrderId>' . preg_replace("/^(.{1})(.{8})(.{4})(.{4})(.{4})/", "$1-$2-$3-$4-$5-", $orderId) . '</a1:OrderId>
								
								<a1:Function>cancel</a1:Function>
							
							</a1:RecurringPayment>
						</a1:Action>
					</fdggwsapi:FDGGWSApiActionRequest>
				</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>
		';
		
		// The stuff for sending the transaction XML SOAP request.
		$ch = curl_init('https://ws.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $un_pw);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSLCERT, $ssl_cert);
		curl_setopt($ch, CURLOPT_SSLKEY, $ssl_key);
		curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $ssl_pw);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,2);
		
		// Debugging
		if ($_SESSION['debug']) {
			$response = '
			
			';
		} else {
			$response = curl_exec($ch);
		}
		
		curl_close($ch);
		
		$output = array();
		
		$output['orderId'] = $orderId;
		
		if (empty($response)) {
			
			$output['merchError'] = 'FAILED: No Response. ' . Chr(10) . 'SENT: ' . $body;
			
		} else {
			$output['response'] = $response;
			
			// Colons and dashes aren't enjoyed by the SimpleXML reader.
			// BE RID OF THEM, I SAY!
			$response = str_replace(":", "", $response);
			$response = str_replace("-", "", $response);
		
			$xml = simplexml_load_string($response);
			$result = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiTransactionResult[0];
			$errorMessage = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiErrorMessage[0];
			
			
			if(strlen($result) > 0) {
				$output['transResult'] = (string) $result;
			}
			
			// Record the merchant error, if there is one.
			if(strlen($errorMessage) > 0) {
				$output['merchError'] = (string) $errorMessage;
			}
			
			if ($output['transResult'] == "APPROVED") {
				$query =
				"UPDATE merchant_transactions 
				SET
				active = 0
				WHERE orderId = '" . $orderId . "'";
				
				mysql_query($query) or $output['sqlError'] = "Query failed with error: " . mysql_error();
			}
		}
		
		return $output;
	}
	
	function UpdateRecurring($orderId, $transInfo) {
		
		// Begin the conditioning of the transaction info.
		
		//userId
		if (!isset($transInfo['userId'])) {
			$transInfo['userId'] = '';
		}
		
		//nameOnCard
		if (!isset($transInfo['nameOnCard'])) {
			$transInfo['nameOnCard'] = '';
		}
		
		//cardNumber
		if (!isset($transInfo['cardNumber'])) {
			$transInfo['cardNumber'] = '';
		}
		
		//cardMonth
		if (!isset($transInfo['cardMonth'])) {
			$transInfo['cardMonth'] = '';
		}
		
		//cardYear
		if (!isset($transInfo['cardYear'])) {
			$transInfo['cardYear'] = '';
		}
		// Make sure the year is a two digit representation.
		if (strlen($transInfo['cardYear']) > 2) {
			$transInfo['cardYear'] = substr($transInfo['cardYear'], -2);
		}
		
		//cardAddress
		if (!isset($transInfo['cardAddress'])) {
			$transInfo['cardAddress'] = '';
		}
		
		//cardCity
		if (!isset($transInfo['cardCity'])) {
			$transInfo['cardCity'] = '';
		}
		
		//cardState
		if (!isset($transInfo['cardState'])) {
			$transInfo['cardState'] = '';
		}
		
		//cardZip
		if (!isset($transInfo['cardZip'])) {
			$transInfo['cardZip'] = '';
		}
		
		//orderTotal
		if (!isset($transInfo['orderTotal'])) {
			$transInfo['orderTotal'] = 0.01;
		}
		// Make sure the total is in the right decimal format.
		if ($transInfo['orderTotal'] > 0) {
			$transInfo['orderTotal'] = number_format($transInfo['orderTotal'], 2, '.', '');
		}
		
		// If we aren't connected to MySQL ...
		if(!isset($dbc)) {
			// Connect to MySQL
			require_once ('../repository_inc/connect.php');
		}
		
		//startDate
		if (!isset($transInfo['startDate'])) {
			$transInfo['startDate'] = date('Ymd');
		}
		
		// Set up the credentials that we need to create our SOAP request.
		$un_pw = "WS896176._.1:7QHdXrpZ";
		$ssl_pw = "ckp_1298998267";
		
		// This will switch the locations of the cert files, depending on if its on a dev server or prod server.
		if (file_exists("C:\certs\WS896176._.1.pem")) {
			$ssl_cert = "C:\certs\WS896176._.1.pem";
			$ssl_key = "C:\certs\WS896176._.1.key";
		} elseif (file_exists("D:\certs\WS896176._.1.pem")) {
			$ssl_cert = "D:\certs\WS896176._.1.pem";
			$ssl_key = "D:\certs\WS896176._.1.key";
		} elseif (file_exists("/Volumes/Macintosh HD/certs/WS896176._.1.pem")) {	
			$ssl_cert = "/Volumes/Macintosh HD/certs/WS896176._.1.pem";
			$ssl_key = "/Volumes/Macintosh HD/certs/WS896176._.1.key";
		}
		
		// Create the soap request.
		// There are PHP functions that can create this, but this works all the same IMO.
		$body = '
			<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
				<SOAP-ENV:Header/>
				<SOAP-ENV:Body>
					<fdggwsapi:FDGGWSApiActionRequest xmlns:v1="http://secure.linkpt.net/fdggwsapi/schemas_us/v1" xmlns:a1="http://secure.linkpt.net/fdggwsapi/schemas_us/a1" xmlns:fdggwsapi="http://secure.linkpt.net/fdggwsapi/schemas_us/fdggwsapi">
						<a1:Action>					
							<a1:RecurringPayment>
							
								<a1:RecurringPaymentInformation>
									<a1:RecurringStartDate>' . $transInfo['startDate'] . '</a1:RecurringStartDate>
									<a1:InstallmentFrequency>1</a1:InstallmentFrequency>
									<a1:InstallmentPeriod>month</a1:InstallmentPeriod>
									<a1:MaximumFailures>5</a1:MaximumFailures>
								</a1:RecurringPaymentInformation>
								
								<a1:TransactionDataType>
									<a1:CreditCardData>
										<v1:CardNumber>' . $transInfo['cardNumber'] . '</v1:CardNumber>
										<v1:ExpMonth>' . $transInfo['cardMonth'] . '</v1:ExpMonth>
										<v1:ExpYear>' . $transInfo['cardYear'] . '</v1:ExpYear>
									</a1:CreditCardData>
								</a1:TransactionDataType>
								
								<v1:Payment>
									<v1:ChargeTotal>' . $transInfo['orderTotal'] . '</v1:ChargeTotal>
								</v1:Payment>
								
								<v1:Billing>
									<v1:CustomerID>' . $transInfo['userId'] . '</v1:CustomerID>
									<v1:Name>' . $transInfo['nameOnCard'] . '</v1:Name>
									<v1:Address1>' . $transInfo['cardAddress'] . '</v1:Address1>
									<v1:City>' . $transInfo['cardCity'] . '</v1:City>
									<v1:State>' . $transInfo['cardState'] . '</v1:State>
									<v1:Zip>' . $transInfo['cardZip'] . '</v1:Zip>
								</v1:Billing>
								
								<a1:OrderId>' . preg_replace("/^(.{1})(.{8})(.{4})(.{4})(.{4})/", "$1-$2-$3-$4-$5-", $orderId) . '</a1:OrderId>
								
								<a1:Function>update</a1:Function>
							
							</a1:RecurringPayment>
						</a1:Action>
					</fdggwsapi:FDGGWSApiActionRequest>
				</SOAP-ENV:Body>
			</SOAP-ENV:Envelope>
		';
		
		// The stuff for sending the transaction XML SOAP request.
		$ch = curl_init('https://ws.firstdataglobalgateway.com/fdggwsapi/services/order.wsdl');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $un_pw);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSLCERT, $ssl_cert);
		curl_setopt($ch, CURLOPT_SSLKEY, $ssl_key);
		curl_setopt($ch, CURLOPT_SSLKEYPASSWD, $ssl_pw);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,2);
		
		// Debugging
		if ($_SESSION['debug']) {
			$response = '
			
			';
		} else {
			$response = curl_exec($ch);
		}
		
		curl_close($ch);
		
		$output = array();
		
		if (empty($response)) {
			
			$output['merchError'] = 'FAILED: No Response. ' . Chr(10) . 'SENT: ' . $body;
			
		} else {
			$output['response'] = $response;
			
			// Colons and dashes aren't enjoyed by the SimpleXML reader.
			// BE RID OF THEM, I SAY!
			$response = str_replace(":", "", $response);
			$response = str_replace("-", "", $response);
		
			$xml = simplexml_load_string($response);
			$result = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiTransactionResult[0];
			
			
			$transactionTime = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiTransactionTime[0];
			$transactionId = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiTransactionID[0];
			$neworderId = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiOrderId[0];
			$referenceNumber = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiProcessorReferenceNumber[0];
			$approvalCode = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiApprovalCode[0];
			$transactionDate = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiTDate[0];
			$errorMessage = $xml->SOAPENVBody[0]->fdggwsapiFDGGWSApiActionResponse[0]->fdggwsapiErrorMessage[0];
			
			
			if(strlen($result) > 0) {
				$output['transResult'] = (string) $result;
			}
			
			// Record the transactionId, if there is one
			if(strlen($transactionId) > 0) {
				$output['transId'] = (string) $transactionId;
			}
			
			// Record the merchant error, if there is one.
			if(strlen($errorMessage) > 0) {
				$output['merchError'] = (string) $errorMessage;
			}
			
			if ($output['transResult'] == "APPROVED") {
				$query =
				"UPDATE merchant_transactions 
				SET
				active = 0
				WHERE orderId = '" . $orderId . "'";
				
				mysql_query($query) or $output['sqlError'] = "Query failed with error: " . mysql_error();
				
				$query =
				"INSERT INTO merchant_transactions 
				SET
				userID = '" . $transInfo['userId'] . "',
				transactionId = '" . $transactionId . "',
				transactionTime = '" . $transactionTime . "',
				orderId = '" . $neworderId . "',
				referenceNumber = '" . $referenceNumber . "',
				approvalCode = '" . $approvalCode . "',
				transactionDate = '" . $transactionDate . "',
				transactionResult = '" . $result . "',
				cardLastFour = '" . substr($transInfo['cardNumber'], -4) . "',
				cardMonth = '" . $transInfo['cardMonth'] . "',
				cardYear = '" . $transInfo['cardYear'] . "',
				amount = '" . $transInfo['orderTotal'] . "',
				type = 'RECURRING',
				active = '1',
				errorMessage = '" . $errorMessage . "'";
				mysql_query($query) or $output['sqlError'] = "Query failed with error: " . mysql_error() . Chr(10) . 'Query Run: ' . $query;
			}
			
		}
		
		return $output;
	}

?>