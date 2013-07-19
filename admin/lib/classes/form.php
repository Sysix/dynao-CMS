<?php

// Klasse zu Erstellung für Formulare
class form {
	
	
	var $method;
	var $action;
	
	var $sql;
	
	var $mode = 'add';
	
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
		
		$this->sql->result();
			
		if($this->sql->num() == 1) {
			$this->setMode('edit');
		}
			
		
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
				
		$this->addSubmitField('save', lang::get('save'));
		$this->toAction('save');
		
		if($this->isEditMode()) {			
			$this->addSubmitField('save-back', lang::get('apply'));	
			$this->toAction('save-edit');		
		}
		
		$back = $this->addButtonField('save-back', lang::get('back'));
		$back->addAttribute('onlick', 'history.go(-1)');
		
	}
	
	public function addFreeField($name, $value, $class, $attributes = array()) {
	
		return new $class($name, $value, $attributes);
		
	}
	
	// Ein Element hinzufügen
	private function addField($name, $value, $class, $attributes = array()) {
		
		$field = new $class($name, $value, $attributes);
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
	
	public function addSubmitField($name, $value, $attributes = array(), $toButtons = true) {
		
		$attributes['type'] = 'submit';
		$field = $this->addFreeField($name, $value, 'formButton', $attributes);
		if($toButtons) {
			$this->buttons[] = $field;
		}
		return $field;
		
	}
	
	public function addButtonField($name, $value, $attributes = array(), $toButtons = true) {
		
		$attributes['type'] = 'button';
		$field = $this->addFreeField($name, $value, 'formButton', $attributes);
		if($toButtons) {
			$this->buttons[] = $field;
		}
		return $field;
		
	}
	
	public function addResetField($name, $value, $attributes = array(), $toButtons = true) {
		
		$attributes['type'] = 'reset';
		$field = $this->addFreeField($name, $value, 'formButton', $attributes);
		if($toButtons) {
			$this->buttons[] = $field;
		}
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
	
		if(in_array($mode, array('add', 'edit'))) {
			
			$this->mode = $mode;
				
		} else {			
			// new Exception();				
		}
		
	}
	
	// Post parameter Action setzten
	public function toAction($action) {
		
		$this->addHiddenField('action', $action);
		
	}
	
	// Ist Edit Mode?
	public function isEditMode() {
	
		return $this->mode == 'edit';
		
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
		
		
		$this->setButtons();
		
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