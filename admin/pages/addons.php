<?php
$action = type::super('action', 'string');
$addon = type::super('addon', 'string');

backend::addSubnavi('Übersicht',	url::backend('addons'), 	'archive', 0);

if($action == 'install') {

	$addonClass= new addon($addon, false);	
	$install = ($addonClass->isInstall()) ? 0 : 1;
	
	$sql = new sql();
	$sql->setTable('addons');
	$sql->setWhere('`name` = "'.$addon.'"');
	$sql->addPost('install', $install);
	
	if(!$install)
		$sql->addPost('active', 0);
	
	$sql->update();
	
	
	if(!$addonClass->isInstall()) {
		$addonClass->install();
	} else {
		$addonClass->uninstall();	
	}
	
	echo message::success('Addon erfolgreich gespeichert');
	
}

if($action == 'active') {
	
	$addonClass = new addon($addon, false);	
	$active = ($addonClass->isActive()) ? 0 : 1;
	
	if(!$addonClass->isInstall()) {
		
		echo message::danger('Bitte installieren Sie das Addon '.$addon.' zuerst');
			
	} else {
	
		$sql = new sql();
		$sql->setTable('addons');
		$sql->setWhere('`name` = "'.$addon.'"');
		$sql->addPost('active', $active);
		$sql->update();
		
		echo message::success('Addon erfolgreich gespeichert');
		
	}
	
}

$table = new table();
$table->addCollsLayout('20,*,300');

$table->addRow()
->addCell('')
->addCell('Name')
->addCell('Aktionen');

$table->addSection('tbody');

$addons = scandir(dir::backend('addons/'));

foreach($addons as $dir) {
	
	if(in_array($dir, array('.', '..', '.htaccess'))) 
		continue;
	
	$curAddon = new addon($dir);
	
	$online_url = url::backend('addons', array('addon'=>$dir, 'action'=>'install'));
	
	if($curAddon->isInstall()) {
		$online = '<a href="'.$online_url.'" class="btn btn-sm structure-online">installiert</a>';
	} else {
		$online = '<a href="'.$online_url.'" class="btn btn-sm structure-offline">nicht installiert</a>';
	}
	$active_url = url::backend('addons', array('addon'=>$dir, 'action'=>'active'));
	
	if($curAddon->isActive()) {
		$active = '<a href="'.$active_url.'" class="btn btn-sm structure-online">aktiviert</a>';
	} else {
		$active = '<a href="'.$active_url.'" class="btn btn-sm structure-offline">nicht aktiviert</a>';
	}
	
	$delete = '<a href="#" class="btn btn-sm btn-danger">Entfernen</a>';
	
	$table->addRow()
	->addCell('<a href="" class="icon-question"></a>')
	->addCell($curAddon->get('name').' <small>'.$curAddon->get('version').'</small>')
	->addCell('<span class="btn-group">'.$online.$active.$delete.'</span>');
		
}

echo $table->show();
?>