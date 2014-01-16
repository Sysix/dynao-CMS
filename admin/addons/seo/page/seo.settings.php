<?php

$config = new addon('seo');

$form = form::factory('user', 'id='.dyn::get('user')->get('id'), 'index.php');
$form->setSave(false);

$field = $form->addSelectField('ending', $form->get('ending', $config->get('ending')));
$field->fieldName('Endung');
$field->add('/', '/');
$field->add('.html', '.html');

$field = $form->addSelectField('start_url', $form->get('start_url', $config->get('start_url')));
$field->fieldName('Startseite');
$field->add('0', dyn::get('hp_url'));
$field->add('1', dyn::get('hp_url').rewriteId(dyn::get('start_page')));

$field = $form->addCheckboxField('robots', $form->get('robots', $config->get('robots')));
$field->fieldName('Seite indexieren?');
$field->add('1', '');

if($form->isSubmit()) {
	
	$config->add('ending', $form->get('ending'), true);
	$config->add('start_url', $form->get('start_url'), true);
	$config->add('robots', $form->get('robots'), true);
	
	if(!$config->saveConfig()) {
		$this->setErrorMessage('SEO Einstellungen konnten nicht gespeichert werden');
	}
		
}

$form->show();


?>