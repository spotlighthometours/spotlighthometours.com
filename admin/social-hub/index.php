<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Social Hub Content Manager</title>
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
	@import "includes/content-manager.css"; /* Social Hub Content Manager CSS */
</style>
</head>
<body>
<div class="container">
	<h1>Concierge Social Content Manager</h1>
  	<p>&nbsp;</p>
  	<!-- MANAGE CATEGORIES -->
   	<h2>Manage Categories</h2>
   	<div class="btn-group cat-man-menu">
    	<button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Manage Categories &nbsp;<span class="caret"></span></button> 
        <ul class="dropdown-menu">
        </ul> 
    </div>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <!-- MANAGE CONTENT -->
    <h2>Manage Content</h2>
    <div class="row">
        <!-- CONTENT PREVIEW CATEGORY SELECTION -->
        <div class="col-xs-6">
            <div class="btn-group cat-cont-menu">
                <button type="button" class="btn btn-default" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All Categories &nbsp;<span class="caret"></span></button> 
                <ul class="dropdown-menu cat-cont-men">
                </ul> 
            </div>
            <button type="button" class="btn btn-default my-content"><i class="glyphicon glyphicon-eye-open"></i> My Content</button>
        </div>
        <!-- CREATE CONTENT BUTTON -->
        <div class="col-xs-6">
            <div align="right">
                <button type="button" class="btn btn-success btn-md" onclick="showCreateContentModal()">
                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> &nbsp;CREATE NEW CONTENT
                </button>
            </div>
        </div>
    </div>
    <!-- SAVED CONTENT: SORT BY SELECTION -->
    <div class="col-xs-6 sort-by">
    	<div class="form-group">
		  Sort By:
		  <select class="form-control" id="sortBy" style="width:auto; display:inline;">
			<option value="createdOn::DESC">Created Descending</option>
			<option value="createdOn::ASC">Created Ascending</option>
			<option value="views::DESC">Views Descending</option>
			<option value="views::ASC">Views Ascending</option>
			<option value="likes::DESC">Likes Descending</option>
			<option value="likes::ASC">Likes Ascending</option>
			<option value="dislikes::DESC">Dislikes Descending</option>
			<option value="dislikes::ASC">Dislikes Ascending</option>
			<option value="previewtitle::ASC">Title Ascending</option>
			<option value="previewtitle::DESC">Title Descending</option>
			<option value="caption::ASC">Caption Ascending</option>
			<option value="caption::DESC">Caption Descending</option>
			<option value="previewdomain::ASC">Domain Ascending</option>
			<option value="previewdomain::DESC">Domain Descending</option>
			<option value="modifiedOn::ASC">Modified Ascending</option>
			<option value="modifiedOn::DESC">Modified Descending</option>
			<option value="scheduledOn::ASC">Scheduled Ascending</option>
			<option value="scheduledOn::DESC">Scheduled Descending</option>
			<option value="scheduleFrom::ASC">Scheduled <-> Ascending</option>
			<option value="scheduleFrom::DESC">Scheduled <-> Descending</option>
		  </select>
		  <span class="num-results"></span>
		</div>
    </div>
    <!-- SAVED CONTENT: LIST PREVIEW NETWORK TYPE SELECTION -->
    <div class="network-type col-xs-6">
    	Network Type: 
		<a class="btn btn-social-icon btn-sm btn-facebook active" href="javascript:setNetwork('facebook')"><span class="fa fa-facebook"></span></a>
   		<a class="btn btn-social-icon btn-sm btn-linkedin" href="javascript:setNetwork('linkedin')"><span class="fa fa-linkedin"></span></a>
   		<a class="btn btn-social-icon btn-sm btn-twitter" href="javascript:setNetwork('twitter')"><span class="fa fa-twitter"></span></a>
   		<a class="btn btn-social-icon btn-sm btn-instagram"><span class="fa fa-instagram"></span></a>
   		<a class="btn btn-social-icon btn-sm btn-pinterest"><span class="fa fa-pinterest"></span></a>
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
<!-- NEW POST MODAL -->
<div class="modal fade" id="cContentModal" tabindex="-1" role="dialog" aria-labelledby="cContentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="cContentModalLabel">Create Content</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
		  	<label>Categories</label>
			<select class="select-categories form-control" style="width:100%;" multiple="true"></select>
		</div>
   		<div class="form-group">
		  	<label>Network</label>
			<select class="form-control selectmulti" name="networkType" multiple>
				<option value="all" selected>All</option>
				<option value="facebook">Facebook</option>
				<option value="twitter">Twitter</option>
				<option value="linkedin">Linkedin</option>
			</select>
		</div>
    	<div class="form-group well content-input">
			<div class="alert alert-danger">
			  <strong>Twitter character limit reached:</strong> Please note this content will be cut off after 140 characters for twitter.
			</div>
			<textarea class="form-control" placeholder="Content goes here..." id="newPostContent" rows="5" maxlength="64000"></textarea>
			<span class="pull-right label label-default" id="count_message"></span>
            <input type='file' id="imgFileSelect" accept="image/*" />
            <input type='file' id="vidFileSelect" accept="video/*" />
            <ul class='list-inline post-actions'>
                <li><a href="#" class="uploadImg"><span class="glyphicon glyphicon-camera"></span></a></li>
                <!-- <li><a href="#" class="uploadVid"><span class="glyphicon glyphicon-facetime-video"></span></a></li> -->
            </ul>
		</div>
        <div class="btn-group" style="margin:5px 0 10px 0;">
		  <button type="button" class="btn btn-primary btn-lg schedule-btn" onclick="showSchedule()"><i class="fa fa-calendar" aria-hidden="true"></i></button>
		  <button type="button" class="btn btn-primary btn-lg content-preview-btn active" onclick="showContentPreview()"><i class="fa fa-file" aria-hidden="true"></i></button>
		</div>
    	<div class="content-preview well">
    		<div class="alert alert-info">
			  <strong>Preview will load here:</strong> Once you upload media or enter a URL into the content textarea above.
			</div>
   			<div class="network-type">
				Network Type: 
				<a class="btn btn-social-icon btn-sm btn-facebook active" href="javascript:setCNetwork('facebook')"><span class="fa fa-facebook"></span></a>
				<a class="btn btn-social-icon btn-sm btn-linkedin" href="javascript:setCNetwork('linkedin')"><span class="fa fa-linkedin"></span></a>
				<a class="btn btn-social-icon btn-sm btn-twitter" href="javascript:setCNetwork('twitter')"><span class="fa fa-twitter"></span></a>
				<a class="btn btn-social-icon btn-sm btn-instagram"><span class="fa fa-instagram"></span></a>
				<a class="btn btn-social-icon btn-sm btn-pinterest"><span class="fa fa-pinterest"></span></a>
			</div>
    		<div class="preview facebook">
				<div class="caption"></div>
				<div class="image-wrapper">
					<div class="play-icon"></div>
					<div class="imgframe"><img src="images/article-post-img.png"></div>
					<video width="476" controls id="cContentVideoPreview" data-setup='{"fluid": true}' class="video-js">
						<source src="mov_bbb.mp4" id="video_here">
						Your browser does not support HTML5 video.
					</video>
					<h2>Have You Seen This? Hikers at Zion form human chain during flash flood | KSL.com</h2>
					<p>A group of hikers at Zion National Park Saturday were making their way through the Narrows when a flash flood hit around 11:30 a.m. Fortunately, due to a little help...</p>
					<a href="">KSL.COM</a>
   				</div>
    		</div>
		</div>
     	<div class="schedule well">
			<div class="btn-group" style="margin-top:-10px;margin-left:-10px;margin-bottom:30px;">
			  <button type="button" class="btn btn-default exact-date-btn active" onclick='showExactDate()'><i class="fa fa-calendar" aria-hidden="true"></i></button>
			  <button type="button" class="btn btn-default date-range-btn" onclick='showDateRange()'><i class="fa fa-arrows-h" aria-hidden="true"></i></button>
			  <button type="button" class="btn btn-default no-date-btn" onclick='showNoDate()'><i class="fa fa-ban" aria-hidden="true"></i></button>
			</div>
			<div class="no-date">
				<div class="alert alert-info">
			  		<strong>No schedule needed for this content:</strong> Selecting this option will create no specific schedule for this content.
				</div>
			</div>
			<div class="form-group exact-date">
				<div class="row">
					<div class="col-md-8">
						<div id="contentSchedule"></div>
					</div>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="scheduleYear" value="0" checked="checked">Ignore year</label>
				</div>
			</div>
			<div class="form-group date-range">
				<div class="row">
					<div class='col-md-5'>
						<div class="form-group">
							<div class='input-group date' id='contentScheduleFrom'>
								<input type='text' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
					<div class="col-md-2">
						<h2 style="margin:0px;text-align:center;"><i class="fa fa-arrows-h" aria-hidden="true"></i></h2>
					</div>
					<div class='col-md-5'>
						<div class="form-group">
							<div class='input-group date' id='contentScheduleTo'>
								<input type='text' class="form-control" />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="scheduleFrameYear" value="0" checked="checked">Ignore year</label>
				</div>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onmousedown='saveContent()'>Save Content</button>
      </div>
    </div>
  </div>
