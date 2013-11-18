<?php

$sql = sql::factory();
$sql->query('DROP TABLE IF EXISTS '.sql::table('media'));
$sql->query('DROP TABLE IF EXISTS '.sql::table('media_cat'));

$sql->query('ALTER TABLE `'.sql::table('structure').'`
  DROP `media01`,
  DROP `media02`,
  DROP `media03`,
  DROP `media04`,
  DROP `media05`,
  DROP `media06`,
  DROP `media07`,
  DROP `media08`,
  DROP `media09`,
  DROP `media10`');
?>