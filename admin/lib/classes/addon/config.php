<?php

class addonConfig {
	
	var $sql;
	
	public function __construct($addon) {
		
		$this->isSaved($addon);
		$this->sql = new sql();
		$this->sql->query('SELECT * FROM '.sql::table('addons').' WHERE `name` = "'.$addon.'"')->result();
		
	}
	
	public function get($name, $default = 0) {
	
		return $this->sql->get($name, $default);
		
	}
	
	public function isOnline() {
		
		return ($this->get('online', 0) == 1);
			
	}
	
	public function isActive() {
		
		return ($this->get('active', 0) == 1);
		
	}
	
	public function isSaved($addon, $save = true) {
	
		$sql = new sql();
		$num = $sql->num('SELECT 1 FROM '.sql::table('addons').' WHERE `name` = "'.$addon.'"');
		if(!$num && $save) {
			$save = new sql();
			$save->setTable('addons');
			$save->addPost('name', $addon);
			$save->save();
		}
		
		return $num;
		
	}
	
	public static function includeAllConfig() {
	
		$sql = new sql();
		$sql->query('SELECT name FROM '.sql::table('addons').' WHERE `online` = 1 AND `active` = 1')->result();
		while($sql->isNext()) {

			$configFile = dir::addon($sql->get('name'), 'config.php');
			include_once($configFile);
			
			$sql->next();	
		}
		
	}
	
}

?>