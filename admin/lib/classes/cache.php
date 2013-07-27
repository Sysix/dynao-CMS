<?php

	class cache {
		
		private $cache = true;
		private $cacheDir = "admin/generated/cache/";
		private $cacheTime = 30;
		private $cacheFile = null; 
		
		// Cache true/false
		public function setCache($bool) { 
			$this->cache = $bool;
		}
		
		//Pfad setzen
		public function setCacheDir($path) {
			$this->cacheDir = $path;
		}
		
		//Zeit setzen
		public function setCacheTime($time) {
			$this->cacheTime = intval($time);
		}
		
		//Namen setzen
		public function setCacheFile($id, $tpl) {
			$this->cacheFile = md5($id.$tpl).'.cache';
		}
		
		//File löschen
		public function deleteCacheFile() {
			
			if(unlink($this->cacheDir.$this->cacheFile)) {
				return true;
			}
			
			return false;
		}
		
		// Prüfen ob bereits erstellt
		public function existCache() {
			
			if(file_exists($this->cacheDir.$this->cacheFile) ) {
				
				if((time() - filemtime($this->cacheDir.$this->cacheFile)) >= $this->cacheTime) {
					$this->deleteCacheFile();
					clearstatcache();
					return false;
				}
				
				clearstatcache();
				return true;
				
			}
			
			return false;
		}
		
		//File erstellen
		public function writeCache($data) {
			
			if($this->cache === true && $this->existCache() === false) {
				
				if(!file_put_contents($this->cacheDir.$this->cacheFile, serialize($data), LOCK_EX)) {
					return false;
				}
				
			}
			
		}
		
		//Auslesen
		public function readCache() {
			$data = file_get_contents($this->cacheDir.$this->cacheFile);
			$cdata = unserialize($data);
			return $cdata;
		}
		
	}

?>