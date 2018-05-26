var platinumMembID = 23;

/* WIZARD */
//Initialize tooltips
$('.nav-tabs > li a[title]').tooltip();

//Wizard
$('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
     var $target = $(e.target);
     if ($target.parent().hasClass('disabled')) {
         return false;
     }
});
$(".next-step").click(function (e) {
    if($(this).hasClass("disabled")){
		
	}else{
		var $active = $('.wizard .nav-tabs li.active');
    	$active.next().removeClass('disabled');
 		nextTab($active);
	}
});
$(".prev-step").click(function (e) {
    var $active = $('.wizard .nav-tabs li.active');
    prevTab($active);
});
function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}
/* MISC */
var setupComplete = false;
var doneLoadingSettings = true;
var membershipName = 'Social Hub Marketing';
var saving = false;
var userType = 'user';
var userID = 0;
$(parent.window).bind('beforeunload', function(e){
	if(setupComplete){
		return undefined;
	}else{
		var confirmationMessage = "Your "+membershipName+" setup is not complete and has not been saved. Please complete all 6 steps and save your selected settings at the end of step 6 to complete your setup. If you leave this page now you will lose any selected settings / completed steps.";
		(e || window.event).returnValue = confirmationMessage; //Gecko + IE
    	return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
	}
});
var waitingDialog = waitingDialog || (function ($) {
    'use strict';

	// Creating modal dialog's DOM
	var $dialog = $(
		'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:115px; overflow-y:visible;">' +
		'<div class="modal-dialog modal-m">' +
		'<div class="modal-content">' +
			'<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
			'<div class="modal-body">' +
				'<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
			'</div>' +
		'</div></div></div>');

	return {
		/**
		 * Opens our dialog
		 * @param message Custom message
		 * @param options Custom options:
		 * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
		 * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
		 */
		show: function (message, options) {
			// Assigning defaults
			if (typeof options === 'undefined') {
				options = {};
			}
			if (typeof message === 'undefined') {
				message = 'Loading';
			}
			var settings = $.extend({
				dialogSize: 'm',
				progressType: '',
				onHide: null // This callback runs after the dialog was hidden
			}, options);

			// Configuring dialog
			$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
			$dialog.find('.progress-bar').attr('class', 'progress-bar');
			if (settings.progressType) {
				$dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
			}
			$dialog.find('h3').text(message);
			// Adding callbacks
			if (typeof settings.onHide === 'function') {
				$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
					settings.onHide.call($dialog);
				});
			}
			// Opening dialog
			$dialog.modal();
		},
		/**
		 * Closes dialog
		 */
		hide: function () {
			$dialog.modal('hide');
		}
	};

})(jQuery);

/* Save Social Networks */
var stepOneDoneLoading = false;
$('#connectedNetworksFrame').load(function(){
	var isConnectedNetwork = $(this)[0].contentWindow.isConnectedNetwork;
	if(isConnectedNetwork){
		showStep(2);
		if(setupComplete){
			showStep(7);
		}
	}
});

/* Select Social Profiles */
var profiles = [];
function getProfiles(){
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/user_socialhub_getauthprofiles.php",
	  dataType: 'json'
	}).done(function( response ) {
		profiles = response;
		showProfiles();
		stepOneDoneLoading = true;
		if(loadingPopUpShowing&&doneLoadingSettings){
			waitingDialog.hide();
			loadingPopUpShowing = false;
		}
	});
}

