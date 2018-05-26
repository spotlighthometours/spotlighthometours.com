<?php
/*
 * Admin: Reports / Regional Sales
 */

// Include appplication's global configuration
require_once('../../repository_inc/classes/inc.global.php');
showErrors();
clearCache();

// Create instances of needed Objects
$affiliates = new affiliates();

// First thing is first lets pull all the states that have tours ordered for the default date which is the last 24 hours.
$whereD = "t.createdOn>CURRENT_DATE - INTERVAL 7 DAY AND t.tourTypeID!='".DIY_TOUR_TYPE_ID."'";
if(isset($_REQUEST['fromdate'])&&isset($_REQUEST['todate'])){
	$whereD = "t.createdOn BETWEEN '".$_REQUEST['fromdate']."' AND '".$_REQUEST['todate']."' AND t.tourTypeID!='".DIY_TOUR_TYPE_ID."'";
}
$whereS = "";
if(isset($_REQUEST['state'])&&isset($_REQUEST['state'])){
	$whereS = " AND t.state='".$_REQUEST['state']."'";
}
$states = $db->select('tours t', $whereD.$whereS, '', 'DISTINCT(state)', 'ORDER BY state ASC');
$statesLs = $db->select('tours t', $whereD, '', 'DISTINCT(state)', 'ORDER BY state ASC');
//$affiliatesLst = $db->run("SELECT DISTINCT(b.affiliatePhotographerID) FROM tours t, users u, brokerages b WHERE b.affiliatePhotographerID>0 AND u.userID = t.userID AND b.brokerageID = u.brokerageID AND ".$whereD.$whereS);
$totalPhoto = 0;
$totalVideo = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sales Report</title>
<script src="../../repository_inc/jquery-1.6.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
	@import "../../repository_css/jquery-ui-1.8.16.custom.css";
	@import "../../repository_css/template.css";
 	@import "../../repository_css/admin-v2.css";
</style>
<script>
	$(function() {
    	$("#fromdate").datepicker({ 
			defaultDate: "-7d",
			dateFormat: 'yy-mm-dd',
			onSelect: function(date){
				if(date<$("#todate").val()){
					loadReport();
				}else{
					alert("Woh! Woh! Hold you're horeses man! The date you just selected for the first field which is suppose to be the from: date is greater then or equal to the to: date! Please select a lesser date then the current date in the right/second date field! If you want the data from a specific day you must select that day in the first field (from:) then the day after in the second field (to:)");
				}
			}
		}).datepicker("setDate", "<?PHP echo (isset($_REQUEST['fromdate']))?$_REQUEST['fromdate']:'-7d'; ?>");
		$("#todate").datepicker({
			defaultDate: "+1d", 
			dateFormat: 'yy-mm-dd',
			onSelect: function(date){
				if(date>$("#fromdate").val()){
					loadReport();
				}else{
					alert("Woh! Woh! Hold you're horeses man! The date you just selected for the second field which is suppose to be the to: date is less then or equal to the from: date! Please select a greater date then the current date in the far left date field! If you want the data from a specific day you must select that day in the first field (from:) then the day after in the second field (to:). This field is meant to be selected second / last. Select the from: date first please...");
				}
			}
		}).datepicker("setDate", "<?PHP echo (isset($_REQUEST['todate']))?$_REQUEST['todate']:'+1d'; ?>");
		$("select[name='states'], select[name='affiliates']").change(function(){
			loadReport();
		});
  	});
	function loadReport(){
		ajaxMessage("Loading Report", "processing");
		var queryString = '?todate='+$("#todate").val()+'&fromdate='+$("#fromdate").val();
		/*if($("select[name='affiliates']").val()!=="0"){
			queryString += '&affiliate='+$("select[name='affiliates']").val();
		}*/
		if($("select[name='states']").val()!=="0"){
			queryString += '&state='+$("select[name='states']").val();
		}
		window.location = queryString;
	}
</script>
</head>
<body>
<div id="ajaxMessage"></div>
<h1>Sales Report</h1>
<table class="filters">
	<tr>
    	<td>
        	<table border="0" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                    	<th colspan="2">Date <em>(default 1 week / 7 days)</em></th>
                    </tr>
                </thead>
                <tr>
                    <td><input type="text" id="fromdate"></td>
                    <td><input type="text" id="todate"></td>
                </tr>
            </table>
        </td>
        <!--<td>
        	<table border="0" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                    	<th>Affiliate</th>
                    </tr>
                </thead>
                <tr>
                    <td>
                    	<select name="affiliates">
                        	<option value="0">All</option>
<?PHP
	/*foreach($affiliatesLst as $arow => $acolumns){
		$affiliates->loadPhotographer($acolumns['affiliatePhotographerID']);
?>
							<option value="<?PHP echo $acolumns['affiliatePhotographerID']?>" <?PHP echo (isset($_REQUEST['affiliate'])&&$_REQUEST['affiliate']==$acolumns['affiliatePhotographerID'])?'selected':''; ?>><?PHP echo preg_replace("/[^A-Za-z ]/", '', $affiliates->fullName);?></option>
<?PHP
	}*/
?>
                        </select>
                    </td>
                </tr>
            </table>
        </td>-->
        <td>
        	<table border="0" cellspacing="0" cellpadding="5">
                <thead>
                    <tr>
                    	<th>State</th>
                    </tr>
                </thead>
                <tr>
                    <td>
                    	<select name="states">
                        	<option value="0">All</option>
