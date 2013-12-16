<?php
class form_install extends form {
	
	public function __construct($table = '', $where = '', $action = '', $method = 'post') {
		
		$this->method = $method;
		$this->action = $action;
		
		$this->setSave(false);
		
		if(dyn::get('backend')) {
			$this->loadBackend();
		}
		
		$this->addFormAttribute('action', $this->action);
		$this->addFormAttribute('method', $this->method);
		
		$this->setButtons();
		
		$this->delButton('save-back');
		
		$this->setSuccessMessage(lang::get('form_saved'));
		$this->addParam('action', $this->mode);
		
	}
	
	public function get($value, $default = null) {
		
		// Falls per Post übermittelt		
		return type::post($value);
		
		
	}
	
	public function saveForm() {
		return $this;	
	}
	
	protected function setPostsVar() {
		return $this;	
	}
	
}

?>