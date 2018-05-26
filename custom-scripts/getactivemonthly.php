<?PHP
/*
 * BERKSHIRE TOUR WINDOW / MICROSITE
 */
	// APPLICATION GLOBAL CONFIG
	require_once($_SERVER['DOCUMENT_ROOT'].'/repository_inc/classes/inc.global.php');
	showErrors();
	$authorizenet = new authorizenetdev();
	$subscriptions = $authorizenet->getSubscriptions();
?>