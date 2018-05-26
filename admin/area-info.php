<?php
	require '../repository_inc/classes/inc.global.php';
	global $db;
	showErrors();
	error_reporting(-1);
	ini_set('display_errors','1');
	ini_set('mysql.connect_timeout', 300);
	ini_set('default_socket_timeout', 300);
	$areaInfo = new areainfo;
	
	$debug = false;
	
	function debug($a){
		global $debug;
		if( $debug ){
			echo "$a\n";
		}
	}
	
	if( isset($_GET['serveImage']) ){
		$areaInfo->echoImage(intval($_GET['id']));
		die();
	}
	
	if( isset($_POST['ajax']) ){
		switch($_POST['mode']){
			case 'delete':
				$areaInfo->delete(intval($_POST['id']));
				die(json_encode(array('status'=>'ok')));
				break;
			case 'load':
				$areaInfo->load("id=" . $_POST['id']);
				$areaInfo[0]->image = null;
				$c = $areaInfo->getVideoList($_POST['id']);
				$a = $areaInfo[0]->export();
				if( count($c) ){
					$a['videos'] = $c;
				}else{
					$a['videos'] = null;
				}
				die(json_encode($a));
				break;
			case 'deleteVideo':
				$areaInfo->deleteVideo(intval($_POST['areaId']),intval($_POST['mediaId']));
				die(json_encode(array('status'=>'ok')));
				break;
			case 'renameVideo':
				if( $_POST['areaId'] == 0 ){
					
				}
			case 'getVideoTitle':
				$res = $areaInfo->getVideo(intval($_POST['areaId']),intval($_POST['mediaId']));
				if( $res === null ){
					die(json_encode(array('title'=>'')));
				}else{
					die(json_encode(array('title'=>$res[0]['title'])));
				}
			default:
				break;
		}
	}
	
	debug('entering crop');
	if( isset($_GET['crop']) ){
		//Insert
		$fileExists = false;
		if( strlen($_FILES['file']['name']) ){
			list($image,$ext) = utils::extractFileInfo("file");
			$fileExists = true;
		}else{
			$image = "";
			$ext = "";
		}
		$tmpFile = $_FILES['file']['tmp_name'];
		$destFile = dirname(__FILE__) . '/uploads/tmp_dest.' . $ext;

		$status = utils::crop($tmpFile,$destFile,json_decode($_POST['data']),$ext);
		debug($status);
		$image  = file_get_contents($destFile);
		debug('filesize dest: ' . filesize($destFile));
		debug('filesize src: ' . filesize($tmpFile));
		$content = new areainfocontent(null,$_POST['title'],$_POST['text'],$image,$ext,$_POST['code']);
		if( isset($_POST['id']) && intval($_POST['id']) ){
			$areaInfo->load("id=" . $_POST['id']);
			if( $fileExists ){
				$areaInfo[0]->image = $image;
				$areaInfo[0]->extension = $ext;
			}
			$areaInfo[0]->code = $_POST['code'];
			$areaInfo[0]->text = $_POST['text'];
			$areaInfo[0]->title = $_POST['title'];
			$areaInfo->saveOffset(0);
			$id = intval($_POST['id']);
		}else{
			debug('inserting');
			$id = $areaInfo->insert($content);
		}
		$vids = array();
		if( strlen($_POST['videos']) ){
			$obj = json_decode($_POST['videos'],true);	
			foreach($obj as $index => $video){
				$a = explode(":~",$video);
				$mediaId = $a[0];
				$desc = $a[1];
				$areaInfo->saveVideo($id,$mediaId,$desc);
			}
		}
		
		die(json_encode(array('id'=>$id)));
	}
	
