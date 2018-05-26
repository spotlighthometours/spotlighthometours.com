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
$users = new users;
$users->authenticateAdmin();

function stripFormatting(&$text){
    //All we're doing is removing the div, if there is any
    //
    $a = str_replace('<div id="fullsizeImage">','',$text);
    $a = str_replace('</div>','',$a);
    $a = str_replace('<div style="clear:both;">','',$a);
    $a = str_replace('<p></p>','',$a);
    $text = $a;
    return $text;
}
if( isset($_POST['city']) && isset($_POST['submit']) ){
	$a = explode("|",$_POST['city']);
	$city = $a[0];
	$state = $a[1];
	$p = $micro->getLocalPage($city,$state);
	$localId = intval($_POST['localId']);
	if( count($p) ){
		//Update
		$localId = $p[0]['id'];
		$res = $db->select("microsite_local_desc","localId=" . intval($localId));
        array_walk($_POST,'stripFormatting');
		if( count($res) ){
			$id = $res[0]['id'];
			$micro->saveLocalPageDesc($id,$localId,$_POST['description'],$_POST['history'],$_POST['demographics'],$_POST['geography']);
		}else{
			$micro->addLocalPageDesc($localId,$_POST['description'],$_POST['history'],$_POST['demographics'],$_POST['geography']);
		}
	}else{
		$error =  "No city specified. ";
		die(json_encode(array('status'=>'error','msg' => $error)));
	}
	die(json_encode(array('status'=>'ok','msg' => 'Saved.')));
}

if( isset($_GET['city']) ){
	$a = explode(",",$_GET['city']);
	$city = $a[0];
	$state = $a[1];
}else{
	$city = $state = null;
}


if( isset($_GET['ajax']) && isset($_GET['mode']) && $_GET['mode'] == 'createAlias' ){
    $source = $_GET['source'];
    $dest = $_GET['dest'];

    $micro->createAlias($source,$dest);

    die(json_encode(array('status'=>'ok')));
}



if( isset($_GET['ajax']) && isset($_GET['mode']) && $_GET['mode'] == 'getSaved' ){
    $a = explode("|",$_GET['city']);
    $city = trim($a[0]);
    $state = trim($a[1]);
    $a = $micro->getLocalPageDesc($city,$state);
    $data = $a[0][$_GET['section']];
    die(json_encode(array('data'=>$data)));
}

if( isset($_GET['ajax']) && isset($_GET['mode']) && $_GET['mode'] == 'getHtml'){
    $localId = $p[0]['id'];
    $a = explode(",",$_GET['city']);
    $city = trim($a[0]);
    $state = trim($a[1]);
    if( $_GET['section'] == 'description' ){
        $a = $wiki->getCityDesc($city,$state);
    }else{
        $a = $wiki->getSection($city,$state,ucfirst($_GET['section']));
    }
    die(json_encode(array('status'=>'ok','data'=>$a)));
}
if( isset($_GET['ajax']) && isset($_GET['mode']) && $_GET['mode'] == 'saveHtml' ){
    $a = explode(",",$_POST['city']);
    $city = trim($a[0]);
    $state = trim($a[1]);
     
    $p = $micro->getLocalPage($city,$state);
    if( count($p) ){
        $localId = $p[0]['id'];
		$res = $db->select("microsite_local_desc","localId=" . intval($localId));
        $description = $history = $demographics = $geography = null;
		if( count($res) ){
			$id = $res[0]['id'];
            $description = $res[0]['description'];
            $history = $res[0]['history'];
            $demographics = $res[0]['demographics'];
            $geography = $res[0]['geography'];
            $$_REQUEST['section'] = stripFormatting($_REQUEST['text']);
			$micro->saveLocalPageDesc($id,$localId,$description,$history,$demographics,$geography);
		}else{
            $$_REQUEST['section'] = $_REQUEST['text'];
			$micro->addLocalPageDesc($localId,$description,$history,$demographics,$geography);
		}
	}else{
        die(json_encode(array('status'=>'error','msg'=>'No city to save to')));
    }
    die(json_encode(array('status'=>'ok','data'=>$$_REQUEST['section'])));
}

