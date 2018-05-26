<?php
/*
 * Author: William merfalen ( william @ spotlighthometours . com )
 * Date: 9/4/2014
 * Purpose: To allow queueing and tracking of fixes
 * Admin->Fixes->Tracking System
 */
require_once('../../repository_inc/classes/inc.global.php');
//error_reporting(-1);
ini_set('display_errors',0);

define("RESOLVED_COLOR","#e2f0fa");
define("RESOLVED_REPLY_COLOR","#e2f0fa");
$resolveEmail = array("heather@spotlighthometours.com",
                      "lisa@spotlighthometours.com"
                );
showErrors();
clearCache();

//###################################
// Create instances of needed objects
//###################################
$users = new users($db);

// Require admin
$users->authenticateAdmin();

$fixes = new fixes;
//###################################
// Update notes via AJAX
//###################################
if( isset($_POST['updateFixNotes']) && isset($_POST['fixIndex']) ){
	/* Update the fix notes */	
	$fixes->updateNote($_POST['fixIndex'],$_POST['updateFixNotes']);
}

//###################################
// Set Status via AJAX
//###################################
if( isset($_GET['status_set']) ){
    $fixes->updateStatus($_POST['id'],$_POST['status'],$_SESSION['admin_id']);
    $emailData = $fixes->buildNotifyEmail(
        array("mode"=> fixes::UPDATE,
            "postId" => $_POST['id'],
            "status" => $_POST['status'],
            "from" => $_SESSION['admin_id'],
            "alwaysSend" => $resolveEmail
        )
    );
    
    //##########################################
    // Email the appropiate people if resolved
    //##########################################
    if( $_POST['status'] == fixes::RESOLVED ){
        /* 
         * This call is being replaced by the buildNotifyEmail($a['alwaysSend']) array
         * $fixes->setAlwaysSend(MASTER_RESOLVE_EMAIL);
         */
        $body = $emailData['body'];
        $body .= $fixes->getTranscript($_POST['id']);
        //var_dump($body);
        $fixes->emailNotify($emailData['sendTo'],$emailData['subject'],$emailData['body'] );
    }
    die();
}
//###################################
// Set HEATHERIZED Status via AJAX
//###################################
if( isset($_GET['heatherized_set']) ){
    $fixes->updateHeatherized($_POST['id'],(bool)$_POST['heatherized'],$_SESSION['admin_id']);
    die();
}
//###################################
// Grab notes via AJAX
//###################################
if( isset($_POST['request']) && $_POST['request'] == 'notes' ){
    $a = $fixes->ajaxGrabNotes($_POST['id']);
    $notes = wordwrap(json_decode($a,true)[0]['notes'],55);
    die($notes);
}


//###################################
// New Fix
//###################################
if( isset($_POST['submit']) && $_POST['submit'] == 'Create' ){
	if( isset($_FILES['attachments']) ){
		//echo "Processing attachments... <hr>";
		$fileKey = "attachments";
	}else{
		$fileKey = null;
	}
	if( isset($_POST['reply']) && $a=intval($_POST['reply']) ){
		$parent = $a;
		// Set the fixType to either 'video' or 'photo'
		
        $res = $db->run("SELECT editor,fixType from fixes where id=$parent");
        $fixType = $res[0]['fixType'];
        $editor = $res[0]['editor'];
        
        if( $editor == 0 ){
            //
            $res2 = $db->run("SELECT type from administrators " . 
                " WHERE administratorID=" . $_SESSION['admin_id']
            )[0]['type'];
            if( $res2 == 'editor' ){
                $db->run('UPDATE fixes set editor=' . $_SESSION['admin_id'] . 
                    ' WHERE id=' . $parent
                );
            }
        }
	}else{
		$parent = null;
		$fixType = $_POST['vidPhoto'];
	}
	
	$fixes->newNote(
		intval($_POST['tourId']), 
		intval($_SESSION['admin_id']),
		null,
		htmlentities($_POST['notes']),
		($parent===null ? "employee" : "editor" ),
		$fileKey,
		$parent,
        $fixType
	);
    if( $parent ){
        $a = $fixes->buildNotifyEmail(
        	[
            	"mode" => fixes::PARENT_REPLY,
            	"postId" => $_POST['reply'],
            	"notes" => htmlentities($_POST['notes']),
            	"from" => $_SESSION['admin_id']
        	]
    	);
        $fixes->emailNotify($a['sendTo'], $a['subject'], $a['body']);
    }
}

