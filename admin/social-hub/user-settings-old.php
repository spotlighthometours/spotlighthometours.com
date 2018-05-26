<?PHP
	// APPLICATION GLOBAL CONFIG
	require_once($_SERVER['DOCUMENT_ROOT'].'/repository_inc/classes/inc.global.php');
	//showErrors();
	$socialmarketing = new socialmarketing();
	$membershipName = $socialmarketing->membership->name;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Social Hub Content Manager | User Settings</title>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css"><!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"><!-- Bootstrap Theme CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap.datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" crossorigin="anonymous"><!-- Bootstrap Datetime CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"><!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css"><!-- Bootstrap Toggle CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css"><!-- Bootstrap Social CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css"><!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css"><!-- Select2 CSS -->
<script src="../../repository_inc/jquery-1.11.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script><!-- jQuery UI -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script><!-- jQuery Validate -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script><!-- jQuery Validate -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script><!-- Tether JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script><!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js" crossorigin="anonymous"></script><!-- Moment JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.13/moment-timezone-with-data.js" crossorigin="anonymous"></script><!-- Moment TZ JS -->
<script src="https://cdn.jsdelivr.net/bootstrap.datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" crossorigin="anonymous"></script><!-- Bootstrap Datetime JS -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0-beta.3/js/select2.min.js'></script><!-- Select2 JS -->
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.5/angular.js" type="text/javascript"></script><!-- Angular JS -->
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js" type="text/javascript"></script><!-- Bootstrap Toggle JS -->
<style type="text/css" media="screen">
	@import "includes/user-settings.css"; /* Social Hub Content Manager User Settings CSS */
