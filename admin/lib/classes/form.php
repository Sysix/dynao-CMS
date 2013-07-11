<?php

// Klasse zu Erstellung für Formulare
class form {
	
	
	var $method;
	var $action;
	
	var $sql;
	
	var $mode = 'new';
	
	var $return = array();
	var $buttons = array();
	
	public function __construct($table, $where, $action, $method = 'post') {
		
		// Gültige Methode?		
		if(!in_array($method, array('post', 'get'))) {
			// new Exception();
		}
		
		$this->method = $method;
		$this->action = $action;
		
		$sql = new sql();
		$this->sql = $sql->query('SELECT * FROM '.$table.' WHERE '.$where.' LIMIT 1');
		
		if(sql::$sql->field_count) {
			
			$this->sql->result();
			
			if($this->sql->num() == 1) {
				$this->setMode('edit');
			}
			
		} else {
			//throw new Exception();
		}
		
		$this->setButtons();
		
	}
	
	
	// Ausgabe der SQL Spalte
	// Falls nicht drin, dann $default zurück
	public function get($value, $default = false) {
		
		if($this->sql->get($value))	{
		
			return $this->sql->get($value);
			
		}
		
		return $default;
		
	}
	
	public function setButtons() {
		
		$save = $this->addFreeField('save', 'Speichern', 'formText');
		$save->addAttribute('type', 'submit');
		$this->buttons[] = $save;
		
		if($this->mode == 'edit') {
			
			$save_back = $this->addFreeField('save-back', 'Übernehmen', 'formText');
			$save_back->addAttribute('type', 'submit');
			$this->buttons[] = $save_back;
			
		}
		
		$back = $this->addFreeField('back', 'Zurück', 'formText');
		$back->addAttribute('type', 'button');
		$back->addAttribute('onlick', 'history.go(-1);return true;');
		$this->buttons[] = $back;
		
	}
	
	public function addFreeField($name, $value, $class, $attributes = array()) {
	
		return new $class($name, $value, $attributes);
		
	}
	
	// Ein Element hinzufügen
	private function addField($name, $value, $class, $attributes = array()) {
		
		$field =  $this->addFreeField($name, $value, $class, $attributes);
		$this->return[] = $field;
		
		return $field;
		
	}
	
	public function addTextField($name, $value, $attributes = array()) {
		
		$attributes['type'] = 'text';
		return $this->addField($name, $value, 'formText', $attributes);
		
	}
	
	public function addPasswordField($name, $value, $attributes = array()) {
		
		$attributes['type'] = 'password';
		return $this->addField($name, $value, 'formText', $attributes);
		
	}
	
	public function addHiddenField($name, $value, $attributes = array()) {
		
		$attributes['type'] = 'hidden';
		$field = $this->addFreeField($name, $value, 'formText', $attributes);
		$this->buttons[] = $field;
		return $field;
				
	}
	
	public function addTextareaField($name, $value, $attributes = array()) {
		
		return $this->addField($name, $value, 'formTextarea', $attributes);
		
	}
	
	public function addRadioField($name, $value, $attributes = array()) {
		
		return $this->addField($name, $value, 'formRadio', $attributes);
		
	}
	
	public function addCheckboxField($name, $value, $attributes = array()) {
		
		$field = $this->addField($name.'[]', $value, 'formCheckbox', $attributes);
		$field->setChecked($value);
		return $field; 
		
	}
	
	public function addSelectField($name, $value, $attributes = array()) {
		
		$field = $this->addField($name, $value, 'formSelect', $attributes);
		$field->setSelected($value);
		return $field;
		
	}
	
	public function addRawField($value) {
		
		return $this->addField('', $value, 'formRaw');
		
	}
	
	// Mode setzten
	public function setMode($mode) {
	
		if(in_array($mode, array('new', 'edit'))) {
			
			$this->mode = $mode;
				
		} else {
			
			// new Exception();	
			
		}
		
	}
	
	public function show() {
		
		$return = '<form action="'.$this->action.'" method="'.$this->method.'">'.PHP_EOL;
		$return .= '<table>'.PHP_EOL;
		
		foreach($this->return as $ausgabe) {
		
			$return .= '<tr>'.PHP_EOL;
			
			$return .= '<td>';
			$return .= $ausgabe->fieldName;
			$return .= '</td>'.PHP_EOL;
			
			$return .= '<td>';
			$return .= $ausgabe->prefix . $ausgabe->get() . $ausgabe->suffix;
			$return .= '</td>'.PHP_EOL;
			
			$return .= '</tr>'.PHP_EOL;	
			
		}
		
		$return .='<tr>';
		$return .='<td></td>';
		$return .='<td>';
		foreach($this->buttons as $buttons) {
			$return .= $buttons->get();	
		}
		$return .='</td>';
		$return .='</tr>';
		
		$return .= '</table>'.PHP_EOL;
		$return .= '</form>';
		
		return $return;
		
	}
	
}

?>