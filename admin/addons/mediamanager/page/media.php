<?php

backend::addSubnavi(lang::get('media_manage'),				url::backend('media', ['subpage'=>'files']));
backend::addSubnavi(lang::get('media_manage_cat'),			url::backend('media', ['subpage'=>'category']));

$action = type::super('action', 'string', '');
$id = type::super('id', 'int', 0);

if($subpage == 'popup') {
	
	layout::addJsCode("$(document.body).on(''change', ''#media-select-category', function() {
	var catId = $(this).val();
	$(''#load').load('index.php?page=media&subpage=popup&catId='+catId+' #load');
});");
	
	dyn::add('contentPage', true);
	backend::addSubnavi('Popup',		url::backend('media', ['subpage'=>'popup']));
	
}

include_once(backend::getSubnaviInclude('mediamanager'));

?>