//##########################################
// AJAX: Assign me to this task 
//##########################################
if( isset($_POST['switch_direction'])){
	$fixes->assign($_POST['id'],
			($_POST['switch_direction']=='switch-on' ?	'on' : 'off'),
			$_SESSION['admin_id']
    );
	if( $_POST['switch_direction'] == 'switch-on'){
		die('on');
	}else{
		die('off');
	}
}

//###########################################
// AJAX: Grab Uploads JSON
//###########################################
if( isset($_POST['uploadsJson']) ){
	if( $fixes->uploadsExist($_POST['id']) ){
		$json = $fixes->getJsonUploads($_POST['id']);
		echo $fixes->webifyUploads($json);
	}else{
		echo "";	
	}
	die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fixit Tracking System</title>



<link rel="stylesheet" href="jquery-ui.css">

<!--  JQUERY + JQUERY UI -->
<script src="../../../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->

<!--  qtip2  -->
<script src="../../../repository_inc/jquery.qtip.js" type="text/javascript"></script>
<link rel="stylesheet" href="../../../repository_css/jquery.qtip.css"></link>

<!--  Spotlight functions -->
<script src="../../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="trackingSystem.js?<?php echo rand(0,100000) * rand(0,1000); ?>" type='text/javascript'></script>

<link href="../includes/admin_styles.css" rel="stylesheet" type="text/css">
<style type="text/css" media="screen">
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css"; 
    @import "../../repository_css/jquery-ui-1.8.16.custom.css";
    @import "trackingSystem.css";
table {
border-collapse: separate;
border-spacing: 2px;
border-color: gray;
}
.notesCreateTextarea {
    border: 1px solid black;
}
.notesCreateRequired {
}
.tourIdRequired {
    float: left;
    margin-left: 150px;
}
.notesRequired {
    margin: 115px 0px 0px 0px;
    border: none;
}

.addFiles { 
    margin: 110px 0px 0px 85px;
    width: 155px;
}
input[type='file'] {
    left: 11px;
    position: absolute;
}
.mainTable {
    width: 100%;
}
#listtable {
    width: 100%;
}
#list {
    width: 100%;
}
#results_wrapper {
    width: 100%;
}
#fixitHeader {
    width: 100%;
    float: right;
    clear: both;
}
#createFixit {
    width: 1050px;
    overflow: hidden;
}
#createAddFilesDiv2 {
    top: -20px;
}
.on {  
    background-position: 0% 100%;  
}  
  
.heatherizedCheckbox  {  
    display: block;  
    width: 87px;  
    height: 28px;  
    background: url('/admin/images/the-heather-button.png') no-repeat;
    background-size: 20px 20px;
    background-position: 20px 4px;
    -webkit-border-radius: 5px;  
    -moz-border-radius: 5px;  
    border-radius: 5px;  
    overflow: hidden;  
    cursor: pointer;  
} 
.heatherizedCheckboxOn {
    display: block;  
    width: 87px;  
    height: 28px;  
    background: url('/admin/images/the-heather-button.png') no-repeat;
    background-position: 20px 4px;
    background-size: 20px 20px;
    -webkit-border-radius: 5px;  
    -moz-border-radius: 5px;  
    border-radius: 5px;  
    overflow: hidden;  
    cursor: pointer;  
    background-color: #C3D9FF;
}   

.heatherizedCheckboxOn:hover { 

    background-position: 20px 0px;
    background-size: 30px 30px;
}
.heatherizedCheckbox:hover {
    background-position: 20px 0px;
    background-size: 30px 30px;
    background-color: #C3D9FF;
}   
</style>
</head>
<body>
<div id='fixitHeader'>
<h1>Fix it! Tracking System</h1>
</div>
<div id=saved>&nbsp;</div>
<?php
	if( isset($error) && strlen($error) ){
		echo '<div class="errors">';
		echo $error;
		echo '</div>';
	}
?>

<?PHP
	if(isset($alert) && strlen($alert) ){
		echo '<div class="alert">';
		echo $alert;
		echo '</div>';
	}

	//Grab all employees
	//$employees = array("Lisa","Bret","Brianna","Amy","Ishma");
	$employees = $db->select("administrators");
?>


