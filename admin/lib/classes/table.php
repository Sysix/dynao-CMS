<?php

class table {
    
    private $thead = array();
    private $tfoot = array();
    private $tbody_array = array();

    private $current_section;
    
    private $tableStr = '';
    
    function __construct($id = '', $class = '', $attr_ar = array() ) {
        // wenn addSection nicht ausgeführt rows zu tbody adden
        $this->current_section = &$this->tbody_array[0];
        
        $this->tableStr = "\n<table" . ( !empty($id)? " id=\"$id\"": '' ) . 
            ( !empty($class)? " class=\"$class\"": '' ) . $this->addAttribute( $attr_ar ).">\n";
    }
    
	//rows zu der zuletzt aufgerufenden Section hinzufügen
    function addSection($section, $class = '', $attr_ar = array() ) {
        switch ($section) {
            case 'thead':
                $ref = &$this->thead;
                break;
            case 'tfoot':
                $ref = &$this->tfoot;
                break;
            case 'tbody':
                $ref = &$this->tbody_array[ count($this->tbody_array) ];
                break;
            
            default: // tbody
                $ref = &$this->tbody_array[count($this->tbody_array) ];
        }
        
        $ref['class'] = $class;
        $ref['atts'] = $attr_ar;
        $ref['rows'] = array();
        
        $this->current_section = &$ref;
    }
    
    public function addCaption($cap, $class = '', $attr_ar = array() ) {
        $this->tableStr.= "<caption" . (!empty($class)? " class=\"$class\"": '') .
            $this->addAttribute($attr_ar) . '>' . $cap . "</caption>\n";
    }
    
    private function addAttribute( $attr_ar ) {
        $str = '';
        foreach( $attr_ar as $key=>$val ) {
            $str .= " $key=\"$val\"";
        }
        return $str;
    }
    
    function addRow($class = '', $attr_ar = array() ) {
        // rows zur letzten Section hinzufügen
        $this->current_section['rows'][] = array(
            'class' => $class,
            'atts' => $attr_ar,
            'cells' => array()
        );
        
    }
    
    function addCell($data = '', $class = '', $type = 'data', $attr_ar = array() ) {
        $cell = array(
            'data' => $data,
            'class' => $class,
            'type' => $type,
            'atts' => $attr_ar
        );
        
        if ( empty($this->current_section['rows']) ) {
            try {
                throw new Exception('vor addCell erst addRow');
            } catch(Exception $ex) {
                $msg = $ex->getMessage();
                echo "<p>Error: $msg</p>";
            }
        }
        
        // zur letzten row der letzten section hinzufügen
        $count = count( $this->current_section['rows'] );
        $curRow = &$this->current_section['rows'][$count-1];
        $curRow['cells'][] = &$cell;
    }
    
    private function getRowCells($cells) {
        $str = '';
        foreach( $cells as $cell ) {
            $tag = ($cell['type'] == 'data')? 'td': 'th';
            $str .= ( !empty( $cell['class'] )? "    <$tag class=\"{$cell['class']}\"": "    <$tag" ) . 
                    $this->addAttribute( $cell['atts'] ) . ">" . $cell['data'] . "</$tag>\n";
        }
        return $str;
    }
    
    function show() {
        
        // section und die rows/cells 
        $this->tableStr .= !empty($this->thead)? $this->getSection($this->thead, 'thead'): '';
        $this->tableStr .= !empty($this->tfoot)? $this->getSection($this->tfoot, 'tfoot'): '';
        
        foreach( $this->tbody_array as $sec ) {
            $this->tableStr .= !empty($sec)? $this->getSection($sec, 'tbody'): '';
        }
        
        $this->tableStr .= "</table>\n";
        return $this->tableStr;
    }
    
    private function getSection($sec, $tag) {
        $class = !empty($sec['class'])? " class=\"{$sec['class']}\"": '';
        $atts = !empty($sec['atts'])? $this->addAttribs( $sec['atts'] ): '';
        
        $str = "<$tag" . $class . $atts . ">\n";
        
        foreach( $sec['rows'] as $row ) {
            $str .= ( !empty( $row['class'] ) ? "  <tr class=\"{$row['class']}\"": "  <tr" ) . 
                    $this->addAttribute( $row['atts'] ) . ">\n" . 
                    $this->getRowCells( $row['cells'] ) . "  </tr>\n";
        }
        
        $str .= "</$tag>\n";
        
        return $str;
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