<?PHP
	foreach($statesLs as $srow => $scolumns){
		$state = $scolumns['state'];
?>
							<option value="<?PHP echo $state ?>" <?PHP echo (isset($_REQUEST['state'])&&$_REQUEST['state']==$state)?'selected':''; ?>><?PHP echo $state ?></option>
<?PHP
	}
?>
                        </select>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table border="0" cellspacing="0" cellpadding="10" class="list">
	<thead>
  		<th>State</th>
    	<th style="text-align:center !important;">Photo Packages Ordered</th>
    	<th style="text-align:center !important;">Video Packages Ordered</th>
  	</thead>
<?PHP
foreach($states as $srow => $scolumns){
	$state = $scolumns['state'];
	$photoPackageCount = $db->select('tours t', "t.state='".$state."' AND (SELECT COUNT(tourTypeID) FROM tourtypes WHERE tourTypeID=t.tourTypeID AND ( (walkthrus>0 OR motion>0 OR photos>0 OR hdr_photos>0) AND videos<1 ))>0 AND ".$whereD.$whereS, '', 'COUNT(t.tourID) as pcount', 'ORDER BY state ASC');
	$photoPackageCount = $photoPackageCount[0]['pcount'];
	$videoPackageCount = $db->select('tours t', "t.state='".$state."' AND (SELECT COUNT(tourTypeID) FROM tourtypes WHERE tourTypeID=t.tourTypeID AND videos>0)>0 AND ".$whereD.$whereS, '', 'COUNT(t.tourID) as vcount', 'ORDER BY state ASC');
	//echo $db->sql;
	$videoPackageCount = $videoPackageCount[0]['vcount'];
	$stAffiliates = $db->run("SELECT DISTINCT(b.affiliatePhotographerID) FROM tours t, users u, brokerages b WHERE b.affiliatePhotographerID>0 AND u.userID = t.userID AND b.brokerageID = u.brokerageID AND t.state='".$state."' AND ".$whereD);
	$affiliateNames = array();
	$affiliatePTourCount = array();
	$affiliateVTourCount = array();
	foreach($stAffiliates as $starow => $stacolumns){
		$affiliates->loadPhotographer($stacolumns['affiliatePhotographerID']);
		$affiliateNames[] = preg_replace("/[^A-Za-z ]/", '', $affiliates->fullName);
		$affiliatePTourCountT = $db->select('tours t, users u, brokerages b', "b.affiliatePhotographerID='".$stacolumns['affiliatePhotographerID']."' AND u.userID = t.userID AND b.brokerageID = u.brokerageID AND t.state='".$state."' AND (SELECT COUNT(tourTypeID) FROM tourtypes WHERE tourTypeID=t.tourTypeID AND ( (walkthrus>0 OR motion>0 OR photos>0 OR hdr_photos>0) AND videos<1 ))>0 AND ".$whereD.$whereS, '', 'COUNT(t.tourID) as pcount');
		$affiliatePTourCount[] = $affiliatePTourCountT[0]['pcount'];
		$affiliateVTourCountT = $db->select('tours t, users u, brokerages b', "b.affiliatePhotographerID='".$stacolumns['affiliatePhotographerID']."' AND u.userID = t.userID AND b.brokerageID = u.brokerageID AND t.state='".$state."' AND (SELECT COUNT(tourTypeID) FROM tourtypes WHERE tourTypeID=t.tourTypeID AND videos>0)>0 AND ".$whereD.$whereS, '', 'COUNT(t.tourID) as vcount');
		//echo $db->sql;
		$affiliateVTourCount[] = $affiliateVTourCountT[0]['vcount'];
	}
?>
	<tr>
    	<td>
<?PHP
			echo $state;
			echo '<div style="font-weight:normal;padding-left:10px;">';
			foreach($affiliateNames as $afnidx => $afn){
				echo $afn;
				echo '<br/>';
			}
			echo 'Other';
			echo '</div>';
?>
		</td>
        <td align="center">
<?PHP 
			echo $photoPackageCount;
			$otherPCount = intval($photoPackageCount);
			$totalPhoto += intval($photoPackageCount);
			echo '<div style="font-weight:normal;">';
			foreach($affiliatePTourCount as $afptcidx => $afptc){
				$otherPCount -= intval($afptc);
				echo $afptc;
				echo '<br/>';
			}
			echo $otherPCount;
			echo '</div>';
?>
        </td>
        <td align="center">
<?PHP 
			echo $videoPackageCount;
			$otherVCount = intval($videoPackageCount);
			$totalVideo += intval($videoPackageCount);
			echo '<div style="font-weight:normal;">';
			foreach($affiliateVTourCount as $afvtcidx => $afvtc){
				$otherVCount -= intval($afvtc);
				echo $afvtc;
				echo '<br/>';
			}
			echo $otherVCount;
			echo '</div>';
?>
        </td>
    </tr>
<?PHP
}
?>
	<tr>
    	<td>Total</td>
        <td align="center"><?PHP echo $totalPhoto ?></td>
        <td align="center"><?PHP echo $totalVideo ?></td>
    </tr>
</table>
<div class="modal-bg"></div>
<div class="modal">
    <div class="content">
    </div>
</div>
</body>
</html>