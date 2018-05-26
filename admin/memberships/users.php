<?php
/*
 * Admin: Memberships (Users)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
$errorHandlerCalled = true;
showErrors();
clearCache();

// Create instances of needed objects
$memberships = new memberships();
$members = new members($_REQUEST['id']);
$users = new users();
$brokerages = new brokerages();

// Require admin
$users->authenticateAdmin();

// Load membership
$memberships->loadMembership($_REQUEST['id']);

// Check if this is Concierge Social Membership
$socialmarketing = new socialmarketing();
$conciergeSocial = false;
if(intval(trim($socialmarketing->membershipID))==intval(trim($_REQUEST['id']))){
	// This is Concierge Social!
	$conciergeSocial = true;
}

// Enable / Disable Membership
if(isset($_REQUEST['status'])){
	if($_REQUEST['status']=="1"){
		$members->setUser('user', $_REQUEST['userID']);
		
		$members->activate();
	}else{
		$members->setUser('user', $_REQUEST['userID']);
		
		$members->deactivate();
		
	}
}

if(isset($_REQUEST['del'])){
	$members->setUser('user', $_REQUEST['userID']);
	$members->delete();
}

if(isset($_REQUEST['headerRemove'])){
	$socialcontentheader = new socialcontentheader();
	$socialcontentheader->removeHeader($_REQUEST['userID']);
}


// Add User
if(isset($_POST['addUser'])){
	if(isset($_POST['userID'])&&!empty($_POST['userID'])){
		if($users->userIDExist(intval($_POST['userID']))){
			$members->setUser('user', $_REQUEST['userID']);
			if($members->exists()){
				$_REQUEST['error'] = "Member already exists!";
			}else{
				$members->create();
				$members->activate();
				header("location: users.php?alert=User Added!&id=".$_REQUEST['id']);	
			}
		}else{
			$_REQUEST['error'] = "User ID does not exist!";
		}
	}else{
		$_REQUEST['error'] = "Please enter a User ID!";
	}
}

// Move user to new membership
if(isset($_REQUEST['moveUser'])){
	$newMembershipID = $_REQUEST['moveToID'];
	$membershipID = $_REQUEST['id'];
	$userID = $_REQUEST['userID'];
	$members->setUser('user', $userID);
	$members->moveMember($newMembershipID);
}

// Get users
$members->userType = 'user';
$userList = $members->listAll();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Membership Users</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-memberships.js" type="text/javascript"></script><!-- Admin Memberships JS file -->
<script src="../../repository_inc/iphone-controls/jquery/jquery-icheckbox.js" type="text/javascript"></script><!-- Iphone Controls JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
	@import "../../repository_inc/iphone-controls/style.css"; /*Iphone Controls Styles*/
</style>
<script type="text/javascript" charset="utf-8">
    $(window).load(function() {
		$('.on_off :checkbox').iphoneStyle();
		$('.on_off :checkbox').change(function(){
			var params = 'type=<?PHP echo intval($_REQUEST['id']); ?>&userID='+$(this).attr('user_id');
			if($(this).attr('checked')){
				var proceed = confirm("Turning this member's membership into a trial membership will reset their membership create date to today.");
				if(proceed){
					params += '&trial=1';
				}else{
					$(this).click();
					return false;
				}
			}else{
				params += '&trial=0';
			}
			url = '../../repository_queries/admin_member_set_trial.php';
			ajaxQuery(url, params, 'nothing');	
		});
	});
</script>
</head>
<body>
<h1><?PHP echo $memberships->name ?> Members</h1>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<div class="button_new button_blue button_mid" onclick="window.location='index.php'">
				<div class="curve curve_left" ></div>
				<span class="button_caption" ><< Memberships</span>
				<div class="curve curve_right" ></div>
			</div>
		</td>
		<td align="right">
			<form action="users.php" method="POST" name="addUser">
				<input type="hidden" name="id" value="<?PHP echo $_REQUEST['id']; ?>" />
				<input type="hidden" name="addUser" value="1" />
				<strong>User ID:</strong> <input type="text" name="userID"/>&nbsp;&nbsp;
				<div class="button_new button_blue button_mid right" onclick="document.forms['addUser'].submit();">
					<div class="curve curve_left" ></div>
					<span class="button_caption" >Add</span>
					<div class="curve curve_right" ></div>
				</div>
			</form>
		</td>
	</tr>
</table>
<?PHP
	if(isset($_REQUEST['error'])){
?>
<div class="errors"><?PHP echo $_REQUEST['error'] ?></div>
<?PHP
	}
?>
<?PHP
	if(isset($_REQUEST['alert'])){
?>
<div class="alert"><?PHP echo $_REQUEST['alert'] ?></div>
<?PHP
	}
