<?php

if(!dyn::get('backend')) {
	
	$seo = new seo();
	$seo->parseUrl($_SERVER['REQUEST_URI']);
	
	extension::add('URL_REWRITE', function($return) {
		return seo::rewriteId($return['id']);
	});
	
} else {

	$page = type::super('page', 'string');
	$subpage = type::super('subpage', 'string');
	$action = type::super('action', 'string');
	$structure_id = type::super('structure_id', 'int', 0);
	
	if($page == 'structure' && $subpage == 'pages' && in_array($action, ['add', 'edit']) && !$structure_id) {
		
		extension::add('FORM_BEFORE_SAVE', function() {
			seo::generatePathlist();
		});
		
	}
	
}

?>