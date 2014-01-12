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
	$id = type::super('id', 'int', 0);
	
	if($page == 'structure' && $subpage == 'pages' && $action == 'seo') {
		
		seoPage::generateForm($id);
		
		layout::addJsCode("
		var default_url = $('#seo-costum-url').text();
		console.log(default_url);
		$('#seo-costum-url-text').keyup(function() {
			var val = $(this).val();
			
			if(val == '')
				val = default_url;
							
			$('#seo-costum-url').text(val);
		});
		
		var default_title = $('#seo-default-title').text();
		$('#seo-title-text').keyup(function() {
			var val = $(this).val();
			
			if(val == '')
				val = default_title;
			
			$('#seo-title').text(val);
		});
		");
		
	}
	
	if($page == 'structure' && $subpage == 'pages' && in_array($action, ['add', 'edit', 'delete', 'seo']) && !$structure_id) {
		print_r($_POST);
		extension::add('FORM_BEFORE_SHOW', function($form) {
			seo::generatePathlist();
			return $form;
		});
		
	}
	
	if($page == 'structure' && $subpage == 'pages' && $structure_id) {
		
		extension::add('BACKEND_OUTPUT', function($output) use ($structure_id) {			
			return seoPage::generateButton($output, $structure_id);			
		});		
		
	}
	
}

?>