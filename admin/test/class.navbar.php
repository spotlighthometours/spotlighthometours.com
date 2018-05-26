<?php
/**
 * @author William Merfalen (william@spotlighthometours.com)
 * @description This class will hold all the logic behind the left nav bar in admin. 
 * 
 */

class navbar {
    private $db;
    private $adminTable = "administrators";
    private $adminId = null;
    private $table = 'navbar_permissions';
    private $pageList = [];
    
    
    public function __construct($adminId){
        global $db;
        $this->db = $db;
        $this->adminId = $adminId;
        $this->loadByAdminId($adminId);
    }
    
    public function canEdit($adminId){
        $res = $this->db->select($this->adminTable, 
                "administratorID=" . intval($adminId) . 
                " AND editNavbar=1"
        );
        if( !empty($res) ){
            return true;
        }else{
            return false;
        }
    }
    
    public function loadByAdminId($aId=null){
    	if( $aId === null ){
    		$adminId = intval($this->adminId);

    	}
    	else{
    		$adminId = intval($aId);
    	}
    	
    	$q = "select n.name,n.href,n.new from " . $this->table  . " " . 
			 " INNER JOIN administrators a ON a.administratorID = " . $this->table . ".adminId " .
			 " INNER JOIN navbar n ON n.id = " . $this->table . ".navbarId " . 
			 " WHERE a.administratorID = $adminId AND n.show =1 "
		;      	
      	return $this->pageList = $this->db->run($q);
      	
    }

    public function get($name){
    	if( isset($this->{$name})){
    		return $this->{$name};
    	}else{
    		$this->errors->set("Error trying to load attribute: '$name'");
    	}
    	return null;
    }
    public function clearPermissions($adminId){
    	$this->db->delete($this->table,"adminId=" . intval($adminId));
    }
    
    public function addPermission($adminId,$pageId){
        $res = $this->db->select($this->table,"adminId=" . intval($adminId) . " AND navbarId=" . intval($pageId));
        if( count($res) ){ //User already has an entry for this item
        	return;
        }else{
        	$this->db->insert($this->table,['adminId' => intval($adminId), 'navbarId' => intval($pageId)]);
        }
    }

    public function deletePermission($adminId,$pageId){
    	$aid = intval($adminId);
    	$res = $this->db->delete($this->table,"adminId=$aid AND navbarId=" . intval($pageId));
    }
    
    
}


function record_sort($records, $field, $reverse=false)
{
    $hash = array();

    foreach($records as $record)
    {
        $hash[$record[$field]] = $record;
    }

    ($reverse)? krsort($hash) : ksort($hash);

    $records = array();

    foreach($hash as $record)
    {
        $records []= $record;
    }

    return $records;
}
