<?php
    require '../repository_inc/classes/inc.global.php';
    global $db;
    $a = $db->select("administrators");
    $b = $db->select("navbar");
    foreach($a as $aindex => $admin){
        foreach($b as $bindex => $nav){
            $db->insert("navbar_permissions",['adminId' => $admin['administratorID'], 'navbarId' => $nav['id']]);
        }
    }
