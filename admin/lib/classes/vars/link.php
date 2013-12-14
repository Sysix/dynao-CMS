<?php

class varsLink extends vars {
	
	public $counts = 10;
	public $DynType = 'LINK';
	
	public function getOutValue($sql) {
		
		foreach($this->outVars[1] as $key=>$type) {
			
			if($this->outVars[2][$key] > $this->counts) {
				continue;	
			}
			
			$sqlEntry = strtolower($this->DynType).$this->dynVars[2][$key];	
			$sqlEntry = $sql->get($sqlEntry);
			
			// DYN_LINK_ID aufgerufen wird	
			if($type != $this->DynType.'_ID') {
				$sqlEntry = url::fe($sqlEntry);
			} 
			
			str_replace(
			$this->outVars[0][$key],
			$sqlEntry,
			$this->content
			);
			
		}
		
		return $this;
		
	}
	
	
}

?>