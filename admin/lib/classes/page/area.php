<?php


class pageArea {

	public $values = array();
	public $isNew;
	
	public function __construct($id) {
		
		$sql = new sql();
		$sql->query('SELECT * FROM '.sql::table('structure_area').' WHERE id = '.$id)->result();
		
		$this->values = $sql->result;
		$this->isNew = ($sql->num() == 0);
		
	}
	
	public function get($value) {
		
		if($this->isNew)
			return;
			
		return $this->values[$value];
		
	}
	
	public function getModulId() {
		
		if($this->isNew) {
			
			return type::super('modul', 'int');
			
		} else {
	
			return $this->get('modul');
			
		}
		
	}
	
	public function getSort() {
		
		if($this->isNew) {
	
			return type::super('sort', 'int');
			
		} else {
		
			return $this->get('sort');
			
		}
		
	}
	
	public function getStructureID() {
	
		if($this->isNew) {
			
			return type::super('structure_id', 'int');
			
		} else {
			
			return $this->get('structure_id');
			
		}
		
	}
	
	public function isOnline() {
	
		return $this->get('online') == 1;
		
	}
	
	public function isFirstBlock() {
		
		return $this->getSort() == 1;
		
	}
	
	public function isLastBlock() {
		
		$sql = new sql;
		$sql->query('SELECT sort FROM '.sql::table('structure_area').' WHERE structure_id = '.$this->getStructureID());
		
		if($this->isNew) {
			
			return $sql->num()+1 == $this->getSort(); // +1 weil Eintrag noch nicht gespeichert ist
			
		} else {
			
			return $sql->num() == $this->getSort();
			
		}
		
		
	}
	
}

?>