<?php

$sql = new sql();
$sql->query('CREATE TABLE IF NOT EXISTS '.sql::table('metainfos').' (
`id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
`label`			varchar(255)			NOT NULL,
`name` 			varchar(255)			NOT NULL,
`sort` 			int(16)		unsigned	NOT NULL,
`default` 		varchar(255)			NOT NULL,
`params`		text					NOT NULL,
`attributes`	text					NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');



?>