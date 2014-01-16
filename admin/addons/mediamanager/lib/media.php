<?php

class media {
	use traitFactory;
	use traitMeta;
	
	public $sql;
	public static $extension;


	public function __construct($id) {
		
		if(is_object($id) && is_a($id, 'sql')) {
			
			$this->sql = $id;
			
		} else {
		
			$this->sql = sql::factory();
			$this->sql->result('SELECT * FROM '.sql::table('media').' WHERE id='.$id);
			
		}
		
		
	}
	
	// Media nach den Namen finden.
	// param	string	$filename
	// return	object
	static public function getMediaByName($filename) {
	
		$sql = sql::factory();
		$sql->result('SELECT * FROM '.sql::table('media').' WHERE filename="'.$filename.'"');
		
		if($sql->num() != 1) {
			//throw new Exception('Kein eindeutige Datei gefunden');	
		}
		
		$class = __CLASS__;
		
		return new $class($sql);
		
	}
	
	// Alle Medien ausgeben, die mit der $extension enden
	// z.B. jpg, png, mp4...
	// param	string	$extension
	// return	array
	static public function getMediaByExtension($extension) {
		
		$returnArray = [];
		$class = __CLASS__;
	
		$sql = sql::factory();
		$sql->result('SELECT * FROM '.sql::table('media').' WHERE filename LIKE "%.'.$extension.'"');
		while($sql->isNext()) {
			
			$returnArray[] = new $class($sql);
			
			$sql->next();
		}
		
		return $returnArray;
		
	}
	
	// Schauen ob $media ein Medienobjekt ist oder nicht
	// param	object	$media
	// return	bool
	static public function isValid($media) {
		
		if(!is_object($media))
			return false;
		
		return is_a($media, get_class($this));
		
	}
	
	// param	string	$name
	// param	mixed	$default
	// return	string
	public function get($name, $default = null) {
		
		$value = $this->sql->get($name);
		
		return (!is_null($value)) ? $value : $default;		
		
	}
	
	// den Dateityp ausgeben
	// return	string
	public function getExtension() {
		
		// .jpg
		$extension = strrchr($this->get('filename'), '.');
		
		// jpg
		return substr($extension, 1);
		
	}
	
	public function isImage() {
		
		self::getExtensionList();
		
		return in_array($this->getExtension(), self::$extension['image']);
		
	}
	
	// Den Pfad der Datei ausgeben
	// return	string
	public function getPath() {
		
		if(dyn::get('backend')) {
			return '../media/'.$this->get('filename');
		} else {
			return 'media/'.$this->get('filename');
		}
	}
	
	public static function getExtensionList() {
		
		if(is_null(self::$extension)) {
		
			$media = json_decode(file_get_contents(dir::addon('mediamanager', 'config.json')), true);
			
			self::$extension = $media['extensions'];
		
		}
		
	}
	
	
	
}

?>