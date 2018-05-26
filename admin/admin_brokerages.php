<?php
/**********************************************************************************************
Document: admin_brokerages.php
Creator: Brandon Freeman
Date: 05-16-11
Purpose: Lists brokerages.
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
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');
	
	// Include appplication's global configuration
	require_once('../repository_inc/classes/inc.global.php');
	showErrors();
	
//=======================================================================
// Objects
//=======================================================================

	$mls = new mls();
	$brokerages = new brokerages();
	
//=======================================================================
// Document
//=======================================================================
	// Pull list of MLS providers
	$MLSProviders = $mls->getProviders();
	
	// Start the session
	session_start();
	
	$debug = false;
	
	// Require Admin Login
	if (!$debug) {
		require_once ('../repository_inc/require_admin.php');
	}
	
	if(isset($_REQUEST['id'])&&isset($_REQUEST['duplicate'])&&$_REQUEST['duplicate']=='true'){
		$newBkrID = $brokerages->duplicate($_REQUEST['id']);
		header('Location: admin_brokerages.php?id='.$newBkrID);
		exit();
	}
	
	if (isset($_POST['id'])) {
		$id = CleanQuery($_POST['id']);
	} elseif (isset($_GET['id'])) {
		$id = CleanQuery($_GET['id']);
	}
	
	$index = 0;
	if (isset($_POST['index'])) {
		$index = CleanQuery($_POST['index']);
	} elseif (isset($_GET['index'])) {
		$index = CleanQuery($_GET['index']);
	}
	if ($index < 0) $index = 0;
	
	$max = 20;
	if (isset($_POST['max'])) {
		$max = CleanQuery($_POST['max']);
	} elseif (isset($_GET['max'])) {
		$max = CleanQuery($_GET['max']);
	}
	
	$search = "";
	if (isset($_POST['search'])) {
		$search = CleanQuery($_POST['search']);
	} elseif (isset($_GET['search'])) {
		$search = CleanQuery($_GET['search']);
	}
	
	$state = "";
	if (isset($_POST['state'])) {
		$state = CleanQuery($_POST['state']);
	} elseif (isset($_GET['search'])) {
		$state = CleanQuery($_GET['state']);
	}
	
	if (isset($_POST['new']) || isset($_GET['new'])) {
		$new = true;
	}
	
	// Pull youtube accounts
	$ytAccs = $brokerages->getYoutubeAccs($id);

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Admin - Brokerages</title>
        <link type="text/css" href="../repository_css/admin.css" rel="stylesheet" />
		<script src="../repository_inc/jquery-1.6.2.min.js"></script>
        <script type="text/javascript">
		
			//Create the http request object
			if (window.XMLHttpRequest) {
				XMLHttpRequestObject = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			function duplicateTour(brokerageName, brokerageID){
				var dupIt = confirm("Are you sure you want to duplicate the following brokerage: "+brokerageName+"?");
				if(dupIt){
					window.location = '?id='+brokerageID+'&duplicate=true';
				}
			}
			
			function SetUserPermissions(id, action, brokerageID) {
//				query = "Action="+action+"&MembershipID="+id+"&BrokerageID="+brokerageID;
//				var url = "../repository_queries/admin_brokerage_SetMemberPermissions.php?"+query;
//				params = "";
//				
//				ajaxQuery(url, params, 'nothing');
				
				try {
					var url = "../repository_queries/admin_brokerage_SetMemberPermissions.php";
					var params = "Action="+action+"&MembershipID="+id+"&BrokerageID="+brokerageID;
					
					if(XMLHttpRequestObject) {
						XMLHttpRequestObject.open("POST", url, true);
						XMLHttpRequestObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						XMLHttpRequestObject.setRequestHeader("Content-length", params.length);
						XMLHttpRequestObject.setRequestHeader("Connection", "close");
						
						XMLHttpRequestObject.onreadystatechange = function() { 
							if (XMLHttpRequestObject.readyState == 4 && XMLHttpRequestObject.status == 200) {
								window.history.back();
							}
						}
						XMLHttpRequestObject.send(params);
						
					}
				} catch(err) {
					window.alert(err);
				}
			}
			
			function ConfirmDelete(itemName, url) {
				if (confirm("Delete " + itemName + "?! \nAre you sure?")) {
					window.location.href = url;
				}
			}
			
			function GetForm(sender, target, name) {
				try {
					if (document.getElementById(sender).selectedIndex > 0 ) {
						var selection = document.getElementById(sender).options[document.getElementById(sender).selectedIndex].value;
						
						var url = "admin_brokerages_types.php";
						var params  = "type=" + selection + "&name=" + name;
				
						var HTTP = false;
						if (window.XMLHttpRequest) {
							HTTP = new XMLHttpRequest();
						} else if (window.ActiveXObject) {
							HTTP = new ActiveXObject("Microsoft.XMLHTTP");
						}
						
						if(HTTP) {
							HTTP.open("POST", url, true);
							HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
							HTTP.setRequestHeader("Content-length", params.length);
							HTTP.setRequestHeader("Connection", "close");

							HTTP.onreadystatechange = function() { 
								if (HTTP.readyState == 4 && HTTP.status == 200) {
									document.getElementById(target).innerHTML = HTTP.responseText;
								}
							}
							HTTP.send(params);
						}
					}
				} catch(err) {
					window.alert("GetForm: " + err + ' (line: ' + err.line + ')');
				}	
			}
			
			function NewWindow(url, x, y) {
				try {
					window.open(url,'Preview',"location=0,status=0,scrollbars=1, width=" + x + ",height=" + y);
				} catch(err) {
					window.alert(err);
				}
			}
			
			function tourTypesWindow(){
				NewWindow('brokerage_tourtypes.php?id=<?PHP echo $_GET['id'] ?>', '800', '500');
			}
			
			function productsWindow(){
				NewWindow('brokerage_products.php?id=<?PHP echo $_GET['id'] ?>', '800', '500');
			}
			
			function MembershipsWindow(){
				NewWindow('brokerage_Memberships.php?id=<?PHP echo $_GET['id'] ?>', '800', '500');
			}
			
			function addMLS(obj){
				$(obj).parent().append($('#mls_html_holder').html());
			}
			
			function removeMLS(obj){
				$(obj).parent().remove();
			}
		</script>
    </head>
    <body>
<?php
/**********************************************************************************************
SOMETHING WAS SUCCESSFUL
**********************************************************************************************/
	if (isset($_GET['success'])) {	
		echo '
			<div class="formrow" >
				Operation was successful.
			</div>
		';
	}
