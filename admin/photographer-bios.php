<?php
	require_once('../repository_inc/classes/inc.global.php');
	global $db;	

	if( isset($_POST['req']) && $_POST['req'] == 'getPics' ){
		$res = $db->select("media","tourID=" . intval($_POST['tourId']));
		die(json_encode($res));
	}
	if( isset($_POST['examples']) ){
		$id = intval($_POST['photog']);
		$db->run("DELETE FROM photographers_examples WHERE photographerId={$id}");
		$arr = json_decode($_POST['examples'],1);
		foreach($arr as $index => $url){
			if( preg_match("|/tours/([0-9]{4,})/photo_400_([0-9]{1,})\.[a-z]{1,4}|",$url,$matches) ){
				$db->insert("photographers_examples",array('photographerId'=>$id,'tourId'=>$matches[1],'mediaId'=>$matches[2]));
			}
		}
		$isAffiliate = $_POST['isAffiliate'] == 'yes' ? '1' : '0' ;
		$show = $_POST['show'] == 'yes' ? '1' : '0' ;
		$bio = $_POST['bio'];
		$fullName = $_POST['name'];
		$a = $db->update("photographers",array('fullName'=>$fullName,'isAffiliate'=>$isAffiliate,'showOnAbout'=>$show,'bio'=>$bio),"photographerID={$id}");
		die();
	}
	if( isset($_FILES['squarePhoto']) || isset($_FILES['rectanglePhoto']) ){
		$photoId = intval($_GET['photog']);
		$res = $db->select('photographers',"photographerID=" . $photoId);
		$fullName = preg_replace("|[^a-zA-Z ]{1,}|","",$res[0]['fullName']);
		$fullName = str_replace(" ","-",trim($fullName));
		$fullName = strtolower($fullName);
		$path = $_SERVER['DOCUMENT_ROOT'] . "/repository_images/new/about/headshots/{$fullName}.jpg";
		$path2 = $_SERVER['DOCUMENT_ROOT'] . "/repository_images/new/about/headshots/lrg/{$fullName}.jpg";
		move_uploaded_file($_FILES['squarePhoto']['tmp_name'],$path);
		move_uploaded_file($_FILES['rectanglePhoto']['tmp_name'],$path2);
	}
