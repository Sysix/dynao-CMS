<?php
class install {
	
	public static function checkChmod($path) {
		
		clearstatcache();
		#self::setChmod($path);
		
		if(self::writeable($path) && self::readable($path))
			return true;
		else
			return false;
    	
	}
	
	protected static function readable($path) {
		
		return is_readable($path);
		
	}
	
	protected static function writeable($path) {
		
		return is_writeable($path);
		
	}
	
	protected static function setChmod($path, $chmod = 0755) {
		
		return chmod($path, $chmod);
		
	}
	
}

?>