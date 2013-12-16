<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

include('..'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'dir.php');

new dir();

include_once(dir::classes('autoload.php'));
autoload::register();
autoload::addDir(dir::classes('utils'));
autoload::addDir('lib'.DIRECTORY_SEPARATOR.'classes');

include(dir::functions('html_stuff.php'));
include(dir::functions('url_stuff.php'));

new dyn();
			
lang::setDefault();
lang::setLang(dyn::get('lang'));

lang::loadLang('lib'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.lang::getLang().'.json');

$page = (isset($_GET["page"]) == '') ? 'overview' : $_GET["page"];

include "layout/index.php";

?>