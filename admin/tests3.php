<?php
    require '../repository_inc/classes/inc.global.php';
    $tours = new tours;
    $tours->queueForS3(54655);