var selectedNetworks = new Array();
function showProfiles(){
	var profilesHTML = '<div class="row">';
	$.each(profiles, function(proIndex, profile) {
		profilesHTML += ' \
			<div class="col-xs-6"> \
				<!-- Begin user profile --> \
				<div class="box-info text-center user-profile-2"> \
					<div class="header-cover"> \
						 \
					</div> \
					<div class="user-profile-inner"> \
						<h4 class="white"><a class="btn btn-social-icon btn-xs btn-'+profile.type+'"><i class="fa fa-'+profile.type+'"></i></a> '+profile.name+'</h4> \
						<img src="'+profile.img+'" class="img-circle profile-avatar" alt="User avatar"> \
						<h5>'+profile.caption+'</h5> \
							\
						<!-- User button --> \
						<div class="user-button"> \
							<div class="row"> \
								<input type="checkbox" class="toggle-check" id="profile'+profile.id+'" data-toggle="toggle"> \
							</div> \
						</div> \
					</div> \
				</div> \
			</div> \
		';
	});
	profilesHTML += '</div>';
	$(".user-profiles").html(profilesHTML);
	//console.log(selectedNetworks);
	$(".user-profiles .toggle-check").each(function () {
		var networkID = $(this).attr('id').replace('profile','');
		//console.log("Network ID: "+networkID);
		var index = selectedNetworks.indexOf(networkID);
		//console.log("Network ID Index: "+index);
		if(index>-1){
			$(this).prop('checked', true);
		}
	});
	$('.toggle-check').bootstrapToggle();
	$(".user-profiles .toggle-check").on("change", function(e){
		//console.log(selectedNetworks);
		var networkID = $(this).attr('id').replace('profile','');
		if($(this).is(':checked')){
			selectedNetworks.push(networkID);
		}else{
			var index = selectedNetworks.indexOf(networkID);
			if (index>-1) {
			   selectedNetworks.splice(index, 1);
			}
		}
		if(selectedNetworks.length>0){
			showStep(3);
		}else{
			$("select[name='perweek']").val(0);
			$("select[name='perweek']").trigger("change");
			daysofweek = new Array();
			checkDaysOfWeek();
			restCategories();
			hideStep(4);
			hideStep(5);
			hideStep(6);
			hideStep(7);
			hideStep(3);
		}
	});
}

var proofEmail = 0;
$("#proofemail").on("change", function(e){
	if($(this).is(':checked')){
		proofEmail = 1;
	}else{
		proofEmail = 0;
		forcepost = 0;
		$('#forcepost').bootstrapToggle('off');
	}
	userChangedData();
});

var forcepost = 0;
$("#forcepost").on("change", function(e){
	if($(this).is(':checked')){
		$("#proofemail").bootstrapToggle('on');
		forcepost = 1;
		proofEmail = 1;
	}else{
		forcepost = 0;
	}
	userChangedData();
});


/* Select Categories */
var myListingCatID = 0;
var myBrokerListingCatID = 00;
var minSelectedCat = 4;
var categories = [];
function getCategoris(){
	loadingPopUpShowing = true;
	waitingDialog.show('Loading Saved Settings');
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialcategories.php",
	  dataType: 'json',
	  data: { action: 'get', userType: userType, userID: userID }
	}).done(function( response ) {
		categories = response;
		generateCatMenus();
		sortCategories();
		loadSettings();
	});
}
$('[data-toggle="tooltip"]').tooltip();
var waitSubCats = [];
var addingCatFromSelect = false;
var selectedCategories = new Array();
var mlproof = 0;
var mlonce = 0;
var blproof = 0;
var blonce = 0;
$('#upgradeModal').on('show.bs.modal', function (e) {
	try {
		if(window.top){
			if (window.top.document.querySelector('iframe')) {
				var topPx = window.top.scrollY-300;
				if(topPx<0){
					topPx = 0;
				}
				$('#upgradeModal').css('top', topPx); //set modal position

			}
		}
	}catch(e){
		console.log(e);
	}
});
function generateMyListingOptionsHTML(title){
	var html = ' \
		<ul class="list-group"> \
			<li class="list-group-item cat-options" id="catOptions"> \
				<div class="panel panel-default"> \
					<div class="panel-heading">'+title+' Post Options</div> \
					<div class="panel-body"> \
						<table class="table">  \
							<tbody> \
								<tr> \
									<th scope="row">How to post</th> \
									<td> \
										<select name="postproof"> \
											<option value="0">Auto (new &amp; modified listing)</option> \
											<option value="1">Preview Email</option> \
										</select> \
									</td> \
								</tr> \
								<tr> \
									<th scope="row">Preview Email (if selected)</th> \
									<td> \
										<select name="once"> \
											<option value="0">Everyday</option> \
											<option value="1">According to saved Days</option> \
										</select> \
									</td> \
								</tr> \
							</tbody> \
						</table> \
					</div> \
				</div> \
			</li> \
		</ul> \
	';
	return html;
}

