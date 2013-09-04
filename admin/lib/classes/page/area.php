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
	
	public function isOnline() {
	
		return $this->get('online') == 1;
		
	}
	
	public function isFirstBlock() {
		
		if($this->isNew) {
	
			return type::super('sort', 'int') == 1;
			
		} else {
		
			return $this->get('sort') == 1;
			
		}
		
	}
	
}

?>