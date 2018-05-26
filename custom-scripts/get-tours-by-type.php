<?PHP
	// Global Application Configuration
	require_once ('../repository_inc/classes/inc.global.php');
	showErrors();
	set_time_limit(0);
	
	$tourTypes = array(
		"129" => "Aerial Photos ONLY",
		"130" => "Aerial Video ONLY",
		"96" => "Hawaii Aerial Only",
		"95" => "Kauai Aerial Only",
		"116" => "Low Altitude Aerial Only",
		"189" => "Town Advisor Aerial"
	);
	
	$additionalProducts = array(
		"23" => "Aerial Photos Addon",
		"20" => "Aerial Videos Addon",
		"124" => "Aerial Videos Plus Addon"
	);
	
	// Pull all of the tours for the above tour types
	$tourList = array();
	foreach($tourTypes as $tourTypeID => $tourTypeName){
		$tourList[$tourTypeName] = $db->select('tours', "tourTypeID='".$tourTypeID."'", "", "tourID, title, address, city, state, zipCode", "ORDER BY tourID DESC");
	}
	
	// Pull all of the tours for the above additional products
	$tourListAp = array();
	foreach($additionalProducts as $prodID => $prodName){
		$tourListAp[$prodName] = $db->run('SELECT t.tourID, t.title, t.address, t.city, t.state, t.zipCode FROM orders o, orderdetails od, tours t WHERE od.type="product" AND od.productID="'.$prodID.'" AND o.orderID = od.orderID AND t.tourID = o.tourid ORDER BY t.tourID DESC');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tours by Tour Type + Addons (Aerials)</title>
<script src="../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<script src="../repository_inc/admin-floorplans.js" type="text/javascript"></script><!-- Floorplan JS file -->
<style type="text/css" media="screen">
	@import "../repository_css/template.css";
 	@import "../repository_css/admin-v2.css";
</style>
</head>
<body>
<h1>Tours by Tour Type + Addons (Aerials)</h1>
<table border="0" cellspacing="0" cellpadding="0" class="list">
	<thead>
		<tr>
			<th align="center">ID</th>
			<th>Title</th>
			<th>Address</th>
            <th></th>
		</tr>
	</thead>
	<tbody>
<?PHP
	// Tour Types
	foreach($tourList as $tourTypeName => $tourTypeTours){
		if(count($tourTypeTours)>0){
?>
		<tr>
        	<td colspan='4'><h2><?PHP echo $tourTypeName ?></h2></td>
        </tr>
<?PHP
			foreach($tourTypeTours as $tttrow => $tttcolumns){
				$finalized = $db->select('tourprogress', "tourid='".$tttcolumns['tourID']."'", "", "finalized, VideoFinalized");
				$finalized = $finalized[0];
				$isfinalized = false;
				if($finalized['finalized']==1||$finalized['VideoFinalized']==1){
					$isfinalized = true;
				}
				if($isfinalized){
?>
		<tr id="tour_<?PHP echo $tttcolumns['tourID'] ?>">
			<td><?PHP echo $tttcolumns['tourID'] ?></td>
			<td style="white-space:nowrap !important;"><?PHP echo $tttcolumns['title'] ?></td>
			<td><?PHP echo $tttcolumns['address']." ".$tttcolumns['city'].", ".$tttcolumns['state']." ".$tttcolumns['zipCode'] ?></td>
			<td class="list-button" style="white-space:nowrap !important;"><a href="javascript:viewTour(<?PHP echo $tttcolumns['tourID'] ?>)">View Tour</a></td>
		</tr>
<?PHP
				}
			}
		}
	}

	// ADDons
	foreach($tourListAp as $prodName => $prodTours){
		if(count($prodTours)>0){
?>
		<tr>
        	<td colspan='4'><h2><?PHP echo $prodName ?></h2></td>
        </tr>
<?PHP
			foreach($prodTours as $tptrow => $tptcolumns){
				$finalized = $db->select('tourprogress', "tourid='".$tptcolumns['tourID']."'", "", "finalized, VideoFinalized");
				$finalized = $finalized[0];
				$isfinalized = false;
				if($finalized['finalized']==1||$finalized['VideoFinalized']==1){
					$isfinalized = true;
				}
				if($isfinalized){
?>
		<tr id="tour_<?PHP echo $tptcolumns['tourID'] ?>">
			<td><?PHP echo $tptcolumns['tourID'] ?></td>
			<td style="white-space:nowrap !important;"><?PHP echo $tptcolumns['title'] ?></td>
			<td><?PHP echo $tptcolumns['address']." ".$tptcolumns['city'].", ".$tptcolumns['state']." ".$tptcolumns['zipCode'] ?></td>
			<td class="list-button" style="white-space:nowrap !important;"><a href="javascript:viewTour(<?PHP echo $tptcolumns['tourID'] ?>)">View Tour</a></td>
		</tr>
<?PHP
				}
			}
		}
	}
?>
	</tbody>
</table>
<script>
	loadListEffects()
</script>
</body>
</html>