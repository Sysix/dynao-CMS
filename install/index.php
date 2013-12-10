<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

include('../admin/lib'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'dir.php');
include('lib'.DIRECTORY_SEPARATOR.'functions.php');

new dir();

include_once(dir::classes('autoload.php'));
autoload::register();
autoload::addDir(dir::classes('utils'));

new dyn();

$DB = dyn::get('DB');
sql::connect($DB['host'], $DB['user'], $DB['password'], $DB['database']);

$page = (isset($_GET["page"]) == '') ? 'step1' : $_GET["page"];

include "layout/index.php";

?>