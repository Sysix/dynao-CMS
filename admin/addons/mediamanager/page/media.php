<?php

$files = scandir(dir::media());

$table = new table();
$table->addRow()
->addCell('Titel')
->addCell('Endung')
->addCell('Aktion');
$table->addSection('tbody');

foreach($files as $file) {

	if(in_array($file, array('..', '.')))
		continue;
		
	$table->addRow()
	->addCell($file)
	->addCell('...')
	->addCell('...');
	
}

echo $table->show();

?>