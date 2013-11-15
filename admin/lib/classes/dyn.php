<?php

class dyn {
	
	static $params = [];
	static $isChange = false;
	
	static $newEntrys = [];

	public function __construct() {
		
		self::$params = json_decode(file_get_contents(dir::backend('lib'.DIRECTORY_SEPARATOR.'config.json')), true);
		
		self::setDebug(self::get('debug'));
		
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
	static public function setDebug($debug) {
	
		if($debug) {
			
			error_reporting(E_ALL | E_STRICT);
			ini_set('display_errors', 1);
			
		} else {
			
			error_reporting(0);
			ini_set('display_errors', 0);
			
		}
		
	}
	
	static public function checkVersion() {
		
		$cacheFile = cache::getFileName(0, 'dynaoVersion');
		
		// jeden Tag
		if(cache::exists($cacheFile, 86400)) {
			
			$content = json_decode($curl, true);
				
		} else {
		
			$server = 'http://version.dynao.de/';
			
			$ch = curl_init($server);
			curl_setopt($ch, CURLOPT_PORT, 80);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla (Statuscheck-Script)');
			curl_setopt($ch, CURLOPT_TIMEOUT, 0);
			curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 300);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$curl = curl_exec($ch);
			curl_close($ch);
			
			$content = json_decode($curl, true);
			
			cache::write($content, $cacheFile);
			
		}
		
		$version = explode('.', $content['version']);
		$cversion = explode('.', dyn::get('version'));
		
		if($verion[0] != $cversion[0]) {		
			return 'Sie verwenden eine veraltete Version';	
		}
		
		if($version[1] != $cversion[1]) {
			return 'Sie verwenden eine veraltete Nebenversion';
		}
		
		if($version[2] != $cversion[2]) {
			return 'Sie verwenden eine veraltete Minor-Version';	
		}
		
		return true;
		
	}
	
}

?>