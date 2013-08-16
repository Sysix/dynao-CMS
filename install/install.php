<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
session_start();

include_once('../admin/lib/classes/dir.php');
new dir();
dir::$base = $_SERVER['DOCUMENT_ROOT'].str_replace(DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR.'install.php', '', $_SERVER['PHP_SELF']);

include_once(dir::classes('autoload.php'));
autoload::register();
autoload::addDir('utils');

include_once(dir::functions('html_stuff.php'));
include_once(dir::functions('url_stuff.php'));

sql::connect('localhost', 'dynao_user', 'dasisteinpasswort', 'dynao');

$sql = new sql();
$sql = $sql->query('CREATE TABLE IF NOT EXISTS `module` (
  `id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
  `name`		varchar(255) 			NOT NULL,
  `input` 		text 					NOT NULL,
  `output`		text 					NOT NULL,
  `sort`		int(16)		unsigned 	NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

$sql->query('INSERT INTO `module` 
(`id`,	`name`, 		`input`, 							`output`, 		`sort`) VALUES
(1, 	"Modul #1",		"Das ist ein automatischer Text", 	"Jop :)", 		0),
(2, 	"Modul #2", 	"Hallo :)", 						"Hallo3", 		0)');

$sql->query('CREATE TABLE IF NOT EXISTS `structure` (
  `id` 			int(16)		unsigned	NOT NULL 	auto_increment,
  `name`		varchar(255) 			NOT NULL,
  `sort`		int(16)		unsigned	NOT NULL,
  `parent_id`	int(16)		unsigned	NOT NULL,
  `lang`		int(2)		unsigned	NOT NULL,
  `online`		int(1)		unsigned	NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

$sql->query('INSERT INTO `structure` (
`id`,	`name`,			`sort`,		`parent_id`,	`lang`,		`online`) VALUES
(1, 	"test4", 		2, 			0, 				0,			0),
(2, 	"test2", 		1, 			0, 				0,			0),
(4, 	"test2224", 	1, 			2, 				0,			1),
(5, 	"test3",		3,			0,				0,			0),
(8, 	"irgendwas",	1, 			4, 				0, 			1),
(9, 	"lol", 			0, 			8, 				0,			0),
(10, 	"Fleischmann",	0, 			9, 				0, 			0),
(11, 	"Junkers", 		0, 			10, 			0, 			0),
(12, 	"test", 		2, 			4, 				0, 			0);');


$sql->query('CREATE TABLE IF NOT EXISTS `user` (
  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
  `email` 		varchar(255)			NOT NULL,
  `password`	varchar(255)			NOT NULL,
  `perms`		varchar(255)			NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;');


$sql->query('INSERT INTO `user` 
(`id`, 	`email`, 					`password`, 									`perms`) VALUES
(1, 	"sysix@sysix-coding.de", 	"a94a8fe5ccb19ba61c4c0873d391e987982fbbd3", 	""),
(2, 	"info@dynao.de", 			"a94a8fe5ccb19ba61c4c0873d391e987982fbbd3", 	"");');

$sql->query('CREATE TABLE IF NOT EXISTS `structure_block` (
  `id`			int(16)		unsigned	NOT NULL		auto_increment,
  `structure_id`int(16) 	unsigned	NOT NULL,
  `sort`		int(16)		unsigned	NOT NULL,
  `modul`		int(16)		unsigned	NOT NULL,
  `value1` 		text 					NOT NULL,
  `value2` 		text					NOT NULL,
  `value3` 		text 					NOT NULL,
  `value4` 		text					NOT NULL,
  `value5` 		text					NOT NULL,
  `value6` 		text 					NOT NULL,
  `value7`		text 				NOT NULL,
  `value8` 		text 				NOT NULL,
  `value9` 		text 				NOT NULL,
  `value10` 	text 				NOT NULL,
  `value11` 	text 				NOT NULL,
  `value12` 	text 				NOT NULL,
  `value13` 	text 				NOT NULL,
  `value14` 	text 				NOT NULL,
  `value15` 	text 				NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

echo message::success('Update erfolgreich');
?>