<?php
    require '../repository_inc/classes/inc.global.php';
    $q = "SELECT DISTINCT(city) as city, state FROM tours WHERE concierge=1";
    $res = $db->run($q);
    $sections = array("Description","History","Demographics","Geography");
    $wiki = new wikipedia;
    $micro = new microsites;
    foreach($res as $index => $row){
        $content = $wiki->getCityDesc($row['city'], $row['state']);
        if( strlen($content) == 0 ){
            if( strstr($row['city'],"Twp.") ){
                $row['city'] = str_replace("Twp.","Township",$row['city']);
            }
            if( strstr($row['city']," Boro") ){
                $row['city'] = str_replace(" Boro","",$row['city']);
            }
            $content = $wiki->getCityDesc($row['city'],null);
            if( strlen($content) == 0 ){
                //echo "!!" . $row['city'] . "\n";
                $content = $wiki->getCityDesc($row['city'],$row['state'],true);
                if( strlen($content) == 0 ){
                    if( strstr($row['city'],"Township") ){
                        $row['city'] = str_replace("Township","",$row['city']);
                        $content = $wiki->getCityDesc($row['city'],$row['state']);
                        if( strlen($content) == 0 ){
                            $content = $wiki->getCityDesc($row['city'],$row['state'],true);
                        }
                    }
                }
            }
            if( strlen($content) == 0 ){
                echo "!!" . $row['city'] . ", " . $row['state'] . "\n";
                $res = $db->select("microsite_local","city='" . $row['city'] . "' AND state='" . $row['state'] . "'");
                if( count($res) == 0 ){
                    $db->insert("microsite_local",array('city'=>$row['city'],'state'=>$row['state']));
                }
            }
        }
        if( strlen($content) ){
                echo $row['city'] . ", " . $row['state'] . ":" . strlen($content) . "\n";
                $description = $content; //$wiki->getSection(null,null,"Description");
                $history = $wiki->getSection(null,null,"History");
                $demographics = $wiki->getSection(null,null,"Demographics");
                $geography = $wiki->getSection(null,null,"Geography");

                if( !$history ){
                    $history = "";
                }else{
                    if( count($history['images']) ){
                        $history = "<p><img src='" . $history['images'][0]['src'] . "'>" . $history['text'] . "</p>";
                    }else{
                        $history = $history['text'];
                    }
                }
                if( !$demographics ){
                    $demographics = "";
                }else{
                    if( count($demographics['images']) ){
                        $demographics = "<p><img src='" . $demographics['images'][0]['src'] . "'>" . $demographics['text'] . "</p>";
                    }else{
                        $demographics = $demographics['text'];
                    }
                }
                if( !$geography ){
                    $geography = "";
                }else{
                    if( count($geography['images']) ){
                        $geography = "<p><img src='" . $geography['images'][0]['src'] . "'>" . $geography['text'] . "</p>";
                    }else{
                        $geography = $geography['text'];
                    }
                }

                $res = $db->select("microsite_local","city='" . $row['city'] . "' AND state='" . $row['state']. "'");
                if( count($res) == 0 ){
                    $db->insert("microsite_local",array('city'=> $row['city'],'state'=>$row['state'],'finished'=>1));
                }
                $res = $db->select("microsite_local","city='" . $row['city'] . "' AND state='" . $row['state'] . "'");
                $localId = $res[0]['id'];
                
                $micro->addLocalPageDesc($localId,$description,$history,$demographics,$geography);
        }
    }
