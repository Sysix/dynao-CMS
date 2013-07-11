<?php

class formSelect extends formField {
	
	var $currentOpt = 0;
	
	var $output = array();
	
	
	public function setSelected($selected) {
		
		if(!is_array($selected)) {
			$selected = explode('|', $selected);
		}
		
		$selected = array_flip($selected);
		
		$this->value = $selected;
		
	}
	
	public function setSize($size) {
		
		$this->addAttribute('size', $size);
		
	}
	
	public function setMultiple($multiple = true) {
		
		if ($multiple) {
			$this->addAttribute('multiple', 'multiple');
		} else {
			$this->delAttribute('multiple');
		}
		
	}
	
	public function addGroup($name, $attributes = array()) {
	
		$attributes['label'] = $name;
	
		$this->currentOpt++;
		$this->output[$this->currentOpt] = array('attr'=>$attributes, 'option'=>array());
		
	}
	
	public function add($name, $value, $attributes = array()) {
		
		$attributes['value'] = $name;
		
		if(isset($this->value[$attributes['value']]))
			$attributes['selected'] = 'selected';
		
		$this->output[$this->currentOpt]['option'][] = array('name'=>$value, 'attr'=>$attributes);
		
	}
	
	protected function getOptions($options) {
	
		if(!is_array($options)) {
			//new Exception();
		}
		
		$return = '';
		
		foreach($options as $option) {
			$return .= '<option '.$this->convertAttr($option['attr']).'>'.$option['name'].'</option>'.PHP_EOL;
		}
		
		return $return;
		
	}
	
	public function get() {
		
		$attributes = $this->attributes;
		$attributes['name'] = $this->name;
		
		if($this->hasAttribute('multiple')) { 
			$attributes['name'] .= '[]';
		}
		
		$haveGroups = ($this->currentOpt !== 0);
		
		$return = '<select '.$this->convertAttr($attributes).'>'.PHP_EOL;
		
		foreach($this->output as $group) {
		
			if($haveGroups) {
				$return .= '<optgroup '.$this->convertAttr($group['attr']).'>'.PHP_EOL;	
			}
			
			$return .= $this->getOptions($group['option']);
			
			if($haveGroups) {
				$return .= '</optgroup>'.PHP_EOL;	
			}
			
		}
		
		$return .= '</select>'.PHP_EOL;
		
		return $return;
		
	}
	
	
}

?>