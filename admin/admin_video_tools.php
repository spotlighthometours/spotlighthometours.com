<?php
/**********************************************************************************************
Document: admin_video_tools.php
Creator: William Merfalen
Date: 2015-01-21
Purpose: Gives admin some insight and control over a video's status
**********************************************************************************************/
	require_once('../repository_inc/classes/inc.global.php');
	showErrors();
    global $db;
    $tourId = null;
    if( isset($_REQUEST['tourId']) ){
        $tourId = intval($_REQUEST['tourId']);
        $s3Status = ( $db->run("SELECT onS3 FROM tours WHERE tourID={$tourId}")[0]['onS3'] == '1') ? true : false ;
        $tours = new tours;
        $tours->loadTour($tourId);
        $s3 = new s3utils;
        
    }
    if(!file_exists(dirname(__FILE__) . '/temp') ){
        mkdir(dirname(__FILE__) . '/temp');
    }
    if( isset($_POST['api']) ){
        $s3->verbose = false;
		ini_set('display_errors',0);
        if( isset($_POST['smilRequest']) ){
            $mediaId = intval($_POST['mediaId']);
            $s3->downloadFile("tours/{$tourId}/video_{$mediaId}.smil",dirname(__FILE__) .'/temp/');
            readfile(dirname(__FILE__) . '/temp/video_' . $mediaId . '.smil');
        }
        if( isset($_POST['save']) ){
            chdir(dirname(__FILE__) . '/temp');
            $mediaId = intval($_POST['mediaId']);
            $tourId = intval($_POST['tourId']);
            file_put_contents( 'video_' . $mediaId . '.smil',$_POST['contents']);
            $s3->uploadFile( "video_" . $mediaId . ".smil","tours/{$tourId}");
            die(json_encode(['status'=>'File saved to S3']));
        }
        die;
    }

    function filePath($tourId){
        $t = intval($tourId);
        $tours = new tours;
        $drive = $tours->whichDrive($t);
        if( $drive == 's3' ){
            $s3 = new s3utils;
            return $s3->getTourPath($t);
        }
        $path = $_SERVER['DOCUMENT_ROOT'] . '/';
        if( $drive == 'd' ){
            $append = "";
        }else{
            $append = "-" . $drive . "/";
        }
        $path .= "images" . $append . "/tours/{$t}";
        return $path;
    }
?>

<style type=text/css>
.row {
    border: 1px solid #EEE;
    float: left;
    color: black;
    height: 26px;
    font-size: 12px;
    font-family: Verdana,sans-serif;
    padding: 10px 10px 10px 10px;
    border-top: 1px solid black;
    border-bottom: 1px solid black;
}

.leftRow {
    background: #EEE;
    width: 220px;
}
.rightRow {
    background: white;
    width: 200px;
}

</style>
<script src="../repository_inc/jquery-1.8.2.min.js"></script>   
<script>
$(document).ready(function(){
    var tour = <?php echo $tourId; ?>;

    $("#s3EditButton").on("click",function(){
        $("#s3Textarea").css("display","block");
        var m = $("#s3Drive option:selected").val();
        $.ajax({
            type: "POST",
            data: {
                tourId: tour,
                mediaId: m,
                api: 1,
                smilRequest: 1
            }
        }).done(function(msg){
            $("#s3Textarea").val(msg);
        });
    });

    $("#s3SaveButton").on("click",function(){
        var m = $("#s3Drive option:selected").val();
        $.ajax({
            type: "POST",
            data: {
                api: 1,
                tourId: tour,
                save: 1,
                mediaId: m,
                contents: $("#s3Textarea").val()
            }
        }).done(function(msg){
            var tmp = $.parseJSON(msg);
            alert(tmp.status);
        });
    });
});
</script>



<form method=GET>
    <div class='leftRow row'>
        Enter a tour ID
    </div>
    <div class='rightRow row'>
        <input type='text' name='tourId' value='<?php if($tourId){ echo $tourId; }?>'>
    </div>
<?php
    if( $tourId == null ){
        die("</form>");
    }
?>
<div style='clear:both;'></div>
<div class='leftRow row'>
    Tour is on S3:
</div>
<div class='rightRow row'>
    <?php if( $s3Status ){ echo "Yes"; }else{ echo "No"; }?>
</div>


<div style='clear:both;'></div>
<div class='leftRow row'>
    Hard Drive:
</div>
<div class='rightRow row'>
    <?php echo $drive = $tours->whichDrive($tourId); ?>
</div>


<div class='leftRow row'>
    Local files:
</div>
<div class='rightRow row'>
    
    <?php
		echo filePath($tourId);
	
        if( $drive != 's3' ){
            $fp = opendir(filePath($tourId));
            while(($file = readdir($fp))!== false){
                echo $file . "<br>";
            }
        }else{
            echo '<b>No local files</b>';
        }
	
    ?>
    </tbody></table>
</div>







<div style='clear:both;'></div>
<div class='leftRow row'>
    SMIL files on
    <?php
        if( $drive != 's3' ){
            echo $drive;
        }else{
            echo "-- No local folders";
        }
    ?>
</div>

<div class='rightRow row'>
    <select name='localDrive'>
        <?php
            if( $drive != 's3' ){
                $path = $_SERVER['DOCUMENT_ROOT'] . '/';
                if( $drive == 'd' ){
                    $append = "";
                }else{
                    $append = "-" . $drive . "/";
                }
                $path .= "images" . $append . "/tours/{$tourId}";
				//echo "$path<hr>";
				
                $fp = opendir($path);
                while(($r = readdir($fp)) !== false ){
                    if( substr($r,-4) == 'smil' ){
                        echo "<option value='$r'>$r</option>\n";
                    }
                }
            }else{
        ?>
                <option>This folder is on S3</option>
        <?php
            }
        ?>
    </select>
</div>

<div style='clear:both;'></div>
<div class='leftRow row'>
    SMIL files on S3:
</div>
<div class='rightRow row'>
	<?php
			$s3->verbose = false;
			$list = $s3->getFolder("tours/{$tourId}/video_");
	?>
    <select id='s3Drive'>
        <?php
                
                if( count($list) ){
                    //$s3->verbose = true;
                    
                    foreach($list as $index => $file){
                        if( substr($file,-4) == 'smil' ){
                            $breakApart = explode('.',$file)[0];
                            echo "<option id='$breakApart' value='$file'>$file</option>";
                        }
                    }
                }
                
                ob_flush();
        ?>
    </select>
    <a href='javascript:void(0);' id='s3EditButton'>Edit</a>
    <a href='javascript:void(0);' id='s3SaveButton'>Save</a>
</div>
<div style='clear:both;'></div>
<div class="leftRow row">
    Files currently on S3
</div>
<div class="rightRow row" style='overflow:scroll;height: 100px;'>
    <?php
        /*
        $s3->verbose = false;
        $list = $s3->getFolder("tours/{$tourId}");
        if( count($list) ){
            foreach($list as $index => $file){
                echo $file . "<br>";
            }
        }
        */
    ?>
</div>
<div style='clear:both;'></div>
<textarea id='s3Textarea' class='rightRow' style='width: 680px;display:none;' rows=20>
</textarea>

