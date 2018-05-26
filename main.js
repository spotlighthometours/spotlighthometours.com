/* TAB DROP DOWN MENUS */
var $mytn3;
var layoutWidth = 1023;
var slideShowWidth = 670;
var slideShowHeight = 489;
var flowPlayerWidth = 670;
var flowPlayerHeight = 377;
var imageSize = 670;
var mediaID = 0;
var imageSrc = '';
var albumTitle = '';
var moveTitle = '';
var videoType = '';
var introPlayed = false;
var outroPlayed = false;
var introPlaying = false;
var outroPlaying = false;
var userID = 0;
$.fn.preload = function() {
    this.each(function(){
        $('<img/>')[0].src = this;
    });
}
window.onload = function(){
	if (window!=window.top) { 
	}else{
		resizeWindow();
	}
}

if (window!=window.top) { 
}else{
	resizeWindow();
	if(!checkParemeterExists("reloaded")){
		insertParam("reloaded", "true");
	}
}

function resizeWindow(){
	window.resizeTo(1055,820);
	var left = (screen.width/2)-(1060/2);
  	var top = (screen.height/2)-(830/2);
	window.moveTo(left,top);
}

function getPercentage(percent, number){
	return (percent/100)*number;
}

function scalePage(origWidth){
	// GET LAYOUT SIZE PERCENTAGE
	windowWidth = $(window).width();
	layoutSizePercentage = (windowWidth/origWidth)*100;
	layoutSizeDecimal = layoutSizePercentage/100;

	// ZOOM BY LAYOUT PERCENTAGE
	if(layoutSizeDecimal<1){
		$(".wrapper, .modal-window").css("zoom", layoutSizePercentage+"%");
		$(".wrapper, .modal-window").css("-moz-transform", "scale("+layoutSizeDecimal+")");
		$(".wrapper, .modal-window").css("-moz-transform-origin", "top left");
		if($.browser.mozilla||$.browser.msie){
			var modalWinLeftMarg = ($(".modal-window").width()/2)*layoutSizeDecimal;
			$(".modal-window").css("margin-left", "-"+modalWinLeftMarg+"px");
		}
	}
}

