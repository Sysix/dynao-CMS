<?php

class template {
	
	public $name;
	public $config = [];
	
	public function __construct($template) {
		
		$this->name = $template;
						
		$configfile = dir::template($template, 'config.json');
		$this->config = json_decode(file_get_contents($configfile), true);
		
	}
	
	public function get($name, $default = null) {
		
		if(isset($this->config[$name])) {
			return $this->config[$name];
		}
		
		return $default;
		
	}
	
	public function checkNeed() {		
		
		$errors = [];
		foreach($this->get('need', []) as $key=>$value) {
			
			$check = templateNeed::check($key, $value);
			// Typcheck, because $check can be a string
			if($check !== true) {
				$errors[] = $check;
			}
				
		}
		
		if(!empty($errors)) {
			echo message::danger(implode('<br />', $errors));
			return false;	
		}
		
		return true;
			
	}
	
	public function installSlots() {
		
		$sql = sql::factory();
		$sql->setTable('slots');
	
		foreach($this->get('slots', []) as $slots=>$description) {
			
			if($sql->num('SELECT id FROM '.sql::table('slots').' WHERE `name` = "'.$slots.'" AND `template` = "'.$this->name.'"')) {
				continue;
			}
			
			$sql->addPost('name', $slots);
			$sql->addPost('description', $description);
			$sql->addPost('template', $this->name);
			$sql->save();
			
		}
		
	}
	
	public function install() {
		
		if(!$this->checkNeed()) {
			return false;	
		}
		
		$this->installSlots();
		
		return true;
				
	}
	
}

?>
