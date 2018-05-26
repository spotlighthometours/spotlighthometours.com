<?php
/* Author:  William Merfalen
 * Date:	10/06/2014
 * Allow an admin to create their own video of the week html 
*/
require_once('../repository_inc/classes/inc.global.php');
//ShowErrors();
session_start();
define("VOTW_TOUR_PREFIX","http://www.spotlighthometours.com/us/");
define("VOTW_VIDEO_PREFIX","http://www.spotlighthometours.com/images/email-campaigns/videos-of-the-week/");
define("VOTW_VIDEO_UPLOAD",'/images/email-campaigns/videos-of-the-week');
define("MULTIPLY_BY",2);
define("IMAGE_SIZE_THRESHOLD",900);
define("VOTW_FILE",$_SERVER['DOCUMENT_ROOT'] . "/new/demos/votw.txt");

global $db;
$votw = new votw;
$tourIdPrefix = VOTW_TOUR_PREFIX;
$uploaded = false;

function limitDesc($txt){
	if( strlen($txt) > 50 ){
		return substr($txt,0,50) . " ... ";
	}else{
		return $txt;
	}
}

if( isset($_POST['verifyTourId']) ){
    $t = new tours;
    die(json_encode(['exists' => $t->loadTour(intval($_POST['verifyTourId'])) ]));
}



if( isset($_POST['mediaID']) && strlen($_POST['mediaID'])){
    //
    $_SESSION['mediaID'] = intval($_POST['mediaID']);
}

if( isset($_POST['load']) ){
	$id = intval($_POST['load']);
	$data = $votw->load($id);
	$_SESSION['front'] = $data['imageFront'];
	$_SESSION['img'] = $data['image'];
	$_SESSION['secondaryImg'] = $data['image2nd'];
	$_SESSION['secondaryDesc'] = $data['description2'];
	$_SESSION['title'] = $data['title'];
	$_SESSION['tourId'] = $data['tourID'];
	$_SESSION['mediaID'] = $data['mediaID'];
	$_SESSION['primaryDesc'] = $data['description'];
	
	die();
}

$prefixFolder = "/images/email-campaigns/videos-of-the-week/" . date('m-d-Y') . "/";


if( isset($_POST['useAsFrontPage']) && $_POST['useAsFrontPage'] == 1 ){
    //Create the votw tour demo for the front page of our website
    //echo "Session img: " . $_SESSION['img'];
    $destFront = $_SERVER['DOCUMENT_ROOT'] . "/" . str_replace('_660.','_front.',$_SESSION['img']);
    $_SESSION['front'] = $destFront;
    //echo "Dest990: $destFront<br>";
    //echo "Scaling down to 990x464<br>";
    $ret = $votw->scaleDown($destFront,$destFront,990,464);
    //var_dump($ret);
    //echo "Putting file contents: " . VOTW_FILE . "<br>";
	
//    echo "Scaled down: $destFront<hr>";
    
    $img = str_replace($_SERVER['DOCUMENT_ROOT'] . "/", '' , $destFront);
    $img = str_replace('//','/',$img);
    $useAsFrontPage = true;

    $votw->saveItem(
            $_POST['tourId'], 
            date('Y-m-d'),
            $_POST['title'],
            $_POST['primaryDesc'],
            $_SESSION['img'],
            'video',
            $_POST['mediaID'],
            $destFront,									//Front image
            $_SESSION['secondaryImg'],					//secondary img
            $_SESSION['secondaryDesc'],					//secondary desc
            1											//Use as front page
            
    );

    $votw->postToBlog();

    //echo "After saved item<hr>";
	
    $insertId = $votw->get("lastInsertId");
    //var_dump($insertId);
    //$votw->setAsFront($insertId);
    //echo "After set as front<hr>";
    //Set as video of the week front (video_of_the_week_front table)
    //$votw->writeToFront($insertId,$_SESSION['img']);
}
//var_dump("FRONT FILE: " , $_SESSION['front']);

