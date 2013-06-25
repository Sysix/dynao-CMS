<?php

class table {
	
	protected $thead = array();
	protected $tfoot = array();
	protected $tbody_array = array();
	
	protected $current_section;
	
	protected $tableStr = '';
	
	function __construct($id = '', $class = '', $attributes= array() ) {
		// wenn addSection nicht ausgeführt rows zu tbody adden
		$this->current_section = &$this->tbody_array[0];
		
		if(!empty($id))
			$attributes['id'] = $id;
			
		if(!empty($class)) 
			$attributes['class'] = $class;
			
 		$this->tableStr = PHP_EOL.'<table'.$this->convertAttr($attributes).'>'.PHP_EOL;
	}
	
	//rows zu der zuletzt aufgerufenden Section hinzufügen
	public function addSection($section, $class = '', $attributes = array() ) {
		
		switch ($section) {
			case 'thead':
 				$ref = &$this->thead;
				break;
			case 'tfoot':
				$ref = &$this->tfoot;
				break;
				
			default: // tbody
				$ref = &$this->tbody_array[count($this->tbody_array)];
		}
		
		if(!empty($class)) 
			$attributes['class'] = $class;
	
		$ref['attr'] = $attributes;
		$ref['rows'] = array();
		
		$this->current_section = &$ref;
		
	}
	
	public function addCaption($cap, $class = '', $attributes = array() ) {
		
		if(!empty($class)) 
			$attributes['class'] = $class;
		
		$this->tableStr .= '<caption'.$this->convertAttr($attributes).'>'.$cap.'</caption>'.PHP_EOL;
	}
	
	protected function convertAttr($attributes) {
		
		$str = '';
		foreach($attributes as $key=>$val) {
			$str .= ' '.$key.'="'.$val.'"';
		}
		
		return $str;
		
	}
	
	public function addRow($class = '', $attributes = array() ) {
		// rows zur letzten Section hinzufügen
		
		if(!empty($class)) 
			$attributes['class'] = $class;
		
		
		$this->current_section['rows'][] = array(
			'attr' => $attributes,
			'cells' => array()
		);
		
	}
	
	public function addCell($data = '', $class = '', $type = 'data', $attributes = array() ) {
		
		if(!empty($class)) 
			$attributes['class'] = $class;
		
		$cell = array(
			'data' => $data,
			'type' => $type,
			'attr' => $attributes
		);
		
		if(empty($this->current_section['rows'])) {
			try {
				throw new Exception('vor addCell erst addRow');
			} catch(Exception $ex) {
				$msg = $ex->getMessage();
				echo "<p>Error: $msg</p>";
			}
		}
		
		// zur letzten row der letzten section hinzufügen
		$count = count( $this->current_section['rows'] );
		$this->current_section['rows'][$count-1]['cells'][] = $cell;
		
	}
	
	protected function getRowCells($cells) {
		
		$str = '';
		
		foreach( $cells as $cell ) {
			$tag = ($cell['type'] == 'data')? 'td': 'th';			
			$str .= '<'.$tag.$this->convertAttr($cell['attr']).'>'.$cell['data'].'</'.$tag.'>'.PHP_EOL;
		}
		
		return $str;
		
	}
	
	protected function getSection($sec, $tag) {
		
 	$attr = !empty($sec['attr'])? $this->convertAttr($sec['attr']) : '';
	
	$str = '<'.$tag.$attr.'>'.PHP_EOL;
	
		foreach($sec['rows'] as $row) {
			$str .= '<tr'.$this->convertAttr($row['attr']).'>'.PHP_EOL.$this->getRowCells($row['cells']).'</tr>'.PHP_EOL;
		}
		
		$str .= '</'.$tag.'>'.PHP_EOL;
		
		return $str;
		
	}
	
	public function show() {
		
		// section und die rows/cells 
		$this->tableStr .= !empty($this->thead)? $this->getSection($this->thead, 'thead'): '';
		$this->tableStr .= !empty($this->tfoot)? $this->getSection($this->tfoot, 'tfoot'): '';
		
 		foreach( $this->tbody_array as $sec ) {
			
			if(!empty($sec))
				$this->tableStr .= $this->getSection($sec, 'tbody');
				
		}
		
 		$this->tableStr .= '</table>'.PHP_EOL;
		return $this->tableStr;
		
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
$tabelle->addCaption('Testtabelle', 'classtest', array('id'=> 'testid') );

//section "thead" aufrufen, rows werden dieser dann hinzugefügt
$tabelle->addSection('thead');

$tabelle->addRow();
    //Header hinzufügen
    $tabelle->addCell('Name', 'first', 'header');
    $tabelle->addCell('Anzahl', '', 'header');
    $tabelle->addCell('Beschreibung', '', 'header');
	$tabelle->addCell('bla', '', 'header');
    
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
    $tabelle->addCell('testfooter', 'foot', 'data', array('colspan'=>4) );
    
echo $tabelle->show();
?>
