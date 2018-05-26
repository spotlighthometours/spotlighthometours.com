<?php
/* Author:  William Merfalen
 * Date:	10/06/2014
 * Allow an admin to create their own video of the week html 
*/
require_once('../repository_inc/classes/inc.global.php');

//Redirect to url by any means necessary
function  bruteForceRedirect($url){
    header("Location: $url");
    echo "<META http-equiv=\"refresh\" content=\"5;URL=$url\">";
    die("<script>window.location.href='$url';</script>");
}

//ShowErrors();

define("VOTW_TOUR_PREFIX","http://www.spotlighthometours.com/us/");
define("VOTW_VIDEO_PREFIX","http://www.spotlighthometours.com/images/email-campaigns/videos-of-the-week/");
define("VOTW_VIDEO_UPLOAD",'/images/email-campaigns/videos-of-the-week');
//$ffmpeg = "ffmpeg -ss 0.5 -i inputfile.mp4 -t 1 -s 480x300 -f image2 imagefile.jpg";
/*
The various options:

-t 1: limit to 1 frame extracted
-ss 0.5: point of movie to extract from (ie seek to 0.5 seconds)
-s 480x300: frame size of image to output (image resized to fit dimensions)
-f image2: forces format"
*/

global $db;
$votw = new votw;
$tourIdPrefix = VOTW_TOUR_PREFIX;

if( isset($_GET['regen']) ){
    callURL("http://cfd342.cfdynamics.com/repository_queries/votw-router.php?mode=regen&tourId=" . intval($_GET['tourId']));
    bruteForceRedirect("/admin/votw.php?tourId=" . intval($_GET['tourId']));
}

if( isset($_GET['tourId']) && !isset($_GET['mediaId'])){
    $tourId = intval($_GET['tourId']);
    //Show available videos
    
}





$html = <<<EOF
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Cinematic Video of the Week!</title>

</html>

EOF;

    include("votw-header.php");
?>

<html>
<head>
    <title> VOTW Generator </title>
    <style>
    /*
     * imgAreaSelect animated border style
     */
    
    .imgareaselect-border1 {
    	background: url(border-anim-v.gif) repeat-y left top;
    }
    
    .imgareaselect-border2 {
        background: url(border-anim-h.gif) repeat-x left top;
    }
    
    .imgareaselect-border3 {
        background: url(border-anim-v.gif) repeat-y right top;
    }
    
    .imgareaselect-border4 {
        background: url(border-anim-h.gif) repeat-x left bottom;
    }
    
    .imgareaselect-border1, .imgareaselect-border2,
    .imgareaselect-border3, .imgareaselect-border4 {
    	opacity: 0.5;
        filter: alpha(opacity=50);
    }
    
    .imgareaselect-handle {
        background-color: #fff;
    	border: solid 1px #000;
    	opacity: 0.5;
    	filter: alpha(opacity=50);
    }
    
    .imgareaselect-outer {
    	background-color: #000;
    	opacity: 0.5;
        filter: alpha(opacity=50);
    }
    
    .imgareaselect-selection {
    }
    </style>    

    <script src='/repository_inc/jquery-1.6.2.min.js'></script>
    <script src='/repository_inc/admin.js'></script>
    <script src='/repository_inc/jquery-ui-1.10.0.custom.min.js'></script>
    <script type="text/javascript" src="../repository_inc/imgareaselect/jquery.imgareaselect.js"></script><!-- Crop Selection JS file -->
    <script>
    $(document).ready(function(){
    	$("#modalCrop").prop("display","none");
        $("img[id^='thumb']").each(function(){
            $(this).bind("click",function(){
                id= $(this).prop("id").split('_')[1];
                window.location.href='votw-cropper.php?tourId=<?php echo intval($_GET['tourId']);?>&mediaId=<?php echo intval($_GET['mediaId']);?>&cropper='+id; 
            });
        });

        $("#modalCrop").bind("click",function(){
            $("#mainForm").trigger("submit");
        });
        
        $("#submit").bind("click",function(){
            window.location.href = '?tourId=' + $("#tourId").val();
        });
        $("#formTitle").bind("change focus blur",function(){
            $("#modalTitle").html($(this).val());
            console.log("formtitle " + $(this).val());
        });
        $("#formDescription").bind("change focus blur",function(){
            $("#modalDescription").html($(this).val());
            console.log("formDesc " + $(this).val());
        });

        $("#regenAnchor").bind("click",function(event){
            event.preventDefault();
            //GetLoadingScreen({foo:'bar'});
            ShowWait();
        });

        
        });
    </script>
    
    <link rel='stylesheet' type='text/css' href='/repository_css/jquery-ui/dark-hive/jquery-ui-1.8.20.custom.css'/>
    <link rel='stylesheet' type='text/css' href='/repository_css/admin-v2.css'/>
    <link rel='stylesheet' type='text/css' href='/repository_css/template.css'/>
    <style>
        .fakeCursor:hover{
            cursor: hand;
            opacity: 0.5;
        }
        .imageList{
            padding-right: 10px;
        }
    </style>
