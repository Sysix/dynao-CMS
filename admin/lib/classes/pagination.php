<?php

class pagination {
	
	var $disable = array();
	var $proSite; // Einträge pro Seite
	var $maxEntrys; // Maximale Einträge
	var $start; //SQL Start
	var $currentSite; // Aktuelle Seite
	var $maxSites; #Maximale Seitem
	
	var $getVar;
	
	public function __construct($proSite, $maxEntrys) {
			
		$this->proSite = $proSite;
		$this->maxEntrys = $maxEntrys;
		
		$this->setGetVar('site');
		
	}
	
	public function setDisable($disable) {
		
		if(strpos($disable, ',') !== false) {
			explode(',', $disable);			
		}
		
		$this->disable = $disable;
		
	}
	
	public function getSqlLimit() {
		
		return 'LIMIT '.$this->start .', '.$this->proSite;
		
	}
	
	public function setGetVar($var) {
		
		$this->getVar = $var;
		
		$this->currentSite = type::get($this->getVar, 'int', 1);
		
		$this->start = $this->currentSite * $this->proSite - $this->proSite;
		
		$this->maxSites = ceil($this->maxEntrys / $this->proSite);
		
	}
	
	public function get($class = false) {
		
		$class = ($class) ? ' pagination-'.$class : '';
		
		$return = '<ul class="pagination'.$class.'">';
		
		$first_page = $this->currentSite-1;
		$last_page = $this->currentSite+1;
		
		if($this->currentSite == 1) {
			$first_page = $this->currentSite;
		}
		
		if($this->currentSite == $this->maxSites) {
			$last_page = $this->currentSite;
		}
		
		$return .= '<li><a href="index.php?'.url_addParam($this->getVar, $first_page).'">«</a></li>';
		
		for($i = 1; $i<=$this->maxSites; $i++) {
			
			$class = '';
			
			if($i == $this->currentSite)
				$class = ' class="active"';
			
			if(in_array($i, $this->disable)) // Disable hat vorrang
				$class = ' class="disabled"';
				
			$return .= '<li'.$class.'><a href="index.php?'.url_addParam($this->getVar, $i).'">'.$i.'</a></li>';
						
		}
		
		$return .= '<li><a href="index.php?'.url_addParam($this->getVar, $last_page).'">»</a></li>';
		
		$return .= '<ul>';
		
		return $return;
		
	}
	
}


?>