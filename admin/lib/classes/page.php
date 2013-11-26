<?php

class page {
	use traitFactory;
	use traitMeta;
	
	protected $sql;
	
	public function __construct($id, $offlinePages = true) {
		
		if(is_object($id)) {
			
			$this->sql = $id;
			
		} else {
			
			$extraWhere = '';
			
			if(!$offlinePages) {				
				$extraWhere =  'AND WHERE s.online = 1';				
			}
	
			$this->sql = sql::factory();
			$this->sql->query('SELECT * 
								FROM '.sql::table('structure').' as s
								LEFT JOIN 
									'.sql::table('structure_area').' as a
										ON s.id = a.structure_id
									LEFT JOIN
										'.sql::table('module').' as m
										ON m.id = a.modul
								WHERE s.id = '.$id.$extraWhere.'
								')->result();
		
		}
		
	}
	
	public function get($value, $default = null) {
		
		return $this->sql->get($value, $default);
		
	}
	
	public function isOnline() {
	
		return $this->get('online', 0) == 1;
		
	}
	
	public function getBlocks() {
		$return = [];
		
		while($this->sql->isNext()) {
			$return[] = new module($this->sql);
			$this->sql->next();	
		}
		
		return $return;
	}
	
	public static function getChildPages($parentId, $offlinePages = true) {
		
		$return = [];
		$classname = __CLASS__;
		
		$extraWhere = '';
			
		if(!$offlinePages) {				
			$extraWhere =  ' AND online = 1';				
		}
	
		$sql = sql::factory();
		$sql->query('SELECT * FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.$extraWhere.' ORDER BY sort')->result();							
		while($sql->isNext()) {
		
			$return[] = new $classname($sql);
			
			$sql->next();	
		}
		
		return $return;
	
	}
	
	public static function getRootPages($offlinePage = true) {
		
		return self::getChildPages(0, $offlinePage);
		
	}
	
	
}

?>