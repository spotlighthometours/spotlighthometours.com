<?PHP
/*	Author: Jacob Edmond Kerr
*	Desc: Microsite header control file
*/
	require_once($_SERVER['DOCUMENT_ROOT'].'/repository_inc/classes/inc.global.php');
	showErrors();
	if(isset($_REQUEST['domain'])&&!empty($_REQUEST['domain'])){
		$tourID = $db->run("SELECT userID FROM settings WHERE name='subdomain' AND userType='tour' AND value='".$_REQUEST['domain']."' AND typeID='1'");
		$tourID = $tourID[0]['userID'];
	}else{
		$tourID = $_SESSION['tourID'];
	}
	$microsites = new microsites($tourID);
	$slideshows = new slideshows();
	$metaData = $microsites->tours->getMetaData();
	$template = $microsites->getTemplate();
	$intro = false;
	$branded = true;
	if(isset($_REQUEST['branded'])&&$_REQUEST['branded']=="false"){
		$branded = false;
	}
	$section = $_REQUEST['section'];
	if(!isset($section)||$section=="details"){
		$intro = true;
	}
	$featuredVideo = $microsites->getFeaturedVideos();
	$featuredVideo = $featuredVideo[0];
	include($microsites->getDocumentRoot().'/templates/body/themes/'.$template.'/header.php');
?>