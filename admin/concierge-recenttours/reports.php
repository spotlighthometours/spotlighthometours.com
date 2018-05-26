<?php
/*
 * Admin: Concierge Reports
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');

clearCache();

// Create instances of needed objects
$memberships = new memberships();
$users = new users($db);
$processwatcher = new processwatcher();
$memberships = new memberships();
$brokerages = new brokerages();

// Require admin
$users->authenticateAdmin();

$mlsProviderFeeds = array(
	10 => "GLVAR",
	5 => "WFRMLS",
	4 => "PCMLS",
	14 => "CTMLS",
	28 => "Louisiana MLS"
);

$conciergeLastRunTime = $processwatcher->getLastProcessTime();
$conciergeLastRunTime = strtotime($conciergeLastRunTime);
$conciergeLastRunTime = date("m/d/Y H:i:s", $conciergeLastRunTime);

// Load Auto Build Tours Membership
$memberships->loadMembership(21);
$members = new members(21);
$members->userType = "broker";
$autoBuildToursBrokerList = $members->listAll();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Concierge Reports</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Concierge Report(s)</h1>
<h2>Active MLS Feeds</h2>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th align="center">ID</th>
			<th>Name</th>
			<th align="center">Last Updated</th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($mlsProviderFeeds as $id => $name){
?>
		<tr id="mlsprovider_<?PHP echo $id ?>">
			<td><?PHP echo $id ?></td>
			<td><?PHP echo $name ?></td>
			<td><?PHP echo $conciergeLastRunTime ?></td>
		</tr>
<?PHP
	}
?>
	</tbody>
</table>
<h2>Active Brokerages/Offices with Concierge Auto Build Tours Membership</h2>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th>Brokerage Name</th>
			<th>MLS ID</th>
            <th>MLS Provider</th>
            <th># Built</th>
            <th># in feed</th>
            <th>Time Built</th>
		</tr>
	</thead>
	<tbody>
<?PHP
	foreach($autoBuildToursBrokerList as $row => $column){
		unset($brokerages->brokerageName);
		unset($brokerages->brokerageDesc);
		// Load brokerage info
		$brokerages->loadBrokerage($column['userID']);
		unset($createDate);
		unset($trial_attr);
		unset($phpdate);
		if($brokerages->brokerageIDExist(intval($column['userID']))&&intval($column['active'])==1){
			$mls = new mls();
			$concierge = new concierge();
			$lastModTime = $concierge->getBrokerTourLastModTime($column['userID']);
			if($lastModTime===false){
				$lastModTime = '';
			}else{
				$lastModTime = strtotime($lastModTime);
				$lastModTime = date("F j, Y, g:i a", $lastModTime);
			}
			$numToursBuilt = $concierge->countBrokerConciergeTours($column['userID']);
			$mlsID = $mls->getBrokerageIDs($column['userID']);
			$officeID = $mlsID[0]['mlsID'];
			$mlsProviderID = $mlsID[0]['mlsProvider'];
			$mlsProvider = $mls->providerFactory($mlsProviderID);
			$numPropInFeed = $mlsProvider->countOfficeProperties($officeID);
?>
		<tr id="brokerage_<?PHP echo $column['userID'] ?>">
			<td><?PHP echo $brokerages->brokerageName; echo (!empty($brokerages->brokerageDesc)&&!is_null($brokerages->brokerageDesc))?' - '.$brokerages->brokerageDesc:''; ?></td>
            <td><?PHP echo $officeID ?></td>
			<td><?PHP echo $mlsProviderFeeds[$mlsProviderID] ?></td>
            <td><a href="list.cfm?brokerageID=<?PHP echo $column['userID'] ?>" target="_blank"><?PHP echo $numToursBuilt ?></a></td>
            <td><a href="reports-properties.php?mlsProvider=<?PHP echo $mlsProviderID ?>&officeID=<?PHP echo $officeID ?>" target="_blank"><?PHP echo $numPropInFeed ?></a></td>
			<td><?PHP echo $lastModTime ?></td>
        </tr>
<?PHP
		}
		unset($column);
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