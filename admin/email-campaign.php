<?php
	require '../repository_inc/classes/inc.global.php';
	global $db;
	showErrors();
	error_reporting(-1);
	ini_set('display_errors','1');
	ini_set('mysql.connect_timeout', 300);
	ini_set('default_socket_timeout', 300);
	$emailCampaign = new emailcampaign;
	$notifications = new notifications;
	$cond = new conditions;
	$notifications->trace = true;
	$debug = false;
	
	function debug($a){
		global $debug;
		if( $debug ){
			echo "$a\n";
		}
	}
	function processConditions($arr,$campaignId,$update=false){
		$c = new conditions;
		$c->clear();
		debug("loading all campaign records");
		//Grab all records and delete them because we're about to overwrite them
		if( $update ){
			$c->load("type='campaignId' AND typeId=" . intval($campaignId));
		
			for($i=0; $i < $c->count(); $i++){
				$c->delete($c[$i]->id);
				
			}
		}
		$project = emailcampaign::PROJECT;
		$type = 'campaignId';
		$typeId = $campaignId;
		
		$cc = new conditionscontent;
		if( $arr['city'] != 'z' ){
			$lhs = "users.city";
			$op = "=";
			$rhs = $arr['city'];
			//($id,$project,$leftHandSide,$operator,$rightHandSide,$type,$typeId){
			$cc->import(array(
				'project'=>$project,
				'leftHandSide' => $lhs,
				'operator' => $op,
				'rightHandSide' => $rhs,
				'type' => $type,
				'typeId' => $typeId
				)
			);
			$c->insert($cc);
		}
		
		if( $arr['affiliate'] != 'z' ){
			$lhs = "photographers.photographerID";
			$op = "=";
			$rhs = $arr['affiliate'];
			$cc->import(array(
				'project'=>$project,
				'leftHandSide' => $lhs,
				'operator' => $op,
				'rightHandSide' => $rhs,
				'type' => $type,
				'typeId' => $typeId
				)
			);
			$c->insert($cc);
		}
		
		if( $arr['state'] != 'z' ){
			$lhs = "users.state";
			$op = "=";
			$rhs = $arr['state'];
			$cc->import(array(
				'project'=>$project,
				'leftHandSide' => $lhs,
				'operator' => $op,
				'rightHandSide' => $rhs,
				'type' => $type,
				'typeId' => $typeId
				)
			);
			$c->insert($cc);
		}
		
		if( strlen($arr['email']) ){
			foreach(json_decode($arr['email'],1) as $index => $email){
				$lhs = "notificationtypes.emailType";
				$op = "=";
				$rhs = $email;
				$cc->import(array(
					'project'=>$project,
					'leftHandSide' => $lhs,
					'operator' => $op,
					'rightHandSide' => $rhs,
					'type' => $type,
					'typeId' => $typeId
					)
				);
				$c->insert($cc);
			}
		}
	}
		
	
	if( isset($_GET['serveImage']) ){
		$emailCampaign->echoImage(intval($_GET['id']));
		die();
	}
			
	if( isset($_POST['ajax']) ){
		switch($_POST['mode']){
			case 'delete':
				$emailCampaign->delete(intval($_POST['id']));
				$c = new conditions;
				$c->load("type='campaignId' AND typeId=" . intval($_POST['id']));
				foreach($c as $index => $row){
					$c->delete($row->id);
				}
				die(json_encode(array('status'=>'ok')));
				break;
			case 'load':
				$emailCampaign->load("id=" . $_POST['id']);
				$emailCampaign[0]->image = null;
				$arr = $emailCampaign[0]->export();
				$cond->load("type='campaignId' AND typeId=" . intval($_POST['id']));
				foreach($cond as $key => $value){
					switch($value->leftHandSide){
						case 'users.state':
							$arr['state'] = $value->rightHandSide;
							break;
						case 'users.city':
							$arr['city'] = $value->rightHandSide;
							break;
						case 'photographers.photographerID':
							$arr['affiliate'] = $value->rightHandSide;
							break;
						case 'notificationtypes.emailType':
							$arr['email'][] = $value->rightHandSide;
					}
				}
				$arr['fallback'] = $emailCampaign->getFallback($_POST['id']);
				die(json_encode($arr));
				break;
			case 'generatePreview':
				$em = new emailcampaign;
				$html = $em->generateEmailPreview($_POST['image'],$_POST['url']);
				die(json_encode(array('html'=>$html)));
				break;
			default:
				break;
		}
	}
	
	
	if( isset($_GET['save']) ){
		$notifications->trace("Saving..");

		if( $_FILES['file'] !== null ){
			$notifications->trace("Extracting file info from uploaded file");
			list($image,$ext) = utils::extractFileInfo("file");
			$notifications->trace("File info extracted");
		}else if( empty($_FILES['file']) ){
			$notifications->trace("No uploaded file, loading instead");
			$res = $emailCampaign->load("id=" . intval($_POST['id']));
			$image = $emailCampaign[0]->image;
		}
		
		$tmpFile = $_FILES['file']['tmp_name'];
		debug('filesize src: ' . filesize($tmpFile));
		$ecc = new emailcampaigncontent;
		$content = $ecc->import($_POST);
		$content->image = $image;
		$notifications->trace("Email campaign content created");
		if( isset($_POST['id']) && intval($_POST['id']) ){
			$emailCampaign->load("id=" . $_POST['id']);
			$emailCampaign[0]->image = $image;
			$emailCampaign[0]->url = $_POST['url'];
			$emailCampaign[0]->text = $_POST['text'];
			$emailCampaign[0]->title = $_POST['title'];
			$emailCampaign[0]->active = $_POST['active'];
			$emailCampaign[0]->fallback = $_POST['fallback'];
			$emailCampaign->saveOffset(0);
			$emailCampaign->saveImage($_POST['id']);
			
			if( $_POST['fallback'] == '1' ){
				//We need this call because only one campaign can be set to 'fallback'
				//This function sets all campaigns to fallback=0 except for the one specified
				$emailCampaign->setFallbackCampaign($_POST['id']);
			}
			if( $_POST['active'] == '1' ){
				$emailCampaign->setActive($_POST['id']);
			}
			debug('saved offset');
			$notifications->trace("Saved offset. Post ID: " . $_POST['id']);
			processConditions($_POST,$_POST['id'],true);
			die($_POST['id']);
		}else{
			debug('inserting');
			$notifications->trace("Creating record  (inserting)");
			$id = $emailCampaign->insert($content);
			$notifications->trace("Inserted. ID: $id");
			$notifications->trace("Saving image...");
			$emailCampaign->saveImage($id);
			$notifications->trace("image saved");
			if( $_POST['active'] == '1' ){
				$notifications->trace("Setting campaign as active");
				$emailCampaign->setActive($id);
			}
			if( $_POST['fallback'] == '1' ){
				$emailCampaign->setFallbackCampaign($id);
			}
			processConditions($_POST,$id,false);
			die($id);
		}
	}
	
