<?php

if(!dyn::has('extensions')) {
	
	$imageExtensions = ["gif", "jpeg", "jpg", "png", "bmp"];
	$videoExtensions = ["3gp", "avi", "flv", "m4v", "mov", "mp4", "mpg", "wmv", "mkv", "mpeg"];
	$audioExtensions = ["mp3", "wma", "m4a"];
	
	dyn::add('extensions', [
		'image'=>$imageExtensions, 
		'video'=>$videoExtensions, 
		'audio'=>$audioExtensions
	], true);
	dyn::add('badExtensions', ['php', 'htaccess', 'htpasswd'], true);
	dyn::save();
	
}


$sql = sql::factory();
$sql->query('CREATE TABLE IF NOT EXISTS '.sql::table('media').' (
`id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
`filename`		varchar(255)			NOT NULL,
`size` 			int(16)		unsigned 	NOT NULL,
`title` 		varchar(255)			NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

$sql->query('CREATE TABLE IF NOT EXISTS '.sql::table('media_cat').' (
`id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
`name`			varchar(255)			NOT NULL,
`sort` 			int(16)		unsigned 	NOT NULL,
`pid` 			int(16)		unsigned	NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');



?>