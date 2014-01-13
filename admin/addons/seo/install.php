<?php

$sql = sql::factory();
$sql->query('ALTER TABLE '.sql::table('structure').' 
ADD `seo_title` 		VARCHAR(255) NOT NULL,
ADD `seo_keywords` 		VARCHAR(255) NOT NULL,
ADD `seo_description`	VARCHAR(255) NOT NULL,
ADD `seo_costum_url` 	VARCHAR(255) NOT NULL,
ADD `seo_robots` 		int(1)		 NOT NULL DEFAULT "1"
');

copy(dir::addon('seo', '_htaccess'), dir::base('.htaccess'));

include_once(dir::addon('seo', 'lib'.DIRECTORY_SEPARATOR.'seo_rewrite.php'));

seo_rewrite::generatePathlist();
?>