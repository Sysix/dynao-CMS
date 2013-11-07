<?php

trait traitMeta {
	
	protected static $classMethods = array();
	
	public static function addClassMethod($name, $method) {
		
		if(!is_callable($method)) {
			throw new Exception(__CLASS__.'::'.__METHOD__.' erwartet als 2 Parameter eine aufrufbare Funktion');
		}
		
		self::$classMethods[$name] = Closure::bind($method, null, get_class());
		
	}
	
	public function __call($name, array $args) {
		
		if(isset(self::$classMethods[$name])) {
			return call_user_func_array(self::$classMethods[$name], $args);
		}
		
		throw new Exception(__CLASS__.' bensitzt die Methode '.$name.' nicht');
		
	}
	
}

?>