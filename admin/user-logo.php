<?php
/* Author:  William Merfalen
 * Date:	07/17/2015
 * Allow an admin to override a user's brokerage logo
*/
require_once('../repository_inc/classes/inc.global.php');
define("LOGO_DIRECTORY",$_SERVER['DOCUMENT_ROOT'] . '/repository_images/users/');
global $db;
$userId = intval($_GET['userId']);
$randNum = rand(99999999999,999999999999999999999999);

if( isset($_POST['remove']) ){
    unlink(LOGO_DIRECTORY . 'user_' . $userId . '.jpg');
}
if( isset($_FILES['picture']) ){
    if (move_uploaded_file($_FILES['picture']['tmp_name'], LOGO_DIRECTORY . 'user_' . $userId . '.jpg')) {
        echo "Uploaded";
    }
}

?>
<html>
<head>
    <title> User Logo </title>
    <style>
    </style>    

    <script src='/repository_inc/jquery-1.6.2.min.js'></script>
    <script src='/repository_inc/admin.js'></script>
    <script src='/repository_inc/jquery-ui-1.10.0.custom.min.js'></script>
    
    <link rel='stylesheet' type='text/css' href='/repository_css/jquery-ui/dark-hive/jquery-ui-1.8.20.custom.css'/>
    <link rel='stylesheet' type='text/css' href='/repository_css/admin-v2.css'/>
    <link rel='stylesheet' type='text/css' href='/repository_css/template.css'/>
</head>
<body>

<?php
    if( file_exists(LOGO_DIRECTORY . "user_$userId.jpg") ){
?>
    <b>Current Custom Logo:</b>
    <img width=250 src='/repository_images/users/user_<?php echo $userId; ?>.jpg?rand=<?php echo $randNum;?>'>
<hr>
<?php
    }
?>
<form method="post" enctype="multipart/form-data">
<p>Picture:
<input type="file" name="picture" />
<input type="submit" value="Send" />
</p>
<input type='submit' name='remove' value='Remove logo'>
</form>

    </body>
</html>





