<?php


class pageArea {

	public $isNew;
	public $sql;
	
	public static $types = [
		'varsValue',
		'varsLink',
		'varsLinklist',
		'varsPhp',
	];
		

	public function __construct($id) {
		
		if(is_object($id) && is_a($id, 'sql')) {
			
			$this->setSql($id);
			
		} else {
		
			$this->sql = sql::factory();
			$this->sql->query('SELECT * FROM '.sql::table('structure_area').' WHERE id = '.$id)->result();		
		
		}
		
		$this->setNew($this->sql->num() == 0);
	}
	
	public static function addType($class) {
		
		if(!class_exists($class, false)) {
			//throw new Exception(PageArea-Typ muss eine Klasse sein	
		}
		self::$types[] = $class;
			
	}
	
	public function setNew($new) {
	
		$this->isNew = $new;
		
		return $this;
		
	}
	
	public function setSql(sql $sql) {
		
		$this->sql = $sql;
		
		return $this;
				
	}
	
	public function get($value) {
		
		if($this->isNew)
			return;
			
		return $this->sql->get($value);
		
	}
	
	public function getSql() {
		
		return $this->sql;
			
	}
	
	public function getModulId() {
		
		if($this->isNew) {
			
			return type::super('modul', 'int');
			
		} else {
	
			return $this->get('modul');
			
		}
		
	}
	
	public function getSort() {
		
		if($this->isNew) {
	
			return type::super('sort', 'int');
			
		} else {
		
			return $this->get('sort');
			
		}
		
	}
	
	public function getId() {
	
		return ($this->isNew) ? 0 : $this->get('id');
		
	}
	
	public function getStructureId() {
	
		if($this->isNew) {
			
			return type::super('structure_id', 'int');
			
		} else {
			
			return $this->get('structure_id');
			
		}
		
	}
	
	public function isOnline() {
	
		return $this->get('online') == 1;
		
	}
	
	public function isFirstBlock() {
		
		return $this->getSort() == 1;
		
	}
	
	public function isLastBlock() {
		
		$sql = sql::factory();
		$sql->query('SELECT sort FROM '.sql::table('structure_area').' WHERE structure_id = '.$this->getStructureId());
			
		return $sql->num() == $this->getSort();
		
		
	}	
	
	////////////////////// Eingabe/Ausgabe Dynamisch machen
	
	protected function getEval($content) {
		
		ob_start();
		ob_implicit_flush(0);
		
		// PHP-Startzeichen  im Code verwenden können
		$content = eval(' ?>'.$content.' <?php ');
		
		// Falls irgendein Fehler gekommen ist
		if(false === $content) {
			echo message::danger(lang::get('modul_php_error'));
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
	}
	
	public function OutputFilter($content, $sql) {
		
		if(!is_object($sql)) // SQL Muss Objekt sein
			return $content;
		
		foreach(self::$types as $class) {
			$class = new $class($content);
			$class->getOutValue($sql);
			$content = $class->getContent();
		}
		
		$content = $this->getEval($content);
		
		return $content;
		
	}
	
}

?>