?>
<!DOCTYPE html>
<html lang='en' dir='ltr' itemscope itemtype="http://schema.org/QAPage">
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<title>Area Info</title>
	<script src='https://code.jquery.com/jquery-1.11.3.min.js'></script>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/cerulean/bootstrap.min.css'/>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
	<link  href="/repository_inc/cropper/dist/cropper.css" rel="stylesheet">
	<script src="/repository_inc/cropper/dist/cropper.js"></script>
	<script>
		$(document).ready(function(){
			//
			$("#editSelect").bind("change",function(){
				loadArea($(this).val());
			});
			$("#mediaId").bind("change",function(){
				if($(this).val() != 'z' ){
					showPreviewLink($(this).val());
					hidePreview();
					loadVideoTitle($(this).val());
				}else{
					$("#addVideoBody div.previewLink").slideUp();
				}
			});
			$("input[type='text']").bind("change",function(){
				changed = true;
			});
			$("textarea").bind("change",function(){
				changed = true;
			});
		});
		var videoData = [];
		var changed = false;
		function hidePreview(){
			$("#addVideoBody div.preview").css("display","none");
		}
		function showPreviewLink(mediaId){
			$("#addVideoBody div.previewLink").slideDown();
		}
		function showDelete(){
			clearArea();
			$("#createWrapper").slideUp();
			$("#deleteWrapper").slideDown();
		}
		function showCreate(){
			clearArea();
			$("#createWrapper").slideDown();
			$("#deleteWrapper").slideUp();
		}
		function deleteArea(obj){
			id= $(obj).val();
			$.ajax({
				url: "/admin/area-info.php",
				type: "POST",
				data:{
					"ajax": 1,
					"mode": "delete",
					"id": id
				}
			}).done(function(msg){
				a = $.parseJSON(msg);
				console.log(a);
				alert("Area has been deleted");
				$(obj).remove();
				
			});
		}
		function loadArea(id){
			if( changed ){
				if( confirm("You have not saved your data. Would you like to save now?") ){
					save(false);
					changed = false;
				}
			}
			$.ajax({
				url: "/admin/area-info.php",
				type: "POST",
				data:{
					"ajax": 1,
					"mode": "load",
					"id": id
				}
			}).done(function(msg){
				a = $.parseJSON(msg);
				console.log(a);
				$("#title").val(a.title);
				$("#text").val(a.text);
				$("#code").val(a.code);
				$("#id").val(a.id);
				$("#realImage").attr("src","/admin/area-info.php?serveImage=1&id=" + id);
				videoData = [];
				$("#editSelect").val(id);
				$("#attachedVideos").html("");
				if(a.videos){
					for( i in a.videos ){
						obj = a.videos[i];
						videoData.push(obj.mediaId +":~" + obj.title);
					}
					refreshVideoList();
				}
			});
		}

		function clearArea(){
			$("#title").val("");
			$("#text").val("");
			$("#code").val("");
			$("#id").val("");
			$("#realImage").attr("src","");
			$("#attachedVideos").html("");
		}
		// from an input element
		var filesToUpload;
		// from drag-and-drop
		function onDrop(e) {
			filesToUpload = e.dataTransfer.files;
		}
		
		function handleFiles(obj){
			changed = true;
			console.log(obj);
			var file = obj[0];
			var img = document.createElement("img");
			img.classList.add("obj");
			//img.file = file;
			img.id = 'previewImage';
			img.src = window.URL.createObjectURL(file);
			$("#preview").html("").append(img);

			console.log($("#previewImage"));
			$("#previewImageModal").modal();
			$("#previewImage").cropper({
				aspectRatio: 2/1,
				autoCrop: true,
				minContainerWidth: 500,
				minContainerHeight: 500,
				crop: function(e) {
					// Output the result data for cropping image.
					console.log(e.x);
					console.log(e.y);
					console.log(e.width);
					console.log(e.height);
					console.log(e.rotate);
					console.log(e.scaleX);
					console.log(e.scaleY);
				}//End crop function
				
			});//end cropper
			$(".cropper-container").css("max-height",500);
		}//End handleFiles
		
		
		function save(redirect){
			cropData = $("#previewImage").cropper("getData");
			console.log("crop data: ");
			console.log(cropData);
			
			var data = new FormData($("#cropForm")[0]);
			data.append('id',$("#id").val());
			data.append('data',JSON.stringify(cropData));
			data.append('file',$("#file")[0].files[0]);
			data.append('code',$("#code").val());
			data.append('title',$("#title").val());
			data.append('text',$("#text").val());
			data.append('videos',videoListToJson());
			
			$.ajax('/admin/area-info.php?crop=1', {
				type: 'post',
				'data': data,
				dataType: 'json',
				processData: false,
				contentType: false,

				beforeSend: function () {
					
				},

				success: function (msg) {
					if( redirect  === false ){
						return;
					}
					window.location.href = '/admin/area-info.php?load=' + msg.id;
				},

				error: function (XMLHttpRequest, textStatus, errorThrown) {
					alert("error");
				},

				complete: function () {
					
				}
			}).done(function(msg){
			});
			
		}
		function closeCrop(){
			$("#closeModal").trigger("click");
		}
		function deleteVideo(obj){
			if( !confirm("Are you sure you want to delete this video?") ){
				return;
			}
			mediaId = $(obj).data('mediaid');
			$.ajax({
				url: "/admin/area-info.php",
				type: "POST",
				data:{
					"ajax": 1,
					"mode": "deleteVideo",
					"mediaId": mediaId,
					"areaId": $("#id").val()
				}
			}).done(function(msg){
				$("#attachedVideo_" + mediaId).remove();
				save();
			});
		}

		function addVideoModal(){
			$("#addVideoModal").modal();
		}
		function loadVideoTitle(mediaId){
			$.ajax({
				url: '/admin/area-info.php?ajax=1',
				type: 'post',
				data: {
					'ajax': 1,
					'mode': 'getVideoTitle',
					'mediaId': mediaId,
					'areaId': $("#id").val()
				}
			}).done(function(msg){
				a = $.parseJSON(msg);
				console.log(a);
				$("#titleVideoInput").val( a.title );
			});
		}
		
		function storeVideoData(mediaId,desc){
			if( $("#attachedVideo_" + mediaId).length ){
				$("#attachedVideo_" + mediaId).remove();
			}
			
			if( videoData.length ){
				temp = [];
				//update videoData structure
				
				for( i in videoData ){
					a = videoData[i].split(":~",2);
					console.log("A after split: " + a);
					//If media ID exists in videoData structure, skip it
					if( a[0] == mediaId ){
						continue;
					}else{
						temp.push(a[0] + ":~" + a[1]);
						console.log("A temp.push: " + a);
						changed = true;
					}
				}
				videoData = temp;
			}
			videoData.push(mediaId + ":~" + desc);
			refreshVideoList();
		}
		
		
		function refreshVideoList(){
			$("#attachedVideos").html("");
			for( i in videoData ){
				m = videoData[i].split(":~",2);
				mediaId = m[0];
				title = m[1];
				addAttachedVideo(mediaId,title);
			}
		}
		function videoListToJson(){
			temp = [];
			$("div[id^='attachedVideo_']").each(function(index,element){
				id = $(element).attr("id");
				m = id.split("_");
				mediaId = m[1];
				desc = $("#attachedVideoDesc_" + mediaId).html();
				temp.push(mediaId + ":~" + desc);
			});
			return JSON.stringify(temp);
		}
		function saveVideo(mediaId,title){
			storeVideoData(mediaId,title);
		}
		function previewVideo(mediaId){
			$("#addVideoBody div.preview").slideDown("slow",function(){
				$("#addVideoBody div.preview iframe")
					.attr("src","http://www.spotlighthometours.com/tours/video-player-new.php?type=video&id=" + mediaId + "&autoPlay=true")
					.slideDown()
				;
			});
		}
		function closeAddVideo(){
			vidTitle = $("#titleVideoInput").val();
			if( vidTitle.length == 0 ){
				alert("Please enter a video title");
				$("#titleVideoInput").trigger("focus");
				return false;
			}
			mediaId = $("#addVideoModal div.modal-body #mediaId").val();
			if( mediaId == 'z' ){
				alert("Please choose a video");
				return false;
			}
			saveVideo(mediaId,vidTitle);
		}
		
		function videoTitle(mediaId){
			return $("#attachedVideoDesc_" + mediaId).html();
		}
		function renameSave(){
			if( $("#videoTitle").val().length == 0 ){
				alert("Please enter a video title");
				return;
			}
			mediaId = $("#videoTitle").data("mediaid");
			title = $("#videoTitle").val();
			saveVideo(mediaId,title);
			$("#myModal").modal("hide");
			changed = true;
		}
		function renameVideo(obj){
			mediaId = $(obj).data("mediaid");
			$("#myModal div.modal-title").html("Rename video");
			$("#myModal div.modal-body").html(
				"<div class='col-lg-8 col-md-8 col-sm-8'>" + 
					"<input type='text' data-mediaid='" + mediaId + "' id='videoTitle' value='" + videoTitle(mediaId) + "'>" + 
				"</div>"
			);
			$("#myModal div.modal-footer").html(
					"<button class='btn btn-default' onClick='renameSave()'>Save</button>" 
			);
			$("#myModal").modal();
			changed = true;
		}
		function viewVideo(mediaId){
			iframe = $("<iframe>")
			.attr("src","http://www.spotlighthometours.com/tours/video-player-new.php?type=video&id=" 
						+ mediaId +
						+ "&autoPlay=true"
			)
			.css("width","260px")
			.css("height","260px");
			$("#myModal .modal-body").html("").append(iframe);
			$("#myModal .modal-footer").html("");
			$("#myModal").modal();
		}
		function addAttachedVideo(mediaId,title){
			html = "<div id='attachedVideo_" + mediaId + "'>"
				+ "<div class='col-lg-2 col-md-2 col-sm-2'>"  
				+ 	mediaId 
				+ "</div>" 
				+ "<div id='attachedVideoDesc_" + mediaId + "' class='col-lg-6 col-md-6 col-sm-6'>"
				+ 	title
				+ "</div>"
				+ "<div class='col-lg-4 col-md-4 col-sm-4' style='padding:0px;'>"
				+ 	"<a href='javascript:void(0);' data-mediaid='" + mediaId + "' onClick='viewVideo($(this).data(\"mediaid\"))'>View</a> || "
				+	"<a href='javascript:void(0);' data-mediaid='" + mediaId + "' onClick='deleteVideo(this)'>Delete</a> ||"
				+ 	"<a href='javascript:void(0);' data-mediaid='" + mediaId + "' onClick='renameVideo(this)'>Rename</a>"
				+ "</div>"
				+ "</div><div style='clear:both;'></div>";
			$("#attachedVideos").append(html);
			console.log(html);
		}
	</script>
	<style>
		#addVideoBody div.titleVideo {
			float: left;
			width: 200px;
		}
		#addVideoBody div.preview {
			width: 200px;
			float: left;
			display: none;
		}
		#addVideoBody div.previewLink {
			width: 90px;
			float: left;
		}
		#addVideoBody select {
			float: left;
			width: 200px;
		}
		.float-left {
			float: left;
		}
		label {
			padding: 10px;
			border-left: 1px solid black;
			float: left;
			width: 80px;
			margin: 5px 10px 0px 10px;
		}
		input {
			padding: 3px 10px 3px 10px;
			border-radius: 5px;
		}
		textarea { 
			padding: 10px;
			width: 500px;
			height: 300px;
		}
		.spinner {
			display: inline-block;
			opacity: 0;
			width: 0;

			-webkit-transition: opacity 0.25s, width 0.25s;
			-moz-transition: opacity 0.25s, width 0.25s;
			-o-transition: opacity 0.25s, width 0.25s;
			transition: opacity 0.25s, width 0.25s;
		}

		.has-spinner.active {
			cursor:progress;
		}

		.has-spinner.active .spinner {
			opacity: 1;
			width: auto; /* This doesn't work, just fix for unkown width elements */
		}

		.has-spinner.btn-mini.active .spinner {
			width: 10px;
		}

		.has-spinner.btn-small.active .spinner {
			width: 13px;
		}

		.has-spinner.btn.active .spinner {
			width: 16px;
		}

		.has-spinner.btn-large.active .spinner {
			width: 19px;
		}
		.row:nth-child(even) {background: #ddd;}
		.row:nth-child(odd) {background: #FFF;}
	</style>
</head>
<body>
<!-- Trigger the modal with a button -->
	<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" id='openModal' style='display:none;'></button>
	<!-- ############# -->
	<!-- MODAL DIALOGS -->
	<!-- ############# -->
	<div id="previewImageModal" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Modal Header</h4>
				</div>
				<div class="modal-body">
					<div id='preview'>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id='closeModal'>Save</button>
				</div>
			</div>
		</div>
	</div>

	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Modal Header</h4>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id='closeModal'>Save</button>
				</div>
			</div>
		</div>
	</div>

	<!-- ADD VIDEO MODAL -->
	<div id="addVideoModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Add Video</h4>
				</div>
				<div class="modal-body">
					<div id='addVideoBody'>
						<div class='float-left col-lg-6 col-md-6 col-sm-6'>
							<div class='select float-left'>
								<select id='mediaId'>
									<option value='z'>-- Choose a video --</option>
									<?php
									
										$res = $db->select("media","tourID=52776 AND mediaType != 'photo'");
										foreach($res as $index => $row){
											echo "<option value='" . $row['mediaID'] . "'>" . $row['room'] . "</option>\n";
										}
										
									?>
								</select>
								
							</div>
							<div class='previewLink'>
								<a href='javascript:void(0);' onClick='previewVideo($("#mediaId").val())'>Preview Video</a>
							</div>
							<div class='titleVideo'>
								<b>Video Title:</b>
								<input type='text' id='titleVideoInput' value='Video Title'>
							</div>
						</div>
						<div class='float-left: col-lg-4 col-md-4 col-sm-4'>
						
							<div class='preview'>
								<iframe id='iframePreview' src='' width=200 height=200></iframe>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" id='closeAddVideoModal' onClick='closeAddVideo()'>Add</button>
				</div>
			</div>
		</div>
	</div>
	<!-- END ADD VIDEO MODAL -->
	
	<div class="page-header" onClick='videoListToJson()'>
		<h1>Area Info</h1>
	</div>	
	
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Area Info</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li <?php echo (isset($_GET['create']) ? 'class="active"' : "" );?>><a href="javascript:void(0);" onClick='showCreate()'>Create/Edit</a></li>
					<li <?php echo (isset($_GET['delete']) ? 'class="active"' : "" );?>><a href="javascript:void(0);" onClick='showDelete()'>Delete</a></li>
				</ul>
			</div>
		</div>
	</nav>

<?php
	if( isset($_GET['load']) ){
		echo "<script>loadArea(" . intval($_GET['load']) . ");</script>";
	}

?>
<div class="jumbotron" id='createWrapper'>
	<h1>Area info</h1>
	
	
		<?php 
				echo "<div id='editArea' style='border-left: 1px solid black;padding-left:88px;margin-left:10px;padding-bottom:10px;'>";
				$areaInfo->load("1=1");
				echo "<select id='editSelect'>";
				echo "<option>--Select an area--</option>";
				foreach($areaInfo as $index => $obj){
					echo "<option value='{$obj->id}'>{$obj->title}</option>\n";
				}
				echo "</select>";
				echo "</div>";
		?>
		
		
	<div id='imageHolder' style='float:right;clear:both;position:absolute;left: 640px;'>
		<div id='currentImage' style='border: 1px solid black; border-radius:10px;padding:10px;text-align:center;'>
			Current Image:<br>
			<img src='/foobar.jpg' alt='current image' id='realImage' style='width:250px;'>
		</div>
	</div>
	<input type='hidden' id='id' name='id'>
	<label for='text'>Code:</label> <input name='code' type='text' id='code'><div style='clear:both;'></div>
	<label for='title'>Title:</label> <input name='title' type='text' id='title'><div style='clear:both;'></div>
	<label for='text'>Text: </label><textarea name='text' id='text'></textarea><div style='clear:both;'></div>
	<label for='file'>Choose JPEG:</label> <input name="image" type="file" accept='image/*' id='file' onChange='handleFiles(this.files)'/><div style='clear:both;'></div>
	<div id='videos' class='col-lg-5 col-md-5 col-sm-5' style='background-color: white;color: black;'>
		<h2>Attach Videos</h2>
		<div class='row'>
			<div class='col-lg-2 col-md-2 col-sm-2'>
				<b>Media ID</b>
			</div>
			<div class='col-lg-6 col-md-6 col-sm-6'>
				<b>Video Info</b>
			</div>
			<div class='col-lg-4 col-md-4 col-sm-4'>
				<b>Action</b>
			</div>
		</div>
		<div id='attachedVideos'>
			
		</div>
		<div class='col-lg-offset-9 col-lg-1 col-md-1 col-sm-1'>
			<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#addVideoModal" id='addVideoModalButton' style='display:none;'>Add Video</button>
			<button type="button" class="btn btn-info btn-sm" onClick='addVideoModal()'>Add Video</button>
		</div>
		<div style='clear:both;'></div>
	</div>
	
	<div style='clear:both;'></div>
	<p>
		<button class="btn btn-primary btn-lg" onClick='save()'>Submit</button>
	</p>
	
</div>


	<div id='deleteWrapper' style='display:none;'>
		<h1>Delete an area</h1>
		<select id='area'>
			<option value='z'>-- Choose an area --</option>
			
			<?php
				$areaInfo = new areainfo;
				$areaInfo->load("1=1");
				foreach($areaInfo as $index => $row){
					echo "<option value='" . $row->id . "'>" . $row->title . "::" . $row->code . "</option>\n";
				}
			?>
		</select>
		<p><a class="btn btn-primary btn-lg" href="#" role="button" onClick='deleteArea($("#area option:selected"))'>Delete</a></p>
	</div>
	
	
</body></html>
