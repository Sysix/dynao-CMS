<?php

$sql = sql::factory();
$sql->query('DROP TABLE IF EXISTS '.sql::table('uninstall'));

?>