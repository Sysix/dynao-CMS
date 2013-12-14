<?php

abstract class vars {

	const dyn_rex = 'DYN_([A-Z]*)\[(\d+)\]';
	const out_rex = 'OUT_([A-Z]*)\[(\d+)\]';
	abstract public $counts;
	abstract public $DynType;
	
	public $content;
	public $dynVars = [];
	public $outVars = [];
	
	public function __construct($content) {
	
		$this->setMatches($content);
		
	}
	
	abstract public function getOutValue($sql);
	
	public function addToSql($sql) {
		
			
		$array = type::post($this->getTypeValue('DYN'), 'array', []);
		
		foreach($array as $key=>$count) {
		
			if($count > $this->counts) {
				continue;	
			}
			
			$sqlName = $this->getSqlPrefix($type, $this->dynVars[2][$key]);
			
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
	
	protected function getTypeValue($prefix) {
		
		return $prefix.'_'.$this->DynType;
		
	}
	
	public function setMatches($content) {
	
		preg_match_all('/'.self::dyn_rex.'/', $content, $matches);
		$this->dynVars = $matches;
		
		preg_match_all('/'.self::out_rex.'/', $content, $matches);
		$this->outVars = $matches;
		
	}
	
	public function getContent() {
		
		return $this->content;
			
	}
	
}

?>