function generateComingSoonHTML(title) {
	var html = ' \
		<ul class="list-group"> \
			<li class="list-group-item cat-options" id="catOptions"> \
				<div class="panel panel-default"> \
					<div class="panel-heading">' + title + ' Coming Soon</div> \
					<div class="panel-body"> \
						<table class="table">  \
							<tbody> \
								<tr> \
									<th scope="row">Coming Soon</th> \
									<td> \
										Exciting New Feature On The Way! \
									</td> \
								</tr> \
							</tbody> \
						</table> \
					</div> \
				</div> \
			</li> \
		</ul> \
	';
	return html;
}

function generateCatMenus(){
	// Bind data to check list
	waitSubCats = [];
	$('.cat-select .cat-list').html('');
	$('.cat-select .cat-list').append('<li id="cat0" class="list-group-item"> My Listings <small>(Spotlight Tour / IDX Feed Required*)</small>'+generateMyListingOptionsHTML("My Listings")+'</li>');
	// $('.cat-select .cat-list').append('<li id="cat00" class="list-group-item"> My Brokerage Listings <small>(IDX Feed Required*)</small>'+generateMyListingOptionsHTML("My Brokerage Listings")+'</li>');
	$('.cat-select .cat-list').append('<li id="cat00" class="list-group-item"> My Brokerage Listings <small>(IDX Feed Required*)</small>'+generateComingSoonHTML("My Brokerage Listings")+'</li>');

	$.each(categories, function(k, v) {
		var membershipID = parseInt(v.membershipID);
		if(v.hidden=="0"){
			var parentIDs = v.parentID.split(",");
			$.each(parentIDs, function(pk, pv) {
				pv = parseInt(pv);
				if(pv>0){
					appendSubCat(k, v, pv);
				}else{
					var additionalClass = '';
					if(membershipID>0){
						if(platinumMember&&(membershipID==platinumMembID)){
							// User is a member so an upgrade is not required!
						}else{
							additionalClass = 'upgrade-required';	
						}
					}
					$('.cat-select > .cat-list').append('<li id="cat'+v.id+'" class="list-group-item '+additionalClass+'"> '+v.name+'<!--<button type="button" class="btn btn-xs btn-info pull-right" data-content-id="'+v.id+'" data-toggle="tooltip" title="example">&nbsp;<i class="glyphicon glyphicon-file" data-content-id="'+v.id+'"></i>&nbsp;</button> --></li>');
				}
			});
		}
	});
	$.each(waitSubCats, function(k, v) {
		var parentIDs = v.parentID.split(",");
		$.each(parentIDs, function(pk, pv) {
			appendSubCat(k, v, pv);
		});
	});
	
	$("#cat00 select[name='postproof']").on("change", function(){
		blproof = $(this).val();
		userChangedData();
	});
	$("#cat00 select[name='once']").on("change", function(){
		blonce = $(this).val();
		userChangedData();
	});
	$("#cat0 select[name='postproof']").on("change", function(){
		mlproof = $(this).val();
		userChangedData();
	});
	$("#cat0 select[name='once']").on("change", function(){
		mlonce = $(this).val();
		userChangedData();
	});
	
	/* build cat check list styles, controls, listeners */
    $('.list-group.checked-list-box .list-group-item').each(function(e) {
		// Settings
		var $widget = $(this),
			$checkbox = $('<input type="checkbox" class="hidden" />'),
			color = ($widget.data('color') ? $widget.data('color') : "info"),
			style = ($widget.data('style') == "button" ? "btn-" : "list-group-item-"),
			settings = {
				on: {
					icon: 'glyphicon glyphicon-check'
				},
				off: {
					icon: 'glyphicon glyphicon-unchecked'
				}
			};

		$widget.css('cursor', 'pointer')
		$widget.append($checkbox);

		// Event Handlers
		$widget.on('click', function (event) {
			if($(this).hasClass('cat-options')||$(this).parent().hasClass('cat-options')){
				return false;
			}else{
				if($(event.target).attr('type')=="button"||$(event.target).hasClass('glyphicon-file')){
					var catID = $(event.target).data('content-id');
					window.open('http://www.spotlighthometours.com/microsites/content.php?catID='+catID+'&userID='+userID, '_blank')
					return false;
				};
				if($(this).parents('.upgrade-required').length||$(this).hasClass('upgrade-required')){
					if (event.originalEvent) {
						$(this).effect( "shake", function(){
							$('#upgradeModal').modal();
						});
					}
					return false;
				}
				if(!checkingSelectedCategories){
					if($(this).hasClass('active')){
						$(this).find(".list-group-item.active").trigger("click");
					}else{
						$(this).find(".list-group-item").not(".active").not(".cat-options").trigger("click");
					}
				}
				$checkbox.prop('checked', !$checkbox.is(':checked'));
				$checkbox.triggerHandler('change');
				updateDisplay();
				var counter = 0;
				$(".checked-list-box li").not(".cat-options").each(function(idx, li) {
					var categoryID = $(this).attr('id').replace('cat', '');
					var selectedCategoryIndex = selectedCategories.indexOf(categoryID);
					if($(this).hasClass('active')){
						if(selectedCategoryIndex>=0){
						}else{
							selectedCategories.push(categoryID);
						}
						// If the categoryID is my listing or my broker listing do not add to the count for min selected categories
						if(parseInt(categoryID)!==parseInt(myListingCatID)&&parseInt(categoryID)!==parseInt(myBrokerListingCatID)){
							counter++;
						}
						$(this).parent().css('display','block');
					}else{
						if(selectedCategoryIndex>=0){
							selectedCategories.splice(selectedCategoryIndex, 1);	
						}
					}
				});
				if(counter>=minSelectedCat){
					showStep(4);	
				}else{
					hideStep(4);
					$("select[name='perweek']").val(0);
					$("select[name='perweek']").trigger("change");
					daysofweek = new Array();
					checkDaysOfWeek();
					hideStep(6);
				}
				return false;
			}
		});
		$checkbox.on('change', function () {
			updateDisplay();
		});

		// Actions
		function updateDisplay() {
			var isChecked = $checkbox.is(':checked');

			// Set the button's state
			$widget.data('state', (isChecked) ? "on" : "off");

			// Set the button's icon
			$widget.find('.state-icon').first()
				.removeClass()
				.addClass('state-icon ' + settings[$widget.data('state')].icon);

			// Update the button's color
			if (isChecked) {
				$widget.addClass(style + color + ' active');
			} else {
				$widget.removeClass(style + color + ' active');
			}
		}

		// Initialization
		function init() {

			if ($widget.data('checked') == true) {
				$checkbox.prop('checked', !$checkbox.is(':checked'));
			}

			updateDisplay();

			// Inject the icon if applicable
			if ($widget.find('.state-icon').length == 0) {
				$widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span>');
			}
		}
		init();
    });
	$('[data-toggle="tooltip"]').tooltip();
}

