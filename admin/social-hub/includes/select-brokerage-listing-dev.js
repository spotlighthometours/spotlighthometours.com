// JavaScript Document
var memberID = 0;

/* NETWORK TYPE */
var networkType = 'facebook';
function setNetwork(network){
	$(".container .btn-"+networkType).removeClass('active');
	$(".container .btn-"+network).addClass('active');
	$(".saved-content .panel").each(function () {
		var networkonly = $(this).data('networkonly');
		if(networkonly){
			var allowedNetworks = networkonly.split(",");
			if(jQuery.inArray(network, allowedNetworks)>-1){
				$(this).removeClass("facebook twitter linkedin instagram pinterest").addClass(network);
			}else{
				$(this).find(".btn-"+allowedNetworks[allowedNetworks.length-1]).addClass('active');
			}
		}else{
			$(this).removeClass("facebook twitter linkedin instagram pinterest").addClass(network);
		}
	});
	networkType = network;
}
var cNetworkType = 'facebook';
function setCNetwork(network){
	$("#cContentModal .preview").removeClass("facebook twitter linkedin instagram pinterest").addClass(network);
	$("#cContentModal .btn-"+cNetworkType).removeClass('active');
	$("#cContentModal .btn-"+network).addClass('active');
	$(".content-preview .preview .caption").html(getCNetworkCaption(network));
	cNetworkType = network;
}
function setItemNetwork(itemObj, network){
	if($(itemObj).parent().parent().attr('class')){
		var parentClasses = $(itemObj).parent().parent().attr('class');
		var currentNetwork = parentClasses.split(" ");
		currentNetwork = currentNetwork[currentNetwork.length-1];
		$(itemObj).parent().parent().removeClass(currentNetwork);
		$(itemObj).parent().find(".btn-"+currentNetwork).removeClass('active');
		$(itemObj).parent().parent().addClass(network);
		$(itemObj).parent().find(".btn-"+network).addClass('active');
	}
}

function ajaxMessage(message, type){
	$('#ajaxMessage').find('div').fadeOut('slow', function() {
		$(this).remove();
	 });
	$('#ajaxMessage').prepend('<div class="ajaxMessage '+type+'">'+message+'</div>');
	$('#ajaxMessage .ajaxMessage.'+type).fadeIn('slow');
	if(type=="success"){
		$('#ajaxMessage .ajaxMessage').delay(2000).fadeOut('slow', function() {
			$(this).remove();
		});
	}
}

/* GET CONTENT */
function getListings(){
	console.log('Entered getListings() in select-brokerage-listing-dev.js');
	console.log('Entering AJAX Call');
	ajaxMessage('Loading Listings, Please Wait (this may take a moment)...', 'processing');
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialmark-get-brokerage-listings-list-dev.php",
	  dataType: 'json',
	  data: { userID: userID }
	}).done(function( response ) {
		console.log("Listings array brought in");
		ajaxMessage('Listings Loaded!', 'success');
		generateListingPreview(response);
		console.log("Completed AJAX Call");
	});
}

