<?php


class pageArea {

	public $isNew;
	public $sql;
	
	const dyn_rex = 'DYN_(\w*)\[(\d+)\]';
	const out_rex = 'OUT_(\w*)\[(\d+)\]';
	
	static $types = [
		'VALUE' => 15, 
		'LINK' => 10,
		'LINK_LIST' => 10,
		'PHP' => 2
	]; #typen
	
	var $values = [];
	var $out = [];
	
	public function __construct($id) {
		
		if(is_object($id) && is_a($id, 'sql')) {
			
			$this->setSql($id);
			
		} else {
		
			$this->sql = sql::factory();
			$this->sql->query('SELECT * FROM '.sql::table('structure_area').' WHERE id = '.$id)->result();		
		
		}
		
		$this->setNew($this->sql->num() == 0);
	}
	
	public static function addType($name, $count) {
			
		self::$types[$name] = $count;
			
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
	
	public function convertValues($content) {
		
		preg_match_all('/'.self::dyn_rex.'/', $content, $values);
		$this->values = $values;
		
		return $this;
		
	}
	
	public function convertOut($content) {
		
		preg_match_all('/'.self::out_rex.'/', $content, $out);
		$this->out = $out;
		
		return $this;
		
	}
	
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
	
	public function convertSpecialVars($content) {
		
		preg_match_all('/'.self::dyn_rex.'/', $content, $match);
		
		list($content) = extension::get('PAGE_AREA_BEFORE_OUTPUTFILTER', [$content, $match, $this]);
		
		return $this->convertLinkVars($content, $match);
		
	}
	
	public function convertLinkVars($content, $match) {
		
		foreach($match[1] as $key=>$type) {
			
			if(!in_array($type, ['LINK', 'LINK_LIST'])) {
				continue;
			}
			
			if($type == 'LINK') {
				$class = 'formLink';
			} else {
				$class = 'formLinklist';
			}
			
			$num = $match[2][$key]; // Zahl in der Klammer z.B. [1]
			$sqlEntry = strtolower($type).$num; //link1

			$class = new $class($match[0][$key], $this->get($sqlEntry));
						
			$content = str_replace(
			$match[0][$key], // DYN_LINK[1]
			$class->get(),
			$content);
			
		}
		
		return $content;
	}
	
	public function OutputFilter($content, $sql) {
		
		if(!is_object($sql)) // SQL Muss Objekt sein
			return $content;
	
		$this->convertOut($content);
		
		$content = $this->convertSpecialVars($content);
		
		$allowTypes = array_keys(self::$types);
		
		foreach($this->out[1] as  $key=>$type) {
			
			if(!in_array($type, $allowTypes) || $this->out[2][$key] >  self::$types[$type])
				continue;
				
			$value = strtolower($type).$this->out[2][$key]; # value1
			
			$content = str_replace(
			$this->out[0][$key], # OUT_VALUE[1]
			$sql->get($value),
			$content);
					
		}
		
		$content = $this->getEval($content);
		
		return $content;
		
	}
	
}

?>