/**********************************************************************************************
DELETE STUFF
**********************************************************************************************/
	if (isset($_GET['op']) && isset($id)) {
		switch ($_GET['op']) {
			case "del":
				$query = 'DELETE FROM brokerages WHERE brokerageID = "' . $id . '" LIMIT 1';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
				
				$query = 'DELETE FROM nf_pricing WHERE category = "broker" AND categoryID = "' . $id . '"';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
				
				$query = 'DELETE FROM nf_broker_billing WHERE brokerID = "' . $id . '"';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
				
				$query = 'DELETE FROM teams_to_brokerages WHERE brokerage_id = "' . $id . '"';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
				
				$query = 'DELETE FROM brokerage_to_mls WHERE brokerageID = "' . $id . '"';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
				
				/*$query = 'DELETE FROM nf_broker_blacklist WHERE brokerID = "' . $id . '"';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);*/
				
				header('Location: ' . basename($_SERVER['PHP_SELF']));
				ob_flush();
				break;
				
			case "del_bp":
				if (isset($_GET['pid'])) {
					$query = 'DELETE FROM nf_pricing WHERE pricingID = "' . CleanQuery($_GET['pid']) . '"';
					mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
				}
				echo $query;
				header('Location: ' . basename($_SERVER['PHP_SELF']) . '?id=' . $id);
				ob_flush();
				break;
				
			case "del_bl":
				if (isset($_GET['blid'])) {
					$query = 'DELETE FROM nf_blacklist WHERE blacklistID = "' . CleanQuery($_GET['blid']) . '"';
					mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
				}
				echo $query;
				header('Location: ' . basename($_SERVER['PHP_SELF']) . '?id=' . $id);
				ob_flush();
				break;
				
			case "del_bb":
				if (isset($_GET['bbid'])) {
					$query = 'DELETE FROM nf_broker_billing WHERE bbID = "' . CleanQuery($_GET['bbid']) . '"';
					mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
				}
				echo $query;
				header('Location: ' . basename($_SERVER['PHP_SELF']) . '?id=' . $id);
				ob_flush();
				break;
		}
/**********************************************************************************************
UPDATE EXISTING BROKERAGE
**********************************************************************************************/	
	} elseif (isset($_POST['update'])) {		   
//var_dump($_FILES);
		$work = false;
		$first = true;

		if (isset($id) && !isset($_POST['op'])) {
			$query =   'SELECT 
						b.brokerageName AS name, b.brokerageDesc AS description, b.brokerageContactPhone AS cPhone, 
						b.brokerageSchedulePhone AS sPhone, b.brokerageContactEmail AS cEmail,
						b.brokerageNotifyPhone AS nPhone, b.brokerageNotifyEmail AS nEmail, 
						b.address, b.city, b.state, b.zipCode, b.url, b.TourWindowType, 
						b.brokerageCountry AS country, b.affiliatePhotographerID,
						b.theme_id AS theme, b.salesRepID, b.logo, b.secondary_logo, b.logo_link, b.secondary_logo_link, b.hidden,
						b.flyerLogoBlackOnWhite, b.flyerLogoWhiteOnBlack
						FROM brokerages b
						WHERE b.brokerageID = "' . $id . '"
						LIMIT 1
					   ';
			$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
			$result = mysql_fetch_array($r);
			
			$query = 'UPDATE brokerages SET ';
			
			if (isset($_POST['name'])) {
				if (trim(CleanQuery($_POST['name'])) != $result['name']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'brokerageName = "' . trim(CleanQuery($_POST['name'])) . '"';
				}
			}
			
			if (isset($_POST['url'])) {
				if (trim(CleanQuery($_POST['url'])) != $result['url']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'url = "' . trim(CleanQuery($_POST['url'])) . '"';
				}
			}
			
			if (isset($_POST['descrip'])) {
				if ($_POST['descrip'] != $result['description']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'brokerageDesc = "' . trim(CleanQuery($_POST['descrip'])) . '"';
				}
			}
			
			if (isset($_POST['cPhone'])) {
				if (trim(CleanQuery($_POST['cPhone'])) != $result['cPhone']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'brokerageContactPhone = "' . trim(CleanQuery($_POST['cPhone'])) . '"';
				}
			}
			
			if (isset($_POST['sPhone'])) {
				if (trim(CleanQuery($_POST['sPhone'])) != $result['sPhone']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'brokerageSchedulePhone = "' . trim(CleanQuery($_POST['sPhone'])) . '"';
				}
			}
			
			if (isset($_POST['cEmail'])) {
				if (trim(CleanQuery($_POST['cEmail'])) != $result['cEmail']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'brokerageContactEmail = "' . trim(CleanQuery($_POST['cEmail'])) . '"';
				}
			}
			
			if (isset($_POST['nPhone'])) {
				if (trim(CleanQuery($_POST['nPhone'])) != $result['nPhone']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'brokerageNotifyPhone = "' . trim(CleanQuery($_POST['nPhone'])) . '"';
				}
			}
			
			if (isset($_POST['nEmail'])) {
				if (trim(CleanQuery($_POST['nEmail'])) != $result['nEmail']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'brokerageNotifyEmail = "' . trim(CleanQuery($_POST['nEmail'])) . '"';
				}
			}
			
			if (isset($_POST['address'])) {
				if (trim(CleanQuery($_POST['address'])) != $result['address']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'address = "' . trim(CleanQuery($_POST['address'])) . '"';
				}
			}
			
			
			if (isset($_POST['city'])) {
				if (trim(CleanQuery($_POST['city'])) != $result['city']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'city = "' . trim(CleanQuery($_POST['city'])) . '"';
				}
			}
			
			
			if (isset($_POST['state'])) {
				if (trim(CleanQuery($_POST['state'])) != $result['state']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'state = "' . trim(CleanQuery($_POST['state'])) . '"';
				}
			}
			
			
			if (isset($_POST['zipCode'])) {
				if (trim(CleanQuery($_POST['zipCode'])) != $result['zipCode']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'zipCode = "' . trim(CleanQuery($_POST['zipCode'])) . '"';
				}
			}
			
			if (isset($_POST['affiliatePhotographerID'])) {
				if ($_POST['affiliatePhotographerID'] != $result['affiliatePhotographerID']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'affiliatePhotographerID = "' . ($_POST['affiliatePhotographerID']) . '"';
				}
			}
			
			if (isset($_POST['TourWindowType'])) {
				if (trim(CleanQuery($_POST['TourWindowType'])) != $result['TourWindowType']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'TourWindowType = "' . trim(CleanQuery($_POST['TourWindowType'])) . '"';
				}
			}
			
			if (isset($_POST['country'])) {
				if (trim(CleanQuery($_POST['country'])) != $result['country']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'brokerageCountry = "' . trim(CleanQuery($_POST['country'])) . '"';
				}
			}
			
			if (isset($_POST['logo_link'])) {
				if (trim(CleanQuery($_POST['logo_link'])) != $result['logo_link']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'logo_link = "' . trim(CleanQuery($_POST['logo_link'])) . '"';
				}
			}
			
			if (isset($_POST['secondary_logo_link'])) {
				if (trim(CleanQuery($_POST['secondary_logo_link'])) != $result['secondary_logo_link']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'secondary_logo_link = "' . trim(CleanQuery($_POST['secondary_logo_link'])) . '"';
				}
			}
			
			if (isset($_FILES['file']['name'])&&!empty($_FILES['file']['name'])) {
				if (trim(CleanQuery(basename($_FILES['file']['name']))) != $result['logo']) {
					$work = true;
					$dir = $_SERVER['DOCUMENT_ROOT'] . "/images/logos";
					$path = $dir . '/' . basename( $_FILES['file']['name']);
					
					// Make sure we are only uploading images.
					$info = pathinfo($path);
					$extension = strtolower($info['extension']);
					
					if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png') {
						var_dump($_FILES);
						if (!move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
							echo "[1]file error:" . $_FILES['file']['error'];
						}
					}
					
					if (file_exists($path)) {
						if ($first) {
							$first = !$first;	
						} else {
							$query .= ', ';
						}
						$query .= 'logo = "' . trim(CleanQuery(basename($_FILES['file']['name']))) . '"';
					}
				}
			}
			
			if (isset($_FILES['file2']['name'])&&!empty($_FILES['file2']['name'])) {
				if (trim(CleanQuery(basename($_FILES['file2']['name']))) != $result['secondary_logo']) {
					$work = true;
					$dir = $_SERVER['DOCUMENT_ROOT'] . "/images/logos";
					$path = $dir . '/' . basename( $_FILES['file2']['name']);
					
					// Make sure we are only uploading images.
					$info = pathinfo($path);
					$extension = strtolower($info['extension']);
					
					if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png') {
						if (!move_uploaded_file($_FILES['file2']['tmp_name'], $path)) {
							echo "[2]file error: " . $_FILES['file2']['error'];
						}
					}
					
					if (file_exists($path)) {
						if ($first) {
							$first = !$first;	
						} else {
							$query .= ', ';
						}
						$query .= 'secondary_logo = "' . trim(CleanQuery(basename($_FILES['file2']['name']))) . '"';
					}
				}
			}
			
			if (isset($_FILES['fileBlackOnWhite']['name'])&&!empty($_FILES['fileBlackOnWhite']['name'])) {
				if (trim(CleanQuery(basename($_FILES['fileBlackOnWhite']['name']))) != $result['flyerLogoBlackOnWhite']) {
					$work = true;
					$dir = $_SERVER['DOCUMENT_ROOT'] . "/images/logos";
					$path = $dir . '/' . basename( $_FILES['fileBlackOnWhite']['name']);
					
					// Make sure we are only uploading images.
					$info = pathinfo($path);
					$extension = strtolower($info['extension']);
					
					if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png') {
						if (!move_uploaded_file($_FILES['fileBlackOnWhite']['tmp_name'], $path)) {
							echo "[3]file error: " . $_FILES['fileBlackOnWhite']['error'];
						}
					}
					
					if (file_exists($path)) {
						if ($first) {
							$first = !$first;	
						} else {
							$query .= ', ';
						}
						$query .= 'flyerLogoBlackOnWhite = "' . trim(CleanQuery(basename($_FILES['fileBlackOnWhite']['name']))) . '"';
					}
				}
			}
			
			if (isset($_FILES['fileWhiteOnBlack']['name'])&&!empty($_FILES['fileWhiteOnBlack']['name'])) {
				if (trim(CleanQuery(basename($_FILES['fileWhiteOnBlack']['name']))) != $result['flyerLogoWhiteOnBlack']) {
					$work = true;
					$dir = $_SERVER['DOCUMENT_ROOT'] . "/images/logos";
					$path = $dir . '/' . basename( $_FILES['fileWhiteOnBlack']['name']);
					
					// Make sure we are only uploading images.
					$info = pathinfo($path);
					$extension = strtolower($info['extension']);
					
					if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png') {
						if (!move_uploaded_file($_FILES['fileWhiteOnBlack']['tmp_name'], $path)) {
							echo "[4]file error: " . $_FILES['fileWhiteOnBlack']['error'];
						}
					}
					
					if (file_exists($path)) {
						if ($first) {
							$first = !$first;	
						} else {
							$query .= ', ';
						}
						$query .= 'flyerLogoWhiteOnBlack = "' . trim(CleanQuery(basename($_FILES['fileWhiteOnBlack']['name']))) . '"';
					}
				}
			}
			
			if (isset($_POST['theme'])) {
				if (trim(CleanQuery($_POST['theme'])) != $result['theme']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'theme_id = "' . trim(CleanQuery($_POST['theme'])) . '"';
				}
			}
			
			if (isset($_POST['salesRep'])) {
				if (trim(CleanQuery($_POST['salesRep'])) != $result['salesRepID']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'salesRepID = "' . trim(CleanQuery($_POST['salesRep'])) . '"';
				}
			}
			
			if(!isset($_POST['hidden'])){
				$_POST['hidden'] = 0;
			}
			
			if (isset($_POST['hidden'])) {
				if (trim(CleanQuery($_POST['hidden'])) != $result['hidden']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'hidden = "' . trim(CleanQuery($_POST['hidden'])) . '"';
				}
			}
			
			if(!isset($_POST['allowMatterport'])){
				$_POST['allowMatterport'] = 0;
			}
			
			if (isset($_POST['allowMatterport'])) {
				if (trim(CleanQuery($_POST['allowMatterport'])) != $result['allowMatterport']) {
					$work = true;
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'allowMatterport = "' . trim(CleanQuery($_POST['allowMatterport'])) . '"';
				}
			}
			
			$query .= ' WHERE brokerageID = "' . $id . '" LIMIT 1';

			if ($work) {
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
				
//				if (isset($_POST['affiliatePhotographerID'])) {
//					if ($_POST['affiliatePhotographerID'] != $result['affiliatePhotographerID']) {
//						$query = "UPDATE users SET affiliatePhotographerID = ".$_POST['affiliatePhotographerID']." WHERE brokerageID = " . $id;
//						//echo $query;
//						$db->run($query);
//					}
//				}
			}
			
			// SAVE MLS IDS AND PROVIDERS
			$mls->saveBrokerIDs($id, $_POST['mls_id'], $_POST['mls_provider']);
			
			// Update / Insert YouTube Account if needed
			if(intval($_POST['ytID'])>0){
				if(!empty($_POST['ytUsername'])&&!empty($_POST['ytPassword'])){
					$updateInfo = array(
						"brokerageID"=>$id,
						"username"=>$_POST['ytUsername'],
						"password"=>$_POST['ytPassword']
					);
					$brokerages->updateYoutubeAcc(intval($_POST['ytID']), $updateInfo);
				}else{
					$brokerages->deleteYoutubeAcc(intval($_POST['ytID']));
				}
			}else{
				if(!empty($_POST['ytUsername'])&&!empty($_POST['ytPassword'])){
					$accInfo = array(
						"brokerageID"=>$id,
						"username"=>$_POST['ytUsername'],
						"password"=>$_POST['ytPassword']
					);
					$brokerages->saveYoutubeAcc($accInfo);
				}
			}
	
