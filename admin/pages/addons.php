<?php
$action = type::super('action', 'string');
$addon = type::super('addon', 'string');

backend::addSubnavi('Übersicht',	url::backend('addons'), 				'archive');

$table = new table();

$table->addRow()
->addCell('')
->addCell('Name')
->addCell('Installiert')
->addCell('Aktiviert')
->addCell('Entfernen');

$table->addSection('tbody');

$addons = scandir(dir::backend('addons/'));

foreach($addons as $dir) {
	
	if(in_array($dir, array('.', '..', '.htaccess'))) 
		continue;
	
	$curAddon = new addon($dir);
	
	$online = $curAddon->isOnline() ? 'installiert' : 'nicht installiert';
	$active = $curAddon->isActive() ? 'aktiviert' : 'nicht aktiviert';
	
	$table->addRow()
	->addCell('<a href="" class="icon-question"></a>')
	->addCell($curAddon->get('name').' <small>'.$curAddon->get('version').'</small>')
	->addCell($online)
	->addCell($active)
	->addCell('Entfernen');
		
}

echo $table->show();
?>