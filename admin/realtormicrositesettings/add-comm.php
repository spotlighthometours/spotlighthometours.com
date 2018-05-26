<?php
/*
 * Author: 
 * Date: 9/5/2014
 * Purpose: Manage community lists
 * Admin->Reltor Website Setting
 */

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('../../repository_inc/classes/inc.global.php');



global $db;



function issetVar($var, $default = ''){
	return ( isset($var) && !empty($var) )?$var : $default;
}


function getMLSFrmHTML($mlsProviders, $c){
	ob_start();
	?>
	<table id="subtbl_<?php echo $c?>" style="width:100%; margin: 0;">
		<tr>
	  		<td style="width:30%" class="name-td">MLS Provides</td>
	  		<td>
	  			<select id="mls_name" name="mls_name[<?php echo $c?>]">
					<option value="" >Select one</option>
	  				<?php
	  				foreach($mlsProviders as $mlsProvider){
	  					?>
	  					<option value="<?php echo $mlsProvider['id']; ?>" > <?php echo $mlsProvider['name']; ?></option>
	  				<?php } ?>
	  			</select>
	  		</td>
	  	</tr>
	  	<tr>
	  		<td class="name-td">MLS City/Community Name</td>
	  		<td><!--<input type="text" name="mls_city_name[<?php echo $c?>]" value="" >-->
				<select name="mls_city_name[<?php echo $c?>]" id="mls_city_name"></select> 
			</td>
	  	</tr>
	  	<tr>
	  		<td class=""></td>
	  		<td>
	  			<input type="button" class="delMLSInfo" value="Delete" style="background: red;border: 0;color: white;font-size: 12px;" >
	  			<input type="button" class="addMLSInfo" value="Add More MLS" style="background: blue;border: 0;color: white;font-size: 12px;" >
	  			<img class="loader" style="display: none" src="/admin/images/ajax-loader.gif">
	  		</td>
	  	</tr>

	</table>
		<script type="text/javascript">
			//jQuery(document).ready(function() {
				$("#mls_name").change(function() {
					$.get('/../repository_queries/mls_city_name.php?mls_provider=' + $(this).val(), function(data) {
						$("#mls_city_name").html(data);
					});	
					//console.log(data);
				});
			 
			//});
		</script>
	<?php
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}







// Include appplication's global configuration

showErrors();
clearCache();

$users = new users();

$users->authenticateAdmin();
$adminID = $_SESSION['admin_id'];

$mls = new mls();
$states = listStates();

$micrositesetting = new micrositesetting();
// $admin = new administrator();

$micrositecommunity = $micrositesetting->getSettingItem();
$mlsProviders = $mls->getProviders();


// handle for delete community confirmation
if( isset($_GET['delete']) ){
	global $db;
	$db->run("DELETE FROM microsite_setting WHERE id=" . intval($_GET['delete']));
	$alert = "Community deleted";
}

// Handle Video Upload
if(isset($_GET['dmajax']) && isset($_GET['comm_vdo']) ){

    $return = array("status"=>false, "response"=>"");
  
    $rel_upload_path =   "/microsite-uploads/community/videos/";
    $upload_path =  dirname(dirname(__DIR__)). $rel_upload_path;

    if( isset( $_FILES ) && !empty($_FILES) )
    {  
		foreach( $_FILES as $file )
        {   
        		//add in array how many video format you want.
        		// eg. "webm", "mp4", "ogv" 
        	    $allowed_extensions = array("mp4", "ogv", "flv", "mov");
    			$file_name_temp = explode(".", $file['name']);
    			$extension = end($file_name_temp);
    			//pr(111);
			if(is_uploaded_file($file['tmp_name']) && in_array($extension, $allowed_extensions)) {
				
				$time = time();
				$sourcePath = $file['tmp_name'];


				$relTargetPath = $rel_upload_path."".$time.'_'.$file['name'];
				$targetPath = $upload_path."".$time.'_'.$file['name'];
				if(move_uploaded_file($sourcePath, $targetPath)) {
					$return['status'] = true;
					$return['path'] = $relTargetPath;
					$return['response'] = "File Uploaded Successfully!";
				}

			}else{
				$return['error'] ='file extension not allowd';
				}
		}
	}
	
	echo json_encode($return);
	die();
}