var mlsID = 0;
var customCotent = Array();
function generateListingPreview(response) {
	var results = response;
	console.log('Results Array: ');
	console.log(results);

	var listingItemHTML = '';
		listingItemHTML += ' \
		<div id="carouselControls" class="carousel slide" data-ride="carousel" data-interval="false"> \
		  <div class="carousel-inner"> \
		';
			var carouselCount = 1;
			$.each(results, function(row, cols) {	
				if(carouselCount == 1){
					var irow = 'active';
					mlsID = cols.id;
				}else{
					var irow = '';
				}
				var title = (cols.previewtitle==null?"":cols.previewtitle);
				var desc = (cols.previewdesc==null?"":cols.previewdesc);
				var domain = (cols.previewdomain==null?"":cols.previewdomain);
				var cover = (cols.previewcover==null?"":cols.previewcover);
				var caption = (cols.caption==null?"":cols.caption);
				var network = "facebook";
				
				listingItemHTML += ' \
				<div class="item '+irow+'" id="'+cols.id+'"> \
					<div  id="content'+cols.id+'"> \
						<div class="panel panel-default '+cols.previewtype+' '+network+'"> \
							<!-- CONTENT ITEM HEADER --> \
							<div class="panel-heading clearfix"> \
							  <h4 class="panel-title">MLS ID# '+cols.id+'</h4> \
							</div> \
							<!-- CONTENT ITEM BODY --> \
							<div class="panel-body"> \
							   <div class="caption ttip">'+caption+'</div> \
							   <div class="image-wrapper"> \
									<div class="play-icon"></div> \
									<div class="imgframe"><a href="http://www.spotlighthometours.com/microsites/agent-listing.php?mlsID='+cols.id+'&propertyType=residential&listno=undefined&userID='+userID+'" target="_blank"><img src="'+cover+'" /></a></div> \
									<h2><a href="http://www.spotlighthometours.com/microsites/agent-listing.php?mlsID='+cols.id+'&propertyType=residential&listno=undefined&userID='+userID+'" target="_blank">'+title+'</a></h2> \
									<p>'+desc+'</p> \
									<a href="" class="domain">'+domain+'</a> \
								</div> \
							</div> \
							<!-- CONTENT ITEM FOOTER --> \
							<div class="panel-footer"> \
					';
					listingItemHTML += ' \
									<a class="btn btn-social-icon btn-xs btn-facebook active" onclick="setItemNetwork(this, \'facebook\')"><span class="fa fa-facebook"></span></a> \
									<a class="btn btn-social-icon btn-xs btn-linkedin" onclick="setItemNetwork(this, \'linkedin\')"><span class="fa fa-linkedin"></span></a> \
									<a class="btn btn-social-icon btn-xs btn-twitter" onclick="setItemNetwork(this, \'twitter\')"><span class="fa fa-twitter"></span></a> \
									<a class="btn btn-social-icon btn-xs btn-instagram" onclick="setItemNetwork(this, \'instagram\')"><span class="fa fa-instagram"></span></a> \
									<a class="btn btn-social-icon btn-xs btn-pinterest" onclick="setItemNetwork(this, \'pinterest\')"><span class="fa fa-pinterest"></span></a> \
					';
					listingItemHTML += ' \
							</div> \
						</div> \
					</div> \
				</div> \
				';
			
				carouselCount++;
			});
		
		listingItemHTML += ' \
		  </div> \
		  <!-- Controls --> \
		  <a class="left carousel-control" href="#carouselControls" role="button" data-slide="prev"> \
			<span class="glyphicon glyphicon-chevron-left"></span> \
		  </a> \
		  <a class="right carousel-control" href="#carouselControls" role="button" data-slide="next"> \
			<span class="glyphicon glyphicon-chevron-right"></span> \
		  </a> \
		</div> \
		</div> \
		';	
	
	$(".saved-content").html(listingItemHTML);
	$('.carousel').carousel();
	$('.carousel').on('slide.bs.carousel', function (ev) {
		mlsID = ev.relatedTarget.id;
	});
	$("img").error(function () {
		if($(this).attr("src").indexOf('../../repository_queries/proxy.php?url=')>-1){

		}else{
			$(this).attr("src", "../../repository_queries/proxy.php?url="+$(this).attr("src"));
		}
	});
	/* EDIT POST TEXT */
	$('.caption').editable({
		type: 'textarea',
		mode: 'inline',
		rows: 2,
		title: 'Post text',
		placement: 'bottom',
		success: function(response, newValue) {
        	var id = $(this).parent().parent().parent().parent().attr('id');
			customCotent[id] = newValue;
    	}
	});
	$('.editable').on('shown', function(e, editable) {
		editable.input.postrender = function() {
			editable.input.$input.select();
			$(".editable-input textarea").get(0).selectionStart=0;
			$(".editable-input textarea").get(0).selectionEnd=999;
		};
		$(".editable-input textarea").on("blur", function(){
			$('.editableform').editable().submit();
		});
	});
}