</div>
<!-- CREATE CATEGORY MODAL -->
<div class="modal fade" id="cCatModal" tabindex="-1" role="dialog" aria-labelledby="cCatModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="cCatModalLabel">Create Category</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
		  	<label>Name</label>
			<input class="form-control" type="text" value="" name="catName">
		</div>
    	<div class="form-group">
		  	<label>Parent</label>
			<select class="select-categories form-control" style="width:568px;" id="catParent" multiple="true" name="catParent">
			</select>
		</div>
     	<div class="form-group">
		  	<label>Hidden</label>
			<div class="form-check">
			  <label class="form-check-label">
				<input class="form-check-input" type="checkbox" name="catHidden">
			  </label>
			</div>
		</div>
     	<div class="form-group">
		  	<label>Platinum</label>
			<div class="form-check">
			  <label class="form-check-label">
				<input class="form-check-input" type="checkbox" name="catPlatinum">
			  </label>
			</div>
		</div>
        <div class="form-group">
		  	<label>Pinterest</label>
			<div class="form-check">
			  <label class="form-check-label">
				<input class="form-check-input" type="checkbox" name="catPinterest">
			  </label>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="createCategory()">Create Category</button>
      </div>
    </div>
  </div>
</div>
<!-- EDIT CATEGORY MODAL -->
<div class="modal fade" id="eCatModal" tabindex="-1" role="dialog" aria-labelledby="eCatModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="eCatModalLabel">Edit Category</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
		  	<label>Name</label>
			<input class="form-control" type="text" value="" name="catName">
		</div>
    	<div class="form-group">
		  	<label>Parent</label>
			<select class="select-categories form-control" style="width:568px;" multiple="true" name="catParent" id="catParent">
			</select>
		</div>
     	<div class="form-group">
		  	<label>Hidden</label>
			<div class="form-check">
			  <label class="form-check-label">
				<input class="form-check-input" type="checkbox" name="catHidden">
			  </label>
			</div>
		</div>
     	<div class="form-group">
		  	<label>Platinum</label>
			<div class="form-check">
			  <label class="form-check-label">
				<input class="form-check-input" type="checkbox" name="catPlatinum">
			  </label>
			</div>
		</div>
        <div class="form-group">
		  	<label>Pinterest</label>
			<div class="form-check">
			  <label class="form-check-label">
				<input class="form-check-input" type="checkbox" name="catPinterest">
			  </label>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Delete</button>
        <button type="button" class="btn btn-primary">Save Category</button>
      </div>
    </div>
  </div>
</div>
<div id="ajaxMessage"></div>
<!-- SOCIAL HUB CONTENT MANAGER JS CONTROL FILES -->
<script src='includes/content-manager.js'></script>
<script>
	userType = '<?PHP echo (isset($_REQUEST['userType']))?$_REQUEST['userType']:'admin';?>';
	userID = <?PHP echo (isset($_REQUEST['userID']))?$_REQUEST['userID']:0;?>;
	getCategoris();
</script>
<script src='../../repository_inc/admin-v3.js'></script>
</body>
</html>