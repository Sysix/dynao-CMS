<?php

class formCheckbox extends formField {
	
	var $output = [];
	
	public function setChecked($checked) {
		
		if(!is_array($checked)) {
			$checked = explode('|', $checked);
		}
		
		$checked = array_flip($checked);
		
		$this->value = $checked;
		
		return $this;
		
	} 
	
	public function add($name, $value, $attributes = []) {
		
		$attributes['type'] = 'checkbox';
		$attributes['value'] = $name;
		$attributes['name'] = $this->name;
		
		if(isset($this->value[$attributes['value']]))
			$attributes['checked'] = 'checked';
			
		$this->output[$attributes['value']] = ['value'=>$value, 'attr'=>$attributes]; //Name als Key speicher, für Methode del();		
		
		return $this;
			
	}
	
	public function del($name) {	
		
		unset($this->output[$name]);
		
		return $this;
			
	}
	
	public function get() {
		
		$return = '';
		$count = count($this->output);
		foreach($this->output as $val) {
			if($count > 1) {
				$val['attr']['name'] = $this->name.'[]';
			}
			$return .= '<label class="checkbox-inline"><input'.$this->convertAttr($val['attr']).'> '.$val['value'].'</label>';
		}
		
		return $return;
		
	}
	
	
	
}


?>