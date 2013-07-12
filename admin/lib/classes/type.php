<?php

class type {

	static public function cast($var, $type) {	
		
		switch($type) {
			
			case 'bool': case 'boolean':
				return (boolean) $var;
				
			case 'int': case 'interger':
				return (int) $var;
				
			case 'double':
				return (double) $var;
				
			case 'float':
				return (float) $var;
				
			case 'string':
				return (string) $var;
				
			case 'object':
				return (object) $var;
				
			case 'array':
				return (empty($var)) ? array() : (array) $var;
				
			default:
				//new Exception();	
			
		}
		
	}
	
	static public function get($var, $type, $default = null) {
				
		return self::checkVar($_GET, $var, $type, $default);
					
	}
	
	static public function post($var, $typ, $default = null) {
		
		return self::checkVar($_POST, $var, $typ, $default);
		
	}
	
	static public function files($var, $default = null) {
		
		return self::checkVar($_FILES, $var, $typ, $default);
		
	}
	
	static public function super($var, $typ, $default = null) {
	
		return self::get($var, $typ, self::post($var, $typ, $default));
		
	}
	
	static public function cookie($var, $typ, $default = null) {
		
		return self::checkVar($_COOKIE, $var, $typ, $default);
		
	}
	
	static public function server($var, $typ, $default = null) {
		
		return self::checkVar($_SERVER, $var, $typ, $default);
		
	}
	
	static public function session($var, $typ, $default = null) {
		
		return self::checkVar($_SESSION, $var, $typ, $default);
		
	}
	
	static private function checkVar($global, $var, $typ, $default = null) {
		
		if(array_key_exists($var, $global)) {
			return self::cast($global[$var], $typ);	
		}
		
		return $default;
		
	}
	
}

?>