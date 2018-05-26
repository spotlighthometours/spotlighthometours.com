// JavaScript Document
/* CATEGORIES */
var categories = [];
function getCategoris(){
	ajaxMessage('Loading Categories...', 'processing');
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialcategories.php",
	  dataType: 'json',
	  data: { action: 'get', userType: userType, userID: userID, userContentOnly: false }
	}).done(function( response ) {
		categories = response;
		ajaxMessage('Categories Loaded!', 'success');
		getContent();
	});
}

function getCategoryVals(id){
	var values = {};
	$.each(categories, function(k, v) {
		if(v.id==id){
			values = v;
		}
	});
	return values;
}

function getPreview(url){
	postData.previewurl = url;
	ajaxMessage('Loading Content Preview...', 'processing');
	$(".content-preview .alert").css('display', 'block');
	$(".content-preview .alert").html("Loading Preview...");
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialcontent-get-preview.php",
	  dataType: 'json',
	  data: { url: url }
	}).done(function( response ) {
		if(response.title){
			ajaxMessage('Content Preview Loaded!', 'success');
			generatePreview(response);
			genCaption();
			postData.image = "0";
		}
	});
}

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
function setItemNetwork(itemObj, network){
	if($(itemObj).parent().parent().parent().attr('class')){
		var parentClasses = $(itemObj).parent().parent().parent().attr('class');
		var currentNetwork = parentClasses.split(" ");
		currentNetwork = currentNetwork[currentNetwork.length-1];
		$(itemObj).parent().parent().parent().removeClass(currentNetwork);
		$(itemObj).parent().parent().find(".btn-"+currentNetwork).removeClass('active');
		$(itemObj).parent().parent().parent().addClass(network);
		$(itemObj).parent().parent().find(".btn-"+network).addClass('active');
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
var page = 1;
var numResults = 20;
var orderBy = 'createdOn';
var order = 'DESC';

function generatePageination(numberOfItems){
	var paginationItems = '';
	var pages = Math.ceil(numberOfItems/numResults);
	$(".num-results").html("&nbsp; Num Results: "+numberOfItems);
	if(page==1||numberOfItems==0){
		paginationItems += '';
	}else{
		paginationItems += '<li class="pagination-prev"><a class="page-link" href="javascript:getPrevPage()">Previous</a></li>';
	}
	for (i = 1; i <= pages; i++) { 
    	var active = '';
		if(i==page){
			active = 'active';
		}
		paginationItems += '<li class="page-item '+active+'"><a class="page-link" href="javascript:getPage('+i+')">'+i+'</a></li>';
	}
	if(page==pages||numberOfItems==0){
		paginationItems += '';
	}else{
		paginationItems += '<li class="pagination-next"><a class="page-link" href="javascript:getNextPage()">Next</a></li>';
	}
	$('.pagination').html(paginationItems);
	$(".pagination").rPage();
}

function getPage(pageNum){
	page = pageNum;
	getContent();
}

function getNextPage(){
	page += 1;
	getContent();
}

function getPrevPage(){
	page -= 1;
	getContent();
}

function getContent(){
	ajaxMessage('Loading Content and Statistics, Please wait this may take a minute...', 'processing');
	$.ajax({
	  method: "POST",
	  url: "../../repository_queries/socialcontent-get-contentsent.php",
	  dataType: 'json',
	  data: { memberID: memberID, page: page, numResults: numResults, orderBy: orderBy, order: order }
	}).done(function( response ) {
		ajaxMessage('Content Loaded!', 'success');
		generateContentPreview(response);
	});
}

function generateContentPreview(response){
	var results = response.results;
	var contentItemHTML = '';
	$.each(results, function(row, cols) {
  		if(cols.categories){
			var contentItemCategoryIDs = cols.categories.split(",");
		}else{
			var contentItemCategoryIDs = new Array();
		}
		var contentItemCategories = '';
		var firstContentItemCategory = true;
		$.each(contentItemCategoryIDs, function(cicidind, cicid) {
			if(!firstContentItemCategory){
				contentItemCategories += ', ';
			}
			contentItemCategories += getCategoryVals(cicid).name;
			firstContentItemCategory = false;
		});
		var UTCTime = moment(cols.createdOn);
		var MSTTime = moment(UTCTime).tz('America/Denver').format('MM/DD/YYYY h:mm:ss A');
		var createdOn = MSTTime;
		var title = (cols.previewtitle?cols.previewtitle:"");
		var desc = (cols.previewdesc?cols.previewdesc:"");
		var domain = (cols.previewdomain?cols.previewdomain:"");
		var cover = (cols.previewcover?cols.previewcover:"");
		var caption = (cols.caption?cols.caption:"");
		var network = "facebook";
		var networkOnly = "";
		if(cols.networkType!=="all"){
			var allowedNetworks = cols.networkType.split(",");
			network = allowedNetworks[allowedNetworks.length - 1];
			networkOnly = cols.networkType;
		}
		var statsAvailable = false;
		if(cols.facebookposturl||cols.linkedinprofileurl||cols.twitterposturl||cols.pinurl){
			statsAvailable = true;
		}
		contentItemHTML += ' \
		<!-- CONTENT ITEM --> \
		<div class="col-xs-6" id="content'+cols.id+'"> \
			<div class="panel panel-success '+cols.previewtype+' '+network+'" data-networkonly="'+networkOnly+'"> \
				<!-- CONTENT ITEM HEADER --> \
				<div class="panel-heading clearfix"> \
				  <h4 class="panel-title pull-left" style="padding-top: 7.5px;">Posted: '+createdOn+'</h4> \
		';
		contentItemHTML += ' \
				</div> \
		   		<!-- CONTENT ITEM BODY --> \
			   	<div class="panel-body"> \
				   <div class="caption">'+caption+'</div> \
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
				';
		var socialFooterStyle="";
		if(!statsAvailable){
			socialFooterStyle="display:none;";
		}
		contentItemHTML += '\
				<div class="panel-footer social-footer" style="'+socialFooterStyle+'"> \
					<div class="social-network-links col-xs-2"> \
		';
		/* FACCEBOOK ICON */
		if(cols.facebookposturl){
			contentItemHTML += ' \
							<a class="btn btn-social-icon btn-xs btn-facebook active" onclick="setItemNetwork(this, \'facebook\')"><span class="fa fa-facebook"></span></a> \
			';
		}
		/* LINKEDIN ICON */
		if(cols.linkedinprofileurl){
			contentItemHTML += ' \
							<a class="btn btn-social-icon btn-xs btn-linkedin" onclick="setItemNetwork(this, \'linkedin\')"><span class="fa fa-linkedin"></span></a> \
			';
		}
		/* TWITTER ICON */
		if(cols.twitterposturl){
			contentItemHTML += ' \
							<a class="btn btn-social-icon btn-xs btn-twitter" onclick="setItemNetwork(this, \'twitter\')"><span class="fa fa-twitter"></span></a> \
			';
		}
		contentItemHTML += ' \
						<!-- <a class="btn btn-social-icon btn-xs btn-instagram" onclick="setItemNetwork(this, \'instagram\')"><span class="fa fa-instagram"></span></a> --> \
		';
		/* PINTEREST ICON */
		if(cols.pinurl){
			contentItemHTML += ' \
						<a class="btn btn-social-icon btn-xs btn-pinterest" onclick="setItemNetwork(this, \'pinterest\')"><span class="fa fa-pinterest"></span></a> \
			';
		}
		contentItemHTML += ' \
					</div> \
					<div class="social-network-stats col-xs-10"> \
		';
		// FACE BOOK STATS
		if(cols.facebookposturl){
			var likesHTML = '<strong>Likes:</strong> <a href="javascript: parent.window.open(\''+cols.facebookposturl+'\',\'mywindow\');">'+cols.facebooklikes+'</a>';
			var sharesHTML = ', <strong>Shares:</strong> <a href="javascript: parent.window.open(\''+cols.facebookposturl+'\',\'mywindow\');">'+cols.facebookshares+'</a>';
			var commentsHTML = ', <strong>Comments:</strong> <a href="javascript: parent.window.open(\''+cols.facebookposturl+'\',\'mywindow\');">'+cols.facebookcomments+'</a>';
			contentItemHTML += ' \
							<div class="facebook">'+likesHTML+sharesHTML+commentsHTML+', <strong>Views:</strong> '+cols.views+'</div> \
			';	
		}
		
		// LINKEDIN STATS
		if(cols.linkedinprofileurl){
			contentItemHTML += ' \
							<div class="linkedin"><strong>Views:</strong> '+cols.views+'</div> \
			';
		}
		// TWITTER STATS
		if(cols.twitterposturl){
			var retweetHTML = '<strong>Retweets:</strong> <a href="javascript: parent.window.open(\''+cols.twitterposturl+'\',\'mywindow\');">'+cols.retweet_count+'</a>';
			var favoritesHTML = ', <strong>Favorites:</strong> <a href="javascript: parent.window.open(\''+cols.twitterposturl+'\',\'mywindow\');">'+cols.favorite_count+'</a>';
			contentItemHTML += ' \
							<div class="linkedin">'+retweetHTML+favoritesHTML+', <strong>Views:</strong> '+cols.views+'</div> \
			';
		}
		// PINTEREST STATS
		if(cols.pinurl){
			var savesHTML = '<strong>Saves:</strong> <a href="javascript: parent.window.open(\''+cols.pinurl+'\',\'mywindow\');">'+cols.pinsaves+'</a>';
			var commentsHTML = ', <strong>Comments:</strong> <a href="javascript: parent.window.open(\''+cols.pinurl+'\',\'mywindow\');">'+cols.pincomments+'</a>';
			contentItemHTML += ' \
							<div class="linkedin">'+savesHTML+commentsHTML+', <strong>Views:</strong> '+cols.views+'</div> \
			';
		}
		contentItemHTML += ' \
					</div> \
					<div class="clear"></div> \
				</div> \
				<div class="panel-footer"><strong>Categorie(s):</strong> '+contentItemCategories+'</div> \
			</div> \
		</div> \
		<div class="clear"></div> \
		';
	});
	$(".saved-content").html(contentItemHTML);
	generatePageination(response.numResults);
	$("img").error(function () {
		if($(this).attr("src").indexOf('../../repository_queries/proxy.php?url=')>-1){
			
		}else{
			$(this).attr("src", "../../repository_queries/proxy.php?url="+$(this).attr("src"));
		}
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
$("img").error(function () {
	if($(this).attr("src").indexOf('../../repository_queries/proxy.php?url=')>-1){
			
	}else{
		$(this).attr("src", "../../repository_queries/proxy.php?url="+$(this).attr("src"));
	}
});