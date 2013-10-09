<?php

class addon {
	
	var $config = array();
	var $name;
	var $addonConfig;
	
	const INSTALL_FILE = 'install.php';
	const UNINSTALL_FILE = 'uninstlal.php';
	
	public function __construct($addon, $config = true) {
		
		$this->name = $addon;
		
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
	
	public function install() {
		
		$file = dir::addon($this->name, self::INSTALL_FILE);
		if(file_exists($file)) {
			include $file;	
		}
				
	}
	
	public function uninstall() {
		
		$file = dir::addon($this->name, self::UNINSTALL_FILE);
		if(file_exists($file)) {
			include $file;	
		}
				
	}
	
}

?>