<?php

class template {
	use traitFactory;
    use traitNeed;
	
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

	
	public function installModule($module, $update = false) {
		
		$modulSql = sql::factory();
		$modulSql->setTable('module');
		
		foreach($module as $modulName=>$modul) {
				
			$modulExists = $modulSql->num('SELECT id FROM '.sql::table('module').' WHERE `name` = "'.$modulName.'"');
			
			if(!$update && $modulExists) {
				continue;	
			}
			
			$modulSql->addPost('name', $modulName);
			$modulSql->addPost('input', $modul['input']);
			$modulSql->addPost('output', $modul['output']);
			
			if(isset($modul['blocks'])) {
				$modulSql->addPost('blocks', $modul['blocks']);
			} else {
				$modulSql->addPost('blocks', 1);
			}
			
			if(!$modulExists) {
				
				$modulSql->save();
				return $modulSql->insertId();
				
			} else {
				
				$modulSql->setWhere('name="'.$modulName.'"');
				$modulSql->update();
				
				$modulSql->select('id')->result();
				return $modulSql->get('id');
				
			}
			
				
		}
		
	}
	
	public function installBlocks($update = false) {
		
		$blocks = sql::factory();
		$blocks->setTable('blocks');
	
		foreach($this->get('blocks', []) as $name=>$block) {
			
			$blockExists = $blocks->num('SELECT id FROM '.sql::table('blocks').' WHERE `name` = "'.$name.'" AND `template` = "'.$this->name.'"');
			
			if(!$update && $blockExists) {
				continue;
			}
			
			$this->installModule($block['module'], $update);
			
			$blocks->addPost('name', $name);
			$blocks->addPost('description', $block['description']);
			$blocks->addPost('template', $this->name);
				
			if(!$blockExists) {
				$blocks->save();
			} else {
				$blocks->setWhere('name="'.$name.'" AND template="'.$this->name.'"');
				$blocks->update();
			}
			
		}
		
	}
	
	public function install($update = false) {

        try {

            if(!$this->checkNeed()) {
                return false;
            }

            $this->installBlocks($update);

        } catch (mysqli_sql_exception $e) {

            echo message::warning('<b>SQL Error:<br />'.$e->getMessage());

        } catch(Exception $e) {

            echo message::danger($e->getMessage());

        }
		
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
