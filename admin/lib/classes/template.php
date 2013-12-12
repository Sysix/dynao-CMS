<?php

class template {
	use traitFactory;
	
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
		
		$slots = sql::factory();
		$slots->setTable('slots');
		
		$modul = sql::factory();
		$modul->setTable('module');
	
		foreach($this->get('slots', []) as $name=>$slot) {
			
			if($slots->num('SELECT id FROM '.sql::table('slots').' WHERE `name` = "'.$name.'" AND `template` = "'.$this->name.'"')) {
				continue;
			}
			
			$modul->addPost('name', $name);
			$modul->addPost('input', $slot['input']);
			$modul->addPost('output', $slot['output']);
			$modul->save();
			
			$modul_id = $modul->insertId();
			
			$slots->addPost('name', $name);
			$slots->addPost('description', $slot['description']);
			$slots->addPost('template', $this->name);
			$slots->addPost('modul', $modul_id);
			$slots->save();
			
		}
		
	}
	
	public function install() {
		
		if(!$this->checkNeed()) {
			return false;	
		}
		
		$this->installSlots();
		
		return true;
				
	}
	
	public function getTemplates($name, $selected = null) {
		
		$select = formSelect::factory($name, $selected);
		
		foreach($this->get('templates', ['Default'=>'index.php']) as $name=>$file) {
		
			$select->add($file, $name);
			
		}
			
		return $select;
			
			
	}
	
}

?>
