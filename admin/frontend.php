<?php


foreach(addonConfig::includeAllConfig() as $file) {
	include($file);	
}

include(dir::template(dyn::get('template'), 'config.php'));

$page = type::super('page_id', 'int', dyn::get('start_page'));
$page = extension::get('SET_PAGE_ID', $page);

if(page::isValid($page)) {
	$page = new page($page);
} else {
	header('HTTP/1.0 404 Not Found');
	$page = new page(dyn::get('error_page'));	
}

ob_end_clean();

echo $page->getTemplate();

?>