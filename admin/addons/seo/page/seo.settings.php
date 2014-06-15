<?php

$config = new addon('seo');

$form = form::factory('user', 'id='.dyn::get('user')->get('id'), 'index.php');
$form->setSave(false);
$form->delButton('save-back');

$field = $form->addSelectField('ending', $form->get('ending', $config->get('ending')));
$field->fieldName(lang::get('ending'));
$field->add('/', '/');
$field->add('.html', '.html');

$field = $form->addSelectField('start_url', $form->get('start_url', $config->get('start_url')));
$field->fieldName(lang::get('start_page'));
$field->add('0', dyn::get('hp_url'));
$field->add('1', dyn::get('hp_url').seo_rewrite::rewriteId(dyn::get('start_page')));

$field = $form->addCheckboxField('robots', $form->get('robots', $config->get('robots')));
$field->fieldName(lang::get('seo_site_indexing'));
$field->add('1', '');

if($form->isSubmit()) {
	
	$config->add('ending', $form->get('ending'), true);
	$config->add('start_url', $form->get('start_url'), true);
	$config->add('robots', $form->get('robots'), true);
	
	if(!$config->saveConfig()) {
		$this->setErrorMessage(lang::get('seo_not_saved'));
	}

    addonConfig::loadAllConfig();
    seo_rewrite::generatePathlist();
		
}
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?= lang::get('settings') ?></h3>
	</div>
	<div class="panel-body">
		<?= $form->show(); ?>
	</div>
</div>

