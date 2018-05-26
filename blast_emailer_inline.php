<?php
/**********************************************************************************************
Document: emailer_inline.php
Creator: Brandon Freeman
Date: 06-14-11
Purpose: Sends emails.
**********************************************************************************************/
	
//=======================================================================
// Includes
//=======================================================================

	require_once('repository_inc/classes/inc.global.php');


	require_once ('repository_inc/phpgmailer/class.phpgmailer.php');
	require_once ('repository_inc/error_recorder_incode.php');


global $placedImageList;

if(isset($_REQUEST['imageList']))
{
	$masterImageList = explode(',',$_REQUEST['imageList']);

}
if(isset($masterImageList))
{
	foreach($masterImageList as $imagePlacement)
	{
		$info = explode(':',$imagePlacement);
		$placedImageList[intval($info[0])] = str_replace('../../','http://www.spotlighthometours.com/',$info[1]);
	}
}


$emailBlastList = array();


if(isset($_REQUEST['recipients']))
{
	$recipientList = explode(',',$_REQUEST['recipients']);
	foreach($recipientList as $recipient)
	{
		$email = filter_var($recipient, FILTER_SANITIZE_EMAIL);
		$emailBlastList[] = $email;
		if(count($emailBlastList)>24)
		{
			break;
		}
	}
}
// die(json_encode($emailBlastList));

	global $brokerLogo, $brokerageLogoLink, $logoHeight, $logoWidth, $users, $tourID;

$tourID = $_REQUEST['tourID'];
	$tours = new tours();
	$users = new users();
	$brokerages = new brokerages();

	$users->validateTourRequest = false;
	$users->authenticate();

	$tours->loadTour($tourID);
	$users->loadUser($tours->userID);
	$users->phone = str_replace("-", "", filter_var($users->phone, FILTER_SANITIZE_NUMBER_INT));
	$users->phone = "(".substr($users->phone, 0, 3).") ".substr($users->phone, 3, 3).".".substr($users->phone, 6, 4);
	$logoHolderHeight = 123;
	$logoholderWidth = 115;
	// LOAD BROKERAGE INFO
	$brokerages->loadBrokerage($users->BrokerageID);
	// GET THE BROKER THEME
	$theme = $brokerages->getTheme();
	// LOAD BROKERAGE LOGO
    if($tours->use_per_tour_bkr_img ){
    	$brokerLogo = BROKER_LOGO_DIR_URL . '/' . $tours->use_per_tour_bkr_img;
        $brokerageLogoLink = $tours->per_tour_bkr_link;
	}elseif($tours->use_secondary_bkr_img){
		$brokerLogo = $brokerages->getSecondaryLogo();
		$brokerageLogoLink = $brokerages->secondary_logo_link;			
    }else{
		$brokerLogo = $brokerages->getLogo();
		$brokerageLogoLink = $brokerages->logo_link;
	}
	if(isset($brokerLogo)&&!empty($brokerLogo)){




// if(class_exists('Imagick'))
// {
	$thumbInfo = pathinfo(str_replace(BROKER_LOGO_DIR_URL, BROKER_LOGO_DIRECTORY, $brokerLogo));
	if(file_exists($thumbInfo['dirname'].'/e_blast_'.$thumbInfo['basename']))
	{
	    // $thumb = new Imagick($thumbInfo['dirname'].'/'.$thumbInfo['basename']);

	    // $thumb->resizeImage(124,0,Imagick::FILTER_LANCZOS,1);
	    // $thumb->writeImage($thumbInfo['dirname'].'/e_blast_'.$thumbInfo['basename']);

	    // $thumb->destroy();
		$brokerLogo = BROKER_LOGO_DIR_URL.'e_blast_'.$thumbInfo['basename'];
	}
	// else
	// {
	//     echo '<img src="'.BROKER_LOGO_DIR_URL.'e_blast_'.$thumbInfo['basename'].'" />';
	//     echo '<img src="'.BROKER_LOGO_DIR_URL.$thumbInfo['basename'].'" />';
	// }
// }
// else
// {
// 	file_put_contents(BROKER_LOGO_DIRECTORY."no-magick.log", 'wasnt able to detect class');
// }


		$brokerageLogoInfo = getimagesize(str_replace(BROKER_LOGO_DIR_URL, BROKER_LOGO_DIRECTORY, $brokerLogo));
		$brokerageHolderHeight = 116;
		$brokerageHolderWidth = 124;
		if($brokerageLogoInfo[0]>$logoHolderHeight){
			$logoWidth = $logoHolderHeight-10;
		}else{
			$logoWidth = $brokerageLogoInfo[0];
		}
	}
	function calculateHeight($width){
		global $logoHolderWidth, $logoHolderHeight;
		$sizePercent = $logoHolderHeight/$logoHolderWidth;
		return round($width*$sizePercent);
	}
	$logoHeght = calculateHeight($logoWidth);
	$logoMargintop = ($logoHolderHeight-$logoHeght)/2;


	die(SendMail($_REQUEST['subject'],getTemplate(intval($_REQUEST['templateID'])),implode(',',$emailBlastList),$_REQUEST['from_email']));
