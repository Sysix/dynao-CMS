<?php

class formLink extends formField {
	
	public function getSaveValue() {
		
		$name = $this->getName();
		$value = type::super($name.'_id');
				
		return $value;
		
	}
	
	public function get() {
		
		$this->addAttribute('name', $this->name);
		$this->addAttribute('value', $this->value);
		$this->addAttribute('type', 'text');
		$this->addClass('form-control');		
		
		return '
		<div class="input-group dyn-link">
			<span class="input-group-addon dyn-link-add"><i class="fa fa-bars" title="Link auswählen"></i></span>
			<input type="hidden" name="'.$this->name.'_id">
			<input'.$this->convertAttr().'>
			<span class="input-group-addon dyn-link-del"><i class="fa fa-minus-square" title="Feld leeren"></i></span>
		</div>';
		
	}
	
}

?>