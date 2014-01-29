<?php

class formFile extends formField {
	
	public $dir = '';
	public $extension = [];
	public $badExtension = [];
		
	public function setBadExtension($extension) {
		
		$this->badExtension = $extension;
		
		return $this;
		
	}
	
	public function setExtension($extension) {
		
		$this->extension = $extension;
		
		return $this;

	}
	
	public function getFilePath() {	
		
	}
	
	public function setDir($dir) {
		
		$this->dir = $dir;
		
		return $this;
		
	}
	
	public function get() {		
		
		$this->addAttribute('name', $this->name);
		$this->addAttribute('value', $this->value);
		$this->addClass('form-control');
		
		return '<input'.$this->convertAttr().' />';
		
	}
	
}

?>