// Handle image Upload
if(isset($_GET['dmajax']) && isset($_GET['comm_img']) ){

    $return = array("status"=>false, "response"=>"video not uploaded");
   
    $rel_upload_path =   "/microsite-uploads/community/images/";
    $upload_path =  dirname(dirname(__DIR__)). $rel_upload_path;

    if( isset( $_FILES ) && !empty($_FILES) )
    {
		foreach( $_FILES as $file )
        {   
        		//add in array how many video format you want.
        		// eg. "png", "gif", "jpeg" 
        	    $allowed_extensions = array("png", "jpeg", "jpg", "gif");
    			$file_name_temp = explode(".", $file['name']);
    			$extension = end($file_name_temp);

			if(is_uploaded_file($file['tmp_name']) && in_array($extension, $allowed_extensions)) {

				$time = time();
				$sourcePath = $file['tmp_name'];


				$relTargetPath = $rel_upload_path."".$time.'_'.$file['name'];
				$targetPath = $upload_path."".$time.'_'.$file['name'];
				if(move_uploaded_file($sourcePath, $targetPath)) {
					$return['status'] = true;
					$return['path'] = $relTargetPath;
					$return['response'] = "File Uploaded Successfully!";
				}

			}else{
				 $return['error'] ='file extension not allowd';
				}
		}
	}
	
	echo json_encode($return);
	die();
}

// Handle community form
if(isset($_GET['dmajax']) && isset($_GET['comm_frm']) ){
		

	$return = array("status"=>false, "response"=>"");


	if( empty($_POST['comm_name']))
	{
		header('HTTP/1.1 500 Internal Server Error');
	}
	else
	{
	  $comm_name= $_POST['comm_name'];
	}

	$uid = issetVar( $_POST['uid'], 0 );
	
	$state = issetVar( $_POST['state'] );
	$mls_names = issetVar( $_POST['mls_name'] );
	$mls_city_names = issetVar( $_POST['mls_city_name'] );
	$vdopath = issetVar( $_POST['vdoupload'] );
	$imgpath = issetVar( $_POST['imgupload'] );

		// pr($mls_names, 1);

		//Save All MLS Names Looping through posted MLS IDs
		$c = 0;
		if(!empty($mls_names)){

			foreach($mls_names as $mls_name){

				if( trim($mls_city_names[$c]) != '' ){

					$sitesetting = array(
			  	                        "user_id"=> $adminID,
			  	                        "community_name" => $comm_name,
			  	                        "state" => $state,
										"mls_name" => $mls_name,
										"mls_city_name" => $mls_city_names[$c],
										"video" => $vdopath,
										"photo" => $imgpath
			  	                        );

					try{
						
						$micrositesetting->saveMicrositesetting( $sitesetting, $uid );
						$return['status'] = true;
						$return['response'] = "";
						$return['mlsinfo'] = getMLSFrmHTML($mlsProviders, 0);

					}catch (Exception $e) {

						$e->getMessage();

					}
				}

				

				$c++;
			}
		}

	// $mls_name = implode(',', $mls_names);
    

  	/*$sitesetting = array(   
  	                        "user_id"=> $adminID,
  	                        "community_name" => $comm_name,
  	                        "state" => $state,
							"mls_name" => $mls_name,
							"mls_city_name" => $mls_city_name,
							"video" => $vdopath,
							"photo" => $imgpath
  	                         );

	try{
		$micrositesetting->saveMicrositesetting( $sitesetting, $uid );
		$return['status'] = true;
		$return['response'] = "";
	}catch (Exception $e) {
		$e->getMessage();
	}*/
	
	$return['REQUEST'] = $_REQUEST;
	echo json_encode($return);
	die();
}


if(isset($_GET['dmajax']) && isset($_GET['mlsHTML']) ){

	// $count = $_POST['num'];
	$count = $_GET['mlsHTML'];
	$response = array('status'=>true, 'response'=>getMLSFrmHTML($mlsProviders, $count), 'error'=>'' );
	echo json_encode( $response );
	die();
}


//Fetch MLLS Providers again after delete
$mlsProviders = $mls->getProviders();

$mode = "new";