$(document).ready(
	function() {
		scalePage(layoutWidth);
		
		$(".blockPage .tn3e-show-thmb").live("mouseover",function(){
			$(".blockPage .tn3e-thumbs").slideToggle('fast');
			$(this).removeClass("tn3e-show-thmb").addClass("tn3e-hide-thmb");
		});
		$(".blockPage .tn3e-hide-thmb").live("click",function(){
			$(".blockPage .tn3e-thumbs").slideToggle('fast');
			$(this).removeClass("tn3e-hide-thmb").addClass("tn3e-show-thmb");
		});
		
		// FLOORPLAN FANCYBOX
		$(".floorplan .snapshot").fancybox({
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'speedIn'		:	600, 
			'speedOut'		:	200, 
			'overlayShow'	:	false
		});
		
		// FORMS
		$("input, textarea").live("focus", function(){
			if($(this).hasClass("hint-text")){
				$(this).removeClass("hint-text");
				$(this).val("");
			}
		});
		// Contact agent
		$(".contact-agent .submit-btn").live("click", function(){
			// validate
			var fromEmail = $("input[name=from]").val();
			if(validate("email", fromEmail)){
				// Valid send email!
				$(".contact-agent form").slideToggle('slow');
				spinnerAlert('contact-agent-msg', 'Sending email')
				var url = "../../repository_queries/tour-window-emailer.php";
				var params = "to="+$("input[name=to]").val()+"&tourAddress="+encodeURI($("input[name=tourAddress]").val())+"&from="+fromEmail+"&message="+encodeURI($("textarea[name=message]").val())+"&agentName="+encodeURI($("input[name=agentName]").val())+"&action=contactAgent";
				ajaxQuery(url, params, 'agentEmailResponse');
				_gaq.push(['_trackEvent','contact agent','emailed', 'from '+fromEmail]);
			}else{
				outputError('contact-agent-msg', 'Please enter your email address. Check the spelling and try again.');
			}
		});
		
		// REMOVE HIDE FROM MAIN WRAPPER IF JS IS WORKING AND ENABLED :)
		$(".wrapper").removeClass("hide");
		
		// SCROLLABLE AREAS
		$('.scroll-pane').jScrollPane({showArrows: false});
		
		// TAB DROP DOWN MENU
		$(".wrapper .left-panel .tabs ul li").mouseenter(function(){
			$(this).find("ul").stop(true, true).slideDown(400, function(){
				// ADD SCROLL BAR IF NEEDED
				var pane = $(this);
				pane.jScrollPane({showArrows: false});
				var api = pane.data('jsp');
				
				// AUTO SCROLL WITH MOUSE LOCATION
				$(this).mousemove(function(e){
   					var offset = $(this).offset();
   					var pixFromTop = e.pageY - offset.top;
					var pixFromBottom = $(this).height() - pixFromTop;
					var scrollRange = 100;
					if(pixFromTop<scrollRange){
						var scrollToPos = api.getContentPositionY() - (scrollRange-pixFromTop);
						api.scrollToY(scrollToPos);
					}
					if(pixFromBottom<scrollRange){
						var scrollToPos = api.getContentPositionY() + (scrollRange-pixFromBottom);
						api.scrollToY(scrollToPos);
					}
				});
			});
		});
		
		$(".wrapper .left-panel .tabs ul li").mouseleave(function(){
			$(this).find("ul").stop(true, true).slideUp(400);
		});
		
		$(".wrapper .left-panel .tabs ul li ul li").mouseenter(function(){
			$(this).addClass("active");
		});
		
		$(".wrapper .left-panel .tabs ul li ul li").mouseleave(function(){
			$(this).removeClass("active");
		});
		
		// PHOTO / VIDEO GALLERY
		$mytn3 = $('.gallery').tn3({
			skinDir:"includes/skins",
			fullscreen: function(e) { 
				if (e.fullscreen&&$mytn3.cAlbum>=vidAlbInd){
					loadFlash($mytn3.n);
					var maxTimeOut = setTimeout(maximizeFlash, 700);
					// Log fullscreen event for video/slideshow
					if(e.fullscreen){
						if(videoType=='video'){
							_gaq.push(['_trackEvent','video','fullscreen',moveTitle,parseInt(mediaID)]);
						}else{
							_gaq.push(['_trackEvent','slideshow','fullscreen',moveTitle]);
						}
						$(".tn3e-show-thmb").hide();
					}
				}else{
					// Log fullscreen event for photo
					var img = $(".tn3e-full-image img");
					var imgSrc = $(img).attr('src');
					if(e.fullscreen){
						_gaq.push(['_trackEvent','photo','fullscreen',imageSrc,parseInt(mediaID)]);
						$(".tn3e-show-thmb").show();
						//$(img).attr('src',imgSrc.replace("_960_", "_high_"));
						setTimeout(function(){
							$(img).css('height','100%');
							$(img).css('width','100%');
						}, 300);
					}else{
						//$(img).attr('src',imgSrc.replace("_high_", "_960_"));
					}
				}
				if (!e.fullscreen&&$mytn3.cAlbum>=vidAlbInd){
					loadFlash($mytn3.n);
					minimizeFlash();
				}
				if(e.fullscreen){
					maxWindow();
					$(".tn3e-thumbs").hide();
					$(".tn3e-hide-thmb").removeClass("tn3e-hide-thmb").addClass("tn3e-show-thmb");
				}else{
					$(".tn3e-thumbs").show();
					minWindow();
				}
			},
			mouseWheel: false,
		    autoplay:false,
		    width:imageSize,
		    delay:5000,
		    skin:"tn3e", 
		    imageClick:"url",
		    image:{
			crop:true,
			stretch:true,
			transitions:[{
			    type:"blinds",
			    duration:300
			    },
			    {
			    type:"grid",
			    duration:160,
			    gridX:9,
			    gridY:7,
			    easing:"easeInCubic",
			    sort:"circle"
			    },{
			    type:"slide",
			    duration:430,
			    easing:"easeInOutExpo"
			    }]
		    },
			thumbnailer:{
        		align:0
     		},
			albums_click: function(e){
				albumTitle = $mytn3.albums.data[$mytn3.cAlbum].title;
				_gaq.push(['_trackEvent','album','clicked (gallery)',albumTitle]);
			},
			image: {
				transition: function(e) {
					// Check if video album
					if($mytn3.cAlbum==vidAlbInd){
						$('.tn3e-play-active').click();
						loadFlash($mytn3.n);
						if($mytn3.config.isFullScreen){
							maximizeFlash();
						}
					}else{
						if($mytn3.config.isFullScreen){
							var img = $(".tn3e-full-image img");
							//var imgSrc = $(img).attr('src');
							//$(img).attr('src',imgSrc.replace("_960_", "_high_"));
							//$(img).load(function() {
							$(img).css('height','100%');
							$(img).css('width','100%');
							//});
						}
						// Photo album
						imageSrc = $($mytn3.items.image).find("img").attr('src');
						mediaID = imageSrc.substring(imageSrc.lastIndexOf('_')+1, imageSrc.lastIndexOf('.'));
						_gaq.push(['_trackEvent','photo','viewed',imageSrc,parseInt(mediaID)]);	
					}
				}
			}
		    }).data('tn3');
		$.fn.tn3.translate("Album List", "Photo / Video List");
		$.fn.tn3.translate("Maximize", "Full Screen");
		$(document).bind("fullscreenchange", function() {
			if($(document).fullScreen()){
				if(!$mytn3.config.isFullScreen){
					$mytn3.fullscreen();
				}
			}else{
				if($mytn3.config.isFullScreen){
					$mytn3.fullscreen();
				}
			}
		});
		if(autoPlay){
			var menuText = $(".left-panel ul:first li:first").next().find("li:first").html();
			if(menuText.length>18){
				$(".left-panel ul:first li:first").next().find('label').html(menuText.substring(0, 15)+"...");
			}else{
				$(".left-panel ul:first li:first").next().find('label').html(menuText);
			}
			$(".left-panel ul:first li:first").next().addClass('active');
			$(".left-panel ul:first li:first").removeClass("active");
			$mytn3.showAlbum(vidAlbInd, 0);
		}
	}
);

