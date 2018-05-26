<?php
    /* @author William Merfalen */
    /* @date 2015-07-13 */

    require '../../repository_inc/classes/inc.global.php';
	$allowed = array(30,2,26,36);
	if( !in_array($_SESSION['admin_id'],$allowed) ){
		die("So like, you're not allowed to see this page n stuff :'(. Naww meeeeen?!?");
	}
    
	//clearCache();
	
	// Create instances of needed objects
	$editors = new editors();
	$tours = new tours($db);
	$users = new users($db);
	
	// Require admin
	$users->authenticateAdmin();
    $whichEdit = 'edit';
    function doEditedStart($row){
        global $whichEdit;
        $editStart = $row['editStart'];
        $reStart = $row['reEditStart'];
        if( $row['is_edit'] == '1' ){
            return $editStart;
        }else{
            $whichEdit = 're';
            return $reStart;
        }
        
    }
    function doEditedEnd($row){
        global $whichEdit;
        if( $whichEdit == 'edit' ){
            return $row['editEnd'];
        }else{
            return $row['reEditEnd'];
        }
    }

    function dateDiff($start,$end){
        $datetime1 = new DateTime($start);
        $datetime2 = new DateTime($end);
        $interval = $datetime1->diff($datetime2);
        return $interval->format("%hh %imin");
    }

    function calcTime($row){
        global $whichEdit;
        if( $whichEdit == 'edit' ){
            return dateDiff($row['editStart'],$row['editEnd']);
        }else{
            return dateDiff($row['reEditStart'],$row['reEditEnd']);
        }
    }

    if( isset($_POST['draw']) ){
        if( isset($_POST['order'][0]['column']) ){
            $sortDir = $_POST['order'][0]['dir'];
            $orderBy = $_POST['order'][0]['column'];
        }else{
            $sortDir = ""; 
            $orderBy = "";
        }
        $start = $_POST['start'];
        $length = $_POST['length'];

        $where = false;
        if( isset($_POST['columns'][5]['search']['value']) && strlen($_POST['columns'][5]['search']['value']) &&
            isset($_POST['columns'][6]['search']['value']) && strlen($_POST['columns'][6]['search']['value'])){
            if( $_POST['columns'][5]['search']['value'] == $_POST['columns'][6]['search']['value']){
                $if = " IF(tp.edited_start BETWEEN '" .  $_POST['columns'][5]['search']['value'] . " 00:00:00' AND '" . 
                     $_POST['columns'][5]['search']['value'] . " 23:59:59',1,0) as is_edit, ";
                $if2 = " IF(tp.ReEditedStart BETWEEN '" .  $_POST['columns'][5]['search']['value'] . " 00:00:00' AND '" . 
                     $_POST['columns'][5]['search']['value'] . " 23:59:59',1,0) as is_reedit, ";
            }else{
                $if = " IF(tp.edited_start BETWEEN '" .  $_POST['columns'][5]['search']['value'] . " 00:00:00' AND '" . 
                     $_POST['columns'][6]['search']['value'] . " 23:59:59',1,0) as is_edit, ";
                $if2 = " IF(tp.ReEditedStart BETWEEN '" .  $_POST['columns'][5]['search']['value'] . " 00:00:00' AND '" . 
                     $_POST['columns'][6]['search']['value'] . " 23:59:59',1,0) as is_reedit, ";
            }
        }
        $mainPrefix  = "SELECT SQL_CALC_FOUND_ROWS " . 
                       " $if  $if2 " . 
                       " t.tourID as tourId, e.fullName as fullName, tt.tourTypeName as name,tt.unitprice as price," .
                       "tt.photos as photos, tt.hdr_photos as hdr,tp.edited_start as editStart, tp.editedOn as editEnd, " . 
                       "tp.ReEditedStart as reEditStart, tp.ReEditedOn as reEditEnd, tp.EditRePhotographer as rephotographer " .
                       " FROM tours t "
        ;
        
        $query = "
            INNER JOIN tourtypes tt ON tt.tourTypeID = t.tourTypeID
            INNER JOIN tourprogress tp ON tp.tourid = t.tourID
            INNER JOIN editors e ON e.id = tp.editphotographer
        ";
        $cols = array(
            't.tourID' => 1,
            'tt.tourTypeName' => 1,
            'e.fullName' => 1,
            'tt.photos' => 1,
            'tt.hdr_photos' => 1,
            'tp.edited_start'=>1,
            'tp.editedOn' => 1,
            'tp.ReEditedStart' => 1,
            'tp.ReEditedOn' => 1
        );
        

        if( isset($_POST['columns'][5]['search']['value']) && strlen($_POST['columns'][5]['search']['value']) &&
            isset($_POST['columns'][6]['search']['value']) && strlen($_POST['columns'][6]['search']['value'])){
            if( $_POST['columns'][5]['search']['value'] == $_POST['columns'][6]['search']['value']){
                $query .= " WHERE ((tp.edited_start BETWEEN '" . $_POST['columns'][5]['search']['value'] . " 00:00:00' ";
                $query .= " AND '" . $_POST['columns'][5]['search']['value'] . " 23:59:59') OR ";
                $query .= " (tp.ReEditedStart BETWEEN '" . $_POST['columns'][5]['search']['value'] . " 00:00:00' ";
                $query .= " AND '" . $_POST['columns'][5]['search']['value'] . " 23:59:59')) ";
            }else{
                $query .= " WHERE ((tp.edited_start BETWEEN '" . $_POST['columns'][5]['search']['value'] . " 00:00:00' ";
                $query .= " AND '" . $_POST['columns'][6]['search']['value'] . " 23:59:59') OR ";
                $query .= " (tp.ReEditedStart BETWEEN '" . $_POST['columns'][5]['search']['value'] . " 00:00:00' ";
                $query .= " AND '" . $_POST['columns'][6]['search']['value'] . " 23:59:59')) ";
            }
            $where = true;
        }

        if( isset($_POST['columns'][2]['search']['value']) && strlen($_POST['columns'][2]['search']['value']) && $_POST['columns'][2]['search']['value'] != '--All--'){
            if( $_POST['columns'][2]['search']['value'] == '--All--' ){ 
                break;
            }else{
                if( $where ){
                    $query .= " AND (e.id=" . $_POST['columns'][2]['search']['value'] . "  ";
                    $query .= " OR tp.EditRePhotographer="  .  $_POST['columns'][2]['search']['value'] . ") ";
                }else{
                    $query .= " WHERE (e.id=" . $_POST['columns'][2]['search']['value'] . " ";
                    $query .= " OR tp.EditRePhotographer="  .  $_POST['columns'][2]['search']['value'] . ") ";
                }
                $where = true;
            }
        }



        $order = false;
        foreach($_POST['order'] as $index => $row){
            if( $cols[ array_keys($cols)[$row['column']] ] == 1 ){
                if( $order ){
                    $query .= " , " . array_keys($cols)[$row['column']] . " " . strtoupper($row['dir']);
                }else{
                    $query .= " ORDER BY " . array_keys($cols)[$row['column']] . " " . strtoupper($row['dir']). " ";
                    $order = true;
                }
            }
        }
        
        $query .= " LIMIT $start,$length ";
        $output = array();
        $final = array();
        $first = true;
        file_put_contents("queries",date("Y-m-d H:i:s") . "::$mainPrefix $query\n",FILE_APPEND);
        $res = $db->run($mainPrefix . " " . $query);
        $count = $db->run("SELECT FOUND_ROWS() cnt");
        $count = $count[0]['cnt'];
        $ctr = 0;
        foreach($res as $index => $row){
            if( $row['is_reedit'] == '1' && $row['rephotographer'] !=  $_POST['columns'][2]['search']['value']){
                continue;
            }
            $output = array();
            $output[] = $row['tourId'];
            $output[] = $row['name'];
            $output[] = $row['fullName'];
            $output[] = $row['photos'];
            $output[] = $row['hdr'];
            $output[] = doEditedStart($row);
            $output[] = doEditedEnd($row);
            $output[] = $row['rephotographer'] == $_POST['columns'][2]['search']['value'] ? 'Yes' : 'No';
            $output[] = calcTime($row);
            $final['aaData'][] = $output;
            if( $first ){
                $final['iTotalRecords'] = $length; 
                $final['iTotalDisplayRecords'] = $count; 
                $first = false;
            }
            $ctr++;
        }
        if( $count ){
            echo json_encode($final);
        }else{
            echo json_encode(array('iTotalRecords'=> 0,'iTotalDisplayRecords'=>0,'aaData'=> array()));
        }
        die;
    }
    
