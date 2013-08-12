<?php

class autoload {
	
	
	static $classes = array();
	static $dirs = array();
	static $registered = false;
	static $isNewCache = false;
	
	
	static public function register() {
		
		if(self::$registered) {
			return;	
		}
		
		if(spl_autoload_register(array(__CLASS__, 'autoloader')) === false) {
			//throw new Exception();
		}
		
		self::loadCache();
		
		
        register_shutdown_function(array(__CLASS__, 'saveCache'));
		
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
		$x = 0;
		foreach($treffer[0] as $dir) {
			
			if($x) {
				$classPath .= DIRECTORY_SEPARATOR.strtolower($dir);
			} else {
				$classPath .= strtolower($dir);
			}
			
			$x++;
		}
		
		self::addClass($classPath.'.php');
		
		return class_exists($class);
		
	}
	
	static public function saveCache() {
		
		if(self::$isNewCache) {
			
			$cacheFile = cache::getFileName(0, 'autoloadcache');
			
			cache::write(json_encode(array(self::$classes, self::$dirs)), $cacheFile);
			self::$isNewCache = false;
			
		}
		
	}
	
	static protected function loadCache() {
		
		$cacheFile = cache::getFileName(0, 'autoloadcache');
		
		if(!cache::exist($cacheFile, 3600))
			return;
				
		list(self::$classes, self::$dirs) = json_decode(cache::read($cacheFile), true);
		
		foreach(self::$classes as $class) {
			include_once($class);
		}
	}
	
	// Hinzufügen von Klassen
	static protected function addClass($path) {
		
		self::$classes[] = dir::classes($path);
		
		include_once(dir::classes($path));
		
	}
	
	static public function addDir($dir) {
		
		if(!is_dir($dir)) {
			//throw new Exception;	
		}
		
		// Schon eingescannt
		if(in_array($dir, self::$dirs)) {
			return;	
		}
		
		self::$dirs[] = $dir;
		
		$files = scandir(dir::classes($dir));
		
		foreach($files as $file) {			
			
			if(in_array($file, array('.', '..')))
				continue;
				
			self::addClass($dir.DIRECTORY_SEPARATOR.$file);
			self::$isNewCache = true;
			
		}
		
	}
	
}

?>