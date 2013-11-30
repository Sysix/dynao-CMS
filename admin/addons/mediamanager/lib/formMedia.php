<?php

class formMedia extends formField {
	
	public function __construct($name, $value) {
		
		parent::__construct($name, $value);
		$this->value = type::super($name.'_id', '', $this->value);
			
	}
	
	public function getSaveValue() {
		
		$name = $this->getName();
		$value = type::super($name.'_id');
				
		return $value;
		
	}
	
	public function get() {
		
		if($this->value != '') {
			$media = media::factory($this->value);
			$this->addAttribute('value', $media->get('filename'));
		}
		
		$this->addAttribute('disabled', 'disabled');
		$this->addAttribute('name', $this->name);
		
		$this->addAttribute('type', 'text');
		$this->addAttribute('placeholder', 'Medium auswählen...');
		$this->addClass('form-control');
		
		return '
		<div class="input-group dyn-media">
			<span class="input-group-addon"><i class="fa dyn-media-add" title="Medium auswählen">=</i></span>
			<input type="hidden" name="'.$this->name.'_id" value="'.$this->value.'">
			<input'.$this->convertAttr().'>
			<span class="input-group-addon"><i class="fa fa-minus-square-o  dyn-media-del" title="Feld leeren"></i></span>
		</div>';
		
	}
	
}

?>