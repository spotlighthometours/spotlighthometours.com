<?php
    require '../../repository_inc/classes/inc.global.php';
    $tv = new tourvideos;
    $tourId = $_REQUEST['tourId'];
    $mediaId = $_REQUEST['mediaId'];
    $ext = $_REQUEST['ext'];
    $fileInfo = [
        'tourID' => $tourId,
        'mediaID' => "$mediaId",
        'fileExt' => $ext
    ];
    $tv->process($fileInfo);

