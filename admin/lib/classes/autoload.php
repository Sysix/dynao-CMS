<?php

class autoload {
	
	
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
		
		self::$classes[] = dir::classes($classPath.'.php');
		
		include_once(dir::classes($classPath.'.php'));
		
		return class_exists($class);
		
	}
	
	static public function getClasses() {
		
		return self::$classes;	
		
	}
	
}

?>