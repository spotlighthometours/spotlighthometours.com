<?php
    require_once( dirname(__FILE__) . '/../repository_inc/classes/inc.global.php' );
    $tours = [ 
     58233,
 29579,
 60331,
 60335,
 60388,
 30054,
 60075,
 27785,
 28100,
 25171,
 59473,
 17589,
 60346,
 60002,
 60184,
 50306,
 60206,
 60161,
 27121,
 59531
    ];
    $ss = new slideshows;
    foreach($tours as $key => $tourId){
        $w = $ss->getWalkThrus($tourId);
        //var_dump($w);
        $ss->addToCue( $w[0]['photoTourID'] );
    }
