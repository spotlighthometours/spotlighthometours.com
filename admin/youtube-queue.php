<?php
/*
 * Admin: Tour Suggestions (Create / Edit)
 */

// Include appplication's global configuration
require_once('../repository_inc/classes/inc.global.php');
global $db;
if( isset($_GET['showAll']) ){
	die(header("Location: /admin/youtube-queue.php"));
}
 
// Create instances of needed objects
$users = new users($db);

// Require admin
$users->authenticateAdmin();

if( isset($_GET['ajax']) ){
	if( $_GET['ajax'] == 'changeTo' ){
		$db->update("video_progress",array('step' => $_POST['to']), "vidProgressID=" . $_POST['id']);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>YouTube Queue</title>
<script src="/repository_inc/jquery-1.8.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="/repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="/repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="/repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="/repository_inc/admin-suggestions.js" type="text/javascript"></script><!-- Admin Package JS file -->
<style type="text/css" media="screen">
	@import "/repository_css/template.css";
 	@import "/repository_css/admin-v2.css";
.list th {
	width: 20px;
}
.list tbody tr td {
	width: 20px;
}
.headerWrapper { 
	position: fixed;
	left: 230px;
	top: 20px;
	z-index: 9;
	background-color: white;
	border: 2px dotted black;
}
.dropdown {
	color: black;
	background-color: white;
}
b { 
	font-size: 10px;
}


body {
  font-family: 'Lucida Grande', 'Helvetica Neue', Helvetica, Arial, sans-serif;
  font-size: 13px;
}

ul {
  text-align: left;
  display: inline;
  margin: 0;
  padding: 5px 4px 7px 0;
  list-style: none;
  -webkit-box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
  -moz-box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
  z-index: 0;
}
ul li {
  font: bold 12px/18px sans-serif;
  display: inline-block;
  margin-right: -4px;
  position: relative;
  padding: 15px 20px;
  background: #fff;
  cursor: pointer;
  -webkit-transition: all 0.2s;
  -moz-transition: all 0.2s;
  -ms-transition: all 0.2s;
  -o-transition: all 0.2s;
  transition: all 0.2s;
}
ul li:hover {
  background: #555;
  color: #fff;
}
ul li ul {
  padding: 0;
  position: absolute;
  top: 48px;
  left: 0;
  width: 250px;
  -webkit-box-shadow: none;
  -moz-box-shadow: none;
  box-shadow: none;
  display: none;
  opacity: 0;
  visibility: hidden;
  -webkit-transiton: opacity 0.2s;
  -moz-transition: opacity 0.2s;
  -ms-transition: opacity 0.2s;
  -o-transition: opacity 0.2s;
  -transition: opacity 0.2s;
}
ul li ul li { 
  background: #555; 
  display: block; 
  color: #fff;
  text-shadow: 0 -1px 0 #000;
}
ul li ul li:hover { background: #666; }
ul li:hover ul {
  display: block;
  opacity: 1;
  visibility: visible;
  z-index: 999;
}
ul li ul li a {
	color: white;
}
a b { 
	font-size: 10px;
}
a { 
	font-size: 10px;
}
.checkedRow {
	background-color: #333;
	opacity: 0.4;
}
#stats {
	width: 100px;
	float: left;
	border: 1px solid red;
}

#cstats { 
	width: 100px;
	float: left;
	border: 1px solid green;
}
#mainWrapper {
	padding-top: 80px;
	float: left;
	margin: 0 auto;
	width: 500px;
}
#leftWrapper {
	float: left;
	border: 1px solid red;
	width: 200px;
	height: 400px;
	clear:left;
}
#emailWrapper { 
	overflow: scroll;
	height: 380px;
}
#leftSection {
	border: 1px solid blue;
	float: left;
}
#emailDump { 
	overflow: scroll;
	height: 380px;
}
</style>
<script>
$(document).ready(function(){
	$("a.ahrefcb").each(function(index,ele){
		$(this).on("click",function(){
			location.href = "/admin/youtube-queue.php?" + $(this).attr("id").split("_")[1] + "=1";
		});
	});
	$(".changeTo option").on("click",function(){
		alert($(this).val());
	});
	$("td.clickable").on('click',function(){
		id = $(this).parent().attr("id").split("_")[1];
		st = $("#cb_" + id).attr("checked");
		if( st == "checked" ){
			st = false;
		}else{
			st = true;
		}
		checkRow(id,st);
	});
	$("#selectAll").on("click",function(){
		if($(this).is(":checked")){
			$("input[type='checkbox']").each(function(a,b){
				checkRow(b.id.split("_")[1],true);
			});
		}else{
			$("input[type='checkbox']").each(function(a,b){
				checkRow(b.id.split("_")[1],false);
			});
		}
	});
	$("#getMonitor").on("click",function(){
		$.ajax({
			type: "POST",
			url: "http://www.spotlighthometours.com/repository_queries/slideshow_monitor.php",
			data:{
				'type': 'dump'
			}
		}).done(function(msg){
			$("#emailDump").html(msg);
		});
	});
});

