<?php
/**********************************************************************************************
Document: /api/users/
Creator: Jacob Edmond Kerr
Date: 01-25-12
Purpose: Handles all API request for users of any kind. Output XML / JSON 
**********************************************************************************************/

//=======================================================================
// Includes
//=======================================================================

	// Include appplication's global configuration
	require_once('../../repository_inc/classes/inc.global.php');
	
//=======================================================================
// Objects
//=======================================================================

	// Create Needed Objects
	(isset($_REQUEST['format'])&&!empty($_REQUEST['format']))?$api = new api($_REQUEST['format']):$api = new api();
	$users = new users($db);
	$errors = new errors();
	
//=======================================================================
// Document
//=======================================================================

switch(strtolower($_REQUEST['method'])){
	case'createapiuser':
		if(isset($_REQUEST['name'])&&!empty($_REQUEST['name'])){
			if($api->createUser($_REQUEST['name'])){
				print "<h1>API USER CREATED!<br/> USER'S NAME: ".$api->name." <br/>USER'S KEY: ".$api->key."</h1>";
			}else{
				print "<h1>There was an error :(. Here it is: ".$api->errors->getUL()."</h1>";
			}
		}else{
			print "<h1>Name is required! Please pass the name of the API user to this page via GET / POST method. Example: ?name=RealCover</h1>?";
		}
	break;
	case'userexists':
		if($api->loadUserByKey($_REQUEST['key'])){
			// Create instance of validation Object
			$validation = new validation();
			// Sanitize tour product data
			$validation->sanitize($_REQUEST);
			// Create validation rules for tour product data
			$dataValidation = array(
				'userEmail' => 'required|valid_email|max_len,60', //varchar(60)
			);
			// Create filter rules for tour product data
			$dataFilers = array(
				'userEmail' => 'trim|sanitize_email', //varchar(60)
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
				if($users->userExist($validated_data['userEmail'])){
					$output = array(
						'status'=>1,
						'userID'=>$users->userID,
						'userExists'=>1
					);
					// Output response
					$api->output($output);	
				}else{
					$output = array(
						'status'=>1,
						'userID'=>0,
						'userExist'=>0
					);
					// Output response
					$api->output($output);	
				}
			}
		}else{
			// API USER NOT FOUND! INVALID API KEY!
			$output = array(
				'status'=>0,
				'error'=>'API user not found! Invalid API key!'
			);
			// Output errors
			$api->output($output);
		}
	break;
	case'createuser':
		if($api->loadUserByKey($_REQUEST['key'])){
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
				if(isset($_REQUEST['phone'])){
					$validated_data['phone'] = $_REQUEST['phone'];
				}
				$users->set($validated_data);
				if($users->register()){
					$output = array(
						'status'=>1,
						'userID'=>$users->userID
					);
					// Output response
					$api->output($output);	
				}else{
					$output = array(
						'status'=>0,
						'error'=>$users->errors->get()
					);
					// Output errors
					$api->output($output);	
				}
			}
		}else{
			// API USER NOT FOUND! INVALID API KEY!
			$output = array(
				'status'=>0,
				'error'=>'API user not found! Invalid API key!'
			);
			// Output errors
			$api->output($output);
		}
	break;
	default:
		if($api->loadUserByKey($_REQUEST['key'])){
	
		}else{
			print 'User not found';
		}
	break;
}
	
?>