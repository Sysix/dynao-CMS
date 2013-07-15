<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class table {
	
	protected $thead = array();
	protected $tfoot = array();
	protected $tbody_array = array();
	
	protected $current_section;
	
	protected $tableAttr = array();	
	protected $collsLayout = array();
	protected $caption = array();
	
	protected $sql;
	var $isSql = false;
	
	
	function __construct($attributes = array()) {
		// wenn addSection nicht ausgeführt rows zu thead adden
		$this->addSection('thead');
			
		$this->tableAttr = $attributes;
	}
	
	//rows zu der zuletzt aufgerufenden Section hinzufügen
	public function addSection($section, $attributes = array() ) {
		
		switch ($section) {
			case 'thead':
				$this->current_section = 'thead';	
				break;
				
			case 'tfoot':
				$this->current_section = 'tfoot';
				break;
				
			default: // tbody
				$this->tbody_array[] = array();
				$this->current_section = count($this->tbody_array)-1;
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
			return $this->tbody_array[$this->current_section];	
		}
	}
	
	protected function setCurrentSection($section) {
		
		if($this->current_section === 'thead') {
			$this->thead = $section;	
		} elseif($this->current_section === 'tfoot') {
			$this->tfoot = $section;	
		} else {
			$this->tbody_array[$this->current_section] = $section;	
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
		
		if($end) {
		
			return '<'.$name.$attributes.'>'.$value.'</'.$name.'>'.PHP_EOL;	
			
		} else {
		
			return '<'.$name.$attributes.'>'.$value.PHP_EOL;
			
		}
		
		
	}
	
	public function addCaption($value, $attributes = array() ) {		
		$this->caption = array('value'=>$value, 'attr'=>$attributes);
	}
	
	protected function getCaption() {
		
		return $this->addTag('caption', $this->caption['attr'], $this->caption['value']);
		
	}
	
	protected function convertAttr($attributes) {
		
		if(!count($attributes) || is_string($attributes))
			return '';
		
		$str = '';
		foreach($attributes as $key=>$val) {
			$str .= ' '.$key.'="'.$val.'"';
		}
		
		return $str;
		
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
	
	public function addRow($attributes = array() ) {
		// rows zur letzten Section hinzufügen
		
		$ref = $this->getCurrentSection();
		
		// Nächste Zeile == Nächste SQL Eintrag
		if($this->current_section == 'tbody' && $this->isSql)
			$this->sql->next();
		
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
		
 		foreach( $this->tbody_array as $sec ) {
				$return .= $this->getSection($sec, 'tbody');				
		}
		
		return $this->addTag('table', $this->tableAttr, PHP_EOL.$return);
		
	}
	
}

?>

<?php
//Inhalt als multi array
$inhalt = array(
    'test1' => array('Flasche', 2, 'flasche halt', 422),
    'test2' => array('Eimer', 2, 'eimer voll', 444),
    'test3' => array('Schale', 2, 'obstschale', 345),
	'test4' => array('Dose', 2, 'dosenessen', 123)
);


$table = new table();
$table->setSql('SELECT * FROM `job_news`');
//titel

$table->addCollsLayout('280,20,20,50');
$table->addCaption('Testtabelle', array('id'=> 'testid', 'class'=>'classtest') );

$table->addRow()
->addCell('Name', array('class'=>'first'))
->addCell('Anzahl')
->addCell('Beschreibung')
->addCell('bla');
    
//section "tbody" aufrufen, rows werden dieser dann hinzugefügt
$table->addSection('tbody');

	//foreach für die Zeilen
while($table->isNext()) {
	$table->addRow()
	->addCell($table->get('title'))
	->addCell($table->get('poster'))
	->addCell(date('d.m.Y', $table->get('date')))
	->addCell($table->get('rubric'));
}
    
$table->addRow()->addCell('testfooter', array('colspan'=>4, 'class'=>'foot'));
	   
echo $table->show();

echo '<pre>';
print_r($table);
echo '</pre>';

?>
