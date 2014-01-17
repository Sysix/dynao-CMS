<?php
class install {
	
	public static function update0_1to0_2() {
		
		$sql = sql::factory();
		$sql->query('ALTER TABLE '.sql::table('module').' ADD `slots` int(1) unsigned NOT NULL');
		$sql->query('ALTER TABLE '.sql::table('user').' ADD `salt` VARCHAR(255) NOT NULL');		
		
	}
	
	public static function newInstall() {
		
		$sql = new sql();
		$sql->query('DROP TABLE `'.sql::table('module').'`');
		$sql->query('CREATE TABLE `'.sql::table("module").'` (
		  `id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
		  `name`		varchar(255) 			NOT NULL,
		  `input` 		text 					NOT NULL,
		  `output`		text 					NOT NULL,
		  `slots`		int(1)		unsigned 	NOT NULL,
		  `sort`		int(16)		unsigned 	NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
		
		$sql->query('DROP TABLE `'.sql::table('structure').'`');				
		$sql->query('CREATE TABLE `'.sql::table("structure").'` (
		  `id` 			int(16)		unsigned	NOT NULL 	auto_increment,
		  `name`		varchar(255) 			NOT NULL,
		  `template`	varchar(255) 			NOT NULL,
		  `sort`		int(16)		unsigned	NOT NULL,
		  `parent_id`	int(16)		unsigned	NOT NULL,
		  `online`		int(1)		unsigned	NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
		$sql->query('DROP TABLE `'.sql::table('user').'`');
		$sql->query('CREATE TABLE `'.sql::table("user").'` (
		  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
		  `firstname` 	varchar(255)			NOT NULL,
		  `name` 		varchar(255)			NOT NULL,
		  `email` 		varchar(255)			NOT NULL,
		  `password`	varchar(255)			NOT NULL,
		  `salt` 		varchar(255)			NOT NULL
		  `perms`		varchar(255)			NOT NULL,
		  `admin`		int(1) 		unsigned	NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
		$salt = userLogin::generateSalt();
		$sql->setTable('user');
		$sql->addPost('firstname', type::post('firstname'));
		$sql->addPost('name', type::post('name'));
		$sql->addPost('email', type::post('email'));
		$sql->addPost('password', userLogin::hash(type::post('password'), $salt));
		$sql->addPost('salt', $salt);
		$sql->addPost('admin', 1);
		$sql->save();
		
		$sql->query('DROP TABLE `'.sql::table('structure_area').'`');				
		$sql->query('CREATE TABLE `'.sql::table("structure_area").'` (
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
		
		$sql->query('DROP TABLE `'.sql::table('addons').'`');				
		$sql->query('CREATE TABLE `'.sql::table("addons").'` (
		  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
		  `name` 		varchar(255)			NOT NULL,
		  `active`		int(1)					NOT NULL,
		  `install`		int(1)					NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
		
		$sql->query('DROP TABLE `'.sql::table('slots').'`');				
		$sql->query('CREATE TABLE `'.sql::table("slots").'` (
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
	
	public static function getModulCode($file) {
		
		return dir::base('install'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.$file);
		
	}
	
	public static function insertDemoContent() {
		
		$sql = sql::factory();
		
		$input = file_get_contents(self::getModulCode('1_input.txt'));
		$output = file_get_contents(self::getModulCode('1_output.txt'));
		
		$sql->setTable('module');
		
		$sql->addPost('name', 'Überschrift');
		$sql->addPost('sort', 1);
		$sql->addPost('input', $input);
		$sql->addPost('output', $output);
		$sql->save();
		
		$input = file_get_contents(self::getModulCode('2_input.txt'));
		$output = file_get_contents(self::getModulCode('2_output.txt'));
		
		$sql->addPost('name', 'Editor');
		$sql->addPost('sort', 2);
		$sql->addPost('input', $input);
		$sql->addPost('output', $output);
		$sql->save();
		
		$sql = sql::factory();
		$sql->setTable('structure');
		
		$sql->addPost('name', 'Home');
		$sql->addPost('template', 'template.php');
		$sql->addPost('sort', 1);
		$sql->addPost('online', 1);
		$sql->save();
		
		$sql->addPost('name', '404 Error');
		$sql->addPost('sort', 2);
		$sql->addPost('online', 0);
		$sql->save();
			
		dyn::add('start_page', 1, true);
		dyn::add('error_page', 2, true);
		dyn::save();
		
	}
	
}

?>