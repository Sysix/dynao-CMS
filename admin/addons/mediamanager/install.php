<?php

if(!dyn::has('extensions')) {
	
	$imageExtensions = array("gif", "jpeg", "jpg", "png", "bmp");
	$videoExtensions = array("3gp", "avi", "flv", "m4v", "mov", "mp4", "mpg", "wmv", "mkv", "mpeg");
	$audioExtensions = array("mp3", "wma", "m4a");
	
	dyn::add('extensions', array($imageExtensions, $videoExtensions, $audioExtensions), true);
	dyn::save();
	
}


$sql = new sql();
$sql->query('CREATE TABLE IF NOT EXISTS '.sql::table('mediamanager').' (
`id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
`filename`		varchar(255)			NOT NULL 	auto_increment,
`size` 			int(16)		unsigned 	NOT NULL 	auto_increment,
`title` 		varchar(255)			NOT NULL 	auto_increment,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

?>