<?php

class module {
	
	public $sql;
	
	public function __construct($id) {
	
		if(is_object($id) && is_a($id, 'sql')) {
			$this->sql = $id;
		} else {
			$this->sql = sql::factory();
			$this->sql->query('
			SELECT a.*, m.output FROM '.sql::table('structure_area').' a
			LEFT JOIN '.sql::table('module').' as m ON m.id = a.modul
			ORDER BY a.sort
			 WHERE id='.$id);
		}
		
	}
	
	public function getContent() {
		$pageArea = new pageArea($this->sql);
		
		return $pageArea->OutputFilter($this->sql->get('output'), $this->sql);
	}
	
	public static function getByStructureId($id) {
		
		$return = [];
		$classname = __CLASS__;
		$sql = sql::factory();
		$sql->query('SELECT a.*, m.output FROM '.sql::table('structure_area').' a
			LEFT JOIN '.sql::table('module').' as m ON m.id = a.modul
			 WHERE structure_id='.$id.'
			 ORDER BY a.sort')->result();
		while($sql->isNext()) {
			$sql2 = clone $sql;
			$return[] = new $classname($sql2);
			$sql->next();
			
		}
		
		return $return;
			
	}

}

?>