<?PHP
	// Include appplication's global configuration
	require_once('../../repository_inc/classes/inc.global.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Posts | Social Compass</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"><!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"><!-- Bootstrap Theme CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" crossorigin="anonymous"><!-- Bootstrap Datetime CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"><!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css"><!-- Bootstrap Social CSS -->
<link href="http://vjs.zencdn.net/6.2.4/video-js.css" rel="stylesheet"><!-- VIDEO JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css"><!-- Select2 CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
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
<script src='includes/responsive-paginate.js'></script> <!-- RESPONSIVE PAGINATION --> 
<style type="text/css" media="screen">
	@import "includes/user-posts.css"; /* Social Hub Content Manager CSS */
</style>
</head>
<body>
<div class="container">
	<h1>Social Compass Previous Post <br/></h1>
    <!-- SAVED CONTENT: LIST PREVIEW NETWORK TYPE SELECTION -->
    <div class="network-type col-xs-6 pull-right">
    	Network Type: 
		<a class="btn btn-social-icon btn-sm btn-facebook active" href="javascript:setNetwork('facebook')"><span class="fa fa-facebook"></span></a>
   		<a class="btn btn-social-icon btn-sm btn-linkedin" href="javascript:setNetwork('linkedin')"><span class="fa fa-linkedin"></span></a>
   		<a class="btn btn-social-icon btn-sm btn-twitter" href="javascript:setNetwork('twitter')"><span class="fa fa-twitter"></span></a>
   		<a class="btn btn-social-icon btn-sm btn-instagram"><span class="fa fa-instagram"></span></a>
   		<a class="btn btn-social-icon btn-sm btn-pinterest" href="javascript:setNetwork('pinterest')"><span class="fa fa-pinterest"></span></a>
    </div>
    <div class="clear"></div>
    <!-- SAVED CONTENT: LIST, PREVIEW, OPTIONS -->
    <div class="saved-content">
		<div class="clear"></div>
   	</div>
 	<!-- SAVED CONTENT LIST PAGINATION -->
  	<div align="right">
   		<nav aria-label="Page navigation example">
        	<ul class="pagination">
            	<li class="page-item"><a class="page-link" href="#">Previous</a></li>
            	<li class="page-item"><a class="page-link" href="#">1</a></li>
            	<li class="page-item"><a class="page-link" href="#">2</a></li>
            	<li class="page-item"><a class="page-link" href="#">3</a></li>
            	<li class="page-item"><a class="page-link" href="#">Next</a></li>
          	</ul>
        </nav>
   </div>
</div>
<div id="ajaxMessage"></div>
<!-- SOCIAL HUB CONTENT MANAGER JS CONTROL FILES -->
<script src='includes/user-posts.js'></script>
<script>
	memberID = <?PHP echo $_REQUEST['memberID']; ?>;
<?PHP
	$socialmarketing = new socialmarketing();
	$memberSettings = $socialmarketing->getSettingsByMemberID($_REQUEST['memberID']);
?>
	userType = '<?PHP echo $memberSettings['userType']; ?>';
	userID = '<?PHP echo $memberSettings['userID']; ?>';
	getCategoris();
</script>
<script src='../../repository_inc/admin-v3.js'></script>
</body>
</html>