function postListing(){
	$("body").html('<div class="alert alert-info" style="width:600px;margin:auto;margin-top:50px;"><span class="glyphicon glyphicon-info-sign"></span> <strong>Creating Post...</strong><hr class="message-inner-separator"><p>We are creating your post now, please wait. This may take a minute or two.</p><br/><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">Creating Post..</div></div></div>');
	var subData = {
		memberID: memberID, 
		mlsID: mlsID,
		selectedNetworks: selectedNetworks
	};
	if(customCotent[mlsID]){
		subData.customContent = customCotent[mlsID];
	}
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialmark-post-listing.php",
	  dataType: 'json',
	  data: subData
	}).done(function( response ) {
		console.log('DONE');
		$("body").html('<div class="alert alert-success" style="width:600px;margin:auto;margin-top:50px;"><span class="glyphicon glyphicon-ok"></span> <strong>Post Created!</strong><hr class="message-inner-separator"><p>We have created the post as requested! Please check your Social Media networks to view your post.</p></div>');
	});
}

/* ADDED 11/02/2017 */
var selectedNetworks = {};
function getUserSelectedNetworks(){
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialmark-user-selected-networks.php",
	  dataType: 'json',
	  data: {userID: userID}
	}).done(function( response ) {
		selectedNetworks = response.split(",");
		console.log(selectedNetworks);
		getProfiles();
	});
}
/* Select Social Profiles */
var profiles = [];
function getProfiles(){
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/user_socialhub_getauthprofiles.php",
	  dataType: 'json',
	  data: {userID: userID}
	}).done(function( response ) {
		profiles = response;
		removeDeletedNetworks();
		console.log(selectedNetworks);
		showProfiles();
	});
}

function isInArray(value, array) {
  return array.indexOf(value) > -1;
}

var profilesArray = Array();
var profilesOn = Array();
var profilesOff = Array();
function removeDeletedNetworks(){
	$.each(profiles, function(proIndex, profile) {
		profilesArray.push(profile.id);
	});
	$.each(selectedNetworks, function(snIndex, networkID) {
		if(networkID){
			if(!isInArray(networkID, profilesArray)){
				console.log(networkID+' is a deleted profile?');
				selectedNetworks[selectedNetworks.indexOf(networkID)] = null;
			}
		}
	});
	var selectedNetworksNew = [];
	for (var i = 0; i < selectedNetworks.length; i++) {
		if (selectedNetworks[i] !== "" && selectedNetworks[i] !== null) {
			selectedNetworksNew.push(selectedNetworks[i]);
		}
	}
	selectedNetworks = selectedNetworksNew;
	$.each(profiles, function(proIndex, profile) {
		if(isInArray(profile.id, selectedNetworks)){
			profilesOn.push(profile);
		}else{
			profilesOff.push(profile);
		}
	});
}

function showProfiles(){
	var profilesHTML = '<div class="row">';
	var rowCount = 0;
	profilesHTML += '<h4>Currently On</h4>';
	$.each(profilesOn, function(proIndex, profile) {
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
		rowCount++;
		if(rowCount>1){
			profilesHTML += ' \
			<div style="clear:both"></div> \
			';
			rowCount = 0;
		}
	});
	rowCount = 0;
	profilesHTML += ' \
			<div style="clear:both"></div> \
	';
	profilesHTML += '<h4>Currently Off <small>(other networks available for this post)</small></h4>';
	$.each(profilesOff, function(proIndex, profile) {
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
		rowCount++;
		if(rowCount>1){
			profilesHTML += ' \
			<div style="clear:both"></div> \
			';
			rowCount = 0;
		}
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
		console.log(selectedNetworks);
	});
}

function selectNetworks(){
	$('#userProfilesModal').modal();
}