if( isset($_GET['add']) ){
	$p = $micro->getLocalPage($city,$state);
	if( count($p) ){
		//Already exists
	}else{
		$micro->addLocalPage($city,$state);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Local Pages</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> 
<script src="/repository_inc/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/javascript"></script><!-- Template JS file -->
<style type="text/css" media="screen">
@import "/repository_css/template.css";
 @import "/repository_css/admin-v2.css";
@import "/repository_inc/bootstrap-3.3.5-dist/css/bootstrap.min.css";
.rightColumn { 
	width: 350px;
	float: left;
}

#imageHolder { 
	width: 500px;
	height: 500px;
	position: fixed;
	overflow: scroll;
	background-color: white;
	right: 0px;
}
.floatLeft{ 
	width: 500px;
	float: left;
}
.outerContent { 
    left: 200px;
    width: 550px;
    
}
.content { 
    float: left;
    width: 340px;
    height: 250px;
    padding-left: 10px;
}
.navigation {
    border-radius: 8px;
    width: 150px;
    padding: 10px;
    float: left;
}
.navigation input {
    padding: 10px 0px 10px 0px;
}
.navigation .btn { 
    padding: 10px;
    margin: 10px;
}
.noShowDiv { 
    visibility: hidden;
}

.showDiv { 
    visibility: visible;
}
#contentDivContainer { 
}
.contentDivs {
    left: 0px;
    top: 0px;
}
.textarea { 
    border-radius: 15px;
    width: 550px;
    height: 666px; 
    overflow-y: scroll;
    padding: 10px;
}
.textarea img {
    padding: 10px;
}
.modal-body { 
    height: 766px;
    width: 650px;
    overflow: hidden;
}
.modal-content {
    height: 866px;
    width: 660px;
}
.modal-body iframe { 
    width: 640px;
    padding: 0px;
    margin: 0px;
}
</style>

<script>
function save(){
	//$("#form1").trigger("submit");
	desc = $("#textareaDescription").html();
	hist = $("#textareaHistory").html();
	demo = $("#textareaDemographics").html();
	geo = $("#textareaGeography").html();
	$.ajax({
        url: "/admin/local-pages.php?city=<?php echo $city . "|" . $state;?>",
        type: "POST",
        data: {
		description: desc,
		history: hist,
		demographics: demo,
		geography: geo,
		localId: $("#localId").val(),
		city: "<?php echo $city . "|" . $state;?>",
        submit: 1
        }
	}).done(function(msg){
		json = $.parseJSON(msg);
        if( json.status == 'ok' ){
            alert("Changes have been saved");
        }
	});
	
}
function updateSection(){
    $.ajax({
        type: 'POST',
        url: '/admin/local-pages.php?ajax=1&mode=getSaved&section=' + globalSection + '&city=<?php echo $city;?>',
        cache: false
    }).done(function(msg){
        $("#textarea" + ucfirst(globalSection)).fadeTo("slow",0.5);
        json = $.parseJSON(msg);
        $("#textarea" + ucfirst(globalSection)).html(json.data);
        $("#textarea" + ucfirst(globalSection)).fadeTo("slow",1.0);
    });
}
function customSearch(){
	a = $("#customCity").val();
    s = $("#customState option:selected").val();
	b = $("#baseCity").val();
	location.href = '/admin/local-pages.php?city=' + b + '&custom=' + a + "&state=" + s;
}

function ucfirst(str) {
  str += '';
  var f = str.charAt(0)
    .toUpperCase();
  return f + str.substr(1);
}

function getSelectedCity(){
    return $("#hiddenCity").val();
}

var imageList;
function loadPics(picData){
    imageList = new Array();
    for(i in picData){
        imageList.push(picData[i].src);
    }
    return imageList[0];
}

