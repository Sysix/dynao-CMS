<?php

class cache {
	
	static protected $cache = true;
	static protected $time = 100;
	
	// Cache true/false
	static public function setCache($bool) {
		self::$cache = (bool)$bool;
	}
	
	//Namen setzen
	static public function getFileName($id, $table) {
		return md5($id.$table).'.cache';
	}
	
	//File löschen
	static public function deleteFile($file) {
		
		return unlink(dir::cache($file));
		
	}
	
	// Prüfen ob bereits erstellt
	static public function exist($file, $time = false) {
		
		if($time === false) {
			$time = self::$time;	
		}
		
		if(file_exists(dir::cache($file))) {
			
			if((time() - filemtime(dir::cache($file))) >= $time) {
				self::deleteFile($file);
				clearstatcache();
				return false;
			}
			
			clearstatcache();
			return true;
			
		}
		
		return false;
	}
	
	//File erstellen
	static public function write($content, $file) {
		
		if(self::$cache === true) {
			
			if(!file_put_contents(dir::cache($file), $content, LOCK_EX)) {
				return false;
			}
			
		}
		
		return true;
		
	}
	
	//Auslesen
	static public function read($file) {
		
		return file_get_contents(dir::cache($file));
		
	}
	
	//komplett löschen
	static public function clear() {
		
		if($dir =  opendir(dir::cache())) {
			
			while (($file = readdir($dir)) !== false) {
		
				if($file != "." && $file != "..") {
					
					self::deleteFile($file);
					
				}
			
			}
			
			closedir($dir);
			
		}

	}
	
}

?>