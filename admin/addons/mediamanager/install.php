<?php

if(!file_exists(dir::base('media'))) {
	mkdir(dir::base('media'), 755);
}

$sql = sql::factory();
$sql->query('CREATE TABLE IF NOT EXISTS '.sql::table('media').' (
`id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
`filename`		varchar(255)			NOT NULL,
`category`		int(16)		unsigned 	NOT NULL,
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

$sql->query('ALTER TABLE '.sql::table('structure_area').' 
ADD `media1` 		int(16) NOT NULL,
ADD `media2` 		int(16) NOT NULL,
ADD `media3` 		int(16) NOT NULL,
ADD `media4` 		int(16) NOT NULL,
ADD `media5` 		int(16) NOT NULL,
ADD `media6` 		int(16) NOT NULL,
ADD `media7` 		int(16) NOT NULL,
ADD `media8` 		int(16) NOT NULL,
ADD `media9` 		int(16) NOT NULL,
ADD `media10` 		int(16) NOT NULL,
ADD `medialist1`	varchar(255) NOT NULL,
ADD `medialist2`	varchar(255) NOT NULL,
ADD `medialist3`	varchar(255) NOT NULL,
ADD `medialist4`	varchar(255) NOT NULL,
ADD `medialist5`	varchar(255) NOT NULL,
ADD `medialist6`	varchar(255) NOT NULL,
ADD `medialist7`	varchar(255) NOT NULL,
ADD `medialist8`	varchar(255) NOT NULL,
ADD `medialist9`	varchar(255) NOT NULL,
ADD `medialist10`	varchar(255) NOT NULL');


?>