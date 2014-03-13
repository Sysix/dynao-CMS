<?php

$sql = sql::factory();
$sql->query('CREATE TABLE IF NOT EXISTS '.sql::table('community_user').' (
`id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
`username`		varchar(255)			NOT NULL,
`password`		varchar(255)		 	NOT NULL,
`email` 		varchar(255)		 	NOT NULL,
`avatar` 		varchar(255)			NOT NULL,
`admin` 		int(1)		unsigned	NOT NULL,
`salt` 			varchar(255)			NOT NULL,
`lostpw_key` 	varchar(16)				NOT NULL,
`registerdate`	TIMESTAMP			 	NOT NULL DEFAULT 0,
`lastlogin`		TIMESTAMP			 	NOT NULL DEFAULT 0,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

?>