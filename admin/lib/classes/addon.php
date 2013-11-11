<?php

class addon {
	
	var $config = [];
	var $name;
	var $addonConfig;
	var $sql;
	
	const INSTALL_FILE = 'install.php';
	const UNINSTALL_FILE = 'uninstall.php';
	
	public function __construct($addon, $config = true) {
		
		$this->name = $addon;
		
		if($config) {
						
			$configfile = dir::addon($addon, 'config.json');
			$this->config = json_decode(file_get_contents($configfile), true);
		}
		
		addonConfig::isSaved($addon);
		
		$this->sql = sql::factory();
		$this->sql->query('SELECT * FROM '.sql::table('addons').' WHERE `name` = "'.$addon.'"')->result();
		
	}
	
	public function getSql($name, $default = null) {
	
		return $this->sql->get($name, $default);
		
	}
	
	public function get($name) {
	
		return $this->config[$name];
		
	}
	
	public function isInstall() {
	
		return $this->getSql('install', 0) == 1;
		
	}
	
	public function isActive() {
	
		return $this->getSql('active', 0) == 1;
		
	}
	
	public function install() {
		
		$file = dir::addon($this->name, self::INSTALL_FILE);
		if(file_exists($file)) {
			include $file;	
		}
		
		return $this;
				
	}
	
	public function uninstall() {
		
		$file = dir::addon($this->name, self::UNINSTALL_FILE);
		if(file_exists($file)) {
			include $file;	
		}
		
		return $this;
				
	}
	
}

?>