die(json_encode(SendMail("testing",getTemplate(intval($_REQUEST['templateID'])),'brad@spotlighthometours.com,brad@spotlighthometours.com')));
die(getTemplate(1));
die(json_encode($_REQUEST));
//=======================================================================
// Document
//=======================================================================
	
	function SendMail($subject, $body, $recipients, $sender='info@spotlighthometours.com') {
// return 'hala';

		try {
		$log = 'Blast Sent Successfully!&nbsp;';
		
		$recipients = explode(",", $recipients);
		
		if(sizeof($recipients) && strlen($subject) && strlen($body)) {
	
			$mail = new PHPGMailer();
			
			foreach ($recipients as $recipient) {
				$mail->AddAddress(trim($recipient));
			}
			
			$mail->Username = 'info@spotlighthometours.com'; 
			$mail->Password = 'bailey22';
			
			$mail->From = $sender;
			$mail->FromName = filter_var($_REQUEST['from_name'], FILTER_SANITIZE_EMAIL);

			// $mail->bcc_from_email = $_REQUEST['bcc_from_email'];
			if(isset($_REQUEST['bcc_from_email']))
			{
				// $mail->AddAddress($_REQUEST['bcc_from_email']);
			}

			$mail->IsHTML(true);
			
			if (strlen($sender) > 0) {
				$mail->AddReplyTo(trim($sender));
				$mail->Subject = $sender . " says: " . $subject;
			} else {
				
				$mail->Subject = $subject;
			}
			
			$mail->Body = $body;
			
			if(!$mail->Send()) {
				$log .= "Mailer Error: " . $mail->ErrorInfo . "\n";
				$log .= 'Email was not sent because:' . "\n";	
				$log .= $mail->ErrorInfo . "\n";
			}
		} else {
			$log .= "Email was not sent because one of the required fields was empty:\n";	
			if (sizeof($recipients) == 0) {
				$log .= "Recipients\n";
			}
			if (strlen($subject) == 0) {
				$log .= "Subject\n";
			}
			if (strlen($body) == 0) {
				$log .= "Email Body\n";
			}
		}
		} catch (Exception $e) {
			$log .= date("YmdHis") . " - ERROR: " . $e->getMessage() . "\n";
		}
		return $log;
	}
	