//Edit Mode Setting
if(isset($_GET['action']) && isset($_GET['uid'])){
	$action = trim($_GET['action']);
	$uid = intval($_GET['uid']);

	if($action == "edit"){
		$editUid = trim($_GET['action']);
		foreach($micrositecommunity as $comSettings){
			if($uid == $comSettings['id']){
				$currentSetting = $comSettings;
				$mode = "edit";
				break;
			}
		}
	}
}


foreach($micrositecommunity as $id){
    $ID = $id['id'];
}
foreach($micrositecommunity as $ID){
    $id = $ID['id'];
    ++$id;
}



?>
<!DOCTYPE html>
<html lang='en' dir='ltr' itemscope itemtype="http://schema.org/QAPage">
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<title>Community</title>
	
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/cerulean/bootstrap.min.css'/>
	<link rel='stylesheet' href='icon/css/fontello.css'>
	
<style>
	body {
	margin: 0;
	padding: 0;
	}
	td.name-td, th {
			background-color: #c4dafd;
			text-align: left;
		}
		td {
		    padding: 5px 10px;
		    background-color: #e8eef7;
		}
	table {
		width: 50%;
	}
		form.adm-community-form {
	    padding-bottom: 20px;
	}
	table.community-data-list {
		width: auto;
	}
	table.community-data-list td, th {
		padding: 10px;
	}
	table.community-data-list th {
		color: #0033cc;
		font-weight: lighter;
		text-decoration: underline;
	}
	table.community-data-list td.list-head {
		color: #0033cc;
		text-decoration: underline;
	}
	table.community-data-list tr:nth-child(odd) td {
	    background-color: #fff;
	}
	select {
	    width: 60%;
	    border: 1px solid #ccc;
	    border-radius: 4px;
	}
	td.select-state select {
		width: 50%;
	}
	.adm-community-form input#videoupload,
	.adm-community-form input#photoupload {
	    float: left;
	    width: 60%;
	}
	tr {
	    border: 2px solid #fff;
	}
	td, th {
	    border-left: 2px solid #fff;
	    border-right: 2px solid #fff;
	}
	td.sub-up {
	    background-color: transparent;
	}
	p.exam-vid {
    display: block;
    float: left;
    width: 50%;
    font-size: 9px;
	}
	 label#videoupload-error, label#photoupload-error {
	    margin-bottom: -50px;
	    margin-top: 34px;
		}
		label.error {
		    color: #ff0000;
		    font-size: 11px;
		    display: block;
		}a.del-icon img {
	    height: 15px;
	    padding-left: 20%;
	    padding-top: 3px;
		}
		a.delete img {
	    height: 15px;
	    padding-left: 25%;
	    }
	    div#success_message {
	    border: 1px solid #ccc;
	    padding: 10px 10px;
	    border-radius: 5px;
	    position: absolute;
	    right: 5%;
	    background-color: #c4dafd;
	    font-weight: bold;
	}
	#popupPlayerOverlay { 
		display: none;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 11;
    background: rgba(0,0,0,0.7);}
	#popupPlayerOverlay #vdoPlayerCont {
    	position:fixed;
    	top:100px;
    	left:50%;
    	width: 700px !important;
    	height: 400px !important;
    	margin-left: -350px;
    	background:#FFF;
    	z-index: 12;
    }

    #popupPlayerOverlay a.closebtn{
    	position: absolute;
    	top:20px;
    	right: 20px;
    	color: #FFF;
    	font-size: 25px;
    	z-index: 13;
    	cursor: pointer; 
    }
    video{ cursor: pointer; }
    table.community-data-list video,
     table.community-data-list img.img_thumb {
 /*   height: 150px;*/
    width: 203px;
    object-fit: fill;
    }
    a.deleted {
    display: inline-block;
    }
   
</style>
 <script src='https://code.jquery.com/jquery-1.11.3.min.js'></script>
  	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
	<script src="../../repository_inc/jquery.form.min.js" type="text/javascript"></script><!-- jQuery Form -->
	<script src='//ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js'></script>
	<script src="../../jwplayer/7/jwplayer.js" type="text/javascript"></script><!-- User CP Realtor Website JS file -->
    <script>jwplayer.key="Qp76XSwnVr3E0zH3GS7MY/A2R11i9fODdXIarfr5nHA="</script>
    <script src="../../swfobject.js" language="JavaScript" type="text/javascript" /></script>
    <script src="jquery-html5.js" language="JavaScript" type="text/javascript" /></script>
    <script src="froogaloop2.min.js" language="JavaScript" type="text/javascript" /></script>
	<script src="../../repository_inc/add-comm.js" type="text/javascript"></script><!-- upload photo from add-comm.php not been used add this point-->
