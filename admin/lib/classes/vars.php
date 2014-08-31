<?php

abstract class vars {

	const dyn_rex = 'DYN_([A-Z_]*)\[(\d+)\]';
	const out_rex = 'OUT_([A-Z_]*)\[(\d+)\]';
	public $counts;
	public $DynType;
	
	public $content;
	public $dynVars = [];
	public $outVars = [];
	
	public function __construct($content = '') {
		
		$this->content = $content;
	
		$this->setMatches();
		
	}
	
	abstract public function getOutValue($sql);
	
	public function addSaveValues($sql) {
		
		$saveArray = type::post('DYN_'.$this->DynType, 'array', []);
		
		foreach($saveArray as $num=>$value) {
			
			if(!$this->isType($this->DynType, $num)) {
				continue;	
			}
			
			if(is_array($value)) {
				$value = '|'.implode('|', $value).'|';	
			}		
			
			$sqlEntry = strtolower($this->DynType).$num;	
			
			$sql->addPost($sqlEntry, $value);
			
		}
		
		return $sql;
		
	}
	
	public function isType($type, $count) {
		
		// Exploden da man mehre Out-Varianten machen kann (HTML, IS, ID, ..)
		$type = explode('_', $type);
		
		if(!in_array($this->DynType, $type)) {
			return false;
		}
		
		if($count > $this->counts) {
			return false;
		}
		
		return true;
		
	}
	
	public function addToSql($sql) {		
			
		$array = type::post('DYN_'.$this->DynType, 'array', []);
		
		foreach($array as $key=>$count) {
		
			if($count > $this->counts) {
				continue;	
			}
			
			$sqlName = $this->getSqlPrefix($this->DynType, $this->dynVars[2][$key]);
			
			$sql->addPost($sqlName, $this->dynVars[0][$key]);
		
		}
	
		return $sql;
		
	}
	
	protected function getSqlPrefix($type, $id) {
	
		return strtolower($type).$id;
		
	}
	
	public function setCounts($counts) {
	
		$this->counts = (int)$counts;
		
	}
	
	public function setMatches() {
	
		preg_match_all('/'.self::dyn_rex.'/', $this->content, $matches);
		$this->dynVars = $matches;
		
		preg_match_all('/'.self::out_rex.'/', $this->content, $matches);
		$this->outVars = $matches;
		
	}
	
	public function getContent() {
		
		return $this->content;
			
	}
	
}

?>