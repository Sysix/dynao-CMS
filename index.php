<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('admin/lib/classes/sql.php');
require('admin/lib/classes/form.php');
require('admin/lib/classes/type.php');

require('admin/lib/classes/form/fields.php');
require('admin/lib/classes/form/text.php');
require('admin/lib/classes/form/textarea.php');
require('admin/lib/classes/form/radio.php');
require('admin/lib/classes/form/checkbox.php');
require('admin/lib/classes/form/select.php');
require('admin/lib/classes/form/button.php');
require('admin/lib/classes/form/raw.php');

sql::connect('localhost', 'c1sysix', 'sysixpw', 'c1dynao');

echo type::super('title', 'string', 'defaultwert');

$form = new form('news','id=1','index.php');

$field = $form->addTextField('title', $form->get('title'), array('class'=>'text'));
$field->fieldName('News-Titel');
$field->setSuffix('<small>max. 50 Buchstaben</small>');

$field = $form->addTextareaField('text', $form->get('text'));
$field->fieldName('Infotext');

$field = $form->addTextareaField('text-de', 'Vorgefertigter Text');
$field->fieldName('Infotext-de');

$field = $form->addPasswordField('pwd', $form->get('pwd'));
$field->fieldName('Passwort');

$field = $form->addCheckboxField('personen', '|1|2|3|5|');
$field->fieldName('Personen');
foreach(range(1,8) as $val) {
	$field->add($val, 'Person #'.$val);
}

$field = $form->addRadioField('mail', 0);
$field->fieldName('Mail Verstecken?');
$field->add(1, 'Nein');
$field->add(0, 'Ja');

$field = $form->addRawField('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.');
$field->fieldName('Informationen');

$field = $form->addSelectField('land', '');
$field->fieldName('Land');
$field->add('', 'Bitte wählen Sie aus!');
$field->add('de', 'Deutschland');
$field->add('en', 'England');
$field->add('us', 'USA');

$field = $form->addSelectField('medizin', '');
$field->fieldName('Medizin');
$field->addGroup('Naturelle');
$field->add('wurzel', 'Wurzel');
$field->add('kraut', 'Heilkäruter');
$field->addGroup('Chemische');
$field->add('antibio', 'Antibiotika');
$field->add('tabletten', 'Andere Tabletten');

$form->addHiddenField('id', 1);

echo $form->show();

?>