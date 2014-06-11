<?php

class dir {

	static $base = '../';

	public function __construct($dir = '../') {
		
		self::$base = realpath($dir);
		
	}

	public static function base($file = '') {

		return self::$base.DIRECTORY_SEPARATOR.$file;

	}

	public static function backend($file = '') {

		return self::base('admin'.DIRECTORY_SEPARATOR.$file);

	}

	public static function template($template = '', $file = '') {
		
		if($template == '') {
			
			return self::base('templates'.DIRECTORY_SEPARATOR);
			
		}

		return self::base('templates'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function media($file = '') {

		return self::base('media'.DIRECTORY_SEPARATOR.$file);

	}

	public static function classes($file = '') {

		return self::backend('lib'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function vendor($file = '') {

		return self::backend('lib'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.$file);

	}

	public static function functions($file = '') {

		return self::backend('lib'.DIRECTORY_SEPARATOR.'functions'.DIRECTORY_SEPARATOR.$file);

	}

	public static function page($file = '') {

		return self::backend('pages'.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function generated($file = '') {

		return self::backend('generated'.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function cache($file = '') {

		return self::generated('cache'.DIRECTORY_SEPARATOR.$file);

	}

	public static function lang($lang, $file = '') {

		return self::backend('lib'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR.$file);

	}
	
	public static function addon($addon, $file = '') {

		return self::backend('addons'.DIRECTORY_SEPARATOR.$addon.DIRECTORY_SEPARATOR.$file);

	}

}

?>