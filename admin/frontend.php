<?php

ob_end_clean();

foreach(addonConfig::includeAllConfig() as $file) {
	include($file);	
}

$page = type::super('page_id', 'int', dyn::get('start_page'));
$page = extension::get('SET_PAGE_ID', $page);
dyn::add('page_id', $page);

if(page::isValid($page)) {
	$page = new page($page);
} else {
	header('HTTP/1.0 404 Not Found');
	$page = new page(dyn::get('error_page'));	
}

echo $page->getTemplate();

?>