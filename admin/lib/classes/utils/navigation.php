<?php

class navigation {
	
	public $sql;

	public function __construct($id) {
		
		if(is_object($id) && is_a($id, 'sql')) {
			
			$this->sql = $id;
			
		} else {
		
			$this->sql = sql::factory();
			$this->sql->query('SELECT * FROM '.sql::table('structure').' WHERE id = '.$id.' AND `lang` = '.lang::getLangId())->result();
			
		}
				
	}
	
	public function hasChild($offlinePages = false) {
		
		$extraWhere = '';
		
		if(!$offlinePages) {				
			$extraWhere =  ' AND online = 1';				
		}		
		$sql = sql::factory();
		
		return $sql->num('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$this->sql->get('id').$extraWhere.' AND `lang` = '.lang::getLangId().' ORDER BY sort') != 0;
		
	}
	
	public function get($name, $default = null) {
		
		return $this->sql->get($name, $default);
	
	}
	
	public function isOnline() {
		
		return $this->get('online') == 1;
		
	}
	
	public function getUrl() {
	
		return url::fe($this->get('id'), ['lang' => $this->get('lang')]);
		
	}
	
	public static function getCategoryById($parentId, $offlinePages = false) {
		
		$extraWhere = '';
		
		if(!$offlinePages) {				
			$extraWhere =  ' AND online = 1';				
		}
		$class = __CLASS__;
		
		$sql = sql::factory();
		
		$return = [];
		
		$sql->query('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.$extraWhere.' AND `lang` = '.lang::getLangId().' ORDER BY sort')->result();
		while($sql->isNext()) {
			$sql2 = clone $sql;
			$return[] =  new $class($sql2);
			
			$sql->next();
		}
		
		return $return;
		
	}
	
}

?>