function checkRow(id,st){
		$("#tr_" + id + " td.clickable").each(function(){
			if( st == true && $(this).hasClass("checkedRow") ||
				st == false && $(this).hasClass("checkedRow") == false){
			}else{
				$(this).toggleClass("checkedRow");
			}
		});
	if( st == undefined ){
		return;
	}else{
		$("#cb_" + id).attr("checked",st);
	}
}
function changeTo(id,ele){
	var a = {};
	if( id == -1 ){
		$("td.checkedRow").each(function(index,ele){
			trId = $(this).parent().attr("id").split("_")[1];
			a[trId] = 1;
		});
		console.log(a);
		for(i in a ){
			changeStatus(i,ele);
		}
	}else{
		changeStatus(id,ele);
	}
	location.reload();
}

function changeStatus(id,ele){
	st = $(ele).html().split(" ")[0];
	$.ajax({
		url: '/admin/youtube-queue.php?ajax=changeTo',
		type: "POST",
		data: {
			'id': id,
			'to': st
		},
		async: false
	}).done(function(msg){
		return;
	});
}
</script>
</head>
<body>

<?php
	$step_0 = $step_1 = $step_2 = $step_3 = $step_4 = $step_5 = 0;
	$cstep_0 = $cstep_1 = $cstep_2 = $cstep_3 = $cstep_4 = $cstep_5 = 0;
/*
	for($i=0;$i < 6;$i++){
		$q = "SELECT count(*) as c FROM video_progress vp
			INNER JOIN photo_tours pt ON pt.photoTourID = vp.typeID
			INNER JOIN tours t ON t.tourID = pt.tourid
			WHERE type='slideshow' AND step=$i AND t.concierge != 1";
		$res = $db->run($q);
		$var = "step_$i";
		$$var = $res[0]['c'];
	}
	for($i=0;$i < 6;$i++){
		$q = "SELECT count(*) as c FROM video_progress vp
			INNER JOIN photo_tours pt ON pt.photoTourID = vp.typeID
			INNER JOIN tours t ON t.tourID = pt.tourid
			WHERE type='slideshow' step=$i AND t.concierge = 1";
		$res = $db->run($q);
		$var = "cstep_$i";
		$$var = $res[0]['c'];
	}
	for($i=0;$i < 6;$i++){
		$q = "SELECT count(*) as c FROM video_progress vp
			INNER JOIN photo_tours pt ON pt.photoTourID = vp.typeID
			INNER JOIN tours t ON t.tourID = pt.tourid
			WHERE type='video' step=$i AND t.concierge != 1";
		$res = $db->run($q);
		$var = "step_$i";
		$$var += $res[0]['c'];
	}
	for($i=0;$i < 6;$i++){
		$q = "SELECT count(*) as c FROM video_progress vp
			INNER JOIN photo_tours pt ON pt.photoTourID = vp.typeID
			INNER JOIN tours t ON t.tourID = pt.tourid
			WHERE type='video' step=$i AND t.concierge = 1";
		$res = $db->run($q);
		$var = "cstep_$i";
		$$var += $res[0]['c'];
	}
*/

?>
<div id='leftSection'>
<div id='stats'>
	<b>Current Stats</b>
<?php
	for($i=0; $i < 6; $i++){
		echo "<p id='stat_{$i}'></p>";
	}
