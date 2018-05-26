<?php
	require dirname(__FILE__) .'/../../repository_inc/classes/inc.global.php';
	global $db;
	ShowErrors();
	if( isset($_GET['photographerID']) == false ){
		die;
	}
	
	$p = $_GET['photographerID'];
	$pid = (string)$p;
	for($i=strlen($p);$i < 11;$i++){
		$pid = "0" . $pid;
	}
	if( isset($_POST['ajax'])){
		switch($_POST['action']){
			case "addInstructions":
				$query = "SELECT * FROM tours t 
				INNER JOIN tourprogress tp ON t.tourID = tp.tourid
				WHERE t.tourID=" . intval($_POST['tourId']);	
				$res = $db->run($query);
				die(json_encode(array('text'=>$res[0]['additionalInstructions'])));
				break;
			case "feedback":
				//$res = $db->select("photographer_feedback","photographerID=" . intval($_GET['photographerID']));
				$query = "SELECT * FROM photographer_feedback WHERE photographerID=" . intval($_GET['photographerID']) . 
					 	 " AND feedback LIKE '%http://www.spotlighthometours.com/us/" . intval($_POST['tourId']) . "%' ";
				$res = $db->run($query);
				if( isset($res[0])){
					$feedback = $res[0]['feedback'];
					die(json_encode(array('text'=>$feedback)));
				}else{
					die(json_encode(array('text'=>"")));
				}
				break;
			default; 
				die(json_encode(array('status'=>'error')));
				break;

		}
		die;
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Photographer's Tours Report</title>


<link type="text/css" href="/admin/includes/jquery-ui-1.8.9/css/ui-lightness/jquery-ui-1.8.9.custom.css" rel="stylesheet" />	
<link type="text/css" href="/repository_css/admin.css" rel="stylesheet" />
<style type="text/css" media="screen">
@import "/repository_css/template.css?rand=14536253";
</style>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script src="/repository_inc/admin.js"></script>
    <script type="text/javascript">

    	function expand(tourId){
    		$.ajax({
    			url: '/admin/photographers/upcoming.php?photographerID=<?php echo intval($_GET['photographerID']);?>',
    			data:{
    				ajax: 1,
    				action: "addInstructions",
    				"tourId":tourId
    			},
    			type: 'POST',
    			complete: function(msg){
	    			json = $.parseJSON(msg.responseText);
		    			$("#addInstructions_" + tourId).html(
		    				json.text +
		    				//"<a href='javascript:void(0);' id='a_" + tourId + " onClick='return collapse(" + tourId + ")'>&lt;&lt;&lt; less</a>" 
		    				"<a href='javascript:void(0);' id='a_" + tourId + ">&lt;&lt;&lt; less</a>" 
		    			);
	    		}/* End function*/
	    	});

    	}
    	function loadFeedback(tourId){
    		$.ajax({
    			ul: '/admin/photographers/upcoming.php?photographerID=<?php echo intval($_GET['photographerID']);?>',
    			data:{
    				ajax: 1,
    				action: "feedback",
    				"tourId":tourId
    			},
    			type: 'POST',
    			complete: function(msg){
	    			json = $.parseJSON(msg.responseText);
		    			$("#pFeed_" + tourId).html(
		    				json.text 
		    			);
	    		}/* End function*/
	    	});
    	}




		function exportTableToCSV($table, filename) {

		    var $rows = $table.find('tr:has(td)'),

		        // Temporary delimiter characters unlikely to be typed by keyboard
		        // This is to avoid accidentally splitting the actual contents
		        tmpColDelim = String.fromCharCode(11), // vertical tab character
		        tmpRowDelim = String.fromCharCode(0), // null character

		        // actual delimiter characters for CSV format
		        colDelim = '","',
		        rowDelim = '"\r\n"',

		        // Grab text from table into CSV formatted string
		        csv = '"' + $rows.map(function (i, row) {
		            var $row = $(row),
		                $cols = $row.find('td');

		            return $cols.map(function (j, col) {
		                var $col = $(col),
		                    text = $col.text();

		                return text.replace('"', '""'); // escape double quotes

		            }).get().join(tmpColDelim);

		        }).get().join(tmpRowDelim)
		            .split(tmpRowDelim).join(rowDelim)
		            .split(tmpColDelim).join(colDelim) + '"',

		        // Data URI
		        csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

		    $(this)
		        .attr({
		        'download': filename,
		            'href': csvData,
		            'target': '_blank'
		    });
		}


	    $(function() {
		    $( "#startdate" ).datepicker();
		    $( "#startdate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#enddate" ).datepicker();
		    $( "#enddate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );

			// This must be a hyperlink
			$("#exportLink").bind('click', function (event) {
				console.log('click');
			    // CSV
			    exportTableToCSV.apply(this, [$('#dvData>table'), 'export.csv']);
			    //exportTableToCSV($("#dvData>table"),"stuff.csv");
			    
			    // IF CSV, don't do event.preventDefault() or return false
			    // We actually need this to be a typical hyperlink
			});
		}); 
	</script>
	<!--


-->
<style type="text/css" media="screen">
	@import "/repository_css/template.css";
 	@import "/repository_css/admin-v2.css";
 .address { 
 	width: 190px;
 }
 .rescheduled {


 	color: green;
 }
 div.rescheduled {
 	color: black;
 }
 /*b { 
 	color: black;
 }*/
 .addInstructions {
 	font-size: 10px;
 	font-color: black !important;
 }
 .name {
 	float: right;
 }
</style>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Photographer Upcoming Tours</h1>










<FORM ACTION="" METHOD="get">
<table>
	<tr>
	<TD>
	        Start Date:<BR> 
	        <INPUT NAME="startdate" TYPE="text" ID="startdate" VALUE=""/>
	</td>
		<td>
	        End Date:<BR> 
	        <INPUT NAME="enddate" TYPE="text" ID="enddate" VALUE="" />            
	        <INPUT TYPE="submit" NAME="GO" ID="GO" VALUE="GO" />
	        
	    </TD>
	</tr>
	<tr>
		<td>
			<a href="#" id="exportLink">Click here to Export to CSV</a>
		</td>
	</tr>

</table>
<div class='name'>
	<?php 
		$res = $db->run("SELECT * FROM photographers WHERE photographerID=$_GET[photographerID]");
		$parts  = explode("-",$res[0]['fullName']);
		if( count($parts) ){
			echo "<h1>" . $parts[0] . "</h1>";
		}else{
			echo "<h1>" . $res[0]['fullName'] . "</h1>";
		}
		$query = "SELECT * FROM tours t 
			INNER JOIN tourprogress tp ON t.tourID = tp.tourid
			WHERE (tp.photographer = $pid OR tp.rephotographer = $pid)";
		if( isset($_GET['startdate']) && isset($_GET['enddate']) ){
			$start = $_GET['startdate'] . " 00:00:00";
			$end = $_GET['enddate'] . " 00:00:00";

			$query .= "
				AND (Scheduledon BETWEEN '$start' AND '$end' 
					OR ReScheduledon BETWEEN '$start' AND '$end'
				)
			";
			
		}
		$query .= "ORDER BY Scheduledon DESC";
		$res = $db->run($query);
		$tours = new tours;
		echo count($res) . " results";

	?>
</div>

<input type='hidden' name='photographerID' value=<?php echo $_GET['photographerID'];?>>
</FORM>
<div id='dvData'>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th>Tour ID</th>
			<th>Tour Type</th>
			<th>Brokerage</th>
			<th>Agent</th>
			<th>Address</th>
			<th>Date Scheduled</th>
			<th>Notes</th>
			<th>Additional Instructions</th>
			<th>Tour Link</th>
			<th>Feedback</th>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($res as $index => $row):
		?>
		<tr>
			<td valign="top" style="white-space:nowrap;"><?php echo $row['tourid']; ?></td>
			<td valign="top" style="white-space:nowrap;"><?php
					$type = $tours->loadTour($row['tourid']);
					$typeId = $tours->tourTypeID;
					$tourTypes = new tourtypes;					
					$tourTypes->load($typeId);
					echo $tourTypes->tourTypeName;
				?></td>
			<td valign="top" style="white-space:nowrap;"><?php
					$userId = $tours->getUserID($row['userID']);
					if( strlen($userId) ){
						$users = new users;
						$brokerId = $users->getBrokerID( $userId );
						if( $brokerId ){
							$res = $db->select("brokerages"," brokerageID={$brokerId}");
							echo $res[0]['brokerageName'];
						}else{
							echo "N/A";
						}
					}else{
						echo "N/A";
					}
				?></td>
			<td valign="top" style="white-space:nowrap;"><?php
					$agentId = $tours->getAgent($row['tourid']);
					if( isset($agentId[0])){
						echo $agentId[0]['firstName'] . " " . $agentId[0]['lastName'];
					}
				?></td>

			<td class="address" valign="top" style=""><?php
					echo $row['address'] . " ";
					if( $row['unitNumber'] ){
						echo "#" . $row['unitNumber'] ." ";
					}
					echo ", $row[city], $row[state], $row[zipCode]";
					if( $row['area'] ){
						echo " <br>(Area:$row[area])<br>";
					}
				?></td>
			<td valign="top" style="white-space:nowrap;"><?php 
					$sched = $row['Scheduledon']; 
					//echo date("D jS F ")
					echo date("m/d/Y h:iA",strtotime($row['Scheduledon']));
					if( $row['ReScheduledon']){
						echo "<br>";
						echo "<div class='rescheduled'>
							  <b class='rescheduled'>Re-Scheduled on: </b><br> " .
						      date("m/d/Y h:iA",strtotime($row['ReScheduledon'])) .
							 "</div>";
					}
				?></td>
			<!-- notes -->
			<td valign="top" >
<?php if( $row['contactNotes']) : ?>
<b>Contact notes: </b><?php echo $row['contactNotes']; ?><?php endif; 
if( $row['scheduleNotes']): ?><b>Schedule notes: </b><?php echo $row['scheduleNotes'];?><?php endif;
if( $row['shootNotes']): ?><b>Shoot notes: </b><?php echo $row['shootNotes']; ?>
<?php endif;
if( $row['agentNotes']): ?><b>Agent notes: </b><?php echo $row['agentNotes']; ?>
<?php endif; ?></td>
			<td><?php
	if( $row['additionalInstructions']){
			echo "<p class=\"addInstructions\" id='addInstructions_$row[tourid]'>";
			$substr = substr($row[additionalInstructions],0,50);
			echo $substr;
			if( strlen($row['additionalInstructions']) > 50 ){
				echo "...";
				echo "<a href='javascript:void(0);' id='a_{$row[tourid]}' onClick='expand($row[tourid])'>expand</a>";
			}
			echo "</p>";
	}
?></td>
			<td><a href='http://www.spotlighthometours.com/tours/tour.php?tourid=<?php echo $row['tourid'];?>&demo=true&reloaded=true' target='_blank'>Tour</a></td>
			<td><?php
					$query = "SELECT * FROM photographer_feedback WHERE photographerID=" . intval($_GET['photographerID']) . 
					 	 " AND feedback LIKE '%http://www.spotlighthometours.com/us/" . intval($row['tourid']) . "%' ";
					$res = $db->run($query);
					if( isset($res[0])){
						echo "<p id='pFeed_$row[tourid]'>";
						echo substr($res[0]['feedback'],0,50) . " ...";
						echo "<a href='javascript:loadFeedback($row[tourid])'>Expand</a>";
					}else{
						echo "--";
					}
				?></td>
		</tr>
		<?php
			endforeach;
		?>

	</tbody>
</table>
</div>

</body>
</html>