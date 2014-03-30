<?php

$settings = dyn_mailer::loadConfig();

$form = form::factory('user', 'id='.dyn::get('user')->getId(), 'index.php');
$form->setSave(false);
$form->delButton('save');
$form->delButton('back');

$field = $form->addTextField('fromName', $form->get('fromName', $settings['fromName']));
$field->fieldName('Absender (Name)');

$field = $form->addTextField('from', $form->get('from', $settings['from']));
$field->fieldName('Absender (E-Mail)');
$field->addValidator('email', lang::get('user_wrong_email'));

$field = $form->addTextField('confirmReading', $form->get('confirmReading', $settings['confirmReading']));
$field->fieldName('Lesebestätigung (E-Mail)');

$field  = $form->addTextField('bbc', $form->get('bbc', $settings['bbc']));
$field->fieldName('Blindkopie (E-Mail');

if($form->isSubmit()) {
	
	$from = $form->get('from');
	$fromName = $form->get('fromName');
	$confirmReading = $form->get('confirmReading');
	$bbc = $form->get('bbc');
	
	if(!dyn_mailer::saveConfig($from, $fromName, $confirmReading, $bbc)) {
		$form->setErrorMessage('Einstellungen konnte nicht gespeichert werden');	
	}
	
}
echo $form->show();

?>