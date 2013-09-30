<?php

class media {
	
	public $sql;


	public function __construct($id) {
		
		$this->sql = new sql();
		$this->sql->result('SELECT * FROM '.sql::table('media').' WHERE id='.$id);
		
		if($sql->num() != 1) {
			//throw new Exception('Kein eindeutige Datei gefunden');	
		}
		
	}
	
	// Media nach den Namen finden.
	// param	string	$filename
	// return	object
	static public function getMediaByName($filename) {
	
		$sql = new sql();
		$sql->result('SELECT id FROM '.sql::table('media').' WHERE filename="'.$filename.'"');
		
		if($sql->num() != 1) {
			//throw new Exception('Kein eindeutige Datei gefunden');	
		}
		
		$class = __CLASS__;
		
		return $class($sql->get('id'));
		
	}
	
	// Alle Medien ausgeben, die mit der $extension enden
	// z.B. jpg, png, mp4...
	// param	string	$extension
	// return	array
	static public function getMediaByExtension($extension) {
		
		$returnArray = array();
		$class = __CLASS__;
	
		$sql = new sql();
		$sql->result('SELECT id FROM '.sql::table('media').' WHERE filename LIKE "%.'.$extension.'"');
		while($sql->isNext()) {
			
			$returnArray[] = $class($sql->get('id'));
			
			$sql->next();
		}
		
		return $returnArray;
		
	}
	
	// Schauen ob $media ein Medienobjekt ist oder nicht
	// param	object	$media
	// return	bool
	static public function isValid($media) {
	
		if(is_object($media)) {
		
			$class = get_class($media);
			
			return $class == get_class($this);
			
		}
		
		return false;
		
	}
	
	// param	string	$name
	// param	mixed	$default
	// return	string
	public function get($name, $default) {
		
		$value = $this->sql->get($name);
		
		return (!is_null($value)) ? $value : $default;		
		
	}
	
	// den Dateityp ausgeben
	// return	string
	public function getExtension() {
		
		// .jpg
		$extension = strrchr($this->get('name'), '.');
		
		// jpg
		return substr($extension, 1);
		
	}
	
	
	
}

?>