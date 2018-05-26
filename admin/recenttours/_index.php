<?php
  /**
    * @author William Merfalen
    * @date 2014-11-10
    * @purpose Porting to PHP
    */
    require '../../repository_inc/classes/inc.global.php';
    error_reporting(E_ALL & ~E_WARNING);
    ini_set('display_errors',1);
    $users = new users;
    $users->authenticateAdmin();
    global $db;
    function formatSched($dt){
        if(preg_match('|([0-9]{4})\-([0-9]{2})\-([0-9]{2})|',
            $dt,
            $matches
        )){
        return preg_replace('|^0|','',$matches[2]) . '/' . 
               preg_replace('|^0|','',$matches[3]) . '/' .
               $matches[1]
        ;
        }else{
            return null;
        }
    }
    
    $debug = 0;
    $rows = (isset($_GET['rows']) ? intval($_GET['rows']) : 100);
    $start = (isset($_GET['start']) ? intval($_GET['start']) : 0);
    $orderBy = (isset($_GET['orderby']) ? $_GET['orderby'] : 'orders.createdOn desc');
    $progOrderBy = (isset($_GET['progorderby']) ? $_GET['progorderby'] : "");
    $tourId = (isset($_GET['tourid']) ? intval($_GET['tourid']) : "");
    $tourAddress = (isset($_GET['touraddress']) ? $_GET['touraddress'] : "");
    $brokerageId = (isset($_GET['brokerageID']) ? $_GET['brokerageID'] : "");
    $startDate = (isset($_GET['startdate']) ? $_GET['startdate'] : "");
    $endDate = (isset($_GET['enddate']) ? $_GET['enddate'] : "");
    $oStartDate = (isset($_GET['ostartdate']) ? $_GET['ostartdate'] : "");
    $oEndDate = (isset($_GET['oenddate']) ? $_GET['oenddate'] : "");
    $videoStartDate = (isset($_GET['videostartdate']) ? $_GET['videostartdate'] : "");
    $videoEndDate = (isset($_GET['videoenddate']) ? $_GET['videoenddate'] : "");
    $st = $tt = "";
    if( isset($_POST['tourTypeID']) ){
        $tt = ' AND tt.tourTypeID = ' . $_POST['tourTypeID'];
    }
    if( isset($_POST['state']) ){
        $st = ' AND tours.state = "' . $_POST['state'] . '"';
    }

    //echo "Start: $start | Rows: $rows<hr>";

    $q = 'select * from brokerages order by BrokerageName';
    $resBrokerages = $db->run($q);

    if( isset($_GET['tourid']) && strlen($_GET['tourid']) ){
        $q= "SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName,
            CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory,
            t.oCreatedOn, t.oID, t.userid,
            tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt,
            tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify,
            tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn,
            tp.VideoReScheduledOn
        FROM (
            SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn,
            tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
            FROM tours
            LEFT JOIN orders ON tours.tourid = orders.tourid
            LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID
            WHERE orders.createdOn IS NOT NULL $tt $st
            AND tours.tourID LIKE '$tourId'
            ORDER BY $orderBy
            LIMIT $start,$rows
        ) as t
        LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
        ";
        if( strlen($progOrderBy) ){
            $q .= " ORDER BY $progOrderBy";
        }
        if( $debug )
        echo "<b>1st IF</b> start $start rows $rows<hr>";

    }elseif( strlen($tourAddress) ){
        //<cfelseif len(url.touraddress) gt 0>
        $q="SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
            CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
            t.oCreatedOn, t.oID, t.userid,
            tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
            tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
            tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
            tp.VideoReScheduledOn
        FROM (
            SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
            tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
            FROM tours
            LEFT JOIN orders ON tours.tourid = orders.tourid
            LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID
            WHERE orders.createdOn IS NOT NULL $tt $st 
            AND tours.address LIKE '$tourAddress'
            ORDER BY $orderBy
            LIMIT $start,$rows
        ) as t
        LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
        ";
        if( strlen($progOrderBy) ){
            $q .= " ORDER BY $progOrderBy ";
        }
        if( $debug )
        echo "<b>2nd IF</b> start $start rows $rows<hr>";
    }elseif( strlen($startDate) && strlen($endDate) ){
        //<cfelseif len(url.startdate) gt 0 and len(url.enddate) gt 0>
        $q = "SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
            CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
            t.oCreatedOn, t.oID, t.userid,
            tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
            tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
            tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
            tp.VideoReScheduledOn
        FROM (
            SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
            tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
            FROM tourtypes tt, tourprogress tp, tours 
            LEFT JOIN orders ON tours.tourID = orders.tourid
            WHERE tours.tourTypeID = tt.tourTypeID $tt $st
            AND tp.tourid = tours.tourid
            AND ((tp.ReScheduledon IS NULL AND tp.Scheduledon BETWEEN date('$startDate') AND DATE_ADD('$endDate',INTERVAL 1 DAY))                 	OR (tp.ReScheduledon BETWEEN date('$startDate') AND DATE_ADD('$endDate',INTERVAL 1 DAY)))
            ORDER BY tp.Scheduledon DESC 
            LIMIT $start,$rows
        ) as t
        LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
        ";
        if( strlen($progOrderBy) ){
            $q .= " ORDER BY $progOrderBy ";
        }
        if( $debug )
        echo "<b>3rd IF</b> start $start rows $rows<hr>";
    }elseif( strlen($oStartDate) && strlen($oEndDate) ){
        //<cfelseif len(url.ostartdate) gt 0 and len(url.oenddate) gt 0>
        $q= "SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
            CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
            t.oCreatedOn, t.oID, t.userid,
            tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
            tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
            tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
            tp.VideoReScheduledOn
        FROM (
            SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
            tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
            FROM tourtypes tt, tourprogress tp, tours 
            LEFT JOIN orders ON tours.tourID = orders.tourid
            WHERE tours.tourTypeID = tt.tourTypeID $tt $st
            AND tp.tourid = tours.tourid
            AND ( orders.createdOn BETWEEN date('$oStartDate') AND DATE_ADD('$oEndDate',INTERVAL 1 DAY) )
            ORDER BY orders.createdOn DESC 
            LIMIT $start,$rows
        ) as t
        LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
        ";
        if( strlen($progOrderBy) ){
            $q .= " ORDER BY $progOrderBy ";
        }
        if( $debug )
        echo "<b>4th IF</b> start $start rows $rows<hr>";
    }elseif( strlen($videoStartDate) && strlen($videoEndDate) ){
        //<cfelseif len(url.videostartdate) gt 0 and len(url.videoenddate) gt 0>
        $q ="SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
            CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
            t.oCreatedOn, t.oID, t.userid,
            tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
            tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
            tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
            tp.VideoReScheduledOn
        FROM (
            SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
            tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
            FROM tourtypes tt, tourprogress tp, tours 
            LEFT JOIN orders ON tours.tourID = orders.tourid
            WHERE tours.tourTypeID = tt.tourTypeID $tt $st
            AND tp.tourid = tours.tourid
            AND ((tp.VideoReScheduledOn IS NULL AND 
                tp.VideoScheduledOn BETWEEN date('$videoStartDate') AND DATE_ADD('$videoEndDate',INTERVAL 1 DAY))
                OR (tp.VideoReScheduledOn BETWEEN date('$videoStartDate') AND DATE_ADD('$videoEndDate',INTERVAL 1 DAY)))
            ORDER BY tp.Scheduledon DESC 
            LIMIT $start,$rows
        ) as t
        LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
        ";
        if( strlen($progOrderBy) ){
            $q .= " ORDER BY $progOrderBy ";
        }
        if( $debug )
        echo "<b>5th IF</b> start $start rows $rows<hr>";
    }elseif( strlen($brokerageId) ){
        //<cfelseif len(url.brokerageID) gt 0>
        $q = "SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
            CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
            t.oCreatedOn, t.oID, t.userid,
            tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
            tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
            tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
            tp.VideoReScheduledOn
        FROM (
            SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
            tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID,
            u.brokerageID
            FROM tours
            LEFT JOIN orders ON tours.tourid = orders.tourid
            LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID
            LEFT JOIN users u ON tours.userID = u.userID
            WHERE orders.createdOn IS NOT NULL $tt $st
            AND u.brokerageID = '$brokerageId'
            ORDER BY $orderBy
            LIMIT $start,$rows
        ) as t
        LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
        ";
        if( strlen($progOrderBy) ){
            $q .= " ORDER BY $progOrderBy ";
        }
        if( $debug )
        echo "<b>6th IF</b> start $start rows $rows<hr>";
    }else{
        $q = "SELECT t.tourid, t.address, t.unitNumber, t.city, t.state, t.createdOn, t.tourTypeName, 
            CASE WHEN t.tourCategory = 'Video Tours' THEN 1 ELSE 0 END AS tourCategory, 
            t.oCreatedOn, t.oID, t.userid,
            tp.ScheduleAttempted, tp.Scheduledon, tp.ReScheduledon, tp.Scheduled, tp.MediaReceived, tp.Edited, tp.TourBuilt, 
            tp.Realtorcom, tp.mls, tp.finalized, tp.ScheduleAttemptednotify, tp.Schedulednotify, tp.finalizednotify, 
            tp.follow_up, tp.VideoMediaReceived, tp.VideoEdited, tp.VideoFinalized, tp.isVideoTour, tp.VideoScheduledOn, 
            tp.VideoReScheduledOn
        FROM (
            SELECT tours.tourid, tours.address, tours.unitNumber, tours.city, tours.state, tours.createdOn, 
            tt.tourTypeName, tt.tourCategory, orders.createdOn as oCreatedOn, orders.userid, orders.orderID as oID
            FROM tours
            LEFT JOIN orders ON tours.tourid = orders.tourid
            LEFT JOIN tourtypes tt ON tours.tourTypeID = tt.tourTypeID 
            WHERE orders.createdOn IS NOT NULL $tt $st
            ORDER BY $orderBy
            LIMIT $start,$rows
        ) as t
        LEFT JOIN tourprogress tp ON t.tourid = tp.tourid
        ";
        if( strlen($progOrderBy) ){
            $q .= " ORDER BY $progOrderBy ";
        }
        if( $debug )
        echo "<b>LAST else</b> start $start rows $rows<hr>";
    }
    if( $debug )
    echo "Running <pre>$q</pre><hr>";
    $resTours = $db->run($q);
    $resTourTypes = $db->run("select tourTypeID,tourTypeName FROM tourtypes");
    $resStates = $db->run("select stateAbbrName from states");
    $range = 0;
    if( strlen($startDate) && strlen($endDate) ){
        $rows = count($resTours);
        $range = 1;
    }
