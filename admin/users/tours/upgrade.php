<?php
	require '../../../repository_inc/classes/inc.global.php';
	$tourId = intval($_GET['tourId']);
	$tours = new tours;
	$tours->loadTour($tourId);
	$tourTypeId = $tours->tourTypeID;
	$res = $db->select("tourtypes","tourTypeID=$tourTypeId");
	$currentTourName = $res[0]['tourTypeName'];
	
	if( isset($_GET['ajax']) ){
		if( $_GET['mode'] == 'save' ){
			$up = new upgrade;
			$up->tourId = intval($_GET['tourId']);
			$up->adminId = $_SESSION['admin_id'];
			$up->fromTourType = intval($_POST['from']);
			$up->toTourType = intval($_POST['to']);
			$up->created_at = date("Y-m-d H:i:s");
			$up->insert();
			
			$db->update("tours",array('tourTypeID'=>intval($_POST['to'])),"tourID=" . intval($_GET['tourId']));
			
			$en = new emailnotifications;
			$en->sendEmail("eileen@spotlighthometours.com","[UPGRADE]: Tour ID: " . intval($_GET['tourId']),
				$up->resolveAdmin($_SESSION['admin_id']) . " just upgraded tour ID (" . intval($_GET['tourId']) . ") " .
				" from a '" . $up->getTourTypeName(intval($_POST['from'])) . "' to a '" . $up->getTourTypeName(intval($_POST['to'])) . "'" .
				". This was done on: " . date("Y-m-d H:i:s")
			);
		}
		die;
	}
?>
<!-- upgrade dialog -->
<div id='upgrade-wrapper'>
	<?php 
		echo "<input type='hidden' id='upgradeTourTypeId' value=" . $tours->tourTypeID . ">";
	?>
	<div>
		<b>Current Tour:</b> <?php echo $currentTourName; ?>
	</div>
	<div>
		<b>Upgrade to:</b> 
		<select id='upgradeOptions'>
			<option value='z'>--Select a tour type--</option>
			<?php
				$res = $db->run("SELECT * FROM tourtypes ORDER BY tourTypeName ASC");
				foreach($res as $index => $row){
					echo "<option value='" . $row['tourTypeID'] . "'>" . $row['tourTypeName'] . "</option>";
				}
			?>
		</select><br>
		<div id='upgradeSaveWrapper' style='display:none;text-align:center;'>
			<input id='upgradeSaveButton' type='button' value='Save'>
		</div>
	</div>

</div>