<?php
    require(dirname(__FILE__) . '/../../repository_inc/classes/inc.global.php');
    $users = new users;
    $users->authenticateAdmin();
    global $db;
    $cols = array(
        "userID" => 1,
        "firstName" => 1,
        "username" => 1,
        "BrokerageID" => 1,
        "lonewolfAgent" => 1,
        "diy" => 1,
        "preview" => 1,
        "socialHub" => 1,
        "concierge" => 1,
        "city" => 1,
        "state" => 1,
        "dateCreated" => 1,
        "tourCount" => 1,
        "lastTour" => 1
    );
    function getBrokerageName($id,$uid){
        global $db;
        $res = $db->select("brokerages","brokerageID=$id");

        $b = $res[0]['brokerageName'];
        if( $b == 'Other' ){
            $res = $db->select("users","userID=$uid");   
            return $res[0]['otherBrokerage'];
        }else{
            return $b;
        }
    }
    function getDIY($uid){
        global $db;
        $query = "SELECT id FROM members WHERE typeID='1' AND active='1' AND userID='$uid'";
        if( count($db->run($query)) ){
            return "yes";
        }else{
            return "no";
        }
    }
    function getPreview($uid){
        global $db;
        $query = "SELECT id FROM members WHERE typeID='2' AND active='1' AND userID='$uid'";
        if( count($db->run($query)) ){
            return "yes";
        }else{
            return "no";
        }
    }
    function getSocial($uid){
        global $db;
        $query = "SELECT id FROM members WHERE typeID='3' AND active='1' AND userID='$uid'";
        if( count($db->run($query)) ){
            return "yes";
        }else{
            return "no";
        }
    }
    function getConcierge($uid){
        global $db;
        $members = new members(CONCIERGE_MEMBERSHIP_ID, 'user', $uid);
        if( $members->active() ){
            return "yes";
        }else{
            return "no";
        }
    }
    function getTourOrderCount($uid){
        global $db;
        $res = $db->select("tours","userID=$uid");
        return count($res);
    }
    function getLastTourOrderedDate($uid){
        global $db;
        $res = $db->run(
            "SELECT * FROM tours WHERE userID=$uid
            ORDER BY createdOn DESC LIMIT 1"
        );
        $a = $res[0]['createdOn'];
        if( strlen($a) == 0 ){
            return "0000-00-00 00:00:00";
        }else{
            return $a;
        }
    }
    function lastTour($t){
        if( strlen($t) ){
            $time = strtotime($t);
            $time2 = strtotime("-3 month",time());
            if( $time2 >= $time ){
                return "<b class='oldLastTour'>$t</b>";
            }else{
                return $t;
            }
        }else{
            return "never";
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

        $query = "SELECT SQL_CALC_FOUND_ROWS userID, CONCAT(lastName,', ',firstName) as firstName, username, u.brokerageID, brokerageName, otherBrokerage, brokerageDesc, dateCreated, lonewolfAgent, u.city,u.state, ";
        $query .= " (SELECT count(*) FROM tours WHERE userID=u.userID) as tourCount, ";
        $query .= " (SELECT createdOn FROM tours WHERE userID=u.userID ORDER BY createdOn DESC LIMIT 1) as lastTour, ";
        $query .= " (SELECT count(*) from members WHERE userType='user' AND userID=u.userID AND active=1 AND typeID=11 ) as concierge, ";
        $query .= " (SELECT count(*) from members WHERE userType='user' AND userID=u.userID AND active=1 AND typeID=3 ) as socialHub, ";
        $query .= " (SELECT count(*) from members WHERE userType='user' AND userID=u.userID AND active=1 AND typeID=2 ) as preview, ";
        $query .= " (SELECT count(*) from members WHERE userType='user' AND userID=u.userID AND active=1 AND typeID=1 ) as diy ";
        $query .= " FROM users u LEFT OUTER JOIN brokerages b ON u.brokerageID = b.brokerageID " ;
        //$query .= " INNER JOIN tours t ON t.userID = u.userID ";

        $where = false;
        
        if( isset($_POST['columns'][3]['search']['value']) && strlen($_POST['columns'][3]['search']['value'])){
            $brokerageId = intval($_POST['columns'][3]['search']['value']);
            $query .= " WHERE b.brokerageID = $brokerageId ";
            $where = true;
        }

        if( isset($_POST['columns'][2]['search']['value']) && strlen($_POST['columns'][2]['search']['value']) && isset($_GET['temp']) ){
            $email = $_POST['columns'][2]['search']['value'];
			$query .= " WHERE u.username = '$email' ";
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

        if( isset($_POST['columns'][9]['search']['value']) && strlen($_POST['columns'][9]['search']['value']) ){
            if( !$where ){
                $query .= " WHERE u.city LIKE '%" . $_POST['columns'][9]['search']['value'] . "%' ";
            }else{
                $query .= " AND u.city LIKE '%" . $_POST['columns'][9]['search']['value'] . "%' ";
            }
            $where = true;
        }

        if( isset($_POST['columns'][10]['search']['value']) && strlen($_POST['columns'][10]['search']['value']) ){
            if( !$where ){
                $query .= " WHERE u.state = '" . $_POST['columns'][10]['search']['value'] . "' ";
            }else{
                $query .= " AND u.state = '" . $_POST['columns'][10]['search']['value'] . "' ";
            }
            $where = true;
            
        }
        if( isset($_POST['columns'][0]['search']['value']) && strlen($_POST['columns'][0]['search']['value']) ){
            if( !$where ){
                $query .= " WHERE u.userID =" . $_POST['columns'][0]['search']['value'];
            }else{
                $query .= " AND u.userID =" . $_POST['columns'][0]['search']['value'];
            }
            $where = true;
        }

        if( isset($_POST['columns'][1]['search']['value']) && strlen($_POST['columns'][1]['search']['value']) ){
            if( !$where ){
                $query .= " WHERE (u.firstname LIKE '%" . $_POST['columns'][1]['search']['value'] . "%' ";
            }else{
                $query .= " AND (u.firstname LIKE '%" . $_POST['columns'][1]['search']['value'] . "%' ";
            }
            $query .= " OR u.lastname LIKE '%" . $_POST['columns'][1]['search']['value'] . "%'";
            $query .= " OR CONCAT(u.firstname,' ',u.lastname) LIKE '%" . $_POST['columns'][1]['search']['value'] . "%' ";
            $query .= " OR CONCAT(u.lastname,' ',u.firstname) LIKE '%" . $_POST['columns'][1]['search']['value'] . "%' ";
            $query .= " )  ";
            $where = true;
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
        file_put_contents("queries",date("Y-m-d H:i:s") . "::$query\n",FILE_APPEND);
        $r = $db->run($query);
        $a = $db->run("SELECT FOUND_ROWS() as cnt");
        foreach($r as $index => $row){
            $output = array();
                $output[] = $row['userID'];
                $output[] = "<a href='/admin/users/users.cfm?pg=editUser&user=" . $row['userID'] . "'>" . $row['firstName'] . "</a>";
                $output[] = $row['username'];
                $output[] = getBrokerageName( $row['brokerageID'], $row['userID'] );
                $output[] = $row['lonewolfAgent'];  // == 0 ? "no" : "yes" );
                $output[] = $row['diy'];//getDIY($row['userID']);
                $output[] = $row['preview'];    //getPreview($row['userID']); 
                $output[] = $row['socialHub'];  //getSocial($row['userID']);
                $output[] = $row['concierge'];  //getConcierge($row['userID']);
                $output[] = $row['city'];
                $output[] = $row['state'];
                $output[] = $row['dateCreated'];
                $output[] = $row['tourCount'];  //getTourOrderCount($row['userID']);
                $output[] = lastTour($row['lastTour']);   //getLastTourOrderedDate($row['userID']); //"last tour ordered date";
                $output[] = "<a href=\"../../users/auto-login.php?username=" . $row['username'] . "\">login</A>";
                $output[] = "<A HREF=\"/admin/users/users.cfm?pg=tours&user=" . $row['userID'] . "\">tours</A>";
                $output[] = "<A onClick=\"return confirmDelete(this);\" data-uid='" . $row['userID'] . "'>delete</A>";
                $output[] = "<A href=\"/admin/admin_invoice.php?id=" . $row['userID'] . "\">invoice</A>";
            $final['aaData'][] = $output;
            if( $first ){
                $final['iTotalDisplayRecords'] = $a[0]['cnt'];
                $final['iTotalRecords'] = $length; 
                $first = false;
            }
        }
        if( count($final) == 0 ){
            echo json_encode(array(
                'aaData' => array(),
                'iTotalDisplayRecords' => 0,
                'iTotalRecords' => 0
            ));
        }else{
            echo json_encode($final);
        }
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
<?php
	if( isset($_GET['email']) ){
		echo 'var email = "' . $_GET['email'] . '";';
	}
?>
$(document).ready(function(){
	$("#nameSearch").trigger("focus");
    table = $('#myTable').DataTable({
			"serverSide": true,
			"ajax": {
				'url': "/admin/users/users.php",
				'type': 'POST'
			},
			"iDisplayLength": 50
		}
	);
    if( typeof email != 'undefined' ){
        tempTable = $('#tempTable').DataTable({
			"serverSide": true,
			"ajax": {
				'url': "/admin/users/users.php?temp=1",
				'type': 'POST'
			},
			"iDisplayLength": 1
			}
		);
		tempTable
        .columns( 2 )
        .search( email )
        .draw();
    }
    table.order([0,'desc']).draw();
    // #column3_search is a <input type="text"> element
    $('#nameSearch').on( 'keyup', function () {
    table
        .columns( 1 )
        .search( this.value )
        .draw();
    } );
    $('#stateSearch').on( 'keyup', function () {
    table
        .columns( 10 )
        .search( this.value )
        .columns( 9 )
        .search( $("#citySearch").val() )
        .draw();
    } );
    $('#uidSearch').on( 'keyup', function () {
    table
        .columns( 0 )
        .search( this.value )
        .draw();
    } );
    $('#citySearch').on( 'keyup', function () {
    table
        .columns( 9 )
        .search( this.value )
        .draw();
    } );
    $('#brokerages').on( 'change', function () {
        table
            .columns( 3 )
            .search( this.value )
            .draw();
    } );
    $("#dateTourCount").on('click',function(){
        table.order([11,'desc'],[12,'desc']).draw();
    });
});
function deleteUser(uid){
    $.ajax({
        'url': '/admin/users/users.cfm?action=deleteUser&user=' + uid
    }).done(function(msg){
        location.reload();
    });
}
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

<TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">
<TR>
<TD>Filter by Name :</TD>
<TD><INPUT NAME="name" TYPE="text" id="nameSearch" value=""></TD>
</TR>
<TR>
<TD WIDTH="10%">Filter by User ID:</TD>
<TD WIDTH="90%"><INPUT NAME="userid" id='uidSearch' TYPE="text" value=""></TD>
</TR>
<TR>
<TD WIDTH="10%">Filter by City:</TD>
<TD WIDTH="90%"><INPUT NAME="userid" id='citySearch' TYPE="text" value=""></TD>
</TR>
<TR>
<TR>
<TD WIDTH="10%">Filter by State:</TD>
<TD WIDTH="90%"><INPUT NAME="userid" id='stateSearch' TYPE="text" value=""></TD>
</TR>
<TR>
<TD>Filter by Brokerage :</TD>
<TD><SELECT NAME="BrokerageID" id='brokerages'>
          <OPTION VALUE=""  selected>Select One...</OPTION>
        <?php
            foreach($db->run("SELECT brokerageID,CONCAT(brokerageName,' -- ',brokerageDesc) as brokerageName FROM brokerages ORDER BY brokerageName ASC") as $index => $row){
                if( strlen($row['brokerageName']) ){
                    echo "<option value='" . $row['brokerageID'] . "'>" . $row['brokerageName'] . "</option>";
                }
            }
        ?>
    </select>
</td>
</tr>
</table>

</form>

<input type='button' id='dateTourCount' value='just joined tour count'>
<?php 
	if( isset($_GET['email']) ): 
?>
<table class='myTable' id='tempTable'>
    <thead>
    <tr>
    <th>UserID</th>
    <th>Name</th>
    <th>Username</th>
    <th>Brokerage</th>
    <th>LW</th>  
    <th>DIY</th>     
    <th>Preview</th>
    <th>Social Hub</th>
    <th>Concierge Level</th>
    <th>City</th>
    <th>State</th>
    <th>Joined</th>
    <th>Tours</th>
    <th>Last Tour</th>
    <th>login</th>
    <th>tours</th>
    <th>delete</th>
    <th>invoice</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
        </td>
    </tr>
    </tbodY>
</table>

<?php 
	endif;
?>
<table class='myTable' id='myTable'>
    <thead>
    <tr>
    <th>UserID</th>
    <th>Name</th>
    <th>Username</th>
    <th>Brokerage</th>
    <th>LW</th>  
    <th>DIY</th>     
    <th>Preview</th>
    <th>Social Hub</th>
    <th>Concierge Level</th>
    <th>City</th>
    <th>State</th>
    <th>Joined</th>
    <th>Tours</th>
    <th>Last Tour</th>
    <th>login</th>
    <th>tours</th>
    <th>delete</th>
    <th>invoice</th>
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
