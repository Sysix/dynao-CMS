<?php

$sql = sql::factory();
$sql->query('ALTER TABLE `'.sql::table('structure').'`
  DROP `seo_title`,
  DROP `seo_keywords`,
  DROP `seo_description`,
  DROP `seo_costum_url`,
  DROP `seo_robots`');

?>