</style>
</head>
<body style="overflow-x:hidden;">
<div class="container">
	<div class="row">
		<section>
			<h2 class="setup-title" style="margin-top:10px;"><img src="images/concierge-social-logo.png" /><?PHP echo ($socialmarketing->isPlatinumMember($_SESSION['user_id']))?'<span class="pull-right"><img src="images/platinum-level.png" style="margin-top:-10px;"></span>':''; ?><br/>Setup <small>0% Complete</small></h2>
			<div class="wizard">
				<div class="wizard-inner">
					<div class="connecting-line"></div>
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active" id="tstep1">
							<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="" data-original-title="Accounts" aria-expanded="false">
								<span class="round-tab">
									<i class="glyphicon glyphicon-link"></i>
								</span>
							</a>
						</li>
						<li role="presentation" class="disabled" id="tstep2">
							<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="" data-original-title="Profiles" aria-expanded="false">
								<span class="round-tab">
									<i class="glyphicon glyphicon-user"></i>
								</span>
							</a>
						</li>
						<li role="presentation" class="disabled" id="tstep3">
							<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="" data-original-title="Content" aria-expanded="false">
								<span class="round-tab">
									<i class="glyphicon glyphicon-file"></i>
								</span>
							</a>
						</li>
						<li role="presentation" class="disabled" id="tstep4">
							<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="" data-original-title="Frequency" aria-expanded="false">
								<span class="round-tab">
									<i class="glyphicon glyphicon-dashboard"></i>
								</span>
							</a>
						</li>
						<li role="presentation" class="disabled" id="tstep5">
							<a href="#step5" data-toggle="tab" aria-controls="step5" role="tab" title="" data-original-title="Days" aria-expanded="true">
								<span class="round-tab">
									<i class="glyphicon glyphicon-calendar"></i>
								</span>
							</a>
						</li>
						<li role="presentation" class="disabled" id="tstep6">
							<a href="#step6" data-toggle="tab" aria-controls="step6" role="tab" title="" data-original-title="Time" aria-expanded="true">
								<span class="round-tab">
									<i class="glyphicon glyphicon-time"></i>
								</span>
							</a>
						</li>
						<li role="presentation" class="disabled" id="tstep7">
							<a href="#step7" data-toggle="tab" aria-controls="step7" role="tab" title="" data-original-title="Complete" aria-expanded="true">
								<span class="round-tab">
									<i class="glyphicon glyphicon-ok"></i>
								</span>
							</a>
						</li>
					</ul>
				</div>
				<form role="form">
					<div class="tab-content">
						<div class="tab-pane active" role="tabpanel" id="step1">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class="panel-title">Link Social Networks <i class="glyphicon glyphicon-link pull-right"></i></h3></div>
								<div class="panel-body">
									<p>Add and save the Social Networks you would like us to post to</p>
									<iframe src="https://www.spotlighthometours.com/users/new/social-hub-include.php?hidetabs=1" height="600px" width="120%" frameborder="0" id="connectedNetworksFrame" style="-ms-zoom: 0.85;-moz-transform: scale(0.85);-moz-transform-origin: 0 0;-o-transform: scale(0.85);-o-transform-origin: 0 0;-webkit-transform: scale(0.85);-webkit-transform-origin: 0 0;"></iframe>
								</div>
								<div class="panel-footer clearfix">
									<a class="btn btn-primary disabled next-step pull-right">Continue</a>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<div class="tab-pane" role="tabpanel" id="step2">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class="panel-title">Social Media Profiles <i class="glyphicon glyphicon-user pull-right"></i></h3></div>
								<div class="panel-body">
									<p>Select which Social Media profiles you would like Spotlight to post to. There is an option to have Spotlight send an email</p>
									<div class="user-profiles">
										Loading Social Media profiles, please wait...
										<div class="progress-bar" style="width: 100%"></div>
									</div>
								</div>
								<div class="panel-footer clearfix">
									<a class="btn btn-primary prev-step pull-left">Previous</a>
									<a class="btn btn-primary disabled next-step pull-right">Continue</a>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<div class="tab-pane" role="tabpanel" id="step3">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class="panel-title">Select Content <i class="glyphicon glyphicon-file pull-right"></i></h3></div>
								<div class="panel-body">
									<p>Select the type of content you would like to post to your saved Social Media networks. Please select at least 4 categories to proceed to the next step.</p>
									<div class="well cat-select" style="max-height: 300px;overflow: auto;">
										<ul class="list-group checked-list-box cat-list">
										</ul>
									</div>
									<p class="lead"><strong>Send proof email?</strong> <small>Turn this feature on if you would like Spotlight to send you a proof email before sending out any content:</small> <input type="checkbox" class="toggle-check" id="proofemail" data-toggle="toggle"></p>
								</div>
								<div class="panel-footer clearfix">
									<a class="btn btn-primary prev-step pull-left">Previous</a>
									<a class="btn btn-primary disabled next-step pull-right">Continue</a>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<div class="tab-pane" role="tabpanel" id="step4">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class="panel-title">How many times a week? <i class="glyphicon glyphicon-dashboard pull-right"></i></h3></div>
								<div class="panel-body">
									<p>Select how many times a week you would like the selected content to post to your saved Social Media networks.</p>
									<div class="num-per-week">
										<select name="perweek" class="form-group">
											<option value="0">Select</option>
											<option value="1">1 time a week</option>
											<option value="2">2 times a week</option>
											<option value="3">3 times a week</option>
											<option value="4">4 times a week</option>
										</select>
									</div>
								</div>
								<div class="panel-footer clearfix">
									<a class="btn btn-primary prev-step pull-left">Previous</a>
									<a class="btn btn-primary disabled next-step pull-right">Continue</a>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<div class="tab-pane" role="tabpanel" id="step5">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class="panel-title">Days of the week? <i class="glyphicon glyphicon-calendar pull-right"></i></h3></div>
								<div class="panel-body">
									<p>Select the day(s) of the week that you would like the selected content to post to your saved Social Media networks.</p>
									<div class="weekDays-selector well">
										<input type="checkbox" id="weekday-mon" class="weekday" value="mond" />
										<label for="weekday-mon" data-toggle="tooltip" data-placement="bottom" title="Monday">M</label>
										<input type="checkbox" id="weekday-tue" class="weekday" value="tues" />
										<label for="weekday-tue" data-toggle="tooltip" data-placement="bottom" title="Tuesday">T</label>
										<input type="checkbox" id="weekday-wed" class="weekday" value="wed" />
										<label for="weekday-wed" data-toggle="tooltip" data-placement="bottom" title="Wednesday">W</label>
										<input type="checkbox" id="weekday-thu" class="weekday" value="thurs" />
										<label for="weekday-thu" data-toggle="tooltip" data-placement="bottom" title="Thursday">T</label>
										<input type="checkbox" id="weekday-fri" class="weekday" value="fri" />
										<label for="weekday-fri" data-toggle="tooltip" data-placement="bottom" title="Friday">F</label>
										<input type="checkbox" id="weekday-sat" class="weekday" value="sat" />
										<label for="weekday-sat" data-toggle="tooltip" data-placement="bottom" title="Saturday">S</label>
										<input type="checkbox" id="weekday-sun" class="weekday" value="sun" />
										<label for="weekday-sun" data-toggle="tooltip" data-placement="bottom" title="Sunday">S</label>
									</div>
								</div>
								<div class="panel-footer clearfix">
									<a class="btn btn-primary prev-step pull-left">Previous</a>
									<a class="btn btn-primary disabled next-step pull-right">Continue</a>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<div class="tab-pane" role="tabpanel" id="step6">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class="panel-title">Time of day? <i class="glyphicon glyphicon-time pull-right"></i></h3></div>
								<div class="panel-body">
									<p>Select up to 4 times of the day <span class="text-muted">(MST)</span> you would like the selected content to post to your saved networks. <span class="text-muted">The deafult times set are known to produce the best results: 1:00pm, 3:45pm, 7:00pm and 10:00pm</span></p>
									<div class="form-group date-range">
										<div class="row">
											<div class='col-xs-5'>
												<div class="form-group">
													<div class='input-group date' id='contentScheduleFrom'>
														<input type='text' class="form-control" />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-time"></span>
														</span>
													</div>
												</div>
											</div>
											<div class="col-xs-2">
												&nbsp;
											</div>
											<div class='col-xs-5'>
												<div class="form-group">
													<div class='input-group date' id='contentScheduleTo'>
														<input type='text' class="form-control" />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-time"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group date-range">
										<div class="row">
											<div class='col-xs-5'>
												<div class="form-group">
													<div class='input-group date' id='contentSchedule2From'>
														<input type='text' class="form-control" />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-time"></span>
														</span>
													</div>
												</div>
											</div>
											<div class="col-xs-2">
												&nbsp;
											</div>
											<div class='col-xs-5'>
												<div class="form-group">
													<div class='input-group date' id='contentSchedule2To'>
														<input type='text' class="form-control" />
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-time"></span>
														</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="panel-footer clearfix">
									<a class="btn btn-primary prev-step pull-left">Previous</a>
									<a class="btn btn-primary next-step pull-right" onclick="showStep(7)">Save and Continue</a>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<div class="tab-pane" role="tabpanel" id="step7">
							<div class="panel panel-default">
								<div class="panel-heading"><h3 class="panel-title">Setup Complete! <i class="glyphicon glyphicon-ok pull-right"></i></h3></div>
								<div class="panel-body">
									<h1 class="text-success" style="margin-top:0px;font-size:50px;"><i class="fa fa-check"></i> Setup <small class="text-muted" >100% Complete</small></h1>
									<p class="lead">You can edit any of the setup options at anytime by clicking on the option above.</p>
									<hr>
									<p>Now that your <?PHP echo $membershipName ?> setup is complete the Spotlight system will autopost new and exciting content to your Social Media accounts / profiles. Post will be made according to the saved settings and/or options. All post that link out from your Social Media accounts will go to a page with your branding and content in place. <strong>Your Social Media content page will look something like this:</strong></p>
									<button type="button" class="btn btn-primary" onclick="window.open('http://www.spotlighthometours.com/microsites/content.php?contentID=76&userID=<?PHP echo $_SESSION['user_id']; ?>', '_blank')">Content Preview</button>
									<div class="clear"></div><br/></p>
								</div>
								<div class="panel-footer clearfix">
									<a class="btn btn-primary prev-step pull-left">Previous</a>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
				</form>
			</div>
	   </section>
   </div>
