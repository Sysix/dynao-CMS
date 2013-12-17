<?php
class install {
	
	public static function newInstall() {
		
		$sql = new sql();
		$sql = $sql->query('CREATE TABLE IF NOT EXISTS `'.$sql->table("module").'` (
		  `id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
		  `name`		varchar(255) 			NOT NULL,
		  `input` 		text 					NOT NULL,
		  `output`		text 					NOT NULL,
		  `sort`		int(16)		unsigned 	NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
		$sql->query('CREATE TABLE IF NOT EXISTS `'.$sql->table("structure").'` (
		  `id` 			int(16)		unsigned	NOT NULL 	auto_increment,
		  `name`		varchar(255) 			NOT NULL,
		  `template`	varchar(255) 			NOT NULL,
		  `sort`		int(16)		unsigned	NOT NULL,
		  `parent_id`	int(16)		unsigned	NOT NULL,
		  `lang`		int(2)		unsigned	NOT NULL,
		  `online`		int(1)		unsigned	NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
								
		$sql->query('CREATE TABLE IF NOT EXISTS `'.$sql->table("user").'` (
		  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
		  `firstname` 	varchar(255)			NOT NULL,
		  `name` 		varchar(255)			NOT NULL,
		  `email` 		varchar(255)			NOT NULL,
		  `password`	varchar(255)			NOT NULL,
		  `perms`		varchar(255)			NOT NULL,
		  `admin`		int(1) 		unsigned	NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
		
		$sql->setTable('user');
		$sql->addPost('email', type::post('email'));
		$sql->addPost('password', userLogin::hash(type::post('password')));
		$sql->addPost('admin', 1);
		$sql->save();
								
		$sql->query('CREATE TABLE IF NOT EXISTS `'.$sql->table("structure_area").'` (
		  `id`			int(16)		unsigned	NOT NULL		auto_increment,
		  `structure_id`int(16) 	unsigned	NOT NULL,
		  `sort`		int(16)		unsigned	NOT NULL,
		  `modul`		int(16)		unsigned	NOT NULL,
		  `online`		int(1)		unsigned	NOT NULL,
		  `value1` 		text 					NOT NULL,
		  `value2` 		text					NOT NULL,
		  `value3` 		text 					NOT NULL,
		  `value4` 		text					NOT NULL,
		  `value5` 		text					NOT NULL,
		  `value6` 		text 					NOT NULL,
		  `value7`		text 					NOT NULL,
		  `value8` 		text 					NOT NULL,
		  `value9` 		text 					NOT NULL,
		  `value10` 	text 					NOT NULL,
		  `value11` 	text 					NOT NULL,
		  `value12` 	text 					NOT NULL,
		  `value13` 	text 					NOT NULL,
		  `value14` 	text 					NOT NULL,
		  `value15` 	text 					NOT NULL,
		  `link1` 		int(11)					NOT NULL,
		  `link2` 		int(11)					NOT NULL,
		  `link3` 		int(11)					NOT NULL,
		  `link4` 		int(11)					NOT NULL,
		  `link5` 		int(11)					NOT NULL,
		  `link6` 		int(11)					NOT NULL,
		  `link7`		int(11)					NOT NULL,
		  `link8` 		int(11)					NOT NULL,
		  `link9` 		int(11)					NOT NULL,
		  `link10` 		int(11)					NOT NULL,
		  `linklist1`	varchar(255)			NOT NULL,
		  `linklist2` 	varchar(255)			NOT NULL,
		  `linklist3` 	varchar(255) 			NOT NULL,
		  `linklist4` 	varchar(255)			NOT NULL,
		  `linklist5` 	varchar(255)			NOT NULL,
		  `linklist6` 	varchar(255)			NOT NULL,
		  `linklist7`	varchar(255)			NOT NULL,
		  `linklist8` 	varchar(255)			NOT NULL,
		  `linklist9` 	varchar(255) 			NOT NULL,
		  `linklist10`	varchar(255) 			NOT NULL,
		  `php1` 		text 					NOT NULL,
		  `php2` 		text 					NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
		$sql->query('CREATE TABLE IF NOT EXISTS `'.$sql->table("addons").'` (
		  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
		  `name` 		varchar(255)			NOT NULL,
		  `active`		int(1)					NOT NULL,
		  `install`		int(1)					NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
		$sql->query('CREATE TABLE IF NOT EXISTS `'.$sql->table("slots").'` (
		  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
		  `name` 		varchar(255)			NOT NULL,
		  `description`	varchar(255)			NOT NULL,
		  `template` 	varchar(255)			NOT NULL,
		  `modul`	 	int(11)		unsigned	NOT NULL,
		  `is-structure`int(1)		unsigned	NOT NULL	DEFAULT "1",
		  `structure` 	varchar(255)			NOT NULL,
		  `value1` 		text 					NOT NULL,
		  `value2` 		text					NOT NULL,
		  `value3` 		text 					NOT NULL,
		  `value4` 		text					NOT NULL,
		  `value5` 		text					NOT NULL,
		  `value6` 		text 					NOT NULL,
		  `value7`		text 					NOT NULL,
		  `value8` 		text 					NOT NULL,
		  `value9` 		text 					NOT NULL,
		  `value10` 	text 					NOT NULL,
		  `value11` 	text 					NOT NULL,
		  `value12` 	text 					NOT NULL,
		  `value13` 	text 					NOT NULL,
		  `value14` 	text 					NOT NULL,
		  `value15` 	text 					NOT NULL,
		   `link1` 		int(11)					NOT NULL,
		  `link2` 		int(11)					NOT NULL,
		  `link3` 		int(11)					NOT NULL,
		  `link4` 		int(11)					NOT NULL,
		  `link5` 		int(11)					NOT NULL,
		  `link6` 		int(11)					NOT NULL,
		  `link7`		int(11)					NOT NULL,
		  `link8` 		int(11)					NOT NULL,
		  `link9` 		int(11)					NOT NULL,
		  `link10` 		int(11)					NOT NULL,
		  `linklist1`	varchar(255)			NOT NULL,
		  `linklist2` 	varchar(255)			NOT NULL,
		  `linklist3` 	varchar(255) 			NOT NULL,
		  `linklist4` 	varchar(255)			NOT NULL,
		  `linklist5` 	varchar(255)			NOT NULL,
		  `linklist6` 	varchar(255)			NOT NULL,
		  `linklist7`	varchar(255)			NOT NULL,
		  `linklist8` 	varchar(255)			NOT NULL,
		  `linklist9` 	varchar(255) 			NOT NULL,
		  `linklist10`	varchar(255) 			NOT NULL,
		  `php1` 		text 					NOT NULL,
		  `php2` 		text 					NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
		
	}
	
}

?>