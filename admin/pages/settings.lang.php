<?php

$id = type::super('id', 'int', 0);

if($action == 'delete') {

    $sql = sql::factory();
    $sql->setTable('lang');
    $sql->select();

    if($sql->num() == 1) {
        echo message::danger(lang::get('lang_last_not_deleted'), true);
    } else {
        $sql->setWhere('id='.$id);
        $sql->delete();

        echo message::success(lang::get('lang_successfull_deleted'), true);
    }

    $action = '';
}

if($action == 'add' || $action == 'edit') {

    $form = new form('lang', 'id='.$id, 'index.php');

    $field = $form->addTextField('name', $form->get('form'));
    $field->fieldName(lang::get('name'));

    if($action == 'edit') {
        $form->addHiddenField('id', $id);
        $title = '"'.$form->get('name').'" '.lang::get('edit');
    } else {
        $title = lang::get('add');
    }

    echo bootstrap::panel($title, [], $form->show());

}

if($action == '') {

    $table = table::factory(['class'=>['js-sort']]);
    $table->addCollsLayout('20,*,110');

    $table->addRow()
        ->addCell()
        ->addCell(lang::get('name'))
        ->addCell(lang::get('action'));

    $table->addSection('tbody');

    $table->setSql('SELECT * FROM '.sql::table('lang').' ORDER BY `sort`');

    while($table->isNext()) {

        $id = $table->get('id');

        $edit = '<a href="'.url::backend('settings', ['subpage'=>'lang', 'action'=>'edit', 'id'=>$id]).'" class="btn btn-sm btn-default fa fa-pencil-square-o"></a>';
        $delete = '<a href="'.url::backend('settings', ['subpage'=>'lang','action'=>'delete', 'id'=>$id]).'" class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';

        $table->addRow(['data-id'=>$id])
            ->addCell('<i class="fa fa-sort"></i>')
            ->addCell($table->get('name'))
            ->addCell('<span class="btn-group">'.$edit.$delete.'</span>');

        $table->next();
    }

    $button = [];

    if(dyn::get('user')->hasPerm('admin[lang]')) {
        $button = ['<a class="btn btn-sm btn-warning" href="'.url::backend('settings', ['subpage'=>'lang', 'action'=>'add']).'">'.lang::get('add').'</a>'];
    }

    echo '<div class="row">'.bootstrap::panel(lang::get('languages'), $button, $table->show(), ['table'=>true]).'</div>';

}

?>