?>
<!DOCTYPE html>
<html lang='en' dir='ltr' itemscope itemtype="http://schema.org/QAPage">
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<title>Email Campaign</title>
	<script src='https://code.jquery.com/jquery-1.11.3.min.js'></script>
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/cerulean/bootstrap.min.css'/>
	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
	<link  href="/repository_inc/cropper/dist/cropper.css" rel="stylesheet">
	<script src="/repository_inc/cropper/dist/cropper.js"></script>
	<script>
		$(document).ready(function(){
			//
			$("#editSelect").bind("change",function(){
				loadCampaign($(this).val());
			});
<?php
	if( isset($_GET['load']) ){
		echo "loadCampaign(" . intval($_GET['load']) . ");";
	}

?>

		});
		function hidePreview(){
			$("#addVideoBody div.preview").css("display","none");
		}
		function showPreviewLink(mediaId){
			$("#addVideoBody div.previewLink").slideDown();
		}
		function showDelete(){
			clearCampaign();
			$("#createWrapper").slideUp();
			$("#deleteWrapper").slideDown();
		}
		function showCreate(){
			clearCampaign();
			$("#createWrapper").slideDown();
			$("#deleteWrapper").slideUp();
		}
		function deleteCampaign(obj){
			if( !confirm("Are you sure you want to delete this campaign?") ){
				return;
			}
			id= $(obj).val();
			$.ajax({
				url: "/admin/email-campaign.php",
				type: "POST",
				data:{
					"ajax": 1,
					"mode": "delete",
					"id": id
				}
			}).done(function(msg){
				window.location.href = '/admin/email-campaign.php';
			});
		}
		function loadCampaign(id){
			clearCampaign();
			if( id == '--Select a campaign --'){
				return;
			}
			showLoading();
			$.ajax({
				url: "/admin/email-campaign.php",
				type: "POST",
				data:{
					"ajax": 1,
					"mode": "load",
					"id": id
				}
			}).done(function(msg){
				a = $.parseJSON(msg);
				clearCampaign();
				console.log(a);
				$("#title").val(a.title);
				$("#text").val(a.text);
				$("#id").val(a.id);
				$("#url").val(a.url);
				$("#editSelect").val(a.id);
				if( a.active == '1' ){
					$("#active").prop("checked",true);
				}else{
					$("#active").prop("checked",false);
				}
				if( a.fallback == '1' ){
					$("#fallback").prop("checked",true);
				}else{
					$("#fallback").prop("checked",false);
				}
				$("#realImage").attr("src","/admin/email-campaign.php?serveImage=1&id=" + id);
				$("#state").val( a.state );
				$("#city").val( a.city );
				$("#affiliate").val( a.affiliate );
				if( typeof a.email != 'undefined' ){
					console.log(a.email);
					for(i=0; i < a.email.length; i++){
						$("input[name='email']").each(function(key,value){
							console.log(value);
							if( $(this).val() == a.email[i] ){
								$(this).prop("checked",true);
							}
						});
					}
				}
				stopLoading();
			});
		}
		
		function transitionClear(){
			$("#statusModal div.modal-body").html(
				"<div style='margin:0 auto;text-align: center'>" + 
				"	<h3>Creating new campaign</h3>" + 
				"</div>"
			);
			$("#statusModal").modal();
			
			window.setTimeout(function(){
				clearCampaign();
				$("#statusModal").slideUp();
				closeStatusModal();
			},1000);
		}

		function clearCampaign(){
			$("#editSelect").val("z");
			$("#title").val("");
			$("#text").val("");
			$("#url").val("");
			$("#id").val("");
			$("#realImage").attr("src","");
			$("#active").prop("checked",false);
			$("#state").val("z");
			$("#city").val("z");
			$("#affiliate").val("z");
			$("#fallback").prop("checked",false);
			$("input[name='email']").each(function(key,value){
				$(value).prop("checked",false);
			});
			$("#fallback").prop("checked",false);
		}
		// from an input element
		var filesToUpload;
		// from drag-and-drop
		function onDrop(e) {
			filesToUpload = e.dataTransfer.files;
		}
		
		function preview(){
			
			$("#myModal").modal();
			body = $("#myModal div.modal-body");
			if( imageChanged ){
				body.append(img);
				imageSource = img.src;
			}else{
				imageSource = $("#realImage").attr("src");
			}
			$.ajax({
				url: '/admin/email-campaign.php',
				type: 'POST',
				data:{
					'ajax': 1,
					'mode': 'generatePreview',
					'image': imageSource,
					'url': $("#url").val()
				}
			}).done(function(msg){
				a = $.parseJSON(msg);
				body.html(a.html);
			});
		}
		
		var imageChanged = false;
		var img;
		function handleFiles(obj){
			imageChanged = true;
			console.log(obj);
			var file = obj[0];
			img = document.createElement("img");
			img.classList.add("obj");
			//img.file = file;
			img.id = 'previewImage';
			img.src = window.URL.createObjectURL(file);
			
			//$("#").html("").append(img);
			/*
			console.log($("#previewImage"));
			$("#openModal").trigger("click");
			$("#previewImage").cropper({
				aspectRatio: 1,
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
			*/
		}//End handleFiles
		
		function closeStatusModal(){
			$("#statusModal").modal('hide');
		}
		
		function showLoading(){
			statusModal(
				"<div style='margin:0 auto;clear:both;text-align:center;width:400px;'>" + 
				"<h3>Loading Data...</h3><br>" + 
				"<img src='http://spotlighthometours.com/repository_images/loading.gif'>" +
				"</div>"
			);
		}
		function stopLoading(){
			statusModal(
				"<div style='margin:0 auto;clear:both;text-align:center;width:400px;'>" + 
				"<h3>Loading Complete</h3>" + 
				"</div>"
			);
			window.setTimeout(function(){
				closeStatusModal();
			},500);
		}
		
		
		
		function savingCampaign(){
			statusModal(
				"<div style='margin:0 auto;clear:both;text-align:center;width:400px;'>" + 
				"<h3>Saving Data...</h3><br>" + 
				"<img src='http://spotlighthometours.com/repository_images/loading.gif'>" +
				"</div>"
			);
		}
		
		function campaignSaved(){
			statusModal(
				"<div style='text-align:center;'>" + 
				"<h3>Saved</h3>" + 
				"</div>"
			);
			
		}
		
		function statusModal(body){
			$("#statusModal div.modal-body").html(
				body
			);
			$("#statusModal").modal();
		}
		
		function alertModal(body){
			$("#statusModal div.modal-body").html(
				body +
				"<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\" id='closeStatusModal'>Okay</button>"
			);
			$("#statusModal").modal();
		}
		
		function explainDefaults(){
			alertModal(
				"<p>" + 
					" When an email is sent out, the system first checks if there are any matching campaigns. " +
					" The system matches campaigns based on what you enter here on this page. " + 
					" If the city is set to Nevada, then that campaign will match whenever an email goes out to an Agent in Nevada" + 
					" If it doesn't match that campaign, then it proceeds to match every other campaign until it reaches the end." + 
					" If no campaign is setup as the default campaign, then emails that don't match a campaign are going to be sent " + 
					" out with no banner and no click tracking. This is not the ideal case. That is why it is important to specify " + 
					" one campaign as the default campaign. A default campaign acts as a 'catch-all'" +
				"</p>"
			);
		}
		
		function validateFields(){
			if( $("#title").val().length == 0 ){
				alertModal(
					"<h3>Please enter a campaign name</h3>"
				);
				return false;
			}
			if( $("#url").val().length == 0 ){
				alertModal(
					"<h3>Please enter a URL</h3>"
				);
				return false;
			}
			if( !$("#url").val().match(/[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/) ){
				alertModal(
					"<h3>Please enter a VALID URL</h3>"
				);
				return false;
			}
			if( !imageChanged && $("#realImage").attr("src").length == 0 ){
				alertModal(
					"<h3>Please choose an image</h3>"
				);
				return false;
			}
			return true;
			
		}
		
		function save(){
			/*cropData = $("#previewImage").cropper("getData");
			console.log("crop data: ");
			console.log(cropData);
			*/
			if( !validateFields() ){
				return;
			}
			savingCampaign();
			window.setTimeout(function(){
				var data = new FormData($("#cropForm")[0]);
				data.append('id',$("#id").val());
				if( imageChanged ){
					data.append('file',$("#file")[0].files[0]);
				}else{
					data.append('file',null);
				}
				data.append('active',$("#active").prop("checked") ? 1 : 0 );
				data.append('title',$("#title").val());
				data.append('text',$("#text").val());
				data.append('url',$("#url").val());
				data.append('city',$("#city").val());
				data.append('state',$("#state").val());
				data.append('affiliate',$("#affiliate").val());
				data.append('fallback',$("#fallback").prop("checked") ? 1 : 0 );

				arr = [];
				$("input[name='email']").each(function(key,value){
					console.log(value);
					if($(value).is(":checked")){
						console.log("Checked");
						arr.push($(value).val());
					}
				});
				data.append('email',JSON.stringify(arr));

				$.ajax('/admin/email-campaign.php?save=1', {
					type: 'post',
					'data': data,
					dataType: 'json',
					processData: false,
					contentType: false
				}).done(function(msg){
					campaignSaved();
					window.setTimeout(function(){
						$("#statusModal").slideUp(function(){
							window.location.href = '/admin/email-campaign.php?load=' + msg;
						});
					},3000);
				});
			},2000);
		}
		function closeCrop(){
			$("#myModal").modal('hide');
		}
		function deleteVideo(obj){
			/*
			if( !confirm("Are you sure you want to delete this video?") ){
				return;
			}
			mediaId = $(obj).data('mediaid');
			$.ajax({
				url: "/admin/email-campaign.php",
				type: "POST",
				data:{
					"ajax": 1,
					"mode": "deleteVideo",
					"mediaId": mediaId,
					"areaId": $("#id").val()
				}
			}).done(function(msg){
				$.remove(obj);
			});
			*/
		}

		function addVideoModal(){
			/* $("#addVideoModalButton").trigger("click"); */
		}
		function loadVideoTitle(mediaId){
/*			$.ajax({
				url: '/admin/email-campaign.php?ajax=1',
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
*/
		}
		function saveVideo(mediaId){
/*
			$.ajax({
				url: '/admin/email-campaign.php?ajax=1',
				type: 'post',
				data: {
					'ajax': 1,
					'mode': 'saveVideo',
					'mediaId': mediaId,
					'areaId': $("#id").val(),
					'title': $("#titleVideoInput").val()
				}
			}).done(function(msg){
				a = $.parseJSON(msg);
				console.log(a);

			});
*/
		}
		function previewVideo(mediaId){
/*			$("#addVideoBody div.preview").slideDown("slow",function(){
				$("#addVideoBody div.preview iframe")
					.attr("src","http://www.spotlighthometours.com/tours/video-player-new.php?type=video&id=" + mediaId + "&autoPlay=true")
					.slideDown()
				;
			});
*/			
		}
	</script>
	<style>
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
		div.input {
			padding-top: 10px;
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
		.hide { 
			display: none !important;
			visibility: hidden !important;
			border: 1px solid black;
		}
	</style>
</head>
<body>
	<!-- ############# -->
	<!-- MODAL DIALOGS -->
	<!-- ############# -->
	<div id="myModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Preview</h4>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id='closeModal' onClick='save()'>Save</button>
				</div>
			</div>
		</div>
	</div>
	
	<div id="statusModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-body">
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
						<div class='float-left col-lg-8'>
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
						<div class='float-left: col-lg-4'>
						
							<div class='preview'>
								<iframe id='iframePreview' src='' width=200 height=200></iframe>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id='closeAddVideoModal'>Add</button>
				</div>
			</div>
		</div>
	</div>
	<!-- END ADD VIDEO MODAL -->
	
	<div class="page-header">
		<h1>Email Campaigns<small></small></h1>
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

<div class="jumbotron" id='createWrapper'>
	
	
	
		<?php 
				echo "<div id='editCampaign' style='border-left: 1px solid black;padding-left:88px;margin-left:10px;padding-bottom:10px;'>";
				$emailCampaign->load("1=1");
				echo "<select id='editSelect'>";
				echo "<option>--Select a campaign --</option>";
				foreach($emailCampaign as $index => $obj){
					echo "<option value='{$obj->id}'>{$obj->title}";
					if( $obj->fallback == '1' ){
						echo "::DEFAULT CAMPAIGN::";
					}
					echo "</option>\n";
				}
				echo "</select>";
				echo "	<span style='padding:20px;width:100px;'>";
				echo "		<button class='btn btn-primary btn-sm' onClick='transitionClear()'>Create New Campaign</button>";
				echo "	</span>";
				echo "</div>";
		?>
		
		
	<div id='imageHolder' style='float:right;clear:both;position:absolute;left: 640px;'>
		<div id='currentImage' style='border: 1px solid black; border-radius:10px;padding:10px;text-align:center;'>
			Current Image:<br>
			<img src='' alt='current image' id='realImage' style='width:250px;'>
		</div>
	</div>
	<input type='hidden' id='id' name='id'>
	<h2>Campaign Info</h2>
	<label for='title'>Campaign Name:</label>
		<div class='input'>
			<input name='title' type='text' id='title'>
		</div>
		<div style='clear:both;'></div>
	<!-- <label for='text'>Description: (optional)</label><textarea name='text' id='text'></textarea><div style='clear:both;'></div> -->
	<label for='url'>URL:</label> 
		<div class='input'>
			<input name='url' type='text' id='url' style='width:400px;'>
		</div>	
		<div style='clear:both;'></div>
	<label for='file'>Choose JPEG:</label>
		<div class='input'>
			<input name="image" type="file" accept='image/*' id='file' onChange='handleFiles(this.files)'/>
		</div>
		<div style='clear:both;'></div>
	<h2>Localization (optional)</h2>
	<label for='state'>When State is:</label>
		<div class='input'>
			<select name='state' id='state'>
				<option value='z'>--Select a state--</option>
				<?php 
					foreach($db->run("SELECT DISTINCT state FROM users ORDER BY state ASC") as $i => $k){
						echo "<option value='" . $k['state'] . "'>" . $k['state'] . "</option>\n";
					}
				?>
			</select>
		</div>
		<div style='clear:both;'></div>
	<label for='city'>When City is:</label>
		<div class='input'>
			<select name='city' id='city'>
			<option value='z'>--Select a City--</option>
			<?php 
					foreach($db->run("SELECT DISTINCT city FROM users ORDER BY city ASC") as $i => $k){
						echo "<option value='" . $k['city'] . "'>" . $k['city'] . "</option>\n";
					}
				?>
			</select>
		</div>
		<div style='clear:both;'></div>
	<label for='affiliate'>When Affiliate is:</label>
		<div class='input'>
			
			<select name='affiliate' id='affiliate'>
			<option value='z'>--Select an affiliate--</option>
				<?php
					foreach($db->run("SELECT * FROM photographers order by fullName ASC") as $i => $key){
						echo "<option value='" . $key['photographerID'] . "'>" . $key['fullName'] . "</option>\n";
					}
				?>
			</select>
		</div>
		<div style='clear:both;'></div>
	<h2>When to send</h2>
	<div style='float:left;height:200px;'>
		<label for='affiliate'>Email Type:</label>
	</div>
		<div class='input' style='border: 1px solid black;'>
			<?php
				foreach($db->run("SELECT * FROM notificationtypes") as $i => $key){
					echo "<input type='checkbox' value='" . $key['emailType'] . "' name='email'>" . $key['emailType'] . "<br>";
				}
			?>
		</div>
		<div style='clear:both;'></div>
	<label for='active'></label>
		<div class='input'>
			<input type='checkbox' name='active' id='active'>
			Active
		</div>
		<div style='clear:both;'></div>

	<label for='fallback'></label>
		<div class='input'>
			<input type='checkbox' name='fallback' id='fallback'>
			Make this the default campaign if no other ones matched
			<?php
				$ec = new emailcampaign;
				$ec->load("fallback='1'");
				if( $ec->count() == 0 ){
					echo "<div class='alert'><b>WARNING</b> No default campaign has been set up. 
						<a href='javascript:void(0);' onClick='explainDefaults()'>
							Why is this important?
						</a>
					</div>";
				}
			?>
		</div>
		<div style='clear:both;'></div>
	
	<p style='padding: 20px;'>
		<button class="btn btn-primary btn-sm" onClick='preview()'>Preview</button>
		<button class="btn btn-primary btn-sm" onClick='save()'>Save</button>
	</p>
<!--
	<p>
		<button class="btn btn-primary btn-lg" onClick='save()'>Submit</button>
	</p>
-->
	
</div>


	<div id='deleteWrapper' style='display:none;'>
		<h1>Delete a campaign</h1>
		<select id='campaign'>
			<option value='z'>-- Choose a campaign --</option>
			
			<?php
				$emailCampaign = new emailcampaign;
				$emailCampaign->load("1=1");
				foreach($emailCampaign as $index => $row){
					echo "<option value='" . $row->id . "'>" . $row->title . "</option>\n";
				}
			?>
		</select>
		<p><a class="btn btn-primary btn-lg" href="#" role="button" onClick='deleteCampaign($("#campaign option:selected"))'>Delete</a></p>
	</div>
	
	
</body></html>
