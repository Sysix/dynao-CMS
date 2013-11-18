<?php


foreach(addonConfig::includeAllConfig() as $file) {
	include($file);	
}

dyn::add('content', ob_get_contents());

ob_end_clean();

include(dir::template(dyn::get('template'), 'template.php'));

?>