?>
<HTML>
	<HEAD>
	<TITLE>Editor Report</TITLE>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src='http://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js'></script>

	<LINK HREF="/admin/includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
    <LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <LINK HREF="http://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" REL="stylesheet" TYPE="text/css">
    <link rel="stylesheet" href="/resources/demos/style.css">
<style type='text/css'>
.myTable th{
    padding: 2px !important;
}
.myTable tr:nth-child(even) {background: #CCC}
.myTable tr:nth-child(odd) {background: #FFF}
.myTable td{
    border-top: 1px solid black;
    border-left: 1px solid black;
    padding: 0px !important;
    margin: 0px !important;
    font-size: 13px;
}
.oldLastTour { 
    color: red;
}
</style>
<script>
$(document).ready(function(){
    table = $('#myTable').DataTable({
        "serverSide": true,
        "ajax": {
            'url': "/admin/editors/report-new.php",
            'type': 'POST'
        },
        "iDisplayLength": 50,
    }
    );
    table.order([0,'desc']).draw();
    // #column3_search is a <input type="text"> element
    $("#editors").on('change',function(){
        table
        .columns( 2 )
        .search( $(this).val() )
        .columns( 5 )
        .search( $("#startdate").val() )
        .columns( 6 )
        .search( $("#enddate").val() )
        .draw();

    });
});

function dateRange(){
    table
        .columns( 5 )
        .search( $("#startdate").val() )
        .columns( 6 )
        .search( $("#enddate").val() )
        .columns( 2 )
        .search( $("#editors option:selected").val() )
        .draw();

}
</script>
	<script type="text/javascript">
	    $(function() {
		    $( "#startdate" ).datepicker();
		    $( "#startdate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
		    $( "#enddate" ).datepicker();
		    $( "#enddate" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	    });
	</script>
</head>
<body>
<FORM ACTION="" METHOD="get">
		Date Range*:<BR>
		<INPUT NAME="startdate" TYPE="text" ID="startdate" VALUE="<?PHP echo $_REQUEST['startdate'] ?>"/>
		<INPUT NAME="startdate" TYPE="text" ID="enddate" VALUE="<?PHP echo $_REQUEST['enddate'] ?>"/>
		<INPUT TYPE="button" NAME="GO" ID="GO" VALUE="GO" onClick='dateRange()'/>
</form>

<select id='editors'>
    <option>--All--</option>
    <?php
        $res = $db->run("SELECT * FROM editors WHERE video != 1 ORDER BY fullName ASC");
        foreach($res as $index => $row){
            echo "<option ";
            echo "value='" . $row['id'] . "' ";
            echo ">" . $row['fullName'] . "</option>";
        }
    ?>
</select>

<table class='myTable' id='myTable'>
    <thead>
    <tr>
    <th>Tour ID</th>
    <th>TourType</th>
    <th>Editor</th>
    <th>Photos</th>
    <th>HDR Photos</th>
    <th>Edit Start</th>  
    <th>Edit End</th>
    <th>Re-Edit</th>
    <th>Edit Time</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
        </td>
    </tr>
    </tbodY>
</table>




</body></html>
