// JavaScript Document
var userID = 0;
var userType = 'user';
var URL = "";
var headerParentTag = '<div class="header"> </div>';
var backgroundImageURL = "";
var backgroundSize = "";
var headerHeight = 133;
var headerWrapperTag = '<div class="wrapper"> </div>';
function replaceAll(str, find, replace) {
    return str.replace(new RegExp(find, 'g'), replace);
}
function generateHeader(){
		var headerParentTagObj = $(headerParentTag);
		var headerWraperTagName = "";
		var tageNameAdded = false;
		if($(headerParentTagObj).attr('id')!= undefined){
			var headerWraperID = $(headerParentTagObj).attr('id');
			tageNameAdded = true;
			headerWraperParentTagName = "#".headerWraperID;
		}
		if($(headerParentTagObj).attr('class')!= undefined&&!tageNameAdded){
			var headerWraperID = $(headerParentTagObj).attr('class');
			var headerWraperParentTagName = $(headerParentTagObj).prop("tagName");
			headerWraperParentTagName += '.'+replaceAll(headerWraperID, " ", ".");
		}
		
		var headerWrapperTagObj2 = $(headerWrapperTag);
		var headerWraperTagName = "";
		var tageNameAdded = false;
		if($(headerWrapperTagObj2).attr('id')!= undefined){
			var headerWraperID = $(headerWrapperTagObj2).attr('id');
			headerWraperTagName = "#"+headerWraperID;
			tageNameAdded = true;
		}
		if($(headerWrapperTagObj2).attr('class')!= undefined&&!tageNameAdded){
			var headerWraperID = $(headerWrapperTagObj2).attr('class');
			headerWraperTagName += $(headerWrapperTagObj2).prop("tagName");
			headerWraperTagName += '.'+replaceAll(headerWraperID, " ", ".");
		}
		
		if(backgroundImageURL){
			$('body').css("background-image", backgroundImageURL);
			$('body').css("background-size", backgroundSize);
		}
		console.log(headerWraperParentTagName);
		$('body').find(headerWraperParentTagName).nextAll().remove();
		$('body').find(headerWraperTagName).nextAll().remove();
		//$('#webpage').height(headerHeight);
}