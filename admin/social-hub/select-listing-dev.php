<?PHP
	// APPLICATION GLOBAL CONFIG
	require_once($_SERVER['DOCUMENT_ROOT'].'/repository_inc/classes/inc.global.php');
	//showErrors();

	$memberID = 0;
	if( isset( $_REQUEST['memberID'] ) ) {
		$memberID = $_REQUEST['memberID'];
		$socialmarketing = new socialmarketing();
		$member = new members($socialmarketing->membershipID);
		$userID = 0;
		if( $member->loadMemberInfoByID($memberID) ) {
			$userID = $member->userID;
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Approve Post | Concierge Social</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"><!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"><!-- Bootstrap Theme CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" crossorigin="anonymous"><!-- Bootstrap Datetime CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"><!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css"><!-- Bootstrap Social CSS -->
<link href="http://vjs.zencdn.net/6.2.4/video-js.css" rel="stylesheet"><!-- VIDEO JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css"><!-- Select2 CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css"><!-- Bootstrap Select -->
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/><!-- X Editable -->
<link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css"><!-- Bootstrap Toggle CSS -->
<script src="../../repository_inc/jquery-1.11.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script><!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js" crossorigin="anonymous"></script><!-- Moment JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js" crossorigin="anonymous"></script><!-- Moment TZ JS -->
<script src="https://cdn.jsdelivr.net/bootstrap.datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" crossorigin="anonymous"></script><!-- Bootstrap Datetime JS -->
<script src='http://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-beta.3/js/select2.min.js'></script><!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/i18n/defaults-en_US.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.js" type="text/javascript"></script><!-- Angular JS -->
<script src="http://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script><!-- VIDEO JS -->
<script src="http://vjs.zencdn.net/6.2.4/video.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script><!-- X Editable -->
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js" type="text/javascript"></script><!-- Bootstrap Toggle JS -->
<link rel="stylesheet" type="text/css" href="includes/select-listing-sm-dev.css" />
<link rel="stylesheet" type="text/css" href="includes/select-listing-dev.css" media="screen  and (min-width: 40.5em)" />
</head>
<body>
<div class="container">
    <h2 align="center" class="main-heading">Approve Post</h2>
    <!-- SAVED CONTENT: LIST, PREVIEW, OPTIONS -->
    <div class="saved-content">
		<div class="clear"></div>
   	</div>
	<div class="text-center" style="padding-top:10px; padding-bottom:10px;">
		<button type="button" class="btn btn-secondary" onclick="window.location='http://www.spotlighthometours.com/'">Not Now</button> <button type="button" class="btn btn-primary" onclick="selectNetworks()">Approve Post</button>
	</div>
	<!-- User Profiles Modal -->
	<div class="modal fade" id="userProfilesModal" tabindex="-1" role="dialog" aria-labelledby="userProfilesModalTitle" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title" id="eCatModalLabel"><i class="glyphicon glyphicon-user"></i> Select Social Media Profiles <small>(for this post)</small></h4>
		  </div>
		  <div class="modal-body">
			<div class="user-profiles">
				<h4>Loading Social Media Profiles, Please Wait...</h4>
			</div>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="glyphicon glyphicon-backward"></i> Back to Select Post</button>
			<button type="button" class="btn btn-primary" onclick="postListing()"><i class="glyphicon glyphicon-ok"></i> Approve Post</button>
		  </div>
		</div>
	  </div>
	</div>
</div>
<div id="ajaxMessage"></div>
<!-- SOCIAL HUB CONTENT MANAGER JS CONTROL FILES -->
<script src='includes/select-listing-dev.js'></script>
<script>
	memberID = <?PHP echo $memberID ?>;
	userID = <?PHP echo $userID ?>;
	getListings();
	getUserSelectedNetworks();
</script>
<!--<script src='../../repository_inc/admin-v3.js'></script>-->
</body>
</html>