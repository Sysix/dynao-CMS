<?php

class lang {
	
	static $lang;
	static $langs = array();
	static $default = array();
	
	static private $isDefaultSet = false;
	static $defaultLang = 'en_gb';
	
	static public function setLang($lang = 'en_gb') {
		
		if(is_dir(dir::lang($lang))) {
			
			self::$lang = $lang;	
			self::loadLang(dir::lang(self::getLang(), 'main'));
			
		}
		
		// throw new Exception();
		
	}
	
	static public function get($name) {
		
		if(isset(self::$langs[$name])) {
			return self::$langs[$name];	
		}
		
		if(isset(self::$default[$name])) {
			return self::$default[$name];
		}
		
		return $name;
		
	}
	
	static public function getLang() {
		
		return self::$lang;
			
	}
	
	static public function loadLang($file) {
		
		$file = file_get_contents($file.'.json');
		
		// Alle Kommentare löschen (mit Raute beginnen
		$file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);	
		$array = json_decode($file, true);
				
		self::$langs = array_merge((array)$array,self::$langs);
		
	}
	
	static public function setDefault() {
		
		if(!self::$isDefaultSet) {
			
			$file = file_get_contents(dir::lang(self::$defaultLang, 'main.json'));
			
			// Alle Kommentare löschen (mit Raute beginnen
			$file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);	
			self::$default = json_decode($file, true);
			
			self::$isDefaultSet = true;
		
		}
		
	}
	
}

?>
