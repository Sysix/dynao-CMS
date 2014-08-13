<?php

$id = type::super('id', 'int', 0);

if($action == 'delete') {

    $sql = sql::factory();
    $sql->setTable('lang');
    $sql->setWhere('id='.$id);
    $sql->delete();

    $action = '';
}

if($action == 'add' || $action == 'edit') {

    $form = new form('lang', 'id='.$id, 'index.php');

    $field = $form->addTextField('name', $form->get('form'));
    $field->fieldName(lang::get('name'));

    if($action == 'edit') {
        $form->addHiddenField('id', $id);
    }

    echo $form->show();

}

?>