?>
</div>
<div id='cstats'>
	<b>Concierge Stats</b>
<?php
	for($i=0; $i < 6; $i++){
		echo "<p id='cstat_{$i}'></p>";
	}
?>
</div>

	<div id='leftWrapper'>
		<input type='button' id='getMonitor' value='Generate Error Report'>
		<div id='emailDump'></div>
	</div>

</div>

<div class='headerWrapper'>
<table>
	<thead>
		<tr>
<th>
                    <ul>
                        <li>
                <div class='dropdown'>
                    <b>Change to:</b>
                        <ul>
                            <?php
                                foreach(array(0 => 'Waiting',
                                    5 => 'Error'
                                ) as $index => $status){
                                    echo "<li onClick='changeTo(-1,this)'>" . $index . " -- " . $status . "</li>";
                                }
                            ?>
                        </ul>
                </div>
                    </ul>
                    </li>
</th>
<th>

<?php if( isset($_GET['showOnlyFinished']) ){ $fin = "checked"; }else{ $fin = ""; } ?>
<?php if( isset($_GET['showOnlyWaiting']) ){ $wait = "checked"; }else{ $wait = ""; } ?>
<?php if( isset($_GET['showOnlyErrors']) ){ $err = "checked"; }else{ $err = ""; } ?>
<a class='ahrefcb' id='ahref_showOnlyFinished' href='javascript:void(0);'><input type='checkbox' <?php echo $fin; ?> id='showOnlyFinished'> Show Only Finished</a>
<a class='ahrefcb' id='ahref_showOnlyWaiting' href='javascript:void(0);'><input type='checkbox' <?php echo $wait;?> id='showOnlyWaiting'> Show Only Waiting</a>
<a class='ahrefcb' id='ahref_showOnlyErrors' href='javascript:void(0);'><input type='checkbox' <?php echo $err;?> id='showOnlyErrors'> Show Only Errors</a>
<a class='ahrefcb' id='ahref_showAll' href='javascript:void(0);'><input type='checkbox' <?php echo $err;?> id='showAll'> Show All</a>

</th>
		</tR>
	</thead>
</table>
</div><!-- headerWrapper -->

<div id='mainWrapper'>
<table class='list'>
	<thead>
		<tr>
			<th style='width:20px;'>
				<input type='checkbox' id='selectAll'>
			</th>
			<th>VideoProgressID</th>
			<th>TourID</th>
			<th>Type</th>
			<th>MediaID</th>
			<th>Step</th>
			<th>Agent</th>
			<th>Address</th>
			<th>Created</th>
			<th>Updated</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$yesterday = date("Y-m-d 00:00:00",strtotime("-1 week",time()));
		$q="SELECT vp.* FROM video_progress vp ";
        $whered = false;
		if( isset($_GET['showOnlyFinished']) ){
			$q .= " WHERE step='4' ";
            $whered = true;
		}
		if( isset($_GET['showOnlyWaiting']) ){
			$q .= " WHERE step='0' ";
            $whered = true;
		}
		if( isset($_GET['showOnlyErrors']) ){
			$q .= " WHERE step='5' ";
            $whered = true;
		}
        if( $whered == false ){
            $q .= " WHERE ";
        }else{
            $q .= " AND ";
        }
		$q .= " (created BETWEEN '{$yesterday}' AND NOW() OR
				updated BETWEEN '{$yesterday}' AND NOW())
		 		ORDER BY vidProgressID ASC LIMIT 100 ";
