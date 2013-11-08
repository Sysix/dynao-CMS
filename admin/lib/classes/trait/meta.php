<?php

trait traitMeta {
	
	protected static $classMethodsStatic = array();
	protected $classMethods = array();
	
	public static function addClassMethod($name, $method) {
		
		if(!is_callable($method)) {
			throw new Exception(__CLASS__.'::'.__METHOD__.' erwartet als 2 Parameter eine aufrufbare Funktion');
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
		
		throw new Exception(__CLASS__.' besitzt die Methode '.$name.' nicht');
		
	}
	
}

?>