function checkParemeterExists(parameter){
   //Get Query String from url
   fullQString = window.location.search.substring(1);
   paramCount = 0;
   queryStringComplete = "?";
   if(fullQString.length > 0)
   {
       //Split Query String into separate parameters
       paramArray = fullQString.split("&");
       
       //Loop through params, check if parameter exists.  
       for (i=0;i<paramArray.length;i++)
       {
         currentParameter = paramArray[i].split("=");
         if(currentParameter[0] == parameter) //Parameter already exists in current url
         {
            return true;
         }
       }
   }
   return false;
}

function insertParam(key, value){
    key = escape(key); value = escape(value);
    var kvp = document.location.search.substr(1).split('&');
    var i=kvp.length; var x; while(i--) 
    {
    	x = kvp[i].split('=');

    	if (x[0]==key)
    	{
    		x[1] = value;
    		kvp[i] = x.join('=');
    		break;
    	}
    }
    if(i<0) {kvp[kvp.length] = [key,value].join('=');}
    //this will reload the page, it's likely better to store this until finished
    document.location.search = kvp.join('&'); 
}
var minimizeWidth;
var minimizeHeight;
function maxWindow(){
	minimizeWidth = 1055;
	minimizeHeight = 820;
	/*window.moveTo(0,0);
	window.resizeTo(screen.availWidth,screen.availHeight);*/
	$(document).fullScreen(true);
}

function minWindow(){
	window.resizeTo(minimizeWidth,minimizeHeight);
	var left = (screen.width/2)-(minimizeWidth/2);
  	var top = (screen.height/2)-(minimizeHeight/2);
	window.moveTo(left,top);
	$(document).fullScreen(false);
}

