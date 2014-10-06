<?php
class install {

    public static function update_0_2_2to0_3() {

        $sql = sql::factory();
        $sql->query('ALTER TABLE `'.sql::table('structure').'` ADD `lang` INT(16) UNSIGNED NOT NULL AFTER `id`');
        $sql->query('ALTER TABLE `'.sql::table('structure').'` DROP PRIMARY KEY');
        $sql->query('ALTER TABLE `'.sql::table('structure').'` ADD `art_id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`art_id`)');
        $sql->query('ALTER TABLE `'.sql::table('structure').'` ADD UNIQUE (`id`, `lang`)');

        $sql->query('ALTER TABLE `'.sql::table('blocks').'` ADD `lang` INT(16) NOT NULL AFTER `name`');

        $sql->query('ALTER TABLE `'.sql::table('structure_area').'` ADD `lang` INT(16) UNSIGNED NOT NULL AFTER `id`');

        $sql->query('DROP TABLE IF EXISTS `'.sql::table('lang').'`');
        $sql->query('CREATE TABLE `'.sql::table("lang").'` (
		  `id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
		  `name`		varchar(255) 			NOT NULL,
		  `sort`		int(16)		unsigned 	NOT NULL,
		  PRIMARY KEY  (`id`) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

        $sql->setTable('lang');
        $sql->addPost('name', 'deutsch');
        $sql->addPost('sort', 1);
        $sql->save();

    }
	
	public static function update0_1to0_2() {

        $sql = sql::factory();
		$sql->query('ALTER TABLE '.sql::table('module').' ADD `blocks` int(1) unsigned NOT NULL');
        $sql->query('ALTER TABLE '.sql::table('user').' ADD `salt` VARCHAR(255) NOT NULL');
        $sql->query('ALTER TABLE '.sql::table('structure_area').' ADD `block` int(1) NOT NULL AFTER `id`');
        $sql->query('ALTER TABLE '.sql::table('structure').' ADD `createdAt` DATETIME NOT NULL,
        ADD `updatedAt` DATETIME NOT NULL');


    }
	
	public static function newInstall() {
		
		$sql = new sql();
		$sql->query('DROP TABLE IF EXISTS `'.sql::table('module').'`');
		$sql->query('CREATE TABLE `'.sql::table("module").'` (
		  `id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
		  `name`		varchar(255) 			NOT NULL,
		  `input` 		text 					NOT NULL,
		  `output`		text 					NOT NULL,
		  `blocks`		int(1)		unsigned 	NOT NULL,
		  `sort`		int(16)		unsigned 	NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
		
		$sql->query('DROP TABLE IF EXISTS `'.sql::table('structure').'`');				
		$sql->query('CREATE TABLE `'.sql::table("structure").'` (
		  `art_id`      int(16)     unsigned    NOT NULL    auto_increment,
		  `id` 			int(16)		unsigned	NOT NULL,
		  `lang`        int(16)     unsigned    NOT NULL,
		  `name`		varchar(255) 			NOT NULL,
		  `template`	varchar(255) 			NOT NULL,
		  `sort`		int(16)		unsigned	NOT NULL,
		  `parent_id`	int(16)		unsigned	NOT NULL,
		  `online`		int(1)		unsigned	NOT NULL,
		  `createdAt`	DATETIME                NOT NULL,
		  `updatedAt`	DATETIME                NOT NULL,
		  PRIMARY KEY  (`art_id`),
		  UNIQUE KEY (`id`, `lang`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
		$sql->query('DROP TABLE IF EXISTS `'.sql::table('user').'`');
		$sql->query('CREATE TABLE `'.sql::table("user").'` (
		  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
		  `firstname` 	varchar(255)			NOT NULL,
		  `name` 		varchar(255)			NOT NULL,
		  `email` 		varchar(255)			NOT NULL,
		  `password`	varchar(255)			NOT NULL,
		  `salt` 		varchar(255)			NOT NULL,
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
		
		$sql->query('DROP TABLE IF EXISTS `'.sql::table('structure_area').'`');				
		$sql->query('CREATE TABLE `'.sql::table("structure_area").'` (
		  `id`			int(16)		unsigned	NOT NULL		auto_increment,
		  `lang`        int(16)     unsigned    NOT NULL,
		  `block`		int(1)		unsigned	NOT NULL,
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
		
		$sql->query('DROP TABLE IF EXISTS `'.sql::table('addons').'`');				
		$sql->query('CREATE TABLE `'.sql::table("addons").'` (
		  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
		  `name` 		varchar(255)			NOT NULL,
		  `active`		int(1)					NOT NULL,
		  `install`		int(1)					NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
		
		$sql->query('DROP TABLE IF EXISTS `'.sql::table('blocks').'`');				
		$sql->query('CREATE TABLE `'.sql::table("blocks").'` (
		  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
		  `name` 		varchar(255)			NOT NULL,
		  `lang`        int(16)                 NOT NULL
		  `description`	varchar(255)			NOT NULL,
		  `template` 	varchar(255)			NOT NULL,
		  `is-structure`int(1)		unsigned	NOT NULL	DEFAULT "1",
		  `structure` 	varchar(255)			NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

        $sql->query('DROP TABLE IF EXISTS `'.sql::table('lang').'`');
        $sql->query('CREATE TABLE `'.sql::table("lang").'` (
		  `id` 			int(16)		unsigned 	NOT NULL 	auto_increment,
		  `name`		varchar(255) 			NOT NULL,
		  `sort`		int(16)		unsigned 	NOT NULL,
		  PRIMARY KEY  (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');

        $sql->reset();
        $sql->setTable('lang');
        $sql->addPost('name', 'deutsch');
        $sql->addPost('sort', 1);
        $sql->save();
		
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
		
		$sql = new sql();
		$sql->setTable('structure');
		
		$sql->addPost('name', 'Home');
		$sql->addPost('template', 'template.php');
        $sql->addPost('id', 1);
        $sql->addPost('lang', 1);
		$sql->addPost('sort', 1);
		$sql->addPost('online', 1);
        $sql->addDatePost('createdAt');
        $sql->addDatePost('updatedAt');
		$sql->save();
		
		$sql->addPost('name', '404 Error');
        $sql->addPost('id', 2);
        $sql->addPost('lang', 1);
		$sql->addPost('sort', 2);
		$sql->addPost('online', 0);
        $sql->addDatePost('createdAt');
        $sql->addDatePost('updatedAt');
		$sql->save();
			
		dyn::add('start_page', 1, true);
		dyn::add('error_page', 2, true);
        dyn::add('langId', 1);
		dyn::save();
		
	}
	
}

?>
