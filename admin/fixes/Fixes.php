<?php

namespace Admin\Fixes;

class Fixes {
	const RESOLVED = "resolved";
	const OPEN = "open";
	const OTHER = "other";

    const PARENT_REPLY = 0;
    const UPDATE = 1;

    const SUBJECT_REPLY = "%s responded to your FIX IT request";
    const SUBJECT_UPDATE = "%s changed a status [tour ID: %d ]";

	protected $db;
	protected $alwaysSend = null;
    protected $alwaysFrom = null;
    protected $resolveEmailBody = "";
	public function __construct(){
		global $db;

		$this->db = $db;
		$stm = $this->db->run("SELECT * FROM fixes LIMIT 1");
		if( $stm === false ){
			$this->createTables();
		}
	}

    public function setAlwaysSend($email){
        $this->alwaysSend = $email;
    }

    public function setAlwaysFrom($email){
        $this->alwaysFrom = $email;
    }
    public function getResolveEmailBody(){
        return $this->resolveEmailBody;
    }
    public function sendToResolveEmail($subject,$msg){
        if( $this->alwaysFrom ){
            $headers = "From: $this->alwaysFrom\r\n" .
                "Reply-To: $this->alwaysFrom\r\n"
            ;
        }else{
            $headers = "";
        }
        if( $this->alwaysSend ){
            mail($this->alwaysSend,     /* Recipient */
                $subject,               /* Subject */
                $msg,                   /*The message */
                $headers                /* Headers for that message */
            );
        }
    }