function appendSubCat(k, v, parentID){
	var membershipID = parseInt(v.membershipID);
	var additionalClass = '';
	if(membershipID>0){
		if(platinumMember&&(membershipID==platinumMembID)){
			// User is a member so an upgrade is not required!
		}else{
			additionalClass = 'upgrade-required';	
		}
	}
	if($('.cat-select .cat-list #cat'+parentID+' ul').length){
		if($('.cat-select .cat-list #cat'+parentID+' > ul > #cat'+v.id).length){
		}else{
			$('.cat-select .cat-list #cat'+parentID+' > ul').append('<li id="cat'+v.id+'" class="list-group-item '+additionalClass+'"> '+v.name+' <!-- <button type="button" class="btn btn-xs btn-info pull-right" data-content-id="'+v.id+'" data-toggle="tooltip" title="example">&nbsp;<i class="glyphicon glyphicon-file" data-content-id="'+v.id+'"></i>&nbsp;</button> --></li>');
		}
	}else{
		if($('.cat-select .cat-list #cat'+parentID).length){
			if($('.cat-select .cat-list #cat'+parentID+' > ul > #cat'+v.id).length){
			}else{
				$('.cat-select .cat-list #cat'+parentID).append('<ul class="list-group checked-list-box cat-list"></ul>');
				$('.cat-select .cat-list #cat'+parentID+' ul').append('<li id="cat'+v.id+'" class="list-group-item '+additionalClass+'"> '+v.name+' <!-- <button type="button" class="btn btn-xs btn-info pull-right" data-content-id="'+v.id+'" data-toggle="tooltip" title="example">&nbsp;<i class="glyphicon glyphicon-file" data-content-id="'+v.id+'"></i>&nbsp;</button> --></li>');
			}
		}else{
			waitSubCats.push(v);
		}		
	}
}