function KeyCode(e){ 
	e=e||window.event; 
	var kk=e.which?e.which:event.keyCode ; 
	if (kk==37 ){ 
		if(!$mytn3.config.isFullScreen){
			$('.tn3e-prev').trigger('click'); 
		}
	}
	if (kk==39){ 
		if(!$mytn3.config.isFullScreen){
			$('.tn3e-next').trigger('click'); 
		}
	}
	return false; 
} 
document.onkeypress=function(evt){ KeyCode(evt); } 
document.onkeydown=function(evt){ KeyCode(evt); } 

function outputError(elementID, error){
	var output = '<div class="errors widthAuto">'+error+'</div>';
	$("#"+elementID).hide();
	$("#"+elementID).html(output);
	$("#"+elementID).fadeIn('fast');
}

function outputAlert(elementID, alertTxt){
	var output = '<div class="alert widthAuto">'+alertTxt+'</div>';
	$("#"+elementID).hide();
	$("#"+elementID).html(output);
	$("#"+elementID).fadeIn('fast');
}

function spinnerAlert(elementID, spinnerTxt){
	var output = '<div class="alert widthAuto"><img width="16" height="16" src="'+window.location.protocol+'//www.spotlighthometours.com/repository_images/spinner-alert.gif" align="absmiddle" /> '+spinnerTxt+'</div>';
	$("#"+elementID).hide();
	$("#"+elementID).html(output);
	$("#"+elementID).fadeIn('slow');
}

function validate(type, value){
   	switch(type){
		case 'alphanumeric':
			var reg = /^[a-z0-9-]+$/i;
			if(reg.test(value) == false) {
			  return false;
			}else{
				return true;
			}
		break;
		case 'email':
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			if(reg.test(value) == false) {
			  return false;
			}else{
				return true;
			}
		break;
		case 'empty':
			if(value){
				return true;
			}else{
				return false;
			}
		break;
		case 'letter':
			var alphaExp = /^[a-zA-Z]+$/;
			if(value.match(alphaExp)){
				return true;
			}else{
				return false;
			}
		break;
		case 'number':
			if(isNaN(value)){
				return false;
			}else{
				return true;
			}
		break;
		case 'number-empty':
			if(isNaN(value)||!value){
				return false;
			}else{
				return true;
			}
		break;
	}
}

function maximizeFlash(){
	var origWidth = slideShowWidth;
	if(loadedFlashType=="slideshow"){
		var origHeight = slideShowHeight;
	}else{
		var origHeight = flowPlayerHeight;
	}
	var newWidth = widthByAspectRatio(origHeight, origWidth, $(".tn3e-full-image").height());
	var newHeight = $(".tn3e-full-image").height();
	$('#mediaPlayer').height(newHeight);
	$('#mediaPlayer').width(newWidth);
	$(".tn3e-full-image").css("text-align","center");
}

function minimizeFlash(){
	var origWidth = slideShowWidth;
	if(loadedFlashType=="slideshow"){
		var origHeight = slideShowHeight;
	}else{
		var origHeight = flowPlayerHeight;	
	}
	$('#mediaPlayer').height(origHeight);
	$('#mediaPlayer').width(origWidth);
	$(".tn3e-full-image").css("text-align","normal");
}


function widthByAspectRatio(origHeight, origWidth, newHeight){
	return (origWidth/origHeight)*newHeight;
}

function albumLinkClicked(obj, albumNum, slideNum){
	var isPhotoLink = true;
	if(albumNum>=vidAlbInd){
		isPhotoLink = false;
	}
	var menuText = $(obj).html();
	albumTitle = menuText;
	if(menuText.length>18){
		menuText = menuText.substring(0, 15)+"...";
	}
	if(isPhotoLink){
		_gaq.push(['_trackEvent','album','clicked (dropdown)', albumTitle]);
		$(".left-panel ul:first li:first").find('label').html(menuText);
		$(".left-panel ul:first li:first").addClass('active');
		$(".left-panel ul:first li:first").next().removeClass("active");
	}else{
		$(".left-panel ul:first li:first").next().find('label').html(menuText);
		$(".left-panel ul:first li:first").next().addClass('active');
		$(".left-panel ul:first li:first").removeClass("active");
	}
	$(obj).parent().parent().parent().hide();
	$mytn3.showAlbum(albumNum, slideNum);
}

