<?php

class formButton extends formField {
	
	public function get() {		
		
		$this->addAttribute('name', $this->name);
		$this->addClass('btn');
		
		return '<button'.$this->convertAttr().'>'.$this->value.'</button>';
		
	}
	
}

?>