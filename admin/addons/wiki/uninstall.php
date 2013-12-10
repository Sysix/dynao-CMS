<?php

$sql = sql::factory();
$sql->query('DROP TABLE IF EXISTS '.sql::table('wiki'));
$sql->query('DROP TABLE IF EXISTS '.sql::table('wiki_cat'));

?>