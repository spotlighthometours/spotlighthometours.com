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
function getContent(){
	ajaxMessage('Loading Content...', 'processing');
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialmark-get-content-list.php",
	  dataType: 'json',
	  data: { memberID: memberID }
	}).done(function( response ) {
		ajaxMessage('Content Loaded!', 'success');
		generateContentPreview(response);
	});
}


var contentID = 0;
var customCotent = Array();
function generateContentPreview(response){
	var results = response;
	var contentItemHTML = '';
		contentItemHTML += ' \
		<div id="carouselControls" class="carousel slide" data-ride="carousel" data-interval="false"> \
		  <div class="carousel-inner"> \
		';
			var carouselCount = 1;
			$.each(results, function(row, cols) {	
			
				if(carouselCount == 1){
					var irow = 'active';
					contentID = cols.id;
				}else{
					var irow = '';
				}
				var title = (cols.previewtitle==null?"":cols.previewtitle);
				var desc = (cols.previewdesc==null?"":cols.previewdesc);
				var domain = (cols.previewdomain==null?"":cols.previewdomain);
				var cover = (cols.previewcover==null?"":cols.previewcover);
				var caption = (cols.caption==null?"":cols.caption);
				var network = "facebook";
				var networkOnly = "";
				if(cols.networkType!=="all"){
					var allowedNetworks = cols.networkType.split(",");
					network = allowedNetworks[allowedNetworks.length - 1];
					networkOnly = cols.networkType;
				}
				
				contentItemHTML += ' \
				<div class="item '+irow+'" id="'+cols.id+'"> \
					<div  id="content'+cols.id+'"> \
						<div class="panel panel-default '+cols.previewtype+' '+network+'" data-networkonly="'+networkOnly+'"> \
							<!-- CONTENT ITEM HEADER --> \
							<div class="panel-heading clearfix"> \
							  <h4 class="panel-title">Post Preview '+carouselCount+'</h4> \
							</div> \
							<!-- CONTENT ITEM BODY --> \
							<div class="panel-body"> \
							   <div class="caption ttip">'+caption+'</div> \
							   <div class="image-wrapper"> \
									<div class="play-icon"></div> \
									<div class="imgframe"><a href="http://www.spotlighthometours.com/microsites/content.php?contentID='+cols.id+'&userID='+userID+'" target="_blank"><img src="'+cover+'" /></a></div> \
									<h2><a href="http://www.spotlighthometours.com/microsites/content.php?contentID='+cols.id+'&userID='+userID+'" target="_blank">'+title+'</a></h2> \
									<p>'+desc+'</p> \
									<a href="" class="domain">'+domain+'</a> \
								</div> \
							</div> \
							<!-- CONTENT ITEM FOOTER --> \
							<div class="panel-footer"> \
								<strong>Rate this content:</strong> \
								<span class="pull-right"> \
									<i class="glyphicon glyphicon-thumbs-up" title="Like"></i> <div class="like-count">'+cols.liked+'</div> \
									<i class="glyphicon glyphicon-thumbs-down" title="Dislike"></i> <div class="dislike-count">'+cols.disliked+'</div> \
								</span> \
								<div class="clear"></div> \
							</div> \
							<div class="panel-footer"> \
					';
					if(cols.networkType!=="all"){
						$.each(allowedNetworks, function(anindex, anetwork) {
							var active = '';
							if(anetwork==network){
								active = 'active';
							}
							contentItemHTML += ' \
										<a class="btn btn-social-icon btn-xs btn-'+anetwork+' '+active+'" onclick="setItemNetwork(this, \''+anetwork+'\')"><span class="fa fa-'+anetwork+'"></span></a> \
							';
						});
					}else{
						contentItemHTML += ' \
									<a class="btn btn-social-icon btn-xs btn-facebook active" onclick="setItemNetwork(this, \'facebook\')"><span class="fa fa-facebook"></span></a> \
									<a class="btn btn-social-icon btn-xs btn-linkedin" onclick="setItemNetwork(this, \'linkedin\')"><span class="fa fa-linkedin"></span></a> \
									<a class="btn btn-social-icon btn-xs btn-twitter" onclick="setItemNetwork(this, \'twitter\')"><span class="fa fa-twitter"></span></a> \
									<a class="btn btn-social-icon btn-xs btn-instagram" onclick="setItemNetwork(this, \'instagram\')"><span class="fa fa-instagram"></span></a> \
									<a class="btn btn-social-icon btn-xs btn-pinterest" onclick="setItemNetwork(this, \'pinterest\')"><span class="fa fa-pinterest"></span></a> \
						';
					}
					contentItemHTML += ' \
							</div> \
						</div> \
					</div> \
				</div> \
				';
			
				carouselCount++;
			});
		
		contentItemHTML += ' \
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
	
	$(".saved-content").html(contentItemHTML);
	$('.carousel').carousel();
	$('.carousel').on('slide.bs.carousel', function (ev) {
		contentID = ev.relatedTarget.id;
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
	$(".saved-content .panel-footer i.glyphicon-thumbs-up").on("click", function(){
		var contentID = $(this).parent().parent().parent().parent().attr('id').replace("content","");
		$(this).parent().find(".like-count").html("1");
		$(this).parent().find(".dislike-count").html("0");
		saveContentLike(contentID);
	});
	$(".saved-content .panel-footer i.glyphicon-thumbs-down").on("click", function(){
		var contentID = $(this).parent().parent().parent().parent().attr('id').replace("content","");
		$(this).parent().find(".like-count").html("0");
		$(this).parent().find(".dislike-count").html("1");
		saveContentDislike(contentID);
	});
}

function saveContentLike(contentID){
	ajaxMessage('Saving like...', 'processing');
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialcontentlikes.php",
	  dataType: 'json',
	  data: { userID: userID, action: 'like', contentID: contentID }
	}).done(function( response ) {
		ajaxMessage('Like Saved!', 'success');
	});
}
function saveContentDislike(contentID){
	ajaxMessage('Saving dislike...', 'processing');
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialcontentlikes.php",
	  dataType: 'json',
	  data: { userID: userID, action: 'dislike', contentID: contentID }
	}).done(function( response ) {
		ajaxMessage('Dislike Saved!', 'success');
	});	
}

function postContent(){
	$("body").html('<div class="alert alert-info" style="width:600px;margin:auto;margin-top:50px;"><span class="glyphicon glyphicon-info-sign"></span> <strong>Creating Post...</strong><hr class="message-inner-separator"><p>We are creating your post now, please wait. This may take a minute or two.</p><br/><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">Creating Post..</div></div></div>');
	var subData = {
		memberID: memberID, 
		contentID: contentID,
		selectedNetworks: selectedNetworks
	};
	if(customCotent[contentID]){
		subData.customContent = customCotent[contentID];
	}
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialmark-post-content.php",
	  dataType: 'json',
	  data: subData
	}).done(function( response ) {
		console.log('DONE');
		$("body").html('<div class="alert alert-success" style="width:600px;margin:auto;margin-top:50px;"><span class="glyphicon glyphicon-ok"></span> <strong>Post Created!</strong><hr class="message-inner-separator"><p>We have created the post as requested! Please check your Social Media networks to view your post.</p></div>');
	});
}

/* ADDED 10/25/2017 */
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