?>
<!doctype html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Sortable - Connect lists</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <link rel="stylesheet" href="/repository_css/template.css">
  <style>
  #sortable1, #sortable2 {
    /* */
    min-height: 20px;
    list-style-type: none;
    margin: 0;
    padding: 0px 0 0 0;
    float: left;
    margin-right: 0px;
  }
  #sortable1 li, #sortable2 li {
    margin: 0 0px 0px 0px;
    padding: 0px;
    font-size: 1.2em;
  }

	.left {
		float: left;
		/* */
		width: 150px;
	}
	.right {
		float: left;
		/* */
		width: 250px;
	}
	.clear { 
		clear: both;
	}
	.master {
		font-family: "Lucida Grande","Helvetica Neue",Helvetica,Arial,sans-serif;
		font-size: 15px;
		margin-left: auto;
		margin-right: auto;
		/* */
		text-align: center;
		width: 600px;
		border: 2px solid black;
		border-radius: 25px;
	}
	#examples { 
		border: 2px dotted black;
		border-radius: 25px;
		width: 1200px;
		position: absolute;
		left: 0px;
		margin: 0 auto;
	}
	.tour-selector { 
		left: 400px;
		position: relative;
	}
  #sortable1,#sortable2 { 
		list-style-type: none; 
	}
  #sortable1 li, #sortable2 li { 
		margin: 3px 3px 3px 0; 
		padding: 1px; 
		float: left; 
		height: 110px; 
		font-size: 4em; 
		text-align: center;
	}
	#examples div.left {
		width: 500px;
		height: 500px;
	}
	#examples div.right {
		width: 490px;
		float: left;
		position:relative;
		left: 20px;
		height: 500px;
	}
	#sortable1 { 
		width: 480px !important;
		border: 1px dashed black;
		border-radius: 25px;
		padding: 10px;
		margin: 10px 0px 10px 10px;
		background-color: white;
		background-image: url(/repository_images/drag-n-drop.png);
	}
	#squarePhotos {
		width: 500px;
		height: 200px;
		border: 1px solid black;
	}
	#rectanglePhotos {
		width: 500px;
		height: 200px;
		border: 1px solid black;
	}
	.scaledLeft {
		float: left;
		padding: 10px 20px 10px 10px;
		width: 150px;
		height: 110px;
	}
	.scaledLeft img {
		width: 80px;
	}
	.scaledLeft b {
		font-size: 15px;
	}
	.uploadLeft {
		float: left;
	}
  </style>
  <script>
  $(function() {
    $( "#sortable1, #sortable2" ).sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();
	$("#tourIdSelector").bind("change",function(){
		tourId = $(this).val();
		$.ajax({
			type: "POST",
			data:{
				req: "getPics",
				"tourId": tourId
			}
		}).done(function(msg){
			json = $.parseJSON(msg);
			$("#sortable2").html("");
			for(var i=0 ; i < json.length ; i++ ){
				if( json[i].mediaType == 'photo' ){
					$("#sortable2").append("<li class='ui-state-default'><img width=150 src='http://spotlight-f-images-tours.s3.amazonaws.com/tours/" + tourId + "/photo_400_" + json[i].mediaID + "." + json[i].fileExt + "'></li>");
				}
			}
		});
	});
  });
	function saveData(){
		choices = getExamples();
		$.ajax({
			type: "POST",
			data:{
				photog: <?php echo intval($_GET['photog']);?>,
				isAffiliate: $("#isAffiliate option:selected").val(),
				show: $("#show option:selected").val(),
				bio: $("#bio").val(),
				name: $("#name").val(),
				examples: choices
			}
		}).done(function(msg){
			var formData = new FormData();
			var fileSelect = document.getElementById('squarePhoto');
			// Get the selected files from the input.
			var files = fileSelect.files;
			var rectSelect = document.getElementById('rectanglePhoto');
			var rectFiles = rectSelect.files;
			var send = 0;
			if( document.getElementById('squarePhoto').files.length ){
				formData.append("squarePhoto",files[0],files[0].name);
				send = 1;
			}
			
			if( document.getElementById('rectanglePhoto').files.length ){
				formData.append("rectanglePhoto",rectFiles[0],rectFiles[0].name);
				send = 1;
			}
			if( send ){
				var xhr = new XMLHttpRequest();
				xhr.open('POST', '/admin/photographer-bios.php?photog=<?php echo intval($_GET['photog']);?>', true);
				// Set up a handler for when the request finishes.
				xhr.onload = function () {
				  if (xhr.status === 200) {
					// File(s) uploaded.
				  } else {
					alert('An error occurred!');
				  }
				};
				// Send the Data.
				xhr.send(formData);
			}
			alert("Changes have been saved");
			window.location.reload();
		});
	}
	function getExamples(){
		var examples = [];
		$("#sortable1 li img").each(function(a,b){
			examples.push($(b).attr("src"));
		});
		return JSON.stringify(examples);
	}
	function sortableEmpty(){
		if( getExamples == "[]" ){	//We have an empty list. Add a placeholder
			$("#sortable1").append("<li class='ui-state-default'></li>");
		}
	}
  </script>
</head>
 
<div class='master'> 
<form method=GET>
	<select name='photog'>
		<?php
			$photogs = $db->run("SELECT * FROM photographers ORDER BY fullName ASC");
			foreach($photogs as $index => $row){
				echo "<option ";
				if( $_GET['photog'] == $row['photographerID'] ){ 
					echo " selected=selected ";
				}
				echo " value='" . $row['photographerID'] . "'>" . $row['fullName'] . "</option>";
			}
		?>
	</select>
	<input type='submit' name='submit' value='Go'>
</form>

