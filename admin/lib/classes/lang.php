<?php

class lang {
	
	static $lang;
	static $langs = array();
	static $default = array();
	static $defaultLang = 'en_gb';
	
	/**
	 * Die Sprache ersetzen, mit automaitschen laden der main Datei
	 *
	 * @param	string	$lang			Die Sprache
	 *
	 */
	static public function setLang($lang = 'en_gb') {
		
		if(is_dir(dir::lang($lang))) {
			
			self::$lang = $lang;	
			self::loadLang(dir::lang(self::getLang(), 'main.json'));
			
		}
		
		// throw new Exception();
		
	}
	
	/**
	 * String in der ensprechende Sprache bekommen, falls nicht gefunden, wird die DefaultSprache genommmen
	 *
	 * @param	string	$name			Der Sprachstring
	 * @return	string
	 *
	 */
	static public function get($name) {
		
		if(isset(self::$langs[$name])) {
			return self::$langs[$name];	
		}
		
		if(isset(self::$default[$name])) {
			return self::$default[$name];
		}
		
		return $name;
		
	}
	
	/**
	 * Gibt die aktuelle Sprache zurück
	 *
	 * @return	string
	 *
	 */
	static public function getLang() {
		
		return self::$lang;
			
	}
	
	/**
	 * Gibt die aktuelle Default Sprache zurück
	 *
	 * @return	string
	 *
	 */
	static public function getDefaultLang() {
		
		return self::$defaultLang;
		
	}
	
	/**
	 * Lädt die entsprechende Datei und fügt sie zur "Datenbank" hinzu
	 *
	 * @param	string	$file			Der Dateipfad ohne .json ende
	 * @param	bool	$defaultLang	Zur Normalen Sprache oder zur Defaultsprache
	 *
	 */
	static public function loadLang($file, $defaultLang = false) {
		
		$file = file_get_contents($file);
		
		// Alle Kommentare löschen (mit Raute beginnen
		$file = preg_replace("/#\s*([a-zA-Z ]*)/", "", $file);	
		$array = json_decode($file, true);
		
		if(!$defaultLang) {
			self::$langs = array_merge((array)$array, self::$langs);
		} else {
			self::$default = array_merge((array)$array,self:: $default);
		}
		
	}
	
	
	
	/**
	 * Standardsprache setzen
	 *
	 */
	static public function setDefault() {
			
		$file = dir::lang(self::getDefaultLang(), 'main.json');
					
		self::loadLang($file, true);
		
	}
	
}

?>
