<?php

class varsPhp extends vars {
	
	public $counts = 2;
	public $DynType = 'PHP';
	
	public function getOutValue($sql) {
		
		foreach($this->outVars[1] as $key=>$type) {
			
			$num = $this->outVars[2][$key];
			
			if(!$this->isType($type, $num)) {
				continue;	
			}			
			
			$sqlEntry = strtolower($this->DynType).$num;	
			$sqlEntry = $sql->get($sqlEntry);
			
			// DYN_HTML_PHP bleibt unberührt
			if($type == 'HTML_'.$this->DynType) {
				//nothing
			} else {				
				$sqlEntry = $this->convertEval($sqlEntry);
			}
			
			
			$this->content = str_replace(
				$this->outVars[0][$key],
				$sqlEntry,
				$this->content
			);
			
		}
		
		return $this;
		
	}
	
	public function convertEval($content) {
		
		ob_start();
		ob_implicit_flush(0);
		
		// PHP-Startzeichen  im Code verwenden können
		$content = eval(' ?>'.$content.' <?php ');
		
		$output = ob_get_contents();
		ob_end_clean();
		
		return $output;
		
	}
	
	
}

?>