<!-- ############################################ -->
<!-- Start New Fixit -->
<!-- ############################################ -->
<div id='form_wrapper'>
	<form style='width: 20px;' enctype="multipart/form-data" action="" method="POST">
        <div id="createFixit" class="form_line" style='position:relative;top: -20px;'>
            <div class="form_direction">Create a fix it request</div>
        </div>
        <div class="form_line" style='position:relative;top: -40px;'>
        <div class="input_line w_lg" style='width: 560px;'>
            <div class="input_title">Tour ID</div>
		<input style='width: 100px;' type='text' name='tourId' value=<?php
			if( isset($_GET['searchByTourId']) && intval($_GET['searchByTourId']) )
				echo intval($_GET['searchByTourId']);
        ?>>
        </div>
        <div class="required_line w_lg" style='float:left;'> 
            <span class="required tourIdRequired">required</span> 
        </div>
        <div style='position:relative;left:200px;top: -43px;width:200px;'>
            Video:<input type='radio' name='vidPhoto' value='video'>
            Photo: <input type='radio' name='vidPhoto' checked="checked" value='photo' style='display:inline-block;'>
        </div>
        <div class="form_line">
        <div class="input_line w_lg notesCreate" style='position:relative;top:-20px;width: 560px;'>
            <div class="input_title">Notes</div>
            <textarea name=notes cols=40 rows=10 class='notesCreateTextarea'></textarea>
        </div>
            <div style='clear:both;'>&nbsp;</div>
        <div style='margin-top: 100px;margin-left: -10px; position:relative;top: -20px;' id='notesCreateDiv' class="required_line w_lg"> <span id='notesCreateRequired' class="required">required</span> </div>
    </div>
	<div class=addFiles id='createAddFilesDiv2'>
        <a href='#' id='createAddFiles' style='position: relative;top: -40px;'>[ + ] Add more files</a><br>
    </div>
	<div id='createAddFilesDiv' style='position:relative;top: -40px;left:-25px;'>
		&nbsp;
	    <input type='file' name='attachments[]' style='margin-left:100px;'> 
	</div>
    </div>
    <div id=createButton style='left:400px;top:140px' class="button_new button_blue button_mid" onclick="$('#hiddenCreateSubmit').trigger('click')">
                <div class="curve curve_left"></div>
                <span class="button_caption">Create</span>
                <div class="curve curve_right"></div>
    </div>
	<input id='hiddenCreateSubmit' type='submit' name='submit' value='Create' style='display:none;'>	
	</form>

<!--	<div style='clear:both;'></div> -->
</div>

<!-- ############################################ -->
<!-- End new fixit -->
<!-- ############################################ -->

<div id='results_wrapper'>
    	<div id='searchBarDiv' style=''>
		<form id='searchForm' method=GET>
		<label for=searchByTourId style='padding-right: 10px; margin-top:35px;'>
			<b>Search: </b>
		</label>
			<input type='text' name='searchByTourId' style='width:200px;height: 30px;' <?php
			if( isset($_GET['searchByTourId']) ){
				echo "value=" . intval($_GET['searchByTourId']);
			}?>>
			<a href='#' id='searchButton'>
                <img src='mag.png' style='width:30px;height:20px;margin:10px 10px 0px 10px;'></a>
		</form>
	</div>
