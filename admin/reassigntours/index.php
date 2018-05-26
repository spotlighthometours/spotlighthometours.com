<?php
    require '../../repository_inc/classes/inc.global.php';
    $users = new users;
    $users->authenticateAdmin(true);
    $query = "SELECT userID, CONCAT(Firstname, ' ', LastName, ' (', userID, ')') AS Fullname FROM users u
WHERE userType = 'Agent'
ORDER BY Fullname";
    global $db;
    if( isset($_GET['mode']) && $_GET['mode'] == 'ajax' ){
        if( strstr($_GET['query']," ") ){
            $res = $db->run("
                SELECT userID,Firstname,LastName FROM users WHERE CONCAT(Firstname, ' ',LastName) LIKE '%" . $_GET['query'] . "%'
            ");
        }else{
            $res = $db->run("
                SELECT userID,Firstname,LastName FROM users WHERE LastName LIKE '%" . $_GET['query'] . "%'
            ");
        }
        if(count($res)){
            $data = array();
            foreach($res as $index => $row){
                $data[] = $row['Firstname'] . " " . $row['LastName'] . "::" . $row['userID'];
            }
            die(json_encode(array('status'=>'ok','data'=>$data)));
        }
        die(json_encode(array('status'=>'error','data'=>null)));
    }
    if( isset($_POST['submit']) ){
        if( strlen(intval($_POST['tourId'])) == 0 ){
            die("Invalid tour ID");
        }
        //Break apart the data
        $a = explode("::",$_POST['users']);
        $userId = $a[1];
        if( strlen($userId) == 0 ){
            die("Invalid user id");
        }
        $tourId = intval($_POST['tourId']);
        //update the tours
        $db->update("tours",array('userID' => $userId),"tourID=$tourId");
        //update the orders
        $db->update("orders",array('userID'=>$userId),"tourID=$tourId");
        echo "<B>Updated</B>";
    }
?>
<html>
<head>
<title>Reassign Tours</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="/admin/includes/admin_styles.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script>

$(document).ready(function(){
    $("#users").autocomplete({
        source: function (request, response) {
            $.get("index.php", {
                query: request.term,
                mode: "ajax"
            }, function (data) {
                a = $.parseJSON(data);
                console.log(a);
                response(a.data);
            });
        },
        minLength: 3
    });

});
</script>
</head>

<body>
<h3>Reassign Tours</h3>

<div>
<form method="post">
<label for='tour'>TourID: </label><input type='text' name='tourId'>
<label for='users'>User:</label><input type='text' name='users' id='users'>
<input type="submit" name="submit" value="Reassign">
</div>

</body>
</html>
