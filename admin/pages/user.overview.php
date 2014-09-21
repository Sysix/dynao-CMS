<?php
if($action == 'delete') {
		
		if(dyn::get('user')->get('id') == $id)
			echo message::danger(lang::get('user_self_deleted'));	
		else {
			$sql = sql::factory();
			$sql->setTable('user');
			$sql->setWhere('id='.$id);
			$sql->delete();
			
			echo message::success(lang::get('user_deleted'));
		}
			
		$action = '';
}

if($action == 'add' || $action == 'edit') {
	
	layout::addJsCode("
	var page_admin_button = $('#pageadmin-button');
	var page_admin_content = $('#pageadmin-content');
	
	page_admin_button.change(function() {
			if(page_admin_button.is(':checked')) {
				page_admin_content.stop().slideUp(300);
			} else {
				page_admin_content.stop().slideDown(300);
			}
	});");

	$form = form::factory('user','id='.$id,'index.php');
	
	$field = $form->addTextField('firstname', $form->get('firstname'));
	$field->fieldName(lang::get('firstname'));
	$field->autofocus();
	
	$field = $form->addTextField('name', $form->get('name'));
	$field->fieldName(lang::get('name'));
	
	$field = $form->addTextField('email', $form->get('email'));
	$field->fieldName(lang::get('email_adress'));
	$field->addValidator('notEmpty', lang::get('user_email_empty'));
	$field->addValidator('email', lang::get('user_wrong_email'));
	
	if($form->get('password') != $form->sql->getValue('password')) {
		$password = userLogin::hash($form->get('password'), $form->get('salt'));
	} else {
		$password = $form->sql->getValue('password');
	}
	
	$field = $form->addTextField('password', $password);
	$field->addValidator('notEmpty', lang::get('user_password_empty'));
	$field->fieldName(lang::get('password'));
	$field->setSuffix(lang::get('password_info'));
	
	if(dyn::get('user')->isAdmin()) {

		$field = $form->addCheckboxField('admin', $form->get('admin'));
		$field->add('1', 'PageAdmin?', ['id'=>'pageadmin-button']);
		
		$field = $form->addSelectField('perms', $form->get('perms'));
		
		if($form->get('admin') == 1)
			$field->addAttribute('style', 'display:none;');
		
		$field->setMultiple(true);
		$field->setId('pageadmin-content');
		$field->setSize(8);
		foreach(userPerm::getAll() as $name=>$value) {
			$field->add($name, $value);	
		}
	
	}
	
	if($action == 'edit') {

		$form->addHiddenField('id', $id);
        $title = '"'.$form->get('firstname').' '.$form->get('name').'" '.lang::get('edit');

	} else {

        $title = lang::get('add');

    }
	
	if($form->isSubmit()) {
		
		if($form->get('password') != $form->sql->getValue('password')) {
			$form->addPost('password', userLogin::hash($form->get('password'), $form->sql->getValue('salt')));
		}
		
	}

    $button = '<a href="'.url::backend('user', ['subpage'=>'overview']).'" class="btn btn-sm btn-default">'.lang::get('back').'</a>';
	
	?>
	<div class="row"><?= bootstrap::panel($title, [$button], $form->show()); ?></div>
    <?php
	
}

if($action == '') {

	$table = table::factory();
	
	$table->addCollsLayout('*, 250,110');
	
	$table->addRow()
	->addCell("Name")
	->addCell(lang::get('email'))
	->addCell(lang::get('action'));
	
	$table->addSection('tbody');	
	
	$table->setSql('SELECT * FROM '.sql::table('user'));
	while($table->isNext()) {
			
		$id = $table->get('id');
			
		$edit = '<a href="'.url::backend('user', ['subpage'=>'overview', 'action'=>'edit', 'id'=>$id]).'" class="btn btn-sm  btn-default fa fa-pencil-square-o"></a>';
		$delete = (dyn::get('user')->get('id') == $id) ? '' : '<a href="'.url::backend('user', ['subpage'=>'overview', 'action'=>'delete', 'id'=>$id]).'" class="btn btn-sm btn-danger fa fa-trash-o delete"></a>';
		
		$table->addRow()
		->addCell($table->get('firstname')." ".$table->get('name'))
		->addCell($table->get('email'))
		->addCell('<span class="btn-group">'.$edit.$delete.'</span>');
		
		$table->next();	
		
	}

    $button = '<a href="'.url::backend('user', ['subpage'=>'overview', 'action'=>'add']).'" class="btn btn-sm btn-default">'.lang::get('add').'</a>';
	
	?>
    <div class="row"><?= bootstrap::panel(lang::get('user'), [$button], $table->show(), ['table' => true]) ?></div>
    <?php
	
}

?>