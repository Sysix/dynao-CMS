<?php

trait traitMeta {
	
	public static $classMethodsStatic = [];
	public $classMethods = [];
	
	public static function addClassMethod($name, $method) {
		
		if(!is_callable($method)) {
			throw new Exception(sprintf(lang::get('traitmeta_callable_func'), __CLASS__, __METHOD__));
		}
		
		self::$classMethodsStatic[$name] = $method;
		
	}
	
	public function setClassMethod($name) {
		
		if(isset(self::$classMethodsStatic[$name])) {
			$this->classMethods[$name] = Closure::bind(self::$classMethodsStatic[$name], $this, get_class());		
		}
		
	}
	
	public function __call($name, array $args) {
		
		if(!isset($this->classMethods[$name])) {
			$this->setClassMethod($name);
		}
		
		if(isset($this->classMethods[$name])) {
			return call_user_func_array($this->classMethods[$name], $args);
		}
		
		throw new Exception(sprintf(lang::get('traitmeta_not_exists'), __CLASS__, $name));
		
	}
	
}

?>