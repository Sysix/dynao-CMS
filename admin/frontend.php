<?php


foreach(addonConfig::includeAllConfig() as $file) {
	include($file);	
}

dyn::add('content', ob_get_contents());

ob_end_clean();

include(dir::template(dyn::get('template'), 'template.php'));

$page = new page(1);
foreach($page->getBlocks() as $block) {
	var_dump($block->getContent());	
}

?>