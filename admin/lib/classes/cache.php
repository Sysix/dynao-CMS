<?php

class cache {
	
	static protected $cache = true;
	static protected $time = 100;
	
	/**
	 * Setzt den Cache auf an/aus
	 *
	 * @param	bool	$bool			An/Aus
	 *
	 */
	static public function setCache($bool) {
		
		self::$cache = (bool)$bool;
		
	}
	
	/**
	 * Gibt einen Namen für die Datei raus
	 *
	 * @param	mixed	$id			eine ID
	 * @param	mixed	$table		eine Tabelle
	 * @return	string
	 *
	 */
	static public function getFileName($id, $table) {
		
		return md5($id.$table).'.cache';
		
	}
	
	/**
	 * Löscht eine Cache Datei
	 *
	 * @param	string	$file			Der Dateiname
	 * @return	bool
	 *
	 */
	static public function deleteFile($file) {
		
		return unlink(dir::cache($file));
		
		return true;
	}
	
	/**
	 * Überprüft ob die Cache Datei noch vorhanden ist
	 *
	 * @param	string	$file			Der Dateiname
	 * @param	int		$time			Zeit zum Leben der Datei, wenn auf false wird die Standardzeit genommen
	 * @return	bool
	 *
	 */
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
	
	/**
	 * Die Datei erstellen
	 *
	 * @param	string	$content		Der Inhalt der Datei
	 * @param	string	$file			Der Dateiname
	 * @return	bool
	 *
	 */
	static public function write($content, $file) {
		
		if(self::$cache === true) {
			
			if(!file_put_contents(dir::cache($file), $content, LOCK_EX)) {
				return false;
			}
			
		}
		
		return true;
		
	}
	
	/**
	 * Auslesen der Datei
	 *
	 * @param	string	$file			Der Dateiname
	 * @return 	string
	 *
	 */
	static public function read($file) {
		
		return file_get_contents(dir::cache($file));
		
	}
	
	/**
	 * Die ganzen Cache Dateien löschen
	 *
	 */
	static public function clear($folder = '') {
		
		if($dir =  opendir(dir::cache($folder))) {
			
			while (($file = readdir($dir)) !== false) {
		
				if(is_file($file)) {
					
					self::deleteFile($file);
					
				}
			
			}
			
			closedir($dir);
			
		}

	}
	
}

?>