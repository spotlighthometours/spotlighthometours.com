<?php
/*
 * Admin: Duplicate Tours
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed objects
$users = new users($db);

// Require admin
$users->authenticateAdmin();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Run Concierge</title>
<script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"/>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script>
function runConcierge(){
	$(".progress").css('display','block');
	$(".concRunner").css('display','block');
	$(".concRunner").attr('src', 'http://52.26.18.149/scheduled_tasks/concierge.php');
}
</script>
<style>
	.concRunner{
		display:none;
	}
</style>
</head>
<body style="padding:20px;">
<div class="well" style="width:800px;margin:auto">
	<div class="jumbotron">
		<div align="center">
		<h1>Process Concierge</h1>
		<p>Simply hit the button bellow to start concierge!</p>
		<p><a class="btn btn-primary btn-lg" onclick="runConcierge()" href="#" role="button"><span class="glyphicon glyphicon-off"></span> Process Concierge</a> </p>
		<p>&nbsp;</p>
		<p><div class="progress" style="display:none;"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width:100%"><span class="sr-only">Processing Concierge</span></div></div></p>
		<p><iframe class="concRunner" src="" width="100%" height="400px" scrolling="auto"></iframe></p>
		</div>
	</div>
</div>
</div>
</body>
</html>