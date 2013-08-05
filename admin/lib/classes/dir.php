<?php

class dir {
	
	static $base = '../';
	
	public function __construct() {
		
		self::$base = $_SERVER['DOCUMENT_ROOT'].str_replace('/admin/index.php', '', $_SERVER['PHP_SELF']);
		
			
	}
	
	public static function base($file = '') {
		
		return self::$base.'/'.$file;
		
	}
	
	public static function backend($file = '') {
	
		return self::base('admin/'.$file);
		
	}
	
	public static function template($file = '') {
	
		return self::base('templates/'.$file);
		
	}
	
	public static function classes($file = '') {
	
		return self::backend('lib/classes/'.$file);
		
	}
	
	public static function page($file = '') {
	
		return self::backend('pages/'.$file);
		
	}
	
	public static function cache($file = '') {
	
		return self::backend('generated/cache/'.$file);
		
	}
	
	public static function logs($file = '') {
	
		return self::backend('generated/logs/'.$file);
		
	}
	
	public static function lang($lang, $file = '') {
	
		return self::backend('lib/lang/'.$lang.'/'.$file);
		
	}
	
}

?>