</head>
<body>
	<div class="admin-community">

  		<?php
  			// pr($currentSetting);

  			if($mode == 'new'){
  				require_once( __DIR__ . "/frm-new-community.php");
  			}else if( $mode == 'edit' ){
  				require_once( __DIR__ . "/frm-edit-community.php");
  			}
  		?>

	  	<h3>Communities </h3>
	  	<table class="community-data-list" id="community-tb">
	  		<tr>
	  			<th>Community Name</th>
	  			<th>State</th>
	  			<th>MLS</th>
	  			<th>MLS City Code/Name</th>
	  			<th>Background Video</th>
	  			<th>Background Photo</th>
	  			<th>Actions</th>
	  		</tr>
	  	
	  			<?php
	  			$delimg = '../images/delete-icon.png';
	  			$tickimg = '../images/check_mark.png';
	  			 foreach($micrositecommunity as $setting){
	  			 	?>
	  				<tr>
	  				<td><?php echo $setting['community_name'] ?></td>
	  				<td><?php echo $setting['state'] ?></td>	
	  				<td><?php
	  					    $mls_names = $setting['mls_name'];
						  $sepreate = explode( "," , $mls_names);
						  $mls_name = array();
						        foreach($sepreate as $mls_names){
						          if(trim($mls_names) != ""){
						          if(!isset($mls_name[$mls_names]))
						           $mls_name[] = trim($mls_names);
						            }
						        }
						         // pr($mls_name);
						        foreach ($mls_name as $mls_id){
	  				foreach($mlsProviders as $mlsProvider){
	  					if($mlsProvider['id'] == $mls_id){
	  						echo "(" .$mls_id. ")" ." ". $mlsProvider['name'] . " &nbsp " ;
	  					}	 
	  				}
	  			}
	  				  ?></td>
	  				<td>
	  				<?php
	  					//pr($setting);
	  					echo $setting['mls_city_name'];
	  				 ?></td>

	  			
	  			    <td>
	  			    	<?php
	  			    if(trim($setting['video'])){
	  			    	echo '<div id="popupPlayer_'.$setting['id'].'"> </div>';
		  			    echo '<video id="pre_'.$setting['id'].'">'; 
	                    echo '<source class="vdo_thumb" src= "'.$setting['video'].'" >'; 
	                    echo '</video>';
	  			    }
	  			     ?></td>
	  			    <td> <?php
	  			    // echo '<img class="thumb" src="'.$list['path'].'"/>';

	  			    echo "<a  class='html5lightbox' href=' ". $setting['photo']."'>"; 
	  			    echo "<img id='img_".$setting['id']."' class='img_thumb' src=' ". $setting['photo']."' >";
	  			    echo'</a>';
	  			    ?>
	  			    </td>
	  			    <td>
	  			    <?php
	  			    echo '<a class="edit" href="?action=edit&uid=' . $setting['id'] . '">Edit</a>';
	  			    // echo  '<a class=delete href="?delete=' . $setting['id'] . '"><img src="'.$delimg.'" alt="delete"></a>'; 
	  			    echo  ' | <a class="delete" href="?delete=' . $setting['id'] . '">Delete</a>'; 
	  			    ?>
	  				</td>
	  				</tr>
	  				
	  				 <script type="text/javascript">
                      $(document).ready(function(){

                     	// console.log(jQuery("#popupPlayer_<?php echo $list['id']; ?>"));
                      	
                        jQuery("#pre_<?php echo  $setting['id'];?>").click(function(){
	                        // jQuery("#popupPlayer_<?php echo $list['id']; ?>").show();


	                        jwplayer("vdoPlayerCont").setup({
		                          "autostart": false,
		                          "controls": true,
		                          "displaydescription": true,
		                          "displaytitle": true,
		                          "flashplayer": "//ssl.p.jwpcdn.com/player/v/7.7.0/jwplayer.flash.swf",
		                          "hlshtml": true,
		                          "key": "Qp76XSwnVr3E0zH3GS7MY/A2R11i9fODdXIarfr5nHA=",
		                          "mute": false,
		                          "ph": 3,
		                          "pid": "qgcLUxn1",
		                          "playlist": [
		                          {
		                            "duration": 0,
		                            "sources": [
		                            {
		                              "file": "<?php echo $setting['video'];?>",
		                              // "image": "<?php echo $setting['photo'];?>",
		                             
		                            }
		                            ],
		                            "tags": "",
		                            "tracks": []
		                          }
		                          ],
		                          "plugins": {
		                          
		                          },
		                          "preload": "none",
		                          "primary": "html5",
		                          "repeat": false,
		                          "stagevideo": false,
		                          "stretching": "uniform",
		                          "visualplaylist": true
		                        });
	                        jQuery("#popupPlayerOverlay").show();

							
                        });
                      

                        jQuery("#popupPlayerOverlay a.closebtn").click(function(){
	                        jQuery("#popupPlayerOverlay").hide();
	                        jQuery("#popupPlayerOverlay #vdoPlayerCont").html('');
                        });
                         });
                      </script>
                       <script type="text/javascript">
						$(document).ready(function(){
						if( $("#img_<?php echo $setting['id']; ?>").attr('src') == '' ){
							$("#img_<?php echo $setting['id']; ?>").css("height", "150px");
							
						}
						
						});
					</script>
                     
	  	<?php	}

 			?>
	  			
	  		
	  	    
	  	</table>
  	</div>
 	<div id="popupPlayerOverlay"><div id="vdoPlayerCont"></div><a class="closebtn">X</a></div>  	



	<div id="rawfrm" style="display: none !important;">
	<?php //require(__DIR__.'/frm-new-community-frm.php'); ?>
	</div>


