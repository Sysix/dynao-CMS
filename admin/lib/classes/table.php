<?php
class table {
	
	protected $thead = array();
	protected $tfoot = array();
	protected $tbody = array();
	
	protected $current_section;
	
	protected $tableAttr = array();	
	protected $collsLayout = array();
	protected $caption = array();
	
	var $sql;
	var $isSql = false;
	
	
	function __construct($attributes = array()) {
		// wenn addSection nicht ausgeführt rows zu thead adden
		$this->addSection('thead');
		
		// Bootstrap Table-Klasse
		$attributes['class'][] = 'table';
		
		$this->tableAttr = $attributes;
	}
	
	//rows zu der zuletzt aufgerufenden Section hinzufügen
	public function addSection($section, $attributes = array() ) {
		
		if(in_array($section, array('thead', 'tfoot'))) {
			
			$this->current_section = $section;
			
		} else {
			
			$this->current_section = 'tbody';
			
		}
		
		$ref = $this->getCurrentSection();
	
		$ref['attr'] = $attributes;
		$ref['rows'] = array();
		
		$this->setCurrentSection($ref);
		
	}
	
	protected function getCurrentSection() {
		
		if($this->current_section === 'thead') {
			return $this->thead;	
		} elseif($this->current_section === 'tfoot') {
			return $this->tfoot;	
		} else {
			return $this->tbody;	
		}
	}
	
	protected function setCurrentSection($section) {
		
		if($this->current_section === 'thead') {
			$this->thead = $section;	
		} elseif($this->current_section === 'tfoot') {
			$this->tfoot = $section;	
		} else {
			$this->tbody = $section;	
		}
	
	}
	
	public function addCollsLayout($cols) {
	
		if(!is_array($cols)) {			
			
			$col2 = explode(',', $cols);
			$cols = array();
			
			foreach($col2 as $key=>$val) {			
				$cols[]['width'] = $val;			
			}			
			
		}
		
		$this->collsLayout = $cols;
		
	}
	
	protected function getCollsLayout() {
		
		$cols = '';
		foreach($this->collsLayout as $val) {
			$cols .= $this->addTag('col', $val, '', false);
		}
		
		return $this->addTag('colgroup', '', $cols);
	}

	
	// Fügt ein HTML Tag hinzu
	// Name z.B.: td, tr, caption, table,
	// Value z.B: Inhalt
	// Attribute
	// End: true => <tag></tag> false => <tag />
	protected function addTag($name, $attributes = array(), $value = '', $end = true) {
		
		$attributes = $this->convertAttr($attributes);
		
		$endtag = ($end) ? '</'.$name.'>' : '';
		
		return '<'.$name.$attributes.'>'.$value.$endtag.PHP_EOL;		
		
	}
	
	public function addCaption($value, $attributes = array() ) {		
		$this->caption = array('value'=>$value, 'attr'=>$attributes);
	}
	
	protected function getCaption() {
		
		if(count($this->caption)) {
			return $this->addTag('caption', $this->caption['attr'], $this->caption['value']);
		}
		
		return '';
		
	}
	
	protected function convertAttr($attributes) {
		
		if(!count($attributes) || is_string($attributes))
			return '';
		
		$return = '';
		
		foreach($attributes as $key=>$val) {
			
			if(is_int($key)) {
				
				$return .= ' '.$val;
				
			} else {
				
				if(is_array($val)) {
					$val = implode(' ', $val);	
				}
				
				$return .= ' '.$key.'="'.$val.'"';	
				
			}			
			
		}
		
		return $return;
		
	}
	
	public function setSql($query) {
	
		$this->sql = new sql();
		$this->sql->query($query)->result();
		
		$this->isSql = true;
		
	}
	
	public function isNext() {
	
		return $this->sql->isNext();
		
	}
	
	public function get($value) {
	
		return $this->sql->get($value);
		
	}
	
	public function next() {
	
		$this->sql->next();	
		
	}
	
	public function addRow($attributes = array() ) {
		// rows zur letzten Section hinzufügen
		
		$ref = $this->getCurrentSection();
		
		$ref['rows'][] = array(
			'attr' => $attributes,
			'cells' => array()
		);
		
		$this->setCurrentSection($ref);
		
		return $this;
		
	}
	
	public function addCell($value = '', $attributes = array() ) {
		
		$type = ($this->current_section === 'thead') ? 'th' : 'td';
		
		$cell = array(
			'value' => $value,
			'type' => $type,
			'attr' => $attributes
		);
		
		$ref = $this->getCurrentSection();
		
		/*
		if(empty($ref['rows'])) {
			try {
				throw new Exception('vor addCell erst addRow');
			} catch(Exception $ex) {
				$msg = $ex->getMessage();
				echo "<p>Error: $msg</p>";
			}
		}
		*/
		
		// zur letzten row der letzten section hinzufügen
		$count = count( $ref['rows'] );
		$ref['rows'][$count-1]['cells'][] = $cell;
		
		$this->setCurrentSection($ref);
		
		return $this;
		
	}
	
	public function addCells($values, $attributes = array()) {
	
		if(!is_array($values)) return $this;
		
		foreach($values as $value) {
		
			$this->addCell($value, $attributes);
			
		}
		
		return $this;
		
	}
	
	protected function getRowCells($cells) {
		
		$str = '';
		
		foreach( $cells as $cell ) {
			$str .= $this->addTag($cell['type'], $cell['attr'], $cell['value']);
		}
		
		return $str;
		
	}
	
	protected function getSection($sec, $tag) {
		
		if(!count($sec))
			return '';	
	
		$str = '';	
		foreach($sec['rows'] as $row) {
			$str .= $this->addTag('tr', $row['attr'], $this->getRowCells($row['cells']));
		}		
		
		return $this->addTag($tag, $sec['attr'], $str);
		
	}
	
	public function show() {		
				
		$return = $this->getCaption();
		
		$return .= $this->getCollsLayout();
		$return .= $this->getSection($this->thead, 'thead');
		$return .= $this->getSection($this->tfoot, 'tfoot');		
		$return .= $this->getSection($this->tbody , 'tbody');				
		
		return $this->addTag('table', $this->tableAttr, PHP_EOL.$return);
		
	}
	
}

?>