<?php
    require(dirname(__FILE__) . '/../repository_inc/classes/inc.global.php');
    $users = new users;
    $users->authenticateAdmin();
    global $db;
    $cols = array(
        "id" => 1,
        "domainName" => 1,
        "tourId" => 1,
        "active" => 1,
        "createdDate" => 1,
        "processed" => 1,
        "propertyUrl" => 1
    );
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

        $query .= "SELECT * FROM domains";
        //$query .= " INNER JOIN tours t ON t.userID = u.userID ";

        $where = false;
        if( isset($_POST['columns'][1]['search']['value']) && strlen($_POST['columns'][1]['search']['value'])){
            $query .= " WHERE domainName LIKE '%" . $_POST['columns'][1]['search']['value'] . "%'";
            $where = true;
        }


        /*
        if( isset($_POST['columns'][11]['search']['value']) && strlen($_POST['columns'][11]['search']['value'])){
            if( !$where ){
                $query .= " WHERE lastTourLength >= 3 ";
            }else{
                $query .= " AND lastTourLength >= 3 ";
            } 
            $where = true;
        }
        */

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
        file_put_contents("queries",date("Y-m-d H:i:s") . "::$query\n",FILE_APPEND);
        foreach($db->run($query) as $index => $row){
            $output = array();
                $output[] = $row['id'];
                $output[] = "<a href='" . $row['domainName'] . "'>" . $row['domainName'] . "</a>";
                $output[] = $row['tourId'];
                $output[] = $row['active'];
                $output[] = $row['createdDate'];
                $output[] = $row['processed'];
                $output[] = $row['propertyUrl'];
            $final['aaData'][] = $output;
            if( $first ){
                $final['iTotalRecords'] = $length; 
                $res2 = $db->run("SELECT COUNT(domainName) as cnt FROM domains");
                $final['iTotalDisplayRecords'] = $res2[0]['cnt'];
                $first = false;
            }
        }
        echo json_encode($final);
        die;
    }
    
?>

<html>
<head>
<LINK HREF="../includes/admin_styles.css" REL="stylesheet" TYPE="text/css">
<LINK HREF="http://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" REL="stylesheet" TYPE="text/css">
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
<script src='https://code.jquery.com/jquery-2.1.4.min.js'></script>
<script src='http://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js'></script>
<script>
$(document).ready(function(){
    table = $('#myTable').DataTable({
        "serverSide": true,
        "ajax": {
            'url': "/admin/domains.php",
            'type': 'POST'
        },
        "iDisplayLength": 50
    }
    );
    table.order([0,'desc']).draw();
    // #column3_search is a <input type="text"> element
    $('#nameSearch').on( 'keyup', function () {
    table
        .columns( 1 )
        .search( this.value )
        .draw();
    } );
});
function confirmDelete(obj){
    uid = $(obj).data("uid");
    if( confirm("Are you sure you want to delete this user?") ){
        deleteUser(uid);
    }else{

    }
}
</script>
</head>
<body>
<form id='searchFrm' method=POST>

<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">
<TR>
<TD>Filter by Name :</TD>
<TD><INPUT NAME="name" TYPE="text" id="nameSearch" value=""></TD>
</tr>
</table>

</form>

<table class='myTable' id='myTable'>
    <thead>
    <tr>
    <th>ID</th>
    <th>Domain Name</th>
    <th>TourID</th>
    <th>Active</th>
    <th>Created Date</th>
    <th>Processed</th>
    <th>Property URL</th>
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
