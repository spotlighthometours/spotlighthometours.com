<?php
/**********************************************************************************************
Document: mc_operations.php
Creator: Brandon Freeman
Date: 01-31-11
Purpose: All the goodies for working with MailChimp ... mostly importing stuff from the DB.
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

// Connect to MySQL
require_once 'inc/connect.php';
// MailChimp API
require_once 'inc/MCAPI.class.php';
// API Key
require_once 'inc/key.php';

//=======================================================================
// Functions
//=======================================================================

function getmicrotime(){ 
  list($usec, $sec) = explode(" ",microtime()); 
  return ((float)$usec + (float)$sec); 
} 

//=======================================================================
// Some Variables to Set
//=======================================================================

// The following are default values.

// Do you want to sync the list structure?
$syncList = false;

// Do you want to sync the list data?
$syncData = false;

// Do you want to run a test on the ouput?
$test = true;  // We want the script to default to a test is no parameters are passed.

// What is the name of the list we are working on?
$listname = null;

//=======================================================================
// Start doing stuff
//=======================================================================

// This script is probably going to take a bit ... better turn off execution time.
if( !ini_get('safe_mode') ){ 
	set_time_limit(0); 
}

// Set the values based on parameters
if (isset($_GET['list'])) {
	$listname = $_GET['list'];
}

// Selecting an operation will bump out of test.
if (isset($_GET['synclist'])) {
	if ($_GET['synclist'] == "t") {
		$syncList = true;
		$test = false;
	}
}

if (isset($_GET['syncdata'])) {
	if ($_GET['syncdata'] == "t") {
		$syncData = true;
		$test = false;
	}
}

// start the global timer.
$global_start = getmicrotime();

// If you are testing by default, list some info about usage.
if ($test) {
	echo "MailChimp Operations Interface<br />";
	echo "A test has been called on this system.  So far so good. <br /><br />";
	echo "Parameters:<br />";
	echo "list - (string) - Set the name of the list in question.<br />";
	echo "synclist - (t/f) - Sync the list structure.<br />";
	echo "syncdata - (t/f) - Sync the list data.<br /><br />";
	echo "Additional Parameters for syncdata.<br />";
	echo "batchsize - (integer) - Number of records to pull at a time.<br />";
	echo "partial - (t/f) - Only sync the first batch of records.<br /><br />";
	echo "Ex. mc_operations.php?list=test&syncdata=t&partial=t&batchsize=10<br /><br />";
	
} else {

	// load the api
	$api = new MCAPI($apikey);

	if (isset($listname)) {
		// Find the listId by the name of the list.
		$return = $api->lists();
		if ($api->errorCode){
			echo "Unable to load lists()!<br />";
			echo "[" . $api->errorCode . "] " . $api->errorMessage . "<br />";
		} else {
			foreach ($return['data'] as $list){
				if ($list['name'] == $listname) {
					$listId = $list['id'];
					echo ("Found List: " . $listname . " => " . $listId . "<BR />");
				}
			}
			if (!isset($listId)) {
				echo "Unable to find the specified list.<br />";
			}
		}
		echo "<br />";
	} else {
		echo "No list was selected.<br />";
	}

	if ($syncList && isset($listId)) {
		$listItems = array();

		// Globals for the different values.
		// These are used for all fields.  Generic.
		$required = false; // Is the field required?
		$public = true; // Is the field publicly accessible?
		$show = true; // Is the field publicly viewable?

		// Building information about our different field, then shoving it into an array.  Good times.
		// Keep the tag parameter the same as the column name from the query.
		// That way, you can just add each row to the batch without having to do anything.
		$mvTag = "ADDRESS";
		$mvName = "Address";
		$mvType = "text"; //one of: text, number, radio, dropdown, date, address, phone, url, imageurl - defaults to text
		$mvDefault = null;
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "CITY";
		$mvName = "City";
		$mvType = "text";
		$mvDefault = null;
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "ZIP";
		$mvName = "Zip Code";
		$mvType = "text";
		$mvDefault = null;
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "CREATED";
		$mvName = "Date Created";
		$mvType = "date";
		$mvDefault = null;
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "LAST";
		$mvName = "Last Tour Date";
		$mvType = "date";
		$mvDefault = null;
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TOURS";
		$mvName = "Number of Tours";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_VWTT"; // TT means Tour Type
		$mvName = "Video Walk-Through Tour";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_VT";
		$mvName = "Video Tour";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_MPTP";
		$mvName = "Motion Photo Tour Plus";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_MPT";
		$mvName = "Motion Photo Tour";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_SPT";
		$mvName = "Still Photo Tour";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_TMPT";
		$mvName = "Twilight Motion Photo Tour";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_TST";
		$mvName = "Twilight Still Tour";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_SEDIY";
		$mvName = "Spotlight Express DIY Tour";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_ER";
		$mvName = "Exterior Reshoot";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_EPT";
		$mvName = "Express Pro Tour";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_PSO";
		$mvName = "Photo Stills Only";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_MPTPR";
		$mvName = "Motion Photo Tour Premium";
		$mvType = "number";
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "TT_MLSMT";
		$mvName = "MLS Mini Tour";
		$mvType = "number"; 
		$mvDefault = "0";
		$mvChoices = null;
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		// The following fields have dropdown boxes that are populated from unique table values.
		$mvTag = "BROKER";
		$mvName = "Brokerage";
		$mvType = "dropdown";
		$mvDefault = "NONE";
		$mvChoices = array("NONE");
		$query = "SELECT DISTINCT brokerageName FROM brokerages ORDER BY brokerageName";
		echo "Running Query To Gather " . $mvName . "s.<br />";
		$time_start = getmicrotime();
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		echo "Query returned " . mysql_num_rows($r) . " results in " . round(getmicrotime() - $time_start, 3) .  " seconds. <br />";
		while($result = mysql_fetch_array($r)){
			array_push($mvChoices, $result['brokerageName']);
		}
		echo sizeof($mvChoices) . " options are available for " . $mvName . ". <br /><br />"; 
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "REP";
		$mvName = "Sales Rep";
		$mvType = "dropdown";
		$mvDefault = "NONE";
		$mvChoices = array("NONE");
		$query = "SELECT DISTINCT fullName FROM salesreps ORDER BY fullName";
		echo "Running Query To Gather " . $mvName . "s.<br />";
		$time_start = getmicrotime();
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		echo "Query returned " . mysql_num_rows($r) . " results in " . round(getmicrotime() - $time_start, 3) .  " seconds. <br />";
		while($result = mysql_fetch_array($r)){
			array_push($mvChoices, $result['fullName']);
		}
		echo sizeof($mvChoices) . " options are available for " . $mvName . ". <br /><br />"; 
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		$mvTag = "STATE";
		$mvName = "State";
		$mvType = "dropdown";
		$mvDefault = "NONE";
		$mvChoices = array("NONE");
		$query = "SELECT DISTINCT stateAbbrName FROM states ORDER BY stateAbbrName";
		echo "Running Query To Gather " . $mvName . "s.<br />";
		$time_start = getmicrotime();
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		echo "Query returned " . mysql_num_rows($r) . " results in " . round(getmicrotime() - $time_start, 3) .  " seconds. <br />";
		while($result = mysql_fetch_array($r)){
			array_push($mvChoices, $result['stateAbbrName']);
		}
		echo sizeof($mvChoices) . " options are available for " . $mvName . ". <br /><br />"; 
		array_push($listItems, array($mvTag, $mvName, $mvType, $mvDefault, $mvChoices));

		// Delete Merge Variables from the array
		/*if (isset($listId) && isset($listItems) && $purge) {

			foreach ($listItems as $item) { 

				$return = $api->listMergeVarDel($listId, $item[0]);
				if ($api->errorCode){
					echo "Unable to remove merge variable!<br />";
					echo "[" . $api->errorCode . "] " . $api->errorMessage . "<br />";
				} else {
					if ($return) {
						echo "Successfully Removed Merge Variable '" . $item[1] . "'.<br />";
					}
				}
				echo "<br />";
			}
		}*/
		
		
		// Add Merge Variables/Fields from the array
		if (isset($listId) && sizeof($listItems) > 0) {

			foreach ($listItems as $item) { 
				$time_start = getmicrotime();
				$options = array('field_type'=>$item[2], 'req'=>$required, 'public'=>$public, 'show'=>$show, 'default_value'=>$item[3], 'choices'=>$item[4]);
				$return = $api->listMergeVarAdd($listId, $item[0], $item[1], $options);
				if ($api->errorCode){
					echo "Unable to add merge variable!<br />";
					echo "[" . $api->errorCode . "] " . $api->errorMessage . "<br />";
					//Add Failed, so try an update.
					$options = array('field_type'=>null, 'req'=>$required, 'public'=>$public, 'show'=>$show, 'default_value'=>$item[3], 'choices'=>$item[4]);
					$return = $api->listMergeVarUpdate($listId, $item[0], $options);
					if ($api->errorCode){
						echo "Unable to update merge variable!<br />";
						echo "[" . $api->errorCode . "] " . $api->errorMessage . "<br />";
					} else {
						if ($return) {
							echo "Successfully Updated Merge Variable '" . $item[1] . "'.<br />";
						}
					}
				} else {
					if ($return) {
						echo "Successfully Added Merge Variable '" . $item[1] . "'.<br />";
					}
				}
				echo "This operation took " . round(getmicrotime() - $time_start, 3) .  " seconds. <br />";
				echo "<br />";
			}
		}
	}

	if ($syncData && isset($listId)) {
		// Get the cound of the number of rows we will be playing with.
		$query = "SELECT email FROM users WHERE users.email LIKE '%@%'";
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error());
		$numRows = mysql_num_rows($r);

		// Some variables for the batch add.
		$optin = false; // Do you want to send op-in (permission) emails?
		$up_exist = true; // Do you want to update existing users?
		$replace_int = false; // Do you want to replace interests instead of adding new?

		$batchMax = 1000;  // You will send data to mailchimp in chunks of this size.
		$currentBatchPos = 0;  // This is your starting position.
		
		// If you would like to specify your batch size
		if (isset($_GET['batchsize'])) {
			$batchMax = $_GET['batchsize'];
		}
		
		while ($currentBatchPos < $numRows) {

			echo "Selecting " . $batchMax . " at position " . $currentBatchPos . ".<br />";

			$query = "".
				"SELECT users.firstname AS FNAME, users.lastname AS LNAME, users.email AS EMAIL, users.address AS ADDRESS, users.city AS CITY, users.state AS STATE, users.zipCode AS ZIP, users.datecreated AS CREATED, " . 
				"brokerages.brokerageName AS BROKER, " . 
				"salesreps.fullName AS REP, " . 
				"(SELECT createdOn FROM tours ORDER BY createdOn DESC LIMIT 1) AS LAST, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID) AS TOURS, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 1)  AS TT_VWTT, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 5)  AS TT_VT, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 15) AS TT_MPTP, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 13) AS TT_MPT, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 10) AS TT_SPT, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 16) AS TT_TMPT, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 17) AS TT_TST, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 18) AS TT_SEDIY, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 25) AS TT_ER, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 22) AS TT_EPT, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 27) AS TT_PSO, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 28) AS TT_MPTPR, " . 
				"(SELECT COUNT(*) FROM tours WHERE users.userID = tours.userID AND tours.tourTypeID = 30) AS TT_MLSMT " . 
				"FROM users " . 
				"LEFT JOIN brokerages ON users.BrokerageID = brokerages.brokerageID " . 
				"LEFT JOIN salesreps  ON users.salesRepID  = salesreps.salesRepID " . 
				"WHERE users.email LIKE '%@%' " . 
				"ORDER BY users.email " . 
				"LIMIT " . $currentBatchPos . "," . $batchMax . 
			"";

			$time_start = getmicrotime();
			$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
			echo "Query returned " . mysql_num_rows($r) . " results in " . round(getmicrotime() - $time_start, 3) .  " seconds. <br />";
			
			$batch = array();

			//shove each row result into the batch.
			while($result = mysql_fetch_array($r)){
				array_push($batch, $result);
			}
			
			$time_start = getmicrotime();
			// Move the batch along to MailChimp.
			$vals = $api->listBatchSubscribe($listId,$batch, $optin, $up_exist, $replace_int);
			
			if ($api->errorCode){
				echo "Batch Subscribe failed!<br />";
				echo "[" . $api->errorCode . "] " . $api->errorMessage . "<br />";
			} else {
				echo "added:   ".$vals['add_count']."<br />";
				echo "updated: ".$vals['update_count']."<br />";
				echo "errors:  ".$vals['error_count']."<br />";
				foreach($vals['errors'] as $val){
					echo "[" . $val['code'] . "] " . $val['message'] . "<br />";
				}
			}
			echo "Operation took " . round(getmicrotime() - $time_start, 3) .  " seconds. <br />";
			echo "<br />";
			
			$currentBatchPos += $batchMax;
			
			// In the offchance that you only want the first few records.
			if ($_GET['partial'] == "t") {
				$currentBatchPos = $numRows;
			}
		}
	}	
}

// A little info about how long the entire process took.
echo "Full operation took " . round(getmicrotime() - $global_start, 3) .  " seconds. <br />";
?>
