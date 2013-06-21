<?php

// Klasse zu Erstellung für Formulare
class form {
	
	
	var $method;
	var $action;
	
	var $sql;
	
	var $mode = 'new';
	
	var $return = array();
	
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
	function get($value, $default = false) {
		
		if($this->sql->get($value))	{
		
			return $this->sql->get($value);
			
		}
		
		return $default;
		
	}
	
	// Ein Element hinzufügen
	private function addField($name, $value, $class, $attributes = array()) {
		
		$field =  new $class($name, $value, $attributes);
		$this->return[] = $field;
		
		return $field;
		
	}
	
	public function addTextField($name, $value, $attributes = array()) {
		
		return $this->addField($name, $value, 'formText', $attributes);
		
	}
	
	public function addTextareaField($name, $value, $attributes = array()) {
		
		return $this->addField($name, $value, 'formTextarea', $attributes);
		
	}
	
	// Mode setzten
	function setMode($mode) {
	
		if(in_array($mode, array('new', 'edit'))) {
			
			$this->mode = $mode;
				
		} else {
			
			// new Exception();	
			
		}
		
	}
	
	function show() {
		
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
		
		$return .= '</table>'.PHP_EOL;
		$return .= '</form>';
		
		return $return;
		
	}
	
}

?>