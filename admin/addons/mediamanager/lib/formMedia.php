<?php

class formMedia extends formField {
	
	public function getSaveValue() {
		
		$name = $this->getName();
		$value = type::super($name.'_id');
				
		return $value;
		
	}
	
	public function get() {
		
		$this->addAttribute('disabled', 'disabled');
		$this->addAttribute('name', $this->name);
		$this->addAttribute('value', $this->value);
		$this->addAttribute('type', 'text');
		$this->addAttribute('placeholder', 'Medium auswählen...');
		$this->addClass('form-control');		
		
		return '
		<div class="input-group dyn-media">
			<span class="input-group-addon dyn-media-add"><i class="fa fa-bars" title="Medium auswählen"></i></span>
			<input type="hidden" name="'.$this->name.'_id">
			<input'.$this->convertAttr().'>
			<span class="input-group-addon dyn-media-del"><i class="fa fa-minus-square" title="Feld leeren"></i></span>
		</div>';
		
	}
	
}

?>