var loadedFlashType = "slideshow";
var flashIndex = 0;
function loadFlash(index){
	introPlaying = false;
	outroPlaying = false;
	loadedFlashType = flashType[index];
	flashIndex = index;
	if(introExists&&!introPlayed){
		loadIntroVideo();
	}else{
		switch(flashType[index]){
			case "slideshow":
				loadSlideshowPlayer(index);
			break;
			case "video":
				loadVideoPlayer(index);
			break;
		}
	}
}

function getFlashMovie(movieName){
	var isIE = navigator.appName.indexOf("Microsoft") != -1;
	return (isIE) ? window[movieName] : document[movieName]; 
}

function loadSlideshowPlayer(index){
	if($("#mediaPlayer_wrapper").length==0) {
		var params = {
			quality: "best",
			wmode: "transparent",
			align: "middle",
			scale: "noborder",
			bgcolor: "#cccccc",
			allowScriptAccess: "always",
			allowFullScreen: "true"
		};
		swfobject.embedSWF('mediaPlayer.swf', "mediaPlayer", slideShowWidth, slideShowHeight, "6.0.65", "expressInstall.swf", '', params, {id: "mediaPlayer", name: "mediaPlayer"}, function callBackFb(e){
			if(e.success){
				var timeout;
				function loadVid(){
					if(typeof getFlashMovie('mediaPlayer').loadThumbVideo == 'function'){
						// GOOD IT WORKED NOW WE CAN CLEAR THE TIMEOUT
						getFlashMovie('mediaPlayer').loadThumbVideo(flashSource[index]);
						clearInterval(timeout);
						moveTitle = $.trim($mytn3.items.text[0].textContent);
						videoType = 'slideshow';
						_gaq.push(['_trackEvent','slideshow','play', moveTitle]);
						if($(document).fullScreen()){
							maximizeFlash();
						}
					}
				}
				function loadVidAfterOneSec(){
					timeout = setInterval(loadVid,1000);
				}
				loadVidAfterOneSec();
			}
		});
		$('#mediaPlayer .error-message').css("display","block");
	}else{
		jwplayer('mediaPlayer').remove();
		setTimeout(function(){
			var params = {
				quality: "best",
				wmode: "transparent",
				align: "middle",
				scale: "noborder",
				bgcolor: "#cccccc",
				allowScriptAccess: "always",
				allowFullScreen: "true"
			};
			swfobject.embedSWF('mediaPlayer.swf', "mediaPlayer", slideShowWidth, slideShowHeight, "6.0.65", "expressInstall.swf", '', params, {id: "mediaPlayer", name: "mediaPlayer"}, function callBackFb(e){
				if(e.success){
					var timeout;
					function loadVid(){
						if(typeof getFlashMovie('mediaPlayer').loadThumbVideo == 'function'){
							// GOOD IT WORKED NOW WE CAN CLEAR THE TIMEOUT
							getFlashMovie('mediaPlayer').loadThumbVideo(flashSource[index]);
							clearInterval(timeout);
							moveTitle = $.trim($mytn3.items.text[0].textContent);
							videoType = 'slideshow';
							_gaq.push(['_trackEvent','slideshow','play', moveTitle]);
							if($(document).fullScreen()){
								maximizeFlash();
							}
						}
					}
					function loadVidAfterOneSec(){
						timeout = setInterval(loadVid,5);
					}
					loadVidAfterOneSec();
				}
			});
			$('#mediaPlayer .error-message').css("display","block");
		},100);
	}
}

function slideShowStopped(){
	if(outroExists&&!outroPlayed){
		loadOutroVideo();
	}
}

function  loadIntroVideo(){
	introPlaying = true;
	var file = introVideo;
	var extension = file.substr((file.lastIndexOf('.')+1));
	var fileName = file.split('/');
	fileName = fileName[fileName.length-1];
	var smilFile = fileName.split('.');
	smilFile = smilFile[0]+'.smil';
	var smilFileLocation = file.replace(fileName,smilFile);
	if(extension.toLowerCase()=="mp4"||extension.toLowerCase()=="mov"){
		$.ajax({
			url:smilFileLocation,
			type:'HEAD',
			error: function()
			{
				var theFile = "rtmp://spotlighthometours.com/vod/mp4:"+file.replace("http://www.spotlighthometours.com/","spotlight/");
				launchVideoPlayer(theFile);
			},
			success: function()
			{
				var theFile = smilFileLocation;
				launchVideoPlayer(theFile);
			}
		});
	}else{
		launchVideoPlayer(file);
	}
	if($(document).fullScreen()){
		maximizeFlash();
	}
}

