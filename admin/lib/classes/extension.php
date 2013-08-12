<?php

class extension {
	
	protected static $extensions = array();
	
	
	// Hinzufügen eines Extension (Erweiterung)
	public static function add($name, $function, $position = -1) {
		
		try {
		
			// Funktion vorhanden?
			if(!function_exists($function)) {
			
				throw new Exception(__CLASS__ .'::add erwartet als 2. Parameter eine vorhandene Funktion');
				return false;
				
			}
			
			self::$extensions[$name] = array();
			
			// Funktion hinzufügen zum $name mit der Position $position
			array_splice(self::$extensions[$name], $position, 0, $function);
			return true;
			
		
		} catch (Exception $e) {
			
			echo message::danger($e->getMessage());
				
		}
		
	}
	
	// Überprüfen ob Extension registriert
	public static function has($name, $function) {
		
		if(!function_exists($function)) {
			return false;				
		}
		
		return (isset(self::$extensions[$name][$function]));
		
	}
	
	// Alle Extension ausführen
	public static function get($name, $object = false) {
	
		try {
			
			if(!isset(self::$extensions[$name])) {
				
				throw new Exception(__CLASS__.'::get kann nicht auf die Erweiterung '.$name.' zugreifen');
				return false;
					
			}
		
			$extension = self::$extensions[$name];
			
			foreach($extension as $function) {
				
				$function($object);
				
			}
			
			return true;
			
			
		} catch(Exception $e) {
		
			echo message::danger($e->getMessage());
			
		}
		
	}
	
}

?>