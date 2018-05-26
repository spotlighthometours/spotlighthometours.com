<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Agent information template
*/
$users = new users();
$brokerages = new brokerages();
// LOAD USER INFO
$users->loadUser($microsites->tours->userID);
$userLogo = $users->getAvatar();
// LOAD BROKERAGE INFO
$brokerages->loadBrokerage($users->BrokerageID);
// LOAD BROKERAGE LOGO
if($microsites->tours->use_per_tour_bkr_img){
	$brokerLogo = BROKER_LOGO_DIR_URL . '/' . $microsites->tours->use_per_tour_bkr_img;
}elseif($microsites->tours->use_secondary_bkr_img){
	$brokerLogo = $brokerages->getSecondaryLogo();			
}else{
	$brokerLogo = $brokerages->getLogo();
}
if(!empty($brokerLogo)){
	$brokerageLogoInfo = getimagesize(str_replace(BROKER_LOGO_DIR_URL, BROKER_LOGO_DIRECTORY, $brokerLogo));
}
// Check width if > 197 then force the width
if($brokerageLogoInfo[0]>197){
	$brokerLogoSize='width="197"';
}
?>
<div class="clear"></div>
<div class="agent-info">
<?PHP
	if(!empty($brokerLogo)){
?>	
    <div class="brokerage-logo">
    	<img src="<?PHP echo $brokerLogo?>" alt="<?PHP echo ($brokerages->brokerageName=="Other")?'':$brokerages->brokerageName ?>" <?PHP echo (isset($brokerLogoSize))?$brokerLogoSize:''; ?> />
    </div>
<?PHP
	}
?>
    <div class="agent-photo">
<?PHP
	if(!empty($userLogo)){
?>
    	<img src="<?PHP echo $userLogo ?>" alt="<?PHP echo $users->firstName." ".$users->lastName; ?>" />
<?PHP
	}
?>
    </div>
    <div class="agent-name">
    	<?PHP echo $users->firstName." ".$users->lastName; ?>
    </div>
    <div class="agent-number">
    	<?PHP echo formatPhoneNumber($users->phone) ?>
    </div>
</div>