if( isset($_REQUEST['cropMe']) && $_POST['useAsFrontPage'] != 1 ){
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['tourId'] = intval($_POST['tourId']);
    $votwFolder = $votw->getWeeklyFolder();
    
    if( !file_exists($votwFolder)){
        $votw->makeWeeklyFolder();
    }
    
    $ret = $votw->upload("file",$votwFolder,'nonlive_' . time());
    //echo "After upload";
    //var_dump($ret); 
    if( is_string($ret) ){
        //echo "Upload okay";
		$ext = $votw->getExtension($ret);
        $dest660 = str_replace(".","_660.",$ret);
        $front = str_replace(".","_front.",$ret);

        // Scale down the image
        $votw->scaleDown($ret,$dest660);
        $votw->addPlayButton($ret,$dest660);
        $votw->scaleDown($ret,$front,990,464);
        $_SESSION['front'] = $front;
        copy($dest660,$d660 = $_SERVER['DOCUMENT_ROOT'] . "{$prefixFolder}email_660.{$ext}");
        copy($ret,$dFront = $_SERVER['DOCUMENT_ROOT'] . "{$prefixFolder}email_front.{$ext}");
        //var_dump($d660,$dFront);
        //echo "Saving item";
        $_SESSION['img'] = str_replace($_SERVER['DOCUMENT_ROOT'] . "/",'',$dest660);
    }
    
//    var_dump("Secondary file: ",isset($_FILES['secondaryFile']), "filename: " , $_FILES['secondaryFile']['name']);
    if( isset($_FILES['secondaryFile']) && strlen($_FILES['secondaryFile']['name'])){
		$secondaryFileUpload = true;
    	$ret = $votw->upload("secondaryFile",$votwFolder,'secondary_' . time());
        //echo "Secondary file: " . $_SESSION['secondaryImg'] . "<br>";
        
        if( is_string($ret) ){
            $votw->scaleDown($ret,$ret);
            $_SESSION['secondaryImg'] = str_replace($_SERVER['DOCUMENT_ROOT'] . "/",'',$ret);
        }
        $ext = $votw->getExtension($ret);
         
        copy( $ret , $_SERVER['DOCUMENT_ROOT'] . "/{$prefixFolder}/secondary.{$ext}");
    }
    if( isset($secondaryFileUpload )){
    	//echo "Calling save item with upload on 2nd<br>";
    	$ret = str_replace($_SERVER['DOCUMENT_ROOT'] . "/",'',$ret);
    	$votw->saveItem(
    			$_POST['tourId'],
    			date('Y-m-d'),
    			$_POST['title'],
    			$_POST['primaryDesc'],
    			$_SESSION['img'],
    			'video',
    			$_POST['mediaID'],
    			$_SESSION['front'],
    			$ret,
    			$_POST['secondaryDesc']
    	);
    	 
    }else{
    	//echo "Calling save item without upload on 2nd<br>";
    	$votw->saveItem(
    			$_POST['tourId'],
    			date('Y-m-d'),
    			$_POST['title'],
    			$_POST['primaryDesc'],
    			$_SESSION['img'],
    			'video',
    			$_POST['mediaID'],
    			$_SESSION['front'],
    			$_SESSION['secondaryImg'],
    			$_SESSION['secondaryDesc']
    	);    	 
    }
    $uploaded = true;
    
}

if( isset($_POST['primaryDesc']) && strlen($_POST['primaryDesc']) ){
    $_SESSION['primaryDesc'] = htmlentities($_POST['primaryDesc']);
}

if( isset($_POST['secondaryDesc']) && strlen($_POST['secondaryDesc'])){
    $_SESSION['secondaryDesc'] = htmlentities($_POST['secondaryDesc']);
}

if( isset($_POST['clear'])){
    //echo "Clear is set<hr>";
    if( $_POST['clear'] == 'primary'){
        $_SESSION['img'] = null;
    }else{
        if( $_POST['clear'] == 'secondary-image'){
            $_SESSION['secondaryImg'] = null;
        }
        if( $_POST['clear'] == 'secondary-desc'){
            $_SESSION['secondaryDesc'] = null;
        }
    }
}

if( isset($_POST['availMedia']) ){
    $_SESSION['tourId'] = intval($_POST['availMedia']);
    $res = $db->select("media","tourID=" . $_SESSION['tourId'] . " AND mediaType='video' AND fileExt != 'jpg' ");
    $tour = new tours;
    $vids = $tour->getVideos($_SESSION['tourId']);
    $a = [];
    foreach($res as $index => $info){
        $a[] = [ 'mediaId' => $info['mediaID'], 'title' => $info['room']];
    }
    die(json_encode($a));
}
?>

