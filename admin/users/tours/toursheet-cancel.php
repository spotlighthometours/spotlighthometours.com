<?php
	require '../../../repository_inc/classes/inc.global.php';
	$tours = new tours;
	$tours->loadTour($_GET['tourId']);
	$tp = new tourprogress;
	$tp->loadTour($_GET['tourId']);
?>
	<div id='cancelContainer' style='border: 1px solid black;'>
		<p>
			<input type='checkbox' id='reschedule'>Reschedule<br>
		</p>
		<div id='rescheduleContainer' style='display:none;border: 1px solid black;'>
			<p>
			Date: <input type='text' id='rescheduleDate' name='rescheduleDate'>
			<select id='photoglist'>
				<option value='0'>-- Select a photographer --</option>
				<?php
					foreach($db->select("photographers","1=1",null,"photographerID,fullName"," ORDER BY fullName ASC") as $index => $row){
						echo '<option value="' . $row['photographerID'] . '">' . $row['fullName'] . "</option>";
					}
				?>
			</select>
			</p>
		</div>
		<div style='border:1px solid black;padding:20px;'>
			<b>Cancelation Fee</b><hr>
			<input type='radio' name='billed' id='agentBilled'>Agent has been billed<br>
			<input type='radio' name='billed' id='agentNeedsBilling'>Agent needs to be billed<br>
			<input type='radio' name='billed' id='agentNotBilled'>Agent not billed<br>
		</div>
		<div style='border:1px solid black;padding: 20px;'>
			<b>Who Cancelled?</b><br>
			Name: <input type='text' id='canceledBy' value='who dat is'><br>
		</div>
	</div>
	<div style='float: right;'>
		<input type='submit' id='cancelScheduleButton' value='Save' style='padding:10px;'>
	</div>
	
