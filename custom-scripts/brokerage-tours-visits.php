<?php

/*
 * Show number of tours and views for brokerage
 */

require_once('../repository_inc/classes/inc.global.php');
showErrors();
$brokerageIDs = array(
	9,
	59,
	60,
	62,
	64,
	66,
	67,
	68,
	80,
	83,
	267,
	469,
	508
);
$brokerages = new brokerages();
?>
<div style="padding:20px;font-size:14px;font-family:Cambria, 'Hoefler Text', 'Liberation Serif', Times, 'Times New Roman', 'serif';color:darkslategray">
<h1>Brokerage Monthly Tours and/or Views</h1>
<h2>June</h2>
<table cellpadding="10">
	<thead>
		<td><strong>Brokerage</strong></td>
		<td style="text-align:center;"><strong># Tours</strong></td>
		<td style="text-align:center;"><strong># Views</strong></td>
	</thead>
<?PHP
foreach($brokerageIDs as $bidx => $brokerageID){
	$brokerages->loadBrokerage($brokerageID);
	$numberOfTours = $db->run("SELECT COUNT(t.tourID) FROM tours t, users u WHERE t.userID = u.userID AND u.brokerageID='".$brokerageID."' AND t.createdOn>'2017-05-30 10:20:17' AND t.createdOn<'2017-07-01'");
	$numberOfTours = $numberOfTours[0]['COUNT(t.tourID)'];
	$numberOfVisits = $db->run("SELECT SUM(c.counter) FROM tours t, users u, tourstats_visitors c WHERE t.userID = u.userID AND u.brokerageID='".$brokerageID."' AND c.tourID = t.tourID AND c.type = 'lifetime' AND t.createdOn>'2017-05-30 10:20:17' AND t.createdOn<'2017-07-01'");
	$numberOfVisits = $numberOfVisits[0]['SUM(c.counter)'];
	$brokerageName = $brokerages->brokerageName.' <strong>'.$brokerages->brokerageDesc.'</strong>';
?>
	<tr>
		<td><?PHP echo $brokerageName; ?></td>
		<td style="text-align:center;"><?PHP echo $numberOfTours; ?></td>
		<td style="text-align:center;"><?PHP echo $numberOfVisits; ?></td>
	</tr>
<?PHP	
}
?>
</table>


<h2>July</h2>
<table cellpadding="10">
	<thead>
		<td><strong>Brokerage</strong></td>
		<td style="text-align:center;" ><strong># Tours</strong></td>
		<td style="text-align:center;"><strong># Views</strong></td>
	</thead>
<?PHP
foreach($brokerageIDs as $bidx => $brokerageID){
	$brokerages->loadBrokerage($brokerageID);
	$numberOfTours = $db->run("SELECT COUNT(t.tourID) FROM tours t, users u WHERE t.userID = u.userID AND u.brokerageID='".$brokerageID."' AND t.createdOn>'2017-06-30 10:20:17' AND t.createdOn<'2017-08-01'");
	$numberOfTours = $numberOfTours[0]['COUNT(t.tourID)'];
	$numberOfVisits = $db->run("SELECT SUM(c.counter) FROM tours t, users u, tourstats_visitors c WHERE t.userID = u.userID AND u.brokerageID='".$brokerageID."' AND c.tourID = t.tourID AND c.type = 'lifetime' AND t.createdOn>'2017-06-30 10:20:17' AND t.createdOn<'2017-08-01'");
	$numberOfVisits = $numberOfVisits[0]['SUM(c.counter)'];
	$brokerageName = $brokerages->brokerageName.' <strong>'.$brokerages->brokerageDesc.'</strong>';
?>
	<tr>
		<td><?PHP echo $brokerageName; ?></td>
		<td style="text-align:center;"><?PHP echo $numberOfTours; ?></td>
		<td style="text-align:center;"><?PHP echo $numberOfVisits; ?></td>
	</tr>
<?PHP	
}
?>
</table>
</div>