<?php

class ajax {
	
	static $return = [];
	
	public static function is() {
	
		return type::server('HTTP_X_REQUESTED_WITH', 'string', '') == 'XMLHttpRequest';
		
	}
	
	public static function addReturn($text) {
		
		self::$return[] = $text;
		
	}
	
	public static function getReturn() {
	
		return implode('<br />', self::$return);
		
	}
	
	public static function convertPOST() {
		
		if(ajax::is()) {
			
			parse_str($_POST['ajaxGet'], $post);
			$_POST = array_merge($_POST, $post);
			
		}
		
	}
}

?>