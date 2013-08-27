<?php

class addon {
	
	var $config = array();
	var $name;
	
	public function __construct($addon, $config = true) {
		
		if($config) {
			
			$configfile = dir::addon($addon, 'config.json');
			$this->config = json_decode(file_get_contents($configfile), true);
			print_r($this->config);
		}
		
	}
	
	public function get($name) {
	
		return $this->config[$name];
		
	}
	
}

?>