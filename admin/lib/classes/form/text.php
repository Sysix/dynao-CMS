<?php

class formText extends formField {
	
	public function get() {
		
		$type = $this->getAttribute('intern::type');
		$this->delAttribute('intern::type');
		
		$this->addAttribute('name', $this->name);
		$this->addAttribute('value', $this->value);
		$this->addAttribute('type', $type);
		
		
		return '<input'.$this->convertAttr().' />';
		
	}
	
}

?>