function displayLoading(){
    html ='<div class="container">';
    html += '<button class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading...</button>';
    html += '</div>';

    $("div.descBtnContainer").each(function(index,ele){
        $(ele).html(html);
    });
}

function stopLoading(){
    html = '<button type="button" class="btn btn-default navbar-btn" onClick=\'addDescription(null)\'>Get Description</button>';
    $("div.descBtnContainer").each(function(index,ele){
        $(ele).html(html);
    });
}

function editSection(){
    saveSection();
    loadEditIframe();
}

function loadEditIframe(){
    $(".modal-body iframe").attr("src","/admin/local-pages-edit-section.php?section=" + globalSection + "&city=" + getSelectedCity() );
}

function getSectionText(section){
    return $("#textarea" + ucfirst(section)).html();
}

function saveSection(){
    $.ajax({
        type: 'POST',
        url: '/admin/local-pages.php?ajax=1&mode=saveHtml&section=' + globalSection,
        data: {
            city: getSelectedCity(),
            text: getSectionText(globalSection)
        }
    }).done(function(msg){
        json = $.parseJSON(msg);
        if( json.status == 'ok' && globalSection == 'description' ){
            //
            console.log(json);
            setTextarea("textarea"+ ucfirst(globalSection),null,json.data);
        }
        if( json.status == 'ok' && globalSection != 'description' ){
            //
            console.log(json.data);
            setTextarea("textarea"+ ucfirst(globalSection),json.data,json.data.text);
        }
        stopLoading();
    });


}
function setTextarea(textarea,pic,data){
    placeholder = 'placeholder.png';
    divClear = "<div style='clear:both;'></div>";
    if( pic ){
        first = loadPics(pic.images);
        $("#" + textarea).html( "<p><img align='left' src='http:" + first + "' >" + data + "</p>" + divClear);
    }else{
        $("#" + textarea).html( "<p>" + data + "</p>" + divClear);
    }

}
var globalSection = 'description';
function addDescription(section){
    if( section == null ){
        section = globalSection;
    }
    displayLoading();
    $.ajax({
        url: '/admin/local-pages.php?ajax=1&mode=getHtml&section=' + section,
        data: {
            city: getSelectedCity()
        }
    }).done(function(msg){
        json = $.parseJSON(msg);
        if( json.status == 'ok' && section == 'description' ){
            //
            console.log(json);
            setTextarea("textarea"+ ucfirst(section),null,json.data);
        }
        if( json.status == 'ok' && section != 'description' ){
            //
            console.log(json.data);
            setTextarea("textarea"+ ucfirst(section),json.data,json.data.text);
        }
        stopLoading();
    });
    
}

function getDestinationCity(){
    return $("#destinationCity").val();
}

function createAlias(){
    $.ajax({
        url: '/admin/local-pages.php?ajax=1&mode=createAlias',
        data: {
            source: getSelectedCity(),
            dest: getDestinationCity()
        }
    }).done(function(msg){
        alert("Alias saved");
    });
   
} 

function load(section){
    $("div.contentDivs").each(function(){
        $(this).addClass("noShowDiv").removeClass("showDiv");
    });
    $("#div" + ucfirst(section)).removeClass("noShowDiv").addClass("showDiv");
    globalSection = section;
}

function loadCity(cityState){
    location.href = '/admin/local-pages.php?city=' + cityState;
}
$(document).ready(function(){
	$("#citySelect").on("change",function(){
		location.href = '/admin/local-pages.php?city=' + $(this).val();
	});
	

    $("li.section").each(function(index,ele){
        $(this).on("click",function(){
            $("li.section").each(function(index2,ele2){
                $(this).removeClass("active");
            });
            $(this).addClass("active");
            load($(this).data("section"));
        });
    });


});
</script>

</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->

