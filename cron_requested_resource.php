<?php
header('Access-Control-Allow-Origin: *');
// APPLICATION GLOBAL CONFIG
require_once($_SERVER['DOCUMENT_ROOT'].'/repository_inc/classes/inc.global.php');
showErrors();
ini_set("memory_limit", "-1");
set_time_limit(0);
$tourphotos =  new tourphotos();
$users = new users();
$tours = new tours();

$resources_data = $db->select("resource_requested","flag=" . 0);
$resources_data = json_decode(json_encode($resources_data), true);
if(count($resources_data)>0){
	print_r($resources_data);
	$res = $resources_data[0];
	// updating the status
    $db->update('resource_requested', array('flag'=>1), 'id='.$res['id']);
	
    $zipName = $tourphotos->createZip($res['tourId'], $res['image_size']);

    // fetch user data and send email
    $user_data = $users->fetchSingleUser($res['requested_by']);

    // fetch tour data and send email
    $tour_data = $tours->existReturn($res['tourId']);

    // send email code
    require_once ($_SERVER['DOCUMENT_ROOT'].'/repository_inc/phpgmailer/class.phpgmailer.php');
    $mail = new PHPGMailer();
    $mail->AddAddress($user_data['email']);
    $mail->Username = 'info@spotlighthometours.com';
    $mail->Password = 'bailey22';
    $mail->From = 'info@spotlighthometours.com';
    $mail->FromName = 'Spotlight';
    $mail->IsHTML(true);
    $mail->Subject = 'Tour images zip file ready for download!';
    $body = '<p>
            '.$user_data['firstName'].' '.$user_data['lastName'].',
            </p>
            <p>
            We are pleased to inform you that your Images zip file for <strong>'.$tour_data['address'].' '.$tour_data['city'].', '.$tour_data['state'].' '.$tour_data['zipCode'].'</strong> is online and available for download by clicking the link below:
            </p>
            <p>
            '.$zipName.'
            </p>
            
            <div id=\'subtext\'>
            <p>
            Please feel free to contact us with any questions or problems with your tour.
            </p>
            </div><!-- end subtext -->
            <p>
            Spotlight Home Tours<br/>
            801-466-4074<br/>
            888-838-8810<br/>
            support@spotlighthometours.com
            </p>';
    $mail->Body = $body;

    if(!$mail->Send()) {
    }
}