function getTemplate ($index = 0)
{
		global $brokerLogo, $brokerageLogoLink, $logoHeight, $logoWidth, $users, $placedImageList, $tourID;

		$prefixBody = "";
if(isset($_REQUEST['message']))
{
	$prefixBody = htmlspecialchars($_REQUEST['message'])."<br /><br />";
}
	if($index===1)
	{
	return $prefixBody.'
<div class="template" id="preview" style="margin:0;width:100%;background:black;text-align:center;">
	<table style="width:100%;height:100px;max-width:720px;" align="center">
		<tr>
			<td style="width:75%;">
<div style="padding-left:10px;padding-right:10px;font-size:20px;position:relative;">
    <div style="display:inline-block;text-align:left;color:white;font­size:20px;" name="address" id="addressDisplay">'.$_REQUEST['address'].'</div>
    <span style="color:white;font­size:20px;">&nbsp;|&nbsp;</span>
    <div style="display:inline-block;text-align:center;color:white;font­size:20px;" name="city" id="cityDisplay">'.$_REQUEST['city'].'</div>
    <span style="color:white;font­size:20px;">&nbsp;|&nbsp;</span>
    <div style="display:inline-block;color:white;font­size:20px;" name="state" id="stateDisplay">'.$_REQUEST['state'].'</div>
    <div style="display:inline-block;color:white;font­size:20px;" name="zip" id="zipDisplay">'.$_REQUEST['zip'].'</div>
</div>
			</td>
		</tr>
	</table>
	<div class="photo-holder intro-photo preview" data-photoholderid="1" id="1">
        <div class="instruct" style=""><img style="width:100%;max-width:720px;" src="'.$placedImageList[1].'"></div>
    </div>
<div style="text-align:center;">
	<table style="max-width:720px;" align="center">
		<tr>
			<td>
<div style="text-align:center;">
	<table style="width:100%;">
		<tr style="">
		    <td>
        <div class="" id="headlineDisplay" style="text-align:left;color:white;margin-left:50px;text-align:left;font-size:20px;position:relative;margin-top:-25px;">'.$_REQUEST['headline'].'</div>
        <span class="descriptionPreviewValue" id="descriptionDisplay" style="text-align:left;float:right;color:white;margin-right:20px;padding:10px;padding-left:50px;height:170px;overflow:hidden;">'.$_REQUEST['description'].'</span>
        <br />
        <div class="clear"></div>
        <div style="text-align:center;position:relative;padding-top:15px;clear:both;">
            <span style="font-size:12px;color:white;">offered at:</span><br />
            <span style="font-size:18px;color:white;" id="priceDisplay">'.$_REQUEST['price'].'</span><br />
        </div>


		    </td>
		</tr>
	    <tr style="text-align:center;">
	    	<td style="padding-right:20px;">
				<div>
    				<hr style="margin-left:50px;" />
                    	<table style="width:100%;margin-left:20px;text-align:left;">
                        	<tr>
                            	<td colspan="2" style="text-align:center;"><span style="color:white;font­size:20px;">Property Details</span></td>
							</tr>
                            <tr>
                            	<td>
                                	<span class="descriptionPreviewTitle" style="color:white;margin-left:40px;">Bedrooms</span>
                                    <span class="descriptionPreviewValue" id="bedroomsDisplay" style="color:white;float:right;margin-right:25px;">'.$_REQUEST['bedrooms'].'</span>
                                </td>
                            	<td>
                                	<span class="descriptionPreviewTitle" style="color:white;margin-left:40px;">Year&nbsp;Built</span>
                                    <span class="descriptionPreviewValue" id="year_builtDisplay" style="color:white;float:right;margin-right:25px;">'.$_REQUEST['year_built'].'</span>
                                </td>
							</tr>
                            <tr>
                            	<td>
                                	<span class="descriptionPreviewTitle" style="color:white;margin-left:40px;">Bathrooms</span>
                                    <span class="descriptionPreviewValue" id="bathroomsDisplay" style="color:white;float:right;margin-right:25px;">'.$_REQUEST['bathrooms'].'</span>
                                </td>
                                <td>
                                	<span class="descriptionPreviewTitle" style="color:white;margin-left:40px;">Acres</span>
                                    <span class="descriptionPreviewValue" id="acresDisplay" style="color:white;float:right;margin-right:25px;">'.$_REQUEST['acres'].'</span>
                                </td>
                            </tr>
							<tr>
                            	<td>
                                	<span class="descriptionPreviewTitle" style="color:white;margin-left:40px;">Square&nbsp;Feet</span>
                                    <span class="descriptionPreviewValue" id="square_feetDisplay" style="color:white;float:right;margin-right:25px;">'.$_REQUEST['square_feet'].'</span>
                                </td>
                                <td>
                                	<span class="descriptionPreviewTitle" style="color:white;margin-left:40px;">MLS #</span>
                                    <span class="descriptionPreviewValue" id="mls_numDisplay" style="color:white;float:right;margin-right:25px;">'.$_REQUEST['mls_num'].'</span>
                            	</td>
                            </tr>
                        </table>
    				<hr style="margin-left:50px;" />
				</div>
			</td>
		</tr>
		<tr>
			<td style="text-align:center;padding-top:10px;">
    			<a href="http://www.spotlighthometours.com/tours/tour.php?tourid='.$tourID.'"><img src="http://www.spotlighthometours.com/repository_images/eBlastButton.png" /></a>
			</td>
		</tr>
	</table>
</div>
			</td>
			<td style="vertical-align:top;min-width:245px;">

                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="2"  id="2">
                                    <div style="display:inline;"" class="instruct"><img width="110" src="'.$placedImageList[2].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[3].'"></div>
                                </div>
                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="4"  id="4">
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[4].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[5].'"></div>
                                </div>
                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="2"  id="2">
                                    <div style="display:inline;"" class="instruct"><img width="110" src="'.$placedImageList[6].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[7].'"></div>
                                </div>
                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="4"  id="4">
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[8].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[9].'"></div>
                                </div>
                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="2"  id="2">
                                    <div style="display:inline;"" class="instruct"><img width="110" src="'.$placedImageList[10].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[11].'"></div>
                                </div>
                                <br />
                                <div style="text-align:center;">
                                    <a href="http://www.spotlighthometours.com/tours/tour.php?tourid='.$tourID.'">view all photos</a>
                                </div>
</td></tr>



<tr><td colspan="2">

<table style="width:100%;">
    <tr>
        <td style="text-align:right;padding-bottom:20px;">
            <img src="' . $users->getAvatar() . '" style="max-height:100px;margin-top:-50px;position:relative;" />
            <div style="display:inline-block;padding-left:10px;padding-top:20px;">
                <span style="color:white;display:block;font-weight:bold;text-align:left;">' .  $users->firstName . " " . $users->lastName . '</span>
                <span style="color:white;display:block;text-align:left;">' . $users->phone . '</span>
                <span style="color:blue;display:block;text-align:left;font-size:12px;"><a href="mailto:' . $users->email . '">' . $users->email . '</span>
                <span style="color:white;display:block;text-align:left;font-size:10px;">' . str_replace("http://","",$users->uri) . '</span>
            </div>
        </td>
        <td style="width:50%;">
            <a href="' . $brokerageLogoLink. '">
                <img src="' . $brokerLogo . '" width="'.$logoHeight.'" height="'.$logoHeight.'" style="max-height:100px;" />
            </a>
            
        </td>
    </tr>


                                    <tr>
                                        <td colspan="2" style="text-align:center;padding:10px;">
                                            <img src="http://www.spotlighthometours.com/repository_images/SpotlightProvidedBy.png" width="'.$logoHeight.'" height="'.$logoHeight.'" />
                                        </td>
                                    </tr>


</table>


</td></tr>
</table>
</div>
                                <div class="clear"></div>
';
}
else
{
	return $prefixBody.'
<div class="template" id="preview" style="margin:0;width:99%;background:white;border:1px solid black;text-align:center;">
	<table style="width:100%;height:100px;max-width:720px;" align="center">
		<tr>
			<td style="width:75%;">
<div style="padding-left:10px;padding-right:10px;font-size:20px;position:relative;">
    <div style="display:inline-block;text-align:left;color:black;font­size:20px;" name="address" id="addressDisplay">'.$_REQUEST['address'].'</div>
    <span style="color:black;font­size:20px;">&nbsp;|&nbsp;</span>
    <div style="display:inline-block;text-align:center;color:black;font­size:20px;" name="city" id="cityDisplay">'.$_REQUEST['city'].'</div>
    <span style="color:black;font­size:20px;">&nbsp;|&nbsp;</span>
    <div style="display:inline-block;color:black;font­size:20px;" name="state" id="stateDisplay">'.$_REQUEST['state'].'</div>
    <div style="display:inline-block;color:black;font­size:20px;" name="zip" id="zipDisplay">'.$_REQUEST['zip'].'</div>
</div>
			</td>
		</tr>
	</table>
	<div class="photo-holder intro-photo preview" data-photoholderid="1" id="1">
        <div class="instruct"><img style="width:100%;max-width:720px;" src="'.$placedImageList[1].'"></div>
    </div>
<div style="text-align:center;">
	<table style="max-width:720px;" align="center">
		<tr>
			<td>
<div style="text-align:center;">
	<table style="width:100%;" align="center">
		<tr style="height:265px;">
		    <td>
        <div class="" id="headlineDisplay" style="text-align:left;color:black;margin-left:50px;text-align:left;font-size:20px;position:relative;margin-top:-25px;">'.$_REQUEST['headline'].'</div>
        <span class="descriptionPreviewValue" id="descriptionDisplay" style="text-align:left;float:right;color:black;margin-right:20px;float:right;padding:10px;padding-left:50px;height:170px;overflow:hidden;">'.$_REQUEST['description'].'</span>
        <br />
        <div class="clear"></div>
        <div style="text-align:center;position:relative;padding-top:15px;">
            <span style="font-size:12px;color:black;">offered at:</span><br />
            <span style="font-size:18px;color:black;" id="priceDisplay">'.$_REQUEST['price'].'</span><br />
        </div>



		    </td>
		</tr>
	    <tr style="text-align:center;">
	    	<td style="padding-right:20px;">
				<div>
    				<hr style="margin-left:50px;" />
                    	<table style="width:100%;margin-left:20px;text-align:left;">
                        	<tr>
                            	<td colspan="2" style="text-align:center;"><span style="color:black;font­size:20px;">Property Details</span></td>
							</tr>
                            <tr>
                            	<td>
                                	<span class="descriptionPreviewTitle" style="color:black;margin-left:40px;">Bedrooms</span>
                                    <span class="descriptionPreviewValue" id="bedroomsDisplay" style="color:black;float:right;margin-right:25px;">'.$_REQUEST['bedrooms'].'</span>
                                </td>
                            	<td>
                                	<span class="descriptionPreviewTitle" style="color:black;margin-left:40px;">Year&nbsp;Built</span>
                                    <span class="descriptionPreviewValue" id="year_builtDisplay" style="color:black;float:right;margin-right:25px;">'.$_REQUEST['year_built'].'</span>
                                </td>
							</tr>
                            <tr>
                            	<td>
                                	<span class="descriptionPreviewTitle" style="color:black;margin-left:40px;">Bathrooms</span>
                                    <span class="descriptionPreviewValue" id="bathroomsDisplay" style="color:black;float:right;margin-right:25px;">'.$_REQUEST['bathrooms'].'</span>
                                </td>
                                <td>
                                	<span class="descriptionPreviewTitle" style="color:black;margin-left:40px;">Acres</span>
                                    <span class="descriptionPreviewValue" id="acresDisplay" style="color:black;float:right;margin-right:25px;">'.$_REQUEST['acres'].'</span>
                                </td>
                            </tr>
							<tr>
                            	<td>
                                	<span class="descriptionPreviewTitle" style="color:black;margin-left:40px;">Square&nbsp;Feet</span>
                                    <span class="descriptionPreviewValue" id="square_feetDisplay" style="color:black;float:right;margin-right:25px;">'.$_REQUEST['square_feet'].'</span>
                                </td>
                                <td>
                                	<span class="descriptionPreviewTitle" style="color:black;margin-left:40px;">MLS #</span>
                                    <span class="descriptionPreviewValue" id="mls_numDisplay" style="color:black;float:right;margin-right:25px;">'.$_REQUEST['mls_num'].'</span>
                            	</td>
                            </tr>
                        </table>
    				<hr style="margin-left:50px;" />
				</div>
			</td>
		</tr>
		<tr>
			<td style="text-align:center;padding-top:10px;">
    			<a href="http://www.spotlighthometours.com/tours/tour.php?tourid='.$tourID.'"><img src="http://www.spotlighthometours.com/repository_images/eBlastButton.png" /></a>
			</td>
		</tr>
	</table>
</div>
			</td>
			<td style="vertical-align:top;min-width:245px;">

                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="2"  id="2">
                                    <div style="display:inline;"" class="instruct"><img width="110" src="'.$placedImageList[2].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[3].'"></div>
                                </div>
                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="4"  id="4">
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[4].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[5].'"></div>
                                </div>
                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="2"  id="2">
                                    <div style="display:inline;"" class="instruct"><img width="110" src="'.$placedImageList[6].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[7].'"></div>
                                </div>
                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="4"  id="4">
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[8].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[9].'"></div>
                                </div>
                                <div style="display:block;" class="photo-holder thmb-photo preview" data-photoholderid="2"  id="2">
                                    <div style="display:inline;"" class="instruct"><img width="110" src="'.$placedImageList[10].'"></div>
                                    <div style="display:inline;" class="instruct"><img width="110" src="'.$placedImageList[11].'"></div>
                                </div>
                                <br />
                                <div style="text-align:center;">
                                    <a href="http://www.spotlighthometours.com/tours/tour.php?tourid='.$tourID.'">view all photos</a>
                                </div>
</td></tr>



<tr><td colspan="2">

<table style="width:100%;">
    <tr>
        <td style="text-align:right;padding-bottom:20px;">
            <img src="' . $users->getAvatar() . '" style="max-height:100px;margin-top:-50px;position:relative;" />
            <div style="display:inline-block;padding-left:10px;padding-top:20px;">
                <span style="color:black;display:block;font-weight:bold;text-align:left;">' .  $users->firstName . " " . $users->lastName . '</span>
                <span style="color:black;display:block;text-align:left;">' . $users->phone . '</span>
                <span style="color:blue;display:block;text-align:left;font-size:12px;"><a href="mailto:' . $users->email . '">' . $users->email . '</span>
                <span style="color:black;display:block;text-align:left;font-size:10px;">' . str_replace("http://","",$users->uri) . '</span>
            </div>
        </td>
        <td style="width:50%;">
            <a href="' . $brokerageLogoLink. '">
                <img src="' . $brokerLogo . '" width="'.$logoHeight.'" height="'.$logoHeight.'" style="max-height:100px;" />
            </a>
            
        </td>
    </tr>
</table>


</td></tr>
</table>
                                <div class="clear"></div>
';
}

}

?>