<!-- Single button -->
<?php
    $html = "";
    $city = "Select A City";
	$list = $micro->getLocalPageList(true);
	foreach($list as $index => $row){
        $res = $db->select("microsite_local_desc","localId=" . $row['id']);
        if( count($res) ){
            $content = "(Has content)";
        }else{
            $content = "";
        }
        $html .= "<li><a href='#' onClick='loadCity(\"" . $row['city'] . "|" . $row['state'] . "\");'>";
        if( $content == "" ){
            $html .= "<b color='red'>" . $row['city'] . ", " . $row['state'] . "</b></a></li>";
        }else{
            $html .= $row['city'] . ", " . $row['state'] . "$content</a></li>\n";
        }
        if( strtolower($_GET['city']) == strtolower($row['city'] . "|" . $row['state']) ){
            $city = $row['city'] . ", " . $row['state'];
        }
	}
?>
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <input type='hidden' id='hiddenCity' value='<?php echo $city;?>'>
    <?php echo $city; ?><span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <?php
        echo $html;
    ?>
  </ul>
    <?php
        $alias = $micro->getAlias(str_replace("|",", ",$_GET['city'])); // . ", " . $row['state']);
    ?>
    ===&gt; <input type='text' id='destinationCity' value='<?php echo $alias;?>'> <input type='button' onClick='createAlias()' value='Create Alias'>
</div>
</nav>

<!--
  <div class="btn-group">

      <a class="navbar-brand" href="#" onClick='load("description");'>Description</a>
      <a class="navbar-brand" href="#" onClick='load("history");'>History</a>
      <a class="navbar-brand" href="#" onClick='load("demographics");'>Demographics</a>
      <a class="navbar-brand" href="#" onClick='load("geography");'>Geography</a>
  </div>
-->



<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class='section' data-section="description"><a href="#">Description</a></li>
        <li class='section' data-section="history"><a href="#">History</a></li>
        <li class='section' data-section="demographics"><a href="#">Demographics</a></li>
        <li class='section' data-section="geography"><a href="#">Geography</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<form id='form1' method=POST>
<input type='hidden' name='localId' id='localId' value="<?php echo $localId;?>">
<?php
/*

		$section = $wiki->getSection($city,$state,$key);
		echo "<textarea cols=80 rows=18>";
		echo strip_tags($section['text']);
		echo "</textarea>";
*/
?>
</form>

<div id='contentDivContainer'>
<?php
    $first = true;
    $top = 0;
    $a = explode(",",$city);
    $city = trim($a[0]);
    $state = trim($a[1]);
    $p = $micro->getLocalPage($city,$state);
    $section = $micro->getLocalPageDesc($city,$state);
    foreach($categories as $key){
        $k = strtolower($key);
        if( $first ){
            $first = false;
            echo "<div class='showDiv contentDivs' id='div" . ucfirst($k) . "'>";
        }else{
?>
<div class='noShowDiv contentDivs' id='div<?php echo ucfirst($k);?>' style='position:absolute;top: 158px;left: 16px;'>
<?php
        }
        $top -= 330;
?>
    <div class='outerContent'>
        <div class='navigation'>
            <div class='descBtnContainer'>
                <button type="button" class="btn btn-default navbar-btn" onClick='addDescription("<?php echo $k;?>")'>Get Description</button>
            </div>
            <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" onClick='editSection()'>Edit</button>
            <button type="button" class="btn btn-info btn-lg" onClick='save()'>Save All</button>
        </div>
        <div class='content'>
            <div class='textarea' id='textarea<?php echo ucfirst($k);?>'>
            <?php
if( count($p) ){
    $html = $section[0][$k];
}else{
    $html = "";
}
echo $html;

?>

            </div>
        </div>
    </div>
</div>
<?php
    }
?>
</div>

<div class="container" onClick='updateSection();'>
  <h2></h2>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog" style='left: 400px;'>
    <div class="modal-dialog" style='left: 0px;'>
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Section</h4>
        </div>
        <div class="modal-body">
            <iframe src='' frameborder=0 width='100%' height='100%'></iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onClick="updateSection()">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>

</body>
</html>
