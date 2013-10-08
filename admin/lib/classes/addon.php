<?php

class addon {
	
	var $config = array();
	var $name;
	var $addonConfig;
	
	public function __construct($addon, $config = true) {
		
		if($config) {
			
			$configfile = dir::addon($addon, 'config.json');
			$this->config = json_decode(file_get_contents($configfile), true);
		}
		
		$this->addonConfig = new addonConfig($addon);
		
	}
	
	public function get($name) {
	
		return $this->config[$name];
		
	}
	
	public function isInstall() {
	
		return $this->addonConfig->isInstall();
		
	}
	
	public function isActive() {
	
		return $this->addonConfig->isActive();	
		
	}
	
}

?>