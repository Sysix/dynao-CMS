<?php

class apiserver {
	
	const VERSION = 'http://api.dynao.de/version.json';
	const NEWS = 'http://api.dynao.de/news.json';
	const ADDON = 'http://api.dynao.de/addons.json';
	const MODULE = 'http://api.dynao.de/module.json';
	
	public static function getFile($file) {
	
		$ch = curl_init($file);
		curl_setopt($ch, CURLOPT_PORT, 80);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla (Statuscheck-Script)');
		curl_setopt($ch, CURLOPT_TIMEOUT, 0);
		curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curl = curl_exec($ch);
		curl_close($ch);
		
		return $curl;
		
	}
	
	public static function getVersionFile() {
		
		return json_decode(self::getFile(self::VERSION), true);
		
	}
	
	public static function getNewsFile() {
		
		return json_decode(self::getFile(self::NEWS), true);
		
	}
	
	public static function getAddonFile() {
		
		return json_decode(self::getFile(self::ADDON), true);
		
	}
	
	public static function getModuleFile() {
		
		return json_decode(self::getFile(self::MODULE), true);
		
	}
	
}

?>