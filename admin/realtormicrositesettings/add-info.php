<?php
/*
 * Author: 
 * Date: 9/5/2014
 * Purpose: Manage Reltor microsite info
 * Admin->Reltor Website Setting
 */

require_once('../../repository_inc/classes/inc.global.php');

error_reporting(-1);
ini_set('display_errors',1);

global $db;
/*
*/

// Include appplication's global configuration

showErrors();
clearCache();

$users = new users();

$users->authenticateAdmin();
$adminID = $_SESSION['admin_id'];

$micrositesetting = new micrositesetting();
$micrositeimage = $micrositesetting->getImages();

foreach($micrositeimage as $id){
    $ID = $id['id'];
}

foreach($micrositeimage as $ID){
    $id = $ID['id'];
    ++$id;
}


// Handle Video Upload
if(isset($_GET['dmajax']) && isset($_GET['upload_vdo']) ){

    $return = array("status"=>false, "response"=>"");
   
    $rel_upload_path =   "/microsite-uploads/videos/";
    $upload_path =  dirname(dirname(__DIR__)). $rel_upload_path;

    if( isset( $_FILES ) && !empty($_FILES) )
    {
		foreach( $_FILES as $file )
        {   
        		//add in array how many video format you want.
        		// eg. "webm", "mp4", "ogv" 
        	    $allowed_extensions = array("mp4", "ogv", "mov", "flv");
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

// Handle submit Video form
if(isset($_GET['dmajax']) && isset($_GET['vdo_frm']) ){
		

	$return = array("status"=>false, "response"=>"");


				if( empty($_POST['video_name']))
				{
					header('HTTP/1.1 500 Internal Server Error');
				}
				else
				{
				  $vdname= $_POST['video_name'];
				}

				//if(empty($_POST['theme_name']) && empty($_POST['holidays']) && empty($_POST['seasons']))
				//{
				//   header('HTTP/1.1 500 Internal Server Error');
				//}
				//else
				//{
				//     $thname= $_POST['theme_name'];
				//}
				if(isset($_POST['theme_name'])){
					$videothname = $_POST['theme_name'];
				}else{
					$videothname = '';
				}
				if(isset($_POST['state'])){
					$state = $_POST['state'];
				}
				
				if(isset($_POST['city_community'])){
					$city_community = $_POST['city_community'];
				}
				if(isset($_POST['holidays'])){
					$holidays = $_POST['holidays'];
				}
				if(isset($_POST['seasons'])){
					$seasons = $_POST['seasons'];
				}
			
				$path1 = $_POST['video'];

				
              	$sitesetting = array( 	"id" => $id,
              	                        "user_id"=> $adminID,
              	                        "title" => $vdname,
              	                        "path" => $path1,
										"theme" => $videothname,
										"state" => $state,
										"city_community" => $city_community,
										"holidays" => $holidays,
										"seasons" => $seasons,
										"filetype" => "video",
										"fileExt" => $extension
              	                         );
				
            	$micrositesetting->saveMicrositeImage( $sitesetting );

            	$return['status'] = true;
            	$return['response'] = "";
		  	    $return['REQUEST'] = $_REQUEST;
  	 		  echo json_encode($return);
            die();
}

// Handle upload Image
if(isset($_GET['dmajax']) && isset($_GET['upload_img']) ){

 	 $return = array("status"=>false, "response"=>"");
 	 $rel_upload_path =   "/microsite-uploads/images/";
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
					if(move_uploaded_file($sourcePath,$targetPath)) {
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


// Handle submit Image form
if(isset($_GET['dmajax']) && isset($_GET['img_frm']) ){
		

	$return = array("status"=>false, "response"=>"");


				if( empty($_POST['photo_name']))
				{
					header('HTTP/1.1 500 Internal Server Error');
				}
				else
				{
				  $imgname= $_POST['photo_name'];
				}

				//if( empty($_POST['theme_name']))
				//{
				//   header('HTTP/1.1 500 Internal Server Error');
				//}
				//else
				//{
				 //    $thname= $_POST['theme_name'];
				//}
				if(isset($_POST['theme_name'])){
					$thname = $_POST['theme_name'];
				}else{
					$thname ='';
				}
				 
				if(isset($_POST['state'])){
					$state = $_POST['state'];
				}
				if(isset($_POST['city_community'])){
					$city_community = $_POST['city_community'];
				}
				if(isset($_POST['holidays'])){
					$holidays = $_POST['holidays'];
				}else{
					$holidays ='';
				}
				if(isset($_POST['seasons'])){
					$seasons = $_POST['seasons'];
				}else{
					$seasons = '';
				}

				$path = $_POST['image'];
				
				
              	$data = array( 	"id" => $id,
              	                        "user_id"=> $adminID,
              	                        "title" => $imgname,
              	                        "path" => $path,
										"theme" => $thname,
										"state" => $state,
										"city_community" => $city_community,
										"holidays" => $holidays,
										"seasons" => $seasons,
										"filetype" => "image",
										"fileExt" => $extension
              	                         );
				
            	$micrositesetting->saveMicrositeImage($data);
            	$return['status'] = true;
            	$return['response'] = "";
		  
		  	    $return['REQUEST'] = $_REQUEST;
  	 		echo json_encode($return);
            die();
}
 


// handle for delete video confirmation
if( isset($_GET['deleted']) ){
	global $db;
	$db->run("DELETE FROM microsite_photo WHERE id=" . intval($_GET['deleted']));
	$alert = "Video deleted";
} 
// handle for delete image confirmation
if( isset($_GET['delete']) ){
	global $db;
	$db->run("DELETE FROM microsite_photo WHERE id=" . intval($_GET['delete']));
	$alert = "Image deleted";
} 

//}
?>
<!DOCTYPE html>
<html lang='en' dir='ltr' itemscope itemtype="http://schema.org/QAPage">
<head>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
	<title>Add New</title>
	
	<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootswatch/3.3.5/cerulean/bootstrap.min.css'/>
	<link rel='stylesheet' href='icon/css/fontello.css'>

<style>
	body {
	margin: 0;
	padding: 0;
		}
		table {
			width: 50%;
		}
		td.name-td, th {
			background-color: #c4dafd;
			text-align: left;
		}
		td {
		    padding: 5px 10px;
		    background-color: #e8eef7;
		}
		.bg-video-form table input[type="text"] {
		    width: 60%;
		}
		td.sub-up {
			background-color: transparent;
		}
		table.data-list td, th {
			padding: 5px 10px;
		}
		table.data-list th {
			color: #0033cc;
			font-weight: lighter;
			text-decoration: underline;
		}
		table.data-list td.list-head {
			color: #0033cc;
			text-decoration: underline;
		}
		table.data-list tr:nth-child(odd) td {
		    background-color: #fff;
		}
		form.bg-video-form span.button_caption {
	    border: 1px solid #999;
	    padding: 2px 6px;
	    background-color: buttonface;
		}
		td, th {
		    border-left: 2px solid #fff;
		    border-right: 2px solid #fff;
		}
		tr {
		    border: 2px solid #fff;
		}
		.bg-photo-form input#fileupload {
		    float: left;
		    width: 50%;
		}
		.bg-video-form input#vdoupload {
		    width: 50%;
		    float: left;
		}
		p.exam-vid {
		    display: block;
		    float: left;
		    width: 55%;
		    font-size: 9px;
		}
		input#fileUploadBtn,
		input#imguploadbtn {
		    margin-top: 8px;
		}
		a.del-icon img {
	    height: 15px;
	    padding-left: 20%;
	    padding-top: 3px;
		}
		a.delete img {
	    height: 15px;
	   
	    }
	    a.deleted img {
	    height: 15px;
	  
	    display: table-cell;
	    vertical-align: middle;
	    margin-top: 70px;
	}

	    label#vdoupload-error, label#fileupload-error {
	    margin-bottom: -50px;
	    margin-top: 34px;
		}
		label.error {
		    color: #ff0000;
		    font-size: 11px;
		    display: block;
		}
		div.ajax_response {
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
    table#add-table video,
     table#add-table img.img_thumb {
    height: 150px;
    width: 203px;
    object-fit: fill;
    }
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
  
                             
    a.delete{ display: inline-block;     padding-left: 23%; }    
    /*a.deleted{ display: inline-block; height: 100%;     padding-left: 20%; }  */  
   
</style>
    <script src='https://code.jquery.com/jquery-1.11.3.min.js'></script>
  	<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
	<script src="../../repository_inc/jquery.form.min.js" type="text/javascript"></script><!-- jQuery Form -->
	<script src='//ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js'></script>
	<script src="realtorsetting.js" type="text/javascript"></script>
	<script src="../../jwplayer/7/jwplayer.js" type="text/javascript"></script><!-- User CP Realtor Website JS file -->
    <script>jwplayer.key="Qp76XSwnVr3E0zH3GS7MY/A2R11i9fODdXIarfr5nHA="</script>
    <script src="jquery-html5.js" language="JavaScript" type="text/javascript" /></script>
    <script src="froogaloop2.min.js" language="JavaScript" type="text/javascript" /></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
</head>
<body>
	<div class="admin-bg">
  		<h3>Add Background Video</h3>
  		<form action="#" id="bg-video-form" method="POST" class="bg-video-form">
		  	<table>
			  	<tr>
			  		<td class="name-td">Video Name</td>
			  		<td><input type="text" id="video_name" name="video_name"></td>
			  	</tr>
			  	<tr>
			  		<td class="name-td">Upload File</td>
			  		<td>

			  			<input type="file" name="fileupload" value="fileupload" id="vdoupload">
			  			<p class="exam-vid">(e.g: .mp4, .ogv, .mov)</p>
			  			<div class="error"></div>
			  			<img id="loading-image4" style="display: none" src="../images/ajax-loader.gif">
			  			<input type="button" name="videoupload" value="video upload" id="fileUploadBtn">
					   <input type="hidden" name="video" value="" id="uploadedVdoFile">
			  		</td>
			  	</tr>
			  	<tr>
			  		<td class="name-td">Theme</td>
			  		<td><input type="text" name="theme_name" class="theme_season_holiday" id="theme_name"></td>
			  	</tr>
				<tr>
			  		<td class="name-td">State/City Community</td>
			  		<td>
						<select name="state">
							<option value="AL">Alabama</option>
							<option value="AK">Alaska</option>
							<option value="AZ">Arizona</option>
							<option value="AR">Arkansas</option>
							<option value="CA">California</option>
							<option value="CO">Colorado</option>
							<option value="CT">Connecticut</option>
							<option value="DE">Delaware</option>
							<option value="DC">District of Columbia</option>
							<option value="FL">Florida</option>
							<option value="GA">Georgia</option>
							<option value="HI">Hawaii</option>
							<option value="ID">Idaho</option>
							<option value="IL">Illinois</option>
							<option value="IN">Indiana</option>
							<option value="IA">Iowa</option>
							<option value="KS">Kansas</option>
							<option value="KY">Kentucky</option>
							<option value="LA">Louisiana</option>
							<option value="ME">Maine</option>
							<option value="MD">Maryland</option>
							<option value="MA">Massachusetts</option>
							<option value="MI">Michigan</option>
							<option value="MN">Minnesota</option>
							<option value="MS">Mississippi</option>
							<option value="MO">Missouri</option>
							<option value="MT">Montana</option>
							<option value="NE">Nebraska</option>
							<option value="NV">Nevada</option>
							<option value="NH">New Hampshire</option>
							<option value="NJ">New Jersey</option>
							<option value="NM">New Mexico</option>
							<option value="NY">New York</option>
							<option value="NC">North Carolina</option>
							<option value="ND">North Dakota</option>
							<option value="OH">Ohio</option>
							<option value="OK">Oklahoma</option>
							<option value="OR">Oregon</option>
							<option value="PA">Pennsylvania</option>
							<option value="RI">Rhode Island</option>
							<option value="SC">South Carolina</option>
							<option value="SD">South Dakota</option>
							<option value="TN">Tennessee</option>
							<option value="TX">Texas</option>
							<option value="UT">Utah</option>
							<option value="VT">Vermont</option>
							<option value="VA">Virginia</option>
							<option value="WA">Washington</option>
							<option value="WV">West Virginia</option>
							<option value="WI">Wisconsin</option>
							<option value="WY">Wyoming</option>
						</select>
						<input type="text" name="city_community" id="city_community">
					</td>
			  	</tr>
				<tr>
			  		<td class="name-td">Holidays</td>
			  		<td><input type="text" name="holidays" class="theme_season_holiday" id="holidays"></td>
			  	</tr>
				<tr>
			  		<td class="name-td">Seasons</td>
			  		<td><select name="seasons" class="theme_season_holiday" id="seasons">	
							<option value="">Select one</option>
							<option value="spring">Spring</option>
							<option value="summer">Summer</option>
							<option value="fall">Fall</option>
							<option value="winter">Winter</option>
						</select>
					</td>
			  	</tr>
			  	<tr>
			  		<td class="sub-up">
			  		
			   	 	<input type="submit" id="btn_sub" value="Save">
			   	 	<img id="loading-image5" style="display: none" src="../images/ajax-loader.gif">
			  		</td>
			  	</tr>
			  	<div id="success_message1" class="ajax_response" style="float:left; display:none"><p>Video successfully added</div>
		  	</table>
	  	</form>

	  	<h3>Add Background Photo</h3>
	  	<form action="#" id="bg-photo-form" class="bg-photo-form">
		  	<table>
			  	<tr>
			  		<td class="name-td">Photo Name</td>
			  		<td><input type="text" name="photo_name" id="photo_name"></td>
			  	</tr>
			  	<tr>
			  		<td class="name-td">Upload File</td>
			  		<td>
			  			<input type="file" name="fileupload" value="fileupload" id="fileupload">
			  			<p class="exam-vid">(e.g: .jpg, .jpeg, .png, .gif)</p>
			  			<img id="loading-image6" style="display: none" src="../images/ajax-loader.gif">
			  			<input type="submit" name="imageupload" value="image upload" id="imguploadbtn">
			  			  <input type="hidden" name="image" value="" id="uploadimgfile">
			  		</td>
			  	</tr>
			  	<tr>
			  		<td class="name-td">Theme</td>
			  		<td><input type="text" name="theme_name" id="theme_name" class="photo_group"></td>
			  	</tr>
				<tr>
			  		<td class="name-td">State/City Community</td>
			  		<td>
						<select name="state">
							<option value="AL">Alabama</option>
							<option value="AK">Alaska</option>
							<option value="AZ">Arizona</option>
							<option value="AR">Arkansas</option>
							<option value="CA">California</option>
							<option value="CO">Colorado</option>
							<option value="CT">Connecticut</option>
							<option value="DE">Delaware</option>
							<option value="DC">District of Columbia</option>
							<option value="FL">Florida</option>
							<option value="GA">Georgia</option>
							<option value="HI">Hawaii</option>
							<option value="ID">Idaho</option>
							<option value="IL">Illinois</option>
							<option value="IN">Indiana</option>
							<option value="IA">Iowa</option>
							<option value="KS">Kansas</option>
							<option value="KY">Kentucky</option>
							<option value="LA">Louisiana</option>
							<option value="ME">Maine</option>
							<option value="MD">Maryland</option>
							<option value="MA">Massachusetts</option>
							<option value="MI">Michigan</option>
							<option value="MN">Minnesota</option>
							<option value="MS">Mississippi</option>
							<option value="MO">Missouri</option>
							<option value="MT">Montana</option>
							<option value="NE">Nebraska</option>
							<option value="NV">Nevada</option>
							<option value="NH">New Hampshire</option>
							<option value="NJ">New Jersey</option>
							<option value="NM">New Mexico</option>
							<option value="NY">New York</option>
							<option value="NC">North Carolina</option>
							<option value="ND">North Dakota</option>
							<option value="OH">Ohio</option>
							<option value="OK">Oklahoma</option>
							<option value="OR">Oregon</option>
							<option value="PA">Pennsylvania</option>
							<option value="RI">Rhode Island</option>
							<option value="SC">South Carolina</option>
							<option value="SD">South Dakota</option>
							<option value="TN">Tennessee</option>
							<option value="TX">Texas</option>
							<option value="UT">Utah</option>
							<option value="VT">Vermont</option>
							<option value="VA">Virginia</option>
							<option value="WA">Washington</option>
							<option value="WV">West Virginia</option>
							<option value="WI">Wisconsin</option>
							<option value="WY">Wyoming</option>
						</select>
						<input type="text" name="city_community" id="city_community">
					</td>
			  	</tr>
				<tr>
			  		<td class="name-td">Holidays</td>
			  		<td><input type="text" name="holidays" id="holidays" class="photo_group"></td>
			  	</tr>
				<tr>
			  		<td class="name-td">Seasons</td>
			  		<td><select name="seasons" id="seasons" class="photo_group">	
							<option value="">Select one</option>
							<option value="spring">Spring</option>
							<option value="summer">Summer</option>
							<option value="fall">Fall</option>
							<option value="winter">Winter</option>
						</select>
					</td>
			  	</tr>
			  	<tr>
			  		<td class="sub-up">
			  			<input type="submit" id="img_btn" value="Save">
			  			<img id="loading-image7" style="display: none" src="../images/ajax-loader.gif"></td>
			  	</tr>
			  	<div id="success_message2" class="ajax_response" style="float:left; display:none"><p>Image successfully added</div>
		  	</table>
	  	</form>

	  	<h3>Background Photo/Video </h3>
	  	<table class="data-list" id="add-table">
	  		<tr>
	  			<th>File Name</th>
	  			<th>Theme</th>
				<th>City/Community</th>
				<th>Holidays</th>
				<th>Seasons</th>
	  			<th>Background Photo</th>
	  			<th>Background Video</th>
	  			<th>Action</th>
	  		</tr>
	  		<?php
	  			$delimg = '../images/delete-icon.png';
	  			$tickimg = '../images/check_mark.png';
	  		    foreach($micrositeimage as $list) {
	  			?>
	  		<tr>
	  			<td class="list-head"> <?php  echo "<a href=' ".$list['path']."'> ".$list['title']."</a>" ;?></td>

	  			<td> <?php echo $list['theme'];?></td>
				<td> <?php echo $list['city_community'];?></td>
				<td> <?php echo $list['holidays'];?></td>
				<td> <?php echo $list['seasons'];?></td>

	  			<td> <?php if( $list['filetype'] === image){
	  				echo "<a class='html5lightbox' href=' ". $list['path']."'>"; 
	  			    echo "<img class='img_thumb' src=' ". $list['path']."'>";
	  			    echo'</a>';
	  				// echo '<a class=delete href="?delete=' . $list['id'] . '">';
	  			 //    echo  '<img src="'.$delimg.'" alt="delete"></a>';

	  				 }else{

	  				}
	  				;?> </td>

	  			<td> <?php if( $list['filetype'] === video){
	  				echo '<div id="bg_vdo">';
	  				echo '<div id="popupPlayer_'.$list['id'].'"> </div>';

	  				echo '<video id="pre_'.$list['id'].'">'; 
                    echo '<source class="vdo_thumb" src= "'.$list['path'].'" >'; 
                    echo '</video>'; 
	  				
	  			
	  			    echo '</div>'; ?>
	  			  

                    <script type="text/javascript">
                      $(document).ready(function(){

                      console.log(jQuery("#popupPlayer_<?php echo $list['id']; ?>"));
                      
                      

                        jQuery("#pre_<?php echo  $list['id'];?>").click(function(){
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
		                              "file": "<?php echo $list['path'];?>",
		                             
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
	 
	  				<?php }else{

	  				}
	  				;?> </td>
	  				  <td><?php
	  			      echo  '<a class="delete" href="?delete=' . $list['id'] . '">Delete</a>';  ?></td>
	  		</tr>
	  <?php	} ?>
	  		
	  		 
	  	</table>
  	</div>
  	<div id="popupPlayerOverlay"><div id="vdoPlayerCont"></div><a class="closebtn">X</a></div>
   
	<script type="text/javascript">
		$(document).ready(function(){
			$("a[class='deleted']").bind("click",function(e){
				if( !confirm("Are you sure you want to delete this?") ){
					e.preventDefault();
				}
				$("#add-table").load(window.location + " #add-table");
			});
		});
	</script>
	
	<script type="text/javascript">
	 	$(document).ready(function(){
	 		$("a[class='delete']").bind("click",function(e){
	 			if( !confirm("Are you sure you want to delete this?") ){
 				e.preventDefault();
	 			}
	 			$("#add-table").load(window.location + " #add-table");
	 		});
	 	});
	</script>
	

</body>
</html>