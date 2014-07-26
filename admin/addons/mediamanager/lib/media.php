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
		
		return is_a($media, __CLASS__);
		
	}
	
	// param	string	$name
	// param	mixed	$default
	// return	string
	public function get($name, $default = null) {
		
		return $this->sql->get($name, $default);
				
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
	
	public function isVideo() {
	
		self::getExtensionList();
		
		return in_array($this->getExtension(), self::$extension['video']);
		
	}
	
	public function isAudio() {
		
		self::getExtensionList();
		
		return in_array($this->getExtension(), self::$extension['audio']);
		
	}
	
	public function getIcon() {
		
		if($this->isImage()) {
			return '<img src="'.$this->getPath().'" alt="'.$this->get('filename').'" />';
		}
		
		if($this->isVideo()) {
			return '<i class="fa fa-file-video-o"></i>';
		}
		
		if($this->isAudio()) {
			return '<i class="fa fa-file-audio-o"></i>';
		}
		
		return '<i class="fa fa-file"></i>';
		
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
			
			self::$extension = dyn::get('addons')['mediamanager']['extensions'];
		
		}
		
	}
	
	
	
}

?>