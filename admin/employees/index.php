<?php
/*
 * Author: William merfalen ( william @ spotlighthometours . com )
 * Date: 9/5/2014
 * Purpose: Manage employee lists
 * Admin->Employees
 */

require_once('../../repository_inc/classes/inc.global.php');

error_reporting(-1);
ini_set('display_errors',1);

global $db;
/*
 * The most ghetto code you'll ever see
$db->run("DROP TABLE employees");
$db->run("CREATE TABLE employees( 
	id INT AUTO_INCREMENT NOT NULL,
	first varchar(16) NOT NULL,
	last varchar(16) NOT NULL,
	email varchar(32) NOT NULL,
	PRIMARY KEY(id)
)
");*/

// Include appplication's global configuration
//$this->notifications = new notifications();
//$this->notifications->trace = $this->trace;
//$this->notifications->trace("Trace message for debugging");

showErrors();
clearCache();

$users = new users();
// Require admin
$users->authenticateAdmin();


  //==================================================================
// Handle new creation of employee
//==================================================================
if( isset($_POST['submit']) ){
	//echo "hello";exit;
	global $db;
	/*$db->run("INSERT INTO microsite_video (user_id,video_name,video_upload,theme) VALUES('" . preg_replace('|[^a-zA-Z]*|','',$_POST['employee_first']) . "'," . 
		"'" . preg_replace('|[^a-zA-Z]*|','',$_POST['video']) . "'," .
		"'" . preg_replace('|[^a-zA-Z_@\-\.]*|','',$_POST['video_upload']) . "'," . 
		"'" . $_POST['theme']."')" 
	);*/
	$employee_first =  $_POST['employee_first'];
	$employee_last =  $_POST['employee_last'];
	$name = $employee_first.' '.$employee_last;
	$email =  $_POST['email'];
	$type =  $_POST['type'][0];
	$password = '11111';
	
	$db->run("INSERT INTO administrators(fullName,email,type,password) VALUES('{$name}', '{$email}', '{$type}','{$password}')");
		
	$alert = "New employee addeds";
}



if( isset($_GET['delete']) ){
	global $db;
	$db->run("DELETE FROM administrators WHERE administratorID=" . intval($_GET['delete']));
	$alert = "Employee deleted";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Photographer Upload Sessions</title>
<link rel="stylesheet" href="jquery-ui.css">
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script>
$(document).ready(function(){
	$("a[class='delete']").bind("click",function(e){
		if( !confirm("Are you sure you want to delete this?") ){
			e.preventDefault();
		}
	});
});
</script>
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css"; 

#form_wrapper { 
	width: 600px;
	padding: 10px;
	border: 1px solid black;
	background-color: #f8f8f8;
}
#main_table {
	padding-left: 10px;
	padding-right: 10px;
	padding-top: 20px;
	padding-bottom: 20px;
}
#main_table td {
	padding: 5px 10px 5px 10px;
	border: 1px solid black;
}
#main_table thead tr td {
	background-color: #C3D9FF;
}
.fixit_title{
	float:left;
	padding-left: 2px;
	padding-right: 10px;
}
.highlight { 
	background-color: #E8EEF7;
}
.nohighlight {
	background-color: #FFFFFF;
}
#saved {
	color: green;
	font-size: 12pt;
}
#dialog {
	display: none;
}
</style>
</head>
<body>
<div>
<h1>Manage Employees</h1>
</div>
<div id=saved>&nbsp;</div>
<?php
	if( isset($error) && strlen($error) ){
		echo '<div class="errors">';
		echo $error;
		echo '</div>';
	}
?>

<?PHP
	if(isset($alert) && strlen($alert) ){
		echo '<div class="alert">';
		echo $alert;
		echo '</div>';
	}

	//Grab all employees
	$employees = array("Lisa","Bret","Brianna","Amy","Ishma");
?>

<div id='form_wrapper'>
	<h4>Create a new employee</h4><hr>
	<form action='' method=POST>
		<label class='label' for=employee_first>
			<b>First Name:   </b>
		</label>
		<input type='text' name='employee_first'>
<br>
		<label class='label' for=employee_last>
			<b>Last Name</b>
		</label>
		<input type='text' name='employee_last'>
<br>
		<label class='label' for=employee_last>
			<b>Email</b>
		</label>
		<input type='text' name='email' style='width:200px;height: 20px;'>
<br>
		<label class='label' for='type'>
			<b>Type</b><br>
		</label>
		
		Editor: <input type='radio' name='type[]' value='editor'> 
		Employee: <input type='radio' name='type[]' value='employee'>
<br>
		<input type='submit' value='Create' name='submit' style='width:200px;height: 40px;float: right;'>		
	</form>

<!--	<div style='clear:both;'></div> -->
</div>

<div id='results_wrapper'>
	<div id='table_wrapper'>
		<table id='main_table'>
			<thead>
				<tr>
					<td>User ID</td>
					<td>Name</td>
					<td>Email</td>
					<td>Type</td>
					<td>Action</td>
				</tr>
			</thead>
			<tbody>
		<?php
			$results = $db->select("administrators","1=1");
			$ctr=0;
			foreach($results as $index => $item){
				echo '<tr';
				$class = ($ctr++ % 2 == 0 ? "highlight" : "nohighlight");
				echo " class='$class'>";
				echo '<td>' . $item['administratorID'] . '</td>';
				//echo '<td>' . $item['first'] . '</td>';
				echo '<td>' . $item['fullName'] . '</td>';
				echo '<td><a href="mailto:' . $item['email'] . '">' . $item['email'] . '</a></td>';
				echo '<td>' . $item['type'] . '</td>';
				echo '<td><a class=delete href="?delete=' . $item['administratorID'] . '">Delete</a></td>';
				echo '</tr>';
			}
		?>
			</tbody>
		</table>
	</div><!-- end table wrapper -->
</div>


<div id="dialog" title="Reply">
<form>
	<h3>Submit a reply</h3>
	<textarea id=updateFixNotes></textarea>
	<input type=hidden id="fixIndex">
	
	<input type='button' id='dialogBtnSubmit' value='Save'>
</form>
</div>

</body>
</html>