	//Tested
	private function createTables(){
		$a = $this->db->run("
		CREATE TABLE fixes(
			id INT AUTO_INCREMENT NOT NULL,
			tourId INT NOT NULL,
			op INT NOT NULL,
			editor INT,
			notes BLOB,
			status enum('resolved','open','other'),
			uploads BLOB,
			parentId INT,
			dateRequested timestamp DEFAULT NOW(),
			empType enum('editor','employee'),
            dateResolved timestamp,
			PRIMARY KEY(id)
		)
		");
        $c = $this->db->run("
        CREATE TABLE fixesNotify(
            id INT NOT NULL AUTO_INCREMENT,
            parent INT NOT NULL,
            admins VARCHAR(512) NOT NULL
        )
        ");
		var_dump($a,$c);
	}

    public function resolveEditor($id){
        $result = $this->db->run(
            "SELECT * FROM administrators ".
            " WHERE type='editor' AND administratorID=" . intval($id) 
        );
        if( isset($result[0]) ){
            return $result[0];
        }
        return array();
    }

	//Tested
	public function ajaxGrabNotes($index){
		$results = $this->db->select("fixes","id=" . intval($index));
		return json_encode($results);
	}
	//Tested
	public function updateNote($index,$text){
		$this->db->update("fixes",array("notes"=>htmlentities($text)),"id=" . intval($index));
	}
	//Tested
	public function updateStatus($index,$status){
		switch($status){
			case Fixes::RESOLVED:
                $resolved = true;break;
			case Fixes::OPEN:
			case Fixes::OTHER:
                $resolved = false;
			break;
			default: return;
		}
		//mail( $editor ... )
		//mail( $employee ... )
        
		$this->db->update("fixes",array(
            "status"=>$status,
            "dateResolved" => ($resolved? date("Y-m-d H:i:s",time()) : '0000-00-00 00:00:00' )
        ),"id=" . intval($index));
	}
    public function addNotify($parent,$who){
        $res = $this->db->run("select * from fixes_notify WHERE parent=" .
            $parent=intval($parent)
        );
        if( count($res) ){
            $arr = json_decode($res[0]['admins'],true);
        }else{
            $arr = array();
        }
        $arr[] = intval($who);
        $json = json_encode(array_unique($arr));
        if( count($arr) > 1 ){
            $query = "update fixes_notify set admins='$json' " .
              " WHERE id=" . intval($res[0]['id'])
            ;
        }else{
            $query = "INSERT INTO fixes_notify (parent,admins) " . 
                "Values($parent,'$json')"
            ;
        }
        $this->db->run($query);
    }
    public function removeNotify($parent,$who){
        $parent = intval($parent); $who = intval($who);
        $res = $this->db->run("SELECT id,admins from fixes_notify " .
            " WHERE parent=$parent "
        );
        if( empty($res) ){
            return;
        }
        $arr = json_decode($res[0]['admins'],true);
        $a = array_filter($arr,function($value) use($who) {
            return $who != $value;
        });
        $json = json_encode(array_values($a));
        $this->db->update("fixes_notify",array("admins" =>$json),"id=" . $res[0]['id']);
        
        //if( empty( $this->getNotify($parent) ) ){
        if( count($this->getNotify($parent)) == 0 ){
            $this->db->delete("fixes_notify","parent=$parent");
        }
    }
    public function resolveNotifyEmails(array $admins){
        $emailList = array();
        foreach($admins as $index => $admin){
            $res = $this->db->run("SELECT * from administrators " .
                " WHERE administratorID=" . intval($admin)
            );
            if( isset($res[0]['email']) ){
                $emailList[$admin] = $res[0];
            }
        } 
        return $emailList;
    }
    public function getNotify($parent){
        $res = $this->db->run("SELECT admins FROM fixes_notify " . 
            " WHERE parent=" . intval($parent)
        );
        return json_decode($res[0]['admins'],true);
    }
    public function toTourId($postId){
        $res = $this->db->select("fixes","id=" . intval($postId));
        return $res[0]['tourId'];
    }
    public function resolveEmail($adminId){
        $res = $this->db->run("SELECT email from administrators " .
            " WHERE adminstratorID=" . intval($adminId)
        );
        return $res[0]['email'];
    }
    public function emailNotify(array $a){
        $admins = $this->getNotify($a['postId']);
#echo "Admins from getNotify: " . var_export($admins,1) . "<hr>";
        if( $admins === null ){
             $this->addNotify($a['postId'],$a['from']);
             $admins[] = $a['from'];
        }
        $admins = array_filter($admins,function($value) use($a){
            return $a['from'] != $value;  
        });
        if( count($admins) == 0 ){
            return;
        }
        $from = $this->resolveNotifyEmails(array($a['from']))[$a['from']];
//var_dump($from);
        $adminList = $this->resolveNotifyEmails($admins);
        foreach($adminList as $adminId => $innerArray){
            $emailList[] = $innerArray['email'];
        }
        $res = $this->db->run("
            SELECT email from administrators a
            INNER JOIN fixes f ON f.op = a.administratorID
            WHERE f.id = " . intval($a['postId'])
        );
        $emailList[] = $res[0]['email'];
        $sendTo = implode(',',$emailList);
//echo "Sending to: $sendTo<hr>";
        $tourId = $this->toTourId($a['postId']);
        switch($a['mode']){
            case Fixes::PARENT_REPLY:
                $msg = <<<EOF
FIX IT Response from: %s
====================
Date: %s
Type: Reply
Tour ID: %d

Message: 
%s
EOF;
                $msg = sprintf($msg,    /* The message to replace */
                    $from['fullName'],  /* Who is responding */
                    date("Y-m-d H:i:s",time()), /* The date and time of this transaction */
                    $tourId,            /* Which tour ID */
                    $a['notes']         /* What they said */
                );
                $subject = sprintf(Fixes::SUBJECT_REPLY,$from['fullName']);
            break;
            case Fixes::UPDATE:
                $status = $a['status'];
                $res = $this->db->run("
                    SELECT * from fixes_notify fn
                    INNER JOIN fixes f on f.id = fn.parent
                ");
                $msg = <<<EOF
FIX IT Status Update: 
====================
Date: %s
Type: Update
Tour ID: %d

%s changed the status of this ticket to %s

To see the ticket click <a href='http://spotlighthometours.com/admin/fixes/trackingSystem.php?searchByTourId=%d'>here</a>
EOF;
            $msg = sprintf($msg,            /* The message to replace */
                date("Y-m-d H:i:s",time()), /* Date and time of this transaction */
                $tourId,                    /* Tour ID */
                $from['fullName'],          /* Who changed the status */
                $a['status'],               /* What the status was chagned to */
                $tourId                     /* Put tour ID in an anchor tag */
            );
            $subject = sprintf(Fixes::SUBJECT_UPDATE,$from['fullName']);
            $this->resolveEmailBody = $msg;
            break;
            default: return;
        }
        //var_dump("SEND TO: " , $sendTo);
        //echo "mail(\"$sendTo\",\"$subject\"," . $msg .");";
        foreach(explode(',',$sendTo) as $person){
            mail($person,$subject,$msg);
        }
    }
    public function friendlyBytes($id){
        $res = $this->db->select("fixes","id=" . intval($id));
        $str = strlen($res[0]['notes']);
        if( ($str / 1000) < 1 ){
            return $str . "bytes";
        }elseif( ($str / 1000) > 0 ){
            $a = $str/1000;
            if( $a > 1000 ){
                return $a / 1000 . "Mb";
            }else{
                return $a . "Kb";
            }
        }
    }
	//Tested
	public function getList($where=null){
		if( $where ){
			$results = $this->db->select("fixes",$where);
		}else{
			$results = $this->db->select("fixes");
		}
		return $results;
	}
	public function resolvePoster($fixId,$op){
		$ret = $this->db->run("
            SELECT fullName from administrators a
            INNER JOIN fixes f on f.op = a.administratorID
            WHERE f.id = " . intval($fixId) . " AND op=" . intval($op) 
		);
		return $ret[0]['fullName'];
	}
	public function webifyUploads($json){
		$arr = json_decode($json,true);
		$html = "";
        if( $arr ){
		    foreach($arr as $index => $file){
		    	$a = str_replace(FIXES_UPLOAD_DIRECTORY,"http://spotlighthometours.com/admin/fixes/uploads/",$file);
		    	$a = str_replace("\\","/",$a);
		    	$html .= "<a target=_blank href='$a'>" . basename($a) . "</a><br>";
		    }
        }
		return $html;
	}
	public function setParent($p){
		$this->m_parent = intval($p);
	}
	public function grabEditorByTourId($tourId){
		$ret = $this->db->run(
			"SELECT * from fixes 
			INNER JOIN employees e ON e.id = fixes.editor
			WHERE fixes.tourId = $tourId AND e.type = 'editor'
			");
	}
	//Tested 
	public function newNote($tourId,$opId,$editorId,$notes,$empType,$fileKey,$parentId,$urgency){
		$ret = $this->db->insert("fixes",array(
			"tourId"=>intval($tourId),
			"op" => intval($opId),
			"editor" => intval($editorId),
			"notes" => htmlentities($notes,ENT_QUOTES,"UTF-8"),
			"status" => Fixes::OPEN,
			"uploads" => "",
			"parentId" => $parentId,
			"empType" => $empType,
			"urgent" => $urgency
			));
		$insertId = $this->db->lastInsertId();
		if( $fileKey != null ){
            try{
			    $json = $this->uploadFile($fileKey,$insertId);
            }catch(\Exception $e){
                return;
            }
			//echo "JSON returned from uploadFile: $json<hr>";
			$this->db->update("fixes",array("uploads"=>$json),"id=" . $insertId);
		}
		//var_dump($this->getList("id=$insertId"));
		//exit;
	}
	/**
	 * @param int $fixIndex The ID of the row you are associating this upload with
	 * @param string $fileKey The key in the $_FILES array that points to your upload
	 */
	public function uploadFile($fileKey,$fixId){
		$row = $this->getList("id=" . intval($fixId))[0];

		$uploadDir = FIXES_UPLOAD_DIRECTORY . DIRECTORY_SEPARATOR . $fixId;
		if( !file_exists($uploadDir) ){
			$ret = @mkdir($uploadDir);
            if( $ret === false ){
                throw new \Exception("Unable to create directory: $uploadDir");
            }
			//echo "Making upload directory: $uploadDir<hr>";
		}
		if( strlen($row['uploads']) ){
//			echo "Grabbing existing uploads json<hr>";
			$arr = json_decode($row['uploads'],true);
			
		}else{
//			echo "No JSON found, utilizing new array object<hr>";
			$arr = array();
		}
		$files = $_FILES[$fileKey]['name'];
		if( !is_array($_FILES[$fileKey]['name']) ){
			//
			$a = $_FILES[$fileKey]['name'];
			$_FILES[$fileKey]['name'][0] = $a;
			$a = $_FILES[$fileKey]['tmp_name'];
			$_FILES[$fileKey]['tmp_name'][0] = $a;
		}

		for($i=0;$i < count($_FILES[$fileKey]); $i++){
            if( isset($_FILES[$fileKey]['name'][$i]) ){
    			$uploadFile = $uploadDir . DIRECTORY_SEPARATOR . basename($_FILES[$fileKey]['name'][$i]);
    			if (move_uploaded_file($_FILES[$fileKey]['tmp_name'][$i], $uploadFile )) {
    				$arr[] = $uploadFile;
    			}
            }
		}
		return json_encode($arr);
	}
	
	public function getChildNodes($fixId){
		return $this->db->select("fixes","parentId=" . intval($fixId));
	}
}
