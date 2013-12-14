<?php

class varsValue extends vars {
	
	public $counts = 15;
	public $DynType = 'VALUE';
	
	public function getOutValue($sql) {
		
		foreach($this->outVars[1] as $key=>$type) {
			
			if($this->outVars[2][$key] > $this->counts) {
				continue;	
			}
			
			$sqlEntry = strtolower($this->DynType).$this->dynVars[2][$key];	
			$sqlEntry = $sql->get($sqlEntry);
			
			// DYN_HTML_VALUE bleibt unberührt
			if($type == $this->DynType) {
				$sqlEntry = htmlspecialchars($sqlEntry);
			}
			
			if($type == 'IS_'.$this->DynType) {
				$sqlEntry = ($sqlEntry != '');
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