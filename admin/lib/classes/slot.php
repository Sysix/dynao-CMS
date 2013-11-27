<?php

class slot {
	use traitFactory;
	use traitMeta;
	
	static $slots = [];
	
	public $sql;
	
	public function __construct($id) {
		
		self::generateAll();
		$this->sql = self::$slots[$id];
	}
	
	public function getContent() {
		$pageArea = new pageArea($this->sql);
		
		return $pageArea->OutputFilter($this->sql->get('output'), $this->sql);	
	}
	
	protected static function generateAll() {
		
		if(empty(self::$slots)) {
			
			$sql = sql::factory();
			$sql->query('
			SELECT 
			  s.*, m.output 
			FROM 
			  '.sql::table('slots').' AS s
			  LEFT JOIN
			  	'.sql::table('module').' AS m
					ON m.id = s.modul
			')->result();
			while($sql->isNext()) {
				self::$slots[$sql->get('id')] = $sql;
				
				$sql->next();
			}
			
		}
		
	}
	
	public static function getArray() {
		
		self::generateAll();
		
		return self::$slots;
		
	}
	
	public static function saveBlock() {
		
		$id = type::post('id', 'int');
		
		$sql = sql::factory();
		$sql->setTable('slots');
		
		foreach(array_keys(pageArea::$types) as $types) {
			
			$array = type::post('DYN_'.$types, 'array', []);
			foreach($array as $key=>$value) {
				$sql->addPost(strtolower($types).$key, $value);			
			}
						
		}
		
		$sql->setWhere('id='.$id);
		$sql->update();
		
	}
	
}

?>