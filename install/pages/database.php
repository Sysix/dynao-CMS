<div class="row">

    <div class="col-lg-12">
    
        <div class="panel panel-default">
        
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo lang::get('db_connect'); ?></h3>
                </div>
                <div class="panel-body">
                
                    <?php
						
						$form = form_install::factory('', '', 'index.php');
						$form->setSave(false);
						
						$form->delButton('back');
						
						$DB = dyn::get('DB');
						
						$field = $form->addRawField('<h4>Datenbank</h4>');
						
						$field = $form->addTextField('db_host', $DB['host']);
						$field->fieldName(lang::get('db_host'));
						
						$field = $form->addTextField('db_user', $DB['user']);
						$field->fieldName(lang::get('db_user'));
						
						$field = $form->addTextField('db_password', $DB['password']);
						$field->fieldName(lang::get('db_password'));
						
						$field = $form->addTextField('db_database', $DB['database']);
						$field->fieldName(lang::get('db_database'));
						
						$field = $form->addRawField('<h4>Admin</h4>');
						
						$field = $form->addTextField('email', '');
						$field->fieldName(lang::get('email'));
						
						$field = $form->addTextField('password', '');
						$field->fieldName(lang::get('password'));
						
						if($form->isSubmit()) {
							
							$sql = sql::connect($form->get('db_host'), $form->get('db_user'), $form->get('db_password'), $form->get('db_database'));
							
							if($sql) {
								
								$DB = [
									'host' => $form->get('db_host'),
									'user' => $form->get('db_user'),
									'password' => $form->get('db_password'),
									'database' => $form->get('db_database')
									];
								
								dyn::add('DB', $DB, true);
								dyn::save();
								
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
								  `template`	varchar(255) 			NOT NULL,
								  `sort`		int(16)		unsigned	NOT NULL,
								  `parent_id`	int(16)		unsigned	NOT NULL,
								  `lang`		int(2)		unsigned	NOT NULL,
								  `online`		int(1)		unsigned	NOT NULL,
								  PRIMARY KEY  (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
								
								$sql->query('CREATE TABLE IF NOT EXISTS `user` (
								  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
								  `firstname` 		varchar(255)			NOT NULL,
								  `name` 		varchar(255)			NOT NULL,
								  `email` 		varchar(255)			NOT NULL,
								  `password`	varchar(255)			NOT NULL,
								  `perms`		varchar(255)			NOT NULL,
								  `admin`		int(1) 		unsigned	NOT NULL,
								  PRIMARY KEY  (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;');
								
								
								$sql->query('INSERT INTO `user` 
								(`id`, 	`email`, 					`password`, 									`admin`) VALUES
								(1, 	"'.type::post('email').'", 	"'.userLogin::hash(type::post('password')).'", 	1);');
								
								$sql->query('CREATE TABLE IF NOT EXISTS `structure_area` (
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
								  `link1` 		varchar(255)			NOT NULL,
								  `link2` 		varchar(255)			NOT NULL,
								  `link3` 		varchar(255) 			NOT NULL,
								  `link4` 		varchar(255)			NOT NULL,
								  `link5` 		varchar(255)			NOT NULL,
								  `link6` 		varchar(255)			NOT NULL,
								  `link7`		varchar(255)			NOT NULL,
								  `link8` 		varchar(255)			NOT NULL,
								  `link9` 		varchar(255) 			NOT NULL,
								  `link10` 		varchar(255) 			NOT NULL,
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
								
								$sql->query('CREATE TABLE IF NOT EXISTS `addons` (
								  `id` 			int(11) 	unsigned	NOT NULL	auto_increment,
								  `name` 		varchar(255)			NOT NULL,
								  `active`		int(1)					NOT NULL,
								  `install`		int(1)					NOT NULL,
								  PRIMARY KEY  (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
								$sql->query('CREATE TABLE IF NOT EXISTS `slots` (
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
								  `link1` 		varchar(255)			NOT NULL,
								  `link2` 		varchar(255)			NOT NULL,
								  `link3` 		varchar(255) 			NOT NULL,
								  `link4` 		varchar(255)			NOT NULL,
								  `link5` 		varchar(255)			NOT NULL,
								  `link6` 		varchar(255)			NOT NULL,
								  `link7`		varchar(255)			NOT NULL,
								  `link8` 		varchar(255)			NOT NULL,
								  `link9` 		varchar(255) 			NOT NULL,
								  `link10` 		varchar(255) 			NOT NULL,
								  `php1` 		text 					NOT NULL,
								  `php2` 		text 					NOT NULL,
									PRIMARY KEY  (`id`)
								) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
								
								$form->addParam('page', 'finish');
							} else
								echo message::danger('sql error');
							
							$form->delParam('subpage');
							$form->delParam('success_msg');
						
						}
						
						echo $form->show();
					
					?>
                
                </div>
        
        </div>
    
    </div>
    
</div>