function loadOutroVideo(){
	outroPlaying = true;
	var file = outroVideo;
	var extension = file.substr((file.lastIndexOf('.')+1));
	var fileName = file.split('/');
	fileName = fileName[fileName.length-1];
	var smilFile = fileName.split('.');
	smilFile = smilFile[0]+'.smil';
	var smilFileLocation = file.replace(fileName,smilFile);
	if(extension.toLowerCase()=="mp4"||extension.toLowerCase()=="mov"){
		$.ajax({
			url:smilFileLocation,
			type:'HEAD',
			error: function()
			{
				var theFile = "rtmp://spotlighthometours.com/vod/mp4:"+file.replace("http://www.spotlighthometours.com/","spotlight/");
				launchVideoPlayer(theFile);
			},
			success: function()
			{
				var theFile = smilFileLocation;
				launchVideoPlayer(theFile);
			}
		});
	}else{
		launchVideoPlayer(file);
	}
	if($(document).fullScreen()){
		maximizeFlash();
	}	
}

function loadVideoPlayer(index){
	var file = flashSource[index];
	var drive = file.split("::");
	file = drive[0];
	drive = drive[1];
	var extension = file.substr((file.lastIndexOf('.')+1));
	var fileName = file.split('/');
	fileName = fileName[fileName.length-1];
	var smilFile = fileName.split('.');
	smilFile = smilFile[0]+'.smil';
	var smilFileLocation = file.replace(fileName,smilFile);
	if(drive=='f'){
		smilFileLocation = smilFileLocation.replace("/images/","/images-f/");
	}
	if(drive=='g'){
		smilFileLocation = smilFileLocation.replace("/images/","/images-g/");
	}
	if(extension.toLowerCase()=="mp4"||extension.toLowerCase()=="mov"){
		$.ajax({
			url:smilFileLocation,
			type:'HEAD',
			error: function()
			{
				var theFile = "rtmp://spotlighthometours.com/vod/mp4:"+file.replace("http://www.spotlighthometours.com/","spotlight/");
				if(drive=='f'){
					theFile = file.replace("/images/","/images-f/");
				}
				if(drive=='g'){
					theFile = file.replace("/images/","/images-g/");
				}
				launchVideoPlayer(theFile);
			},
			success: function()
			{
				var theFile = smilFileLocation;
				launchVideoPlayer(theFile);
			}
		});
	}else{
		launchVideoPlayer(file);
	}
}

function launchVideoPlayer(theFile){
	jwplayer("mediaPlayer").setup({
		file: theFile,
		height: '100%',
		width: '100%',
		autostart: 'true',
		primary: 'flash'
	});
	videoType = 'video';
	// Send data to Google Analytics!
	var file = jwplayer().config.file;
	moveTitle = $.trim($mytn3.items.text[0].textContent);
	mediaID = file.substring(file.lastIndexOf('_')+1, file.lastIndexOf('.'));
	if(introPlaying){
		moveTitle = 'User Intro Video';
		mediaID = userID;
	}
	if(outroPlaying){
		moveTitle = 'User Outro Video';
		mediaID = userID;
	}
	jwplayer().onFullscreen(function(e){
		if(e.fullscreen){
			_gaq.push(['_trackEvent','video','fullscreen', moveTitle, parseInt(mediaID)]);	
		}else{
			if($(document).fullScreen()){
				maximizeFlash();
			}
		}
	});
	jwplayer().onPlay(function(e){
		_gaq.push(['_trackEvent','video','play', moveTitle, parseInt(mediaID)]);
	});
	jwplayer().onPause(function(){
		_gaq.push(['_trackEvent','video','pause', moveTitle, parseInt(mediaID)]);
	});
	jwplayer().onComplete(function(){
		if(introPlaying){
			switch(flashType[flashIndex]){
				case "slideshow":
					loadSlideshowPlayer(flashIndex);
				break;
				case "video":
					loadVideoPlayer(flashIndex);
				break;
			}
			introPlaying = false;
			introPlayed = true;
		}else{
			if(outroExists){
				if(!outroPlayed){
					if(!outroPlaying){
						loadOutroVideo();
					}
				}
			}
		}
		if(outroPlaying){
			outroPlaying = false;
			outroPlayed = true;
		}
		_gaq.push(['_trackEvent','video','complete', moveTitle, parseInt(mediaID)]);
	});
}

