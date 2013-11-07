<?php

trait traitMeta {
	
	protected static $classMethods_static = array();
	protected $classMethods = array();
	
	public static function addClassMethod($name, $method) {
		
		if(!is_callable($method)) {
			throw new Exception(__CLASS__.'::'.__METHOD__.' erwartet als 2 Parameter eine aufrufbare Funktion');
		}
		
		self::$classMethods_static[$name] = $method;
		
	}
	
	public function setClassMethod() {
	
		if(!empty(self::$classMethods_static)) {
		
			foreach(self::$classMethods_static as $name=>$method) {
				$this->classMethods[$name] = Closure::bind($method, $this, get_class());
			}
			
		}
		
	}
	
	public function __call($name, array $args) {
		
		if(isset($this->classMethods[$name])) {
			return call_user_func_array($this->classMethods[$name], $args);
		}
		
		throw new Exception(__CLASS__.' bensitzt die Methode '.$name.' nicht');
		
	}
	
}

?>