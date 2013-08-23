<?php

class dyn {
	
	static $params = array();
	static $isChange = false;

	public function __construct() {
		
		self::$params = json_decode(file_get_contents(dir::backend('config.json')), true);
		
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
	
	public static function add($name, $value) {
	
		self::$params[$name] = $value;
		self::$isChange = true;
		
	}
	
	public static function save() {
		
		if(!self::$isChange)
			return true;
			
		return file_put_contents(dir::backend('config.json'), json_encode(self::$params, JSON_PRETTY_PRINT));
		
	}
	
}

?>