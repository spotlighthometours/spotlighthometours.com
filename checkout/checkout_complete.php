<?php
/**********************************************************************************************
Document: checkout_complete.php
Creator: Brandon Freeman
Date: 03-03-11
Purpose: Displays the order complete confirmation. (for Ajax request)  
**********************************************************************************************/

//=======================================================================
// Error Reporting & Output Buffering
//=======================================================================

	ini_set ('display_errors', 1);
	error_reporting (E_ALL & ~E_NOTICE);
	ob_start();
	
	// Start the session
	session_start();
//=======================================================================
// Includes
//=======================================================================

	// Connect to MySQL
	require_once ('../repository_inc/connect.php');
	require_once ('../repository_inc/clean_query.php');

//=======================================================================
// Document
//=======================================================================
	
?>

  Thank you very much for your order. You will receive a confirmation shortly 
  at the email address you provided. We look forward to a continued working relationship 
  with you and hope to become your valued partner in the real estate industry 
  and an irreplaceable asset to your business.<br><br>
  If you have any questions please don't hesitate to contact us at
  <a href="mailto:customerservice@spotlighthometours.com">customerservice@spotlighthometours.com</a>.
	<div style="margin-top:20px;text-align:center;">
<?php
	// Get the most recent tourid
	$query = 'SELECT tourID, tourTypeID FROM tours WHERE userID = ' . $_SESSION['user_id'] . ' ORDER by createdOn DESC LIMIT 1';
	$r = mysql_query($query) or die("Query failed with error: " . mysql_error() . "<br />Query being run: " . str_replace(Chr(10), "<br>", $query));
	$result = mysql_fetch_array($r);
	$tourId = $result['tourID'];
	$tourtypeId = $result['tourTypeID'];
	
	if ($tourtypeId == 21) {
		//check for if a self service tour was just purchased. if so, provide link to video upload page
		echo '
		<a href="/users/users.cfm?pg=listmedia&tour=' . $tourId . '"><img src="/images/common/picture_add.png" title="Click here to upload and edit your images for this tour." style="border:0px;"/></a>	
		<a href="/users/users.cfm?pg=listmedia&tour=' . $tourId . '">Click Here to Start Uploading Images</a>
		';
	} else {
		//all other tour types, provide a link to their home page
		if (isset($_SESSION['team_user_name'])) {
			echo '<div id="formdescriptionclose" class="button right visible" onclick="parent.location = ' . Chr(39) . '/teams/users.cfm ' . Chr(39) . ';" >';
		} else {
			echo '<div id="formdescriptionclose" class="button right visible" onclick="parent.location = ' . Chr(39) . '/users/users.cfm ' . Chr(39) . ';" >';
		}
		echo '
			<div class="buttoncap greenbuttonleft" ></div>
			<div class="buttontext green" >Close</div>
			<div class="buttoncap greenbuttonright" ></div>
		</div>
		<!---<a href="/users/users.cfm">Click Here to Go to Your Account Home</a>--->
		';
	}
?>
	</div> 
</div>