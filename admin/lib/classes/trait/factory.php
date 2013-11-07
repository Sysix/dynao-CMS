<?php

trait traitFactory {
	
	public static function factory() {
		
		$class = get_called_class();
		$args = array();
		
		if(extension::has($class.'_CLASS')) {
			$class = extension::get($class.'_CLASS');
		}
		
		if(func_num_args()) {
			$args = func_get_args();
		}
		
		$reflect = new ReflectionClass($class);
		return $reflect->newInstanceArgs($args);
		
	}
	
	public static function addExtension($classname) {
		
		$class = get_called_class();
		
		extension::add($class.'_CLASS', create_function('', 'return "'.$classname.'";'));
		
	}
	
}

?>