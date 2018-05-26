<?php
ini_set ('display_errors', 1);
error_reporting (E_ALL & ~E_NOTICE);

session_start();

unset($_SESSION['broker_id_switch']);

$userid = '';
if(isset($_POST['userid'])) {
	$userid = intval($_POST['userid']);
} elseif (isset($_GET['userid'])) {
	$userid = intval($_GET['userid']);
}

if( isset($_REQUEST['orderType']) && $_REQUEST['orderType'] == 'schedulednotordered' ){
    $notOrdered = $_REQUEST['tourId'];
    $extra = "&notOrdered=$notOrdered";
}else{
    $extra = "";
}

// The checkout options
if(isset($_SESSION['team_user_id'])) {
	$old_checkout = '/teamtools/team_login_router.php?userid=' . $userid . '&' . $extra . '&destination=/checkout/checkout.php';
} else {
	$old_checkout = '/checkout/checkout.php?' . $extra . '&';
}
$new_checkout = '/checkout_v2/checkout.php?' . $extra . '&';
if(isset($_REQUEST['session_id'])){
	$new_checkout .= 'session_id='.$_REQUEST['session_id'].'&userid=' . $userid;
}else{
	$new_checkout .= 'userid=' . $userid;
}

if(isset($_SESSION['team_user_id'])) {
	$new_checkout = '/teamtools/team_login_router.php?userid=' . $userid . '&' . $extra . '&destination=/checkout_v2/checkout.php';
}

// Are we testing?
// if we aren't everyone gets sent to the new checkout
// otherwise, users defined in the test group will go to the new checkout
// and everyone else goes to the old one.
$testing = false;

// define brokerages we are testing on.
$test_group = array();
$test_group[] = 445;
$test_group[] = 9;
$test_group[] = 413;


// array of coldwell brokerage ids
$coldwell = array();
$coldwell[] = 406;
$coldwell[] = 407;
$coldwell[] = 408;
$coldwell[] = 409;
$coldwell[] = 410;
$coldwell[] = 411;
$coldwell[] = 412;
$coldwell[] = 413;
$coldwell[] = 414;
$coldwell[] = 415;
$coldwell[] = 416;
$coldwell[] = 417;
$coldwell[] = 418;
$coldwell[] = 419;
$coldwell[] = 420;
$coldwell[] = 421;
$coldwell[] = 445;

// array of fuller brokerage ids
$fuller = array();
$fuller[] = 27;
$fuller[] = 114;
$fuller[] = 163;
$fuller[] = 731;
$fuller[] = 402;


$coldwell_chooser = "/checkout_v2/checkout_coldwell_chooser.php?$extra&";
$fuller_chooser = "/checkout_v2/checkout_fuller_chooser.php?$extra&";

if(isset($_REQUEST['session_id'])){
	$coldwell_chooser .= 'session_id='.$_REQUEST['session_id'];
	$coldwell_chooser .= "&userid=" . $userid;
}else{
	$coldwell_chooser .= "userid=" . $userid;
	$fuller_chooser .= "userid=" . $userid;
}

// Get the users brokerage id
include ('../repository_inc/data.php');
$dbh = new PDO("mysql:host=" . $server . ";dbname=" . $database, $username, $password);
$dbh->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$query = 'SELECT brokerageID FROM users WHERE userId = :userid LIMIT 1';
if($stmt = $dbh->prepare($query)) {
	$stmt->bindParam(':userid', $userid);
	try {
		$stmt->execute();
	} catch (PDOException $e){
		WriteLog("checkout_router", $e->getMessage());
	}
	$result = $stmt->fetch();
}

$b_id = -1;
if(isset($result['brokerageID']) && intval($result['brokerageID']) >= 0) {
	$b_id = intval($result['brokerageID']);
	
	// is the user part of the coldwell team from the array?
	if(in_array($b_id, $coldwell)) {
		$is_coldwell = true;
	} else {
		$is_coldwell = false;
	}
	
	// is the user part of the fuller team from the array?
	if(in_array($b_id, $fuller)) {
		$is_fuller = true;
	} else {
		$is_fuller = false;
	}
	
	// are we testing?
	if($testing) {
		if(in_array($b_id, $test_group)) {
			if($is_coldwell) {
				Relocate($coldwell_chooser);
			} elseif($is_fuller) {
				Relocate($fuller_chooser);
			}else{
				Relocate($new_checkout);	
			}
		} else {
			Relocate($old_checkout);	
		}
	} else {
		if($is_coldwell) {
			Relocate($coldwell_chooser);
		} elseif($is_fuller) {
			Relocate($fuller_chooser);
		}else{
			Relocate($new_checkout);	
		}	
	}
	
}
function Relocate($location) {
	header('Location: ' . $location);
	ob_flush();	
}

?>
