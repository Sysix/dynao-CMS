<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Globale Variablen
$mainClasses = array();
$subClasses = array();

function selectClasses($dir) {

    // Globale Variablen einbezihene
    global $mainClasses;
    global $subClasses;

    // Ordner öffnen und Dateien in $files speichern
    $files = scandir($dir);

    // Jede Datei durchforten
    foreach($files as $file) {

        // Unzulässige Dateien
        if(in_array($file, array('.', '..'))) {
            continue;   
        }

        // Ist Ordner? Wenn ja dann Function erneut aufrufen
        if(strpos($file, '.php') === false) {
            selectClasses($dir.'/'.$file);
            continue;
        }       

        // Datei einlesen und die ersten 200 Zeichen schauen ob es eine Kindklasse ist oder nicht
        $section = file_get_contents($dir.'/'.$file, NULL, NULL, 0, 200);
        $section = preg_match('/class (\w+) extends (\w+)/', $section, $treffer);
        if(isset($treffer[1])) { # Ist Kindklasse
            $subClasses[] = $dir.'/'.$file; 
        } else { # Ist Vaterklasse
            $mainClasses[] = $dir.'/'.$file;    
        }

    }

}

// Funktion öffnen
selectClasses('admin/lib/classes');

// Hauptklasse einbinden
foreach($mainClasses as $class) {
    require_once($class);
}


// Kindklassen einbinden
foreach($subClasses as $class) {
    require_once($class);
}

lang::setDefault();
lang::setLang('de_de');


sql::connect('localhost', 'dynao_user', 'dasisteinpasswort', 'dynao');

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);

if($action == 'save' || $action == 'save-edit') {
	
	$sql = new sql();	
	$sql->setTable('news');
	$types = array('title'=>'string', 'text'=>'string');
					
	$sql->getPosts($types);
	$sql->addPost('date', time());
	
	
	if($action == 'save-edit') {
		$sql->setWhere('id='.$id);
		$sql->update();
	} else {
		$sql->save();	
	}
		
}

if($action == 'add' ||$action == 'edit') {

	$form = new form('news','id='.$id,'index.php');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName('News-Titel');
	
	$field = $form->addTextareaField('text', $form->get('text'));
	$field->fieldName('Infotext');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	echo $form->show();
	
} else {

	$table = new table();
	
	$table->addRow()
	->addCell('Titel')
	->addCell('Datum')
	->addCell('Aktion');
	
	$table->addSection('tbody');
	
	$table->setSql('SELECT * FROM news ORDER BY date DESC');
	while($table->isNext()) {
		
		$id = $table->get('id');
		
		$edit = '<a href="index.php?action=edit&amp;id='.$id.'">'.lang::get('edit').'</a>';
		$delete = '<a href="index.php?action=del&amp;id='.$id.'">'.lang::get('delete').'</a>';
		
		$table->addRow()
		->addCell($table->get('title'))
		->addCell(date('d.m.Y', $table->get('date')))	
		->addCell($edit.' | '.$delete);
		
		$table->next();	
	}
	
	echo $table->show();
	
}

?>