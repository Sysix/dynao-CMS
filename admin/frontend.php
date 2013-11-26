<?php


foreach(addonConfig::includeAllConfig() as $file) {
	include($file);	
}

$page = new page(1);
$blocks = $page->getBlocks();
foreach($blocks as $block) {
	echo $block->getContent();
}

dyn::add('content', ob_get_contents());

ob_end_clean();

include(dir::template(dyn::get('template'), 'template.php'));

?>