<script type="text/javascript">/************ handle for video upload for community*************/
	
		$(document).ready(function (e) {
			
			// Variable to store your files
			var vdofiles=[];

			// Add events
			jQuery('#vdotblcontainer').on('change', 'input[type=file]', prepareVdoUpload);

			// Grab the files and set them to our variable
			function prepareVdoUpload(event)
			{
				vdofiles = event.target.files;
			}

			$("#btn_vdo").on('click', function(e) {

				e.preventDefault();
				// Create a formdata object and add the files
				var data = new FormData();

				jQuery.each( vdofiles, function( key, value )
				{
					data.append(key, value);
				});


				$('#loading-image3').show();
				$.ajax({
						url: "/admin/realtormicrositesettings/add-comm.php?dmajax=1&comm_vdo=1", // Url to which the request is send
						type: "POST",             // Type of request to be send, called as method
						data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
						dataType: 'json',       // The content type used when sending data to the server.
						contentType: false,       // The content type used when sending data to the server.
						cache: false,             // To unable request pages to be cached
						processData:false,        // To send DOMDocument or non processed data file it is set to false
						success: function(data)   // A function to be called if request succeeds
						{
		 
							if(data){
								
								if(data.status){
									
									console.log(data);
								    jQuery("#vdoupload").val(data.path);
								    jQuery(".vdoMsg").html("<span class='preview' style='color:green;'>Video Uploaded to: "+data.path+"</span>");
								    // jQuery("#vdotblcontainer").prepend('<tr style="border:0;"> <td style="width:70%;border:0;"> <video id="newuploaded" style="width:100px;height:60px;" > <source class="vdo_thumb" src= "'+data.path+'" > </video> </td> <td style="width:30%;border:0"> <img id="loading-image3" style="display: none" src="../images/ajax-loader.gif"> <input type="button" name="btn_vdo_del" value="remove" id="btn_vdo_del"> </td> </tr>');

								    jQuery('#loading-image3').hide();
									jQuery("#btn_vdo").hide();
									jQuery("#vdoupload-error").remove();
								}
								if(!data.status){
									alert(data.error);
						        }
							}
						},

						 complete: function(){
					        $('#loading-image3').hide();
					      },

						failure : function(){
							console.log("AJAX Failed!");
						}

				});
			});
			

			/************ handle for image upload for community*************/
	
			// Variable to store your files
			var files;
			// Add events
			jQuery('#imgtblcontainer input[type=file]').on('change', prepareUpload);

			// Grab the files and set them to our variable
			function prepareUpload(event)
			{
				files = event.target.files;
			}

			$("#btn_img").on('click', function(e) {
				
				e.preventDefault();
				// Create a formdata object and add the files
				var data = new FormData();

				jQuery.each( files, function( key, value )
				{
					data.append(key, value);
				});
				$('#loading-image2').show();

				$.ajax({
						url: "/admin/realtormicrositesettings/add-comm.php?dmajax=1&comm_img=1", // Url to which the request is send
						type: "POST",             // Type of request to be send, called as method
						data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
						dataType: 'json',       // The content type used when sending data to the server.
						contentType: false,       // The content type used when sending data to the server.
						cache: false,             // To unable request pages to be cached
						processData:false,        // To send DOMDocument or non processed data file it is set to false
						success: function(data)   // A function to be called if request succeeds
						{
							
							if(data){
									
								if(data.status){
									console.log(data.path);
									jQuery("#imgupload").val(data.path);
									jQuery("#imgtblcontainer").prepend('<tr class="preview" style="border:0;"><td style="width:70%;border:0;"> <a class="html5lightbox" href="'+data.path+'"> <img class="img_thumb" src="'+data.path+'" style="width:100px;height:60px;"  /> </a></td> <td style="width:30%;border:0"> <img id="loading-image3" style="display: none" src="../images/ajax-loader.gif"> <input type="button" name="btn_img_del" value="remove" id="btn_img_del"> </td>	</tr>');
									// jQuery(".imgMsg").html("<span style='color:green;'>Image Uploaded to: "+data.path+"</span>");
									jQuery("#btn_img").hide();
									jQuery("#imgupload-error").remove();
									// alert("Video Uploaded");
								}
								if(!data.status){
									alert(data.error);
								}
								
								
							}

						},
						 complete: function(){
					        $('#loading-image2').hide();
					      },
						failure : function(){
							console.log("AJAX Failed!");
						}

				});
			});

			$(document).on('click', "#btn_vdo_del", function(e) {
				jQuery("#vdoupload").val('');
				jQuery("#btn_vdo").show().val('Upload Video');
				jQuery(this).closest('tr').remove();
			});

			$(document).on('click', "#btn_img_del", function(e) {
				jQuery("#imgupload").val('');
				jQuery("#btn_img").show().val('Upload Photo');
				jQuery(this).closest('tr').remove();
			});



 			/************ handle for community form*************/

	        $("#adm-community-form").validate(
	        {
	            // Rules for form validation
	            rules:
	            {
	                comm_name: { required: true },
	                mls_city_name: { required: true },
	                vdoupload: { required: false },
	                imgupload: { required: false }
	            },
	                                
	            // Messages for form validation
	            messages:
	            {
	                comm_name: { required: 'Please enter your Community name' },
	                mls_city_name: { required: 'Please enter your MLS city name' },
	                vdoupload: { required: 'Please upload video' },
	                imgupload: { required: 'Please upload image' }
	  
	            },
	          
	           // Ajax form submition
	            submitHandler: function(form)
	            {  
				   	$('#loading-image1').show();
				     $.ajax({
					      url: "/admin/realtormicrositesettings/add-comm.php?dmajax=1&comm_frm=1",
					      data: $( "#adm-community-form" ).serialize(),
					      type: "POST",
					      dataType: "json",
					      
				     success: function( data )
				     {   
				      	if(data){
				      		
		                   	$('#success_message').show("slow");
							setTimeout(function() {
									$('#success_message').hide("slow");
								}, 2000 );

						    $("#adm-community-form").trigger("reset");
						    $("#vdotblcontainer .preview").remove();
						    $("#imgtblcontainer .preview").remove();
						    jQuery("#mlsinfo").html(data.mlsinfo);
						    // $("#adm-community-form").replaceWith( jQuery("#rawfrm").html() );

						}
						$("#community-tb").load(window.location + " #community-tb");
				    },
				     complete: function(){
						        $('#loading-image1').hide();
						      },
				      failure : function(){
							console.log("AJAX Failed!");
						}

		  });
		 }
		});
	}); 
</script>
<script type="text/javascript">/******** delete *********/
		$(document).ready(function(){
			$("#community-tb").on("click", "a.delete",function(e){
				if( !confirm("Are you sure you want to delete this?") ){
					e.preventDefault();
				}else{
					$("#body").load(window.location + " #body");
				}
				
			});
		});
</script>




</body>
</html>