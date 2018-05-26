<?php
    session_start();
    require('../repository_inc/classes/inc.global.php');
    $pid = $_REQUEST['pid'];
    $_SESSION['photographerID'] = intval($_REQUEST['pid']);
    $res = $db->select("photographers","photographerID=" . $_REQUEST['pid']);
    $_SESSION['photographerName'] = $res[0]['fullName'];
    $_SESSION['photographerEMail'] = $res[0]['email'];
    $_SESSION['photographerPassword'] = $res[0]['password'];;
    $_SESSION['photographerPhone'] = $res[0]['phone'];
    header("Location: /users/new/affiliatePhotographer.php");
?>