echo "<!-- $q -->";
		$res = $db->run($q);
		$stats = array();
		$conc = array();
		foreach($res as $index => $row){
			$res2 = $db->select("media","mediaID=" . $row['mediaID']);
			if( isset($res2[0]) !== false ){
			    $row['tourID'] = $res2[0]['tourID'];
			}else{
				$row['tourID'] = 0;
				if( $row['type'] == 'slideshow' ){
					$r = $db->select("photo_tours","photoTourID=" . $row['typeID']);
					if( isset($r[0]) ){
						$row['tourID'] = $r[0]['tourid'];
					}
				}else if( $row['type'] == 'video'){
					$r = $db->select("media","mediaID=" . $row['typeID']);
					if( isset($r[0]) ){
						$row['tourID'] = $r[0]['tourID'];
					}
				}
			}
			$res3 = $db->select("tours","tourID=" . $row['tourID']);
			if( isset($res3[0]) ){
				if( $res3[0]['concierge'] == '1' ){
					if( isset($conc[$row['step']]) ){
						$conc[$row['step']] += 1;
					}else{
						$conc[$row['step']] = 1;
					}
				}else{
					if( isset($stats[$row['step']]) ){
						$stats[$row['step']] += 1;
					}else{
						$stats[$row['step']] = 1;
					}
				}
				$uid = $res3[0]['userID'];
				$res4 = $db->select("users","userID=$uid");
				$agent = $res4[0]['firstName'] . " " . $res4[0]['lastName'];
				$address = $res3[0]['address'];
			}else{
				if( isset($stats[$row['step']]) ){
					$stats[$row['step']] += 1;
				}else{
					$stats[$row['step']] = 1;
				}
			}
			
	?>
		<tr id='tr_<?php echo $row['vidProgressID'];?>'>
			<td class='checkbox'><input type='checkbox' id='cb_<?php echo $row['vidProgressID'];?>' onClick='checkRow(<?php echo $row['vidProgressID'];?>)'></td>
			<td class='clickable'><?php echo $row['vidProgressID']; ?></td>
			<td class='clickable'><?php echo isset($row['tourID']) ? $row['tourID'] : "--unavailable--"; ?></td>
			<td class='clickable'><?php echo $row['type']; ?></td>
			<td class='clickable'><?php echo $row['mediaID'];?></td>
			<td style='width: 150px;'><ul class='tour-list'>
					<li>
			<a href='javascript:void(0);'> <?php echo $row['step']; ?>
				<b>(<?php 
					switch($row['step']){
						case 0:
							echo "Waiting";
						break;
						case 1:
							echo "Processing";
						break;
						case 2:
							echo "Overlay";
						break;
						case 3:
							echo "Uploading";
						break;
						case 4:
							echo "Uploaded";
						break;
						case 5:
							echo "Error";
						break;
						default:
							echo "Unknown status";
						break;
					}
				?>)</b></a>
					<ul>
						<li>
				<div id='div_<?php echo $row['vidProgressID'];?>' class='dropdown'>
					<b>Change to:</b>
						<ul>
							<?php
								foreach(array(0 => 'Waiting',
									5 => 'Error'
								) as $index => $status){
									echo "<li onClick='changeTo(" . $row['vidProgressID'] . ",this)'>" . $index . " -- " . $status . "</li>";
								}
							?>
						</ul>
				</div>
					</ul>
					</li>
				</ul>
			</td>
			<td class='clickable'><?php echo $agent; ?></td>
			<td class='clickable'><?php echo $address; ?></td>
			<td class='clickable'><?php echo $row['created'];?></td>
			<td class='clickable'><?php echo $row['updated'];?></td>
		</tr>
	<?php
		}//end foreach
	?>
	</tbody>
</table>
</div>
<?PHP
	include('../repository_inc/html/modal.html');
?>
<script>
<?php
	for($i = 0; $i < 6; $i++){ 
		echo "var stat_{$i} = ";
		if( strlen($stats[$i]) ){
			echo $stats[$i];
		}else{ echo "0";
		}
		echo ";\n";
		echo "var cstat_{$i} = ";
		$var = "cstep_$i";
		if( strlen($conc[$i]) ){
			echo $conc[$i];
		}else{
			echo "0";
		}
		echo ";\n";
	}
	foreach(array(0 => 'videos waiting',
		1 => 'videos processing',
		2 => 'videos processing overlay',
		3 => 'videos being uploaded',
		4 => 'videos have been uploaded',
		5 => 'videos are in error status'
		) as $status => $msg){
		echo '$("#stat_' . $status . '").html(stat_' . $status . ' + " ' . $msg . '");';echo "\n";
		echo '$("#cstat_' . $status . '").html(cstat_' . $status . ' + " ' . $msg . '");';echo "\n";
	}
?>
</script>
</body>
</html>
