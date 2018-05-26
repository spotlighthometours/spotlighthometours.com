<?php

/*
 * Show how many users there are in Colorado. Also show a list of brokerages with number of users in 
 * Colorado as well as number of tours ordered.
 */

require_once('../repository_inc/classes/inc.global.php');

$count = $db->select('users', "state='CO'","","COUNT(*)");
$count = $count[0]['COUNT(*)'];

$sql = 'SELECT DISTINCT br.brokerageName, br.brokerageDesc,
(SELECT COUNT(userID) FROM users WHERE brokerageID=br.brokerageID AND state=\'CO\') AS user_count
FROM brokerages br, users us
WHERE br.brokerageID = us.brokerageID AND us.state=\'CO\'
ORDER BY user_count DESC';

$brokerages = $db->run($sql);

?>
<h1>Current # of users in Colorado: <?PHP echo $count; ?></h1>
<h1>Brokerages</h1>
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><h2>Brokerage Name</h2></td>
    <td><h2># of users</h2></td>
  </tr>
<?PHP
    foreach($brokerages as $row => $column){
?>
  <tr>
    <td><?PHP echo $column['brokerageName'] ?> -<?PHP echo $column['brokerageDesc'] ?></td>
    <td><?PHP echo $column['user_count'] ?></td>
  </tr>
<?PHP
    }
?>
</table>