<div id="tableWrapper" class="visible" >

                <div id="listtable" >

	<?php
        
        function tourInfoLink($tourId){
            $buffer = "<a href='#' id='infoLink_$tourId'>$tourId</a>";
            return $buffer;
        }
        function recentTourLink($tourAddress,$tourId){
            return '<td><a href="/admin/recenttours/?tourid=' . $tourId . '"' .
                 '&GO2=GO" id="recentTourLink_' . $tourId . '"' . 
                 ' target=_blank>' .
                    $tourAddress . 
                 '</td>';
            
        }
        function getBrokerage($tourId){
            global $db;
            $query = "SELECT brokerageName FROM brokerages b " .
                "INNER JOIN users u ON u.BrokerageID = b.brokerageID " .
                "INNER JOIN tours t ON t.userID = u.userID " . 
                "WHERE t.tourID = " . intval($tourId);

            return $db->run($query)[0]['brokerageName'];
        }
        function checkedAssign($fixId,$adminId){
            global $db;
            $res = $db->run("select assignedTo FROM fixes where id=" . intval($fixId) . 
                " AND assignedTo=" . intval($adminId)
            );
            if( isset($res[0]) ){
            	if( $adminId == $res[0]['assignedTo']){
            		return "checked=checked";
            	}
            }
        }

        if( isset($_GET['searchByTourId']) ){
        	dumpTable(" parentId IS NULL and status='" . fixes::OPEN . "' AND tourId=" .
        			$_GET['searchByTourId'], false
        	);
        	echo "<div style='padding-top: 50px;'><h1>Resolved: </h1><hr></div>";
        	dumpTable(/*" parentId IS NULL and */ " status='" . fixes::RESOLVED . "' AND tourId=" .
        			$_GET['searchByTourId'], true, false
        	);
        	//dumpTable(" AND parentId IS NULL and status='resolved'",$_GET['searchByTourId']);
        }else{
        	dumpTable(" parentId IS NULL AND status='" . fixes::OPEN . "'",null,false);
        	echo "<h2>Resolved: </h2><hr>";
        	dumpTable(
        	   " parentId IS NULL AND status='" . fixes::RESOLVED . "'", /* Query */
        	   null, /* Tour ID */
        	   true, /* resolved */
        	   false);/* Oldest First */
        	//dumpTable(" parentId IS NULL AND status='resolved' ");
        }
    
        function dumpTable($query,$_tourId = null,$resolved=false,$oldestFirst=true){
                global $fixes;
                global $db;
                $tour = new \tours();
                $rows = $fixes->getList($query);
                if( $oldestFirst == false ){
                    $rows = array_reverse($rows);
                }
                echo "
                <table class=mainTable>
                    <tr>
                    <th>Tour ID</th>
                    <th>Address</th>
                    <th>Tour Type</th>
                    <th>Agent</th>
                    <th>Brokerage</th>
                    <th>Fix Type</th>
                    <th>Requested By</th>
                    <th>Editor</th>
                    <th>Notes</th>
                    <th>Status</th>
                    <th>Date Requested</th>
                    <th>Uploads</th>
                    <th>Resolved</th>
                    <th>Resolved Date</th>
                    <th>Action</th>
                    </tr>
                ";
                $ctr = 0;
                
                foreach($rows as $array => $item){
                    $list = $fixes->getChildNodes($item['id']);
                    if( $_tourId != null ){
                        $tourId = intval($_tourId);
                    }else{
                        $tourId = intval($item['tourId']);
                    }
					$tour->loadTour( $tourId );
                    $type = $tour->getTourType($tour->tourTypeID,'tourTypeName')['tourTypeName'];
					$tourAddress = $tour->getAddress();
					
                    //########################################
                    // REQUESTED BY 
                    //########################################
                    //Grab original poster's full name
                    $op = $db->run("SELECT fullName from administrators " .
                          " where administratorID=" . intval($item['op'])
                    )[0];
                    echo '<tr id=trRows_' . $item['id'];
                    
                    //Alternate between highlight and nohighlight styles
                    $h = ($ctr++ % 2 == 0) ? " bgcolor='#E8EEF7' " : " bgolor='#ffffff' " ;
                    echo $h;
                    //print(' style="background-color: ' . RESOLVED_COLOR . ';" ');
					$editor = $fixes->resolveEditor($item['editor']);
                    echo ">";
                    //####################################
                    // TOUR ID + ADDRESS
                    //####################################
                    echo '<td class=center>' . tourInfoLink( $item['tourId'], $tourAddress ) . '</td>';
                    //####################################
                    // RECENT TOURS LINK
                    //####################################
                    echo recentTourLink($tourAddress,$tourId);
                    //#####################################
                    // TOUR TYPE 
                    //#####################################
                    echo '<td class=center>' . $type . '</td>';

                    
                    //#####################################
                    // AGENT NAME
                    //#####################################
                    if( isset($tour->userID) ){
                        $res = $db->run("SELECT firstName, lastName FROM users ".
                            " WHERE userID=" . $tour->userID
                        )[0];
                    }

                    echo "<td class=center>" . $res['firstName'] . " " . 
                        $res['lastName'] . "</td>";
                    //#####################################
                    // BROKERAGE
                    //#####################################
                    echo "<td class=center>". getBrokerage($item['tourId']) . "</td>";
                    //#####################################
                    // FIX TYPE
                    //#####################################
                    echo "<td class=center>" . $fixes->getType($item['id']) . "</td>";
                    //#####################################
                    // REQUESTED BY
                    //#####################################
                    echo '<td class=center>' . $op['fullName'] . '</td>';
                    //#####################################
                    // EDITOR NAME
                    //#####################################
                    $res = $fixes->grabOGEditor($tourId);
                    echo "<td>";
                    if( $res === null ){
						$fullName = "--<br>";
					}else{
                    	$fullName = $res['fullName'] . "<br>";
                    }
                    //#####################################
                    // ASSIGNED TO
                    //#####################################
                    $who = $fixes->getAssignedTo($item['id'])[0];
                    
                    if( $_SESSION['admin_id'] == $who['administratorID'] ){
                        //This post belongs to current user
                        $checked = "checked=checked";
                    }else{
                        $checked = "";
                        echo "Current:" . $who['fullName'] . "<br>";
                    }
                    if( $resolved ){
                        $name = $fixes->getResolvedBy($item['id']);
                        if( strlen($name)) {
                            echo "Resolved By:$name<br>";
                        }
                    }
                    echo "OG: $fullName<br>";
                    echo "Assign me:";
                    echo "<input type='checkbox' " . checkedAssign($item['id'],$_SESSION['admin_id']) ;
                    echo " id='cbAssign_" . $item['id'] . "'/>";
                    

                    //######################################
                    // VIEW NOTES
                    //######################################
                        echo '<td class=center><a href="" class=viewNotes_' . $item['id'] . '>View Notes</a></td>' ;
                    //######################################
                    // STATUS
                    //######################################
                        echo '<td class=center id=status_' . $item['id'] . '>' . $item['status'];
                        echo '</td>';
                    //######################################
                    // DATE REQUESTED
                    //######################################
                        echo '<td class=center style="width: 20px">' . $item['dateRequested'] . '</td>';
                    //######################################
                    // UPLOADS
                    //######################################
                        echo '<td class=center style="width: 10px;">';
                        if( $fixes->uploadsExist($item['id']) ){
							$cnt = $fixes->countUploads($item['id']);
							if( $cnt != 1 ){
								$append = 's';
							}else{
								$append = '';
							}
							echo '<a title="' . $cnt . ' upload' . $append .
								'" href="javascript:void(0);" id="aUploads_' . $item['id'] . '">';
							echo 'uploads </a>';
						}else{
							echo 'none';
						}
						echo '</td>';
                    //######################################
                    // RESOLVED
                    //######################################
                        echo "<td style='width:0px;padding:0px;margin:0px;'><input class='input' type='checkbox' id=resolver_" . $item['id'] ;
                        echo $resolved ? " checked=checked " : "";
                        echo " name=status_set>";
                    //######################################
                    // HEATHERIZER 
                    //######################################
                    
                        if( $item['heatherized'] != '0' ){
                            echo "<div class='heatherizedCheckboxOn'>";
                        }else{
                            echo "<div class='heatherizedCheckbox'>";
                        }
                        echo "<input class='input' type='checkbox' id=heatherizer_" . $item['id'] ;
                        echo $item['heatherized'] != '0' ? " checked=checked" : "";
                        echo " name=heatherizer_set>";
                        echo "</div>";
                        echo "</td>";
                    //######################################
                    // DATE RESOLVED
                    //######################################
                        if( substr($item['dateResolved'],0,4) != '0000' ){
                            echo "<td class=center>" . $item['dateResolved'] . "</td>";
                        }else{
                            echo "<td class=center></td>";
                        }
                    //######################################
                    // REPLY BUTTON
                    //######################################
                    echo '<td> <a tourId=' . $item['tourId'] . ' target=_blank id="replyButton_' . $item['id'] . '" href="' . $item['id'] . '">Reply</a> </td>';
                    if( $list ){
                    	 
                        //##################################
                        // SLIDE TOGGLE
                        //##################################
                        echo "<tr $h><td colspan=99><a href='javascript:void(0);' id='slideToggleAnchor_" . $item['id'] . "'>Click here to see replies</a></td></tr>";
                        //##################################
                        // CHILD NODES
                        //##################################
                        $ctr=0;
                        
                        foreach($list as $key => $innerItem){
                            $op = $db->run("SELECT fullName from administrators " . 
                                " where administratorID=" . intval($innerItem['op']))[0];
                            $person = $fixes->resolvePoster($innerItem['id'],$innerItem['op']);
                            
                            /*
                            if( $tour->tourTypeID === null && $resolved == false ){
//								var_dump($tour);
//								die;
                            	                            
                                //die("Could not find tour by that tour ID");
                                
                                continue;
                            }*/
                            
                            //#############################
                            // REPLY BUTTON
                            //#############################
                            echo    "<tr $h id=\"slideToggle_" . $item['id'] . "\" class='reply_" . $item['id'] . "'>" . 
                                    "<td class='reply_row' $styles" .
                                    "><img src='2rightarrow.png' width=20px;></td>" .
                                    "<td colspan=11 $styles>";
                            //#############################
                            // CHILD NODE REPLY BUTTON
                            //#############################
                            echo "<div id='replyDiv_" . $item['id'] . "a' " . 
                                 " class='reply_notes_text' $styles>At " . $innerItem['dateRequested'] .
                                 " <b>$person said:</b>  " .
                                 html_entity_decode( $innerItem['notes'],ENT_COMPAT) . "</div>" . 
                                 '<div id="replyDiv_' . $item['id'] . 'b" class="reply_uploads" ' . 
                                 $styles . '>' . $fixes->webifyUploads($innerItem['uploads']) . 
                                 "</div></td>"
                            ;
                            echo "</tr>";//Slide toggle class
                        }//end foreach
                        //echo "Count: " . count($list) . "<br>";
                        //echo "<b>CTR: $ctr<hr>";
                    }//end if($list)
            }//end foreach
            echo "</table>";
		}//end function
		echo '</table>';
	?>
    </div><!-- End listtable -->
    </div><!-- end id=list -->
