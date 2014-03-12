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
	
	public function installBlocks($update = false) {
		
		$blocks = sql::factory();
		$blocks->setTable('blocks');
		
		$modul = sql::factory();
		$modul->setTable('module');
	
		foreach($this->get('blocks', []) as $name=>$block) {
			
			$blockExists = $blocks->num('SELECT id FROM '.sql::table('blocks').' WHERE `name` = "'.$name.'" AND `template` = "'.$this->name.'"');
			
			if(!$update && $blockExists) {
				continue;
			}
			
			$modul->addPost('name', $name);
			$modul->addPost('input', $block['input']);
			$modul->addPost('output', $block['output']);
			
			if(!$blockExists) {
				$modul->save();
				$modul_id = $modul->insertId();			
			} else {
				$modul->setWhere('name="'.$name.'"');
				$modul->update();
				
				$modul->select('id')->result();
				$modul_id = $modul->get('id');
			}
			
			$blocks->addPost('name', $name);
			$blocks->addPost('description', $block['description']);
			$blocks->addPost('template', $this->name);
			$blocks->addPost('modul', $modul_id);
				
			if(!$blockExists) {
				$blocks->save();
			} else {
				$blocks->setWhere('name="'.$name.'" AND template="'.$this->name.'"');
				$blocks->update();
			}
			
		}
		
	}
	
	public function install($update = false) {
		
		if(!$this->checkNeed()) {
			return false;	
		}
		
		$this->installBlocks($update);
		
		return true;
				
	}
	
	public function getTemplates($name, $selected = null) {
		
		$select = formSelect::factory($name, $selected);
		
		foreach($this->get('views', $this->get('templates', ['Default'=>'index.php'])) as $name=>$file) {
		
			$select->add($file, $name);
			
		}
			
		return $select;
			
			
	}
	
}

?>
