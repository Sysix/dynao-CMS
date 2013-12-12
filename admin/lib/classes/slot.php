<?php

class slot {
	use traitFactory;
	use traitMeta;
	
	static $slots = [];
	
	public $sql;
	
	/*
	 * Einen Slot aufrufen
	 *
	 * @param	string	$name			Slotname
	 */
	public function __construct($name) {
		
		self::generateAll();
		$this->sql = self::getSql($name);
	}
	
	/*
	 * Gibt den Content des Slots aus
	 *
	 * @return	string
	 */
	public function getContent() {
		
		if(!self::isInCategory($this->sql->get('is-structure'), $this->sql->getArray('structure'))) {
			return '';
		}
		
		$pageArea = new pageArea($this->sql);
		
		return $pageArea->OutputFilter($this->sql->get('output'), $this->sql);		
	}
	
	/*
	 * Gibt den Slot direkt aus
	 *
	 * @param	string	$name			Slotname
	 * @return	string
	 */
	public static function getSlot($name) {
		
		self::generateAll();
		
		$sql = self::getSql($name);
		
		if(!self::isInCategory($sql->get('is-structure'), $sql->getArray('structure'))) {
			return '';	
		}
		
		$pageArea = new pageArea($sql);
		
		return $pageArea->OutputFilter($sql->get('output'), $sql);	
	}
	
	/*
	 * Überprüfen ob der Slot in der Kategorie angezeigt werden darf
	 *
	 * @param	int		$is-structure	Ob in allen Kategorien oder nicht
	 * @param	array	$structure		Die Kategorien, wo der Slot angezeigt wird
	 * @return	bool
	 */
	public static function isInCategory($is_structure, $structure) {
		
		if($is_structure == 1)
			return true;
			
		return in_array(dyn::get('page'), (array)$structure);
			
	}
	
	/*
	 * SQL Eintrag rausholen
	 *
	 * @param	string	$name			Name des Slots
	 * @return	object
	 */
	public static function getSql($name) {
	
		if(!isset(self::$slots[$name])) {
			throw new Exception(sprintf(lang::get('slot_name_not_exist'), $name));
		}
		
		return self::$slots[$name];
	}
	
	/*
	 * Alle Slots aus der Datenbank holen und statische Variable speichern
	 */
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
				$sql2 = clone $sql;
				self::$slots[$sql->get('name')] = $sql2;
				
				$sql->next();
			}
			
		}
		
	}
	
	/*
	 * Alle Slots zurückgeben
	 *
	 * @return	array
	 */
	public static function getArray() {
		
		self::generateAll();
		
		return self::$slots;
		
	}
	
	/*
	 * Content des Slots speichern
	 */
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