</div>
<!-- Upgrade Modal -->
<div class="modal fade" id="upgradeModal" tabindex="-1" role="dialog" aria-labelledby="upgradeModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="modal-title" id="eCatModalLabel">Upgrade to Concierge Social Platinum</h3>
      </div>
      <div class="modal-body">
      	<div class="alert alert-success" role="alert">
  			
		</div>
      	<div class="col-xs-6">
			<div class="desc">
				<p>This category is for Concierge Social Platinum members only. With Concierge Social Platinum you get full access to personalized categories such as local and specific categories. An example: if you have selected the Sports category as a Concierge Social Platinum member you have the option of selecting and/or entering your favorite team.</p>
				<p>Once you become a Concierge Social Platinum member someone from the Spotlight Home Tours marketing team will be contacting you right away to help you setup and customize categories to fit your specific needs.</p>
				<button type="button" class="btn btn-primary" onclick="upgradeNow()">Get Started</button>
			</div>
     		<div class="card">
     			<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><i class="glyphicon glyphicon-credit-card"></i> Payment</h3>
					</div>
					<div class="panel-body">
						<form id="upgradePaymentForm">
							<div class='form-row'>
							  <div class='col-xs-12 form-group required'>
								<label class='control-label'>Name on Card</label>
								<input class='form-control' size='4' type='text' name="cardname" id="cardname">
							  </div>
							</div>
							<div class='form-row'>
							  <div class='col-xs-12 form-group card required'>
								<label class='control-label'>Card Number</label>
								<input autocomplete='off' class='form-control card-number' size='20' type='text' name="cardnumber" id="cardnumber">
							  </div>
							</div>
							<div class='form-row'>
							  <div class='col-xs-4 form-group expiration required'>
								<label class='control-label'>Expiration</label>
								<input class='form-control card-expiry-month' placeholder='MM' size='2' type='text' name="cardmonth" id="cardmonth">
							  </div>
							  <div class='col-xs-4 form-group expiration required'>
								<label class='control-label'> </label>
								<input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text' name="cardyear" id="cardyear">
							  </div>
							  <div class='col-xs-4'>
								&nbsp;
							  </div>
							</div>
						</form>
					</div>
					<div class="panel-footer">
						<small>*use "Upgrade Now" button below.</small>
					</div>
				</div>
     		</div>
      	</div>
      	<div class="col-xs-6">
			<div class="panel panel-primary platinum-panel">
					<div class="platinum-icon"></div>
					<div class="panel-heading">
						<h3 class="panel-title">Platinum</h3>
					</div>
					<div class="panel-body">
						<div class="the-price">
							<h1>+$100<span class="subscript">/mo</span></h1>
							<small>Premium Categories</small>
						</div>
						<table class="table">
							<tbody>
								<tr class="active">
									<td>
										Localized Categories
									</td>
								</tr>
								<tr>
									<td>
										Personalized Categories
									</td>
								</tr>
								<tr class="active">
									<td>
										Marketing Specialist
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
            </div>
            <div class="clear"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary process-btn" onclick="upgradeNow()">Get Started</button>
      </div>
    </div>
  </div>
</div>
<div id="ajaxMessage"></div>
<!-- SOCIAL HUB CONTENT MANAGER USER SETTINGS JS CONTROL FILES -->
<script src='includes/user-settings.js?rand=<?PHP echo rand(999999,999999999) ?>'></script>
<script>
	membershipName = "<?PHP echo $membershipName; ?>";
	userID = <?PHP echo $_SESSION['user_id']; ?>;
	platinumMember = <?PHP echo ($socialmarketing->isPlatinumMember($_SESSION['user_id']))?'true':'false'; ?>;
	getCategoris();
</script>
</body>
</html>