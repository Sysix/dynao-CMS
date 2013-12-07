<?php

$sql = sql::factory();
$sql->query('DROP TABLE IF EXISTS '.sql::table('media'));
$sql->query('DROP TABLE IF EXISTS '.sql::table('media_cat'));

?>