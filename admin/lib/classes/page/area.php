<?php


class pageArea {

	public $isNew;
	public $sql;
	
	const dyn_rex = 'DYN_(\w*)\[(\d+)\]';
	const out_rex = 'OUT_(\w*)\[(\d+)\]';
	
	static $types = [
		'VALUE' => 15, 
		'LINK' => 10,
		'MEDIA' => 10,
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
		
		if($this->isNew) {
			
			return $sql->num()+1 == $this->getSort(); // +1 weil Eintrag noch nicht gespeichert ist
			
		} else {
			
			return $sql->num() == $this->getSort();
			
		}
		
		
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
	
	public function OutputFilter($content, $sql) {
		
		if(!is_object($sql)) // SQL Muss Objekt sein
			return $content;
	
		$this->convertOut($content);
		
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