function sortCategories(){
	$('.cat-select ul').each(function(){
    	var mylist = $(this);
		var listitems = $('> li', mylist).get();
		listitems.sort(function(a, b) {
			var compA = $(a).text().toUpperCase();
			var compB = $(b).text().toUpperCase();
			return (compA < compB) ? -1 : 1;
		});
		$.each(listitems, function(i, itm) {
			mylist.append(itm);
		});
	});
}

function restCategories(){
	$(".list-group.checked-list-box .list-group-item").each(function () {
		if($.inArray($(this).attr('id').replace('cat', ''), selectedCategories)>=0){
			$(this).trigger("click");
		}
	});
	selectedCategories = new Array();
}

var checkingSelectedCategories = false;
function checkSelectedCategories(){
	checkingSelectedCategories = true;
	var objList = new Array();
	$(".list-group.checked-list-box .list-group-item").each(function () {
		if($.inArray($(this).attr('id').replace('cat', ''), selectedCategories)>=0){
			objList.push($(this));
		}
	});
	$.each(objList, function( index, value ) {
  		value.trigger("click");
	});
	$("#cat00 select[name='postproof']").val(blproof);
	$("#cat00 select[name='once']").val(blonce);
	$("#cat0 select[name='postproof']").val(mlproof);
	$("#cat0 select[name='once']").val(mlonce);
	checkingSelectedCategories = false;
}

/* How man times a week */
var perweek = 0;
var daysofweek = new Array();
$("select[name='perweek']").on('change', function(){
	perweek = $(this).val();
	if(perweek>0){
		if(perweek<daysofweek.length){
			var howManyLess = daysofweek.length - perweek;
			daysofweek.splice((daysofweek.length-howManyLess),howManyLess);
			checkDaysOfWeek();
		}
		if(daysofweek.length==perweek){
		
		}else{
			hideStep(7);
			hideStep(6);
		}
		showStep(5);
	}else{
		hideStep(5);
		daysofweek = new Array();
		checkDaysOfWeek();
		hideStep(6);
	}
})

/* Days of the week */
$(".weekday").on("change", function(){
	if ($(this).is(':checked')) {
		if(daysofweek.length<perweek){
			daysofweek.push($(this).val());
		}else{
			daysofweek.splice(-1,1);
			daysofweek.push($(this).val());
			checkDaysOfWeek();
		}
	}else{
		var index = daysofweek.indexOf($(this).val());
		daysofweek.splice(index, 1);
	}
	if(daysofweek.length==perweek){
		showStep(6);
		showStep(7);
		return false;
	}else{
		hideStep(7);
		hideStep(6);
	}
})

function checkDaysOfWeek(){
	$(".weekDays-selector input").each(function () {
		if($.inArray($(this).val(), daysofweek)==-1){
			$(this).prop('checked', false);
		}else{
			$(this).prop('checked', true);
		}
	});
}

/* Time of day */
var scheduleFrom = '';
var scheduleTo = '';
var schedule2From = '';
var schedule2To = '';
$('#contentScheduleFrom').datetimepicker({format: 'LT'});
$('#contentScheduleTo').datetimepicker({
	useCurrent: false, //Important! See issue #1075
	format: 'LT'
});
$("#contentScheduleFrom").on("dp.change", function (e) {
    if(e.date){
		scheduleFrom = $(this).data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
	}
	$('#contentScheduleTo').data("DateTimePicker").minDate(e.date);
});
$("#contentScheduleTo").on("dp.change", function (e) {
    if(e.date){
		scheduleTo = $(this).data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
	}
	$('#contentScheduleFrom').data("DateTimePicker").maxDate(e.date);
});
$('#contentSchedule2From').datetimepicker({format: 'LT'});
$('#contentSchedule2To').datetimepicker({
	useCurrent: false, //Important! See issue #1075
	format: 'LT'
});
$("#contentSchedule2From").on("dp.change", function (e) {
    if(e.date){
		schedule2From = $(this).data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
	}
	$('#contentSchedule2To').data("DateTimePicker").minDate(e.date);
});
$("#contentSchedule2To").on("dp.change", function (e) {
    if(e.date){
		schedule2To = $(this).data("DateTimePicker").date().format("YYYY-MM-DD HH:mm:ss");
	}
	$('#contentSchedule2From').data("DateTimePicker").maxDate(e.date);
});

