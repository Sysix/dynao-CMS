<?php

class validator {
	use traitFactory;
	
	protected $types = array();
	protected $message;
	
	/**
	 * Ein Validator hinzufügen
	 *
	 * @param	string	$type			Der Validatortype (eine Methode von der Klasse)
	 * @param	string	$message		Die Fehlermeldung, die ausgeben werden soll
	 * @param	mixed	$option			Zusätzlicher Parameter für $type
	 * @return	this
	 */
	public function add($type, $message = null, $option = null) {
		
		if(!method_exists($this, $type)) {
			throw new Exception(__CLASS__.'::'.__METHOD__.' erwartet als $type eine vorhandene Methode');	
		}
		
		$this->types[] = array('type'=>$type, 'message'=>$message, 'option'=>$option);
		
		return $this;
		
	}
	
	/**
	 * Durchläuft alle Methoden die Registriert sind durch ::add hinzugefügt worden sind
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @return	bool
	 */
	public function isValid($value) {
	
		foreach($this->types as $type) {
			
			if(!$this->$type['type']($value, $type['option'])) {
				$this->message = $type['message'];
				return false;
			}
			
		}
		
		return true;
		
	}
	
	/**
	 * Fehlermeldung ausgeben
	 *
	 * @return	string
	 */
	public function getError() {
	
		return $this->message;
		
	}
	
	/**
	 * Nach seinen Type checken
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @param	string	$type			int/string/float
	 * @return	bool
	 */
	public function type($value, $type) {
	
		switch($type) {
			case 'int':
			case 'integer':
				return $this->match($value, '/^\d+$/');
				
			case 'float':
			case 'real':
				return is_numeric($value);
				
			case 'string':
				return $this->match($value, '/^[a-zA-Z]+$/');
				
			default:
				throw new Exception(__CLASS__.'::'.__METHOD__.' erwartet int/float/string als $type');			
		}
		
	}
	
	/**
	 * Überprüfen ob Wert leer ist
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @return	bool
	 */
	public function notEmpty($value) {
	
		return strlen($value) !== 0;
		
	}
	
	/**
	 * Den Wert mit RegEx überprüfen
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @param	string	$regex			Die Regex Funktion
	 * @return	bool
	 */
	public function match($value, $regex) {
	
		return (bool)preg_match($regex, $value);
		
	}
	
	/**
	 * Den Wert mit RegEx überprüfen
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @param	string	$regex			Die Regex Funktion
	 * @return	bool
	 */
	public function notMatch($value, $regex) {
	
		return !$this->match($value, $regex);
		
	}
	
	/**
	 * Überprüfen ob der Wert die Mindestlänge erreicht
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @param	int		$min			Mindestlänge
	 * @return	bool
	 */
	public function minLength($value, $min) {
	
		return mb_strlen($value) >= $min;
		
	}
	
	/**
	 * Überprüfen ob der Wert die Maximallänge nicht erreicht
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @param	int		$man			Maximallänge
	 * @return	bool
	 */
	public function maxLength($value, $max) {
	
		return mb_strlen($value) <= $max;
		
	}
	
	/**
	 * Überprüfen ob der Wert größer als $min ist
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @param	int		$min			
	 * @return	bool
	 */
	public function min($value, $min) {
	
		return $value >= $min;
		
	}
	
	/**
	 * Überprüfen ob der Wert kleiner als $max ist
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @param	int		$max			
	 * @return	bool
	 */
	public function max($value, $max) {
		
		return $value <= $max;
		
	}
	
	/**
	 * Überprüfen ob der Wert gleich einen anderen Wert ist
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	  * @param	string	$value2			Der Wert der er haben sollte			
	 * @return	bool
	 */
	public function is($value, $value2) {
		
		return $value == $value2;
		
	}
	
	/**
	 * Überprüfen ob der Wert eine Email ist
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @return	bool
	 */
	public function email($value) {
		
		return filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_FLAG_SCHEME_REQUIRED);
		
	}
	
	
	/**
	 * Überprüfen ob der Wert eine URL ist
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @return	bool
	 */
	public function url($value) {
		
		return $this->match($value, '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i');
		
	}
	
	/**
	 * Überprüfen des Wertes durch eine eigene Funktion
	 *
	 * @param	string	$value			Der zu überprüfende Wert
	 * @param	callable $callback		Die Funktion/Methode
	 * @return	bool
	 */
	public function costum($value, $callback) {
	
		return $callback($value);
		
	}
	
}

?>