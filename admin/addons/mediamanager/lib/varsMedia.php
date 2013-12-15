<?php

class varsMedia extends vars {
	
	public $counts = 10;
	public $DynType = 'MEDIA';
	
	public function getDynValue($sql) {
	
		foreach($this->dynVars[1] as $key=>$type) {
			
			$num = $this->dynVars[2][$key];
			
			if(!$this->isType($type, $num)) {
				continue;	
			}
			
			$sqlEntry = strtolower($type).$num; //media1
			
			$class = formMedia::factory($this->dynVars[0][$key], $sql->get($sqlEntry));
			
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
			
			//DYN_MEDIA_ID bleibt unberührt
			if($type == $this->DynType.'_ID') {
				//nothing
			} else {
				$class = new media($sqlEntry);
				$sqlEntry = $class->get('filename');
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