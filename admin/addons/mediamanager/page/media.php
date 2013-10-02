<?php

$files = scandir(dir::media());

$table = new table();
$table->addRow()
->addCell()
->addCell('Titel')
->addCell('Endung')
->addCell('Aktion');
$table->addSection('tbody');

foreach($files as $file) {

	if(in_array($file, array('..', '.')))
		continue;
		
	$media = media::getMediaByName($file);
		
	$table->addRow()
	->addCell('<img src="'.$media->getPath().'" style="max-width:50px; max-height:50px" />')
	->addCell($media->get('title'))
	->addCell($media->getExtension())
	->addCell('...');
	
}

echo $table->show();

?>