</div>

<input type='hidden' id='fixIndex' value=0/>

<div id='viewNotesModal' title='View Notes' style='display:none'>
    <span><b>Notes</b></span> | <a href='#' id="viewNotesEdit" style='display:inline-block;'>Edit</a>
    <div id='viewNotesModalNote' style='overflow:scroll;height: 400px;'>&nbsp;</div>
    <div id='viewNotesTWrapper' style='display:none;'>
    	<textarea id='viewNotesTextarea' rows="10" cols="8"></textarea>
    	  <div id='viewNotesSaveWrapper' style='float:right;' class="button_new button_blue button_mid">
                <div class="curve curve_left"></div>
                <span class="button_caption">Save</span>
                <div class="curve curve_right"></div>
    	</div>
    </div>
    
</div>
<div id='uploadsModal' title='View Notes' style='display:none'>
    <h3>Uploads</h3>
    <div id='uploadsModalContent' style='overflow-y:scroll;height: 400px;'>&nbsp;</div>
</div>

<div id="dialog" title="Reply Notes">
	<h3>Reply to Fix request</h3>
	<form id='modalReplyForm' enctype="multipart/form-data" action="" method="POST">
    <div class="form_line">
        <div class="input_line w_lg">
            <div class="input_title">Tour ID</div>
	    	<input type='text' name='tourId' id='dialogTourId' value=<?php
			if( isset($_GET['searchByTourId']) && intval($_GET['searchByTourId']) ){
				echo intval($_GET['searchByTourId']);
			}
		?>>
        </div>
        <div class="required_line w_lg"> <span class="required" style='margin-top: 40px;'>required</span> </div>
    </div>
