<?php

class autoload {
	
	const mainDir = '../lib/classes';
	
	static $classes = array();
	static $registered = false;
	
	
	static public function register() {
		
		if(self::$registered) {
			return;	
		}
		
		if(spl_autoload_register(array(__CLASS__, 'autoloader')) === false) {
			//throw new Exception();
		}
		
		self::$registered = true;
		
	}
	
	static public function unregister() {
	
		 spl_autoload_unregister(array(__CLASS__, 'autoloader'));
		self::$registered = false;
		
	}
	
	static public function autoloader($class) {
		
		if(class_exists($class)) {
			return true;	
		}
		
		preg_match_all("/(?:^|[A-Z])[a-z]+/", $class, $treffer);
		
		$classPath = '';
		foreach($treffer[0] as $dir) {
			$classPath .= '/'.strtolower($dir);
		}
		
		self::$classes[] = self::mainDir.$classPath.'.php';
		
		include_once(self::mainDir.$classPath.'.php');
		
		return class_exists($class);
		
	}
	
	static public function getClasses() {
		
		return self::$classes;	
		
	}
	
}

?>