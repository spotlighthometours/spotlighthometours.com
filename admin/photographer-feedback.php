<?php
/*
 * Admin: Photographer Feedback 
 */
// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
showErrors();

// Create instances of needed objects
$emailer = new emailer();
$photographers = new photographers();
$editors = new editors();
$tours = new tours($db);
$users = new users($db);

$s3Url = "http://spotlight-f-images-tours.s3.amazonaws.com/photographer-feedback/temp_%s/";

ini_set('memory_limit','1G');
ini_set('upload_max_filesize','800M');
ini_set('post_max_size','800M');

//var_dump($_FILES['userfile']);
$fileCount = count($_FILES['userfile']['name']);
$fileList = array();
if( $fileCount ){
	$targetFile = dirname(__FILE__) . '/photographer-feedback/';
	$random =  time() .  rand(2222220,99999999999);
	mkdir($dir = $targetFile . "temp_" . $random,0777,true);
	$stripped = str_replace(dirname(__FILE__) . '/photographer-feedback/','',$dir);
}
for($i=0; $i < $fileCount;$i++){
	$a = tempnam($dir,"temp_");
	$arr = explode(".",$a);
	$a = $arr[0];
	$ext = explode(".",$_FILES['userfile']['name'][$i]);
	$ext = array_pop($ext);
	$tmpName = "{$a}.{$ext}";
	rename($a,$tmpName);
    if (move_uploaded_file($_FILES["userfile"]["tmp_name"][$i], $tmpName)) {
        //echo "The file ". basename( $_FILES["userfile"]["name"][$i]). " has been uploaded.";
		$fileList[] = str_replace($_SERVER['DOCUMENT_ROOT'] . "\\admin\\photographer-feedback\\temp_{$random}\\",'',$a) . "." . $ext;
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
$uploadsHtml = "";
//If the file uploads were a success
if( count($fileList) ){
	$aws = new awss3;
	$ret = $aws->uploadDirectory($dir,"","photographer-feedback/{$stripped}/" );
	//Generate uploadsHtml
	$uploadsHtml = "<br><b>The following images were uploaded as part of your feedback</b><br>";
	$uploadsHtml .= "<table>";
	foreach($fileList as $idx => $file){
		$uploadsHtml .= "<tr><td>";
		$url = sprintf($s3Url,$random);
		$url .= $file;
		$uploadsHtml .= "<a href='{$url}' target=_blank>{$url}</a>";
		$uploadsHtml .= "</td></tr>";
	}
	$uploadsHtml .= "</table>";
}

// Require admin
$users->authenticateAdmin();

if(isset($_REQUEST['tourID'])){
	$tourID = intval($_REQUEST['tourID']);
}else{
	die("<h1>Tour ID missing!</h1>");
}

$negative = array(
	"Overexposed",
	"Lens flares",
	"Underexposed",
	"No email sent",
	"HDR's not aligned",
	"Photos sent too late",
	"Dirty lense",
	"Not enough HDRs",
	"Twilight shot too early",
	"HDR exposures",
	"Twilight shot too late",
	"Not enough images",
	"Tour not cut"
);

$positive = array(
	"Great job!",
	"Great HDR exposures!",
	"Good exposures!",
	"Great composition!"
);

$tours->tourID = $tourID;
$tours->get("address");
$tours->get("city");
$tours->get("state");
$tours->get("zipCode");
$tours->get("userID"); 

$Progress = $db->run("SELECT *, ISNULL(ReScheduledon) as PhotoReIsNull, ISNULL(VideoReScheduledOn) as VideoReIsNull 
						FROM tourprogress WHERE tourID = ".$tourID." LIMIT 1");
$Progress = $Progress[0];

$tourAddress = $tours->address." <br/>".$tours->city.", ".$tours->state." ".$tours->zipCode;

$phpdate = strtotime($Progress['Scheduledon']);
$PhotoShootDate = date('n/j/Y', $phpdate);
$phpdate = strtotime($Progress['ReScheduledon']);
$PhotoReShootDate = date('n/j/Y', $phpdate);
$phpdate = strtotime($Progress['VideoScheduledOn']);
$VideoShootDate = date('n/j/Y', $phpdate);
$phpdate = strtotime($Progress['VideoReScheduledOn']);
$VideoReShootDate = date('n/j/Y', $phpdate);

$agentName = $users->getName($tours->userID);
$agentName = $agentName['firstName'] . " " . $agentName['lastName'];

$tourTypeCategory = $tours->getTourTypeCategory($tourID);

$photographerID = $tours->getPhotographer($tourID, "photographer");
if(!empty($photographerID)){
	$Temp1 = $photographers->get("email,fullName", $photographerID);
	$photographerEmail = $Temp1['email'];
	$photographerName = $Temp1['fullName'];
}
$RePhotographerID = $tours->getPhotographer($tourID, "rephotographer");
if(!empty($RePhotographerID)){
	$Temp2 = $photographers->get("email,fullName", $RePhotographerID);
	$RePhotographerEmail = $Temp2['email'];
	$RePhotographerName = $Temp2['fullName'];
}
$editorID = $tours->getPhotographer($tourID, "editphotographer");
if(!empty($editorID)){
	$editorName = $editors->get("fullName", $editorID);
	$editorName = $editorName['fullName'];
}
$ReEditorID = $tours->getEditor($tourID, "EditRePhotographer");
if(!empty($ReEditorID)){
	$ReEditorName = $editors->get("fullName", $ReEditorID);
	$ReEditorName = $ReEditorName['fullName'];
}

if ($_REQUEST['video'] == '1' && $tourTypeCategory == "Video Tours") {
	$feedbackTitle = "Video ";
	$VideoPhotographerID = intval($tours->getPhotographer($tourID, "VideoPhotographer"));
	if(!empty($VideoPhotographerID)){
		$Temp3 = $photographers->get("email,fullName", $VideoPhotographerID);
		$VideoPhotographerEmail = $Temp3['email'];
		$VideoPhotographerName = $Temp3['fullName'];
	}
	$VideoRePhotographerID = intval($tours->getPhotographer($tourID, "VideoRePhotographer"));
	if(!empty($VideoRePhotographerID)){
		$Temp4 = $photographers->get("email,fullName", $VideoRePhotographerID);
		$VideoRePhotographerEmail = $Temp4['email'];
		$VideoRePhotographerName = $Temp4['fullName'];
	}
	$VideoEditID = $tours->getEditor($tourID, "VideoEditPhotographer");
	if(!empty($VideoEditID)){
		$VideoEditorName = $editors->get("fullName", $VideoEditID);
		$VideoEditorName = $VideoEditorName['fullName'];
	}
	$VideoEditReEditorID = $tours->getEditor($tourID, "VideoEditRePhotographer");
	if(!empty($VideoEditReEditorID)){
		$VideoReEditorName = $editors->get("fullName", $VideoEditReEditorID);
		$VideoReEditorName = $VideoReEditorName['fullName'];
	}
}
else
	$feedbackTitle = "";
	
// If submitted then send feedback to photographer
if(isset($_REQUEST['send_email'])){
	if(isset($_REQUEST['negative_selection'])){
		$negativeMsg = "<ul>";
		foreach($_REQUEST['negative_selection'] as $index => $on_off){
			$negativeMsg .= "<li>".$negative[$index]."</li>";
		}
		$negativeMsg .= "</ul>";
	}else{
		$negativeMsg = "None selected.";
	}
	
	if(isset($_REQUEST['positive_selection'])){
		$positiveMsg = "<ul>";
		foreach($_REQUEST['positive_selection'] as $index => $on_off){
			$positiveMsg .= "<li>".$positive[$index]."</li>";
		}
		$positiveMsg .= "</ul>";
	}else{
		$positiveMsg = "None selected.";
	}
	
	if(isset($_REQUEST['comments'])){
		$comments = $_REQUEST['comments'];
	}else{
		$comments = "No comments";
	}
	
	$comments =	$_REQUEST['comments'];
	if(empty($_REQUEST['comments'])){
		$comments = "No comments.";
	}
	
	if ($_REQUEST['video'] == '1') {
		if ($_REQUEST['shootType'] == 'Initial') {
			$editorName = $VideoEditorName;
			$PhotoShootDate = $VideoShootDate;
			$photographerName = $VideoPhotographerName;
			$photographerEmail = $VideoPhotographerEmail;
			$photographerID = $VideoPhotographerID;
		} else {	// Secondary Video
			$editorName = $VideoReEditorName;
			$PhotoShootDate = $VideoReShootDate;
			$photographerName = $VideoRePhotographerName;
			$photographerEmail = $VideoRePhotographerEmail;
			$photographerID = $VideoRePhotographerID;
		}
	} else {
			// the 3 variables being set are already the "Initial" variables for Photo.  
			// So only check if shoot is secondary.
		if ($_REQUEST['shootType'] == 'Subsequent') {
			$editorName = $ReEditorName;
			$PhotoShootDate = $ReShootDate;
			$photographerName = $RePhotographerName;
			$photographerEmail = $RePhotographerEmail;
			$photographerID = $RePhotographerID;
		}
	}

	$templateData = array(
    	'photographer' => $photographerName,
    	'editorName' => $editorName,
		'shootDate' => $PhotoShootDate,
		'agentName' => $agentName,
		'tourID' => $tourID,
		'tourAddress' => $tourAddress, 
		'negativeFeedback' => $negativeMsg,
        'positiveFeedback' => $positiveMsg,
        'comments' => $comments,
        'supportEmail' => SUPPORT_EMAIL,
		'companyPhone' => COMPANY_PHONE,
		'uploads' => $uploadsHtml
	);

	// Determine the editor's email
	//==============================
	$adminId = $_SESSION['admin_id'];
	$res = $db->run("SELECT e.email as editor_email FROM editors e
		INNER JOIN administrators a ON a.administratorID = e.adminId
		WHERE a.administratorID={$adminId}"
	);
	$editorsEmail = $res[0]['editor_email'];

	$email_configuration = array(
    	'from' => $editorsEmail,
        'recipients' => $photographerEmail.", bret@spotlighthometours.com, shankula@spotlighthometours.com, miranda@spotlighthometours.com",
        'template' => "photographer-feedback",
        'templateData' => $templateData,
        'subject' => "Spotlight Home Tours Photography Feedback"
    );
	$emailer->configure($email_configuration);
    $ret = $emailer->send();
	
	$body = substr(CleanString($emailer->getConfiguration('email_body')), strpos(CleanString($emailer->getConfiguration('email_body')), "The Spotlight Home Tours photo editor"));
	
	$body = substr($body, strpos($body, "The Spotlight Home Tours photo editor"));
	
	$body = substr($body, 0, strpos($body, "--np")-1);
	
	$SQL = "INSERT INTO photographer_feedback (photographerID, feedback_userID, feedback, createDate) ".
				"VALUES (".$photographerID.",".$tours->userID.",'".$body."',NOW())";
	$db->run($SQL);
	
}

clearCache();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo($feedbackTitle);?>Photography Feedback</title>
<script src="../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../repository_inc/admin-packages.js" type="text/javascript"></script><!-- Packages JS file -->
<script src="../repository_inc/jquery.wysiwyg.js" type="text/javascript"></script><!-- WYSIWYG JS file -->
<script src="../repository_inc/wysiwyg-controls/wysiwyg.colorpicker.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../repository_inc/wysiwyg-controls/wysiwyg.cssWrap.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../repository_inc/wysiwyg-controls/wysiwyg.image.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../repository_inc/wysiwyg-controls/wysiwyg.link.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script src="../repository_inc/wysiwyg-controls/wysiwyg.table.js" type="text/javascript"></script><!-- WYSIWYG Control -->
<script>
	var userFiles = 0;
	function addUserFiles(){
		$("#extraUserFiles").append("<div id='userFiles_" + userFiles + "' class='userFileDiv'>" + 
				"<input name=\"userfile[]\" type=\"file\" />" + 
				"<a href='javascript:void(0);' onClick='removeFile(" + userFiles++ + ")'>[x]</a>" + 
        	"</div>"
		);
	}
	function removeFile(index){
		$("#userFiles_" + index).remove();
	}
	$(document).ready(function(){
		addUserFiles();
		addUserFiles();
		addUserFiles();

	});
</script>
<link rel="Stylesheet" type="text/css" href="../repository_css/jquery.wysiwyg.css" />
<!-- WYSIWYG Style Sheet -->
<style type="text/css" media="screen">
@import "../repository_css/template.css";
 @import "../repository_css/admin-v2.css";
</style>
</head>
<body>
<?php
	echo "<!--";
var_dump($_SESSION);
	echo "-->";
?>
<?PHP if(isset($_REQUEST['send_email'])){ 
?>
    <h1><?php echo($feedbackTitle);?>Photographer feedback sent!</h1>
    <div class="form_line" >
        <div class="button_new button_dgrey button_mid" onclick="window.close();">
            <div class="curve curve_left" ></div>
            <span class="button_caption" >Close</span>
            <div class="curve curve_right" ></div>
        </div>
    </div>
    
<?PHP 
}else{ ?>
	<form method="post" id="feedback" enctype="multipart/form-data">
    <input type="hidden" name="send_email" value="1" />
    <input type="hidden" name="tourID" value="<?PHP echo $tourID;?>" />
    <input type="hidden" name="video" value="<?PHP echo $_REQUEST['video'];?>"/>
    <h1><?php echo($feedbackTitle);?>Photographer Feedback</h1>
    <div class="form_line" >
        <div class="form_direction" >Tour Information &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tour ID: <?PHP echo $tourID; ?>
        </div>
    </div>
    <strong>Agent:</strong> <?PHP echo $agentName; ?><BR /><BR />
    <strong>Address:</strong> <?PHP echo $tourAddress; ?><BR />
    <div class="form_line" >
        <div class="form_direction" >Select <?php echo($feedbackTitle);?>Photographer</div>
    </div>
    <table width="500" border="0" cellspacing="0" cellpadding="0" >
        <TR>
            <TD><strong>Shoot Type</strong></TD><TD><strong>Date</strong></TD><TD><strong>Photographer</strong></TD><TD><strong>Editor</strong></TD>
        </TR>
<?PHP 	if($_REQUEST['video'] == 0) { ?>   
            <TR> 
                <TD><INPUT type="radio" name="shootType" value="Initial" <?php if($Progress['PhotoReIsNull']) echo"checked=checked";?>>Initial Shoot</TD>
                <TD><?php echo($PhotoShootDate);?></TD>
                <TD><?php echo($photographerName);?></TD>
                <TD><?php echo($editorName);?></TD>
            </TR>
<?php 		if ($Progress['PhotoReIsNull'] == 0) { ?>
                <TR>
                    <TD><INPUT type="radio" name="shootType" value="Subsequent" checked=checked>Re-Shoot</TD>
                    <TD><?php echo($PhotoReShootDate);?></TD>
                    <TD><?php echo($RePhotographerName);?></TD>
                    <TD><?php echo($ReEditorName);?></TD>
                </TR>
<?php		} else { ?>
                <TR>
                    <TD><INPUT type="radio" name="shootType" value="Subsequent" disabled="disabled">Re-Shoot</TD>
                    <TD>Not Scheduled</TD>
                    <TD></TD>
                    <TD></TD>
                </TR>
<?php   	}
		} else {  
			$negative = array(
				"Overexposed",
				"Underexposed",
				"No email sent",
				"Video sent too late",
				"Dirty lense",
				"Video Not long enough",
				"Too much caffeine?"
			);
			
			$positive = array(
				"Great job!",
				"Great exposures!",
				"Good exposures!",
				"Great composition!"
			);
?>				<TR> 
				<TD><INPUT type="radio" name="shootType" value="Initial" <?php if($Progress['VideoReIsNull']) echo"checked=checked";?>>Initial Shoot</TD>
				<TD><?php echo($VideoShootDate);?></TD>
				<TD><?php echo($VideoPhotographerName);?></TD>
				<TD><?php echo($VideoEditorName);?></TD>
			</TR>
<?php		if ($Progress['VideoReIsNull'] == 0) { ?>
				<TR>
					<TD><INPUT type="radio" name="shootType" value="Subsequent" checked=checked>Re-Shoot</TD>
					<TD><?php echo($VideoReShootDate);?></TD>
					<TD><?php echo($VideoRePhotographerName);?></TD>
					<TD><?php echo($VideoReEditorName);?></TD>
				</TR>
<?php 		} else { ?>
                <TR>
                    <TD><INPUT type="radio" name="shootType" value="Subsequent" disabled="disabled">Re-Shoot</TD>
                    <TD>Not Scheduled</TD>
                    <TD></TD>
                    <TD></TD>
                </TR>
<?php   	}
		}
    ?>
    </table>
    <div id="packageMsg" style="margin-bottom:-10px;"></div>
    <div class="form_line" >
        <div class="form_direction" >Negative Feedback</div>
    </div>
    <div class="form_line" style="height:auto;">
        <table width="560" border="0" cellspacing="0" cellpadding="0" >
            <?PHP
        // Split array into 2 for columns
        $arrayLength = count($negative);
        $column1 = array_slice($negative, 0, ceil(($arrayLength/2)));
        $column2 =  array_slice($negative, ceil(($arrayLength/2)), $arrayLength);
        $column1Length = count($column1);
        foreach($column1 as $index => $text){
    ?>
            <tr>
                <td width="280"><input type="checkbox" name="negative_selection[<?PHP echo $index ?>]" />
                    <?PHP echo $text ?></td>
                <td width="280"><?PHP if(isset($column2[$index])){?>
                    <input type="checkbox" name="negative_selection[<?PHP echo $index+$column1Length ?>]" />
                    <?PHP echo $column2[$index] ?>
                    <?PHP }?></td>
            </tr>
            <?PHP
        }
    ?>
        </table>
    </div>
    <br/>
    <div class="form_line" >
        <div class="form_direction">Positive Feedback</div>
    </div>
    <div class="form_line" >
        <table width="560" border="0" cellspacing="0" cellpadding="0">
            <?PHP
        // Split array into 2 for columns
        $arrayLength = count($positive);
        $column1 = array_slice($positive, 0, ceil(($arrayLength/2)));
        $column2 =  array_slice($positive, ceil(($arrayLength/2)), $arrayLength);
        $column1Length = count($column1);
        foreach($column1 as $index => $text){
    ?>
            <tr>
                <td width="280"><input type="checkbox" name="positive_selection[<?PHP echo $index ?>]" />
                    <?PHP echo $text ?></td>
                <td width="280"><?PHP if(isset($column2[$index])){?>
                    <input type="checkbox" name="positive_selection[<?PHP echo $index+$column1Length ?>]" />
                    <?PHP echo $column2[$index] ?>
                    <?PHP }?></td>
            </tr>
            <?PHP
        }
    ?>
        </table>
    </div>
    <div class="form_line" >
        <div class="form_direction">Uploads</div>
    </div>
	<div class="form_line">
		<div class='userFileDiv'>
			<input name="userfile[]" type="file" /><a href='javascript:void(0);'>[x]</a>
		</div>
		<div id='extraUserFiles'>

		</div>
		<div style='clear:both;'></div>
		<div style='float:left;position:relative;left:200px;'><a href='javascript:void(0);' onClick='addUserFiles()'>[+] Add</a></div>
	</div>
	<div style='clear:both;'></div>
    <div class="form_line" >
        <div class="form_direction" >Comments</div>
    </div>
   	<textarea rows="5" cols="67" name="comments"/></textarea>
	<BR />
    <div class="form_line" >
            <div class="button_new button_blue button_mid" onclick="$('#feedback').submit();">
                <div class="curve curve_left" ></div>
                <span class="button_caption" >Send</span>
                <div class="curve curve_right" ></div>
            </div>
        </div>
    <div class="form_line" >
        <div class="button_new button_dgrey button_mid" onclick="window.close();">
            <div class="curve curve_left" ></div>
            <span class="button_caption" >Close</span>
            <div class="curve curve_right" ></div>
        </div>
    </div>
    </form>
<?PHP 
} ?>
</body>
</html>
