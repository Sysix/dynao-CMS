<?php

	class cache {
		
		static protected $cache = false;
		static protected $cacheDir = "admin/generated/cache/";
		static protected $cacheTime = 30;
		static protected $cacheFile = null; 
		
		// Cache true/false
		static public function setCache($bool) { 
			self::$cache = $bool;
		}
		
		//Pfad setzen
		static public function setCacheDir($path) {
			self::$cacheDir = $path;
		}
		
		//Zeit setzen
		static public function setCacheTime($time) {
			self::$cacheTime = intval($time);
		}
		
		//Namen setzen
		static public function setCacheFile($id, $tpl) {
			self::$cacheFile = md5($id.$tpl).'.cache';
		}
		
		//File löschen
		static public function deleteCacheFile() {
			
			if(unlink(self::$cacheDir.self::$cacheFile)) {
				return true;
			}
			
			return false;
		}
		
		// Prüfen ob bereits erstellt
		static public function existCache() {
			
			if(file_exists(self::$cacheDir.self::$cacheFile) ) {
				
				if((time() - filemtime(self::$cacheDir.self::$cacheFile)) >= self::$cacheTime) {
					self::$deleteCacheFile();
					clearstatcache();
					return false;
				}
				
				clearstatcache();
				return true;
				
			}
			
			return false;
		}
		
		//File erstellen
		static public function writeCache($data) {
			
			if(self::$cache === true && self::$existCache() === false) {
				
				if(!file_put_contents(self::$cacheDir.self::$cacheFile, serialize($data), LOCK_EX)) {
					return false;
				}
				
			}
			
		}
		
		//Auslesen
		static public function readCache() {
			$data = file_get_contents(self::$cacheDir.self::$cacheFile);
			$cdata = unserialize($data);
			return $cdata;
		}
		
	}

?>