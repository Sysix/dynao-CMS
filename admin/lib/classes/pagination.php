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
		
		$first_get_string = http_build_query(array($this->getVar=>$first_page) + $_GET);
		$return .= '<li><a href="index.php?'.$first_get_string.'">«</a></li>';
		
		for($i = 1; $i<=$this->maxSites; $i++) {
			
			$class = '';
			
			if($i == $this->currentSite)
				$class = ' class="active"';
			
			if(in_array($i, $this->disable)) // Disable hat vorrang
				$class = ' class="disabled"';
				
			// $_GET['site'] neu setzen			
			$get_string = http_build_query(array($this->getVar=>$i) + $_GET);
				
			$return .= '<li'.$class.'><a href="index.php?'.$get_string.'">'.$i.'</a></li>';
						
		}
		
		
		$last_get_string = http_build_query(array($this->getVar=>$last_page) + $_GET);
		$return .= '<li><a href="index.php?'.$last_get_string.'">»</a></li>';
		
		$return .= '<ul>';
		
		return $return;
		
	}
	
}


?>