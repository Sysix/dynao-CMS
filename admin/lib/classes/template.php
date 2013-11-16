<?php

class template {
	
	static $template;
	static $templates = [];
	static $default = [];
	static $defaultTemplate = 'default';
	
	static public function getTemplate() {
		
		return self::$template;
			
	}
	
	static public function getDefaultTemplate() {
		
		return self::$defaultTemplate;
		
	}
	
}

?>
