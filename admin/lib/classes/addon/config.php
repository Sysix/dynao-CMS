<?php

class addonConfig {
	
	var $sql;
	
	public function __construct($addon) {
		
		$this->isSaved($addon);
		$this->sql = new sql();
		$this->sql->query('SELECT * FROM '.sql::table('addons').' WHERE `name` = "'.$addon.'"')->result();
		
	}
	
	public function get($name, $default = 0) {
	
		return $this->sql->get($name, $default);
		
	}
	
	public function isInstall() {
		
		return ($this->get('install', 0) == 1);
			
	}
	
	public function isActive() {
		
		return ($this->get('active', 0) == 1);
		
	}
	
	public function isSaved($addon, $save = true) {
	
		$sql = new sql();
		$num = $sql->num('SELECT 1 FROM '.sql::table('addons').' WHERE `name` = "'.$addon.'"');
		if(!$num && $save) {
			$save = new sql();
			$save->setTable('addons');
			$save->addPost('name', $addon);
			$save->save();
		}
		
		return $num;
		
	}
	
	public static function getAll($active = true) {
		
		$return = array();
		$active = ($active) ? ' AND `active` = 1' : '';
		
		$sql = new sql();		
		$sql->query('SELECT name FROM '.sql::table('addons').' WHERE `install` = 1'.$active)->result();
		while($sql->isNext()) {
			$return[] = $sql->get('name');
			$sql->next();		
		}
		
		return $return;
		
	}
	
	public static function includeAllConfig() {
		
		foreach(self::getAll() as $name) {
			$configFile = dir::addon($name, 'config.php');
			include_once($configFile);
		}
		
	}
	
	public static function includeAllLangFiles() {
		
		foreach(self::getAll() as $name) {
			
			$file = dir::addon($name, 'lang/'.lang::getLang());
			if(file_exists($file.'.json')) {				
				lang::loadLang($file);
			}
		}
		
	}
	
	public static function getAllConfig() {
		
		$return = array();
		
		foreach(self::getAll() as $name) {
			$configFile = dir::addon($name, 'config.json');
			$return[$name] = json_decode(file_get_contents($configFile), true);
		}
		
		return $return;
	}
	
}

?>