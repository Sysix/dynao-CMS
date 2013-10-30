<?php

class dyn {
	
	static $params = array();
	static $isChange = false;
	
	static $newEntrys = array();

	public function __construct() {
		
		self::$params = json_decode(file_get_contents(dir::backend('lib'.DIRECTORY_SEPARATOR.'config.json')), true);
		
		$this->setDebug(self::get('debug'));
		
	}
	
	public static function has($name) {
		
		return array_key_exists($name, self::$params);
		
	}
	
	public static function get($name, $default = null) {
		
		if(self::has($name)) {
	
			return self::$params[$name];
			
		}
		
		return $default;
			
	}
	
	public static function add($name, $value, $toSave = false) {
	
		self::$params[$name] = $value;
		
		if($toSave) {
			self::$isChange = true;
			self::$newEntrys[$name] = $value;
		}
		
	}
	
	public static function save() {
		
		if(!self::$isChange)
			return true;
			
		$newEntrys = array_merge(self::$params, self::$newEntrys);
			
		return file_put_contents(dir::backend('lib'.DIRECTORY_SEPARATOR.'config.json'), json_encode($newEntrys, JSON_PRETTY_PRINT));
		
	}
	
	// Allgemeine Einstellungen	
	protected function setDebug($debug) {
	
		if($debug) {
			
			error_reporting(E_ALL | E_STRICT);
			ini_set('display_errors', 1);
			
		} else {
			
			error_reporting(0);
			ini_set('display_errors', 0);
			
		}
		
		return $this;
		
	}	
	
}

?>