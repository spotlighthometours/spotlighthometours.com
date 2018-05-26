<?php
/**********************************************************************************************
Document: /api/tours/
Creator: Jacob Edmond Kerr
Date: 01-25-12
Purpose: Handles all API request for tours of any kind. Output XML / JSON 
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Include appplication's global configuration
	require_once('../../repository_inc/classes/inc.global.php');
	//showErrors();
	
//=======================================================================
// Objects
//=======================================================================

	// Create Needed Objects
	(isset($_REQUEST['format'])&&!empty($_REQUEST['format']))?$api = new api($_REQUEST['format']):$api = new api();
	$tours = new tours($db);
	$errors = new errors();
	neverDie();
	
//=======================================================================
// Document
//=======================================================================

// Load API user by key
if($api->loadUserByKey($_REQUEST['key'])){
	switch(strtolower($_REQUEST['method'])){
		
		// GET TOUR PHOTOS
		case'getphotos':
			if(isset($_REQUEST['vid'])){
				$_REQUEST['id'] = $tours->getTourIDByVendorID($_REQUEST['vid'], $api->id);
				//echo $_REQUEST['id'];
			}
			$photos = $tours->getPhotos(intval($_REQUEST['id']));
			$photoSizeAbrev = array(
				'th',
				'sm',
				'high',
				'400',
				'600',
				'640',
				'800',
				'960'
			);
			$safeArray['status'] = 1;
			foreach($photos as $row => $columns){
				$photoURLs = array();
				foreach($photoSizeAbrev as $index => $value){
					$photoURLs['size_'.$value] = TOUR_IMAGE_DIR_URL_S3.intval($_REQUEST['id']).'/photo_'.$value.'_'.$columns['mediaID'].'.jpg';
				}
				$photos[$row]['urls'] = $photoURLs;
			}
			$safeArray['photo'] = $photos;
			$api->output($safeArray);
		break;
		
		// GET TOUR ID BY MLS ID
		case'gettouridbymlsid':
			if(isset($_REQUEST['id'])){
				if(isset($_REQUEST['state'])){
					$tourID = $tours->getIDByMLS($_REQUEST['id'], $_REQUEST['state']);
				}else{
					$tourID = $tours->getIDByMLS($_REQUEST['id']);
				}
				if($tourID>0){
					$response = array(
						"tour"=>array(
							"status"=>1,
							"tourid"=>$tourID
						)
					);
					$api->output($response);
				}else{
					$errors->set('Tour not found! Method: '.$_REQUEST['method'].', API Key: '.$_REQUEST['key'].'. ID: '.$_REQUEST['id'].'. State: '.$_REQUEST['state']);
					$errors->log();
					$output = array(
						"status"=>0,
						'error'=>$errors->get()
					);
					$api->output($output);
				}
			}else{
				$errors->set('ID is required. Example: key=your_api_key&method=getTourIDByMLSID&id=684195&state=ut&format=xml');
			}
		break;
		
		// CREATE TOUR
		case'create':
			if(isset($_REQUEST['desired_date'])||isset($_REQUEST['desired_time'])){
				if(!isset($_REQUEST['additionalInstructions'])){
					$_REQUEST['additionalInstructions'] = '';
				}
				if(isset($_REQUEST['desired_date'])){
					$_REQUEST['additionalInstructions'] .= ' Desired shoot date: '.$_REQUEST['desired_date'];
				}
				if(isset($_REQUEST['desired_time'])){
					$_REQUEST['additionalInstructions'] .= ' Desired shoot time: '.$_REQUEST['desired_time'];
				}
			}
			$_REQUEST['vendor'] = $api->id;
			$tourID = $tours->createTour($_REQUEST);
			if($tourID){
				// if createOrder then create a blank order with 0.00 pricing. Can use the tourid to find and update/add onto the order later if needed
				if(isset($_REQUEST['createOrder'])&&$_REQUEST['createOrder']=1){
					$orders = new orders();
					$orderInfo = array(
						'userID' =>$tours->validated_data['userID'],
						'tourid' => $tourID,
						'subTotal' => 0.00,
						'salesTax' => 0.00,
						'total' => 0.00,
						'createdOn' => $db->now()
					);
					$orders->saveOrder($orderInfo);
				}
				// Output the tourID
				$output = array(
					'status'=>1,
					'id'=>$tourID
				);
				$api->output($output);
			}else{
				$output = array(
					'status'=>0,
					'error'=>$tours->errors->get()
				);
				$api->output($output);
			}
		break;
		
		// GET TOUR TYPES
		case'gettypes':
			// Pull the userID via userEmail if set and user exist
			if(isset($_REQUEST['userEmail'])&&(!isset($_REQUEST['userID'])||empty($_REQUEST['userID']))){
				$users = new users();
				if($users->userExist($_REQUEST['userEmail'])){
					$_REQUEST['userID'] = $users->userID;
				}else{
					$output = array(
						'status'=>0,
						'error'=>"User with the username email: ".$_REQUEST['userEmail']." does not exist!"
					);
					$api->output($output);
					exit();
				}
			}
			// Loop through convertTypeIDs if exists and create an array for the tourTypeIDs and an array for the IDs to convert to (outputs as id)
			if(isset($_REQUEST['convertTypeIDs'])&&!empty($_REQUEST['convertTypeIDs'])){
				$typeIDsToConvert = array();
				$covertedTypeIDs = array();
				if(is_array($_REQUEST['convertTypeIDs'])){
					foreach($_REQUEST['convertTypeIDs'] as $index => $convertTypeIDs){
						$typeIDConvertedID = explode(":",$convertTypeIDs);
						$typeIDsToConvert[] = $typeIDConvertedID[0];
						$covertedTypeIDs[] = $typeIDConvertedID[1];
					}
				}else{
					$typeIDConvertedID = explode(":",$_REQUEST['convertTypeIDs']);
					$typeIDsToConvert[] = $typeIDConvertedID[0];
					$covertedTypeIDs[] = $typeIDConvertedID[1];
				}
			}
			// Create instance of validation Object
			$validation = new validation();
			// Sanitize tour type data
			$validation->sanitize($_REQUEST);
			// Create validation rules for tour type data
			$dataValidation = array(
				'userEmail' => 'valid_email|max_len,60', //varchar(60)
				'userID' => 'required|integer|max_len,11', //int(11)
				'city' => 'required|max_len,50', //varchar(50)
				'zip' => 'required|max_len,10', //varchar(10)
			);
			// Create filter rules for tour type data
			$dataFilers = array(
				'userEmail' => 'trim|sanitize_email', //int(11)
				'userID' => 'trim|sanitize_numbers', //int(11)
				'city' => 'trim|sanitize_string', //varchar(50)
				'zip' => 'trim|sanitize_string', //varchar(10)
			);
			// Set tour type data validation rules
			$validation->validation_rules($dataValidation);
			// Set tour type data filter rules
			$validation->filter_rules($dataFilers);
			// Filter and validate tour type data
			$validated_data = $validation->run($_REQUEST);
			if($validated_data === false){
				// Validation failed! Letz process those errors.
				$validation->get_readable_errors(true);
				$output = array(
					'status'=>0,
					'error'=>$validation->errors->get()
				);
				// Output errors
				$api->output($output);
			}else{
				// Tour type data valid! Lets pull the user information needed as well as all the tour types
				require_once ('../../transactional/transactional_pricing.php');
				$users = new users();
				$users->userID = $_REQUEST['userID'];
				$brokerageID = $users->getBrokerID();
				$tourtypes = $db->run('SELECT tt.tourTypeID AS id, 1 as qty, tt.tourCategory AS category, tt.tourTypeName AS title, tt.tagline, tt.description, tt.upgradeId, tt.upgradeDoc, tt.iconImage AS icon, tt.DemoLinkLink AS demo
									   FROM tourtypes tt
									   LEFT JOIN tour_category tc ON tt.tourCategory = tc.category_name WHERE hidden = 0 AND tt.expressOnly != 1 ORDER BY tc.category_order, tt.tour_order');
				// Some brokerages required that we pull tour types from multiple brokerages...
				$brkIDs['coldwell'][] = array(
					406,
					407,
					408,
					409,
					410,
					411,
					412,
					413,
					414,
					415,
					416,
					417,
					418,
					419,
					420,
					421,
					445
				);
				$brkIDs['fuller'][] = array(
					27,
					114,
					163,
					731
				);
				// Additional brokerages
				$addBrkIDs['coldwell'][] = 441;
				// Check if the user's brokerage ID is in the list of brokerage IDs that need additional brokerages applied
				foreach($brkIDs as $brokerage => $brokerageIDs){
					if(in_array($brokerageID,$brokerageIDs[0])){
						$addBrokerageName = $brokerage;
					}
				}
				$brokerageList = array($brokerageID);
				if(isset($addBrokerageName)&&!empty($addBrokerageName)){
					foreach($addBrkIDs[$addBrokerageName] as $index => $brokerageID){
						$brokerageList[] = $brokerageID;
					}
				}
				$new_list = array();
				foreach($brokerageList as $index => $brokerageID){
					// Process tour types according to user, area and brokerage
					$return = order($tourtypes, $items, $validated_data['city'], $validated_data['zip'], $brokerageID, $users->userID, "", 0);
					for ($i = 0; $i < sizeof($tourtypes); $i++) {
						for ($j = 0; $j < sizeof($return['lines']); $j++) {
							if(intval($tourtypes[$i]['id']) == intval($return['lines'][$j]['itemID'])) {
								$tourtypes[$i]['price'] = ($return['lines'][$j]['ub_item'] + $return['lines'][$j]['mb_item']);
								// Only set if package pricing exist
								if(isset($return['lines'][$j]['ub_item_retail'])&&isset($return['lines'][$j]['mb_item_retail'])){
									$tourtypes[$i]['retail_price'] = ($return['lines'][$j]['ub_item_retail'] + $return['lines'][$j]['mb_item_retail']);
								}elseif(isset($return['lines'][$j]['ub_item_retail'])){
									$tourtypes[$i]['retail_price'] = ($return['lines'][$j]['ub_item_retail'] + $return['lines'][$j]['mb_item']);
								}elseif(isset($return['lines'][$j]['mb_item_retail'])){
									$tourtypes[$i]['retail_price'] = ($return['lines'][$j]['ub_item'] + $return['lines'][$j]['mb_item_retail']);
								}
								// Overwrite package pricing for the API (we do not want package pricing on the API)
								if(isset($tourtypes[$i]['retail_price'])&&!empty($tourtypes[$i]['retail_price'])){
									$tourtypes[$i]['price'] = $tourtypes[$i]['retail_price'];
									unset($tourtypes[$i]['retail_price']);
								}
								$tourtypes[$i]['icon'] = str_replace("../repository_thumbs/", "www.spotlighthometours.com/repository_thumbs/", $tourtypes[$i]['icon']);
								if(isset($typeIDsToConvert)){
									if(in_array($tourtypes[$i]['id'],$typeIDsToConvert)){
										$tourtypes[$i]['tourTypeID'] = $tourtypes[$i]['id'];
										if(isset($covertedTypeIDs[array_search($tourtypes[$i]['id'],$typeIDsToConvert)])){
											$tourtypes[$i]['id'] = $covertedTypeIDs[array_search($tourtypes[$i]['id'],$typeIDsToConvert)];
										}
									}
								}
								$new_list[] = $tourtypes[$i];
							}
						}
					}
				}
				$tourtypes = $new_list;
				$output = array(
					'status'=>1,
					'tourtypes'=>$tourtypes
				);
				$api->output($output);
			}
		break;
		
		// GET TOUR PRODUCTS
		case'getproducts':
			// Pull the userID via userEmail if set and user exist
			if(isset($_REQUEST['userEmail'])&&(!isset($_REQUEST['userID'])||empty($_REQUEST['userID']))){
				$users = new users();
				if($users->userExist($_REQUEST['userEmail'])){
					$_REQUEST['userID'] = $users->userID;
				}else{
					$output = array(
						'status'=>0,
						'error'=>"User with the username email: ".$_REQUEST['userEmail']." does not exist!"
					);
					$api->output($output);
				}
			}
			// Create instance of validation Object
			$validation = new validation();
			// Sanitize tour product data
			$validation->sanitize($_REQUEST);
			// Create validation rules for tour product data
			$dataValidation = array(
				'userEmail' => 'valid_email|max_len,60', //varchar(60)
				'userID' => 'required|integer|max_len,11', //int(11)
				'city' => 'required|max_len,50', //varchar(50)
				'zip' => 'required|max_len,10', //varchar(10)
				'tourTypeID' => 'required|integer|max_len,11' //int(11)
			);
			// Create filter rules for tour product data
			$dataFilers = array(
				'userEmail' => 'trim|sanitize_email', //int(11)
				'userID' => 'trim|sanitize_numbers', //int(11)
				'city' => 'trim|sanitize_string', //varchar(50)
				'zip' => 'trim|sanitize_string', //varchar(10)
				'tourTypeID' => 'trim|sanitize_numbers' //int(11)
			);
			// Set tour product data validation rules
			$validation->validation_rules($dataValidation);
			// Set tour product data filter rules
			$validation->filter_rules($dataFilers);
			// Filter and validate tour product data
			$validated_data = $validation->run($_REQUEST);
			if($validated_data === false){
				// Validation failed! Letz process those errors.
				$validation->get_readable_errors(true);
				$output = array(
					'status'=>0,
					'error'=>$validation->errors->get()
				);
				// Output errors
				$api->output($output);
			}else{
				// Tour type data valid! Lets pull the user information needed as well as all the tour types
				require_once ('../../transactional/transactional_pricing.php');
				$users = new users();
				$users->userID = $_REQUEST['userID'];
				$brokerageID = $users->getBrokerID();
				$additional_products = $db->run('SELECT p.productId AS id, 1 AS qty, p.productName AS title, p.tagline, p.onePerOrder, productIcon AS icon 
									   FROM tour_products tp
									   LEFT JOIN products p ON  tp.productID = p.productID
									   WHERE p.productName IS NOT NULL
									   AND p.visible = 1 
								       AND p.parentProduct IS NULL
									   AND tp.tourTypeID = '.$validated_data['tourTypeID'].'
									   ORDER BY p.sort ASC');
				$return = order(null, $additional_products, $validated_data['city'], $validated_data['zip'], $brokerageID, $users->userID, "", 0 );
				$new_list = array();
				for ($i = 0; $i < sizeof($additional_products); $i++) {
					for ($j = 0; $j < sizeof($return['lines']); $j++) {
						if(intval($additional_products[$i]['id']) == intval($return['lines'][$j]['itemID'])) {
							$additional_products[$i]['price'] = $return['lines'][$j]['ub_item'];
							// Only set if package pricing exist
							if(isset($return['lines'][$j]['ub_item_retail'])){
								$additional_products[$i]['retail_price'] = $return['lines'][$j]['ub_item_retail'];
							}
							// Overwrite package pricing for the API (we do not want package pricing on the API)
							if(isset($additional_products[$i]['retail_price'])&&!empty($additional_products[$i]['retail_price'])){
								$additional_products[$i]['price'] = $additional_products[$i]['retail_price'];
								unset($additional_products[$i]['retail_price']);
							}
							$additional_products[$i]['icon'] = str_replace("../repository_thumbs/", "www.spotlighthometours.com/repository_thumbs/", $additional_products[$i]['icon']);
							$new_list[] = $additional_products[$i];
						}
					}
				}
				$additional_products = $new_list;
				$output = array(
					'status'=>1,
					'products'=>$additional_products
				);
				$api->output($output);
			}
		break;
		
		// CREATE TOUR ORDER WHICH MAY OR MAY NOT INCLUDE ADDITIONAL PRODUCTS (THIS WILL CREATE THE TOUR, IF tourID !isset AND SAVE THE ORDER)
		case'createorder':
			if(isset($_REQUEST['zip'])&&!empty($_REQUEST['zip'])){
				$_REQUEST['zipCode'] = $_REQUEST['zip'];
			}
			// If the tourID is set the userID and tourtypeID can be pulled from the tour
			$createTour = true;
			if(isset($_REQUEST['tourID'])&&!empty($_REQUEST['tourID'])){
				$tours = new tours();
				$tours->tourID = intval($_REQUEST['tourID']);
				$_REQUEST['tourTypeID'] = $tours->get('tourTypeID');
				$_REQUEST['userID'] = $tours->get('userID');
				$createTour = false;
			}else{
				if(isset($_REQUEST['username'])){
					$_REQUEST['userEmail'] = $_REQUEST['username'];
				}
				// Pull the userID via userEmail if set and user exist
				if(isset($_REQUEST['userEmail'])&&(!isset($_REQUEST['userID'])||empty($_REQUEST['userID']))){
					$users = new users();
					if($users->userExist($_REQUEST['userEmail'])){
						$_REQUEST['userID'] = $users->userID;
					}else{
						// If userEmail has been passed and the user does not exist create the user!
						$_REQUEST['username'] = $_REQUEST['userEmail'];
						$users = new users();
						if(isset($_REQUEST['office_id'])){
							$officeIDToBkrID = array(
								'305' => '406',
								'306' => '407',
								'307' => '408',
								'308' => '409',
								'309' => '409',
								'310' => '411',
								'311' => '412',
								'312' => '413',
								'313' => '413',
								'314' => '419',
								'315' => '419',
								'316' => '420',
								'317' => '410',
								'318' => '415',
								'319' => '416',
								'320' => '418',
								'321' => '417'
								
							);
							$_REQUEST['BrokerageID'] = $officeIDToBkrID[$_REQUEST['office_id']];
						}
						if(!isset($_REQUEST['password'])||empty($_REQUEST['password'])){
							$security = new security();
							$_REQUEST['password'] = $security->generatePassword();
						}
						if(!isset($_REQUEST['userType'])||empty($_REQUEST['userType'])){
							$_REQUEST['userType'] = 'Agent';
						}
						if(!isset($_REQUEST['BrokerageID'])||empty($_REQUEST['BrokerageID'])){
							$_REQUEST['BrokerageID'] = 0;
						}
						// Create instance of validation Object
						$validation = new validation();
						// Sanitize tour product data
						$validation->sanitize($_REQUEST);
						// Create validation rules for tour product data
						$dataValidation = array(
							'BrokerageID' => 'required|integer|max_len,11', //int(11)
							'userType' => 'required|max_len,50', //varchar(50)
							'firstName' => 'required|max_len,25', //varchar(25)
							'lastName' => 'required|max_len,24', //varchar(24)
							'username' => 'required|valid_email|max_len,60', //varchar(60)
							'password' => 'required|max_len,32' //varchar(32)
						);
						// Create filter rules for tour product data
						$dataFilers = array(
							'BrokerageID' => 'trim|sanitize_numbers', //int(11)
							'userType' => 'trim|sanitize_string', //varchar(50)
							'firstName' => 'trim|sanitize_string', //varchar(25)
							'lastName' => 'trim|sanitize_string', //varchar(24)
							'username' => 'trim|sanitize_email', //varchar(60)
							'password' => 'trim|sanitize_string' //varchar(32)
						);
						// Set tour product data validation rules
						$validation->validation_rules($dataValidation);
						// Set tour product data filter rules
						$validation->filter_rules($dataFilers);
						// Filter and validate tour product data
						$validated_data = $validation->run($_REQUEST);
						if($validated_data === false){
							// Validation failed! Letz process those errors.
							$validation->get_readable_errors(true);
							$output = array(
								'status'=>0,
								'error'=>$validation->errors->get()
							);
							// Output errors
							$api->output($output);
						}else{
							$validated_data['email'] = $validated_data['username'];
							$users->set($validated_data);
							if($users->register()){
								$_REQUEST['userID'] = $users->userID;	
							}else{
								$output = array(
									'status'=>0,
									'error'=>$users->errors->get()
								);
								// Output errors
								$api->output($output);	
							}
						}
					}
				}
			}
			// If the tourID has not been passed over then create a tour
			if($createTour){
				if(isset($_REQUEST['desired_date'])||isset($_REQUEST['desired_time'])){
					if(!isset($_REQUEST['additionalInstructions'])){
						$_REQUEST['additionalInstructions'] = '';
					}
					if(isset($_REQUEST['desired_date'])){
						$_REQUEST['additionalInstructions'] .= ' Desired shoot date: '.$_REQUEST['desired_date'];
					}
					if(isset($_REQUEST['desired_time'])){
						$_REQUEST['additionalInstructions'] .= ' Desired shoot time: '.$_REQUEST['desired_time'];
					}
				}
				$_REQUEST['vendor'] = $api->id;
				$tourID = $tours->createTour($_REQUEST);
				if($tourID){
					$_REQUEST['tourID'] = $tourID;
				}else{
					$output = array(
						'status'=>0,
						'error'=>$tours->errors->get()
					);
					$api->output($output);
					exit();
				}
			}
			// Parse products string and create products array if isset else create empty array for products
			if(isset($_REQUEST['products'])&&!empty($_REQUEST['products'])){
				$items = array();
				$products = explode(";",$_REQUEST['products']);
				foreach ($products as $prod) {
					if(!empty($prod)){
						$item = explode(",",$prod);
						$index = sizeof($items);
						$items[$index]['id'] = intval($item[0]);
						$items[$index]['qty'] = intval($item[1]);
					}
				}
				$_REQUEST['products'] = $items;
				unset($items);
			}else{
				$_REQUEST['products'] = array();
			}
			// Create instance of validation Object
			$validation = new validation();
			// Sanitize tour product data
			$validation->sanitize($_REQUEST);
			// Create validation rules for tour product data
			$dataValidation = array(
				'tourID' => 'required|integer|max_len,11', //int(11)
				'userEmail' => 'valid_email|max_len,60', //varchar(60)
				'userID' => 'required|integer|max_len,11', //int(11)
				'city' => 'required|max_len,50', //varchar(50)
				'zip' => 'required|max_len,10', //varchar(10)
				'tourTypeID' => 'required|integer|max_len,11' //int(11)
			);
			// Create filter rules for tour product data
			$dataFilers = array(
				'tourID' => 'trim|sanitize_numbers', //int(11)
				'userEmail' => 'trim|sanitize_email', //int(11)
				'userID' => 'trim|sanitize_numbers', //int(11)
				'city' => 'trim|sanitize_string', //varchar(50)
				'zip' => 'trim|sanitize_string', //varchar(10)
				'tourTypeID' => 'trim|sanitize_numbers' //int(11)
			);
			// Set tour product data validation rules
			$validation->validation_rules($dataValidation);
			// Set tour product data filter rules
			$validation->filter_rules($dataFilers);
			// Filter and validate tour product data
			$validated_data = $validation->run($_REQUEST);
			if($validated_data === false){
				// Validation failed! Letz process those errors.
				$validation->get_readable_errors(true);
				$output = array(
					'status'=>0,
					'error'=>$validation->errors->get()
				);
				// Output errors
				$api->output($output);
			}else{
				require_once ('../../transactional/transactional_pricing.php');
				$users = new users();
				$users->userID = $validated_data['userID'];
				$brokerageID = $users->getBrokerID();
				$tourtypes = array(array('id'=>intval($validated_data['tourTypeID']),'qty'=>1));
				$order = order($tourtypes, $_REQUEST['products'], $validated_data['city'], $validated_data['zip'], $brokerageID, $users->userID, "", 0);
				$orders = new orders();
				$orderInformation = array(
					'userID' => $validated_data['userID'],
					'tourid' => $validated_data['tourID'],
					'subTotal' => $order['totals']['ub_sub'],
					'salesTax' => $order['totals']['ub_tax'],
					'total' => $order['totals']['ub_total'],
					'broker_total' => $order['totals']['bb_total'],
					'broker_paySold_total' => $order['totals']['bb_paySoldTotal'],
					'agent_paySold_total' => $order['totals']['ub_paySoldTotal'],
					'paid' => 0
				);
				$orderID = $orders->saveOrder($orderInformation);
				$lineItems = array();
				foreach($order['lines'] as $lineIndex => $lineItem){
					$lineItems[$lineIndex]['type'] = $lineItem['itemType'];
					$lineItems[$lineIndex]['productID'] = $lineItem['itemID'];
					$lineItems[$lineIndex]['quantity' ] = $lineItem['qty'];
					$lineItems[$lineIndex]['unitPrice'] = $lineItem['ub_item'];
					$lineItems[$lineIndex]['broker_price'] = $lineItem['bb_item'];
				}
				$orders->saveOrderDetails($lineItems, $orderID);
				$output = array(
					'status'=>1,
					'orderID'=>$orderID,
					'userID'=>$validated_data['userID'],
					'tourID'=>$validated_data['tourID']
				);
				// Output orderID
				$api->output($output);
			}
		break;
		
		// GET LIST OF USER TOURS BY userID / email
		case'getusertours':
			if(isset($_REQUEST['userID'])){
				$tourIDRes = $tours->getTours(intval($_REQUEST['userID']), true, "", "10", "t.createdOn", "", 't.tourID', true);
				$tourIDs = array();
				foreach($tourIDRes as $row => $columns){
					$tourIDs[] = $columns['tourID'];
				}
				$output = array(
					"status"=>1,
					'tourID'=>$tourIDs
				);
				$api->output($output);
			}else if(isset($_REQUEST['email'])){
				$users = new users();
				$userID = $users->getUserByEmail($_REQUEST['email']);
				if(is_null($userID)){
					$errors->set('User not found! I\'m sorry we can not find a user in our database with the username or email adress: '.$_REQUEST['email']);
					$output = array(
						"status"=>0,
						'error'=>$errors->get()
					);
					$api->output($output);
				}else{
					$tourIDRes = $tours->getTours($userID, true, "", "10", "t.createdOn", "", 't.tourID', true);
					$tourIDs = array();
					foreach($tourIDRes as $row => $columns){
						$tourIDs[] = $columns['tourID'];
					}
					$output = array(
						"status"=>1,
						'tourID'=>$tourIDs
					);
					$api->output($output);
				}
			}else{
				$errors->set('userID or email is required. Example: key=your_api_key&method=getusertours&email=jacob@spotlighthometours.com&format=xml');
				$output = array(
					"status"=>0,
					'error'=>$errors->get()
				);
				$api->output($output);
			}
		break;
	}
}else{
	// API USER NOT FOUND! INVALID API KEY!
	print 'API user not found! Invalid API key!';
}
	
?>