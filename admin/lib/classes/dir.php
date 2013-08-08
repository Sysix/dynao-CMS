<?php

class dir {
	
	static $base = '../';
	
	public function __construct() {
		
		self::$base = $_SERVER['DOCUMENT_ROOT'].str_replace(DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'index.php', '', $_SERVER['PHP_SELF']);
		
			
	}
	
	public static function base($file = '') {
		
		return self::$base.DIRECTORY_SEPARATOR.$file;
		
	}
	
	public static function backend($file = '') {
	
		return self::base('admin'.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function template($file = '') {
	
		return self::base('templates'.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function classes($file = '') {
	
		return self::backend('lib'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function functions($file = '') {
	
		return self::backend('lib'.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function page($file = '') {
	
		return self::backend('pages'.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function cache($file = '') {
	
		return self::backend('generated'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function logs($file = '') {
	
		return self::backend('generated'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function lang($lang, $file = '') {
	
		return self::backend('lib'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.$file);
		
	}
	
}

?>