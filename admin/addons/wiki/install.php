<?php

$sql = sql::factory();
$sql->query('CREATE TABLE IF NOT EXISTS '.sql::table('wiki').' (
`id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
`category` 		int(16)		unsigned	NOT NULL,
`name`			varchar(255)			NOT NULL,
`sort` 			int(16)		unsigned	NOT NULL,
`text`			text					NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

$sql = sql::factory();
$sql->query('CREATE TABLE IF NOT EXISTS '.sql::table('wiki_cat').' (
`id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
`pid` 			int(16)		unsigned	NOT NULL,
`name`			varchar(255)			NOT NULL,
`sort` 			int(16)		unsigned	NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');



?>