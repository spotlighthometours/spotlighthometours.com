<?php
/*
 * @author William Merfalen
 * @date 06-11-2015
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
global $db;
error_reporting(-1);
//ini_set('display_errors',1);
$micro = new microsites;
$wiki = new wikipedia;
$categories = array('Description','History','Demographics','Geography');
$section = "";
$a = explode(",",$_REQUEST['city']);
$city = trim($a[0]);
$state = trim($a[1]);

$p = $micro->getLocalPage($city,$state);
$sectionName = $_REQUEST['section'];
if( count($p) ){
    $section = $micro->getLocalPageDesc($city,$state);
    $section = $section[0][$_REQUEST['section']];
}else{
    $section = "";
}

$imagesDesc = $wiki->getCityImages($city,$state);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Local Pages</title>
<script src="/repository_inc/jquery-1.8.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="/repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="/repository_inc/jquery.wysiwyg.js" type="text/javascript"></script><!-- WYSIWYG JS file -->
<script src="/repository_inc/wysiwyg-controls/wysiwyg.colorpicker.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="/repository_inc/wysiwyg-controls/wysiwyg.cssWrap.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="/repository_inc/wysiwyg-controls/wysiwyg.image.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="/repository_inc/wysiwyg-controls/wysiwyg.link.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="/repository_inc/wysiwyg-controls/wysiwyg.table.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<link rel="Stylesheet" type="text/css" href="../../repository_css/jquery.wysiwyg.css" /><!-- WYSIWYG Style Sheet -->
<style type="text/css" media="screen">
@import "/repository_css/template.css";
@import "/repository_css/admin-v2.css";
@import "/repository_inc/bootstrap-3.3.5-dist/css/bootstrap.min.css";
body { 
    margin: 0px;
    padding: 0px;
}
#textareaSection { 
    width: 440px;
}
#imageSection {
    width: 200px;
}
#textareaWrapper { 
    float: left;
    height: 500px;
}
#textareaWrapper textarea {
    height: 450px;
}
#imageWrapper { 
    float: left;
}

.thumbDiv { 
    white-space: nowrap;
    padding: 5px;
}

#thumbnails { 
    float: left;
    width: 598px;
    height: 150px;
    overflow-x: scroll;
    white-space:nowrap;
}
#textareaContent { 
    float: left;
} 

#fullsizeImage { 
    float: left;
    width: 198px;
}
.selectedImage { 
    opacity: 0.5;
}
.unselectedImage { 
    opacity: 1;
}
#buttonWrapper { 
    float: left;
    clear: both;
    position: relative;
    left: 500px;
}
</style>

<script>









function wigit(selector){
        $(selector).wysiwyg({
          controls: {
            bold          : { visible : true },
            italic        : { visible : true },
            underline     : { visible : true },
            strikeThrough : { visible : true },
            
            justifyLeft   : { visible : true },
            justifyCenter : { visible : true },
            justifyRight  : { visible : true },
            justifyFull   : { visible : true },

            indent  : { visible : true },
            outdent : { visible : true },

            subscript   : { visible : true },
            superscript : { visible : true },
            
            undo : { visible : true },
            redo : { visible : true },
            
            insertOrderedList    : { visible : true },
            insertUnorderedList  : { visible : true },
            insertHorizontalRule : { visible : true },

            h4: {
                visible: true,
                className: 'h4',
                command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
                arguments: ($.browser.msie || $.browser.safari) ? '<h4>' : 'h4',
                tags: ['h4'],
                tooltip: 'Header 4'
            },
            h5: {
                visible: true,
                className: 'h5',
                command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
                arguments: ($.browser.msie || $.browser.safari) ? '<h5>' : 'h5',
                tags: ['h5'],
                tooltip: 'Header 5'
            },
            h6: {
                visible: true,
                className: 'h6',
                command: ($.browser.msie || $.browser.safari) ? 'formatBlock' : 'heading',
                arguments: ($.browser.msie || $.browser.safari) ? '<h6>' : 'h6',
                tags: ['h6'],
                tooltip: 'Header 6'
            },
            
            cut   : { visible : true },
            copy  : { visible : true },
            paste : { visible : true },
            html  : { visible: true },
            increaseFontSize : { visible : true },
            decreaseFontSize : { visible : true },
            exam_html: {
                exec: function() {
                    this.insertHtml('<abbr title="exam">Jam</abbr>');
                    return true;
                },
                visible: true
            }
          },
          maxLength: 20000
        });
    }//end function





function save(){
    h = $("#textareaSection").html();
    $.ajax({
        type: 'POST',
        url: '/admin/local-pages.php?ajax=1&mode=saveHtml',
        data:{
            'city': "<?php echo $city . ", " . $state; ?>",
            'section' : "<?php echo $sectionName; ?>",
            'text' : $("#textareaSection").val()
        }
    }).done(function(msg){
        alert("Changes have been saved");
    });
}


$(document).ready(function(){
    wigit("#textareaSection");
    $("img[class='thumb']").each(function(index,ele){
        $(this).on("click",function(){
            $("img[class='thumb selectedImage']").each(function(i,e){
                $(this).removeClass('selectedImage');
            });
            $(this).addClass("selectedImage");
            a = $(this).attr("src");
            $("#fullsizeImage").html("<img src='" + a + "'>");
        });
    });
    
});
</script>

</head>
<body>
<div id='pageWrapper'>
<form>
<input type='hidden' name='localId' id='localId' value="<?php echo $localId;?>">
    <div id='textareaWrapper'>
        <div id='textareaContent'>
        <textarea id='textareaSection'>
<?php
    echo $section;
?>
        </textarea>
        </div>
        <div id='fullsizeImage'>
        </div>
    </div>
    <div id='thumbnails'>
        
            <?php
                foreach($imagesDesc as $index => $img){
                    echo "  <img class='thumb' src='" . $img['src'] . "' width=80>";
                }
        
            ?>
    </div>
    <div id='buttonWrapper'>
        <div class="btn-group" role="group" aria-label="...">
          <button type="button" class="btn btn-default" onClick='save()'>Save</button>
        </div>
    </div>
</div>
</form>
</body>

</html>