function showMap(address){
	mapWindow = window.open('http://maps.google.com/maps?q='+encodeURI(address),'','fullscreen=yes, scrollbars=auto');
	mapWindow.moveTo((screen.width/2)-500,0); 
	mapWindow.resizeTo(1000,800);
	_gaq.push(['_trackEvent','map','clicked']);
}

function showBrochure(id){
	broWindow = window.open('http://www.spotlighthometours.com/tours/printBrochure.cfm?cid='+id,'','fullscreen=yes, scrollbars=auto');
	broWindow.moveTo((screen.width/2)-500,0);
	broWindow.resizeTo(1000,800);
	_gaq.push(['_trackEvent','tools','clicked', 'brochure']);	
}

function showLocalSchools(){
	schWindow = window.open('about:blank','','fullscreen=yes, scrollbars=auto');
	schWindow.moveTo((screen.width/2)-500,0);
	schWindow.resizeTo(1000,800);
	schWindow.location.href = localSchoolsURL;
	_gaq.push(['_trackEvent','tools','clicked', 'local schools']);
}

function getCustButtonURL(url){
	custWindow = window.open('about:blank','','fullscreen=yes, scrollbars=auto');
	custWindow.moveTo((screen.width/2)-600,0);
	custWindow.resizeTo(1200,800);
	custWindow.location.href = url;
	_gaq.push(['_trackEvent','custom button','clicked', url]);
}

function contactAgent(agentPhoto, agentPhone, agentEmail, agentName, agentAddress, tourAddress){
	var formHTML = '';
	if (agentPhoto.length > 2) {
		formHTML += '                <div class="contact-agent">';
		formHTML += '                    	<img class="photo-agent" src="'+agentPhoto+'" width="63" />';
		formHTML += '                </div>';
	}
	if (agentPhone.length > 2) {
		formHTML += '                <div class="contact-agent" style="width:auto;">';
		formHTML += '                    	<h2 class="phone">Phone: <a href="tel:'+agentPhone+'">'+agentPhone+'</a></h2>';
		formHTML += '                    	<h2>Email: '+agentEmail+'</h2>';
	if (agentAddress.length > 2) {
		formHTML += '                    	<h2>Address: '+agentAddress+'</h2>';
	}
		formHTML += '                </div>';
	}
	formHTML += '                <div class="msg" id="contact-agent-msg"></div>';
	formHTML += '                <div class="contact-agent">';
    formHTML += '                	<form>';
	formHTML += '                		<input type="hidden" name="to" value="'+agentEmail+'" />';
	formHTML += '                		<input type="hidden" name="tourAddress" value="'+tourAddress+'" />';
	formHTML += '                		<input type="hidden" name="agentName" value="'+agentName+'" />';
    formHTML += '                    	<label>From:</label>';
    formHTML += '                    	<input name="from" value="Your email address" class="hint-text"/>';
    formHTML += '                    	<label>Message:</label>';
    formHTML += '                    	<textarea name="message" rows="10" class="hint-text">Your message to the agent</textarea>';
    formHTML += '                    	<input type="button" class="submit-btn" value="Send Message" />';
    formHTML += '                        <div class="clear"></div>';
    formHTML += '                    </form>';
    formHTML += '                </div>';
	ShowPopUp('Contact Agent: '+agentName, formHTML);
	_gaq.push(['_trackEvent','contact agent','clicked']);
}

function agentEmailResponse(){
	var theResponse = response;
	outputAlert('contact-agent-msg', 'Your message has been sent to the agent via email.')
}