/**********************************************************************************************
CREATE NEW BROKERAGE
**********************************************************************************************/	
		} elseif (!isset($id) && isset($_POST['name'])) {

			$work = true;
			
			$query = 'INSERT INTO brokerages SET ';
			
			if (isset($_POST['name'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'brokerageName = "' . trim(CleanQuery($_POST['name'])) . '"';
			}
			
			if (isset($_POST['descrip'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'brokerageDesc = "' . trim(CleanQuery($_POST['descrip'])) . '"';
			}
			
			if (isset($_POST['cPhone'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'brokerageContactPhone = "' . trim(CleanQuery($_POST['cPhone'])) . '"';
			}
			
			if (isset($_POST['sPhone'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'brokerageSchedulePhone = "' . trim(CleanQuery($_POST['sPhone'])) . '"';
			}
			
			if (isset($_POST['cEmail'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'brokerageContactEmail = "' . trim(CleanQuery($_POST['cEmail'])) . '"';
			}
			
			if (isset($_POST['nPhone'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'brokerageNotifyPhone = "' . trim(CleanQuery($_POST['nPhone'])) . '"';
			}
			
			if (isset($_POST['nEmail'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'brokerageNotifyEmail = "' . trim(CleanQuery($_POST['nEmail'])) . '"';
			}
			
			if (isset($_POST['country'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'brokerageCountry = "' . trim(CleanQuery($_POST['country'])) . '"';
			}
			
			if (isset($_POST['affiliatePhotographerID'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'affiliatePhotographerID = "' . ($_POST['affiliatePhotographerID']) . '"';
			}
			
			if (isset($_POST['TourWindowType'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'TourWindowType = "' . trim(CleanQuery($_POST['TourWindowType'])) . '"';
			}
			
			if (isset($_POST['logo_link'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'logo_link = "' . trim(CleanQuery($_POST['logo_link'])) . '"';
			}
			
			// API KEY
			if ($first) {
				$first = !$first;	
			} else {
				$query .= ', ';
			}
			$query .= 'api_key  = "' . rtrim(base64_encode(trim(CleanQuery($_POST['name'])).rand(999, 99999)), '='). '"';
			
			if (isset($_POST['secondary_logo_link'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'secondary_logo_link = "' . trim(CleanQuery($_POST['secondary_logo_link'])) . '"';
			}
			
			if (isset($_FILES['file'])&&!empty($_FILES['file']['name'])) {
				$dir = $_SERVER['DOCUMENT_ROOT'] . "/images/logos";
				$path = $dir . '/' . basename( $_FILES['file']['name']);
				
				// Make sure we are only uploading images.
				$info = pathinfo($path);
				$extension = strtolower($info['extension']);
				
				if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png') {
					if (!move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
						//echo $_FILES['file']['error'];
					}
				}
				
				if (file_exists($path)) {
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'logo = "' . trim(CleanQuery(basename($_FILES['file']['name']))) . '"';
				}
			}
			
			if (isset($_FILES['file2'])&&!empty($_FILES['file2']['name'])) {
				$dir = $_SERVER['DOCUMENT_ROOT'] . "/images/logos";
				$path = $dir . '/' . basename( $_FILES['file2']['name']);
				
				// Make sure we are only uploading images.
				$info = pathinfo($path);
				$extension = strtolower($info['extension']);
				
				if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png') {
					if (!move_uploaded_file($_FILES['file2']['tmp_name'], $path)) {
						//echo $_FILES['file']['error'];
					}
				}
				
				if (file_exists($path)) {
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'secondary_logo = "' . trim(CleanQuery(basename($_FILES['file2']['name']))) . '"';
				}
			}
			
			if (isset($_FILES['fileBlackOnWhite']['name'])&&!empty($_FILES['fileBlackOnWhite']['name'])) {
				
				$dir = $_SERVER['DOCUMENT_ROOT'] . "/images/logos";
				$path = $dir . '/' . basename( $_FILES['fileBlackOnWhite']['name']);
				
				// Make sure we are only uploading images.
				$info = pathinfo($path);
				$extension = strtolower($info['extension']);
				
				if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png') {
					if (!move_uploaded_file($_FILES['fileBlackOnWhite']['tmp_name'], $path)) {
						echo $_FILES['fileBlackOnWhite']['error'];
					}
				}
				
				if (file_exists($path)) {
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'flyerLogoBlackOnWhite = "' . trim(CleanQuery(basename($_FILES['fileBlackOnWhite']['name']))) . '"';
				}
			}
			
			if (isset($_FILES['fileWhiteOnBlack']['name'])&&!empty($_FILES['fileWhiteOnBlack']['name'])) {
				$dir = $_SERVER['DOCUMENT_ROOT'] . "/images/logos";
				$path = $dir . '/' . basename( $_FILES['fileWhiteOnBlack']['name']);
				
				// Make sure we are only uploading images.
				$info = pathinfo($path);
				$extension = strtolower($info['extension']);
				
				if ($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png') {
					if (!move_uploaded_file($_FILES['fileWhiteOnBlack']['tmp_name'], $path)) {
						echo $_FILES['fileWhiteOnBlack']['error'];
					}
				}
				
				if (file_exists($path)) {
					if ($first) {
						$first = !$first;	
					} else {
						$query .= ', ';
					}
					$query .= 'flyerLogoWhiteOnBlack = "' . trim(CleanQuery(basename($_FILES['fileWhiteOnBlack']['name']))) . '"';
				}
			}
			
			if (isset($_POST['theme'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'theme_id = "' . trim(CleanQuery($_POST['theme'])) . '"';
			}
			
			if (isset($_POST['salesRep'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'salesRepID = "' . trim(CleanQuery($_POST['salesRep'])) . '"';
			}
			
			if(!isset($_POST['hidden'])){
				$_POST['hidden'] = 0;
			}
			
			if (isset($_POST['hidden'])) {
				if ($first) {
					$first = !$first;	
				} else {
					$query .= ', ';
				}
				$query .= 'hidden = "' . trim(CleanQuery($_POST['hidden'])) . '"';
			}
			
			if ($work) {
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
			}
			
			$query = 'SELECT brokerageID FROM brokerages WHERE brokerageName = "' . $_POST['name'] . '" LIMIT 1';
			$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
			$result = mysql_fetch_array($r);
			$id = $result['brokerageID'];
			
			// SAVE MLS IDS AND PROVIDERS
			$mls->saveBrokerIDs($id, $_POST['mls_id'], $_POST['mls_provider']);
			
			// Set all the deault items for a brokerage (made for new brokers and setting default tour types and products).
			
			// Set defaults
			$brokerages->brokerageID = $id;
			$brokerages->setDefaults();
			
			// Insert YouTube Account
			if(!empty($_POST['ytUsername'])&&!empty($_POST['ytPassword'])){
				$accInfo = array(
					"brokerageID"=>$id,
					"username"=>$_POST['ytUsername'],
					"password"=>$_POST['ytPassword']
				);
				$brokerages->saveYoutubeAcc($accInfo);
			}
		}

/**********************************************************************************************
ADD BROKERAGE PRICE
**********************************************************************************************/	
		if (isset($id)) {
			if (isset($_POST['bp_category']) && isset($_POST['bp_item']) && isset($_POST['bp_price'])) {
					$query = '
						INSERT INTO nf_pricing
						(itemType, itemID, category, categoryID, price)
						VALUES
						("' . trim(CleanQuery($_POST['bp_category'])) . '", "' . trim(CleanQuery($_POST['bp_item'])) . '", "broker", "' . $id . '", "' . trim(CleanQuery($_POST['bp_price'])) . '")
						ON DUPLICATE KEY UPDATE price = "' . trim(CleanQuery($_POST['bp_price'])) . '"
					';
					mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
			}
		}
		
/**********************************************************************************************
ADD BROKERAGE BLACKLIST
**********************************************************************************************/	
		if (isset($id)) {
			
			if (isset($_POST['bl_category']) && isset($_POST['bl_item']) ) {
					$query = '
						INSERT INTO nf_blacklist
						(itemType, itemID, category, categoryID, permission)
						VALUES
						("' . trim(CleanQuery($_POST['bl_category'])) . '", "' . trim(CleanQuery($_POST['bl_item'])) . '", "broker", "' . CleanQuery($id) . '", "' . intval(CleanQuery($_POST['bl_permission'])) . '")
						ON DUPLICATE KEY UPDATE permission = "' . intval(CleanQuery($_POST['bl_permission'])) . '"
					';
					mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
			}
		}
		
/**********************************************************************************************
ADD BROKERAGE BILLABLE
**********************************************************************************************/	

		if (isset($id)) {
			
			if (isset($_POST['bb_category']) && isset($_POST['bb_item']) && (isset($_POST['bb_dollar']) || isset($_POST['bb_percent'])) ) {
					
					
				$query = '
					INSERT INTO nf_broker_billing
					(brokerID, itemType, itemID, dollar, percent)
					VALUES
					("' . $id . '", "' . trim(CleanQuery($_POST['bb_category'])) . '", "' . trim(CleanQuery($_POST['bb_item'])) . '", "' . floatval(CleanQuery($_POST['bb_dollar'])) . '", "' . floatval(CleanQuery($_POST['bb_percent'])) . '")
					ON DUPLICATE KEY UPDATE dollar = "' . floatval(CleanQuery($_POST['bb_dollar'])) . '", percent = "' . floatval(CleanQuery($_POST['bb_percent'])) . '"
				';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
			}
			
			// Add mileage as broker billable
			if (intval($_POST['bb_mileage']) == 1) {
				$query = '
					INSERT IGNORE INTO nf_broker_billing
					(brokerID, itemType, itemID, dollar, percent)
					VALUES
					("' . $id . '", "mileage", "0", "0.0", "1.0")
				';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
			} else {
				$query = '
					DELETE FROM nf_broker_billing
					WHERE brokerID = "' . $id . '" AND itemType = "mileage"
					LIMIT 1
				';
				mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query run: " . $query);
			}
			
		}
		
/**********************************************************************************************
RETURN USER TO PAGE
**********************************************************************************************/	
		if (isset($id)) {
			header('Location: ' . basename($_SERVER['PHP_SELF']) . '?success=1&id=' . $id);
		} else {
			header('Location: ' . basename($_SERVER['PHP_SELF']) . '?success=1');
		}
		ob_flush();
		
	} elseif (isset($id) || isset($_GET['new'])) {
/**********************************************************************************************
EDIT/ADD BROKERAGE
**********************************************************************************************/	
		$query =   'SELECT 
					b.brokerageName AS name, b.brokerageDesc AS description, b.brokerageContactPhone AS cPhone, 
					b.brokerageSchedulePhone as sPhone, b.brokerageContactEmail AS cEmail,
				  	b.brokerageNotifyPhone AS nPhone, b.brokerageNotifyEmail AS nEmail, 
					b.address, b.city, b.state, b.zipCode, b.url, b.TourWindowType,
					b.brokerageCountry AS country, b.api_key AS api, b.affiliatePhotographerID,
					b.theme_id AS theme, b.salesRepID, b.logo, b.secondary_logo, b.logo_link, b.secondary_logo_link, b.hidden,
					b.flyerLogoBlackOnWhite, b.flyerLogoWhiteOnBlack, b.allowMatterport 
				  	FROM brokerages b
					WHERE b.brokerageID = "' . $id . '"
				  	LIMIT 1
				   ';
				  
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		$result = mysql_fetch_array($r);
		
		$query =   'SELECT id, name FROM THEMES';
		$t = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		
		$query =   'SELECT DISTINCT country FROM nf_locations WHERE LENGTH(country) <= 3 ORDER BY country';
		$c = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		
		$query =   'SELECT stateAbbrName FROM states ORDER BY stateAbbrName';
		$st = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		
		$query =   'SELECT salesRepID AS id, fullName AS name FROM salesreps ORDER BY id';
		$s = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		
		$query =   'SELECT * FROM photographers WHERE isAffiliate = 1 ORDER BY fullName';
		$a = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		
		if(isset($id)&&!empty($id)){
			// GET MLS IDs
			$mlsIDs = $mls->getBrokerageIDs($id);
		}
		
		echo '
		<form enctype="multipart/form-data" action="' . basename($_SERVER['PHP_SELF']) . '" method="post">
		';
		
		if (isset($id)) {
			echo '<input id="id" name="id" type="hidden" value="' . $id . '" />';
		}
		
		$checked = "";
		if($result['hidden']){
			$checked = "checked";
		}
			
		echo '
			<div class="formrow" >
				<div class="row r_name" >Name</div>
				<div class="row r_content" >
					<input id="name" name="name" class="input mid exp" type="text" value="' . $result['name'] . '" /> <input type="checkbox" name="hidden" value="1" '.$checked.' /> Hide from public
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >URL</div>
				<div class="row r_content" >
					<input id="url" name="url" class="input mid exp" type="text" value="' . $result['url'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Description</div>
				<div class="row r_content" >
					<input id="descrip" name="descrip" class="input mid exp" type="text" value="' . $result['description'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Contact Phone</div>
				<div class="row r_content" >
					<input id="cPhone" name="cPhone" class="input mid exp" type="text" value="' . $result['cPhone'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Contact Email</div>
				<div class="row r_content" >
					<input id="cEmail" name="cEmail" class="input mid exp" type="text" value="' . $result['cEmail'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Notify Phone</div>
				<div class="row r_content" >
					<input id="nPhone" name="nPhone" class="input mid exp" type="text" value="' . $result['nPhone'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Notify Email</div>
				<div class="row r_content" >
					<input id="nEmail" name="nEmail" class="input mid exp" type="text" value="' . $result['nEmail'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Schedule Phone</div>
				<div class="row r_content" >
					<input id="sPhone" name="sPhone" class="input mid exp" type="text" value="' . $result['sPhone'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Address</div>
				<div class="row r_content" >
					<input id="address" name="address" class="input mid exp" type="text" value="' . $result['address'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >City</div>
				<div class="row r_content" >
					<input id="city" name="city" class="input mid exp" type="text" value="' . $result['city'] . '" />
				</div>
			</div>
			
			<div class="formrow" >
				<div class="row r_name" >State</div>
				<div class="row r_content" >
					<select id="state" name="state" class="input mid" >
						<option value="--" ';
			if (strlen(trim($result["state"])) > 0) {
				$selected = "";
				$stateValue = $result["state"];
			}
			else
			{
				$selected = " SELECTED";
				$stateValue = "";
			}
			echo $selected.'>Select State</option>
		';
		
		while($state = mysql_fetch_array($st)) {
			
			if (trim($result["state"]) == $state['stateAbbrName']) {
				echo '<option value="' . $state['stateAbbrName'] . '" SELECTED >' . $state['stateAbbrName'] . '</option>	';	
			} else {
				echo '<option value="' . $state['stateAbbrName'] . '" >' . $state['stateAbbrName'] . '</option>	';	
			}
		}
		
		echo '			
					</select>
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Zip Code</div>
				<div class="row r_content" >
					<input id="zipCode" name="zipCode" class="input mid exp" type="text" value="' . $result['zipCode'] . '" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Country</div>
				<div class="row r_content" >
					<select id="country" name="country" class="input mid" >
						<option value="--" >Select Country</option>
		';
		
		while($country = mysql_fetch_array($c)) {
			if ($result['country'] == $country['country']) {
				echo '<option value="' . $country['country'] . '" SELECTED >' . $country['country'] . '</option>	';	
			} else {
				echo '<option value="' . $country['country'] . '" >' . $country['country'] . '</option>	';	
			}
		}
		
		

		$ytID = (count($ytAccs)>0)?'<input type="hidden" name="ytID" value="'.$ytAccs[0]['id'].'" />':'<input type="hidden" name="ytID" value="0" />';
		$ytUsername = (count($ytAccs)>0)?$ytAccs[0]['username']:'';
		$ytPassword = (count($ytAccs)>0)?$ytAccs[0]['password']:'';
		
		echo '			
					</select>
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Key</div>
				<div class="row r_content" >
					' . $result['api'] . '
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >YouTube UN</div>
				<div class="row r_content" >
					'.$ytID.'
					<input id="ytUsername" name="ytUsername" class="input mid exp" type="text" value="'.$ytUsername.'" />
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >YouTube PW</div>
				<div class="row r_content" >
					<input id="ytPassword" name="ytPassword" class="input mid exp" type="text" value="'.$ytPassword.'" />
				</div>
			</div>
			
			<div class="formrow" >
				<div class="row r_name" >Affiliate Photographer</div>
				<div class="row r_content" >
					<select id="affiliatePhotographerID" name="affiliatePhotographerID" class="input mid" > ';
						if ($result["affiliatePhotographerID"] == '0') {
							echo '<option value="0" selected="selected">--</option>';
						} else {
							echo '<option value="0">--</option>'.$result["affiliatePhotographerID"];
						}
						while($affiliatePhotographer = mysql_fetch_array($a)) {
							if ($result['affiliatePhotographerID'] == $affiliatePhotographer['photographerID']) {
								echo '<option value="' . $affiliatePhotographer['photographerID'] . '" SELECTED >' . $affiliatePhotographer['fullName'] . '</option>	';	
							} else {
								echo '<option value="' . $affiliatePhotographer['photographerID'] . '" >' . $affiliatePhotographer['fullName'] . '</option>	';	
							}
						}
		echo '		</select>
				</div>	
			</div>	
			
			<div class="formrow" >
				<div class="row r_name" >Tour Window Type</div>
				<div class="row r_content" >
					<select id="TourWindowType" name="TourWindowType" class="input mid" >';
foreach($tourWindows as $index => $TourWindowType){
					echo	'<option value="'.$TourWindowType.'" ';
					echo ($result["TourWindowType"] == $TourWindowType)?'selected ':' ';
					echo '>'.$TourWindowType.'</option>';
}
$allowMatterPortChecked = ($result['allowMatterport']==1)?'checked="checked"':'';
echo '
					</select>
				</div>	
			</div>
			
			<div class="formrow" >
				<div class="row r_name" >Allow Matterport</div>
				<div class="row r_content" >
					<input type="checkbox" name="allowMatterport" value="1" '.$allowMatterPortChecked.' />
				</div>	
			</div>	
			
			<div class="formrow frtall" >
				<div class="row r_name" >Logo</div>
				<div class="row r_content r_tall" >
		';
		
		if (strlen($result['logo']) > 0) {
			echo '
					<div style="position: relative; float: left; max-height: 95px; max-width: 49%; padding-right: 5px;" >
						<img style="max-height: 95px; max-width: 49%;" src="../images/logos/' . $result['logo'] . '" />
					</div>
					<div style="position: relative; float: left; height: 95px; max-width: 49%;" >
						<span style="white-space:nowrap;">Current: ' . $result['logo'] . '<br /></span>
						<input name="file" type="file" />
					</div>
			';
		} else {
			echo '
					<input name="file" type="file" />
			';
		}
		
		echo '		
				</div>
			</div>
		<div class="formrow" >
			<div class="row r_name" >Logo Link</div>
			<div class="row r_content" >
				<input id="logo_link" name="logo_link" class="input mid exp" type="text" value="' . $result['logo_link'] . '" />
			</div>
		</div>
			
		<div class="formrow frtall" >
				<div class="row r_name" >Secondary Logo</div>
				<div class="row r_content r_tall" >
		';
		
		if (strlen($result['secondary_logo']) > 0) {
			echo '
					<div style="position: relative; float: left; max-height: 95px; max-width: 49%; padding-right: 5px;" >
						<img style="max-height: 95px; max-width: 49%;" src="../images/logos/' . $result['secondary_logo'] . '" />
					</div>
					<div style="position: relative; float: left; height: 95px; max-width: 49%;" >
						<span style="white-space:nowrap;">Current: ' . $result['secondary_logo'] . '<br /></span>
						<input name="file2" type="file" />
					</div>
			';
		} else {
			echo '
					<input name="file2" type="file" />
			';
		}
		
		echo '		
				</div>
			</div>
			
			<div class="formrow" >
				<div class="row r_name" >Secondary Logo Link</div>
				<div class="row r_content" >
					<input id="logo_link" name="secondary_logo_link" class="input mid exp" type="text" value="' . $result['secondary_logo_link'] . '" />
				</div>
			</div>
			
			<div class="formrow frtall" >
				<div class="row r_name" style="height:70px">Flyer Logo Black on White</div>
				<div class="row r_content r_tall" >
		';
		
		if (strlen($result['flyerLogoBlackOnWhite']) > 0) {
			echo '
					<div style="position: relative; float: left; max-height: 95px; max-width: 49%; padding-right: 5px;" >
						<img style="max-height: 95px; max-width: 49%;" src="../images/logos/' . $result['flyerLogoBlackOnWhite'] . '" />
					</div>
					<div style="position: relative; float: left; height: 95px; max-width: 49%;" >
						Current: ' . $result['flyerLogoBlackOnWhite'] . '<br />
						<input name="fileBlackOnWhite" type="file" />
					</div>
			';
		} else {
			echo '
					<input name="fileBlackOnWhite" type="file" />
			';
		}
		
		echo '		
				</div>
			</div>
			
			<div class="formrow frtall" >
				<div class="row r_name" style="height:70px">Flyer Logo White on Black</div>
				<div class="row r_content r_tall" >
		';
		
		if (strlen($result['flyerLogoWhiteOnBlack']) > 0) {
			echo '
					<div style="position: relative; float: left; max-height: 95px; max-width: 49%; padding-right: 5px;" >
						<img style="max-height: 95px; max-width: 49%;" src="../images/logos/' . $result['flyerLogoWhiteOnBlack'] . '" />
					</div>
					<div style="position: relative; float: left; height: 95px; max-width: 49%;" >
						Current: ' . $result['flyerLogoWhiteOnBlack'] . '<br />
						<input name="fileWhiteOnBlack" type="file" />
					</div>
			';
		} else {
			echo '
					<input name="fileWhiteOnBlack" type="file" />
			';
		}
		
		echo '		
				</div>
			</div>
			
			<div class="formrow" >
				<div class="row r_name">Theme</div>
				<div class="row r_content" >
					<select id="theme" name="theme" class="input mid" />
		';
		
		while($theme = mysql_fetch_array($t)) {
			if ($result['theme'] == $theme['id']) {
				echo '<option value="' . $theme['id'] . '" SELECTED >' . $theme['name'] . '</option>	';	
			} else {
				echo '<option value="' . $theme['id'] . '" >' . $theme['name'] . '</option>	';	
			}
		}
		
		echo '	
					</select>
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Sales Rep</div>
				<div class="row r_content" >
					<select id="salesRep" name="salesRep" class="input mid" />
						<option value="-1" >Select Sales Rep</option>
		';
		
		while($salesRep = mysql_fetch_array($s)) {
			if ($result['salesRepID'] == $salesRep['id']) {
				echo '<option value="' . $salesRep['id'] . '" SELECTED >' . $salesRep['name'] . '</option>	';	
			} else {
				echo '<option value="' . $salesRep['id'] . '" >' . $salesRep['name'] . '</option>	';	
			}
		}
		
		echo '	
					</select>
				</div>
			</div>
			';
		
/**********************************************************************************************
BROKERAGE PRICING
**********************************************************************************************/	
/*		echo '
			<div class="formrow invisible" ></div>
			<table>
				<tr>
					<th colspan="4">Brokerage Prices</th>
				</tr>
				<tr>
					<th>Type</th>
					<th>Item</th>
					<th>Price (Standard)</th>
					<th></th>
				</tr>
		';
			
		$query = '
			SELECT
			p.pricingID AS id, p.price, p.itemType, tt.tourTypeName AS tname, pr.productName AS pname, pp.price as stdprice 
			FROM nf_pricing p
			LEFT JOIN tourtypes tt ON p.itemType = "tour" AND p.itemID = tt.tourTypeID
			LEFT JOIN products pr ON p.itemType = "product" AND p.itemID = pr.productID
			LEFT JOIN nf_pricing pp ON pp.category = "standard" AND p.itemType = pp.itemType AND p.itemID = pp.itemID
			WHERE p.category = "broker" 
			AND p.categoryID = "' . $id . '"
			ORDER BY p.itemType, tname, pname
			';
		$pr = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		while ($pricing = mysql_fetch_array($pr)) {
			$name = "--";
			if (strlen($pricing['tname']) > 0) {
				$name = $pricing['tname'];
			} elseif (strlen($pricing['pname']) > 0) {
				$name = $pricing['pname'];
			}
			
			echo '
			<tr>
				<td>' . $pricing['itemType'] . '</td>
				<td>' . $name . '</td>
				<td>$' . $pricing['price'] . ' ($' . $pricing['stdprice'] . ')</td>
				<td><img src="../repository_images/del.png" onclick="ConfirmDelete(' . chr(39) . 'this broker price of ' . $pricing['price'] . chr(39) .  ', ' . chr(39) . basename($_SERVER['PHP_SELF']) . '?op=del_bp&id=' . $id . '&pid=' . $pricing['id'] . chr(39) .');" /></td>
			</tr>
			
			';	
		}
		
		echo '
		</table>
		<div class="formrow" >
			<div class="row r_name" >Category</div>
			<div class="row r_content" >
				<select id="bp_category" name="bp_category" class="input mid" onchange="GetForm(' . chr(39) . 'bp_category' . chr(39) . ', ' . chr(39) . 'bp_items' . chr(39) . ', ' . chr(39) . 'bp_item' . chr(39) . ');" >
					<option value="" >Select Type</option>
					<option value="tour">Tour</option>
					<option value="product">Product</option>							
				</select>
			</div>
		</div>
		<div id="bp_items" >
			<div class="formrow" >
				<div class="row r_name" >Item</div>
				<div class="row r_content" >
					Choose a category
				</div>
			</div>
		</div>
		<div class="formrow" >
			<div class="row r_name" >Price</div>
			<div class="row r_content" >
				<input id="bp_price" name="bp_price" class="input mid exp" type="text" /> (ex. 3.20 for $3.20)
			</div>
		</div>
		<div class="formrow" >
			<div class="row r_name invisible" ></div>
			<div class="row r_content" >
				<input type="submit" name="update" value="update" />
				<a href="' . basename($_SERVER['PHP_SELF']) . '" ><input type="button" value="close" /></a>
			</div>
		</div>
		';*/
		if (isset($id)) {
			echo '<div id="bp_items" >
				<div class="formrow" >
					<div class="row r_name" >Products &amp; Pricing</div>
					<div class="row r_content" >
						[ <a href="javascript:productsWindow()">SELECT</a> ]
					</div>
				</div>
			</div>';
			echo '<div id="bp_items" >
				<div class="formrow" >
					<div class="row r_name" >Tour Types &amp; Pricing</div>
					<div class="row r_content" >
						[ <a href="javascript:tourTypesWindow()">SELECT</a> ]
					</div>
				</div>
			</div>';
			echo '<div id="bp_items" >
				<!-- <div class="formrow" >
					<div class="row r_name" >Membership &amp; Pricing</div>
					<div class="row r_content" >
						[ <a href="javascript:MembershipsWindow()">SELECT</a> ]
					</div>
				</div>
			</div> -->
';
		}
		/*echo '
			<table>
				<tr>
					<th colspan="3">Memberships For All Brokerage Agents (OVERRIDES USER SELECTION)</th>
				</tr>
				<tr>
					<th>Membership</th>
					<th>Status</th>
					<th>Set</th>
				</tr>';
				$query = "SELECT m.id, m.name, case when isNull(b.permission) then 1 else b.permission end as permission
						FROM memberships as m LEFT OUTER JOIN nf_blacklist as b 
						ON m.id = b.itemID AND b.categoryID = '" . $id . "' AND b.itemType = 'Membership'
						AND b.category = 'broker'
						WHERE m.concierge = 0
						ORDER BY m.id";
				$result = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
				while ($memberships = mysql_fetch_array($result)) {
					if ($memberships['permission'] == 1) {
						echo "<tr> <th>";
						echo $memberships['name'];
						echo "</th><th>";
						unset($result2);
						$query2 = "SELECT active FROM members WHERE typeID = '".$memberships['id']."' AND userType = 'broker' AND userID = '".$id."'";
						$result2 = mysql_query($query2);
						$active = mysql_fetch_array($result2);
						
						//echo ("| |".$active['active']);
						if ($active['active'] == 0) {
							echo "Deactivated";
							$btnSet = "Activate";
						}
						else {
							echo "Active";
							$btnSet = "Deactivate";
						}
						echo "</th><th>";
						echo "<div class='button_txt' style='margin-top: 0px;' onclick='SetUserPermissions("
									.$memberships['id'].",".abs(1-$active['active']).",".$id.");'>$btnSet</div>";
						echo "</th></tr>";
					}
				}
			echo '</table>';*/
		
		if ($id > 0) {
			echo '<div id="bp_items" >
				<div class="formrow" >
					<div class="row r_name" style="height: 70px;">MLS Brokerage (Office) IDs</div>
					<div class="row r_content" style="height:auto;">
			';
		
			if (!$new) {
				foreach($mlsIDs as $row => $mlscolumn){
	?>
							<div>
							<input name="mls_id[]" class="input sm exp" type="text" value="<?PHP echo $mlscolumn['mlsID']; ?>" />
							<select name="mls_provider[]" class="input mid">
								<option value="0">Select MLS Provider</option>
	<?PHP
					foreach($MLSProviders as $row => $column){
	?>
								<option value="<?PHP echo $column['id'] ?>" <?PHP echo ($mlscolumn['mlsProvider']==$column['id']?'selected="selected"':''); ?>><?PHP echo $column['name'] ?></option>
	<?PHP
					}
	?>			
							</select>
							<a href="javascript:" onClick="removeMLS(this)">- REMOVE</a>
							</div>		
	<?PHP
				}
			}
			echo'			
						<input name="mls_id[]" class="input sm exp" type="text" value="" />
						<select name="mls_provider[]" class="input mid">
						<option value="0">Select MLS Provider</option>
			';
			foreach($MLSProviders as $row => $column){
	?>
							<option value="<?PHP echo $column['id'] ?>"><?PHP echo $column['name'] ?></option>
	<?PHP
			}
			echo '
						</select>
						<a href="javascript:" onClick="javascript:addMLS(this)">+ ADD</a>
					</div>
				</div>
			</div>';
		}
		echo '
				<div id="mls_html_holder" style="display:none;">
					<div>
					<input name="mls_id[]" class="input sm exp" type="text" value="" />
					<select name="mls_provider[]" class="input mid">
					<option value="0">Select MLS Provider</option>
		';
		foreach($MLSProviders as $row => $column){
?>
						<option value="<?PHP echo $column['id'] ?>"><?PHP echo $column['name'] ?></option>
<?PHP
		}
		echo '
					</select>
					<a href="javascript:" onClick="removeMLS(this)">- REMOVE</a>
					</div>
				</div>
		';
/**********************************************************************************************
BLACK LISTING
**********************************************************************************************/	
		/*echo '
			<div class="formrow invisible" ></div>
			<table>
				<tr>
					<th colspan="4">Brokerage Blacklist</th>
				</tr>
				<tr>
					<th>Type</th>
					<th>Item</th>
					<th>Permission</th>
					<th></th>
				</tr>
		';
			
		$query = '
			SELECT
			bl.blacklistID AS id, bl.itemType, bl.permission,
			tt.tourTypeName AS tname, pr.productName AS pname
			FROM nf_blacklist bl
			LEFT JOIN tourtypes tt ON bl.itemType = "tour" AND bl.itemID = tt.tourTypeID
			LEFT JOIN products pr ON bl.itemType = "product" AND bl.itemID = pr.productID
			WHERE category = "broker" AND categoryID = "' . $id . '"
			ORDER BY bl.itemType, tname, pname
			';
		$bl = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		while ($blacklist = mysql_fetch_array($bl)) {
			$name = "--";
			if (strlen($blacklist['tname'])) {
				$name = $blacklist['tname'];
			} elseif (strlen($blacklist['pname'])) {
				$name = $blacklist['pname'];
			}
			
			$permission = "--";
			if ($blacklist['permission'] == 0) {
				$permission = '<span style="color: red;" >deny</span>';
			} else {
				$permission = '<span style="color: green;" >allow</span>';
			}
			echo '
			<tr>
				<td>' . $blacklist['itemType'] . '</td>
				<td>' . $name . '</td>
				<td>' . $permission . '</td>
				<td><img src="../repository_images/del.png" onclick="ConfirmDelete(' . chr(39) . 'this blacklist' . chr(39) .  ', ' . chr(39) . basename($_SERVER['PHP_SELF']) . '?op=del_bl&id=' . $id . '&blid=' . $blacklist['id'] . chr(39) .');" /></td>
			</tr>
			
			';	
		}
		
		echo '
		</table>
		<div class="formrow" >
			<div class="row r_name" >Category</div>
			<div class="row r_content" >
				<select id="bl_category" name="bl_category" class="input mid" onchange="GetForm(' . chr(39) . 'bl_category' . chr(39) . ', ' . chr(39) . 'bl_items' . chr(39) . ', ' . chr(39) . 'bl_item' . chr(39) . ');" >
					<option value="" >Select Type</option>
					<option value="tour">Tour</option>
					<option value="product">Product</option>							
				</select>
			</div>
		</div>
		<div id="bl_items" >
			<div class="formrow" >
				<div class="row r_name" >Item</div>
				<div class="row r_content" >
					Choose a category
				</div>
			</div>
		</div>
		<div id="bl_items" >
			<div class="formrow" >
				<div class="row r_name" >Permission</div>
				<div class="row r_content" >
					<input type="radio" name="bl_permission" value="1">Allow
					<input type="radio" name="bl_permission" value="0" checked="checked" >Deny
				</div>
			</div>
		</div>
		<div class="formrow" >
			<div class="row r_name invisible" ></div>
			<div class="row r_content" >
				<input type="submit" name="update" value="update" />
				<a href="' . basename($_SERVER['PHP_SELF']) . '" ><input type="button" value="close" /></a>
			</div>
		</div>
		';*/

/**********************************************************************************************
BROKER BILLABLE
**********************************************************************************************/
		if ((isset($_POST['new']) || isset($_GET['new'])) == false) {
			echo '
				<div class="formrow invisible" ></div>
				<table>
					<tr>
						<th colspan="5">Brokerage Billable</th>
					</tr>
					<tr>
						<th>Type</th>
						<th>Item</th>
						<th>Dollar</th>
						<th>Percent</th>
						<th></th>
					</tr>
			';
		
			
			$query = '
				SELECT bb.bbID AS id, bb.itemType, 
					CASE WHEN NOT ISNULL(tt.tourTypeName) THEN tt.tourTypeName 
						WHEN NOT ISNULL(pr.productName) THEN pr.productName
						ELSE m.name END as name,
					bb.dollar, bb.percent
				FROM nf_broker_billing bb
				LEFT JOIN tourtypes tt ON bb.itemType = "tour" AND bb.itemID = tt.tourTypeID
				LEFT JOIN products pr ON bb.itemType = "product" AND bb.itemID = pr.productID
				LEFT JOIN memberships m ON bb.itemType = "concierge" AND bb.itemID = m.id
				WHERE bb.brokerID = '.$id.'
				ORDER BY bb.itemType, name
				';
			$mileage = false;
			$bb = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
			while ($billing = mysql_fetch_array($bb)) {
				
				if ($billing['itemType'] == 'mileage') {
					$mileage = true;
				} else {
					echo '
				<tr>
					<td>' . $billing['itemType'] . '</td>
					<td>' . $billing['name'] . '</td>
					<td>' . $billing['dollar'] . '</td>
					<td>' . ($billing['percent']*100) . '</td>
					<td><img src="../repository_images/del.png" onclick="ConfirmDelete(' . chr(39) . 'this billable' . chr(39) .  ', ' . chr(39) . basename($_SERVER['PHP_SELF']) . '?op=del_bb&id=' . $id . '&bbid=' . $billing['id'] . chr(39) .');" /></td>
				</tr>
					';	
				}
			}

			echo '
			</table>
			<div>
				<div class="formrow" >
					<div class="row r_name" >Mileage</div>
					<div class="row r_content" >
			';
			
			if ($mileage) {
				echo '<input type="checkbox" id="bb_mileage" name="bb_mileage" value="1" checked >';
			} else {
				echo '<input type="checkbox" id="bb_mileage" name="bb_mileage" value="1">';
			}
			
			echo '
						Mileage is Billable
					</div>
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Category</div>
				<div class="row r_content" >
					<select id="bb_category" name="bb_category" class="input mid" onchange="GetForm(' . chr(39) . 'bb_category' . chr(39) . ', ' . chr(39) . 'bb_items' . chr(39) . ', ' . chr(39) . 'bb_item' . chr(39) . ');" >
						<option value="" >Select Type</option>
						<option value="tour">Tour</option>
						<option value="product">Product</option>;
						<option value="concierge">Concierge</option>;
					</select>
				</div>
			</div>
			<div id="bb_items" >
				<div class="formrow" >
					<div class="row r_name" >Item</div>
					<div class="row r_content" >
						Choose a category
					</div>
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Dollar</div>
				<div class="row r_content" >
					<input id="bb_dollar" name="bb_dollar" class="input mid exp" type="text" /> (ex. 3.20 for $3.20)
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name" >Percent</div>
				<div class="row r_content" >
					<input id="bb_percent" name="bb_percent" class="input mid exp" type="text" /> (ex. .67 for 67%)
				</div>
			</div>
			<div class="formrow" >
				<div class="row r_name invisible" ></div>
				<div class="row r_content" >
					Note: Dollar values over-rule percent values.
				</div>
			</div>';
		}
		echo '
		<div class="formrow" >
			<div class="row r_name invisible" ></div>
			<div class="row r_content" >
				<input type="submit" name="update" value="update" />
				<a href="' . basename($_SERVER['PHP_SELF']) . '" ><input type="button" value="close" /></a>
			</div>
		</div>
		';


/**********************************************************************************************
FORM SUBMIT
**********************************************************************************************/
		echo '
		</form>
		';
	} else {
/**********************************************************************************************
MAIN BROKERAGE LIST
**********************************************************************************************/	
		$query = "SELECT b.brokerageID, b.brokerageName, b.brokerageDesc, s.fullName
				  FROM brokerages b
				  LEFT JOIN salesReps s ON b.salesRepID = s.salesRepID 
						  ";	  
		if (strlen($search) > 0) {
			$query .= 'WHERE brokerageID LIKE "%' . $search . '%" ';
			$query .= 'OR brokerageName LIKE "%' . $search . '%" ';
		}
		
		if (strlen($state) > 0) {
			if(strlen($search) > 0){
				$query .= 'AND';
			}else{
				$query .= 'WHERE';
			}
			$query .= ' state = "' . $state . '" ';
		}
		
		$query .= "ORDER BY brokerageName
				  LIMIT " . $index . "," . $max . " 
				 ";
				  
		$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />");
		
		$count = intval(@mysql_num_rows($r));
		
		echo '
			<table>
				<tr>
					<th colspan="10" >
						<form action="' . basename($_SERVER['PHP_SELF']) . '" method="get">
							Search: <input type="text" name="search" value="' . $search . '" />
							State: <select id="state" name="state" class="input mid">
						<option value="">Select State</option>
		<option value="AB">AB</option>	<option value="AK">AK</option>	<option value="AL">AL</option>	<option value="AR">AR</option>	<option value="AZ">AZ</option>	<option value="BC">BC</option>	<option value="CA">CA</option>	<option value="CO">CO</option>	<option value="CT">CT</option>	<option value="DC">DC</option>	<option value="DE">DE</option>	<option value="FL">FL</option>	<option value="GA">GA</option>	<option value="HI">HI</option>	<option value="IA">IA</option>	<option value="ID">ID</option>	<option value="IL">IL</option>	<option value="IN">IN</option>	<option value="KS">KS</option>	<option value="KY">KY</option>	<option value="LA">LA</option>	<option value="MA">MA</option>	<option value="MB">MB</option>	<option value="MD">MD</option>	<option value="ME">ME</option>	<option value="MI">MI</option>	<option value="MN">MN</option>	<option value="MO">MO</option>	<option value="MS">MS</option>	<option value="MT">MT</option>	<option value="NB">NB</option>	<option value="NC">NC</option>	<option value="ND">ND</option>	<option value="NE">NE</option>	<option value="NH">NH</option>	<option value="NJ">NJ</option>	<option value="NL">NL</option>	<option value="NM">NM</option>	<option value="NS">NS</option>	<option value="NT">NT</option>	<option value="NU">NU</option>	<option value="NV">NV</option>	<option value="NY">NY</option>	<option value="OH">OH</option>	<option value="OK">OK</option>	<option value="ON">ON</option>	<option value="OR">OR</option>	<option value="PA">PA</option>	<option value="PE">PE</option>	<option value="QC">QC</option>	<option value="RI">RI</option>	<option value="SC">SC</option>	<option value="SD">SD</option>	<option value="SK">SK</option>	<option value="TN">TN</option>	<option value="TX">TX</option>	<option value="UT">UT</option>	<option value="VA">VA</option>	<option value="VT">VT</option>	<option value="WA">WA</option>	<option value="WI">WI</option>	<option value="WV">WV</option>	<option value="WY">WY</option>	<option value="YT">YT</option>				
					</select>
							<input type="submit" id="submit" name="submit" value="submit" />
						</form>
					</th>
				</tr>
				<tr>
				';
		if ($index >= $max) {
			echo '<th colspan="2" style="text-align: right;" ><a href="' . basename($_SERVER['PHP_SELF']) . '?index=' . ($index - $max) . '&max=' . $max . '&search=' . $search . '&state=' . $state . '" >[PREV]</a></th>';
		} else {
			echo '<th colspan="2" ></th>';
		}
		
		if ($count >= $max) {
			echo '<th colspan="2" style="text-align: left;" ><a href="' . basename($_SERVER['PHP_SELF']) . '?index=' . ($index + $max) . '&max=' . $max . '&search=' . $search . '&state=' . $state . '" >[NEXT]</a></th>';
		} else {
			echo '<th colspan="2" ></th>';
		}
		
		
		echo '
					<th colspan="6" ></th>
				</tr>
			<tr>
					<th><a href="' . basename($_SERVER['PHP_SELF']) . '?new=1" ><img src="../repository_images/new.png" /></a></th>
					<th>&nbsp;</th>
					<th>ID</th>
					<th>Name</th>
					<th>Description</th>
					<th>Sales Rep</th>
					<th></th>
				</tr>
		';
		
		$highlight = true;
		while($result = mysql_fetch_array($r)){
			if ($highlight) {
				$class = "highlight";
			} else {
				$class = "nohighlight";
			}
			$highlight = !$highlight;
			
			echo '
				<tr class="' . $class . '" >
					<td><a href="' . basename($_SERVER['PHP_SELF']) . '?id=' . $result['brokerageID'] . '" ><img src="../repository_images/look.png" /></a></td>
					<td><a href="javascript:duplicateTour(\''.$result['brokerageName'].' - '.$result['brokerageDesc'].'\', '.$result['brokerageID'].')" ><img src="../repository_images/icons/duplicate.png" title="Duplicate Brokerage" style="margin:5px;"/></a></td>
					<td>' . $result['brokerageID'] . '</td>
					<td>' . $result['brokerageName'] . '</td>
					<td>' . $result['brokerageDesc'] . '</td>
					<td>' . $result['fullName'] . '</td>
					<td><img src="../repository_images/del.png" onclick="ConfirmDelete(' . chr(39) . str_replace("'","",$result['brokerageName']) . chr(39) .  ', ' . chr(39) . basename($_SERVER['PHP_SELF']) . '?op=del&id=' . $result['brokerageID'] . chr(39) .');" /></td>
				</tr>
			';		
		}
		echo '
			</table>
		';
	}
	    
?>
    </body>
</html>
