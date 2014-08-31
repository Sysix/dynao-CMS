<?php

class varsLink extends vars {
	
	public $counts = 10;
	public $DynType = 'LINK';
	
	public function getDynValue($sql) {
	
		foreach($this->dynVars[1] as $key=>$type) {
			
			$num = $this->dynVars[2][$key];
			
			if(!$this->isType($type, $num)) {
				continue;	
			}
			
			$sqlEntry = strtolower($type).$num; //link1
			
			$class = formLink::factory($this->dynVars[0][$key], $sql->get($sqlEntry));
			
			$this->content = str_replace(
				$this->dynVars[0][$key],
				$class->get(),
				$this->content
			);
			
		}
		
	}
	
	public function getOutValue($sql) {
		
		$this->getDynValue($sql);
		
		foreach($this->outVars[1] as $key=>$type) {
			
			if(!$this->isType($type, $this->outVars[2][$key])) {
				continue;	
			}
			
			$sqlEntry = strtolower($this->DynType).$this->outVars[2][$key];
			$sqlEntry = $sql->get($sqlEntry);
			
			//DYN_LINK_ID bleibt unberührt
			if($type == $this->DynType.'_ID') {
				//nothing
			} else {
				$sqlEntry = url::fe($sqlEntry);
			}
			
			$this->content = str_replace(
				$this->outVars[0][$key],
				$sqlEntry,
				$this->content
			);
			
		}
		
		return $this;
		
	}
	
	
}

?>