?>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th>Member ID</th>
			<th>User ID</th>
			<th>Username</th>
			<th>Brokerage</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Create Date</th>
			<th>Trial</th>
			<th>Status</th>
			<th></th>
			<th></th>
			<th></th>
		<?PHP echo ($conciergeSocial)?'<th></th>':''; ?>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($userList as $row => $column){
		if(!empty($column['userID'])){
			if($conciergeSocial){
				// Check if the user has completed their setup!
				$conciergeSocialSetupComplete = $socialmarketing->setupComplete($column['userID']);
			}
			unset($users->username);
			unset($users->firstName);
			unset($users->lastName);
			unset($createDate);
			unset($trial_attr);
			unset($phpdate);
			// Load user info
			$users->loadUser($column['userID']);
			unset($brokerages->brokerageName);
			unset($brokerages->brokerageDesc);
			// Load brokerage info
			$brokerages->loadBrokerage($users->BrokerageID);
			// Is trial?
			$trial_attr = ($column['trial']=="1")?'checked="checked" id="on_off_on"':'id="on_off"';
			// Create Date
			$phpdate = strtotime($column['createDate']);
			$createDate = date('n/j/Y', $phpdate);
?>
		<tr id="user_<?PHP echo $column['userID'] ?>" <?PHP if($conciergeSocial&&intval($column['active'])==1){ echo ($conciergeSocialSetupComplete)?'style="background-color:#b7fcac !important;"':'style="background-color:#fcacac !important;"'; }?>>
			<td><?PHP echo $column['id'] ?></td>
			<td><a href="http://www.spotlighthometours.com/admin/users/users.cfm?pg=editUser&user=<?PHP echo $column['userID'] ?>" target="_blank"><?PHP echo $column['userID'] ?></a></td>
			<td><?PHP echo $users->username ?></td>
			<td><?PHP echo $brokerages->brokerageName; echo (!empty($brokerages->brokerageDesc)&&!is_null($brokerages->brokerageDesc))?' - '.$brokerages->brokerageDesc:''; ?></td>
			<td><?PHP echo $users->firstName ?></td>
			<td><?PHP echo $users->lastName ?></td>
			<td><?PHP echo $createDate ?></td>
			<td class="on_off"><input user_id="<?PHP echo $column['userID'] ?>" type="checkbox" <?PHP echo $trial_attr ?>></td>
			<td>
<?PHP
			if(intval($column['active'])==0){
?>
				<strong>Inactive</strong>
<?PHP
			}else{
?>
				<strong>Active</strong>
<?PHP
			}
?>
			</td>
			<td class="list-button">
<?PHP
			if(intval($column['active'])==0){
?>
				<a href="?id=<?PHP echo $_REQUEST['id']; ?>&userID=<?PHP echo $column['userID']; ?>&status=1&alert=User Membership Enabled!">Enable</a>
<?PHP
			}else{
?>
				<a href="?id=<?PHP echo $_REQUEST['id']; ?>&userID=<?PHP echo $column['userID']; ?>&status=0&alert=User Membership Disabled!">Disable</a>
<?PHP
			}
?>
			</td>
			<td class="list-button">
				<a href="?id=<?PHP echo $_REQUEST['id']; ?>&userID=<?PHP echo $column['userID']; ?>&del=1&alert=User Membership Deleted!">Delete</a>
			</td>
			<td class="list-button">
				<a href="javascript:moveUserSelect(<?PHP echo $column['userID']; ?>);">Move</a>
			</td>
			<?PHP 
			$socialcontentheader = new socialcontentheader();
			$socialcontentheader = $socialcontentheader->user($column['userID'])->get();
			if($socialcontentheader->exists()){
				//$headerText = 'Header Saved';
				echo ($conciergeSocial)?'<td class="list-button" style="white-space:nowrap;"><a href="javascript:window.location=\'http://www.spotlighthometours.com/admin/social-hub/import-header.php?userID='.$column['userID'].'\'">Header Saved</a></td>':'';
				echo '<td class="list-button" style="white-space:nowrap;"><a href="?id='.$_REQUEST['id'].'&userID='.$column['userID'].'&headerRemove=1&alert=Header removed!">Remove header</a></td>';
			}else{
				//$headerText = 'Custom Header';
				echo ($conciergeSocial)?'<td class="list-button" style="white-space:nowrap;"><a href="javascript:window.location=\'http://www.spotlighthometours.com/admin/social-hub/import-header.php?userID='.$column['userID'].'\'">Custom Header</a></td>':'';
			}
			//echo ($conciergeSocial)?'<td class="list-button" style="white-space:nowrap;"><a href="javascript:window.location=\'http://www.spotlighthometours.com/admin/social-hub/import-header.php?userID='.$column['userID'].'\'">'.$headerText.'</a></td>':''; 
			?>

		</tr>
<?PHP
		}
		unset($column);
	}
?>
	</tbody>
</table>
<script>
<?PHP
	if(!$conciergeSocial){
?>
	loadListEffects();
<?PHP
	}
?>
	membershipID = <?PHP echo $_REQUEST['id']; ?>;
</script>
<div class="modal-bg"></div>
<div class="modal">
  <div class="content"> </div>
</div>
</body>
</html>