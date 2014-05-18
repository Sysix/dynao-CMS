<?php

class block {
	use traitFactory;
	use traitMeta;
	
	static $blocks = [];
	
	public $sql;
	
	/*
	 * Einen Block aufrufen
	 *
	 * @param	string	$name			Blockname
	 */
	public function __construct($name) {
		
		$this->sql = self::getSql($name);
	}
	
	/*
	 * Gibt den Content des Block aus
	 *
	 * @return	string
	 */
	public function getContent() {
		
		if(!self::isInCategory($this->sql->get('is-structure'), $this->sql->getArray('structure'))) {
			return '';
		}
		
		return module::getByStructureId($this->sql->get('id'), true);
		
	}
	
	/*
	 * Gibt den Block direkt aus
	 *
	 * @param	string	$name			Blockname
	 * @return	string
	 */
	public static function getBlock($name) {
		
		$class = __CLASS__;
		
		try {
		
			$block = new $class($name);
			
			return $block->getContent();
			
		} catch(Exception $e) {
			
			return $e->getMessage();
				
		}
	}
	
	/*
	 * Überprüfen ob der Block in der Kategorie angezeigt werden darf
	 *
	 * @param	int		$is-structure	Ob in allen Kategorien oder nicht
	 * @param	array	$structure		Die Kategorien, wo der Block angezeigt wird
	 * @return	bool
	 */
	public static function isInCategory($is_structure, $structure) {
		
		if($is_structure == 1)
			return true;
			
		return in_array(dyn::get('page_id'), (array)$structure);
			
	}
	
	/*
	 * SQL Eintrag rausholen
	 *
	 * @param	string	$name			Name des Block
	 * @return	object
	 */
	public static function getSql($name) {
	
		if(!isset(self::$blocks[$name])) {
			throw new Exception(sprintf(lang::get('block_name_not_exist'), $name));
		}
		
		return self::$blocks[$name];
	}
	
	/*
	 * Content des Blocks speichern
	 */
	public static function saveBlock() {
		
		$id = type::post('id', 'int');
		
		$sql = sql::factory();
		$sql->setTable('blocks');
		
		foreach(pageArea::$types as $class) {
			$class = new $class();
			$sql = $class->addSaveValues($sql);
		}
		
		$sql->setWhere('id='.$id);
		$sql->update();
		
	}
	
}

?>