</head>
<body>
<!-- MODAL WINDOW -->
<div class="modal">
  <div id="backdrop" style="display: none;" onclick="HidePopUp();"></div>
  <div class="modal-window" id="pop_up_frame">
    <div class="top"><a class="close" href="javascript:HidePopUp();"></a></div>
    <div class="middle">
      <h1 id="pop_up_title">Loading...</h1>
      <div id="pop_up_content"> </div>
    </div>
    <div class="bottom"></div>
  </div>
</div>
<!-- END MODAL WINDOW -->
    <form id='mainForm' method="POST">
    
    <?php 
        if( isset($_GET['tourId']) && !isset($_GET['mediaId'])):
    ?>
    
    <script>
        $(document).ready(function(){
            $("img[id^='img_']").bind("click",function(){
                window.location.href = '?tourId=<?php echo intval($_GET['tourId']);?>&mediaId=' + $(this).prop("id").split('_')[1];
            });
        });
    </script>
    
    
    
<b>Don't see the video you're looking for?</b><a id='regenAnchor' href='?regen=1&tourId=<?php echo intval($_GET['tourId']);?>'>Click here</a>
  
  
  
    <div class="form_line" style="position:relative;top: 0px;">
        <div class="input_line w_lg" style="width: 560px;">

		      <?php
		      
		        $votw = new votw;
		        $thumbs = $votw->getVideoThumbs($tourId=intval($_GET['tourId']));
		        if( $thumbs < 0 ){
                    echo "No videos exist for this tour :/<hr>";
                }else{
    		        foreach($thumbs as $mediaId => $info){
                        echo "<img class='fakeCursor imageList' id='img_$mediaId' src=\"/images/tours/$tourId/$info\" width=150>";
                    }
                }
		      
		      ?>
        </div>
    </div>    
    
    <?php 
        endif;
    ?>
    <?php 
        if( isset($_GET['tourId']) && isset($_GET['mediaId']) ):
    ?>
    
     
            
    
    <?php 
            $votw = new votw;
    
            echo "<table style='position:relative;top:78px;'>";
            echo "<tr>";
            $ctr=0;
            if( isset($_GET['regen'])){
                
            }
            foreach($votw->grabFrames($_GET['tourId'],$_GET['mediaId'],$regenVideos=false) as $index => $frame){
                if( $ctr == 5 ){
                    $ctr = 0;
                    echo "</tr><tr>";
                }
                $ctr++;
                echo "<td class='fakeCursor'>";
                $id = explode(".",explode("_",$frame)[2])[0];
                echo "<img id='thumb_$id' width='150' src='/images/tours/" . intval($_GET['tourId']) . "/frames/$frame'><br>";
                echo "</td>";
            }
            echo "</tr></table>";
    
        endif; 
    ?>
    
    <div id='cropper'>
    
    </div>

    </body>
</html>





