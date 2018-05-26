<?php
/*
 * Admin: Memberships (Users)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();
showErrors();

// Create instances of needed objects
$memberships = new memberships();
$members = new members($_REQUEST['id']);
$users = new users();
$brokerages = new brokerages();

// Require admin
$users->authenticateAdmin();

// Load membership
$memberships->loadMembership($_REQUEST['id']);

$members->userType = "broker";

// Enable / Disable Membership
if(isset($_REQUEST['status'])){
	$members->userID = $_REQUEST['userID'];
	if($_REQUEST['status']=="1"){
		if($members->exists()){
			$members->activate();
		}
	}else{
		if($members->exists()){
			$members->deactivate();
		}
	}
}

// Add User
if(isset($_POST['addUser'])){
	if(isset($_POST['userID'])&&!empty($_POST['userID'])){
		foreach($_POST['userID'] as $userIDRow => $userID){
			$members->userID = $userID;
			$members->create();
			$members->activate();
			header("location: brokerages.php?alert=Brokerage Added!&id=".$_REQUEST['id']);
		}
	}else{
		$_REQUEST['error'] = "Please enter a Brokerage ID!";
	}
}

if(isset($_REQUEST['del'])){
	$members->setUser('broker', $_REQUEST['userID']);
	$members->delete();
}

// Create and enable all of the brokerage's users
if(isset($_REQUEST['enusers'])){
	$brokerageUsers = $brokerages->getUsers($_REQUEST['userID']);
	foreach($brokerageUsers as $bkrusridx => $user){
		$members->setUser('user', $user['userID']);
		$members->create();
		$members->activate();
	}
}

// Disable all of the brokerage's users
if(isset($_REQUEST['disusers'])){
	$brokerageUsers = $brokerages->getUsers($_REQUEST['userID']);
	foreach($brokerageUsers as $bkrusridx => $user){
		$members->setUser('user', $user['userID']);
		if($members->exists()){
			$members->deactivate();
		}
	}
}

// Move user to new membership
if(isset($_REQUEST['moveUser'])){
	$newMembershipID = $_REQUEST['moveToID'];
	$membershipID = $_REQUEST['id'];
	$userID = $_REQUEST['userID'];
	$members->setUser('broker', $userID);
	$members->moveMember($newMembershipID);
}

$members->userType = "broker";

// Get brokerage list
$brokerageList = $brokerages->listAll();
// Get users
$userList = $members->listAll();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Membership Brokerages</title>
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
	.select-user option{
		padding:3px;
	}
</style>
<script type="text/javascript" charset="utf-8">
    $(window).load(function() {
		$('.on_off :checkbox').iphoneStyle();
		$('.on_off :checkbox').change(function(){
			var params = 'type=<?PHP echo intval($_REQUEST['id']); ?>&userID='+$(this).attr('user_id')+'&userType=broker';
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
<h1><?PHP echo $memberships->name ?> Brokerage Members</h1>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left">
			<div class="button_new button_blue button_mid" onclick="window.location='index.php'">
				<div class="curve curve_left" ></div>
				<span class="button_caption" ><< Memberships</span>
				<div class="curve curve_right" ></div>
			</div>
		</td>
	</tr>
    <tr>
		<td align="right" valign="bottom">
			<br/><br/>
            <form action="brokerages.php" method="POST" name="addUser">
				<input type="hidden" name="id" value="<?PHP echo $_REQUEST['id']; ?>" />
				<input type="hidden" name="addUser" value="1" />
				<select name="userID[]" multiple size="6" style="width:80%" class="select-user">
					<option value="">Please select a brokerage</option>
<?PHP
	foreach($brokerageList as $row => $columns){
?>
					<option value="<?PHP echo $columns['brokerageID'] ?>"><?PHP echo (!is_null($columns['brokerageDesc']))?$columns['brokerageName'].' - '.$columns['brokerageDesc']:$columns['brokerageName'] ?></option>
<?PHP
	}
?>
                </select>&nbsp;&nbsp;
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
			<th>Brokerage ID</th>
			<th>Brokerage Name</th>
			<th>Create Date</th>
			<th>Trial</th>
			<th>Status</th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($userList as $row => $column){
		unset($brokerages->brokerageName);
		unset($brokerages->brokerageDesc);
		// Load brokerage info
		$brokerages->loadBrokerage($column['userID']);
		unset($createDate);
		unset($trial_attr);
		unset($phpdate);
		// Is trial?
		$trial_attr = ($column['trial']=="1")?'checked="checked" id="on_off_on"':'id="on_off"';
		// Create Date
		$phpdate = strtotime($column['createDate']);
		$createDate = date('n/j/Y', $phpdate);
		if($brokerages->brokerageIDExist(intval($column['userID']))){
?>
		<tr id="user_<?PHP echo $column['userID'] ?>">
			<td><?PHP echo $column['userID'] ?></td>
			<td><?PHP echo $brokerages->brokerageName; echo (!empty($brokerages->brokerageDesc)&&!is_null($brokerages->brokerageDesc))?' - '.$brokerages->brokerageDesc:''; ?></td>
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
?>			</td>
			<td class="list-button">
				<a href="?id=<?PHP echo $_REQUEST['id']; ?>&userID=<?PHP echo $column['userID']; ?>&enusers=1&alert=Brokerage Users Enabled!">Enable*</a>
			</td>
			<td class="list-button">
				<a href="?id=<?PHP echo $_REQUEST['id']; ?>&userID=<?PHP echo $column['userID']; ?>&disusers=1&alert=Brokerage Users Disabled!">Disable*</a>
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
?>			</td>
			<td class="list-button">
				<a href="?id=<?PHP echo $_REQUEST['id']; ?>&userID=<?PHP echo $column['userID']; ?>&del=1&alert=User Membership Deleted!">Delete</a>
			</td>
			<td class="list-button">
				<a href="javascript:moveUserSelect(<?PHP echo $column['userID']; ?>);">Move</a>
			</td>
		</tr>
<?PHP
		}
		unset($column);
	}
?>
	</tbody>
</table>
<script>
	loadListEffects();
	membershipID = <?PHP echo $_REQUEST['id']; ?>;
</script>
<div class="modal-bg"></div>
<div class="modal">
  <div class="content"> </div>
</div>
</body>
</html>