<br>
<div>
<div class="form_line">
        <div class="input_line w_lg" style='height: 70px;'>
            <div class="input_title">Notes</div>
		    <textarea name=notes cols=5 rows=3></textarea>
        </div>
        <div class="required_line w_lg"> 
            <span style='top:33px;float:right;clear:left; ' class="required">required</span> 
        </div>
    </div>
</div>
<br>
            <div style='clear:both;'>&nbsp;</div>
	<div id='replyAddFilesDiv'>
    <div class="form_line">
        <div class="input_line w_lg">
            <div class="input_title">Upload </div>
	        <a href='' id='replyAddFiles' style='line-height:3;margin-left:25px;'>[ + ] Add more files</a><br>
            <input type='file' class='file' name='attachments[]' style='margin-left: 100px;'>
            <div class="input_info" style="display: none;">
                <div class="info_text">Floorplan label. I.e: 1rst Floor</div>
            </div>
        </div>
    </div>
    </div>
	
	<br>
    <div style='float:right;' class="button_new button_blue button_mid" onclick="$('#hiddenSubmit').trigger('click')">
                <div class="curve curve_left"></div>
                <span class="button_caption">Create</span>
                <div class="curve curve_right"></div>
    </div>
    <input type='submit' id='hiddenSubmit' name='submit' value='Create' style='display:none;'>
	<input type='hidden' name='reply' id='parent_node' value="">
	</form>
</div>

</body>
</html>
