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
								WHERE s.id = '.$id.$extraWhere.'
								ORDER BY `sort`')->result();
		
		}
		
	}
	
	public function get($value, $default = null) {
		
		return $this->sql->get($value, $default);
		
	}
	
	public function isOnline() {
	
		return $this->get('online', 0) == 1;
		
	}
	
	public static function getChildPages($parentId, $offlinePages = true) {
		
		$return = array();
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
	
	public static function getTreeStructure($offlinePages = true, $spacer = '&nbsp; &nbsp;', $parentId = 0, $lvl = 0) {
		
		$extraWhere = '';
		
		
		if(!$offlinePages) {				
			$extraWhere =  ' AND online = 1';				
		}
		
		$select = '';
		
		$sql = sql::factory();
		$sql->query('SELECT id, name, online FROM '.sql::table('structure').' WHERE parent_id = '.$parentId.$extraWhere.' ORDER BY sort')->result();	
		while($sql->isNext()) {
			
			if($offlinePages) {
				$style = ($sql->get('online') == 1) ? ' class="page-online"' : ' class="page-offline"';
			} else {
				$style = '';	
			}
			
			$name = $sql->get('name');
			
			if($spacer != '') {
				
				for($i = 1; $i <= $lvl; $i++) {
					$name = $spacer.$name;
				}
				
			}
			
			$select .= '<option value="'.$sql->get('id').'"'.$style.'>'.$name.'</option>'.PHP_EOL;
			
			if($sql->num('SELECT id FROM '.sql::table('structure').' WHERE parent_id = '.$sql->get('id').$extraWhere)) {
				$select .= self::getTreeStructure($offlinePages, $spacer, $sql->get('id'), $lvl+1);
			}
			
			$sql->next();
		}
		
		return $select;
		
	}
	
}

?>