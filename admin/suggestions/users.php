<?php
/*
 * Admin: Packages (Users)
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$packages = new packages();
$users = new users($db);

// Require admin
$users->authenticateAdmin();

// Load package
$packages->loadPackage($_REQUEST['id'], false);

// Enable / Disable User Credits
if(isset($_REQUEST['status'])){
	if($_REQUEST['status']=="1"){
		$packages->enableUserCredits($_REQUEST['userID'], $_REQUEST['id']);
	}else{
		$packages->disableUserCredits($_REQUEST['userID'], $_REQUEST['id']);
	}
}

// Add User
if(isset($_POST['addUser'])){
	if(isset($_POST['userID'])&&!empty($_POST['userID'])){
		if($users->userIDExist(intval($_POST['userID']))){
			$packages->giveCredits(intval($_POST['userID']));
			header("location: users.php?alert=User Added!&id=".$_REQUEST['id']);
		}else{
			$_REQUEST['error'] = "User ID does not exist!";
		}
	}else{
		$_REQUEST['error'] = "Please enter a User ID!";
	}
}

// Get users
$userList = $packages->getUsers();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Packages</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../../repository_inc/admin-packages.js" type="text/javascript"></script><!-- Admin Package JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1><?PHP echo $packages->name ?> Users</h1>
<div align="right">
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
</div>
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
			<th>ID</th>
			<th>Username</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($userList as $row => $column){
		// Load user info
		$users->loadUser($column['userID']);
?>
		<tr id="user_<?PHP echo $column['userID'] ?>">
			<td><?PHP echo $column['userID'] ?></td>
			<td><?PHP echo $users->username ?></td>
			<td><?PHP echo $users->firstName ?></td>
			<td><?PHP echo $users->lastName ?></td>
			<td class="list-button">
<?PHP
	if($column['active']=="0"){
?>
				<a href="?id=<?PHP echo $_REQUEST['id']; ?>&userID=<?PHP echo $column['userID']; ?>&status=1&alert=User Package Credits Enabled!">Enable</a>
<?PHP
	}else{
?>
				<a href="?id=<?PHP echo $_REQUEST['id']; ?>&userID=<?PHP echo $column['userID']; ?>&status=0&alert=User Package Credits Disabled!">Disable</a>
<?PHP
	}
?>
			</td>
		</tr>
<?PHP
	}
?>
	</tbody>
</table>
<script>
	loadListEffects()
</script>
<?PHP
	include('../../repository_inc/html/modal.html');
?>
</body>
</html>