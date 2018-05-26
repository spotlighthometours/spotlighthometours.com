<?PHP
	include('../repository_inc/classes/inc.global.php');
	showErrors();
    global $db;
    $res = $db->run("SELECT * FROM tours ORDER BY tourID DESC");
    define("START_TOUR_ID",69531);
    $replacements = array();
    $res2 = $db->run("SELECT * FROM tours WHERE tourID < 666420 ORDER BY tourID DESC LIMIT 1");
    $nextInc = $res2[0]['tourID'] + 1;
    foreach($res as $index => $row){
        if( strlen($row['tourID']) > 5 ){
            $replacements[$row['tourID']] = $nextInc;
            $nextInc++;
        }
    }
    $nextInc++;
//die;
    echo json_encode($replacements);


    foreach($replacements as $wrong => $right){
        echo json_encode(array($wrong=>$right)) . "\n";
        $res = $db->run("UPDATE tours SET tourID=$right where tourID=$wrong");
        $res2 = $db->run("UPDATE orders SET tourid=$right WHERE tourid=$wrong");
        $res3 = $db->run("UPDATE tourprogress SET tourid=$right WHERE tourid=$wrong");
    }

    $res = $db->run("ALTER TABLE tours AUTO_INCREMENT = $nextInc;");
var_dump($res);
    $res2 = $db->run("SELECT max(tourID) FROM tours");
    var_dump($res2);

/*
    foreach($replacements as $index => $a ){
        var_dump($index);
        var_dump($a);
    }
*/
?>