// Set schedule default / user selection
$("#contentScheduleTo").data("DateTimePicker").date("15:45:00");
$("#contentScheduleFrom").data("DateTimePicker").date("13:00:00");
$("#contentSchedule2To").data("DateTimePicker").date("22:00:00");
$("#contentSchedule2From").data("DateTimePicker").date("19:00:00");

/* Misc Controls */
function showStep(step){
	$("#step"+(step-1)+" .next-step").removeClass("disabled");
	$(".wizard .nav #tstep"+step).removeClass("disabled");
	if(allStepsGreen()){
		setupComplete = true;
		$('.setup-title small').html("100% Complete");
	}
	if(!setupComplete){
		var perPerStep = 14.2857142857;
		var percentDone = Math.round(perPerStep*step)+'%';
		$('.setup-title small').html(percentDone+" Complete");
	}
	if(step==7){
		setupComplete = true;
	}
	if(step==2){
		getProfiles();
	}
	userChangedData();
}

function allStepsGreen(){
	$('.wizard-inner li').each(function(){
		if($(this).hasClass('disabled')){
			return false;
		}
	});
	return true;
}

function hideStep(step){
	$("#step"+(step-1)+" .next-step").addClass("disabled");
	$(".wizard .nav #tstep"+step).addClass("disabled");
	if(doneLoadingSettings){
		var perPerStep = 14.2857142857;
		var percentDone = Math.round(perPerStep*(step-1))+'%';
		$('.setup-title small').html(percentDone+" Complete");
	}
	setupComplete = false;
}

var loadingPopUpShowing = false;
function loadSettings(){
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialcontent-marketing-settings.php",
	  dataType: 'json',
	  data: { }
	}).done(function( response ) {
		selectedCategories = response.categories.split(",");
		if(response.networks){
			selectedNetworks = response.networks.split(",");
		}
		getProfiles();
		doneLoadingSettings = false;
		mlonce = response.mlonce;
		mlproof = response.mlproof;
		blonce = response.blonce;
		blproof = response.blproof;
		checkSelectedCategories();
		setTimeout(function () {
			daysofweek = response.days.split(",");
			perweek = response.perweek;
			scheduleFrom = response.scheduleFrom;
			scheduleTo = response.scheduleTo;
			schedule2From = response.schedule2From;
			schedule2To = response.schedule2To;
			proofEmail = response.proofEmail;
			if(proofEmail=="1"){
				$("#proofemail").prop('checked', true);
				$("#proofemail").bootstrapToggle('on');
			}
			forcepost = response.forcepost;
			if(forcepost=="1"){
				$("#forcepost").prop('checked', true);
				$("#forcepost").bootstrapToggle('on');
			}
			$("#contentScheduleTo").data("DateTimePicker").date(scheduleTo);
			$("#contentScheduleFrom").data("DateTimePicker").date(scheduleFrom);
			$("#contentSchedule2To").data("DateTimePicker").date(schedule2To);
			$("#contentSchedule2From").data("DateTimePicker").date(schedule2From);
			$("select[name='perweek']").val(perweek);
			$("select[name='perweek']").trigger("change");
			checkDaysOfWeek();
			if(perweek>0){
				if(daysofweek.length==perweek){
					showStep(3);
					showStep(6);
					showStep(7);
					$("#tstep1, #step1").removeClass("active");
					$("#tstep7, #step7").addClass("active");
					$("#tstep7, #step7").removeClass("disabled");
				}
			}
			if(stepOneDoneLoading){
				waitingDialog.hide();
				loadingPopUpShowing = false;
			}
		}, 500);
		setTimeout(function () {
			if(loadingPopUpShowing&&stepOneDoneLoading){
				waitingDialog.hide();
				loadingPopUpShowing = false;
			}
			if(!setupComplete){
				waitingDialog.hide();
				loadingPopUpShowing = false;
			}
			doneLoadingSettings = true;
		}, 5000);
	});
}

