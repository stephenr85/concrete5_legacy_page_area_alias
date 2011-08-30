<?php


class PageAreaAliasHelper {
	
	public function __construct() {
		
	}
	
	public function getAreaHandlesByCollection(&$c){
		$db = Loader::db();
		$query = $db->query('select arHandle from Areas where cID = ? order by arHandle', $c->getCollectionID());
		$arHandles = array();
		//fetch rows
		while($row = $query->fetchRow()){
			$arHandles[] = $row['arHandle'];
		}
		return $arHandles;
	}
	
	public function getAreaHandlesByCollectionID($cID){
		if(empty($cID) || !is_object($c = Page::getByID($cID))){
			return FALSE;
		}
		return $this->getAreaHandlesByCollection($c);
	}
	
	
	public function getAreasByCollection(&$c){
		if(!is_object($c)){
			return FALSE;	
		}
		$arHandles = $this->getAreaHandlesByCollection($c);
				
		$areas = array();
		foreach($arHandles as $arHandle){
			$areas[] = Area::get($c, $row['arHandle']);	
		}
		return $areas;
	}
	
	public function getAreasByCollectionID($cID){
		if(empty($cID) || !is_object($c = Page::getByID($cID))){
			return FALSE;
		}
		return $this->getAreasByCollection($c);
	}
	
		
}