?>
<HTML>
<HEAD>
    <TITLE>Users</TITLE>
    <META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8">
    <LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
	<script src="/javascripts/javascript.js"></script>
<!--    
	<link type="text/css" href="/admin/includes/jquery-ui-1.8.9/css/ui-lightness/jquery-ui-1.8.9.custom.css" rel="stylesheet" />	
	<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="/admin/includes/jquery-ui-1.8.9/js/jquery-ui-1.8.9.custom.min.js"></script> 
-->
<script src="../../repository_inc/jquery-1.7.2.min.js" type="text/javascript"></script><!-- jQuery -->
<script src="../../repository_inc/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script><!-- jQuery Exras -->
<script src="../../repository_inc/template.js" type="text/javascript"></script><!-- Template JS file -->
<script src="../../repository_inc/admin-v2.js" type="text/javascript"></script><!-- Admin JS file -->
<style type="text/css" media="screen">
    @import "../../repository_css/template.css";
    @import "../../repository_css/admin-v2.css";
    @import "../../repository_css/jquery-ui-1.8.16.custom.css";
</style>
    <script type="text/javascript">
	    $(function() {
		    $( "#startdate" ).datepicker();
		    $( "#startdate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#enddate" ).datepicker();
		    $( "#enddate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#ostartdate" ).datepicker();
		    $( "#ostartdate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#oenddate" ).datepicker();
		    $( "#oenddate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		    $( "#videostartdate" ).datepicker();
		    $( "#videostartdate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
			$( "#videoenddate" ).datepicker();
		    $( "#videoenddate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	    });
	</script>
    <SCRIPT TYPE="text/javascript">
        function confirmDelete() {
	        if(!confirm("Are you sure you want to remove this tour?"))
			return false;
		}

		function confirmProcess() {
		    if(!confirm("This process may take several minutes depending on the number of files to process.\nAre you sure you want to continue?"))
			    return false;
		}
		
		function ShowTourType(tourTypeID, startDate, endDate) {
			var form = document.createElement("form");
			form.setAttribute("method", "post");
			form.setAttribute("action", "");
		
			var hiddenField1 = document.createElement("input");
			hiddenField1.setAttribute("type", "hidden");
			hiddenField1.setAttribute("name", "tourTypeID");
			hiddenField1.setAttribute("value", tourTypeID);
			form.appendChild(hiddenField1);
		
			if (startDate.length > 0 && endDate.length > 0) {
				var hiddenField2 = document.createElement("input");
				hiddenField2.setAttribute("type", "hidden");
				hiddenField2.setAttribute("name", "startdate");
				hiddenField2.setAttribute("value", startDate);
				form.appendChild(hiddenField2);
			
				var hiddenField3 = document.createElement("input");
				hiddenField3.setAttribute("type", "hidden");
				hiddenField3.setAttribute("name", "enddate");
				hiddenField3.setAttribute("value", endDate);
				form.appendChild(hiddenField3);
			}
			
			document.body.appendChild(form);
			form.submit();
		}
		
		function ShowState(state, startDate, endDate) {
			//alert(state);
			var form = document.createElement("form");
			form.setAttribute("method", "post");
			form.setAttribute("action", "");
		
			var hiddenField1 = document.createElement("input");
			hiddenField1.setAttribute("type", "hidden");
			hiddenField1.setAttribute("name", "state");
			hiddenField1.setAttribute("value", state);
			form.appendChild(hiddenField1);
		
			if (startDate.length > 0 && endDate.length > 0) {
				var hiddenField2 = document.createElement("input");
				hiddenField2.setAttribute("type", "hidden");
				hiddenField2.setAttribute("name", "startdate");
				hiddenField2.setAttribute("value", startDate);
				form.appendChild(hiddenField2);
			
				var hiddenField3 = document.createElement("input");
				hiddenField3.setAttribute("type", "hidden");
				hiddenField3.setAttribute("name", "enddate");
				hiddenField3.setAttribute("value", endDate);
				form.appendChild(hiddenField3);
			}
		
			document.body.appendChild(form);
			form.submit();
		}

    </SCRIPT>
    <STYLE TYPE="text/css">
        <!--
        .style1 {
	    color: #0000CC;
	    font-weight: bold; 
    	}
        body {
            width: 100%;
        }
	    -->
	</STYLE>
</HEAD>

<BODY>

<div id='jtable'></div>

<H3><?php echo $rows; ?> Most Recent Tours, Starting with Row <?php echo $start + 1; ?></H3>

<TABLE WIDTH="100%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
    <TR>
	    <TD HEIGHT="65" COLSPAN="17" VALIGN="top"><A HREF="proc_media.cfm?initiate=true" onClick="return confirmProcess();"></A>
	        <TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
                <TR>
                    <TD>
		                
		  			</TD>
                    <TD>
		                <FORM ACTION="" METHOD="get">
			                Show Daily Queue (Photo):<BR> 
                            <INPUT NAME="startdate" TYPE="text" ID="startdate" VALUE="<?php echo $startDate; ?>"/>
                            <INPUT NAME="enddate" TYPE="text" ID="enddate" VALUE="<?php echo $endDate; ?>" />            
			                <INPUT TYPE="submit" NAME="GO" ID="GO" VALUE="GO" />
			           	</FORM>
		  			</TD>
                    <TD><FORM ACTION="" METHOD="get">
			                Show Daily Queue (Video):<BR> 
                            <INPUT NAME="videostartdate" TYPE="text" ID="videostartdate" VALUE="<?php echo $videoStartDate; ?>"/>
                            <INPUT NAME="videoenddate" TYPE="text" ID="videoenddate" VALUE="<?php echo $videoEndDate;?>" />            
			                <INPUT TYPE="submit" NAME="GO" ID="GO" VALUE="GO" />
			           	</FORM>
		  			</TD>
                    <TD><FORM ACTION="" METHOD="get">
                    	Search Tour ID<BR>
                    	<INPUT NAME="tourid" TYPE="text" ID="tourid" VALUE="<?php echo $tourId;?>" />
                    	<INPUT TYPE="submit" NAME="GO2" ID="GO2" VALUE="GO">
                    	<BR>
                    	</FORM></TD>
            		<TD>
		  			    <FORM ACTION="" METHOD="get">
		  				Search Tour Address<BR>
            			<INPUT NAME="touraddress" TYPE="text" ID="touraddress" VALUE="<?php echo $tourAddress;?>" />
			            <INPUT TYPE="submit" NAME="GO2" ID="GO2" VALUE="GO">
            			<BR>
						</FORM>
		  			</TD>
          		</TR>
                <TR>
                	<TD colspan="3">
                        <FORM ACTION="" METHOD="get">
                            Search Brokerage<BR>
                            <select name="brokerageID">
                                <option value="">Select One...</option>
    <?php
        foreach($resBrokerages as $index => $brk){
            echo "<option value='$brk[brokerageID]' ";
            if( $brokerageId == $brk['brokerageID'] ){
                echo " selected ";
            }
            echo ">$brk[brokerageName] (desc:$brk[brokerageDesc])</option>";
        }
    ?>
                            </select>
                            <INPUT TYPE="submit" NAME="GO2" ID="GO2" VALUE="GO">
                        </FORM>
                    </TD>
                    <TD colspan="2">
                        <FORM ACTION="" METHOD="get">
			                Show Order Queue (All):<BR> 
                            <INPUT NAME="ostartdate" TYPE="text" ID="ostartdate" VALUE="<?php echo $startDate;?>"/>
                            <INPUT NAME="oenddate" TYPE="text" ID="oenddate" VALUE="<?php echo $endDate;?>" />            
			                <INPUT TYPE="submit" NAME="GO" ID="GO" VALUE="GO" />
			           	</FORM>
                    </TD>
                </TR>
    		</TABLE>
		</TD>
    	<TD COLSPAN="4">
		    <DIV ALIGN="right">
				<select onChange="window.location='?startdate=<?php echo "{$startDate}&enddate={$endDate}&brokerageID={$brokerageId}&orderby={$orderBy}&progorderby={$progOrderBy}&rows='+this.value\""?>>
            <?php
                foreach([50,100,150,200,300,400] as $key => $val){
                    echo "<option value=$val ";
                    if( $rows == $val ){
                        echo " selected=selected ";
                    }
                    echo ">$val</option>";
                }
            ?>
		    </select>
				per page | 
            <?php
echo "<b>Range: $range</b><hr>";
                if( $range != 1 ){
                    if( $start > 0 ){
                        echo "<a href='?start=";
                        echo $start - $rows;
                        echo "&rows=$rows'>Previous Page</a>";
                    }
                    if( count($resTours) == $rows ){
                        echo "<a href='?start=";
                        echo $start + $rows;
                        echo "&rows=$rows'>Next Page</a>";
                    }
                }
            ?>
			</DIV>
		</TD>
  	</TR>
	<tr>
		<td colspan=17>
			<h3>
			    [Results : <?php echo count($resTours);?>]
                <?php
                    foreach($resTourTypes as $index => $val){
                        $ttName = str_replace("'","\'",$val['tourTypeName']);
                        $count = 0;
                        foreach($resTours as $_index => $_val){
                            if( $_val['tourTypeName'] == $val['tourTypeName'] ){
                                $count++;
                            }
                        }
                        if( $count > 0 ){
                            echo "<a style='text-decoration: none;' href='javascript:void(0)' onClick='ShowTourType(\"$val[tourTypeID]\",\"$startDate\",\"$endDate\");'>";
                            echo "[$val[tourTypeName] : $count]";
                            echo "</a>";
                        }
                    }
                    echo "<br><br>States:";
                    foreach($resStates as $key => $value){
                        $count = 0;
                        foreach($resTours as $index => $info){
                            if( $info['state'] == $value['stateAbbrName'] ){
                                $count++;
                            }
                        }
                        if( $count > 0 ){
                            echo "<a style='text-decoration: none;' href='javascript:void(0)' onClick='ShowState(\"$value[stateAbbrName]\",\"$startDate\",\"$endDate\");'>";
                            echo "[$value[stateAbbrName] : $count ]";
                            echo "</a>";

                        }
                    }
                
                ?>
			</h3>
		</td>
	</tr>
  	<TR style="white-space:nowrap; text-align:center">
  	    <TH valign="bottom" WIDTH="4%">
        <?php
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&";
            echo "orderby=tours.tourid";
            if($orderBy == 'tours.tourid'){ 
                echo "%20desc";
            }
            echo "\">TourID</A>";
            echo "</TH>";
    
  		    echo "<TH valign=\"bottom\" WIDTH=\"19%\">";
            echo "<A HREF=\"?startdate=$startDate";
            echo "&enddate=$endDate&brokerageID=$brokerageId;";
            echo "&rows=$rows&orderby=tours.address";
            if( $orderBy == "tours.address" ){ 
                echo "%20desc"; 
            }
            echo "\">Address</A></TH>";
            echo '   
  		<TH valign="bottom" WIDTH="2%">Conc.<BR>Level</TH>
  		<TH valign="bottom" WIDTH="12%">Tour Type</TH>
  		<TH valign="bottom" WIDTH="2%" align="center">A</TH>
  		<TH valign="bottom" WIDTH="5%">';
            echo "<A HREF=\"?startdate=$startDate;&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows";
            echo "&orderby=orders.createdOn";
            if( $orderBy == 'orders.createdOn' ){   
                echo "%20desc\"";
            }
            echo "\">Created</A></TH>";
		    echo "<TH valign=\"bottom\" WIDTH=\"5%\">";
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tp.Scheduledon";
            if( $progOrderBy == "tp.Scheduledon" ){
                echo "%20desc";
            }
            echo "\">Scheduled<BR>On</A></TH>";
            
            echo "<TD valign=\"bottom\" WIDTH=\"4%\" BGCOLOR=\"#C3D9FF\">";
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tp.ScheduleAttempted";
            if( $progOrderBy != "tp.ScheduleAttempted%20desca" ){
                echo "%20desc";
            }
            echo "\">";
            echo "<SPAN CLASS=\"style1\">Schedule<BR>Attempt </SPAN>";
            echo "</A></TD>";
            
            echo "<TD valign=\"bottom\" WIDTH=\"4%\" BGCOLOR=\"#C3D9FF\">";
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate;";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tp.Scheduled";
            if( $progOrderBy != 'tp.Scheduled desc' ){
                echo "%20desc";
            }
            echo "\"><SPAN CLASS=\"style1\">Scheduled</SPAN></A></TD>";
            echo "<TD valign=\"bottom\" WIDTH=\"4%\" BGCOLOR=\"#C3D9FF\"";
            echo "><A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tp.MediaReceived";
            if( $progOrderBy != 'tp.MediaReceived desc' ){
                echo "%20desc";
            }
            echo "\"><SPAN CLASS=\"style1\">Photo<BR>Media<BR>Received";
            echo "</SPAN></A></TD>";
            
            echo "<TD valign=\"bottom\" WIDTH=\"4%\" BGCOLOR=\"#C3D9FF\">";
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tourCategory";
            if( $progOrderBy != 'tourCategory desc, tp.VideoMediaReceived desc'){
                echo "%20desc";
            }
            echo "\"><SPAN CLASS=\"style1\">Video<BR>Media<BR>Received";
            echo "</SPAN></A></TD>";
            
            echo "<TD valign=\"bottom\" WIDTH=\"4%\" BGCOLOR=\"#C3D9FF\">";
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tp.Edited";
            if( $progOrderBy != 'tp.Edited desc' ){
                echo "%20desc";
            }
            echo "\"><SPAN CLASS=\"style1\">Photo<BR>Edited";
            echo "</SPAN></A></TD>";
		    echo "<TD valign=\"bottom\" WIDTH=\"4%\" BGCOLOR=\"#C3D9FF\">";
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tourCategory";
            if( $progOrderBy != 'tourCategory desc, tp.VideoEdited desc' ){
                echo "%20desc";
            }
            echo ", tp.VideoEdited";
            if( $progOrderBy != 'tourCategory desc, tp.VideoEdited desc' ){
                echo "%20desc";
            }
            echo "\"><SPAN CLASS=\"style1\">Video<BR>Edited</SPAN></A></TD>";
		    echo '<TD valign="bottom" WIDTH="4%" BGCOLOR="#C3D9FF">';
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tp.Realtorcom";
            if( $progOrderBy != 'tp.Realtorcom desc' ){
                echo "%20desc";
            }
            echo '"><SPAN CLASS="style1">Realtor<BR>.com </SPAN></A></TD>';
            

		    echo '<TD valign="bottom" WIDTH="4%" BGCOLOR="#C3D9FF">';
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tp.mls";
            if( $progOrderBy != 'tp.mls desc' ){
                echo "%20desc";
            }
            echo "\"><SPAN CLASS=\"style1\">MLS</SPAN></A></TD>";


		    echo '<TD valign="bottom" WIDTH="4%" BGCOLOR="#C3D9FF">';
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tp.finalized";
            if( $progOrderBy != 'tp.finalized desc' ){
                echo "%20desc";
            }
            echo "\"><SPAN CLASS=\"style1\">Photo<BR>Tour<BR>Finalized </SPAN></A></TD>";

            echo '<TD valign="bottom" WIDTH="4%" BGCOLOR="#C3D9FF">';
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tourCategory";
            if( $progOrderBy != 'tourCategory desc, tp.VideoFinalized desc'){
                echo "%20desc";
            }
            echo ", tp.VideoFinalized";
            if( $progOrderBy != "tourCategory desc, tp.VideoFinalized desc"){
                echo "%20desc";
            }
            echo "\"><SPAN CLASS=\"style1\">Video<BR>Tour<BR>Finalized </SPAN></A></TD>";

            
            echo "<TD valign=\"bottom\" WIDTH=\"4%\" BGCOLOR=\"#C3D9FF\">";
            echo "<A HREF=\"?startdate=$startDate&enddate=$endDate";
            echo "&brokerageID=$brokerageId&rows=$rows&orderby=$orderBy";
            echo "&progorderby=tp.follow_up";
            if( $progOrderBy != 'tp.follow_up desc' ){
                echo "%20desc";
            }
            echo "\"><SPAN CLASS=\"style1\">Followed<BR>Up </SPAN></A></TD>";
        ?>
        <TH WIDTH="6%"></TH>
        <TH WIDTH="4%"></TH>
        <TH WIDTH="4%"></TH>
  		<TH valign="bottom" WIDTH="4%">(beta)</TH>
  		<TH WIDTH="10%"></TH>
	</TR>
        <?php 
            $mod = 0;
            foreach($resTours as $index => $tour){
                $resOrderType = $db->run(
                    "SELECT type from orderdetails " . 
                    " WHERE orderID = $tour[oID]"
                );
                if( isset($resOrderType[0]) && $resOrderType[0]['type'] == 'product' ){
                    $additional ='Y';
                }else{
                    $additional ='';
                }
                $resConcierge = $db->run("
                SELECT CASE WHEN ISNULL(ms.membershipType) THEN 'N/A' ELSE SUBSTRING(ms.membershipType,1,1) END as ConciergeLevel 
                FROM users u
                LEFT JOIN members as m ON m.userID = u.userID AND m.active = 1 AND m.typeID IN (4,5,6)
                LEFT JOIN memberships as ms ON ms.id = m.typeID
                WHERE u.userID = $tour[userid] 
                ");
            
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo '">';
            
                if( $tour['createdOn'] < '2009-03-22' ){
                    echo '<a href="javascript:void(0);" ' ;
                    echo 'onClick="openPopup(\'/../../tours/tour.cfm?';
                    echo 'tourid=' . $tour['tourid'] . '\',780,570);">';
                    echo $tour['tourid'] . '</a>'; 
                }else{
                    echo '<a href="javascript:void(0);" ';
                    echo 'onClick="openPopup(\'/../../tours/tour.cfm?';
                    echo 'tourid=' . $tour['tourid'] . '\',980,740);">';
                    echo $tour['tourid'] . '</a>';
                }
            
                //======================================================
                // ADDRESS + UNIT NUMBER
                //======================================================    
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo '">';
                
                echo "<a href='../users/users.cfm?pg=editTour&tour=$tour[tourid]'>$tour[address]";
                if( strlen($tour['unitNumber']) ){
                    echo ", Unit: $tour[unitNumber]";
                    echo "<br>";
                }
                echo "$tour[city], $tour[state]</a></td>";
                

                //===================================
                // Concierge level
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo '">';
                echo "<a href=\"../users/users.cfm?pg=tours&user=" ;
                echo $tour['userid']. "\">";
                echo $resConcierge[0]['ConciergeLevel'] . "</a></td>";
                //===================================
                // Tour Type
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo '">';
                echo $tour['tourTypeName'];
                echo "</td>";

                //===================================
                // Additional
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                echo $additional . "</td>";

                //===================================
                // Created On
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                echo $tour['createdOn'] . "</td>";
                //===================================
                // Scheduled on
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";

                $resTourProgress = $db->run("
                    SELECT * FROM tourprogress
                    WHERE tourid = $tour[tourid]
                ");
//var_dump($tour['tourid']);
//var_dump($resTourProgress);
//var_dump($tour);
                if( strlen($tour['ReScheduledon']) ){
                    echo formatSched($tour['ReScheduledon']);
//var_dump($resTourProgress[0]);
                }else{
                    echo formatSched($tour['Scheduledon']);
                }
                if( isset($resTourProgress[0]) ){
                    if( $resTourProgress[0]['isVideoTour'] == '1' ){
                        echo "<br><font color='blue'>";
                        if( strlen($resTourProgress[0]['VideoReScheduledOn']) ){
                            echo formatSched($resTourProgress[0]['VideoReScheduledOn']);
                        }else{
                            echo formatSched($resTourProgress[0]['VideoScheduledOn']);
                        }
                        echo "</font>";
                    }
                }
                echo "</td>";
                //===================================
                // Scheduled attempt
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['ScheduleAttempted'] == '1' ){
                    if( $resTourProgress[0]['ScheduleAttemptednotify'] == '1' ){
			            echo '<IMG SRC="../images/check_mark.png" ';
                        echo 'TITLE="Scheduled Attempt" >';
                    }else{
			            echo '<IMG SRC="../images/check_mark-no.png" ';
                        echo ' TITLE="Schedule Attempt not emailed" >';
                    }
                }
                echo '</TD>';
                //===================================
                // Scheduled 
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['Scheduled'] == '1' ){
                    if( $resTourProgress[0]['Schedulednotify'] == '1' ){
			            echo '<IMG SRC="../images/check_mark.png" ';
                        echo 'TITLE="Scheduled" >';
                    }else{
                        echo '<IMG SRC="../images/check_mark-no.png" ';
                        echo ' TITLE="Scheduled Not Emailed" >';
                    }
                }
                echo '</TD>';
                //===================================
                // Photo Media Received
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['MediaReceived'] == '1' ){
		            echo '<IMG SRC="../images/check_mark.png" ';
                    echo 'TITLE="Photo Media Received" >';
                }
                echo '</TD>';
                
                //===================================
                // Video Media Received
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['tourCategory'] == '1' ){
                    echo '<IMG SRC="../images/check_mark.png" ';
                    echo 'TITLE="Video Media Received">';
                }else{
                    echo "N/A";
                }
                
                echo "</TD>";

                //===================================
                // Edited
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['Edited'] == '1' ){
		            echo '<IMG SRC="../images/check_mark.png" ';
                    echo 'TITLE="Photo Edited" >';
                }
                echo "</TD>";

                //===================================
                // Video Edited
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['tourCategory'] == '1' ){
                    if( $tour['VideoEdited'] == '1' ){
		    	        echo '<IMG SRC="../images/check_mark.png" ';
                        echo 'TITLE="Video Edited" >';
                    }
                }else{
                    echo "N/A";
                }
                echo "</TD>";
                    
                
                //===================================
                // Realtor Com
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['Realtorcom'] == '1' ){
		            echo '<IMG SRC="../images/check_mark.png" ';
                    echo 'TITLE="Realtor.com" >';
                }
                echo '</TD>';

                //===================================
                // MLS
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['mls'] == '1' ){
		            echo '<IMG SRC="../images/check_mark.png" ';
                    echo ' TITLE="MLS" >';
                }
                echo '</TD>';


                //===================================
                // Photo Tour Finalized
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['finalizednotify'] == '1' ){
		            echo '<IMG SRC="../images/check_mark.png" ';
                    echo 'TITLE="Photo Tour Finalized" >';
                }else{
                    /*
		            echo '<IMG SRC="../images/check_mark-no.png" ';
                    echo 'TITLE="Photo Tour Finalized not emailed" >';
                    */
                }
                echo "</TD>";
                //===================================
                // Video finalized
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['tourCategory'] == '1' ){
                    if( $tour['VideoMediaReceived'] == '1' ){
                        echo '<IMG SRC="../images/check_mark.png" ';
                        echo 'TITLE="Video Finalized" >';
                    }
                }else{
                    echo "N/A";
                }
                echo "</TD>";    

                //===================================
                // Follow Up
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                if( $tour['follow_up'] == '1' ){
			        echo '<IMG SRC="../images/check_mark.png" ';
                    echo 'TITLE="Followed Up" >';
                } 

                //===================================
                // Slideshows
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                echo '<A HREF="../users/users.cfm?pg=slideshows';
                echo '&tourid=' . $tour['tourid'] . '">';
                echo 'slideshows</A>';
                echo "</TD>";

                //===================================
                // Floorplans
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
	            echo '<A HREF="http://www.spotlighthometours.com/';
                echo 'admin/floorplans/index.php?tourID=';
                echo $tour['tourid'] . '">floorplans</A>';
                echo '</TD>';
                

                //===================================
                // Media
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
	            echo '<A HREF="../users/users.cfm?pg=media&tour=';
                echo $tour['tourid'];
                echo '">media</A>';
                echo '</TD>'; 

                //===================================
                // Reorder
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                echo '<A HREF="../users/users.cfm?pg=reorder&';
                echo 'tour=' . $tour['tourid'] . '">reorder</A>';
                echo '</TD>';

                //===================================
                // Tour Sheet
                //===================================
                echo '<TD BGCOLOR="';
                if( ($mod % 2) == 0 ){
                    echo "E8EEF7";
                }else{
                    echo "ffffff";
                }
                echo "\">";
                echo '<A  href="../users/users.cfm?pg=toursheet&';
                echo 'tour=' . $tour['tourid'] . '&user=';
                echo $tour['userid'] . '">Tour Sheet</A>';
                echo "</TD>";
    ?>

  </TR>
    <?php
        //=========================================================
        // END FOREACH RES TOURS
        //=========================================================

                $mod++;
        }//end foreach res tours
    ?>
  <TR>
    <TD COLSPAN="12" ></TD>
    <TD COLSPAN="4">
	    <DIV ALIGN="right">
            <?php
                if( $range != 1 ){
                    if( $start > 0 ){
		                echo '<A HREF="?start=' . ($start - $rows); 
                        echo '&rows=' . $rows . '">Previous Page</A>';
                    }
                    if( count($resTours) == $rows ){
			            echo '<A HREF="?start=' . ($start + $rows); 
                        echo '&rows=' . $rows . '">Next Page</A>';
                    }
                }
            ?>
		</DIV>
	</TD>
  </TR>
</TABLE>
</BODY>
</HTML>