function saveSettings(){
	//console.log('Saving settings!');
	if(selectedNetworks.length>0){
		saving = true;
		waitingDialog.show('Saving');
		$.ajax({
		  method: "POST",
		  url: "../../repository_queries/socialcontent-marketing-settings.php",
		  dataType: 'json',
		  data: { categories: selectedCategories.join(","), days: daysofweek.join(","), perweek: perweek, scheduleFrom: scheduleFrom, scheduleTo: scheduleTo, schedule2From: schedule2From, schedule2To: schedule2To, networks: selectedNetworks.join(","), proofEmail: proofEmail, forcepost: forcepost, mlproof: mlproof, mlonce: mlonce, blproof: blproof, blonce: blonce}
		}).done(function( response ) {
			waitingDialog.hide();
			saving = false;
		});
		//console.log('Saved networks! '+selectedNetworks.join(","));
	}
}

/* UPGRADE MODAL */
var platinumMember = false;
var collectingCard = false;
$( "#upgradePaymentForm" ).validate({
	rules: {
		cardname: {
			required: true
		},
		cardnumber: {
			required: true,
			creditcard: true
		},
		cardmonth: {
			required: true,
      		digits: true,
			minlength: 2
		},
		cardyear: {
			required: true,
      		digits: true,
			minlength: 4
		}
	}
});
function upgradeNow(){
	if(platinumMember){
		// The user has already upgraded so lets close the modal window. They are done!
		$("#upgradeModal").modal('toggle');
		return false;
	}
	if(!collectingCard){
		// This is the first click on the button (Get Started). Lets show the payment form and get them started with the upgrade.
		collectingCard = true;
		$("#upgradeModal .desc").slideToggle('fast', function(){
			$("#upgradeModal .card").slideToggle('slow');
		});
		$("#upgradeModal .process-btn").html("Upgrade Now");
	}else{
		// Set data vars needed to process this order
		var cardName = $("#upgradeModal input[name='cardname']").val();
		var cardNumber = $("#upgradeModal input[name='cardnumber']").val();
		var cardMonth = $("#upgradeModal input[name='cardmonth']").val();
		var cardYear = $("#upgradeModal input[name='cardyear']").val();
		// Check if the form data is valid and ready to go
		var isValid = $( "#upgradePaymentForm" ).valid();
		if(!isValid){
			// The card information is not valid. Using the JQuery validate method a message will be shown to the user when checking if the form is valid above.
		}else{
			// Show loader while we process the card and create membership etc.
			$("#upgradeModal .alert").removeClass('alert-danger').addClass('alert-success');
			$("#upgradeModal .alert").html('<strong>Processing Payment, Please wait!</strong> <br/><br/> <div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>');
			$("#upgradeModal .alert").fadeIn('slow');
			// Make Payment and create memberhip on success. Send the data to process the card and create the membership...
			$.ajax({
			  method: "POST",
			  url: "../../repository_queries/socialmark-upgrade.php",
			  dataType: 'json',
			  data: { nameOnCard: cardName, cardMonth: cardMonth, cardYear: cardYear, cardNumber: cardNumber }
			}).done(function( response ) {
				var status = response.status;
				if(status == "success"){
					// Payment processed and user upgraded!
					// Show Success Message
					$("#upgradeModal .col-xs-6").fadeOut('slow');
					$("#upgradeModal .alert").removeClass('alert-danger').addClass('alert-success');
					$("#upgradeModal .alert").html("<strong>Upgrade Complete!</strong> You are now a Concierge Social Platinum member! A marketing specialist at Spotlight Home Tours will be contacting you shortly.");
					$("#upgradeModal .alert").fadeIn('slow');
					// Set platinum member status to true
					platinumMember = true;
					// Change button on modal to say complete. Since we set member status to true when clicked will close modal window.
					$("#upgradeModal .process-btn").html("Upgrade Complete!");
					// Remove upgrade required from the categories allowing the user to select them now.
					$(".list-group-item").removeClass('upgrade-required');
				}else{
					// There's errors lets output them for the user
					$("#upgradeModal .alert").removeClass('alert-success').addClass('alert-danger');
					$("#upgradeModal .alert").html("<strong>There was an error processing your payment:</strong> "+response.errors);
					$("#upgradeModal .alert").fadeIn('slow');
				}
			});
		}
	}
}

function userChangedData(){
	if(setupComplete&&doneLoadingSettings&&allStepsGreen()){
		saveSettings();
	}
}