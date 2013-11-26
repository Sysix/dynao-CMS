<?php

class module {
	
	public $sql;
	
	public function __construct($id) {
	
		if(is_object($id) && is_a($id, 'sql')) {
			$this->sql = $id;
		} else {
			$this->sql = sql::factory();
			$this->sql->query('SELECT * FROM '.sql::table('structure_area').' WHERE id='.$id);
		}
		
	}
	
	public function getContent() {
		$pageArea = new pageArea($this->sql);
		
		return $pageArea->OutputFilter($this->sql->get('output'), $this->sql);
	}

}

?>