function ShowPopUp(title, content) {
	try {
		var f_title = false;
		if(document.getElementById("pop_up_title")) {
			f_title = document.getElementById("pop_up_title");
		}
		var f_content = false;
		if(document.getElementById("pop_up_content")) {
			f_content = document.getElementById("pop_up_content");
		}
		$("#backdrop").stop(true, true).fadeTo("slow", 0.7);
		f_title.innerHTML = title;
		f_content.innerHTML = content;
		$("#pop_up_frame").stop(true, true).fadeIn("slow");
	} catch(err) {
		alert("ShowPopUp: " + err);
	}
}

function HidePopUp() {
	try {
		$("#backdrop").fadeOut("slow");
		$("#pop_up_frame").fadeOut("slow", function() {
			if(document.getElementById("pop_up_title")) {
				document.getElementById("pop_up_title").style.display = "block";
			}
			$("#pop_up_content").find('iframe').attr('src', 'google.com');
		});
		
	} catch(err) {
		alert("HidePopUp: " + err);
	}
}

/* AJAX */

var response = "";
var responseXML = "";

function ajaxQuery(url, params, functionName){
try {
		var HTTP = false;
		if (window.XMLHttpRequest) {
			HTTP = new XMLHttpRequest();
		}else{
			HTTP = new ActiveXObject("Microsoft.XMLHTTP");
		}
		if(HTTP) {
			HTTP.open("POST", url, true);
			HTTP.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			HTTP.setRequestHeader("Content-length", params.length);
			HTTP.setRequestHeader("Connection", "close");

			HTTP.onreadystatechange = function() { 
				if (HTTP.readyState == 4 && (HTTP.status == 200 || HTTP.status == 0)) {
					
					response = HTTP.responseText;
					responseXML = HTTP.responseXML;
					var funcCall = functionName + "();";
					eval(funcCall);
	
				}else if(HTTP.readyState == 4 && HTTP.status == 403){
					message = '<p>Your session may have expired, please login:</p>';
					message += '<div class="button_new button_blue button_mid" onclick="window.location=\'http://www.spotlighthometours.com/login/\'">';
           			message += '<div class="curve curve_left" ></div>';
					message += '<span class="button_caption" >Login</span>';
					message += '<div class="curve curve_right" ></div>';
				  	message += '</div>';
					HidePopUp();
					ShowPopUp("Access Denied", message);
				}
			}
			HTTP.send(params);
		}			
					
	} catch(err) {
		alert("AjaxQuery: " + err);
	}
}

function getTools(){
	var html = "<div class='mcalc'></div>";
	html += '<div class="tool-links">';
	html += '	<ul>';
    html += '    	<li onClick="showMortgageCalc()">Mortgage<br/>Calculator</li>';
    html += '        <li onClick="showBrochure('+tourID+')" class="print">Print<br/>Brochure</li>';
    html += '        <li onClick="showLocalSchools()" class="schools">Local<br/>Schools</li>';
    html += '    </ul>';
	html += '    <div class="clear"></div>';
	html += '</div>';
	ShowPopUp('Tools', html);
	_gaq.push(['_trackEvent','tools','clicked', 'link']);
}
var headerAdded = false;
function showMortgageCalc(){
	var params = {
    	price: listingPrice,
    	down_payment: ''
  	}
	$('.mcalc').mortgagecalc(params);
	$('.mcalc').slideToggle('slow');
	_gaq.push(['_trackEvent','tools','clicked', 'mortgage calculator']);
}

function getFloorplan(floorplanID){
	var url = "../../repository_queries/tour-get-floorplans-html.php";
	if(floorplanID){
		var params = "floorplanID="+floorplanID;
	}else{
		var params = "tourID="+tourID;
	}
	ajaxQuery(url, params, 'showFloorplan');
}

function showFloorplan(){
	$('.floorplan-wrapper').html(response);
	$('.floorplan-wrapper').fadeIn('slow');
}

function hideFloorplan(){
	$('.floorplan-wrapper').html('');
	$('.floorplan-wrapper').fadeOut('slow');
}