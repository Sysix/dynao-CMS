<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class table {
	
	protected $thead = array();
	protected $tfoot = array();
	protected $tbody_array = array();
	
	protected $caption = array();
	
	protected $current_section;
	
	protected $tableAttr = array();
	
	
	function __construct($attributes= array() ) {
		// wenn addSection nicht ausgeführt rows zu tbody adden
		$this->current_section = 0;
			
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
				$this->tbody_array[] = array('rows'=>'');
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
		
			return '<'.$name.$attributes.' />'.PHP_EOL;
			
		}
		
		
	}
	
	public function addCaption($value, $attributes = array() ) {		
		$this->caption = array('value'=>$value, 'attr'=>$attributes);
	}
	
	protected function getCaption() {
		
		return $this->addTag('caption', $this->caption['attr'], $this->caption['value']);
		
	}
	
	protected function convertAttr($attributes) {
		
		if(!count($attributes))
			return '';
		
		$str = '';
		foreach($attributes as $key=>$val) {
			$str .= ' '.$key.'="'.$val.'"';
		}
		
		return $str;
		
	}
	
	public function addRow($attributes = array() ) {
		// rows zur letzten Section hinzufügen
		
		$ref = $this->getCurrentSection();
		
		$ref['rows'][] = array(
			'attr' => $attributes,
			'cells' => array()
		);
		
		$this->setCurrentSection($ref);
		
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


$tabelle = new table();

//titel
$tabelle->addCaption('Testtabelle', array('id'=> 'testid', 'class'=>'classtest') );

//section "thead" aufrufen, rows werden dieser dann hinzugefügt
$tabelle->addSection('thead');

$tabelle->addRow();
    //Header hinzufügen
    $tabelle->addCell('Name', array('class'=>'first'));
    $tabelle->addCell('Anzahl');
    $tabelle->addCell('Beschreibung');
	$tabelle->addCell('bla');
    
//section "tbody" aufrufen, rows werden dieser dann hinzugefügt
$tabelle->addSection('tbody');
	//foreach für die Zeilen
    foreach($inhalt as $produkt) {
        list($name, $anzahl, $des, $bla) = $produkt;
        $tabelle->addRow();
            $tabelle->addCell($name);
			$tabelle->addCell($anzahl);
			$tabelle->addCell($des);
			$tabelle->addCell($bla);
    }
    
$tabelle->addRow();
    $tabelle->addCell('testfooter', array('colspan'=>4, 'class'=>'foot'));
	   
echo $tabelle->show();
?>