<html>
<head>
    <title> VOTW Generator </title>

    <script src='/repository_inc/jquery-1.6.2.min.js'></script>
    <script src='/repository_inc/jquery-ui-1.10.0.custom.min.js'></script>
    <script type="text/javascript" src="../repository_inc/imgareaselect/jquery.imgareaselect.js"></script><!-- Crop Selection JS file -->
    <script type="text/javascript" src="/repository_inc/zeroclipboard/ZeroClipboard.js"></script>
    <script type="text/javascript" src="/repository_inc/preview.js"></script>
	<script>
	var clip;
    $(document).ready(function(){
        function soloTab(t){
            
            $("#tab1Div").hide();
            $("#tab2Div").hide();
            $("#tab3Div").hide();
            $("#tab4Div").hide();
            $("#" + t + "Div").fadeIn();
        }
        <?php if( isset($_GET['tab']) ){
            echo "\$('#tab" . intval($_GET['tab']) . "').trigger('click');";            
        }
        ?>
        $("a[id^='pastImage_']").each(function(){
			$(this).click(function(e){
				e.preventDefault();
				modal("PastImage",700,589);
				id = $(this).prop("id").split('_')[1];
				src = $("#pastImageSrc_" + id).prop("src");
				$("#modalPastImage").html("<img src='"  + src + "'>");
			});
        });
        $("a[id^='pastImageSecondary_']").each(function(){
			$(this).click(function(e){
				e.preventDefault();
				modal("PastImage",700,589);
				id = $(this).prop("id").split('_')[1];
				src = $("#pastImageSrcSecondary_" + id).prop("src");
				$("#modalPastImage").html("<img src='"  + src + "'>");
			});
        });
        
		$("li").click(function(e) {
			  e.preventDefault();
			  $("li").removeClass("selected");
			  $(this).addClass("selected");
			  id = $(this).children().prop("id");
			  console.log(id);
			  soloTab(id);
		});

		$("a[id^='loadThis_']").each(function(){
			$(this).click(function(e){
				e.preventDefault();
				id = $(this).prop("id").split("_")[1];
				$.ajax({
					type: "POST",
					data:{
						load: id
					}
				}).done(function(msg){
					//a = $.parseJSON(msg);
					window.location.reload();
				});
			});
		});

	    <?php if( isset($_GET['tab']) ):?>
	    $("#tab" + <?php echo intval($_GET['tab']);?>).trigger("click");
	    <?php endif;?>
		$("#tab1").bind("click",function(){
		    $("#tab1Div").css("display","auto");
		});
	    
	    $("#tab1Next").bind("click",function(){
	        $("#tab2").trigger("click");
	    });
	    $("#tab2Next").bind("click",function(){
	        $("#tab3").trigger('click');
	    });
        $("#formTitle").bind("change focus blur",function(){
            $("#modalTitle").html($(this).val());
            $("#preview").trigger("click");
            console.log("formtitle " + $(this).val());
        });

        $("#clearSecondaryImage").bind("click",function(event){
            event.preventDefault();
            $.ajax({
                type: 'POST',
                data:{
                    clear: "secondary-image"
                }
            }).done(function(msg){
                
                $("#mainForm").trigger("submit");
            });

        });

        $("#clearSecondaryDesc").bind("click",function(event){
        	event.preventDefault();
            $.ajax({
                type: 'POST',
                data:{
                    clear: "secondary-desc"
                }
            }).done(function(msg){
                $("#secondaryDesc").val("");
                $("#mainForm").trigger("submit");
            });

        });

        $("#copyHtml").bind("click",function(){
            $("#d_clip_container").trigger("click");
        });

        
        $("#frontPageButton").bind("click",function(){
        	$("#useAsFrontPage").val(1);
            $("#mainForm").trigger("submit");
        });

        $("#tourId").bind("blur click keydown keyup",function(){
            var a;
            $.ajax({
                type: 'post',
                data: {
                    verifyTourId: $(this).val()
                }
            }).done(function(msg){
                a = $.parseJSON(msg);
                if( a.exists ){
                    $("#tourInfo").html("<b style='color:green;'>Tour Exists</b>");
                    
                }else{
                    $("#tourInfo").html("<b style='color:red;'>Tour not found</b>");
                }
                $.ajax({
                    type: 'post',
                    data:{
                        availMedia: $("#tourId").val()
                    }
                }).done(function(msg){
                    a = $.parseJSON(msg);
                    console.log(a);
                    availableVideos(a);
                });
            });
        });

        function availableVideos(a){
            $("#availMedia").html("");
            for(i=0;i < a.length;i++){
                $("#availMedia").append("<a href='#' id='availMedia_" + a[i].mediaId + "'>" + a[i].mediaId + ":" + a[i].title + "</a><br>");
            }
            $("a[id^='availMedia_']").each(function(){
                $(this).bind("click",function(){
                    var a = $(this).prop('id').split(/_/)[1];
                	$("#modalPreviewMedia").dialog({
                    	width: 700,
                    	height: 589,
                    	close: function(e,ui){
							$("#modalPreviewMedia").html("");
                    	}
                	});
                	$("#mediaId").val(a);
                	$("#modalPreviewMedia").html(
                        	"<iframe width=660 height=519 src='http://www.spotlighthometours.com/tours/" + 
                        	"video-player-new.php?type=video&id=" + a + "&autoPlay=true'" +
                        	"></iframe>"
                    );
                });

            });
        }


        $("#preview").bind("click",function(){
            $("#mainForm").prop("action","?tab=3&preview=1");
            $("#mainForm").trigger("submit");
        });
        
        <?php if( isset($useAsFrontPage) ):?>
        modal("Notice",400,100);
        $("#modalNotice").html("Image will now appear on Cinematic Video page");
        <?php endif;?>
        <?php if( $uploaded ): ?>
            var a = '';
            $.ajax({
                type: 'post',
                data: {
                    title: $("#title").val(),
                    tourId: $("#tourId").val(),
                    primaryDesc: $("#primaryDesc").val(),
                    secondaryDesc: $("#secondaryDesc").val()
                },
                url: "/admin/votw-final.php?tourId=<?php echo intval($_GET['tourId']);?>;?>"
            }).done(function(msg){
                $("#modalFinal").html(msg);
                $("#htmlTextarea").val(msg);
            });
            $("#modalFinal").dialog({
                width: 796,
                height: 1000,
                close: function(){
                    $("a").each(function(a,b,c){
                        $(this).css("color","black");
                    });
                }
            });
            
            $("#modalFinal").fadeIn(); 
        <?php endif; ?>
            function modal(which,w,h){
            	$("#modal" + which).dialog({
    				width: w,
    				height: h,
    				modal: true,
    				open: function(event, ui) { 
    				    $('.ui-widget-overlay').bind('click', function(){ 
        				    closeModal(which); 
        				}); 
    				}
    			});
            }
            function closeModal(which){
                $("#modal" + which).dialog('close');
            }
            $("#clearImg").bind("click",function(event){
                event.preventDefault();
            	modal("Delete",400,200);
    			$("#whichImage").val("primary");
            });
            $("#clearImg2").bind("click",function(event){
                event.preventDefault();
                modal("Delete",400,200);
    			$("#whichImage").val("secondary");
            });
            $("#tab1Update").bind("click",function(){
                $("#mainForm").trigger("submit");
            });
            $("#tab1Div input").bind("change",function(){
                $("#tab1Update").fadeIn();
            });
            $("#tab1Div textarea").bind('input propertychange',function(){
                $("#tab1Update").fadeIn();
            });
            $("#tab2Div textarea").bind('input propertychange',function(){
                $("#tab2Update").fadeIn();
            });
            $("#tab2Div input").bind("change",function(){
                $("#tab2Update").fadeIn();
            });
            $("#tab2Update").bind("click",function(){
                $("#mainForm").trigger("submit");
            });
            $("#yesClearImg").bind('click',function(){
                
                $.ajax({
                    type: "POST",
                    data: { 
                        clear: $("#whichImage").val()
                    }
                }).done(function(msg){
                    closeModal("Delete");
                    modal("ImageDeleted",400,200);
                    if( $("#whichImage").val() == 'primary' ){
                        $("#primaryImgHolder").hide();
                    }else{
                        $("#secondaryImgHolder").hide();
                    }
                });
            });
            $("#noClearImg").bind("click",function(){
                $("button[title='close']").trigger("click");
            });

            $("#htmlTextarea").bind("change input propertychange",function(){
                clip.setText($(this).val());
            });

            $("#d_clip_container").css({
                position: "absolute",
                left: "-1110px",
                top: "-1110px"
            });
            if( location.href.match(/tab=3/) ){
                showClip();
            }
            $("#tab3").bind("click",function(){
                showClip();
            });
            function showClip(){
                $("#d_clip_container").css({
                    position: "relative",
                    left: "0px",
                    top: "70px",
                    width: "100px",
                    height: "210px"
                });
                $("#d_clip_container").appendTo("#clipboardWrapper");
            }
            clip = new ZeroClipboard.Client();
    			clip.setHandCursor( true );
    			
    			clip.addEventListener('load', function (client) {
    				debugstr("Flash movie loaded and ready.");
    			});
    			
    			clip.addEventListener('mouseOver', function (client) {
    				// update the text on mouse over
    				clip.setText( $('#htmlTextarea').val() );
    				console.log($("#htmlTextarea").val());
    			});
    			
    			clip.addEventListener('complete', function (client, text) {
    
    			    modal("Final",400,200);
    				$("#modalFinal").html("Copied to your clipboard :)");
    			});
    			
    			clip.glue( 'copyHtml', 'd_clip_container' );
            
    		function debugstr(msg) {
        		console.log(msg);
    		}
    		<?php if( isset($_SESSION['tourId']) && strlen($_SESSION['tourId']) ):?>
    		$("#tourId").trigger("click");
    		<?php endif; ?>
    });
    </script>


    

    <link rel='stylesheet' type='text/css' href='/repository_css/jquery-ui/dark-hive/jquery-ui-1.8.20.custom.css'/>
    <link rel='stylesheet' type='text/css' href='/repository_css/admin-v2.css'/>
    <link rel='stylesheet' type='text/css' href='/repository_css/template.css'/>
    <link rel='stylesheet' type='text/css' href='http://spotlighthometours.com/repository_css/user-cp.css'/>
    <style>
        #availMediaWrapper {
            border: 1px solid green;
            width: 100px;
            padding: 10px;
            float: left;
            position: relative;
        }
        #availMediaWrapper h5 {
            margin: 0px;
        }
        .imageHolder {
            border: 1px dotted black;
            display: inline-block;
            vertical-align: text-top;
            padding: 10px;
            position: relative;
        }
        .imageHolder b {
            clear: both;
            text-align: center;
            padding-top: 10px;
        }
        .imageHolder img {
        }
        .fakeCursor:hover{
            cursor: hand;
            opacity: 0.5;
        }
        .imageList{
            padding-right: 10px;
        }
        .form_line{
            height: 36px;
            width: 860px;
        }
        .box{
        
        }
        div.input_line.w_lg{
            width: 860px;
            height: 42px;
            background: none;
            clear: left;
        }
        .topBox {
        }
        .sideBox{
            width: 500px;
        }
        body{
background: rgb(233,246,253); /* Old browsers */
background: -moz-linear-gradient(top,  rgba(233,246,253,1) 0%, rgba(211,238,251,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(233,246,253,1)), color-stop(100%,rgba(211,238,251,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  rgba(233,246,253,1) 0%,rgba(211,238,251,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  rgba(233,246,253,1) 0%,rgba(211,238,251,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  rgba(233,246,253,1) 0%,rgba(211,238,251,1) 100%); /* IE10+ */
background: linear-gradient(to bottom,  rgba(233,246,253,1) 0%,rgba(211,238,251,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e9f6fd', endColorstr='#d3eefb',GradientType=0 ); /* IE6-9 */
            margin: 0px;
            padding: 0px;
        }
        h1 b {
            color: black;
        }
        .widen_title{
            padding: 0px 60px 0px 1px;
            width: 130px !important;
        }
        textarea{
            float: left;
            
        }

		.tabrow {
		    text-align: center;
		    list-style: none;
		    margin: 0px 0 0px;
		    padding: 0;
		    line-height: 24px;
		    height: 26px;
		    overflow: hidden;
		    font-size: 12px;
		    font-family: verdana;
		    position: relative;
		}
		.tabrow li {
		    border: 1px solid #AAA;
		    background: #D1D1D1;
		    background: -o-linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
		    background: -ms-linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
		    background: -moz-linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
		    background: -webkit-linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
		    background: linear-gradient(top, #ECECEC 50%, #D1D1D1 100%);
		    display: inline-block;
		    position: relative;
		    z-index: 0;
		    border-top-left-radius: 6px;
		    border-top-right-radius: 6px;
		    box-shadow: 0 3px 3px rgba(0, 0, 0, 0.4), inset 0 1px 0 #FFF;
		    text-shadow: 0 1px #FFF;
		    margin: 0 -5px;
		    padding: 0 20px;
		}
		
		#tab1Div{
		height: 100%;
		}
		
		
		.tabrow a {
			  color: #555;
			  text-decoration: none;
		}
		.tabrow li.selected {
		    background: #FFF;
		    color: #333;
		    z-index: 2;
		    border-bottom-color: #FFF;
		}
		.tabrow:before {
		    position: absolute;
		    content: " ";
		    width: 100%;
		    bottom: 0;
		    left: 0;
		    border-bottom: 1px solid #AAA;
		    z-index: 1;
		}
		.tabrow li:before,
		.tabrow li:after {
		    border: 1px solid #AAA;
		    position: absolute;
		    bottom: -1px;
		    width: 5px;
		    height: 5px;
		    content: " ";
		}
		.tabrow li:before {
		    left: -6px;
		    border-bottom-right-radius: 6px;
		    border-width: 0 1px 1px 0;
		    box-shadow: 2px 2px 0 #D1D1D1;
		}
		.tabrow li:after {
		    right: -6px;
		    border-bottom-left-radius: 6px;
		    border-width: 0 0 1px 1px;
		    box-shadow: -2px 2px 0 #D1D1D1;
		}
		.tabrow li.selected:before {
		    box-shadow: 2px 2px 0 #FFF;
		}
		.tabrow li.selected:after {
		    box-shadow: -2px 2px 0 #FFF;
		}
		.tabBg{
		  background-color: white;
		  padding: 10px;
		  height: 100%;
		}
		a:visited{
		  color: black !important;
		}
		.my_clip_button { 
		  width:150px;
		  height: 30px;
		  text-align:center; 
		  border:1px solid black; 
		  background-color:#ccc; 
		  margin:10px; 
		  padding:10px; 
		  cursor:default; 
		  font-size:9pt; 
		  }
		.my_clip_button.hover { background-color:#eee; }
		.my_clip_button.active { background-color:#aaa; }
		#copyHtml b {
		  position: relative;
		  top: -30px;
		}
		#pastTable td {
			font-size: 0.8em;
			border-bottom: 1px solid black;
		}
		#pastTable thead td {
			border: 1px solid black;
			font-size: 1.0em;
		}
		#pastTable tr:nth-child(even) {
			background-color: #C3D9FF;
		}
		#pastTable tr:nth-child(odd) {
			background-color: #fff;
		}
		span.ui-button-icon-primary {
			left: -1px !important;
			top: -3px !important;
		}
		td.description {
			font-size: 0.6em !important;
		}
	</style>        
        

</head>
<body style='background-color:#fff'>

<h1><b>Video of the week generator</b></h1>
	<ul class="tabrow">
	   <?php 
	       if( !isset($_GET['tab'])){
	           $tab1 = "class='selected'";
	       }else{
	           $tab{intval($_GET['tab'])} = "class='selected'";
	       }
	   ?>
	    <li <?php echo $tab1;?>><a href="#" id='tab1' >Primary Info</a></li>
	    <li <?php echo $tab2;?>><a href="#" id='tab2'>Secondary Info</a></li>
	    <li <?php echo $tab3;?>><a href="#" id='tab3'>Preview</a></li>
	    <li <?php echo $tab4;?>><a href="#" id='tab4'>Past Entries</a></li>
	</ul>
	
    <div id='tab1Div' class='tabBg'>
        <form id="mainForm" method="post" action="?preview=1" enctype="multipart/form-data">
            <input type="hidden" name='useAsFrontPage' id='useAsFrontPage' value=0 />
            <input type="hidden" name='cropMe'/>
            <div class="form_line">
                    <div class="input_line w_lg topBox">
                        <div class="input_title" >Tour ID</div>
            		    <input style="left: 106px;width: 100px;" type="text" id='tourId' name="tourId" value="<?php if(isset($_SESSION['tourId'])){ echo intval($_SESSION['tourId']);}?>">
            		    <span id='tourInfo' style='top: 7px;left: 117px;position:relative;'>
                            Tour Info
                        </span>
            		</div>
            </div>
            
            <div class="form_line" style='width:300px;float:left;'>
                    <div class="input_line w_lg topBox">
                        <div class="input_title" >Media ID</div>
            		    <input style="left: 106px;width: 100px;" type="text" id='mediaId' name="mediaID" value="<?php if(isset($_SESSION['mediaID'])){ echo intval($_SESSION['mediaID']);}?>">
            
            		</div>
            </div>
                        <div id='availMediaWrapper'>
                            <h5>Available videos:</h5>
                            <div id='availMedia'>
                            </div>
                        </div>
            <div style='clear:both;'></div>
            
            <div class="form_line">
                <div class="input_line w_lg sideBox">
                    <div class="input_title widen_title">Primary Title</div>
    		      <input type="text" id='title' name="title" value="<?php if(isset($_SESSION['title'])){ echo $_SESSION['title'];}?>">
    		    
                </div>
            </div>
        
        
            <div class="form_line">
                <div class="input_line w_lg sideBox">
                    <div class="input_title widen_title" style='width: 160px;'>Primary Image</div>
                    <div><b>Best Image Resolution: 990 x 464 </b></div>
        		    <input style="width: 300px;" type="file" id='file' name="file">
        		    
        		    <?php 
            		          if( strlen($_SESSION['img'])){
            		              $lazy = str_replace($_SERVER['DOCUMENT_ROOT'],'',$_SESSION['img']);
            		              echo "
                                  <a href='$lazy" . randomString() . "' width=80 target='_blank'> 
                                      <div class='imageHolder' id='primaryImgHolder'>
                                            <b>Current</b> 
                		                      <img src='$lazy" . randomString() . "' width=80>
                                      </div>
                                  </a>
                                  ";
            		          }
            		?>
            		
    
                </div>
            </div>
        
            <div style='clear:both;'>&nbsp;</div>

            <div class="form_line">
                <div class="input_line w_lg sideBox">
                    <div class="input_title widen_title" style='position:relative; top: 30px;width: 224px;padding:0;float: left;'>Primary Description</div>
            	    <textarea id='description' name="primaryDesc" cols=50 rows=10 style='position: relative;left: 65px;top: 20px;'><?php echo $votw->br2nl( $_SESSION['primaryDesc'] );?></textarea>
                </div>
            </div>
            <div style='margin-left: 500px;clear: both;'>
                <a href='#' id="tab1Next">Next -> </a>
            </div>
            <div id='tab1Update' style='display:none;position:relative;top:80px;'>
             	<div>
            		<div id='tab1Update' class="button_new button_blue_big button_mid" style='float:left; padding-left: 20px;'>
                        <div class="curve curve_left"></div>
                        <div class="button_caption">Save</div>
                        <div class="curve curve_right"></div>
                    </div>
                </div>     
            </div>
     </div><!--  tab1Div -->
     

     <div id='tab2Div' style='display: none;'  class='tabBg'>
            <div class="form_line">
                <div class="input_line w_lg sideBox">
                    <div class="input_title widen_title" style='width: 224px;padding:0;text-align: left;'>Secondary Image</div>
        		    <input style="width: 300px;" type="file" id='secondaryFile' name="secondaryFile">
    
        		    <?php 
        		          if( strlen($_SESSION['secondaryImg'])){
        		              $lazy = str_replace($_SERVER['DOCUMENT_ROOT'],'',$votw->br2nl( $_SESSION['secondaryImg'] ) );
        		              echo "
                              <a href='$lazy" . randomString() . "' width=80 target='_blank'> 
                                  <span class='imageHolder' id='primaryImgHolder'>
                                        <b>Current</b> 
            		                      <img src='$lazy" . randomString() . "' width=80>
                                  </span>
                              </a>
                              ";
        		              
        		          }
        		    ?>

                </div>
            </div>
            <div style='clear:both;'>&nbsp;</div>
            
            <div class="form_line">
                <div class="input_line w_lg sideBox">
                    <div class="input_title widen_title" style='position:relative; top: 40px;width: 224px;padding:0;text-align: left;'>Secondary Description</div>
        		    <textarea id='secondaryDesc' name="secondaryDesc" cols=50 rows=10 style='position: relative;top: 20px;'><?php echo $_SESSION['secondaryDesc'];?></textarea>
                </div>
            </div>    
            
            
            
            
            <div id='tab2Update' style='display:none;position:relative;top:80px;'>
        		<div class="button_new button_blue_big button_mid" style='padding-left: 20px;'>
                    <div class="curve curve_left"></div>
                    <div class="button_caption">Save</div>
                    <div class="curve curve_right"></div>
                </div>
            </div>

            <div style='margin-left: 500px;clear: both;'>
                <a href='#' id="clearSecondaryImage">[x]Clear Image</a>
            </div>
            <div style='margin-left: 500px;clear: both;'>
                <a href='#' id="clearSecondaryDesc">[x]Clear Description</a>
            </div>
            <br>
            
            <div style='margin-left: 500px;clear: both;'>
                <a href='#' id="tab2Next">Next -> </a>
            </div>
            
    </div><!--  End tab2Div -->
    </form>
    
    <div id='tab3Div' style='display: none;'  class='tabBg'>            
        <div id='preview' class="button_new button_blue_big button_mid" style='float:left; padding: 10px 0px 0px 20px;'>
            <div class="curve curve_left"></div>
            <div class="button_caption">Preview</div>
            <div class="curve curve_right"></div>
        </div>
  
    <?php if( isset($_GET['preview'])):?>
        <div id='frontPageButton' class="button_new button_blue_big button_mid" style='float:left; padding: 10px 0px 0px 20px;'>
            <div class="curve curve_left"></div>
            <div class="button_caption">Use as Front Page</div>
            <div class="curve curve_right"></div>
        </div> 

        <div style='clear: both;padding: 20px;'>
            <b>HTML</b><br>
                <textarea cols=50 rows=5 id='htmlTextarea' style='float:left;' onChange="clip.setText(this.value)"></textarea>
        </div>

      

		
		
		<div id='clipboardWrapper'>
		  
		</div>
		
	
		
		
		
		
		
		
		
		
    <?php endif;?>
    </div><!--  End tab3Div -->
    
    
    <div id='tab4Div' class='tabBg' style='display:none;'>
    	<h1>Past Entries</h1>
    	<div>
    		<table id='pastTable'>
    			<thead>
    			<tr>
    				<td>Load This!</td>
    				<td>ID</td>
    				<td>Date</td>
    				<td>Title</td>
    				<td>Description</td>
    				<td>tourID</td>
    				<td>Image</td>
    				<td>Secondary Image</td>
    				<td>Secondary Description</td>
    				<td>Used on front page</td>
    			</tr>
    			</thead>
    		
    		<?php 
    			foreach($votw->getPastEntries(true,$desc=true) as $index => $info ){
    				echo "<tr>";
    				echo "<td><a href=# id='loadThis_$info[id]'>Load</a></td>";
    				echo "<td>$info[id]</td>";
    				echo "<td>";
    				echo $info['date'];
    				echo "</td>";
    				echo "<td>$info[title]</td>";
    				echo "<td class=description>" . limitDesc($info['description']) . "</td>";
    				echo "<td>$info[tourID]</td>";
    				echo "<td><a id='pastImage_$info[id]' href='javascript:void();'><img id='pastImageSrc_$info[id]' width=80 src='$info[image]'></a></td>";
    				echo "<td><a id='pastImageSecondary_$info[id]' href='javascript:void();'><img id='pastImageSrcSecondary_$info[id]' width=80 src='$info[image2nd]'></a></td>";
    				echo "<td class=description>" . limitDesc($info['description2']) . "</td>";
    				echo "<td>" . ($info['useAsFront']? "yes" : "no");
    				echo "</td></tr>";
    			}
    		?>
    		</table>
    	</div>
    
    </div>
    
    
    
    <div id='modalPastImage' style='display:none;'></div>
    
    <div id="d_clip_container">
	    <div id="copyHtml" class="my_clip_button"><b>Copy To Clipboard...</b></div>
	</div>   
    <div id='modalNotice' style='display:none;'></div>
    <div id='modalFinal' style='display:none;'></div>
    <div id='modalImageDeleted' style='display:none;'>Image has been deleted</div>
    <div id='modalPreviewMedia' style='display:none;'>&nbsp;</div>
    <div id='modalDelete' style='display:none;'>
        Are you sure you want to delete this image?
        <input type='hidden' id='whichImage' value='primary'>
        <input type='button' id=yesClearImg value='Yes, delete it'>  
        <input type='button' id=noClearImg value='Cancel'>
    </div>

    
    
    
    </body>
</html>
