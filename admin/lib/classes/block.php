<?php

class block {
	use traitFactory;
	use traitMeta;
	
	static $blocks = [];
	
	public $sql;
	
	/**
	 * Einen Block aufrufen
	 *
	 * @param	string	$name			Blockname
	 */
	public function __construct($name) {
		
		$this->sql = sql::factory();
		$this->sql->query("SELECT * FROM ".sql::table('blocks')." WHERE name = '".$name."'")->result();	
			
	}
	
	/**
	 * Gibt den Block direkt aus
	 *
	 * @param	string	$name			Blockname
	 * @return	string
	 */
	
	public static function getBlock($name) {
		
		$sql = sql::factory();
		$sql->query("SELECT * FROM ".sql::table('blocks')." WHERE name = '".$name."'")->result();
		
		if(!self::isInCategory($sql->get('is-structure'), $sql->getArray('structure')))
			return '';
		else {
		
			if(!pageCache::exist($sql->get('id'), $sql->get('lang',  lang::getLangId()), false, 'block')) {
				pageCache::generateArticle($sql->get('id'), $sql->get('lang',  lang::getLangId()), 'block');
			}
					
			$content = pageCache::read($sql->get('id'), $sql->get('lang',  lang::getLangId()), 'block');

			return pageArea::getEval($content);
		
		}
		
	}
	
	/**
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
	
	/**
	 * SQL Eintrag rausholen
	 *
	 * @param	string	$name			Name des Block
	 * @return	object
	 */
	public static function getSql($name) {
	
		if(!isset(self::$blocks[$name])) {
			throw new Exception(lang::get('block_name_not_exist', $name));
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