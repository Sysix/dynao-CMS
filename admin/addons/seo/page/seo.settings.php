<?php

$form = form::factory('user', 'id='.dyn::get('user')->get('id'), 'index.php');

$field = $form->addSelectField('ending', $form->get('ending'));
$field->fieldName('Endung');
$field->add('/', '/');
$field->add('.html', '.html');

$field = $form->addSelectField('start_url', $form->get('start_url'));
$field->fieldName('Startseite');
$field->add('0', dyn::get('hp_url'));
$field->add('1', dyn::get('hp_url').rewriteId(dyn::get('start_page')));

$field = $form->addCheckboxField('robots', $form->get('robots'));
$field->fieldName('Seite indexieren?');
$field->add('1', '');

$form->show();


?>