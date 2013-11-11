<?php

class extension {
	
	protected static $extensions = [];
	
	
	/**
	 * Fügt eine Erweiterung hinzu
	 *
	 * @param	string	$name			Der Name der Erweiterung
	 * @param	string	$function		Die Funktion die auf die Erweiterung zugreift
	 * @param	int		$position		Die Position wann die Funktion aufgerufen werden soll
	 *
	 */
	public static function add($name, $function, $position = -1) {
		
		try {
		
			
			if(!is_callable($function)) {
			
				throw new Exception(__CLASS__ .'::add erwartet als 2. Parameter eine vorhandene Funktion');
				return false;
				
			}
			
			self::$extensions[$name] = [];
			
			if($position < 0) {
				$position = count(self::$extensions[$name]);
			}
			
			// Funktion hinzufügen zum $name mit der Position $position
			array_splice(self::$extensions[$name], $position, 0, $function);
			return true;
			
		
		} catch (Exception $e) {
			
			echo message::danger($e->getMessage());
				
		}
		
	}
	
	/**
	 * Überprüft ob eine Erweiterung vorhanden ist
	 *
	 * @param	string	$name			Der Name der Erweiterung
	 * @param	string	$function		Die Funktion die auf die Erweiterung zugreift
	 * @return	bool
	 *
	 */
	public static function has($name, $function = false) {
		
		if(!$function) {
			return isset(self::$extensions[$name]);
		}
		
		if(!function_exists($function)) {
			return false;				
		}
		
		return isset(self::$extensions[$name][$function]);
		
	}
	
	/**
	 * Alle Erweiterung ausführen
	 *
	 * @param	string	$name			Der Name der Erweiterung
	 * @param	mixed	$object			Das Objekt (kann eine Variable, Objekt, ...) sein
	 * @return	mixed
	 *
	 */
	public static function get($name, $object = false) {
	
			
		if(!self::has($name)) {
			
			return $object;
				
		}
		
		$extension = self::$extensions[$name];
		
		foreach($extension as $function) {
			
			$object = $function($object);
			
		}
		
		return $object;
	}
	
}

?>