<?php
	if( isset($_GET['photog']) ){
		$photog = $db->select("photographers","photographerID=" . intval($_GET['photog']));
?>
<form method=post>
	<div class="left">
	 	<b>Name:</b>
	</div>
	<div class="right">
		<input type='text' id='name' name='name' value='<?php echo $photog[0]['fullName']; ?>'>
	</div>

	<div class="clear"></div>

	<div class="left">
		<b>Is Affiliate:</b>
	</div>
	<div class="right">
		<select id='isAffiliate' name='isAffiliate'>
			<option <?php if($photog[0]['isAffiliate'] =='1'){ echo "selected=selected "; }?> value='yes'>yes</option>
			<option <?php if($photog[0]['isAffiliate'] =='0'){ echo "selected=selected "; }?> value='no'>no</option>
		</select>
	</div>
	<div class="clear"></div>
	<div class="left">
		<b>Show:</b>
	</div>
	<div class="right">
		<select name='show' id='show'>
			<option <?php if($photog[0]['showOnAbout'] =='1'){ echo "selected=selected "; }?> value='yes'>yes</option>
			<option <?php if($photog[0]['showOnAbout'] =='0'){ echo "selected=selected "; }?> value='no'>no</option>
		</select>
	</div>

	<div class="clear"></div>

	<div class="left">
		<b>Bio:</b>
	</div>
	<div class="right">
		<?php echo "<textarea cols=35 rows=10 name='bio' id='bio'>" . $photog[0]['bio'] . "</textarea>"; ?>
	</div>

	<div class="clear"></div>


	<!-- FILE UPLOAD -->
	<?php
		$res = $db->select("photographers","photographerID=" . intval($_GET['photog']));
		$fullName = $res[0]['fullName'];
		$fullName = preg_replace("|[^a-zA-Z ]{1,}|","",$fullName);
		$fullName = str_replace(" ","-",trim($fullName));
		$fullName = strtolower($fullName);
		$squareExists = $rectExists = false;
		if( file_exists($a=dirname(__FILE__) . '/../repository_images/new/about/headshots/' . $fullName . '.jpg') ){
			$squareExists = true;
		}
		if( file_exists($b=dirname(__FILE__) . '/../repository_images/new/about/headshots/lrg/' . $fullName . '.jpg') ){
			$rectExists = true;
		}
		$rand = rand(999999,999999999999999);
	?>
	<form id="file-form" action="handler.php" method="POST">
  		<div id='squarePhotos'>
			<div class='scaledLeft'>
				<?php
					if( $squareExists ){
						echo "<img src='/repository_images/new/about/headshots/{$fullName}.jpg?a=$rand'>";
					}else{
						echo "<img src='/repository_images/scaled-icon.png'>";
					}
				?>
				<br>
				<b>184px x 184px</b><br>(JPG only)
			</div>
			<div class='uploadLeft'>
				<h4>Upload thumbnail picture</h4>
				<input type="file" id="squarePhoto" name="squarePhoto"/>
			</div>
		</div>
  		<div id='rectanglePhotos'>
			<div class='scaledLeft'>
				<?php
					if( $rectExists ){
						echo "<img src='/repository_images/new/about/headshots/lrg/{$fullName}.jpg?a=$rand'>";
					}else{
						echo "<img src='/repository_images/scaled-icon.png'>";
					}
				?>
				<br>
				<b>300px x 420px</b><br>(JPG only)
			</div>
			<div class='uploadLeft'>
				<h4>Upload full-sized picture</h4>
				<input type="file" id="rectanglePhoto" name="rectanglePhoto"/>
			</div>
		</div>
	</form>










	<div class="button_new button_blue button_mid" onclick="saveData()" style='float:right;padding:20px;'>
            <div class="curve curve_left"></div>
            <span class="button_caption">Save</span>
            <div class="curve curve_right"></div>
          </div>
	<div class=clear></div>
	<h2>Examples</h2>
	<div id='examples'>

	<div class='left'>
	<h3>Current Examples:</h3>
<ul id="sortable1" class="connectedSortable list">
	<?php
		$res = $db->select("photographers_examples","photographerId=:id",array('id'=>$_GET['photog']));
		if( count($res) ){
			echo "<li class='ui-state-default'></li>";
			foreach($res as $index => $photo){
				$tourId = $photo['tourId'];
				$media = $photo['mediaId'];
				echo "<li class='ui-state-default'>";
				echo "<img width=150 src='http://spotlight-f-images-tours.s3.amazonaws.com/tours/{$tourId}/photo_400_{$media}.jpg'></li>";

			}
		}else{
				echo "<li class='ui-state-default'>";
				echo "</li>";
		}
	?>
</ul>
	</div> 
	<div class="right tour-selector">
	<h3>Tours:</h3>
	<select name='tourId' id='tourIdSelector'>
		<option>-- select one --</option>
		<?php
			$q = "SELECT t.tourid,t.address as address,t.title as title FROM tourprogress tp
				INNER JOIN tours t ON t.tourID = tp.tourid
				WHERE photographer=" . intval($_GET['photog']) . 
				" OR rephotographer=" . intval($_GET['photog']);
			$res = $db->run($q);
			foreach($res as $index => $row){
				echo "<option value='" . $row['tourid'] . "'>" . $row['tourid'] . " :: " .  $row['address'] . ' (' . $row['title'] . ")</option>";
			}
		?>
	</select>
<ul id="sortable2" class="connectedSortable list2">
</ul>
	</div>
	
	</div><!-- end examples -->
	<div class=clear></div>	
<?php
	}//endif
?>
</form>
</div>
</body>
</html>
