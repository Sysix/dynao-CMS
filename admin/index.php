<?php

session_start();

include_once('lib/classes/dir.php');
new dir();

include_once(dir::classes('autoload.php'));
autoload::register();
autoload::addDir('utils');

new dyn();

include_once(dir::functions('html_stuff.php'));
include_once(dir::functions('url_stuff.php'));

lang::setDefault();
lang::setLang(dyn::get('lang'));

mb_internal_encoding('UTF-8');

$DB = dyn::get('DB');
sql::connect($DB['host'], $DB['user'], $DB['password'], $DB['database']);

cache::setCache(dyn::get('cache'));

ajax::convertPOST();

$page = type::super('page', 'string', 'dashboard');
$subpage = type::super('subpage', 'string');

layout::addCSS('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Open+Sans+Condensed:300,700');
layout::addCSS('http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css');
layout::addCSS('http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css');

layout::addCSS('layout/css/style.css');
layout::addCSS('layout/css/mobile.css');

layout::addJS('http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js');
layout::addJS('http://code.jquery.com/ui/1.10.3/jquery-ui.js');
layout::addJS('http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js');
layout::addJS('layout/js/scripts.js');

addonConfig::includeAllConfig();

ob_start();

$login = new userLogin();

if($login->isLogged()) {
	if(file_exists(dir::page($page.'.php'))) {
		
		include(dir::page($page.'.php'));
		
	} else {
		$sql = new sql();
		$sql->query('SELECT name FROM '.sql::table('addons').' WHERE `online` = 1 AND `active` = 1')->result();
		while($sql->isNext()) {
			if(file_exists(dir::addon($sql->get('name'), 'page/'.$page.'.php'))) {
				
				include(dir::addon($sql->get('name'), 'page/'.$page.'.php'));
				break;
				
			}
			$sql->next();
		}
	}
	
}

$CONTENT = ob_get_contents();

ob_end_clean();


if(ajax::is()) {
	echo ajax::getReturn();
	die;
}

if($login->isLogged()) {
include(dir::backend('layout/index.php'));
} else {
	include(dir::backend('layout/login.php'));
}
?>