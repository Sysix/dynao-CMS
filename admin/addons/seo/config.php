<?php

if(!dyn::get('backend')) {
	
	extension::add('URL_REWRITE', function($return) {
		return seo_rewrite::rewriteId($return['id'], $return['lang']);
	});
	
	$seo = new seo_rewrite();
	$id = $seo->parseUrl($_SERVER['REQUEST_URI']);
	seo::setPageId($id);
	
	$seoFunc = type::get('seo_func', 'string');
	
	if($seoFunc == 'robots') {
		
		$robots = new seo_robots();
		$robots->send();
		die();
		
	}

    if($seoFunc == 'sitemap') {

        $sitemap = new seo_sitemap();
        $sitemap->setArticles(dyn::get('addons')['seo']['sitemap']['offlines']);
        $sitemap->send();
        die();

    }
	
} else {
	
	userPerm::add('admin[seo]', 'SEO Administrator');

    seo_control::addToPathlist('default', seo_control::getUrlsFromTable('addons', ['id', 'name']));
	
	backend::addAddonNavi(lang::get('seo'), url::backend('seo'), 'flag', -1, function() {
		return dir::addon('seo', 'page'.DIRECTORY_SEPARATOR.'seo.php');
	});

    seo::setPageId(dyn::get('start_page'));

	$page = type::super('page', 'string');
	$subpage = type::super('subpage', 'string');
	$action = type::super('action', 'string');
	$structure_id = type::super('structure_id', 'int', 0);
	$id = type::super('id', 'int', 0);


	// Falls die Sprache geändert worden ist
	if($page == 'settings' && $subpage == 'lang') {
		extension::add('FORM_AFTER_SAVE', function($sql) {
			seo_rewrite::generatePathlist();
			return $sql;
		});
	}

	// Falls was an der Page geändert worden ist
	if($page == 'structure' && $subpage == 'pages' && in_array($action, ['add', 'edit', 'seo']) && !$structure_id) {
		extension::add('FORM_AFTER_SAVE', function($sql) {
            seo_rewrite::generatePathlist();
			return $sql;
		});
		
	}
	
	// Wenn SEO Button geklickt worden ist
	if($page == 'structure' && $subpage == 'pages' && $action == 'seo') {
		
		seoPage::generateForm($id);
		
		layout::addJsCode("
		var default_url = $('#seo-costum-url').text();
		
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
	
	// Wenn Sortiert worden ist
	if($page == 'structure' && $subpage == 'pages' && (ajax::is() || ($action == 'delete' && !$structure_id))) {
		
		extension::add('BACKEND_OUTPUT', function($output) {
			seo_rewrite::generatePathlist();
			return $output;
		});
		
	}
	
	// Inhaltsseite der page
	if($page == 'structure' && $subpage == 'pages' && ($structure_id || ($action == 'edit' && $id))) {
		
		$id = ($structure_id) ? $structure_id : $id;
		
		extension::add('BACKEND_OUTPUT', function($output) use ($id) {			
			return seoPage::generateButton($output, $id);			
		});		
		
	}
	
}

?>